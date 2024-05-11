<template>
    <div class="max-w-4xl mx-auto px-4 py-6">
      <h1 class="text-2xl font-semibold mb-6">GitHub Analysis</h1>
      <p class="text-gray-700 text-lg mb-4">
        This form allows you to analyze changes within a GitHub repository over a specified period. Select a repository from the dropdown menu, then enter an interval in hours to define how often changes should be checked, and specify the number of iterations to determine how many past intervals should be analyzed. This will help you track development progress, identify frequent updates, and understand the repository's modification history.
        </p>
        <p class="text-gray-700 text-lg mb-4"> The two repositories available for analysis are opendevin and devika. They are both open source AI agent tools. You can find them on github here:
            <ul class="list-disc ml-8">
                <li><a href="https://github.com/OpenDevin/OpenDevin" target="_blank">OpenDevin</a></li>
                <li><a href="https://github.com/stitionai/devika" target="_blank">Devika</a></li>
            </ul>
        </p>

      <form @submit.prevent="fetchGithubAnalysis" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="repo">Repository</label>
          <select v-model="form.repo" id="repo" class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
            <option value="opendevin">opendevin</option>
            <option value="devika">devika</option>
          </select>
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="interval">Interval (hours)</label>
          <input v-model="form.interval" id="interval" type="number" placeholder="500" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="iterations">Iterations</label>
          <input v-model="form.iterations" id="iterations" type="number" placeholder="2" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <div class="flex items-center justify-between">
          <button :disabled="loading" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
            <span v-if="loading">
              <i class="fas fa-spinner fa-spin"></i> Loading...
            </span>
            <span v-else>
              Analyze
            </span>
          </button>
          
        </div>
        <p class="text-sm mt-4 text-gray-600">
            Please be patient, generating the report may take a moment.
        </p>
      </form>
      <div v-if="analysis && analysis.length" class="bg-gray-100 p-6 rounded shadow-lg">
        <h2 class="text-xl font-semibold mb-2">Analysis</h2>
        <div v-for="(entry, index) in analysis" :key="index">
            <h3 class="text-lg font-semibold mb-2">Iteration {{ index + 1 }}</h3>
          <pre class="whitespace-pre-wrap mb-4">{{ entry }}</pre>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        form: {
          repo: 'opendevin',
          interval: 500,
          iterations: 2
        },
        analysis: null,
        loading: false
      };
    },
    methods: {
      fetchGithubAnalysis() {
        this.loading = true;
        axios.get('/api/github-analysis', { params: this.form })
          .then(response => {
              this.analysis = response.data;
              this.loading = false;
          })
          .catch(error => {
              console.error('Error fetching GitHub analysis:', error);
              alert('Failed to fetch GitHub analysis.');
              this.loading = false;
          });
      }
    }
  };
  </script>
  
  <style>
  /* Styles here */
  button[disabled] {
    background-color: #999;
    cursor: not-allowed;
  }
  </style>
  