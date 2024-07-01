<script setup>
import { ref, reactive, toRefs } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Interaction from '@/Components/Interaction.vue';

const { interactions, templates, models, currentInteraction } = defineProps({
  interactions: Array,
  templates: Array,
  models: Array,
  currentInteraction: Object,
});

const state = reactive({
  currentInteraction: currentInteraction || {}  // defaulting if no prop is passed
});

const form = useForm({
  templateChoice: '',
  templateName: '',
  model: 'gpt-3.5-turbo',
  temperature: 0.5,
  maxTokens: 1024,
  prompt: '',
  errors: {},
  fields: [{
            name: 'Prompt',
            content: '',
          }]
});

function addField() {
  form.fields.push({
    name: `Field ${form.fields.length + 1}`,
    value: ''
  });
}

function removeField(index) {
  form.fields.splice(index, 1);
}

function handleSubmit() {
    //turn the fields array into json
    const fieldsObject = form.fields.map(f => ({ name: f.name, value: f.value }));

    // Include the fields object in the form data
    form.post(route('interactions.store'), {
        ...form, // Spread the existing form properties
        fields: fieldsObject, // Override the fields with the JSON object
        onSuccess: () => {
            console.log('Submission successful!');
        },
        onError: (errors) => {
            form.errors = errors;
            console.error('Error:', errors);
        }
    });
}

function loadInteraction(interaction) {
    state.currentInteraction = {...interaction};
    // Clear current fields first
    form.fields.splice(0, form.fields.length);

    if (interaction.fields && Array.isArray(interaction.fields) && interaction.fields.length > 0) {
        // Load the fields if they exist and are not empty
        form.fields.push(...interaction.fields.map(f => ({
            name: f.name || 'Field',  // Fallback name if none provided
            value: f.value
        })));
    } else if (interaction.prompt && typeof interaction.prompt === 'string') {
        // Fallback to using prompt as a single field if fields are not available
        form.fields.push({
            name: 'Prompt',
            value: interaction.prompt
        });
    } else {
        // Log an error or set up a default field if neither fields nor prompt are suitable
        console.error('No suitable data found in the interaction to load fields.');
        // Optionally add a default empty field to allow user input
        form.fields.push({
            name: 'Field 1',
            value: ''
        });
    }

    //Update the rest of the form fields
    form.model = interaction.model_name;

}


function logInteraction() {

    //turn the fields array into json
    const fieldsObject = form.fields.map(f => ({ name: f.name, value: f.value }));

    // Include the fields object in the form data
    form.post(route('interact.log'), {
        ...form, // Spread the existing form properties
        fields: fieldsObject, // Override the fields with the JSON object
    onSuccess: () => {
        console.log('Submission successful!');
    },
    onError: (errors) => {
        form.errors = errors;
        console.error('Error:', errors);
    }
});
  
}

function estimateCosts() {
  form.prompt = form.fields.map(f => `${f.name}: ${f.value}`).join('\n');
  axios.post(route('interact.estimate'), {
    prompt: form.prompt,
    maxTokens: form.maxTokens,
    model: form.model
  })
    .then(response => {
      console.log('Costs estimated:', response.data);
      form.costEstimate = response.data;
    })
    .catch(error => console.error('Error estimating costs', error));
}

function clearFields() {
  form.fields.forEach(field => field.value = '');
  console.log('Fields cleared');
}

function saveTemplate() {
  const fieldsObject = form.fields.map(f => ({ name: f.name, value: f.value }));
  const templateData = {
    fields: fieldsObject,
    settings: {
      model: form.model,
      temperature: form.temperature,
      maxTokens: form.maxTokens
    }
  };

  //if templateName is empty, create one with the time stamp
  if (form.templateName=='') {
    const timestamp = new Date().toISOString().replace(/[-:]/g, '').replace(/\..+/, '');
    form.templateName = `Template ${timestamp}`;
  }
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
             form.fields = template.fields;
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
        <div class="template-buttons">
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
        <div v-for="(field, index) in form.fields" :key="index" class="mb-4">
          <div>
              <label :for="'fieldName' + index">Field Name</label>
              <input :id="'fieldName' + index" v-model="field.name">
          </div>
          <div>
              <label :for="'fieldContent' + index">Field Content</label>
              <textarea :id="'fieldContent' + index" v-model="field.value"></textarea>
          </div>
          <div>
            <button type="button" @click="removeField(index)" class="btn-danger btn-remove">Remove</button>
          </div>
        </div>
        <button type="button" @click="addField" class="btn-primary">Add Field</button>
        <button type="submit" class="btn-primary">Submit</button>
        <button type="button" @click="logInteraction" class="btn-primary">Log Interaction</button>
        <button type="button" @click="estimateCosts" class="btn-info">Estimate Costs</button>

        <button type="button" @click="clearFields" class="btn-danger">Clear Fields</button>
        <div v-if="form.costEstimate">Input Cost: {{ form.costEstimate.prompt_cost }} Output Cost: {{ form.costEstimate.completion_cost }} Total Cost: {{ form.costEstimate.total_cost }}</div>
        <InputError :message="form.errors.message" class="mt-2" />
      </form>
      <div v-if="state.currentInteraction">
        <h2 class="mt-6 text-lg font-semibold">Completion</h2>
        <p>{{ state.currentInteraction.completion }}</p>
        <div class="flex">
        <small class="p-4 text-sm text-gray-500">Tokens: {{ state.currentInteraction.prompt_tokens + state.currentInteraction.completion_tokens }}</small>
        <!--Add fields for input and output tokens and their prices-->
        <small class="p-4 text-sm text-gray-500">Input Tokens: {{ state.currentInteraction.prompt_tokens }}</small>
        <small class="p-4 text-sm text-gray-500">Output Tokens: {{ state.currentInteraction.completion_tokens }}</small>
        <small class="p-4 text-sm text-gray-500">Input Token Price: ${{ state.currentInteraction.prompt_token_price }}</small>
        <small class="p-4 text-sm text-gray-500">Output Token Price: ${{ state.currentInteraction.completion_token_price }}</small>
        <small class="p-4 text-sm text-gray-500">Cost: ${{ state.currentInteraction.total_cost }}</small>
      </div>

        </div>
    </div>

    <div class="mt-6 bg-white shadow-sm rounded-lg divide-y">
      <Interaction v-for="interaction in interactions" :key="interaction.id" :interaction="interaction" @loadInteraction="loadInteraction" />

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
  margin-bottom: 1rem;
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

form div.template-buttons {
  display: flex;
  flex-direction: row;
  gap: 1rem;
}


</style>
