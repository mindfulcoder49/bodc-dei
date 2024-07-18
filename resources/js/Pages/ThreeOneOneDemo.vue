
<template>
  <PageTemplate>
    <Head>
      <title>311 Code Demo</title>
    </Head>
    <div class="border p-4 rounded shadow items-center space-4 bg-gray-100 overflow-wrap">
    <Link href="/cases" class="inline-block px-6 py-3 bg-gray-500 text-gray-100 font-semibold rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 transition ease-in-out duration-150 m-2">
    Case List
  </Link>

  <Link href="/scatter" class="inline-block px-6 py-3 bg-gray-500 text-gray-100 font-semibold rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 transition ease-in-out duration-150 m-2">
    Case Map
  </Link>
  <Link href="/311models" class="inline-block px-6 py-3 bg-gray-500 text-gray-100 font-semibold rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 transition ease-in-out duration-150 m-2">
    Model Info
  </Link>
  <Link href="/311demo" class="inline-block px-6 py-3 bg-gray-500 text-gray-100 font-semibold rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 transition ease-in-out duration-150 m-2">
    Notebook Demo
  </Link>
</div>
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
