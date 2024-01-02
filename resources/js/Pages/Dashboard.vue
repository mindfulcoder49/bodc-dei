<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PageTemplate from '../Components/PageTemplate.vue';

</script>

<template>
    <Head title="Dashboard" />

    <PageTemplate>
        <div class="pageTemplate">
        <main>
        <section>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Welcome to your BODC-DEI Dashboard</h2>
        </section>
      <!-- Event Description -->
    <section>
        <h3 class="text-2xl font-semibold mb-4">App Details</h3>
        <p class="mb-6">
          This app is hosted by the Boston Open Data Collaborative for Diversity, Equity, and Inclusion. 
        </p>
    </section>

    <!-- Chirps App Instructions -->
    <section>
            <h4 class="text-xl font-medium">Using the Chirps App</h4>
            <h5 class="text-lg mt-2">A Public Introduction</h5>
            <p>Use the Chirps app to introduce yourself. You can find the Chirps app in the menu above. Remember:</p>
            <ul class="list-disc list-inside pl-5">
              <li>All Chirps are private by default.</li>
              <li>Include <strong>#public</strong> to make your Chirp public.</li>
            </ul>
    </section>

    <section>
            <h5 class="text-lg font-medium">Public Chirps</h5>
            <p>Share your thoughts and feedback during the event using Chirps. You can find the Chirps app in the menu above. Remember:</p>
            <ul class="list-disc list-inside pl-5">
              <li>All Chirps are private by default, visible only to the event host.</li>
              <li>Include <strong>#public</strong> to share publicly.</li>
            </ul>
    </section>
</main>
</div>
</PageTemplate>
</template>


