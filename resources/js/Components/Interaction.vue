<template>
    <div class="p-6 flex space-x-2">
        <!-- ...existing content... -->
        
        <div class="flex-1 max-w-full">
            <div class="flex justify-between items-center">
                <div>
                    <span class="text-gray-800">{{ chirp.timestamp }}</span>
                    <small class="ml-2 text-sm text-gray-600">{{ new Date(interaction.created_at).toLocaleString() }}</small>
                </div>
                <!-- Delete Button -->
                <button @click="editInteraction" class="text-red-600 hover:text-red-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <p class="mt-4 text-lg text-gray-900 overflow-auto">Interaction goes here</p>
        </div>
    </div>
</template>

<script>
export default {
    props: ['chirp'],
    methods: {
        editInteraction() {
            if (confirm('Edit this interaction?')) {
                this.$inertia.edit(`/interaction/${this.interaction.id}`);
            }
        }
    }
}
</script>
