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
       <p>&copy; 2024 AlcivarTech</p>
        <h2>Connect with us on GitHub</h2>
        <p><a href="https://github.com/mindfulcoder49/bodc-dei" target="_blank" class="btn btn-primary">This is the GitHub</a></p>
     </footer>
  </GuestLayout>
  </div>

  

 
 </template>
  

  