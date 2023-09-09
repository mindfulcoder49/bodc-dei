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
            <Link href="/cases" class="btn btn-primary">You can view some of the preliminary data being produced in the Boston 311 project here.</Link> The 311 Project dashboard is still under construction, but we're happy to share what we have so far.
        </p>
        <!-- <button>View Details</button> -->
      </section>
    </main>
  </PageTemplate>
</template>