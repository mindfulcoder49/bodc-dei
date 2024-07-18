<template>
  <PageTemplate>
    <CrimeMapComponent />
  </PageTemplate>
</template>

<script setup>
import CrimeMapComponent from '../Components/CrimeMapComponent.vue';
import PageTemplate from '@/Components/PageTemplate.vue';
</script>

<style scoped>
#map {
  height: 70vh;
}
</style>
