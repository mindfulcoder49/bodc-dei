<template>
  <div class="">
    <h3 class="text-2xl font-semibold mb-4">Interactive Boston Crime Map</h3>
    <div id="map" class="h-[70vh] mb-6"></div>

    <h4 class="text-lg font-semibold mb-4">Natural Language Query</h4>
    <p class="mb-4">Enter a natural language query to filter the crime data:</p>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <button @click="naturalLanguageQuery = 'All the fraud that happened last week'" class="p-2 border rounded-md w-full mb-4">All the fraud that happened last week</button>
      <button @click="naturalLanguageQuery = 'Last month\'s welfare checks'" class="p-2 border rounded-md w-full mb-4">Last month's welfare checks</button>
      <button @click="naturalLanguageQuery = 'Todo el robo que ocurrió el mes pasado'" class="p-2 border  rounded-md w-full mb-4">Todo el robo que ocurrió el mes pasado</button>
    </div>
    <input v-model="naturalLanguageQuery" type="text" placeholder="Example: All the fraud that happened last week" class="p-2 border rounded-md w-full mb-4">
    <button @click="submitQuery" id="submitQuery" class="p-2 bg-blue-500 text-white rounded-md mb-4">Submit to GPT-4o-mini</button>
    <pre v-if="filters" class="p-2 border rounded-md w-full mb-4 overflow-scroll" rows="5" readonly>{{ JSON.stringify(filters, null, 2) }}</pre>

    <h4 class="text-lg font-semibold mb-4">Or Use Manual Filters</h4>
    <p class="mb-4">Use the manual filters below to filter the crime data:</p>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <div class="flex flex-col">
        <label for="offenseCategory" class="font-medium mb-1">Choose Offense Categories:</label>
        <select v-model="filters.offense_category" multiple class="p-2 border rounded-md">
          <option value="">All</option>
          <option v-for="category in offenseCategories" :key="category.value" :value="category.value">{{ category.label }}</option>
        </select>
      </div>
      <div class="flex flex-col">
        <label for="district" class="font-medium mb-1">Choose Districts:</label>
        <select v-model="filters.district" multiple class="p-2 border rounded-md">
          <option value="">All</option>
          <option v-for="district in districts" :key="district" :value="district">{{ district }}</option>
        </select>
      </div>
      <div class="flex flex-col">
        <label for="year" class="font-medium mb-1">Choose Years:</label>
        <select v-model="filters.year" multiple class="p-2 border rounded-md">
          <option value="">All</option>
          <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
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
      <div class="flex flex-col">
        <label for="shooting" class="font-medium mb-1">Shooting:</label>
        <input type="checkbox" v-model="filters.shooting" class="p-2 border rounded-md">
      </div>
      <!-- add record limit filter called limit-->
      <div class="flex flex-col">
        <label for="limit" class="font-medium mb-1">Record Limit:</label>
        <input type="number" v-model="filters.limit" class="p-2 border rounded-md">
        </div>  
    </div>
    <button @click="updateMarkers" class="mt-4 p-2 bg-blue-500 text-white rounded-md">Submit Filters</button>
    <button @click="clearFilters" class="m-4  p-2 bg-blue-500 text-white rounded-md">Clear Filters</button>
  </div>
  <div class="mt-6">
    <h4 class="text-lg font-semibold mb-4">List of Crime Data Points</h4>
    <button @click="downloadCSV" class="p-2 bg-green-500 text-white rounded-md mb-4">Download as CSV</button>
    <CrimeDataList :filteredCrimeData="filteredCrimeData" />
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import 'leaflet/dist/leaflet.css';
import * as L from 'leaflet';
import 'leaflet.markercluster/dist/MarkerCluster.css';
import 'leaflet.markercluster/dist/MarkerCluster.Default.css';
import 'leaflet.markercluster';
import CrimeData from './CrimeData.vue';
import CrimeDataList from './CrimeDataList.vue';

const { props } = usePage();

const initialMap = ref(null);
const markers = ref(null);
const crimeData = ref(props.crimeData || []);
const filteredCrimeData = ref([]);
const filters = ref({
  offense_codes: '',
  offense_category: [],
  district: [],
  start_date: '',
  end_date: '',
  year: [],
  limit: 1500,
  shooting: false
});
const naturalLanguageQuery = ref('');

axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

const offenseCategories = ref([
  { value: 'murder_and_manslaughter', label: 'Murder and Manslaughter' },
  { value: 'rape', label: 'Rape' },
  { value: 'robbery', label: 'Robbery' },
  { value: 'assault', label: 'Assault' },
  { value: 'burglary', label: 'Burglary' },
  { value: 'larceny', label: 'Larceny' },
  { value: 'auto_theft', label: 'Auto Theft' },
  { value: 'simple_assault', label: 'Simple Assault' },
  { value: 'arson', label: 'Arson' },
  { value: 'forgery_counterfeiting', label: 'Forgery and Counterfeiting' },
  { value: 'fraud', label: 'Fraud' },
  { value: 'embezzlement', label: 'Embezzlement' },
  { value: 'stolen_property', label: 'Stolen Property' },
  { value: 'vandalism', label: 'Vandalism' },
  { value: 'weapons_violations', label: 'Weapons Violations' },
  { value: 'prostitution', label: 'Prostitution' },
  { value: 'sex_offenses', label: 'Sex Offenses' },
  { value: 'drug_violations', label: 'Drug Violations' },
  { value: 'gambling', label: 'Gambling' },
  { value: 'child_offenses', label: 'Child Offenses' },
  { value: 'alcohol_violations', label: 'Alcohol Violations' },
  { value: 'disorderly_conduct', label: 'Disorderly Conduct' },
  { value: 'kidnapping', label: 'Kidnapping' },
  { value: 'miscellaneous_offenses', label: 'Miscellaneous Offenses' },
  { value: 'vehicle_laws', label: 'Vehicle Laws' },
  { value: 'investigations', label: 'Investigations' },
  { value: 'other_services', label: 'Other Services' },
  { value: 'property', label: 'Property' },
  { value: 'disputes', label: 'Disputes' },
  { value: 'animal_incidents', label: 'Animal Incidents' },
  { value: 'missing_persons', label: 'Missing Persons' },
  { value: 'other_reports', label: 'Other Reports' },
  { value: 'accidents', label: 'Accidents' }
]);

const districts = ['A1', 'A15', 'A7', 'B2', 'B3', 'C11', 'C6', 'D14', 'D4', 'E13'];
const years = ['2024', '2023', '2022', '2021', '2020', '2019', '2018', '2017'];

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
    const response = await axios.post('/api/crime-data', { filters: filters.value });
    const data = response.data;
    markers.value.clearLayers();
    filteredCrimeData.value = data.crimeData;
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

const escapeCSVField = (field) => {
  if (typeof field === 'string') {
    // Replace newlines and carriage returns with a space
    field = field.replace(/[\r\n]+/g, ' ');
    // Escape double quotes by doubling them
    if (field.includes(',')) {
      return `"${field.replace(/"/g, '""')}"`;
    }
  }
  return field;
};



const downloadCSV = () => {
  /*
  const csvContent = [
    ['Incident Number', 'Offense Description', 'District', 'Occurred On Date']
    .join(','), // header row
    ...crimeData.value.map(crime => [
      crime.incident_number,
      crime.offense_description,
      crime.district,
      new Date(crime.occurred_on_date).toLocaleString()
    ].join(',')) // data rows
  ].join('\n');

  full data
      protected $fillable = [
        'incident_number',
        'offense_code',
        'offense_code_group',
        'offense_description',
        'district',
        'reporting_area',
        'shooting',
        'occurred_on_date',
        'year',
        'month',
        'day_of_week',
        'hour',
        'ucr_part',
        'street',
        'lat',
        'long',
        'location',
        'offense_category',
    ];
  */
  //like above but full data, using escapeCSVField
  const csvContent = [
    ['Incident Number', 'Offense Code', 'Offense Code Group', 'Offense Description', 'District', 'Reporting Area', 'Shooting', 'Occurred On Date', 'Year', 'Month', 'Day of Week', 'Hour', 'UCR Part', 'Street', 'Lat', 'Long', 'Location', 'Offense Category']
    .map(escapeCSVField)
    .join(','), // header row
    ...filteredCrimeData.value.map(crime => [
      crime.incident_number,
      crime.offense_code,
      crime.offense_code_group,
      crime.offense_description,
      crime.district,
      crime.reporting_area,
      crime.shooting ? 'Yes' : 'No',
      new Date(crime.occurred_on_date).toLocaleString(),
      crime.year,
      crime.month,
      crime.day_of_week,
      crime.hour,
      crime.ucr_part,
      crime.street,
      crime.lat,
      crime.long,
      crime.location,
      crime.offense_category
    ].map(escapeCSVField)
    .join(',')) // data rows
  ].join('\n');

  const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
  const link = document.createElement('a');
  const url = URL.createObjectURL(blob);
  link.setAttribute('href', url);
  link.setAttribute('download', 'crime_data.csv');
  link.style.visibility = 'hidden';
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
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
    // Change the button text for element with id submitQuery to "Loading..."
    document.getElementById('submitQuery').innerText = 'Loading...';
    //disable it
    document.getElementById('submitQuery').disabled = true;
    const response = await axios.post('/api/natural-language-query', { query: naturalLanguageQuery.value });
    const data = response.data;
    crimeData.value = data.crimeData;

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

    // Change the button text for element with id submitQuery back to "Submit to GPT-4o-mini"
    document.getElementById('submitQuery').innerText = 'Submit to GPT-4o-mini';
    //enable it
    document.getElementById('submitQuery').disabled = false;

    updateMarkers();
  } catch (error) {
    // Change the button text for element with id submitQuery back to "Submit to GPT-4o-mini"
    document.getElementById('submitQuery').innerText = 'Submit to GPT-4o-mini';
    //enable it
    document.getElementById('submitQuery').disabled = false;
    console.error("Failed to process natural language query", error);
  }
};

const clearFilters = () => {
  Object.keys(filters.value).forEach(key => {
    if (key === 'offense_category' || key === 'district' || key === 'year') {
      filters.value[key] = [];
    } else if (key === 'shooting') {
      filters.value[key] = false;
    } else {
      filters.value[key] = '';
    }
  });
  updateMarkers();
};

watch(filters, updateMarkers, { deep: true });
</script>

<style scoped>
#map {
  height: 70vh;
}
</style>
