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
      <title>About</title>
    </Head>
    <main>
      <section>
      <h2>The Boston Open Data Collaborative for Diversity, Equity, and Inclusion</h2>
        <p>Welcome to the website of the Boston Open Data Collaborative for Diversity Equity and Inclusion (BODC-DEI). You're the best for being here! We are a group of tech enthusiasts, data analysts, UX designers, quality assurance professionals, and Linux systems administrators who are passionate about using open data to create tools that serve the public in a fair and responsible way.</p>
        <p>Our group was founded with the vision of collaborating on projects based on the <a href="https://data.boston.gov/">250 datasets available on data.boston.gov</a>. We aim to create individual projects and showcase them as part of a collaborative group. We believe that by combining our diverse skills and fresh perspectives, we can create tools that are useful to everyone in the community.</p>
        <p>At BODC-DEI, we strongly believe in the principles of diversity, equity, and inclusion. We recognize the importance of considering these factors when dealing with public data about the public. We are committed to creating tools that serve everyone and present data in a fair and responsible way.</p>
        <p>We invite you to explore our website and learn more about our ongoing projects and initiatives. We also encourage you to get involved and join our group of collaborators. Whether you are a seasoned professional or just starting out in the tech industry, we welcome your contributions and ideas.</p>
      </section>
    </main>
</PageTemplate>
</template>