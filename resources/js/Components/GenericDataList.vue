<template>
  <div>
    <div class="flex justify-between items-center mt-4 mb-4">
      <button
        @click="prevPage"
        :disabled="currentPage === 1"
        class="p-2 bg-blue-500 text-white rounded-lg shadow-lg hover:bg-blue-600 w-1/4 sm:w-1/6 disabled:bg-gray-300"
      >
        Previous
      </button>
      <div class="flex items-center">
        <span class="mr-2">Page</span>
        <input
          v-model.number="inputPage"
          @change="goToPage"
          type="number"
          min="1"
          :max="totalPages"
          class="w-16 p-1 border rounded-md text-center"
        />
        <span class="ml-2">of {{ totalPages }}</span>
      </div>
      <button
        @click="nextPage"
        :disabled="currentPage === totalPages"
        class="p-2 bg-blue-500 text-white rounded-lg shadow-lg hover:bg-blue-600 w-1/4 sm:w-1/6 disabled:bg-gray-300"
      >
        Next
      </button>
    </div>

    <div v-if="paginatedData.length === 0" class="text-center text-gray-500">
      No results found
    </div>
    <div v-else class="overflow-x-auto">
      <div class="text-center text-gray-500 mb-4">
        Number of results: {{ totalData.length }}
      </div>
      <table class="table-auto w-full border-collapse border border-gray-200"> 
        <thead class="bg-gray-100">
          <tr>
            <th @click="sortBy('latitude')" class="px-4 py-2 border border-gray-300 cursor-pointer">
              Latitude
              <span v-if="sortKey === 'latitude'">
                {{ sortOrder === 'desc' ? '↓' : '↑' }}
              </span>
            </th>
            <th @click="sortBy('longitude')" class="px-4 py-2 border border-gray-300 cursor-pointer">
              Longitude
              <span v-if="sortKey === 'longitude'">
                {{ sortOrder === 'desc' ? '↓' : '↑' }}
              </span>
            </th>
            <th @click="sortBy('date')" class="px-4 py-2 border border-gray-300 cursor-pointer">
              Date
              <span v-if="sortKey === 'date'">
                {{ sortOrder === 'desc' ? '↓' : '↑' }}
              </span>
            </th>
            <th @click="sortBy('type')" class="px-4 py-2 border border-gray-300 cursor-pointer">
              Type
              <span v-if="sortKey === 'type'">
                {{ sortOrder === 'desc' ? '↓' : '↑' }}
              </span>
            </th>
            <th class="px-4 py-2 border border-gray-300">Info</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in paginatedData" :key="index">
            <td class="border px-4 py-2 text-center">{{ item.latitude }}</td>
            <td class="border px-4 py-2 text-center">{{ item.longitude }}</td>
            <td class="border px-4 py-2 text-center">{{ new Date(item.date).toLocaleString() }}</td>
            <td class="border px-4 py-2 text-center">{{ item.type }}</td>
            <td class="border px-4 py-2">
              <ul class=" max-h-32 overflow-y-auto">
                <li v-for="(value, key) in item.info" :key="key">
                  <span class="font-semibold">{{ key }}:</span> {{ value }}
                </li>
              </ul>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
export default {
  name: "GenericDataList",
  props: {
    totalData: {
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
      sortKey: 'date',  // Default sort by date
      sortOrder: 'desc' // Default to descending order
    };
  },
  computed: {
    totalPages() {
      return Math.ceil(this.totalData.length / this.itemsPerPage);
    },
    sortedData() {
      return [...this.totalData].sort((a, b) => {
        let result = 0;
        if (a[this.sortKey] < b[this.sortKey]) {
          result = -1;
        } else if (a[this.sortKey] > b[this.sortKey]) {
          result = 1;
        }
        return this.sortOrder === 'asc' ? result : -result;
      });
    },
    paginatedData() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.sortedData.slice(start, end);
    },
  },
  watch: {
    currentPage(newPage) {
      this.inputPage = newPage;
    },
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
    },
    sortBy(key) {
      if (this.sortKey === key) {
        // If the same column is clicked, toggle the sort order
        this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc';
      } else {
        // If a new column is clicked, set it as the sort key and default to descending order
        this.sortKey = key;
        this.sortOrder = 'desc';
      }
    }
  }
};
</script>

<style scoped>
/* Add necessary styles */
</style>
