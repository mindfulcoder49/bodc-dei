<template>
  <PageTemplate>
    <Head>
      <title>311 Case List</title>
      <meta property="og:title" content="311 Case List" />
      <meta property="og:description" content="A comprehensive list of Boston 311 cases with detailed predictions and insights." />
      <meta property="og:image" content="https://bodc-dei.com/images/logo.jpeg" />
      <meta property="og:url" content="https://bodc-dei.com/cases" />
      <meta property="og:type" content="website" />
      <meta property="og:site_name" content="311 Case Management" />

      <!-- Twitter Card Meta Tags -->
      <meta name="twitter:card" content="summary_large_image" />
      <meta name="twitter:title" content="311 Case List" />
      <meta name="twitter:description" content="A comprehensive list of Boston 311 cases with detailed predictions and insights." />
      <meta name="twitter:image" content="https://bodc-dei.com/images/logo.jpeg" />
      <meta name="twitter:site" content="@BODC-DEI" />
    </Head>
    <div class="border p-4 rounded shadow items-center space-4 bg-gray-100 overflow-wrap">
      <Link href="/cases" class="inline-block px-6 py-3 bg-gray-500 text-gray-100 font-semibold rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 transition ease-in-out duration-150 m-2">
        Case List
      </Link>
      <Link href="/scatter" class="inline-block px-6 py-3 bg-gray-500 text-gray-100 font-semibold rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 transition ease-in-out duration-150 m-2">
        Case Map
      </Link>
      <Link href="/311models" class="inline-block px-6 py-3 bg-gray-500 text-gray-100 font-semibold rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 transition ease-in-out duration-150 m-2">
        Model Info
      </Link>
      <Link href="/311demo" class="inline-block px-6 py-3 bg-gray-500 text-gray-100 font-semibold rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 transition ease-in-out duration-150 m-2">
        Notebook Demo
      </Link>
    </div>
    <main class="bg-gray-100 p-8">
      <div class="flex flex-col items-center">
        <input
          :value="searchTerm"
          @input="updateSearchTerm"
          placeholder="Search..."
          type="text"
          class="form-input mt-1 block w-full border-none bg-white h-11 rounded-xl shadow-lg hover:bg-blue-100 focus:bg-blue-100 focus:ring-0"
        />
        <button
          @click="fetchCases"
          class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
        >
          Search
        </button>
      </div>
      <section class="text-lg font-semibold mt-6 text-center">Total number of cases: {{ numberOfCases }}</section>
      <div class="mt-6" v-if="filteredCases.length">
        <template v-for="item in filteredCases" :key='item["case_enquiry_id"]'>
          <div class="bg-white rounded-lg shadow-lg p-4 mb-6">
            <div class="flex justify-between items-center">
              <h3 class="text-lg font-bold">{{ item["case_title"] }}</h3>
              <span>Case ID: {{ item["case_enquiry_id"] }}</span>
            </div>
            <div class="mt-2">
              <div>Date: {{ item["open_dt"] }}</div>
              <div>SLA: {{ item["sla_target_dt"] }}</div>
              <div>Status: {{ item["case_status"] }}</div>
              <div v-if="item['closed_dt']">Closed date: {{ item["closed_dt"] }}</div>
            </div>
            <div v-if="item.predictions.length" class="mt-4">
              <h4 class="text-lg font-bold">Predictions</h4>
              <div class="mt-2"><strong>Model:</strong> {{ item.predictions[0].ml_model_name }}</div>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                <div v-for="prediction in item.predictions" :key="prediction.id" class="flex flex-col">
                  <div class="font-semibold">Predicted Days to Close:</div>
                  <ul>
                    <li v-if="prediction.predictionTimespan.length > 0"><strong>First:</strong> {{ convertToDaysRange(prediction.predictionTimespan[0]) }} ({{ (prediction.predictionMaxThree[0] * 100).toFixed(2) }}%)</li>
                    <li v-if="prediction.predictionTimespan.length > 1"><strong>Second:</strong> {{ convertToDaysRange(prediction.predictionTimespan[1]) }} ({{ (prediction.predictionMaxThree[1] * 100).toFixed(2) }}%)</li>
                    <li v-if="prediction.predictionTimespan.length > 2"><strong>Third:</strong> {{ convertToDaysRange(prediction.predictionTimespan[2]) }} ({{ (prediction.predictionMaxThree[2] * 100).toFixed(2) }}%)</li>
                  </ul>
                </div>
              </div>
            </div>
            <button v-if="!item.showDetails" @click="item.showDetails = true" class="text-indigo-600 hover:text-indigo-900">
              + More Details
            </button>
            <button v-else @click="item.showDetails = false" class="text-indigo-600 hover:text-indigo-900">
              - Less Details
            </button>
            <div v-if="item.showDetails" class="mt-4">
              <h4 class="text-lg font-bold">Case Details</h4>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                <div v-for="key in filteredKeys" :key="key">
                  <div class="font-semibold">{{ key }}</div>
                  <div class="overflow-auto">{{ item[key] }}</div>
                </div>
              </div>
            </div>
          </div>
        </template>
      </div>
      <div v-else class="text-center mt-6 text-xl">No cases found.</div>
    </main>
  </PageTemplate>
</template>

<script>
import PageTemplate from '../Components/PageTemplate.vue';
import { Head, Link } from '@inertiajs/vue3';

export default {
  props: {
    cases: {
      type: Array,
      required: true,
    },
    search: {
      type: String,
      required: false,
    },
  },
  components: {
    PageTemplate,
    Head,
    Link,
  },
  data() {
    return {
      searchTerm: this.search,
    };
  },
  computed: {
    filteredCases() {
      if (!this.searchTerm) return this.cases;
      const term = this.searchTerm.toLowerCase();
      return this.cases.filter((item) => {
        return Object.values(item).some((value) => String(value).toLowerCase().includes(term));
      });
    },
    numberOfCases() {
      return this.filteredCases.length;
    },
    filteredKeys() {
      // Exclude predictions and the initially displayed fields

      return Object.keys(this.filteredCases[0]).filter((key) => !['predictions', 'case_title', 'case_enquiry_id', 'open_dt', 'sla_target_dt'].includes(key));
    },
  },
  methods: {
    updateSearchTerm(event) {
      this.searchTerm = event.target.value;
    },
    fetchCases() {
      this.$inertia.get('/cases', { searchTerm: this.searchTerm });
    },
    convertToDaysRange(value) {

      const hoursPerDay = 24;
      const startDay = Math.floor(value[0] / hoursPerDay);
      const endDay = Math.floor(value[1] / hoursPerDay);

      //return `[${startDay}, ${endDay}] days`;
      return `${startDay} - ${endDay} days`;
    },
  },
};
</script>

<style scoped>
.scrollable {
  overflow-y: auto;
  height: 50px; /* replace with actual line-height */
}
</style>
