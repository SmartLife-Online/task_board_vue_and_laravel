<template>
    <div>
      <h1>Task {{ idTask }}</h1>
      <TaskBoardFormular :rows="formRows" :entry="task" @submitForm="handleFormSubmit" />
    </div>
  </template>
  
  <script lang="ts">
  import { ref, onMounted } from 'vue';
  import TaskBoardFormular from '../../components/TaskBoardFormular.vue';
  import { FormField } from '../../types/Form';
  import { Task } from '../../types/ModelsForm';
  import { useRoute } from 'vue-router';
  import { useStore } from 'vuex';

  export default {
    name: 'TasksEdit',
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
      const idTask = parseInt(Array.isArray(route.params.id) ? route.params.id[0] : route.params.id);
      const task = ref<Task|undefined>(undefined);

      onMounted(async () => {
        await store.dispatch('fetchTask', idTask);
        
        task.value = store.getters.getTask;
      });

      const handleFormSubmit = async formData => {
        await store.dispatch('submitEditTask', {idTask, formData});
      };

      return {
        formRows,
        idTask,
        task,
        handleFormSubmit
      };
    },
  };
  </script>
  
  <style scoped>
  /* Add your styles here */
  </style>
