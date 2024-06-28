
  
  <script setup>
  import { ref, reactive } from 'vue';
  import { useForm, Head } from '@inertiajs/vue3';
  import InputError from '@/Components/InputError.vue';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import Interaction from '@/Components/Interaction.vue';

  defineProps(['interactions','currentInteraction']);
  
  const form = useForm({
    interactions: [],
    currentInteraction: null,
    errors: {},
  });
  
  const fields = reactive([
    { label: 'Introduction', content: '', placeholder: 'Enter the introduction...' },
    { label: 'Field 1 Intro', content: '', placeholder: 'Enter the first field introduction...' },
    { label: 'Field 1 Name', content: '', placeholder: 'Enter the first field name...' },
    { label: 'Field 1 Content', content: '', placeholder: 'Enter the first field content...' },
    { label: 'Field 2 Intro', content: '', placeholder: 'Enter the second field introduction...' },
    { label: 'Field 2 Name', content: '', placeholder: 'Enter the second field name...' },
    { label: 'Field 2 Content', content: '', placeholder: 'Enter the second field content...' },
    { label: 'Field 3 Intro', content: '', placeholder: 'Enter the third field introduction...' },
    { label: 'Field 3 Name', content: '', placeholder: 'Enter the third field name...' },
    { label: 'Field 3 Content', content: '', placeholder: 'Enter the third field content...' },
    { label: 'Field 4 Intro', content: '', placeholder: 'Enter the fourth field introduction...' },
    { label: 'Field 4 Name', content: '', placeholder: 'Enter the fourth field name...' },
    { label: 'Field 4 Content', content: '', placeholder: 'Enter the fourth field content...' },
    { label: 'Conclusion', content: '', placeholder: 'Enter the conclusion...' }
  ]);
  
  
  function handleSubmit() {
    form.post(route('interactions.store', {
      fields: fields.map(field => ({ name: field.name, content: field.content })),
      onSuccess: () => {
        //fields.forEach(field => field.content = '');
        console.log('Submission successful!');
      },
      onError: (errors) => {
        form.errors = errors;
        console.error(errors);
      }
    }));
  }
  
  function logInteraction() {
    console.log('Interaction logged:', fields);
    // Implement logging logic here
  }
  
  function estimateCosts() {
    console.log('Cost estimation:', fields);
    // Implement cost estimation logic here
  }
  
  function clearFields() {
    fields.forEach(field => field.content = '');
    console.log('Fields cleared');
  }
  </script>

<template>
    <AuthenticatedLayout>
      <Head title="AI Template Tracker" />
  
      <div class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8">
        <form @submit.prevent="handleSubmit">
            <div class="flex space-x-4 mt-4">
            <PrimaryButton>Submit</PrimaryButton>
            <button type="button" @click="logInteraction" class="btn-primary">Log Interaction</button>
            <button type="button" @click="estimateCosts" class="btn-info">Estimate Costs</button>
            <button type="button" @click="clearFields" class="btn-danger">Clear Fields</button>
          </div>
          <InputError :message="form.errors.message" class="mt-2" />
        <div v-if="currentInteraction" class="mt-4">
                <p class="text-lg font-semibold">{{ currentInteraction.prompt }}</p>
                <p class="text-gray-600">{{ currentInteraction.completion }}</p>
                <small class="p-4 text-sm text-gray-500">Tokens: {{ currentInteraction.prompt_tokens + currentInteraction.completion_tokens }}</small>
                <small class="p-4 text-sm text-gray-500">Cost: ${{ currentInteraction.total_cost }}</small>
        </div>
          <div v-for="(field, index) in fields" :key="index" class="mb-4">
            <label class="font-semibold">{{ field.label }}</label>
            <textarea v-model="field.content"
                      :placeholder="field.placeholder"
                      class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"></textarea>
          </div>
          

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
  .btn-danger { background-color: #dc3545; color: white; padding: 0.5rem 1rem; border-radius: 0.25rem; }
  </style>
  