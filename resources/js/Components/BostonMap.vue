<template>
    <div class="boston-map">
      <div id="map" class="h-[70vh] mb-6"></div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted, watch } from 'vue';
  import 'leaflet/dist/leaflet.css';
  import * as L from 'leaflet';
  import 'leaflet.markercluster/dist/MarkerCluster.css';
  import 'leaflet.markercluster/dist/MarkerCluster.Default.css';
  import 'leaflet.markercluster';
  
  const props = defineProps({
    dataPoints: {
      type: Array,
      required: true,
      default: () => []
    }
  });
  
  const initialMap = ref(null);
  const markers = ref(null);
  
  onMounted(() => {
    // Initialize map
    initialMap.value = L.map('map').setView([42.3601, -71.0589], 13);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    }).addTo(initialMap.value);
  
    markers.value = L.markerClusterGroup();
    initialMap.value.addLayer(markers.value);
  
    // Watch for changes in dataPoints and update markers
    updateMarkers(props.dataPoints);
  });
  
  const updateMarkers = (dataPoints) => {
    if (!markers.value) return;
    
    markers.value.clearLayers();
  
    dataPoints.forEach((dataPoint) => {
      if (dataPoint.latitude && dataPoint.longitude) {
        //dataPoint.info is a dict with key-value pairs
        const popupContent = `
          <div>
            <strong>Date:</strong> ${new Date(dataPoint.date).toLocaleString()}<br>
            <strong>Type:</strong> ${dataPoint.type}<br>
            <strong>Info:</strong>  ${Object.entries(dataPoint.info).map(([key, value]) => `<br>${key}: ${value}`)}
          </div>
        `;
        const marker = L.marker([dataPoint.latitude, dataPoint.longitude]);
        marker.bindPopup(popupContent);
        markers.value.addLayer(marker);
      }
    });
  };
  
  // Update markers when dataPoints prop changes
  watch(() => props.dataPoints, updateMarkers, { deep: true });
  </script>
  
  <style scoped>
  #map {
    height: 70vh;
  }
  </style>
  