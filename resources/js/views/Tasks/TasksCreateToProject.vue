<template>
  <div>
    <h1>Add Task to Project {{ idProject }}</h1>
    
    <input v-model="chatgtp_input" @change="checkChatgtpInput">
    <TaskBoardFormular :rows="formRows" :entry="task" @submitForm="handleFormSubmit" />
  </div>
</template>

<script lang="ts">
import { ref } from 'vue';
import TaskBoardFormular from '../../components/TaskBoardFormular.vue';
import { FormField } from '../../types/Form';
import { Task } from '../../types/ModelsForm';
import { useRoute } from 'vue-router';
import { useStore } from 'vuex';
import OpenAI from "openai";

export default {
  name: 'ProjectsCreate',
  components: {
    TaskBoardFormular
  },
  setup() {
    const route = useRoute();
    const store = useStore();
    
    const openai = new OpenAI({
      apiKey: import.meta.env.VITE_OPENAI_API_KEY,
      dangerouslyAllowBrowser: true,
      maxRetries: 0
    });
    
    const chatgtp_input = ref<string>('');
    const checkChatgtpInput = async () => {
      if(!chatgtp_input.value) return;

      console.log('ChatGPT input:', chatgtp_input.value);

      const response = await openai.responses.create({
          model: "gpt-4.1-mini",
          input: chatgtp_input.value,
      });

      console.log(response.output_text);
    };

    const formRows = ref<FormField[][]>([
      [
        { name: 'title', type: 'text', label: 'Title' },
        { name: 'description', type: 'text', label: 'Description' },
      ],
      [
        { name: 'points_upon_completion', type: 'text', label: 'Points upon completion' },
      ]
    ]);

    const idProject = parseInt(Array.isArray(route.params.id) ? route.params.id[0] : route.params.id);
    const task = ref<Task>({
      title: '',
      description: '',
      points_upon_completion: ''
    });
    
    const handleFormSubmit = async formData => {
      const data = await store.dispatch('submitStoreTaskToProject', {idProject, formData});
    };

    return {
      chatgtp_input,
      checkChatgtpInput,
      formRows,
      idProject,
      task,
      handleFormSubmit
    };
  },
};
</script>

<style scoped>
/* Add your styles here */
</style>
