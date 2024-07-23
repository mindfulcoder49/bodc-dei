<template>
    <div class="crime-card p-4 border rounded-md shadow-md mb-4 bg-white hover:bg-gray-100 transition duration-300 ease-in-out">
      <div class="flex flex-col md:flex-row md:justify-between md:items-center">
        <div class="mb-2 md:mb-0">
          <h4 class="text-xl font-semibold mb-1">Incident Number: {{ crimeData.incident_number }}</h4>
          <p class="text-gray-600">Offense: {{ crimeData.offense_description }}</p>
        </div>
        <div class="text-sm text-gray-500">
          <p>District: {{ crimeData.district }}</p>
          <!-- use year, month, day, and hour to show date-->
          <p>Date: {{ formatDate(crimeData) }}</p>
        </div>
      </div>
      <div class="mt-2 text-sm text-gray-600">
        <p>Offense Code: {{ crimeData.offense_code }}</p>
        <p>Reporting Area: {{ crimeData.reporting_area }}</p>
        <p>Shooting: {{ crimeData.shooting ? 'Yes' : 'No' }}</p>
        <p>Street: {{ crimeData.street }}</p>
        <p>Location: {{ crimeData.location }}</p>
        <p>Offense Category: {{ crimeData.offense_category }}</p>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'CrimeData',
    props: {
      crimeData: {
        type: Object,
        required: true,
      },
    },
    methods: {
      formatDate(crimeData) {
        //get from month day hour and year properties
        const { month, hour, year } = crimeData;
        return `${month}/${year} ${hour}:00`;
      },
    },
  };
  </script>
  
  <style scoped>
  .crime-card {
    transition: all 0.3s ease-in-out;
  }
  
  .crime-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
  }
  </style>
  