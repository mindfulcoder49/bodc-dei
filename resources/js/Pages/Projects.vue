<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PageTemplate from '../Components/PageTemplate.vue';


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
    }
});
</script>

<template>


  <PageTemplate>
    <Head>
      <title>Projects</title>
    </Head>
    <main>
      <section>
        <h2 class="text-2xl font-semibold mb-3">Boston 311 Preliminary Results</h2>
        <p>
            The 311 Project dashboard is still under construction, but we're happy to share what we have so far.
        </p>
        <div class="space-y-4">
          <div class="border p-4 rounded shadow flex items-center space-x-4 bg-gray-100">
  <Link href="/cases" class="inline-block px-6 py-3 bg-gray-800 text-gray-100 font-semibold rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 transition ease-in-out duration-150">
    Case List
  </Link>
  <p class="text-sm text-gray-600">Explore case outcomes and predictions.</p>
</div>

<div class="border p-4 rounded shadow flex items-center space-x-4 bg-gray-100">
  <Link href="/scatter" class="inline-block px-6 py-3 bg-gray-800 text-gray-100 font-semibold rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 transition ease-in-out duration-150">
    Case Map
  </Link>
  <p class="text-sm text-gray-600">Visualize case locations with a scatterplot.</p>
</div>

<div class="border p-4 rounded shadow flex items-center space-x-4 bg-gray-100">
  <Link href="/311models" class="inline-block px-6 py-3 bg-gray-800 text-gray-100 font-semibold rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 transition ease-in-out duration-150">
    Model Info
  </Link>
  <p class="text-sm text-gray-600">Review the predictive model details and accuracy metrics.</p>
</div>

<div class="border p-4 rounded shadow flex items-center space-x-4 bg-gray-100">
  <Link href="/311demo" class="inline-block px-6 py-3 bg-gray-800 text-gray-100 font-semibold rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 transition ease-in-out duration-150">
    Notebook Demo
  </Link>
  <p class="text-sm text-gray-600">Engage with a tutorial on utilizing the Boston 311 package for predictive modeling.</p>
</div>

</div>

        <!-- <button>View Details</button> -->
      </section>
      <section>
        <h2 class="text-2xl font-semibold mb-3">The Boston App</h2>
        <p>
            The Boston App is still under construction, but we're happy to share what we have so far.
        </p>
        <p><Link href="/thebostonappdemo" class="btn btn-primary">This page shows examples of the kind of reports this app might generate, and a python notebook with proof of concept code that generates those example reports.</Link></p>
        <!-- <button>View Details</button> -->
      </section>
    </main>
  </PageTemplate>
</template>