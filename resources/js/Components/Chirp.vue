<template>
    <div class="p-6 flex space-x-2">
        <!-- ...existing content... -->
        
        <div class="flex-1 max-w-full">
            <div class="flex justify-between items-center">
                <div>
                    <span class="text-gray-800">{{ chirp.user.name }}</span>
                    <small class="ml-2 text-sm text-gray-600">{{ new Date(chirp.created_at).toLocaleString() }}</small>
                </div>
                <!-- Delete Button -->
                <button @click="deleteChirp" class="text-red-600 hover:text-red-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <p class="mt-4 text-lg text-gray-900 overflow-auto">{{ chirp.message }}</p>
        </div>
    </div>
</template>

<script>
export default {
    props: ['chirp'],
    methods: {
        deleteChirp() {
            if (confirm('Are you sure you want to delete this chirp?')) {
                this.$inertia.delete(`/chirps/${this.chirp.id}`);
            }
        }
    }
}
</script>
