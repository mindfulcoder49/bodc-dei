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
    },
});
</script>

<template>

<PageTemplate>
  <Head>
    <title>Home</title>
  </Head>
    <main>
    
      <section>
        <h2>Welcome to BODC-DEI</h2>
      </section>  
      <section>
        <h2>Coming Soon: Boston 311 Project</h2>
        <p>The Boston 311 project is a machine learning project focused on the 311 service requests dataset, which is available on data.boston.gov. The project aims to develop a tool that can predict the likelihood of a 311 service request being resolved within a certain timeframe, based on various factors such as the type of request, the location of the request, and the time of day.</p>

        <p>By analyzing the data from the 311 service requests, the project team hopes to gain insights into the patterns and trends of service requests in the city of Boston. The ultimate goal is to develop a tool that can assist city officials in allocating resources and prioritizing service requests more effectively.</p>
          
        <p>The team is using Jupyter notebooks and Google Colaboratory to analyze the data and develop predictive models, and they are planning to build a web application to showcase their findings.</p>

        <p>See the code demo here:</p>
        <p><Link href="/311demo" class="btn btn-primary">This page has a Jupyter notebook showing a demo of how the Boston 311 python package can easily be used to train several types of predictive models, including a Neural Network.</Link> </p>
        <!-- <button>View Details</button> -->
      </section>
      <section>
        <h2>Coming Soon: "The Boston App"</h2>
        <p>The proposed project is an app for residents of Boston to stay informed about city operations in their area that affect their daily lives. The app will provide information about city services, such as trash pickup and street cleaning, as well street construction, building permits, and health inspections</p>
        <p>See the code demo here:</p>
        <p><Link href="/thebostonappdemo" class="btn btn-primary">This page shows examples of the kind of reports this app might generate, and a python notebook with proof of concept code that generates those example reports.</Link></p>
        <!-- <button>View Details</button> -->
      </section>
    </main>
</PageTemplate>

</template>
