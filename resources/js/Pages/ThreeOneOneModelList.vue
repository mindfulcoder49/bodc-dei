
<template>
    <PageTemplate>
      <Head>
        <title>311 Model Tracker</title>
      </Head>
  
      <div class="container mx-auto p-4">
    <div 
      v-for="(value, key) in modelAccuracy" 
      :key="key" 
      class="bg-gradient-to-r from-gray-100 via-gray-200 to-gray-100 p-6 rounded-lg mb-4 "
    >
      <section class="">
          <h2 class="text-xl text-black font-extrabold">
            Model: <br /> {{ parseModelName(key) }}
          </h2>
        <div class="px-4 py-2 bg-gray-100 rounded-lg shadow-md">
  <div class="font-bold text-xl mb-2">Model Predictions</div>
  
  <div class="flex flex-col md:flex-col">
    <!-- Most Likely Prediction -->
    <div class="flex flex-col w-full md:w-full mb-4 md:mb-4">
      <span class="text-gray-700 font-semibold text-lg">Most Likely</span>
      <div class="text-md text-gray-600 flex justify-between">
        <span>Accuracy:</span>
        <span class="text-green-600">{{ value.firstaccuracy.toLocaleString(undefined,{style: 'percent', minimumFractionDigits:2}) }}</span>
      </div>
      <div class="text-md text-gray-600 flex justify-between">
        <span>Correct Cases:</span>
        <span>{{ value.firstcorrect }}</span>
      </div>
    </div>
    
    <!-- Next Likely Prediction -->
    <div class="flex flex-col w-full md:w-auto">
      <span class="text-gray-700 font-semibold text-lg">Next Likely</span>
      <div class="text-md text-gray-600 flex justify-between">
        <span>Accuracy:</span>
        <span class="text-yellow-600">{{ value.secondaccuracy.toLocaleString(undefined,{style: 'percent', minimumFractionDigits:2}) }}</span>
      </div>
      <div class="text-md text-gray-600 flex justify-between">
        <span>Correct Cases:</span>
        <span>{{ value.secondcorrect }}</span>
      </div>
    </div>
  </div>
  
  <!-- Aggregate Metrics -->
  <div class="flex flex-col mt-4">
    <span class="text-gray-700 font-semibold text-lg mb-2">Aggregate Metrics</span>
    <div class="text-md text-gray-600 flex justify-between">
      <span>Combined Accuracy:</span>
      <span class="text-blue-600">{{ (value.secondaccuracy + value.firstaccuracy).toLocaleString(undefined,{style: 'percent', minimumFractionDigits:2}) }}</span>
    </div>
    <div class="text-md text-gray-600 flex justify-between">
      <span>Total Correct:</span>
      <span>{{ value.secondcorrect + value.firstcorrect }}</span>
    </div>
    <div class="text-md text-gray-600 flex justify-between">
      <span>Total Cases:</span>
      <span>{{ value.total }}</span>
    </div>
  </div>
</div>

      </section>
    </div>
  </div>

    </PageTemplate>
  </template>
  
  <script>
  import PageTemplate from '../Components/PageTemplate.vue';
  import { Head, Link } from '@inertiajs/vue3';
  
  export default {
    props: {
      modelAccuracy: {
        type: Object,
        required: true,
      },
    },
    components: {
      PageTemplate, Head, Link
    },
    data() {
      return {
      };
    },
    computed: {
    
    },
    methods: {
        parseModelName(modelName) {
            //split on underscore, first is yyyymmdd, second is hhmmss
            const date = modelName.split('_')[0];
            const year = date.slice(0,4);
            const month = date.slice(4,6);
            const day = date.slice(6,8);
            const time = modelName.split('_')[1];
            const hour = time.slice(0,2);
            const minute = time.slice(2,4);
            const second = time.slice(4,6);
            const name = modelName.split('_')[2];
            //add spaces between letters and numbers
            const namespace = name.replace(/([0-9])([a-z])/ig, '$1 $2');
            return `${year}-${month}-${day} ${hour}:${minute}:${second} ${namespace}`;
        },
    }
  };
  </script>
  