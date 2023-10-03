
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
      <section>
          <h2 class="text-xl text-black font-extrabold">
            Model: <br /> {{ parseModelName(key) }}
          </h2>
        <div class="text-black text-opacity-80 m-10">
          Considering closed cases only:
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 p-4 bg-gray-100 rounded-lg shadow-md">
  <div class="font-bold text-gray-700 text-lg md:text-xl">Top Predictions</div>
  <div class="font-bold text-gray-700 text-lg md:text-xl">Performance Metrics</div>

  <div class="text-gray-600 text-sm md:text-base flex items-center">
    <span class="w-32">Most Likely Accuracy:</span>
    <span class="ml-auto text-green-600">{{ value.firstaccuracy.toLocaleString(undefined,{style: 'percent', minimumFractionDigits:2}) }}</span>
  </div>
  <div class="text-gray-600 text-sm md:text-base flex items-center">
    <span class="w-32">Most Likely Cases:</span>
    <span class="ml-auto">{{ value.firstcorrect }}</span>
  </div>

  <div class="text-gray-600 text-sm md:text-base flex items-center">
    <span class="w-32">Next Likely Accuracy:</span>
    <span class="ml-auto text-yellow-600">{{ value.secondaccuracy.toLocaleString(undefined,{style: 'percent', minimumFractionDigits:2}) }}</span>
  </div>
  <div class="text-gray-600 text-sm md:text-base flex items-center">
    <span class="w-32">Next Likely Cases:</span>
    <span class="ml-auto">{{ value.secondcorrect }}</span>
  </div>

  <div class="font-bold text-gray-700 text-lg md:text-xl">Composite Results</div>
  <div class="font-bold text-gray-700 text-lg md:text-xl">Aggregate Metrics</div>

  <div class="text-gray-600 text-sm md:text-base flex items-center">
    <span class="w-32">Combined Accuracy:</span>
    <span class="ml-auto text-blue-600">{{ (value.secondaccuracy+value.firstaccuracy).toLocaleString(undefined,{style: 'percent', minimumFractionDigits:2}) }}</span>
  </div>
  <div class="text-gray-600 text-sm md:text-base flex items-center">
    <span class="w-32">Total Correct Predictions:</span>
    <span class="ml-auto">{{ value.secondcorrect + value.firstcorrect }}</span>
  </div>

  <div class="col-span-2 font-bold text-gray-700 text-lg md:text-xl">Case Statistics</div>

  <div class="col-span-2 text-gray-600 text-sm md:text-base flex items-center">
    <span class="w-32">Total Cases Analyzed:</span>
    <span class="ml-auto">{{ value.total }}</span>
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
            return `${year}-${month}-${day} ${hour}:${minute}:${second} ${name}`;
        },
    }
  };
  </script>
  