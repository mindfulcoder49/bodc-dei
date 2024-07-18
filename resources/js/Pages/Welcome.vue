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
          <h1 class="text-2xl font-semibold mb-3">Boston Open Data Collaboration for Diversity Equity and Inclusion</h1>
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
        <h2 class="text-2xl font-semibold mb-3">What is BODC-DEI?</h2>
        <p class="mb-2">
            BODC-DEI stands for Boston Open Data Collaboration for Diversity, Equity, and Inclusion. The goal is to foster the exploration of open data and open-source solutions to champion diversity, equity, and inclusion within Boston's vibrant AI and ML landscape.
        </p>
        </section>
        <section>
        <h2 class="text-2xl font-semibold mb-3">Featured Project</h2>
        <p class="mb-2">
            The featured project is the Boston AI Crime Map. This project is a web application that allows users to explore crime data in Boston. The data is sourced from the Boston Police Department and is updated daily. The application allows users to filter the data by various criteria, such as date, time, and type of crime. The application also features a map view that displays the location of each crime on a map of Boston.
            <Link href="/crime-map" class="text-blue-500">Explore the Boston AI Crime Map</Link>
        </p>
        <CrimeMapComponent />
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