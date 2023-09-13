<template>
    <v-chart class="scatter-chart" :option="chartOption" autoresize />
  </template>
  
  <script setup>
  import { use } from 'echarts/core';
  import { CanvasRenderer } from 'echarts/renderers';
  import { ScatterChart } from 'echarts/charts';
  import VChart from 'vue-echarts';
  import { ref } from 'vue';
  
  use([CanvasRenderer, ScatterChart]);
  
  const props = defineProps({
    cases: {
      type: Array,
      required: true,
    },
  });
  
  const chartOption = ref({
    xAxis: {
    name: 'Longitude',
    scale: true,  // Auto scale based on data
    min: 'dataMin',  // Optional: set minimum value based on data
    max: 'dataMax',  // Optional: set maximum value based on data
    axisLabel: {
      fontSize: 12,
      color: '#333'
    }
  },
  yAxis: {
    name: 'Latitude',
    scale: true,  // Auto scale based on data
    min: 'dataMin',  // Optional: set minimum value based on data
    max: 'dataMax',  // Optional: set maximum value based on data
    axisLabel: {
      fontSize: 12,
      color: '#333'
    }
  },
    series: [
      {
        type: 'scatter',
        data: props.cases.map(item => [item.longitude, item.latitude]),
        symbol: 'round',
        itemStyle: {
          color: 'green'  // Change to your desired color
        },
        symbolSize: 5,
      },
    ],
  });
  </script>
  
  <style scoped>
  .scatter-chart {
    /* make css height the same as page width */


    height: 100vw;
    width: 100vw;
  }
  </style>
  