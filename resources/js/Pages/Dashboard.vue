<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">AI Meetup: End-of-Year Edition</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Event Description -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">
                    <h3 class="text-lg font-medium">App Details</h3>
                    <p>This app is hosted by the Boston Open Data Collaborative for Diversity, Equity, and Inclusion, showcasing our collective efforts in using open data responsibly. Learn more about us and our initiatives at <a href="https://bodc-dei.org" class="text-blue-600 hover:underline">bodc-dei.org</a>.</p>

                    <!-- Chirps App Instructions -->
                    <h4 class="mt-4 font-medium">Using the Chirps App</h4>
                    <p>Share your thoughts and feedback during the event using Chirps. You can find the Chirps app in the menu above. Remember:</p>
                    <ul class="list-disc list-inside">
                        <li>All Chirps are private by default, visible only to the event host.</li>
                        <li>Include <strong>#public</strong> to share publicly.</li>
                        <li>Example: "Impressed by the innovation at demo 3! #public"</li>
                    </ul>
                    <!-- use this app to vote, your vote can be private or public -->
                    <h4 class="mt-4 font-medium">Voting</h4>
                    <p>Use this app to vote on your favorite startup pitch. Your vote can be private or public.</p>
                    <ul class="list-disc list-inside">
                        <li>Include <strong>#vote</strong> to cast a private vote.</li>
                        <li>Include <strong>#vote #public</strong> to cast a public vote.</li>
                        <li>Change your vote at any time by voting again. Your most recent vote will be counted.</li>
                    </ul>

                    <!-- Speakers and Schedule -->
                    <h4 class="mt-4 font-medium">Schedule & Speakers</h4>
                    <p>Join us for an evening of insights and networking:</p>
                    <ul class="list-disc list-inside">
                        <li><strong>5:30 to 6:30 PM</strong> - Networking and Startup Showcase</li>
                        <li><strong>6:30 to 6:45 PM</strong> - Introduction</li>
                        <li><strong>6:45 to 7:15 PM</strong> - Dean Andrews from InterSystems discusses the global innovation program in Fintech, Healthcare, and Supply Chain Technology.</li>
                        <li><strong>7:20 to 7:50 PM</strong> - Sid Probstein from Swirl on how AI can transform knowledge work.</li>
                        <li><strong>7:50 to 8:00 PM</strong> - Awards Ceremony</li>
                        <li><strong>8:00 to 8:30 PM</strong> - Roya Kandalan explores the balance between AI advancements and ethical concerns.</li>
                    </ul>

                    
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>


