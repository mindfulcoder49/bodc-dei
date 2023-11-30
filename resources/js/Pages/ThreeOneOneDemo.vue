
<template>
  <PageTemplate>
    <Head>
      <title>311 Code Demo</title>
    </Head>
    <section>
    <iframe 
      :src="notebookUrl" 
      style="width:100%; height:80vh; border:none;"
    ></iframe>
  </section>
  </PageTemplate>
</template>

<script>
import PageTemplate from '../Components/PageTemplate.vue';
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
    PageTemplate, Head, Link
  },
  data() {
    return {
      // URL to the HTML file
      notebookUrl: '/boston_311_tutorial.html'
    };
  },
  computed: {
  },
  methods: {
  },
};
</script>
