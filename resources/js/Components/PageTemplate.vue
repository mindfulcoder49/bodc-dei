<script>
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';

export default {
  name: 'PageTemplate',
  components: {
    Link,
    Head,
    GuestLayout,
  },
};
</script>

<template>
<GuestLayout>

  <header class="bg-white p-4">
  <div class="container mx-auto flex justify-between items-center">
    <h1 class="text-2xl font-semibold">BODC-DEI</h1>
    <nav>
      <ul class="flex space-x-4">
        <li><Link :href="route('home')" class="text-base text-gray-700 hover:text-gray-900">Home</Link></li>
        <li><Link :href="route('about')" class="text-base text-gray-700 hover:text-gray-900">About</Link></li>
        <li><Link :href="route('projects')" class="text-base text-gray-700 hover:text-gray-900">Projects</Link></li>
        <li><Link :href="route('contact')" class="text-base text-gray-700 hover:text-gray-900">Contact</Link></li>
        <!--Add login and register links-->
        <li><Link :href="route('login')" class="text-base text-gray-700 hover:text-gray-900">Login</Link></li>
        <li><Link :href="route('register')" class="text-base text-gray-700 hover:text-gray-900">Register</Link></li>
      </ul>
    </nav>
  </div>
</header>

     <slot></slot>
  
     <footer>
       <p>&copy; 2023 BODC-DEI</p>
     </footer>
    </GuestLayout>
 </template>
  

  