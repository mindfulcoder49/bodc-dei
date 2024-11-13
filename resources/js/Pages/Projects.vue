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
      
      <section class="mb-8">
        <h1 class="text-2xl font-semibold mb-3">BODC-DEI Projects</h1>
        <p class="mb-3">These projects are free or open source, focused on educating people on AI, and as accessible to all skill levels as possible. The projects span various technologies including python, javascript, php, SQL, Machine Learning, and Natural Language Processing.</p>
      </section>

      <!-- Add a section about the Crime Map-->
      <section>
        <h2 class="text-2xl font-semibold mb-3">Boston AI Crime Map</h2>
        <p>
            The Boston Crime Map is a web application that allows users to explore crime data in Boston. The data is sourced from the Boston Police Department and is updated daily. The application allows users to filter the data by various criteria, such as date, time, and type of crime. The application also features a map view that displays the location of each crime on a map of Boston.
        </p>
        <p><Link href="/crime-map" class="btn btn-primary">Explore the Boston Crime Map</Link></p>
      </section>
      <section>
        <h2 class="text-2xl font-semibold mb-3">Boston 311 Machine Learning Python Package</h2>
        <p class="mb-5">
            The 311 Project is a demonstration of a python package that can be used to easily train machine learning models with Boston 311 data. <a href="https://github.com/mindfulcoder49/Boston_311"> The package is open source and the repository is on Github here.</a> Below are links to pages on this site that demonstrate the package in action.
        </p>
        <div class="space-y-4">
          <div class="border p-4 rounded shadow flex items-center space-x-4 bg-gray-100">
  <Link href="/cases" class="inline-block px-6 py-3 bg-gray-500 text-gray-100 font-semibold rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 transition ease-in-out duration-150">
    Case List
  </Link>
  <p class="text-sm text-gray-600">Explore case outcomes and predictions.</p>
</div>

<div class="border p-4 rounded shadow flex items-center space-x-4 bg-gray-100">
  <Link href="/scatter" class="inline-block px-6 py-3 bg-gray-500 text-gray-100 font-semibold rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 transition ease-in-out duration-150">
    Case Map
  </Link>
  <p class="text-sm text-gray-600">Visualize case locations with a scatterplot.</p>
</div>

<div class="border p-4 rounded shadow flex items-center space-x-4 bg-gray-100">
  <Link href="/311models" class="inline-block px-6 py-3 bg-gray-500 text-gray-100 font-semibold rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 transition ease-in-out duration-150">
    Model Info
  </Link>
  <p class="text-sm text-gray-600">Review the predictive model details and accuracy metrics.</p>
</div>

<div class="border p-4 rounded shadow flex items-center space-x-4 bg-gray-100">
  <Link href="/311demo" class="inline-block px-6 py-3 bg-gray-500 text-gray-100 font-semibold rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 transition ease-in-out duration-150">
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
            The Boston App is still under construction, but the idea is to make localized information available to Boston residents.
        </p>
        <p><Link href="/dashboard" class="btn btn-primary">The BODC-DEI Dashboard</Link> right now has a form that allows you to get a crime report for a location in Boston. The report is generated using a Google Cloud Function that uses two data sets on data.boston.gov, one for crime reports, and one to get the coordinates for street addresses, and it then sends that data to chatGPT for summary and translation.  The function is written in Python and uses the Google Cloud Platform to run the function. The function is called from the form on the dashboard and the report is displayed on the page.</p>
        <Link href="/dashboard" class="btn btn-primary">Check out the BODC-DEI Crime Report Generator</Link>
      </section>
    </main>
  </PageTemplate>
</template>