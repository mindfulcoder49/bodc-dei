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
        <h2>Boston 311 Preliminary Results</h2>
        <p>
            The 311 Project dashboard is still under construction, but we're happy to share what we have so far.
        </p>
        <p><Link href="/cases" class="btn btn-primary">This page shows a searchable list of the cases and their outcome predictions.</Link></p>
        <p><Link href="/scatter" class="btn btn-primary">This page shows a geographical scatterplot of 311 case location.</Link> </p>
        <p><Link href="/311models" class="btn btn-primary">This page shows information about the model and its accuracy.</Link> </p>
        <!-- <button>View Details</button> -->
      </section>
    </main>
  </PageTemplate>
</template>