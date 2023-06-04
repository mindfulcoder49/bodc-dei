<script>
import PageTemplate from '../Components/PageTemplate.vue';

export default {
  props: {
    cases: {
      type: Array,
      required: true,
    },
  }
};
</script>

<template>
  <PageTemplate>
    <div>
      <h1>ThreeOneOneCase List</h1>
      <table>
        <tr><th v-for="(key, value) in cases[0]">{{ value }}</th></tr>
        <tr v-for=" item in cases" :key='item["case_enquiry_id"]'>
          
          <td v-for="(key, value) in item">{{ key }}</td>
          <!-- Add other fields you want to display -->
        </tr>
      </table>
    </div>
  </PageTemplate>
</template>
  

  