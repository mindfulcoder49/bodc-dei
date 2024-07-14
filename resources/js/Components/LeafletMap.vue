<template>
    <div>
      <h3>Interactive Crime Map</h3>
      <div id="map" style="height:90vh;"></div>
      <div>
        <label for="offenseCategory">Choose an Offense Category:</label>
        <select v-model="selectedCategory" @change="updateMarkers">
          <option v-for="category in offenseCategories" :key="category" :value="category">{{ category }}</option>
        </select>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted, watch } from 'vue';
  import 'leaflet/dist/leaflet.css';
  import * as L from 'leaflet';
  import 'leaflet.markercluster/dist/MarkerCluster.css';
  import 'leaflet.markercluster/dist/MarkerCluster.Default.css';
  import 'leaflet.markercluster';
  
  const initialMap = ref(null);
  const selectedCategory = ref('');
  const markers = ref(null);
  const crimeData = ref([]);
  const offenseCategories = ref([]);
  
  // Fetch crime data from the public directory
  const fetchCrimeData = async () => {
    const response = await fetch('/cleaned_data.json'); // Adjust the path if necessary
    const data = await response.json();
    crimeData.value = data;
  
    // Extract unique offense categories
    const categories = [...new Set(data.map(crime => crime.offense_category))];
    offenseCategories.value = categories;
  };
  
  // Initialize the map and add markers
  onMounted(async () => {
    await fetchCrimeData();
  
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
    crimeData.value
      .filter((crime) => !selectedCategory.value || crime.offense_category === selectedCategory.value)
      .forEach((crime) => {
        const marker = L.marker([crime.latitude, crime.longitude]);
        marker.bindPopup(`<strong>Offense Category:</strong> ${crime.offense_category}`);
        markers.value.addLayer(marker);
      });
  };
  
  watch(selectedCategory, updateMarkers);
  </script>
  
  <style scoped>
  #map {
    height: 90vh;
  }
  </style>
  