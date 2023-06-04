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
    <main>
      <section>
        <h2>About Page Coming Soon!</h2>
        <p>This page is under construction, thank you for your interest!</p>
        <!-- <button>View Details</button> -->
      </section>
    </main>
</PageTemplate>
</template>