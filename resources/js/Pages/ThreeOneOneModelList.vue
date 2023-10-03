
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
        <div class="relative items-center ">
          <div class="text-lg m-10"><span class="font-medium text-black">Accuracy:</span> <span class="font-light absolute right-20">{{ value.accuracy.toLocaleString(undefined,{style: 'percent', minimumFractionDigits:2}) }} </span></div>
          <div class="text-lg m-10"><span class="font-medium text-black">Correct:</span> <span class="font-light absolute right-20">{{ value.correct }}</span></div>
          <div class="text-lg m-10"><span class="font-medium text-black">Total:</span> <span class="font-light absolute right-20">{{ value.total }}</span></div>
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
  