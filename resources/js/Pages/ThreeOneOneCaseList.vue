


<template>
  <PageTemplate>
    <Head>
      <title>311 Case List</title>
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
                    <h3 class="text-lg font-bold">Case ID: {{ item["case_enquiry_id"] }}</h3>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                    <div v-for="key in filteredKeys" :key="key">
                        <div class="font-semibold">{{ key }}</div>
                        <div class="overflow-auto">{{ item[key] }}</div>
                    </div>
                </div>

                <button v-if="!item.predictions.showPredictions" @click="item.predictions.showPredictions = !item.predictions.showPredictions" class="text-indigo-600 hover:text-indigo-900">+</button>
                    <button v-if="item.predictions.showPredictions" @click="item.predictions.showPredictions = !item.predictions.showPredictions" class="text-indigo-600 hover:text-indigo-900">-</button>
                <div v-if="item.predictions.showPredictions" class="mt-4">
                    <h4 class="text-lg font-bold">Predictions</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                        <div v-for="prediction in item.predictions" :key="prediction.id" class="flex flex-col">
                            <div v-for="(predValue, predKey) in prediction" :key="predKey">
                                <div class="font-semibold">{{ predKey }}</div>
                                <div>{{ displayValue(predKey, predValue) }}</div>
                            </div>
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
import ThreeOneOneCaseScatter from '../Components/ThreeOneOneCaseScatter.vue'; 
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
    PageTemplate, Head, Link, ThreeOneOneCaseScatter
  },
  data() {
    return {
      searchTerm: this.search
    };
  },
  computed: {
    filteredCases() {
      if (!this.searchTerm) return this.cases;
      const term = this.searchTerm.toLowerCase();

      return this.cases.filter(item => {
        return Object.values(item).some(value => 
          String(value).toLowerCase().includes(term)
        );
      });
    },
    numberOfCases() {
      return this.filteredCases.length;
    },
    filteredKeys() {
      return Object.keys(this.filteredCases[0]).filter(key => key !== 'predictions');
    },
    filteredPredKeys() {
      //return Object.keys(this.filteredCases[0].predictions[0]);
      return ['id', 'three_one_one_case_id', 'case_enquiry_id', 'ml_model_id', 'ml_model_name', 'prediction', 'prediction_date', 'predictionTimespan', 'predictionMaxThree'];
    }

  },
  methods: {
    updateSearchTerm(event) {
        this.searchTerm = event.target.value;
    },
    fetchCases() {
        this.$inertia.get('/cases', { searchTerm: this.searchTerm });
    },
    displayValue(predKey, predValue) {
      if (predKey === 'predictionTimespan') {
        return `First:${predValue[0]} Second:${predValue[1]} Third:${predValue[2]}`;
      } else if (predKey === 'predictionMaxThree') {
        return `First:${predValue[0]} Second:${predValue[1]} Third:${predValue[2]}`;
      } else if (predKey === 'prediction') {
        return "Predicted hours to close"
      } else {
      return predValue;
      }
    }
  }
};
</script>
<style scoped>
.scrollable {
  overflow-y: auto;
  height: 50px; /* replace with actual line-height */
}
</style>