<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PageTemplate from '../Components/PageTemplate.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import LeafletMap from '@/Components/LeafletMap.vue';

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
        <title class="text-xl font-bold">Home</title>
      </Head>
        <section class="mb-8">
          <h1 class="text-2xl font-semibold mb-3">Boston Open Data Collaboration for Diversity Equity and Inclusion</h1>
        </section>  
        <section>
        <h2 class="text-2xl font-semibold mb-3">What is BODC-DEI?</h2>
        <p class="mb-2">
            BODC-DEI stands for Boston Open Data Collaboration for Diversity, Equity, and Inclusion. It is the main principle and organizing ethos for my community projects. The goal is to foster the exploration of open data and open-source solutions to champion diversity, equity, and inclusion within Boston's vibrant AI and ML landscape.
        </p>
        </section>
        <section>
        <h2 class="text-2xl font-semibold mb-3">Featured Project</h2>
          <div id="leafletMapDiv">
            <LeafletMap />
          </div>
        </section>
        <section>
        <h2 class="text-2xl font-semibold mb-3">BODC-DEI Groups</h2>
        <p class="mb-2">
            There are currently two BODC DEI Groups, both hosted on Meetup.com. One geared more towards developers and one geared more towards other professionals that will be heavily impacted by AI and ML technologies:
            <ul>
                <li><a href="https://www.meetup.com/north-shore-ai-developers/">North Shore AI Developers</a></li>
                <li><a href="https://www.meetup.com/boston-ai-group-for-health-law-education/">Boston AI Group for Healthcare Law and Education</a></li>
            </ul>
        </p>
        </section>
        <section>
            <h2 class="text-2xl font-semibold mb-3">
              <a href="https://www.meetup.com/north-shore-ai-developers/">North Shore AI Developers</a> 
            </h2>
            <div>
              
              <p class="mb-2">The North Shore AI, Machine Learning, and Coding Meetup Group is based in Everett, MA. It is a vibrant community of tech enthusiasts, professionals, and hobbyists interested in exploring the exciting realms of artificial intelligence, machine learning, and coding.</p>

              <p class="mb-2">The vast majority of AI Developer meetups in Boston happen either at an educational insitution, or in Kendall Sqaure in Cambridge. The group fills that gap by having in person meetups in Everett and other locations north of Boston. This group also has regular virtual meetups that each focus on one of the latest cutting edge open source AI tools</p>

              <p class="mb-2">Check out our past events here: <a href="https://www.meetup.com/north-shore-ai-developers/events/past/">North Shore AI Developers Past Events</a></p>
              </div>
         
        </section>
        <section>
          <h2 class="text-2xl font-semibold mb-3">
                <a href="https://www.meetup.com/boston-ai-group-for-health-law-education/">Boston AI Group for Healthcare Law and Education</a>
            </h2>
            <p class="mb-2">The Boston AI Group for Healthcare Law and Education is a community designed for people who are interested in examining AI's effects on healthcare, law, and education. This group has had two successful in person meetups at the Microsoft New England Research and Development Center in Kendall Sqaure. While the other meetup is about bringing developer to Everett MA, a city that otherwise would have none, this meetup is about bringing non-technical professionals to Kendall Square to feel they can be a part of this AI revolution.</p>

            <p class="mb-2">Check out our past events here: <a href="https://www.meetup.com/boston-ai-group-for-health-law-education/events/past/">Boston AI Group for Healthcare Law and Education Past Events</a></p>
        </section>
        
    </PageTemplate>
  </template>
  
  <style scoped>
  #leafletAppDiv {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>