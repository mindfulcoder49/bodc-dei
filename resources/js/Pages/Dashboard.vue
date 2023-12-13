<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PageTemplate from '../Components/PageTemplate.vue';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="pageTemplate">
        <main>
        <section>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">AI Meetup: End-of-Year Edition</h2>
        </section>
      <!-- Event Description -->
    <section>
        <h3 class="text-2xl font-semibold mb-4">App Details</h3>
        <p class="mb-6">
          This app is hosted by the Boston Open Data Collaborative for Diversity, Equity, and Inclusion. Learn more about it at 
          <a href="https://bodc-dei.org" class="text-indigo-600 hover:text-indigo-800 underline">bodc-dei.org</a>. 
          BODC-DEI is a creation of Alex Alcivar. 
          <a href="https://www.linkedin.com/in/mindfulcoder49/" class="text-indigo-600 hover:text-indigo-800 underline">Connect with Alex Alcivar on LinkedIn</a>.
        </p>
    </section>

    <!-- Chirps App Instructions -->
    <section>
            <h4 class="text-xl font-medium">Using the Chirps App</h4>
            <h5 class="text-lg mt-2">A Public Introduction</h5>
            <p>Use the Chirps app to introduce yourself to the group. You can find the Chirps app in the menu above. Remember:</p>
            <ul class="list-disc list-inside pl-5">
              <li>All Chirps are private by default.</li>
              <li>Include <strong>#public</strong> to make your Chirp public.</li>
              <li>Example: "Hi, I'm Alex! I am trying to start a non-profit called the Boston Open Data Collaborative for Diversity Equity and Inclusion. I'm also a retail manager at Vineyard Vines. I have a B.A. in Computer Science and Political Science from Grinnell College. I am currently exploring the Boston AI and ML scene and open to the right opportunities. #public"</li>
            </ul>
    </section>

    <section>
            <h5 class="text-lg font-medium">Feedback for the Startups</h5>
            <p>Share your thoughts and feedback during the event using Chirps. You can find the Chirps app in the menu above. Remember:</p>
            <ul class="list-disc list-inside pl-5">
              <li>All Chirps are private by default, visible only to the event host.</li>
              <li>Include <strong>#public</strong> to share publicly.</li>
              <li>Example: "I really enjoyed the pitch for demo 3, I think the concept has the potential to generate a lot of revenue. #public"</li>
            </ul>
    </section>

    <section>
            <h5 class="text-lg font-medium">Voting</h5>
            <p>Use this app to vote on your favorite startup pitch. Your vote can be private or public.</p>
            <ul class="list-disc list-inside pl-5">
              <li>Include <strong>#vote</strong> to cast a private vote.</li>
              <li>Include <strong>#vote #public</strong> to cast a public vote.</li>
              <li>Change your vote at any time by voting again. Your most recent vote will be counted.</li>
            </ul>
    </section>

        <!-- Speakers and Schedule -->
        <section>
          <h4 class="text-xl font-medium mb-2">Schedule & Speakers</h4>
          <p class="mb-4">Join us for an evening of insights and networking:</p>
          <ul class="list-disc list-inside pl-5 space-y-2">
            <ul class="list-disc list-inside pl-5 space-y-2">
  <li>
    <strong>5:30 to 6:30 PM</strong> - Networking and Startup Showcase
    <ul class="list-disc list-inside pl-6 mt-2">
      <li>
        Jingying Huang with Moonshot, an app to help developers communicate. 
        <a class="text-indigo-600 hover:text-indigo-800 underline" href="https://www.linkedin.com/in/jingyinghuang/">Connect with Jingying Huang on LinkedIn</a>
      </li>
      <li>
        Henry Sabin with Convi.io, a service to create pitch videos for clients. 
        <a class="text-indigo-600 hover:text-indigo-800 underline" href="https://www.linkedin.com/in/henry-sabin/">Connect with Henry Sabin on LinkedIn</a>
      </li>
      <li>
        Jawad Iqbal with liberate-labs.com showcasing a project on knowledge graph generation using Gen AI. 
        <a class="text-indigo-600 hover:text-indigo-800 underline" href="https://www.linkedin.com/in/jawad-iqbal/">Connect with Jawad Iqbal on LinkedIn</a>
      </li>
    </ul>
  </li>
  <li>
    <strong>6:30 to 7:00 PM</strong> - Startup Presentations
  </li>
  <li>
    <strong>7:00 to 7:25 PM</strong> - Dean Andrews from InterSystems discusses the global innovation program in Fintech, Healthcare, and Supply Chain Technology. 
    <a class="text-indigo-600 hover:text-indigo-800 underline" href="https://www.linkedin.com/in/iamdeanandrews/">Connect with Dean Andrews on LinkedIn</a>
  </li>
  <li>
    <strong>7:30 to 7:55 PM</strong> - Sid Probstein from Swirl on how AI can transform knowledge work. 
    <a class="text-indigo-600 hover:text-indigo-800 underline" href="https://www.linkedin.com/in/sidprobstein/">Connect with Sid Probstein on LinkedIn</a>
  </li>
  <li>
    <strong>8:00 to 8:15 PM</strong> - Awards Ceremony
  </li>
  <li>
    <strong>8:15 to 8:45 PM</strong> - Roya Kandalan explores the balance between AI advancements and ethical concerns. 
    <a class="text-indigo-600 hover:text-indigo-800 underline" href="https://www.linkedin.com/in/roya-nrz/">Connect with Roya Kandalan on LinkedIn</a>
  </li>
</ul>

          </ul>
    </section>
</main>
</div>
    </AuthenticatedLayout>
</template>


