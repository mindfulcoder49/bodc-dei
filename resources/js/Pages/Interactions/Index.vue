<script setup>
import { ref, reactive } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Interaction from '@/Components/Interaction.vue';

const { interactions, currentInteraction, templates, models } = defineProps({
  interactions: Array,
  currentInteraction: Object,
  templates: Array,
  models: Array
});

const form = useForm({
  interactions: [],
  currentInteraction: null,
  errors: {},
    templateChoice: 'default',
    templateName: '',
    model: 'gpt-3.5-turbo',
    temperature: 0.5,
    maxTokens: 1024,
    prompt: '',

});

const fields = reactive([{
  label: 'Introduction',
  content: '',
  placeholder: 'Enter the introduction...'
}]);

function addField() {
  fields.push({
    label: `Field ${fields.length + 1} Content`,
    content: '',
    placeholder: `Enter field ${fields.length + 1} content...`
  });
}

function removeField(index) {
  fields.splice(index, 1);
}

function handleSubmit() {
  //const combinedPrompt = fields.map(f => f.content).join(' '); // Combine all field contents
  const combinedPrompt = fields.map(f => `${f.placeholder} ${f.label}: ${f.content}`).join('\n'); // Combine all field contents
  form.prompt = combinedPrompt,
  form.post(route('interactions.store'), {
    onSuccess: () => {
      fields.forEach(field => field.content = '');
      console.log('Submission successful!');
    },
    onError: (errors) => {
      form.errors = errors;
      console.error(errors);
    }
  });
}

function logInteraction() {
  console.log('Interaction logged:', fields);
}

function estimateCosts() {
  console.log('Cost estimation:', fields);
}

function clearFields() {
  fields.forEach(field => field.content = '');
  console.log('Fields cleared');
}

function saveTemplate() {
  const templateData = {
    fields: fields,
    settings: {
      model: form.model,
      temperature: form.temperature,
      maxTokens: form.maxTokens
    }
  };
  const templateName = form.templateName;
  axios.post(route('templates.store'), { template: templateData, name: templateName })
    .then(response => {
      console.log('Template saved');
      const newTemplate = {
        name: templateName,
        ...response.data.template
      };
      templates.push(newTemplate);  // This should automatically update your component
      form.templateChoice = newTemplate.name;
    })
    .catch(error => console.error('Error saving template', error));
}



function loadTemplate(templateName) {
    axios.get(`/templates/${templateName}`)
         .then(response => {
             // Adjust the reference to match the nested structure
             const template = response.data.template.template; // Access the nested template object
             if (!template.fields) {
                 console.error('Template fields are missing');
                 return;
             }
             fields.splice(0, fields.length, ...template.fields); // Reset and repopulate fields
             form.model = template.settings.model;
             form.temperature = template.settings.temperature;
             form.maxTokens = template.settings.maxTokens;
             form.templateName = template.name; // Update the form to reflect the loaded template name
         })
         .catch(error => console.error('Error loading template', error));
}


function deleteTemplate(templateName) {
    axios.delete(`/templates/${templateName}`)
         .then(() => {
             console.log('Template deleted');
             // Optionally, remove the deleted template from the local list
             const index = templates.findIndex(t => t.name === templateName);
             if (index !== -1) {
                 templates.splice(index, 1);
             }
         })
         .catch(error => console.error('Error deleting template', error));
}



</script>

<template>
  <AuthenticatedLayout>
    <Head title="AI Template Tracker" />


    <div class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8">
      <form @submit.prevent="handleSubmit">
        <div>
        <label for="template">Select Template</label>
        <select id="template" v-model="form.templateChoice">
            <option v-for="template in templates" :key="template.name" :value="template.name">{{ template.name }}</option>
        </select>
        </div>
        <div>
        <!-- Bind the selected template name for loading and deleting -->
        <button type="button" @click="saveTemplate" class="btn-primary">Save Template</button>
        <button type="button" @click="() => loadTemplate(form.templateChoice)" class="btn-primary">Load Template</button>
        <button type="button" @click="() => deleteTemplate(form.templateChoice)" class="btn-danger">Delete Template</button>
        </div>
        <div>
        <label for="templateName">Template Name</label>
        <input type="text" id="templateName" v-model="form.templateName" placeholder="Enter new template name">
        </div>


        <div>
          <label for="model">Model</label>
          <select id="model" v-model="form.model">
            <option v-for="model in models" :key="model" :value="model">{{ model }}</option>
          </select>
        </div>
        <div>
          <label for="temperature">Temperature: {{ form.temperature }} </label>
          <input type="range" id="temperature" min="0" max="1" step="0.01" v-model.number="form.temperature">
          <!-- show the temperature number -->

          <label for="maxTokens">Max Tokens: {{ form.maxTokens }} </label>
          <input type="range" id="maxTokens" min="10" max="4096" step="1" v-model.number="form.maxTokens">
        </div>
        <div v-for="(field, index) in fields" :key="index" class="mb-4">
            <!--
          <label class="font-semibold">{{ field.label }}</label>
          <textarea v-model="field.content" :placeholder="field.placeholder"
                    class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"></textarea>

                    Instead of the above, make three user editable fields in a box,field name which is the label, field intro, which is the placeholder, and field content, which is the content
        -->
        <div>
            <label for="fieldName">Field Name</label>
            <input type="text" id="fieldName" v-model="field.label">
        </div>
        <div>
            <label for="fieldIntro">Field Intro</label>
            <input type="text" id="fieldIntro" v-model="field.placeholder">
        </div>
        <div>
            <label for="fieldContent">Field Content</label>
            <textarea id="fieldContent" v-model="field.content"></textarea>
        </div>
          <button type="button" @click="removeField(index)" class="btn-danger btn-remove">Remove</button>
        </div>
        <button type="button" @click="addField" class="btn-primary">Add Field</button>
        <button type="submit" class="btn-primary">Submit</button>
        <button type="button" @click="logInteraction" class="btn-primary">Log Interaction</button>
        <button type="button" @click="estimateCosts" class="btn-info">Estimate Costs</button>
        <button type="button" @click="clearFields" class="btn-danger">Clear Fields</button>
        <InputError :message="form.errors.message" class="mt-2" />
      </form>
    </div>

    <div class="mt-6 bg-white shadow-sm rounded-lg divide-y">
      <Interaction v-for="interaction in interactions" :key="interaction.id" :interaction="interaction" />
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
.btn-primary { background-color: #007bff; color: white; padding: 0.5rem 1rem; border-radius: 0.25rem; }
.btn-info { background-color: #17a2b8; color: white; padding: 0.5rem 1rem; border-radius: 0.25rem; }
.btn-danger { 
    background-color: #dc3545; color: white; padding: 0.5rem 1rem; border-radius: 0.25rem; 
}
.btn-remove {
    width: 6rem;
}
button { margin-right: 0.5rem; margin-top: 0.5rem; }

form div {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}
form div label {
  font-weight: bold;
}
form div input[type="range"] {
  width: 100%;
}
form div textarea {
  height: 100px;
}
form div input {
  width: 100%;
}


</style>
