<template>
    <div>
        <div class="flex justify-between items-center mt-4">
        <button @click="prevPage" :disabled="currentPage === 1" class="p-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Previous</button>
        <div>
          <span>Page </span>
          <input v-model.number="inputPage" @change="goToPage" type="number" min="1" :max="totalPages" class="w-16 p-1 border rounded-md text-center">
          <span> of {{ totalPages }}</span>
        </div>
        <button @click="nextPage" :disabled="currentPage === totalPages" class="p-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Next</button>
      </div>
      <ul class="pl-5">
        <!-- number of results -->
        <li v-if="paginatedCrimeData.length === 0" class="text-gray-500">No results found</li>
        <li v-else class="text-gray-500">Number of results: {{ filteredCrimeData.length }}</li>
        <li v-for="(crime, index) in paginatedCrimeData" :key="index">
          <CrimeData :crimeData="crime" />
        </li>
      </ul>

    </div>
  </template>
  
  <script>
  import CrimeData from './CrimeData.vue';
  
  export default {
    name: 'CrimeDataList',
    components: { CrimeData },
    props: {
      filteredCrimeData: {
        type: Array,
        required: true,
      },
      itemsPerPage: {
        type: Number,
        default: 10,
      },
    },
    data() {
      return {
        currentPage: 1,
        inputPage: 1,
      };
    },
    computed: {
      totalPages() {
        return Math.ceil(this.filteredCrimeData.length / this.itemsPerPage);
      },
      paginatedCrimeData() {
        const start = (this.currentPage - 1) * this.itemsPerPage;
        const end = start + this.itemsPerPage;
        return this.filteredCrimeData.slice(start, end);
      },
    },
    watch: {
      currentPage(newPage) {
        this.inputPage = newPage;
      }
    },
    methods: {
      nextPage() {
        if (this.currentPage < this.totalPages) {
          this.currentPage++;
        }
      },
      prevPage() {
        if (this.currentPage > 1) {
          this.currentPage--;
        }
      },
      goToPage() {
        if (this.inputPage >= 1 && this.inputPage <= this.totalPages) {
          this.currentPage = this.inputPage;
        } else {
          this.inputPage = this.currentPage;
        }
      }
    },
  };
  </script>
  