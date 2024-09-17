<template>
  <PageTemplate>

    <!-- Page Title -->
    <h1 class="text-2xl font-bold text-gray-800 text-center">The Boston App</h1>


<!-- Form to submit new center coordinates -->
<p class="text-gray-700 mt-4 mt-8 text-lg leading-relaxed text-center">
  This map displays crime, 311 cases, and building permits located within a half mile from the center point. You can choose a new center point by clicking the "Choose New Center" button and then clicking on the map. Click "Save New Center" to update the map.
</p>

<form @submit.prevent="submitNewCenter" class="space-y-4 mb-4">
  

  <!-- Selected Center Coordinates display -->
  <div v-if="newCenter" class="p-4 bg-gray-100 rounded-lg shadow text-center">
    <p class="font-bold text-gray-800">Selected Center Coordinates:</p>
    <p class="text-gray-700">{{ newCenter.lat }}, {{ newCenter.lng }}</p>
  </div>
  <div v-else class="p-4 bg-gray-100 rounded-lg shadow text-center">
    <p class="font-bold text-gray-800">Current Center Coordinates:</p>
    <p class="text-gray-700">{{ centralLocation.latitude }}, {{ centralLocation.longitude }}</p>
  </div>
  <!-- Button container -->
  <div class="flex space-x-4">
    <!-- Choose New Center button -->
    <button 
      type="button"
      @click="toggleCenterSelection" 
      class="px-4 py-2 text-white bg-blue-500 rounded-lg shadow-lg disabled:bg-gray-400 hover:bg-blue-600 transition-colors w-1/2"
    >
      {{ centerSelectionActive ? 'Cancel' : 'Choose New Center' }}
    </button>
    
    <!-- Save New Center button -->
    <button 
      type="submit" 
      :disabled="!centerSelected" 
      class="px-4 py-2 text-white bg-green-500 rounded-lg disabled:bg-gray-400 hover:bg-green-600 transition-colors w-1/2"
    >
      Save New Center
    </button>
  </div>
</form>



        


    <!-- Boston Map Component -->
    <BostonMap 
      v-if="showMap"
      :dataPoints="filteredDataPoints" 
      :center="mapCenter" 
      :centerSelectionActive="centerSelectionActive" 
      :cancelNewMarker="cancelNewMarker"
      @map-click="setNewCenter" 
    />
    <div>
  
    <!-- Filter Buttons -->
    <div class="filter-container flex space-x-4">
      <button 
        v-for="(isActive, type) in filters" 
        :key="type" 
        @click="toggleFilter(type)"
        :class="{'active': isActive, 'inactive': !isActive, [`${type.toLowerCase().replace(' ', '-').replace(/\d/g, 'a')}-filter-button`]: true}"
        class="filter-button px-2 py-2 text-white bg-blue-500 rounded-lg shadow-lg disabled:bg-gray-400 hover:bg-blue-600 transition-colors w-1/4"
      >
        {{ type }}
      </button>
            <!-- Reload Button -->
            <button 
        @click="reloadMap" 
        class="px-4 py-2 text-white bg-red-500 rounded-lg shadow-lg hover:bg-red-600 transition-colors w-1/4"
      >
        Reload Map
      </button>
 
    </div>
    <p class="text-gray-700 mt-4 mb-4 text-lg leading-relaxed">
      Filter by data type by clicking the filter buttons above
    </p>

      <AiAssistant :context="filteredDataPoints" />

    <GenericDataList :totalData="filteredDataPoints" :itemsPerPage="5">
      <template v-slot:default="{ item }">
        <JsonTree :json="item" />
      </template>
    </GenericDataList>
  </div>
    <!-- Pass filteredDataPoints as context to AiAssistant -->
    
  </PageTemplate>
</template>

<script>
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3'; // Import Inertia form helper
import BostonMap from '../Components/BostonMap.vue';
import PageTemplate from '@/Components/PageTemplate.vue';
import AiAssistant from '@/Components/AiAssistant.vue';
import GenericDataList from '@/Components/GenericDataList.vue';
import JsonTree from '@/Components/JsonTree.vue';

export default {
  name: 'RadialMap',
  components: { BostonMap, PageTemplate, AiAssistant, GenericDataList, JsonTree },
  props: ['dataPoints', 'centralLocation'],
  
  setup(props) {
    const filters = ref({});
    const centerSelectionActive = ref(false);
    const centerSelected = ref(false);
    const newCenter = ref(null);
    const newMarker = ref(null); // Track the new center marker
    const mapCenter = ref([42.3601, -71.0589]); // Initial map center
    const cancelNewMarker = ref(false);
    const showMap = ref(true);

    // Use Inertia form for submitting centralLocation
    const form = useForm({
      centralLocation: {
        latitude: props.centralLocation.latitude,
        longitude: props.centralLocation.longitude,
      },
    });

    // Function to reload the map by toggling visibility
    const reloadMap = () => {
      showMap.value = false;    // Remove the map from the DOM
      setTimeout(() => {
        showMap.value = true;   // Re-add the map after a small delay
      }, 0);  // Use a 0 ms delay to force the rerender
    };

    // Populate filters with unique types from dataPoints
    props.dataPoints.forEach((dataPoint) => {
      if (!filters.value[dataPoint.type]) {
        filters.value[dataPoint.type] = true;
      }
    });

    // Compute filtered dataPoints based on active filters
    const filteredDataPoints = computed(() => {
      return props.dataPoints.filter(dataPoint => filters.value[dataPoint.type]);
    });

    // Toggle filter state
    const toggleFilter = (type) => {
      filters.value[type] = !filters.value[type];
    };

    

    const toggleCenterSelection = () => {
      if (centerSelectionActive.value) {
        // Cancel behavior: deactivate center selection and remove the marker
        centerSelectionActive.value = false;
        centerSelected.value = false;
        newCenter.value = null;

        // Emit the cancel event by setting `cancelNewMarker` to true
        cancelNewMarker.value = true;

        // If a marker is present, remove it from the map
        if (newMarker.value) {
          newMarker.value = null;
        }
      } else {
        // Enable center selection
        centerSelectionActive.value = true;
        centerSelected.value = false;
        newCenter.value = null;
        cancelNewMarker.value = false; // Reset the cancel state
      }
    };


    // Set new center on map click and place a marker
    const setNewCenter = (latlng) => {
      if (centerSelectionActive.value) {
        newCenter.value = latlng;
        centerSelected.value = true;
        form.centralLocation.latitude = latlng.lat;
        form.centralLocation.longitude = latlng.lng;

        // Emit the new marker position to BostonMap component
        // This ensures the new marker is updated correctly
        newMarker.value = latlng;
      }
    };

    // Submit the new center to the backend
    const submitNewCenter = () => {
      form.post('/map', {
        preserveScroll: true,
        onSuccess: () => {
          centerSelectionActive.value = false;
          centerSelected.value = false;
          // Optionally clear the marker here if necessary
        }
      });
    };

    return {
      filters,
      filteredDataPoints,
      toggleFilter,
      toggleCenterSelection,
      setNewCenter,
      submitNewCenter,
      centerSelectionActive,
      centerSelected,
      newCenter,
      form,
      mapCenter,
      cancelNewMarker,
      newMarker,
      showMap,
      reloadMap,
    };
  },
};

</script>

<style scoped>
#map {
  height: 70vh;
}

.filter-container {
  display: flex;
  margin-bottom: 15px;
}

.filter-button {
  border: 1px solid transparent;
}

.filter-button.active {
  background-color: black;
  color: white;
}

.building-permit-filter-button.active {
  background-color: green;
}

.crime-filter-button.active {
  background-color: red;
}

.aaa-case-filter-button.active {
  background-color: blue;
}

.center-filter-button {
  display:none;
}

.filter-button.inactive {
  background-color: #f0f0f0;
  color: #333;
}

.filter-button:hover {
  border: 1px solid black;
}

.center-control {
  margin-bottom: 15px;
  display: flex;
  gap: 10px;
}

.center-form {
  margin-bottom: 15px;
}
</style>
