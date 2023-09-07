<template>
  <PageTemplate>
    <main class="pageTemplate maintable">
        <input 
          :value="searchTerm" 
          @input="updateSearchTerm" 
          placeholder="Search..."
          type="text"
          class="pageTemplate"
        />
        <button @click="fetchCases">Search</button>
        <table v-if="filteredCases.length">
          <thead>
            <tr>
              <th v-for="(value, key) in filteredCases[0]" :key="key">{{ key }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in filteredCases" :key='item["case_enquiry_id"]'>
              <td v-for="(value, key) in item" :key="key">{{ value }}</td>
            </tr>
          </tbody>
        </table>
        <div v-else>No cases found.</div>
    </main>
  </PageTemplate>
</template>

<script>
export default {
  props: {
    cases: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      searchTerm: ''
    };
  },
  computed: {
    filteredCases() {
      if (!this.searchTerm) return this.cases;
      const term = this.searchTerm.toLowerCase();

      return this.cases.filter(item => {
        return Object.values(item).some(value => 
          String(value).toLowerCase().includes(term)
        );
      });
    }
  },
  methods: {
    updateSearchTerm(event) {
        this.searchTerm = event.target.value;
    },
    fetchCases() {
        this.$inertia.get('/cases', { searchTerm: this.searchTerm });
    }
  }
};
</script>
