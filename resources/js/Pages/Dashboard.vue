<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PageTemplate from '../Components/PageTemplate.vue';
import CrimeReport from '../Components/CrimeReport.vue';
import GithubAnalysis from '../Components/GithubAnalysis.vue';

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
          <!-- <CrimeReport /> -->
  </PageTemplate>
</template>