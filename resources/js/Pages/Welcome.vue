<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PageTemplate from '../Components/PageTemplate.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import CrimeMapComponent from '../Components/CrimeMapComponent.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <PageTemplate>
      <Head>
        <title class="text-xl font-bold">Home</title>
      </Head>
        <section class="mb-8">
          <h1 class="text-2xl font-semibold mb-3">A Set of Boston AI Projects</h1>
          <!-- add "sponsored by" div element with logo from public/images/Logo-half-inverted.png-->
          <div class="flex mx-4">
            <a href="https://alcivartech.com" target="_blank" rel="noopener noreferrer">Sponsored by</a>
            <!-- add logo image only 100 px tall linked to alcivartech.com-->
            <a href="https://alcivartech.com" target="_blank" rel="noopener noreferrer">
              <img src="/images/Logo-half-inverted.png" alt="Alcivartech Logo" class="h-10 " />
            </a>
            
          </div>

        </section>  
        <section>
          <h2 class="text-2xl font-semibold mb-3">Featured Project</h2>
          <p class="mb-2">
            The featured project is the Boston App, a dynamic web application designed to provide users with an intuitive and interactive way to explore Boston's rich collection of open data. The app visualizes various datasets such as crime reports, 311 cases, and building permits, empowering users to uncover insights, patterns, and trends within the city. Whether you're a resident, researcher, or simply curious about Boston, the app offers a clean, user-friendly interface that makes navigating and interpreting the data easy for everyone. By leveraging modern web technologies, the Boston App ensures that users can filter, search, and engage with data on a highly interactive map.
          </p>
          <p class="mb-2">
            Click below to explore the Boston App and dive into a world of open data!
          </p>
          <Link href="/map">
            <img src="/images/bostonmap.png" alt="Boston Map" class="border border-gray-500 mx-auto" />
            <p class="text-center text-blue-600 font-bold mt-2 hover:underline">
              Click the map to explore the Boston App
            </p>
          </Link>
        </section>

        
    </PageTemplate>
  </template>
  
  <style scoped>
  #leafletAppDiv {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>