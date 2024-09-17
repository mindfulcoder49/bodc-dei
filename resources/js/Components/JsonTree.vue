<template>
    <div class="json-tree">
      <ul class="list-none pl-5">
        <li v-for="(value, key) in json" :key="key" class="">
          <span @click="toggleCollapse(key)" class="cursor-pointer font-semibold">
            {{ key }}:
            <!-- Display a preview of the value -->
            <template v-if="isObjectOrArray(value)">
              <span class="text-blue-500">
                [{{ collapsed[key] ? '...' : (Array.isArray(value) ? 'Array' : 'Object') }}]
              </span>
            </template>
            <template v-else>
              <span class="text-gray-700">{{ formatValue(value) }}</span>
            </template>
          </span>
          <!-- Display nested objects or arrays only if not collapsed -->
          <div v-if="!collapsed[key] && isObjectOrArray(value)" class="ml-5">
            <JsonTree :json="value" v-if="isObjectOrArray(value)" />
          </div>
        </li>
      </ul>
    </div>
  </template>
  
  <script>
  export default {
    name: 'JsonTree',
    props: {
      json: {
        type: Object,
        required: true
      }
    },
    data() {
      return {
        collapsed: {} // Track which fields are collapsed
      };
    },
    methods: {
      isObjectOrArray(value) {
        return typeof value === 'object' && value !== null;
      },
      formatValue(value) {
        if (typeof value === 'string') {
          return `"${value}"`; // Add quotes around strings
        }
        return value;
      },
      toggleCollapse(key) {
        // Toggle collapsed state in Vue 3
        this.collapsed[key] = !this.collapsed[key];
      }
    }
  };
  </script>
  
  <style scoped>
  /* You can remove the CSS file, as everything is handled by Tailwind */
  </style>
  