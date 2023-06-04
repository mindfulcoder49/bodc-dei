<script>

import PageTemplate from '../Components/PageTemplate.vue';

export default {

  components: {
    PageTemplate
  },
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
    <main class="maintable">
      <table>
        <tr v-if="cases.length">
          <th v-for="(value, key) in cases[0]" :key="key">{{ key }}</th>
        </tr>
        <tr v-for="item in cases" :key='item["case_enquiry_id"]'>
          <td v-for="(value, key) in item" :key="key">{{ value }}</td>
        </tr>
      </table>
    </main>
  </PageTemplate>
</template>
  

  