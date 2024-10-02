<template>
    <div>
      <input
        type="text"
        v-model="searchQuery"
        @input="searchAddresses"
        placeholder="Enter address"
        class="border p-2 rounded w-full"
      />
  
      <ul v-if="results.length" class="mt-2 border rounded p-2 bg-white shadow">
        <li 
          v-for="(result, index) in results" 
          :key="index" 
          @click="selectAddress(result)"
          class="cursor-pointer hover:bg-gray-100 p-2 rounded"
        >
          {{ result.full_address }} - ({{ result.x_coord }}, {{ result.y_coord }})
        </li>
      </ul>
  
      <p v-if="!results.length && searchQuery" class="text-gray-500 mt-2">No results found.</p>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  export default {
    props: ['initialSearchQuery'],
    data() {
      return {
        searchQuery: this.initialSearchQuery || '',
        results: [],
      };
    },
    methods: {
      async searchAddresses() {
        if (this.searchQuery.length >= 3) {
          try {
            const response = await axios.get('/search-address', {
              params: { address: this.searchQuery },
            });
            this.results = response.data.data;
          } catch (error) {
            console.error("Error fetching address data:", error);
          }
        } else {
          this.results = [];
        }
      },
      selectAddress(address) {
        const coords = {
          lat: address.y_coord,
          lng: address.x_coord,
        };
        this.$emit('address-selected', coords); // Emit the selected coordinates to the parent
      },
    },
  };
  </script>
  
  <style scoped>
  input {
    margin-bottom: 10px;
  }
  ul {
    max-height: 200px;
    overflow-y: auto;
  }
  li {
    padding: 8px;
    border-bottom: 1px solid #e0e0e0;
  }
  li:last-child {
    border-bottom: none;
  }
  </style>
  