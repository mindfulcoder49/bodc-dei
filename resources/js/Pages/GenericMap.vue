<template>
  <PageTemplate>
    <!-- Filter Buttons -->
    <div class="filter-container">
      <button 
        v-for="(isActive, type) in filters" 
        :key="type" 
        @click="toggleFilter(type)"
        :class="{'active': isActive, 'inactive': !isActive}"
        class="filter-button"
      >
        {{ type }}
      </button>
    </div>
    
    <!-- Boston Map Component -->
    <BostonMap :dataPoints="filteredDataPoints" />
    
    <!-- Pass filteredDataPoints as context to AiAssistant -->
    <AiAssistant :context="filteredDataPoints" />
  </PageTemplate>
</template>

<script>
import { ref, computed } from 'vue';
import BostonMap from '../Components/BostonMap.vue';
import PageTemplate from '@/Components/PageTemplate.vue';
import AiAssistant from '@/Components/AiAssistant.vue';

export default {
  name: 'GenericMap',
  components: { BostonMap, PageTemplate, AiAssistant },
  props: ['dataPoints'],
  
  setup(props) {
    // Initialize filters for each type based on the dataPoints
    const filters = ref({});
    
    // Populate filters with unique types from dataPoints
    props.dataPoints.forEach((dataPoint) => {
      if (!filters.value[dataPoint.type]) {
        filters.value[dataPoint.type] = true; // Default all to active (true)
      }
    });

    // Compute filtered dataPoints based on active filters
    const filteredDataPoints = computed(() => {
      return props.dataPoints.filter(dataPoint => filters.value[dataPoint.type]);
    });

    // Method to toggle filter state
    const toggleFilter = (type) => {
      filters.value[type] = !filters.value[type];
    };

    return {
      filters,
      filteredDataPoints,
      toggleFilter,
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
  flex-wrap: wrap;
  gap: 10px;
  margin-bottom: 15px;
  justify-content: center;
}

.filter-button {
  padding: 10px 20px;
  border: none;
  cursor: pointer;
  border-radius: 5px;
  transition: background-color 0.3s, color 0.3s;
  font-weight: bold;
}

.filter-button.active {
  background-color: #4CAF50;
  color: white;
}

.filter-button.inactive {
  background-color: #f0f0f0;
  color: #333;
}

.filter-button:hover {
  background-color: #3e8e41;
  color: white;
}
</style>
