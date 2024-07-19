<template>
        <div class="">
        <h3 class="text-2xl font-semibold mb-4">Interactive Boston 311 Map</h3>
        <div id="map" class="h-[70vh] mb-6"></div>
      </div>
</template>

<script setup>
import { computed, ref, onMounted, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import 'leaflet/dist/leaflet.css';
import * as L from 'leaflet';
import 'leaflet.markercluster/dist/MarkerCluster.css';
import 'leaflet.markercluster/dist/MarkerCluster.Default.css';
import 'leaflet.markercluster';
import { init } from 'echarts';

const props = defineProps({
  cases: {
    type: Array,
    required: true,
  },

});

const initialMap = ref(null);
const markers = ref(null);

const closedCases = computed(() => 
  props.cases.filter(item => item.case_status === 'Closed').map(item => [item.longitude, item.latitude])
);

const openCases = computed(() => 
  props.cases.filter(item => item.case_status === 'Open').map(item => [item.longitude, item.latitude])
);

onMounted(() => {
    initialMap.value = L.map('map').setView([42.3601, -71.0589], 13);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    }).addTo(initialMap.value);
  
    markers.value = L.markerClusterGroup();
    initialMap.value.addLayer(markers.value);
    updateMarkers();
  });

  const updateMarkers = () => {
    markers.value.clearLayers();
    props.cases.forEach(item => {
      const marker = L.marker([item.latitude, item.longitude]);
      marker.bindPopup(`<b>${item.case_title}</b><br>${item.case_status}`);
      markers.value.addLayer(marker);
    });
  };
</script>

<style scoped>
.scatter-chart {
  /* make css height the same as page width */


  height: 100vh;
  width: 100vh;
}
</style>
