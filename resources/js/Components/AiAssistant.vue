<template>
  <div class="ai-assistant border border-gray-700 rounded-lg shadow-lg p-4 bg-gray-900/25 relative z-2">
      <div ref="chatHistory" class="p-2 bg-transparent chat-history max-h-[69vh] rounded-lg overflow-y-auto mb-4 scrollbar-thin scrollbar-thumb-gray-500 scrollbar-track-gray-800">
          <div class="assistant-message text-gray-800 bg-gradient-to-r from-gray-200 to-gray-300 p-4 mr-1 rounded-lg inline-block max-w-[95%] float-left mb-2 text-left">
              <p>Hi! I'm the Boston App AI Assistant, based on OpenAI's GPT-4o-mini model. How can I help you today?</p>
          </div>
          <div v-for="(message, index) in messages" :key="index" class="message-item mb-2 clear-both">
              <p v-if="message.role === 'user'" class="user-message text-gray-800 bg-gradient-to-r from-blue-200 to-blue-400 p-4 ml-2 rounded-lg inline-block max-w-[95%] float-right mb-2 text-right">
                  {{ message.content }}
              </p>
              <div v-if="message.role === 'assistant'" class="assistant-message text-gray-800 bg-gradient-to-r from-gray-200 to-gray-300 p-4 mr-1 rounded-lg inline-block max-w-[95%] float-left mb-2 text-left">
                  <div v-html="renderMarkdown(message.content)"></div>
              </div>
          </div>
          <div v-if="loading" class="loading-indicator text-gray-800 mt-4 italic">
              <p>...</p>
          </div>
      </div>

      <div class="suggested-prompts flex flex-col gap-2 mb-4 float-right">
          <button v-for="(prompt, index) in suggestedPrompts" :key="index" 
                  @click="insertPrompt(prompt)" 
                  class="bg-gradient-to-r from-blue-400 to-blue-600 text-white p-2 rounded-lg cursor-pointer">
              {{ prompt }}
          </button>
      </div>

      <form @submit.prevent="handleResponse" class="text-lg">
          <textarea
              v-model="form.message"
              placeholder="Type your message..."
              class="w-full p-3 rounded-lg border-none bg-gradient-to-r from-blue-200 to-blue-400 text-gray-800 text-lg"
              rows="2"
          ></textarea>

          <button type="submit" class="send-button cursor-pointer rounded-lg border border-white bg-gradient-to-r from-gray-200 to-gray-300 text-gray-800 p-4 mt-4 w-full">
              Send
          </button>
      </form>
  </div>
</template>

<style scoped>
.scrollbar-thin {
scrollbar-width: thin;
}
.scrollbar-thumb-gray-500 {
scrollbar-color: #6b7280 #1f2937;
}
</style>

<script setup>
import { reactive, ref, nextTick, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import markdownit from 'markdown-it';
import markdownItLinkAttributes from 'markdown-it-link-attributes';

const props = defineProps({
context: {
  type: Array,
  default: () => [],
},
});

const md = markdownit({
html: true,
linkify: true,
typographer: true
});

md.use(markdownItLinkAttributes, {
attrs: {
  target: "_blank",
  rel: "noopener",
},
});

const form = reactive(useForm({
message: '',
errors: {}
}));

const messages = ref([]);
const loading = ref(false);
const chatHistory = ref(null);
const context = ref(props.context); // Store context

// Suggested prompts
const suggestedPrompts = ref([
"Summarize all the events on this report for me"
]);

const scrollToBottom = () => {
nextTick(() => {
  if (chatHistory.value) {
    chatHistory.value.scrollTop = chatHistory.value.scrollHeight;
  }
});
};

// Insert prompt into the textarea
const insertPrompt = (prompt) => {
form.message = prompt;
handleResponse();
suggestedPrompts.value = suggestedPrompts.value.filter((item) => item !== prompt);
};

const handleResponse = async () => {
if (form.message.trim() === '') return;

messages.value.push({ role: 'user', content: form.message });
loading.value = true;

const userMessage = form.message;
form.message = '';

scrollToBottom(); // Scroll after user message is added

const requestBody = {
  message: userMessage,
  history: messages.value,
  context: JSON.stringify(context.value)
};

const response = await fetch(route('ai.assistant'), {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
  },
  body: JSON.stringify(requestBody)
});

const reader = response.body.getReader();
const decoder = new TextDecoder();
let assistantMessage = '';
let chunk;

messages.value.push({ role: 'assistant', content: '' }); // Prepare to append the assistant message

while (!(chunk = await reader.read()).done) {
  assistantMessage += decoder.decode(chunk.value, { stream: true });

  const assistantMessageIndex = messages.value.findLastIndex((message) => message.role === 'assistant');
  messages.value[assistantMessageIndex].content = assistantMessage;

  scrollToBottom();
}

loading.value = false;
};

const renderMarkdown = (content) => {
return md.render(content);
};

//watch for changes in the context and update the context
watch(() => props.context, (newContext) => {
  context.value = newContext;
});
</script>
