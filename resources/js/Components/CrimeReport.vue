<template>
  <div class="max-w-4xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-semibold mb-6">Crime Report Form</h1>
    <p class="px-4 py-6" >This BODC-DEI Dashboard right now has a form that allows you to get a crime report for a location in Boston. The report is generated using a Google Cloud Function that uses two data sets on data.boston.gov, one for crime reports, and one to get the coordinates for street addresses, and it then sends that data to chatGPT for summary and translation. The function is written in Python and uses the Google Cloud Platform to run the function. The function is called from the form on the dashboard and the report is displayed on the page.</p>

    <p class="mb-4 px-4 py-6">Fill out the form below to generate a crime report for a specific location. Spelling for the street name and street suffix must be correct. Capitalization does not matter</p>
    <form @submit.prevent="fetchCrimeReport" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
      
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="street_number">
          Street Number
        </label>
        <input v-model="form.street_number" id="street_number" type="text" placeholder="123" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="street_prefix">
          Street Prefix
        </label>
        <select v-model="form.street_prefix" id="street_prefix" class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
          <option value="">Nothing (blank)</option>
          <option value="N">North (N)</option>
          <option value="S">South (S)</option>
          <option value="E">East (E)</option>
          <option value="W">West (W)</option>
        </select>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="street_name">
          Street Name
        </label>
        <input v-model="form.street_name" id="street_name" type="text" placeholder="Main" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="street_suffix">
          Street Suffix
        </label>
        <select v-model="form.street_suffix" id="street_suffix" class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
          <option value="St">Street (St)</option>
          <option value="Ave">Avenue (Ave)</option>
          <option value="Rd">Road (Rd)</option>
          <option value="Blvd">Boulevard (Blvd)</option>
          <option value="Dr">Drive (Dr)</option>
          <option value="Ln">Lane (Ln)</option>
          <option value="Ter">Terrace (Ter)</option>
          <option value="Pl">Place (Pl)</option>
          <option value="Ct">Court (Ct)</option>
          <option value="Sq">Square (Sq)</option>
          <option value="Pkwy">Parkway (Pkwy)</option>
          <option value="Cir">Circle (Cir)</option>
          <option value="">Nothing (blank)</option>
        </select>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="radius">
          Radius in Miles
        </label>
        <input v-model="form.radius" id="radius" placeholder="0.5" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="language">
          Language
        </label>
        <select v-model="form.language" id="language" class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
          <option value="English">English</option>
          <option value="Spanish">Spanish</option>
          <option value="French">French</option>
          <!-- Additional language options: portuguese, chinese, vietnamese, haitain kreyol/creole etc-->
          <option value="Portuguese">Portuguese</option>
          <option value="Chinese">Chinese</option>
          <option value="Vietnamese">Vietnamese</option>
          <option value="Haitian Kreyol">Haitian Kreyol</option>
          <option value="Arabic">Arabic</option>
        </select>
      </div>
      <div class="flex items-center justify-between">
        <button :disabled="loading" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
          <span v-if="loading">
            <i class="fas fa-spinner fa-spin"></i> Loading...
          </span>
          <span v-else>
            Get Crime Report
          </span>
        </button>
      </div>
      <p class="text-sm mt-4 text-gray-600">
        Please be patient, generating the report may take up to a minute.
      </p>
    </form>
    <div v-if="report" class="bg-gray-100 p-6 rounded shadow-lg">
      <h2 class="text-xl font-semibold mb-2">Analysis</h2>
      <p>{{ report.analysis }}</p>
      <h2 class="text-xl font-semibold mt-4 mb-2">Detailed Report</h2>
      <pre class="whitespace-pre-wrap text-sm">{{ report.report }}</pre>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      form: {
        street_number: '',
        street_name: '',
        street_suffix: '',
        radius: 0.5,
        language: 'English'
      },
      report: null,
      loading: false
    };
  },
  methods: {
    fetchCrimeReport() {
      this.loading = true; // Set loading to true when the request starts
      axios.get('/api/crime-reports', { params: this.form })
          .then(response => {
              this.report = response.data;
              this.loading = false; // Set loading to false when the data is received
          })
          .catch(error => {
              console.error('Error fetching crime report:', error);
              alert('Failed to fetch crime report.');
              this.loading = false; // Set loading to false even on error
          });
    }
  }
};
</script>

<style scoped>
/* make the button bounce when disabled */
@keyframes bounce {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-5px);
  }
}

/* Styling for the spinner icon */
i.fas.fa-spinner {
  animation: spin 2s linear infinite;
}

/* Styling for the submit button when loading */
button[disabled] {
  animation: bounce 0.5s infinite;
  background-color: #999; /* Lighter shade to indicate loading */
  cursor: not-allowed;
}
</style>
