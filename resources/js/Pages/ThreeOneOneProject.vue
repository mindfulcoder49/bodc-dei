
<template>
  <PageTemplate>
    <Head>
      <title>311 Case ScatterPlot</title>
    </Head>
    <main class="maintable">
        <div>
          <input 
            :value="searchTerm" 
            @input="updateSearchTerm" 
            placeholder="Search..."
            type="text"
            class="pageTemplate"
          />
          <button @click="fetchCases">Search</button>
        </div>

        <ThreeOneOneCaseScatter :cases="filteredCases" />
        
    <section>Total number of cases: {{ numberOfCases }}</section>

    
    </main>
  </PageTemplate>
</template>

<script>
import PageTemplate from '../Components/PageTemplate.vue';
import ThreeOneOneCaseScatter from '../Components/ThreeOneOneCaseScatter.vue'; 
import { Head, Link } from '@inertiajs/vue3';

export default {
  props: {
    cases: {
      type: Array,
      required: true,
    },
    search: {
      type: String,
      required: false,
    },
  },
  components: {
    PageTemplate, Head, Link, ThreeOneOneCaseScatter
  },
  data() {
    return {
      searchTerm: this.search
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
    },
    numberOfCases() {
      return this.filteredCases.length;
    },
    filteredKeys() {
      return Object.keys(this.filteredCases[0]).filter(key => key !== 'predictions');
    },
    filteredPredKeys() {
      //return Object.keys(this.filteredCases[0].predictions[0]);
      return ['id', 'created_at', 'updated_at', 'three_one_one_case_id', 'case_enquiry_id', 'ml_model_id', 'ml_model_name', 'prediction', 'prediction_date'];
    }

  },
  methods: {
    updateSearchTerm(event) {
        this.searchTerm = event.target.value;
    },
    fetchCases() {
        this.$inertia.get('/scatter', { searchTerm: this.searchTerm });
    }
  }
};
</script>
