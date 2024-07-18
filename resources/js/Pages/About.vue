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
      <!-- Section description with a focus on clarity and engagement, styled with Tailwind CSS -->
<section class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Empowering Communities with Open Technology</h2>
    <p class="text-gray-600 mb-4">
        At the heart of our initiative lies a commitment to foster the adoption of open data and open-source solutions. Our aim is to champion diversity, equity, and inclusion within Boston's vibrant AI and ML landscape and extend these values globally.
    </p>
    <p class="text-gray-600 mb-4">
        We envision a thriving community of practice where open-source AI and ML technologies become catalysts for positive change, directly benefiting those in marginalized communities.
    </p>
    <p class="text-gray-600 mb-4 font-medium">Our strategic objectives include:</p>
    <ul class="list-disc pl-5 space-y-2">
        <li class="text-gray-600">Engaging with marginalized groups in Boston and beyond to understand and act upon their unique challenges.</li>
        <li class="text-gray-600">Selecting impactful open-source tools tailored to meet these challenges effectively.</li>
        <li class="text-gray-600">Leveraging open data to inform and guide our technology-driven interventions.</li>
        <li class="text-gray-600">Fostering a culture of collaboration and continuous learning among technology advocates and developers.</li>
        <li class="text-gray-600">Creating accessible educational resources on open technologies to empower community members.</li>
        <li class="text-gray-600">Promoting sustainable development through transparent and inclusive technology practices.</li>
    </ul>
</section>

    </main>
</PageTemplate>
</template>