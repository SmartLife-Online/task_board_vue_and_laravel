<template>
    <div class="page">
      <div class="page-head">
        <div>
          <p class="kicker">Bearbeiten</p>
          <h1 class="page-title">Subtask {{ idSubtask }}</h1>
        </div>
      </div>
      <TaskBoardFormular :rows="formRows" :entry="subtask" @submitForm="handleFormSubmit" />
    </div>
  </template>
  
  <script lang="ts">
  import { ref, onMounted } from 'vue';
  import TaskBoardFormular from '../../components/TaskBoardFormular.vue';
  import { FormField } from '../../types/Form';
  import { Subtask } from '../../types/ModelsForm';
  import { useRoute } from 'vue-router';
  import { useStore } from 'vuex';

  export default {
    name: 'SubtasksEdit',
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
        ],
        [
          { name: 'completed', type: 'checkbox', label: 'Completed?' },
        ]
      ]);
      const idSubtask = parseInt(Array.isArray(route.params.id) ? route.params.id[0] : route.params.id);
      const subtask = ref<Subtask|undefined>(undefined);

      onMounted(async () => {
        await store.dispatch('fetchSubtask', idSubtask);
        
        subtask.value = store.getters.getSubtask;
      });

      
      const handleFormSubmit = async formData => {
        await store.dispatch('submitEditSubtask', {idSubtask, formData});
      };

      return {
        formRows,
        idSubtask,
        subtask,
        handleFormSubmit
      };
    },
  };
  </script>
  
  <style scoped>
  /* Add your styles here */
  </style>
