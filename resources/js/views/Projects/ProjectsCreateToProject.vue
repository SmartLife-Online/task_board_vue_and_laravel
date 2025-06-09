<template>
    <div>
      <h1>Add Project to Project {{ idProject }}</h1>
      <TaskBoardFormular :rows="formRows" :entry="project" @submitForm="handleFormSubmit" />
    </div>
  </template>
  
  <script lang="ts">
  import { ref } from 'vue';
  import TaskBoardFormular from '../../components/TaskBoardFormular.vue';
  import { FormField } from '../../types/Form';
  import { Project } from '../../types/ModelsForm';
  import { useRoute } from 'vue-router';
  import { useStore } from 'vuex';

  export default {
    name: 'ProjectsCreateToProject',
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
          { name: 'points_multiplier_in_percent', type: 'text', label: 'Points-Multiplier (in percent)' },
          { name: 'points_upon_completion', type: 'text', label: 'Points upon completion' },
        ]
      ]);

      const idProject = parseInt(Array.isArray(route.params.id) ? route.params.id[0] : route.params.id);
      
      const project = ref<Project>({
        title: '',
        description: '',
        points_multiplier_in_percent: '',
        points_upon_completion: ''
      });
      
      const handleFormSubmit = async formData => {
        const data = await store.dispatch('submitStoreProjectToProject', {idProject, formData});
      };

      return {
        formRows,
        idProject,
        project,
        handleFormSubmit
      };
    },
  };
  </script>
  
  <style scoped>
  /* Add your styles here */
  </style>
