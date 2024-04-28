<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PageTemplate from '../Components/PageTemplate.vue';

const apiUrl = 'https://us-central1-llama-3-attempt-2.cloudfunctions.net/llama-3-chat';
const defaultPrompts = [
  { text: "Write a poem about the ocean", value: "Write a poem about the ocean" },
  { text: "Summarize the main points of the theory of relativity", value: "Summarize the main points of the theory of relativity" },
  { text: "Tell me a joke", value: "Tell me a joke" },
];
let selectedPrompt = null;
let customPrompt = '';

const submitPrompt = () => {
  const formData = new FormData();
  if (customPrompt !== '') {
    formData.append('user_input', customPrompt);
  } else if (selectedPrompt) {
    formData.append('user_input', selectedPrompt);
  } else {
    alert('Please select or enter a prompt.');
    return;
  }
  window.open(`${apiUrl}?${new URLSearchParams(formData)}`, '_blank');
};
</script>

<template>
  <Head title="Dashboard" />
  <PageTemplate>
    <div class="pageTemplate p-6">
      <main>
        <section>
          <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
            Welcome to your BODC-DEI Dashboard
          </h2>
        </section>
        <section>
          <h3 class="text-2xl font-semibold mb-4">Llama 3 Prompts</h3>
          <form @submit.prevent="submitPrompt">
            <div class="flex flex-wrap gap-4 mb-4">
              <button 
                v-for="prompt in defaultPrompts" 
                :key="prompt.value" 
                :class="{ 'bg-blue-500 text-white': selectedPrompt === prompt.value, 'bg-gray-200 hover:bg-gray-300': selectedPrompt !== prompt.value }"
                class="px-4 py-2 rounded border font-medium"
                @click="selectedPrompt = prompt.value"
              >
                {{ prompt.text }}
              </button>
            </div>
            <div v-if="!selectedPrompt" class="mb-4">
              <label for="custom-prompt" class="block text-lg font-medium mb-2">
                Or enter your own:
              </label>
              <textarea 
                id="custom-prompt" 
                v-model="customPrompt" 
                rows="4" 
                class="w-full rounded border px-3 py-2 focus:ring focus:ring-blue-500 focus:border-blue-500"
              ></textarea>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
              Submit Prompt
            </button>
          </form>
        </section>
      </main>
    </div>
  </PageTemplate>
</template>