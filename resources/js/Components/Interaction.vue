<template>
    <div class="p-6 flex space-x-2">
        <div class="flex-1 max-w-full">
            <div class="flex justify-between items-center">
                <div class="m-4">
                    <p class="text-lg font-semibold">{{ interaction.prompt }}</p>
                    <p class="text-gray-600">{{ interaction.completion }}</p>
                    <small class="p-4 text-sm text-gray-500">Tokens: {{ interaction.prompt_tokens + interaction.completion_tokens }}</small>
                    <!--Add fields for input and output tokens and their prices-->
                    <small class="p-4 text-sm text-gray-500">Input Tokens: {{ interaction.prompt_tokens }}</small>
                    <small class="p-4 text-sm text-gray-500">Output Tokens: {{ interaction.completion_tokens }}</small>
                    <small class="p-4 text-sm text-gray-500">Input Token Price: ${{ interaction.prompt_token_price }}</small>
                    <small class="p-4 text-sm text-gray-500">Output Token Price: ${{ interaction.completion_token_price }}</small>
                    <small class="p-4 text-sm text-gray-500">Cost: ${{ interaction.total_cost }}</small>
                    <small class="p-4 text-sm text-gray-500">{{ new Date(interaction.created_at).toLocaleString() }}</small>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        interaction: Object,
    }
}
</script>
