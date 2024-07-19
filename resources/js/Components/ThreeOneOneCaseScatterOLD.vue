<template>
    <v-chart class="scatter-chart" :option="chartOption" autoresize />
  </template>
  
  <script setup>
  import { use } from 'echarts/core';
  import { CanvasRenderer } from 'echarts/renderers';
  import { ScatterChart } from 'echarts/charts';
  import { LegendComponent } from 'echarts/components';
  import VChart from 'vue-echarts';
  import { ref, computed, defineProps } from 'vue';
  
  use([CanvasRenderer, ScatterChart, LegendComponent]);
  
  const props = defineProps({
    cases: {
      type: Array,
      required: true,
    },
  });

  const closedCases = computed(() => 
    props.cases.filter(item => item.case_status === 'Closed').map(item => [item.longitude, item.latitude])
  );

  const openCases = computed(() => 
    props.cases.filter(item => item.case_status === 'Open').map(item => [item.longitude, item.latitude])
  );
  
  const chartOption = computed(() => ({
    legend: {
    // Try 'horizontal'
    orient: 'vertical',
    right: 10,
    top: 'center'
  },
    xAxis: {
    name: 'Longitude',
    scale: true,  // Auto scale based on data
    min: -71.2,  // Adjusted westernmost point
    max: -70.98,  // Adjusted easternmost point
    axisLabel: {
      fontSize: 16,
      color: '#333'
    }
  },
  yAxis: {
    name: 'Latitude',
    scale: true,  // Auto scale based on data
    min: 42.22,  // Adjusted southernmost point
    max: 42.4,  // Adjusted northernmost point
    axisLabel: {
      fontSize: 16,
      color: '#333'
    }
  },
  series: [
      {
        name: 'Closed',
        type: 'scatter',
        data: closedCases.value,
        symbol: 'circle',
        itemStyle: {
          color: '#0D6986'
        },
        symbolSize: 5,
      },
      {
        name: 'Open',
        type: 'scatter',
        data: openCases.value,
        symbol: 'circle',
        itemStyle: {
          color: '#DB073D'
        },
        symbolSize: 5,
      }
    ],
  }));

  </script>
  
  <style scoped>
  .scatter-chart {
    /* make css height the same as page width */


    height: 100vh;
    width: 100vh;
  }
  </style>
  