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
        <title>Contact</title>
        </Head>
    <main>
      <section>
        <h2>Connect with us on GitHub</h2>
        <p><a href="https://github.com/mindfulcoder49/" target="_blank" class="btn btn-primary">This is the GitHub</a> for the Boston Open Data Collaborative for Diversity, Equity, and Inclusion. All of our code is open source. Put in an issue and we will get back to you!</p>
        <!-- <button>View Details</button> -->
      </section>
    </main>
   </PageTemplate>
</template>