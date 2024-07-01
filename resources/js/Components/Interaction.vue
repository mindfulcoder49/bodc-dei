<template>
    <div class="p-6 flex space-x-2">
        <div class="flex-1 max-w-full">
            <div class="flex justify-between items-center">
                <div class="m-4 ">
                    <p class="text-lg font-semibold">Prompt {{ new Date(interaction.created_at).toLocaleString() }}</p>
                    <textarea class="w-full p-2 mt-2 border border-gray-300 rounded-lg" readonly>{{ interaction.prompt }}</textarea>
                    <p>Completion</p>
                    <textarea class="w-full p-2 mt-2 border border-gray-300 rounded-lg" readonly>{{ interaction.completion }}</textarea>
                    <div class="flex">
                        <small class="p-4 text-sm text-gray-500">Model: {{ interaction.model_name }}</small>
                        <small class="p-4 text-sm text-gray-500">Tokens: {{ interaction.prompt_tokens + interaction.completion_tokens }}</small>
                        <small class="p-4 text-sm text-gray-500">Input Tokens: {{ interaction.prompt_tokens }}</small>
                        <small class="p-4 text-sm text-gray-500">Output Tokens: {{ interaction.completion_tokens }}</small>
                        <small class="p-4 text-sm text-gray-500">Input Token Price: ${{ interaction.prompt_token_price }}</small>
                        <small class="p-4 text-sm text-gray-500">Output Token Price: ${{ interaction.completion_token_price }}</small>
                        <small class="p-4 text-sm text-gray-500">Cost: ${{ interaction.total_cost }}</small>
                        <small class="p-4 text-sm text-gray-500">{{ new Date(interaction.created_at).toLocaleString() }}</small>
                    </div>
                </div>
                <button @click="loadInteraction" class="btn-info">Load Interaction</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        interaction: Object,
    },
    emits: ['loadInteraction'],
    methods: {
        loadInteraction() {
            this.$emit('loadInteraction', this.interaction);
        }
    }
}
</script>

<style scoped>
.btn-info {
    background-color: #17a2b8;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 0.25rem;
}
</style>
