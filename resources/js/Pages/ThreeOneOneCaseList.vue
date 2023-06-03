<script>
export default {
  props: {
    cases: {
      type: Array,
      required: true,
    },
  },
};
</script>

<template>
    <div>
      <h1>ThreeOneOneCase List</h1>
      <ul>
        <li v-for="item in cases" :key='item["case_enquiry_id"]'>
          {{ item['case_enquiry_id'] }}
          <!-- Add other fields you want to display -->
        </li>
      </ul>
    </div>
</template>
  

  