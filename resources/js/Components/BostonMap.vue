<template>
  <div class="boston-map">
    <div id="map" class="h-[70vh] mb-6 rounded-lg shadow-lg"></div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, nextTick } from 'vue';
import 'leaflet/dist/leaflet.css';
import * as L from 'leaflet';

// Define the icons for different types of markers
const getDivIcon = (type) => {
  let className = 'default-div-icon'; // Fallback class

  switch (type) {
    case 'Crime':
      className = 'crime-div-icon';
      break;
    case '311 Case':
      className = 'case-div-icon';
      break;
    case 'Building Permit':
      className = 'permit-div-icon';
      break;
    case 'Center':
      className = 'center-div-icon';
      break;
    default:
      break;
  }

  return L.divIcon({
    className,
    html: ``, // You can customize this to show more data
    iconSize: [10, 10],
    popupAnchor: [0, -15],
  });
};

const props = defineProps({
  dataPoints: {
    type: Array,
    required: true,
    default: () => [],
  },
  center: {  
    type: Array, //used for the centerview of the map
    required: true,
  },
  centralLocation: { 
    type: Object, //used for the central point so it can be set independent of the centerview
    required: true,
  },
  centerSelectionActive: {
    type: Boolean, // A prop to determine if "Choose New Center" is active
    required: true,
  },
  cancelNewMarker: {
    type: Boolean, // A prop to signal the cancellation of the new marker
    required: true,
    default: false,
  },
});

const emit = defineEmits(['map-click']);
const initialMap = ref(null);
const markerCenter = ref(null); // Store the center marker
const newMarker = ref(null); // Ref for dynamically added marker

onMounted(() => {
  nextTick(() => {
    // Initialize the map with the provided center or default to Boston if not available
    initialMap.value = L.map('map').setView(props.center || [42.3601, -71.0589], 16);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    }).addTo(initialMap.value);

    // Handle map clicks to set a new center only if centerSelectionActive is true
    initialMap.value.on('click', (e) => {
      if (props.centerSelectionActive) {
        emit('map-click', e.latlng);

        // Add a new marker for the clicked location
        if (newMarker.value) {
          initialMap.value.removeLayer(newMarker.value); // Remove old marker if exists
        }

        // Add new center marker dynamically
        newMarker.value = L.marker([e.latlng.lat, e.latlng.lng], {
          icon: getDivIcon('Center'),
        }).addTo(initialMap.value);
      }
    });

    // Add the center marker
    markerCenter.value = L.marker(props.center, {
      icon: getDivIcon('Center'),
    }).addTo(initialMap.value); 

    // Initialize the markers for the dataPoints
    updateMarkers(props.dataPoints);
  });
});

// Update the markers for dataPoints and add the new center
const markers = ref([]);

const updateMarkers = (dataPoints) => {
  if (!initialMap.value) return;

  // Clear existing markers
  markers.value.forEach(marker => initialMap.value.removeLayer(marker));
  markers.value = []; // Reset the markers array

  // Add new markers with DivIcons
  dataPoints.forEach((dataPoint) => {
    if (dataPoint.latitude && dataPoint.longitude) {
      const popupContent = `

         <div><strong>Date:</strong> ${new Date(dataPoint.date).toLocaleString()}<br></div>
        <div><strong>Type:</strong> ${dataPoint.type}<br></div>
        ${Object.entries(dataPoint.info).map(([key, value]) => `<div><div class="infoname">${key}:</div><div class="infovalue">${value}</div></div>`).join('')}


      `;

      const marker = L.marker([dataPoint.latitude, dataPoint.longitude], {
        icon: getDivIcon(dataPoint.type),
      });

      marker.bindPopup(popupContent);
      marker.addTo(initialMap.value);

      // Store marker reference in array
      markers.value.push(marker);
    }
  });
};

// Watch for changes in the dataPoints and update markers accordingly
watch(() => props.dataPoints, updateMarkers, { deep: true });

// Watch for changes in the center and update the map center and center marker
watch(() => props.center, (newCenter) => {
  if (initialMap.value) {
    initialMap.value.setView(newCenter);


  }
});

watch(() => props.centralLocation, (centralLocation) => {
    // Remove old center marker
    if (markerCenter.value) {
      initialMap.value.removeLayer(markerCenter.value);
    }

    // Add new center marker
    markerCenter.value = L.marker([centralLocation.latitude, centralLocation.longitude], {
      icon: getDivIcon('Center'),
    }).addTo(initialMap.value);
});


watch(() => props.cancelNewMarker, (cancel) => {
  console.log('cancel', cancel);
  if (cancel && newMarker.value) {
    initialMap.value.removeLayer(newMarker.value);
  }
});
</script>

<style scoped>
#map {
  height: 70vh;
}

/* Define your custom DivIcons */
.default-div-icon {
  background-color: gray;
  width: 10px;
  height: 10px;
  border-radius: 50%;
}

.crime-div-icon {
  background-color: red;
  width: 10px;
  height: 10px;
  border-radius: 50%;
}

.case-div-icon {
  background-color: blue;
  width: 10px;
  height: 10px;
  border-radius: 50%;
}

.permit-div-icon {
  background-color: green;
  width: 10px;
  height: 10px;
  border-radius: 50%;
}

.center-div-icon {
  background-color: yellow;
  width: 10px;
  height: 10px;
  border-radius: 50%;
}


</style>
