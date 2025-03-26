<template>
  <div>
    <h1>Add Task to Category {{ idCategory }}</h1>
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

export default {
  name: 'ProjectsCreate',
  components: {
    TaskBoardFormular
  },
  setup() {
    const route = useRoute();
    const store = useStore();

    const formRows = ref<FormField[][]>([
      [
        { name: 'title', type: 'text', label: 'Title' },
        { name: 'description', type: 'text', label: 'Description' },
      ],
      [
        { name: 'points_upon_completion', type: 'text', label: 'Points upon completion' },
      ]
    ]);

    const idCategory = parseInt(Array.isArray(route.params.id) ? route.params.id[0] : route.params.id);
    const task = ref<Task>({
      title: '',
      description: '',
      points_upon_completion: ''
    });
    
    const handleFormSubmit = async formData => {
      const data = await store.dispatch('submitStoreTaskToCategory', {idCategory, formData});
    };

    return {
      formRows,
      idCategory,
      task,
      handleFormSubmit
    };
  },
};
</script>

<style scoped>
/* Add your styles here */
</style>
