<script>
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

export default {
  name: 'PageTemplate',
  components: {
    Link,
    Head,
    GuestLayout,
    AuthenticatedLayout,
  },
  computed: {
    user() {
      return this.$page.props.auth.user;
    }
  },
};
</script>

<template>
  <div>
    <!-- Check if user is authenticated -->
    <AuthenticatedLayout v-if="user">
     
      <slot></slot>
    </AuthenticatedLayout>
    
    <GuestLayout v-else>
     <slot></slot>
  
     <footer class="pl-20">
       <p>&copy; 2023 BODC-DEI</p>
       <!-- add Vue Links to about and contact pages-->
        <p><Link href="/about">About</Link></p>
        <p><Link href="/contact">Contact</Link></p>
     </footer>
  </GuestLayout>
  </div>

  

 
 </template>
  

  