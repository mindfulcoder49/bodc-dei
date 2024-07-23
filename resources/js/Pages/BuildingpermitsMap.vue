<template>
    <div class="">
        <h3 class="text-2xl font-semibold mb-4">Interactive Buildingpermits Map</h3>
        <div id="map" class="h-[70vh] mb-6"></div>
        <h4 class="text-lg font-semibold mb-4">Natural Language Query</h4>
        <p class="mb-4">Enter a natural language query to filter the data:</p>
        <input v-model="naturalLanguageQuery" type="text" placeholder="Example: All the fraud that happened last week" class="p-2 border rounded-md w-full mb-4">
        <button @click="submitQuery" class="p-2 bg-blue-500 text-white rounded-md mb-4">Submit to GPT-4o-mini</button>
        <pre v-if="filters" class="p-2 border rounded-md w-full mb-4 overflow-scroll" rows="5" readonly>{ JSON.stringify(filters, null, 2) }</pre>
        <h4 class="text-lg font-semibold mb-4">Or Use Manual Filters</h4>
        <p class="mb-4">Use the manual filters below to filter the data:</p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="flex flex-col">
                <label for="category" class="font-medium mb-1">Choose a Category:</label>
                <select v-model="filters.category" class="p-2 border rounded-md">
                    <option value="">All</option>
                    <option v-for="category in categories" :key="category" :value="category">{ category }</option>
                </select>
            </div>
            <div class="flex flex-col">
                <label for="year" class="font-medium mb-1">Choose a Year:</label>
                <select v-model="filters.year" class="p-2 border rounded-md">
                    <option value="">All</option>
                    <option v-for="year in years" :key="year" :value="year">{ year }</option>
                </select>
            </div>
        </div>
        <button @click="updateMarkers" class="mt-4 p-2 bg-blue-500 text-white rounded-md">Submit Filters</button>
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

const { props } = usePage();

const initialMap = ref(null);
const markers = ref(null);
const data = ref(props.data || []);
const filters = ref({
    category: '',
    year: '',
});
const naturalLanguageQuery = ref('');

axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

const categories = ref([...new Set(data.value.map(item => item.category))].sort());
const years = [2024, 2023, 2022, 2021, 2020, 2019, 2018, 2017];

onMounted(() => {
    initialMap.value = L.map('map').setView([42.3601, -71.0589], 13);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    }).addTo(initialMap.value);

    updateMarkers();
});

const updateMarkers = async () => {
    try {
        const response = await axios.post('/api/BuildingPermits', { filters: filters.value });
        const responseData = response.data;

        initialMap.value.eachLayer((layer) => {
            if (layer instanceof L.Marker) {
                initialMap.value.removeLayer(layer);
            }
        });

        responseData.data.forEach((item) => {
            if (item.lat && item.long) {
                const popupContent = `
                    <div>
                        <strong>ID:</strong> ${item.id}<br>
                        <strong>Category:</strong> ${item.category}<br>
                        <strong>Year:</strong> ${item.year}
                    </div>
                `;
                const marker = L.marker([item.lat, item.long]);
                marker.bindPopup(popupContent);
                marker.addTo(initialMap.value);
            }
        });
    } catch (error) {
        console.error("Failed to fetch data", error);
    }
};

const submitQuery = async () => {
    try {
        const response = await axios.post('/api/BuildingPermits/natural-language-query', { query: naturalLanguageQuery.value });
        const responseData = response.data;
        data.value = responseData.data;

        Object.keys(filters.value).forEach(key => {
            if (responseData.filters.hasOwnProperty(key)) {
                filters.value[key] = responseData.filters[key];
            } else {
                filters.value[key] = '';
            }
        });

        updateMarkers();
    } catch (error) {
        console.error("Failed to process natural language query", error);
    }
};

watch(filters, updateMarkers, { deep: true });
</script>

<style scoped>
#map {
    height: 70vh;
}
</style>
