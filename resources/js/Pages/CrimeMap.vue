<template>
  <PageTemplate>
    <div class="p-6">
      <h3 class="text-2xl font-semibold mb-4">Interactive Boston Crime Map</h3>
      <div id="map" class="h-[70vh] mb-6"></div>
      <!-- Add some long buttons that look like text inputs with examples of naturalLanguageQuerys and update the models and run them if they are clicked-->
      <h4 class="text-lg font-semibold mb-4">Natural Language Query</h4>
      <p class="mb-4">Enter a natural language query to filter the crime data:</p>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <button @click="naturalLanguageQuery = 'All the fraud that happened last week'" class="p-2 border rounded-md w-full mb-4">All the fraud that happened last week</button>
      <button @click="naturalLanguageQuery = 'All the shootings that happened last month'" class="p-2 border rounded-md w-full mb-4">All the shootings that happened last month</button>
      <button @click="naturalLanguageQuery = 'All the thefts that happened last year'" class="p-2 border  rounded-md w-full mb-4">All the thefts that happened last year</button>
    </div>
      <input v-model="naturalLanguageQuery" type="text" placeholder="Example: All the fraud that happened last week" class="p-2 border rounded-md w-full mb-4">
      <button @click="submitQuery" class="p-2 bg-blue-500 text-white rounded-md mb-4">Submit to GPT-4o</button>
      <pre v-if="filters" class="p-2 border rounded-md w-full mb-4" rows="5" readonly>{{ JSON.stringify(filters, null, 2) }}</pre>
      <h4 class="text-lg font-semibold mb-4">Or Use Manual Filters</h4>
      <p class="mb-4">Use the manual filters below to filter the crime data:</p>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="flex flex-col">
          <label for="offenseCategory" class="font-medium mb-1">Choose an Offense Category:</label>
          <select v-model="filters.offense_category" class="p-2 border rounded-md">
            <option value="">All</option>
            <option v-for="category in offenseCategories" :key="category" :value="category">{{ category }}</option>
          </select>
        </div>
        <div class="flex flex-col">
          <label for="district" class="font-medium mb-1">Choose a District:</label>
          <select v-model="filters.district" class="p-2 border rounded-md">
            <option value="">All</option>
            <option v-for="district in districts" :key="district" :value="district">{{ district }}</option>
          </select>
        </div>
        <div class="flex flex-col">
          <label for="year" class="font-medium mb-1">Choose a Year:</label>
          <select v-model="filters.year" class="p-2 border rounded-md">
            <option value="">All</option>
            <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
          </select>
        </div>
        <div class="flex flex-col">
          <label for="month" class="font-medium mb-1">Choose a Month:</label>
          <select v-model="filters.month" class="p-2 border rounded-md">
            <option value="">All</option>
            <option v-for="month in months" :key="month" :value="month">{{ month }}</option>
          </select>
        </div>
        <div class="flex flex-col">
          <label for="dayOfMonth" class="font-medium mb-1">Choose a Day of the Month:</label>
          <select v-model="filters.day_of_month" class="p-2 border rounded-md">
            <option value="">All</option>
            <option v-for="day in daysOfMonth" :key="day" :value="day">{{ day }}</option>
          </select>
        </div>
        <div class="flex flex-col">
          <label for="dayOfWeek" class="font-medium mb-1">Choose a Day of the Week:</label>
          <select v-model="filters.day_of_week" class="p-2 border rounded-md">
            <option value="">All</option>
            <option v-for="day in daysOfWeek" :key="day" :value="day">{{ day }}</option>
          </select>
        </div>
        <div class="flex flex-col">
          <label for="hour" class="font-medium mb-1">Choose an Hour:</label>
          <select v-model="filters.hour" class="p-2 border rounded-md">
            <option value="">All</option>
            <option v-for="hour in hours" :key="hour" :value="hour">{{ hour }}</option>
          </select>
        </div>
        <div class="flex flex-col">
          <label for="offenseCodes" class="font-medium mb-1">Enter Offense Codes (comma separated):</label>
          <input v-model="filters.offense_codes" class="p-2 border rounded-md" placeholder="1103, 1104">
        </div>
        <div class="flex flex-col">
          <label for="startDate" class="font-medium mb-1">Start Date:</label>
          <input type="date" v-model="filters.start_date" class="p-2 border rounded-md">
        </div>
        <div class="flex flex-col">
          <label for="endDate" class="font-medium mb-1">End Date:</label>
          <input type="date" v-model="filters.end_date" class="p-2 border rounded-md">
        </div>
      </div>
      <button @click="updateMarkers" class="mt-4 p-2 bg-blue-500 text-white rounded-md">Submit Filters</button>
    </div>
  </PageTemplate>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import 'leaflet/dist/leaflet.css';
import * as L from 'leaflet';
import 'leaflet.markercluster/dist/MarkerCluster.css';
import 'leaflet.markercluster/dist/MarkerCluster.Default.css';
import 'leaflet.markercluster';
import PageTemplate from '../Components/PageTemplate.vue';

const { props } = usePage();

const initialMap = ref(null);
const markers = ref(null);
const crimeData = ref(props.crimeData || []);
const filters = ref({
  offense_codes: '',
  offense_category: '',
  district: '',
  start_date: '',
  end_date: '',
  days_of_week: [],
  hours: [],
  day_of_month: '',
  month: '',
  year: '',
  ucr_part: '',
  location: '',
  shooting: ''
});
const naturalLanguageQuery = ref('');

axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


const offenseCategories = ref([...new Set(crimeData.value.map(crime => crime.offense_category))].sort());
const districts = ref([...new Set(crimeData.value.map(crime => crime.district))].sort());
const years = [2024, 2023, 2022, 2021, 2020, 2019, 2018, 2017];
const months = ref([...new Set(crimeData.value.map(crime => crime.month))].sort((a, b) => a - b));
const daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
const daysOfMonth = ref([...new Set(crimeData.value.map(crime => new Date(crime.occurred_on_date).getDate()))].sort((a, b) => a - b));
const hours = ref([...new Set(crimeData.value.map(crime => crime.hour))].sort((a, b) => a - b));

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

const updateMarkers = async () => {
  try {
    const response = await axios.post( '/api/crime-data', { filters: filters.value });
    const data = response.data;
    markers.value.clearLayers();
    data.crimeData.forEach((crime) => {
      if (crime.lat && crime.long) {
        const popupContent = `
          <div>
            <strong>Incident Number:</strong> ${crime.incident_number}<br>
            <strong>Offense Code:</strong> ${crime.offense_code}<br>
            <strong>Offense Code Group:</strong> ${crime.offense_code_group}<br>
            <strong>Offense Description:</strong> ${crime.offense_description}<br>
            <strong>District:</strong> ${crime.district}<br>
            <strong>Reporting Area:</strong> ${crime.reporting_area}<br>
            <strong>Shooting:</strong> ${crime.shooting ? 'Yes' : 'No'}<br>
            <strong>Occurred On:</strong> ${new Date(crime.occurred_on_date).toLocaleString()}<br>
            <strong>Year:</strong> ${crime.year}<br>
            <strong>Month:</strong> ${crime.month}<br>
            <strong>Day of Week:</strong> ${crime.day_of_week}<br>
            <strong>Hour:</strong> ${crime.hour}<br>
            <strong>UCR Part:</strong> ${crime.ucr_part}<br>
            <strong>Street:</strong> ${crime.street}<br>
            <strong>Location:</strong> ${crime.location}<br>
            <strong>Offense Category:</strong> ${crime.offense_category}
          </div>
        `;
        const marker = L.marker([crime.lat, crime.long]);
        marker.bindPopup(popupContent);
        markers.value.addLayer(marker);
      }
    });
  } catch (error) {
    console.error("Failed to fetch crime data", error);
  }
};


const formatDate = (dateString) => {
  const date = new Date(dateString);
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const day = String(date.getDate()).padStart(2, '0');
  return `${year}-${month}-${day}`;
};

const submitQuery = async () => {
  try {
    const response = await axios.post('/api/natural-language-query', { query: naturalLanguageQuery.value });
    const data = response.data;
    crimeData.value = data.crimeData;

    /* Object.keys(data.filters).forEach(key => {
      if (filters.value.hasOwnProperty(key)) {
        if (key === 'start_date' || key === 'end_date') {
          filters.value[key] = formatDate(data.filters[key]);
        } else {
          filters.value[key] = data.filters[key];
        }
      } else {
        filters.value[key] = '';
      }
    }); */
    // Loop through the page filters and update the filters object, if the key is start_date or end_date, format the date, otherwise just set the value
    // If the key is not in the filters object, set it to an empty string
    Object.keys(filters.value).forEach(key => {
      if (data.filters.hasOwnProperty(key)) {
        if (key === 'start_date' || key === 'end_date') {
          filters.value[key] = formatDate(data.filters[key]);
        } else {
          filters.value[key] = data.filters[key];
        }
      } else {
        filters.value[key] = '';
      }
    });

    updateMarkers();
  } catch (error) {
    console.error("Failed to process natural language query", error);
  }
};


// Watchers to ensure the map updates when filters change
watch(filters, updateMarkers, { deep: true });
</script>

<style scoped>
#map {
  height: 70vh;
}
</style>
