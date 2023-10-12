


<template>
  <PageTemplate>
    <Head>
      <title>311 Case List</title>
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

        <!-- <ThreeOneOneCaseScatter :cases="cases" /> -->
        
<section>Total number of cases: {{ numberOfCases }}</section>

<div class="cases table" v-if="filteredCases.length">
    <div class="thead">
        <div class="tr">
            <div class="th">Show Predictions</div>
            <div class="th" v-for="key in filteredKeys" :key="key">{{ key }}</div>
        </div>
    </div>
    <div class="tbody">
        <template v-for="item in filteredCases" :key='item["case_enquiry_id"]'>
            <!-- Main Data Row -->
            <div class="tr">
                <!-- Plus icon to toggle predictions -->
                <div class="td">
                    <button v-if="!item.predictions.showPredictions" @click="item.predictions.showPredictions = !item.predictions.showPredictions">+</button>
                    <button v-if="item.predictions.showPredictions" @click="item.predictions.showPredictions = !item.predictions.showPredictions">-</button>
                </div>
                <div class="td" v-for="key in filteredKeys" :key="key">{{ item[key] }}</div>
            </div>
            
            <!-- Predictions Row -->
            <div class="table prediction" v-if="item.predictions.showPredictions">
                <div class="thead">
                  <div class="tr">
                      <div class="th" v-for="key in filteredPredKeys" :key="key">{{ key }}</div>
                  </div>
                </div>
                        <div v-for="prediction in item.predictions" :key="prediction.id" class="tr ">
                            <div v-for="(predValue, predKey) in prediction" :key="predKey" class="td" >
                              {{ displayValue(predKey, predValue) }}
                            </div>
                        </div>
            </div>
        </template>
    </div>
</div>


        <div v-else>No cases found.</div>
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
      return ['id', 'three_one_one_case_id', 'case_enquiry_id', 'ml_model_id', 'ml_model_name', 'prediction', 'prediction_date', 'predictionTimespan', 'predictionMaxThree'];
    }

  },
  methods: {
    updateSearchTerm(event) {
        this.searchTerm = event.target.value;
    },
    fetchCases() {
        this.$inertia.get('/cases', { searchTerm: this.searchTerm });
    },
    displayValue(predKey, predValue) {
      if (predKey === 'predictionTimespan') {
        return `First:${predValue[0]} Second:${predValue[1]} Third:${predValue[2]}`;
      } else if (predKey === 'predictionMaxThree') {
        return `First:${predValue[0]} Second:${predValue[1]} Third:${predValue[2]}`;
      } else if (predKey === 'prediction') {
        return "Predicted hours to close"
      } else {
      return predValue;
      }
    }
  }
};
</script>
<style scoped>
.scrollable {
  overflow-y: auto;
  height: 50px; /* replace with actual line-height */
}
</style>