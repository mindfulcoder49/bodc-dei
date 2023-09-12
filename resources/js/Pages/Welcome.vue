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
        <h2>The Boston Open Data Collaborative for Diversity, Equity, and Inclusion</h2>
        <p>Welcome to the website of the Boston Open Data Collaborative for Diversity Equity and Inclusion (BODC-DEI). We are a group of tech enthusiasts, data analysts, UX designers, quality assurance professionals, and Linux systems administrators who are passionate about using open data to create tools that serve the public in a fair and responsible way.</p>
        <p>Our group was founded with the vision of collaborating on projects based on the <a href="https://data.boston.gov/">250 datasets available on data.boston.gov</a>. We aim to create individual projects and showcase them as part of a collaborative group. We believe that by combining our diverse skills and fresh perspectives, we can create tools that are useful to everyone in the community.</p>
        <p>At BODC-DEI, we strongly believe in the principles of diversity, equity, and inclusion. We recognize the importance of considering these factors when dealing with public data about the public. We are committed to creating tools that serve everyone and present data in a fair and responsible way.</p>
        <p>We invite you to explore our website and learn more about our ongoing projects and initiatives. We also encourage you to get involved and join our group of collaborators. Whether you are a seasoned professional or just starting out in the tech industry, we welcome your contributions and ideas.</p>
      </section>
      <section>
        <h2>Coming Soon: Boston 311 Project</h2>
        <p>The Boston 311 project is a machine learning project focused on the 311 service requests dataset, which is available on data.boston.gov. The project aims to develop a tool that can predict the likelihood of a 311 service request being resolved within a certain timeframe, based on various factors such as the type of request, the location of the request, and the time of day.</p>

        <p>By analyzing the data from the 311 service requests, the project team hopes to gain insights into the patterns and trends of service requests in the city of Boston. The ultimate goal is to develop a tool that can assist city officials in allocating resources and prioritizing service requests more effectively.</p>
          
        <p>The team is using Jupyter notebooks and Google Colaboratory to analyze the data and develop predictive models, and they are planning to build a web application to showcase their findings.</p>
        <!-- <button>View Details</button> -->
      </section>
      <section>
        <h2>Coming Soon: "The Boston App"</h2>
        <p>The proposed project is an app for residents of Boston to stay informed about city operations in their area that affect their daily lives. The app will provide information about city services, such as trash pickup and street cleaning, as well street construction, building permits, and health inspections</p>
        <!-- <button>View Details</button> -->
      </section>
    </main>
</PageTemplate>

</template>
