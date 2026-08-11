<template>
    <div class="page">
      <div class="page-head">
        <div>
          <p class="kicker">Neu anlegen</p>
          <h1 class="page-title">Add Category to Life-Area {{ idLifeArea }}</h1>
        </div>
      </div>
      <TaskBoardFormular :rows="formRows" :entry="category" @submitForm="handleFormSubmit" />
    </div>
  </template>
  
  <script lang="ts">
  import { ref } from 'vue';
  import TaskBoardFormular from '../../components/TaskBoardFormular.vue';
  import { FormField } from '../../types/Form';
  import { Category } from '../../types/ModelsForm';
  import { useRoute } from 'vue-router';
  import { useStore } from 'vuex';

  export default {
    name: 'CategoriesCreate',
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
        ]
      ]);

      const idLifeArea = parseInt(Array.isArray(route.params.id) ? route.params.id[0] : route.params.id);
      const category = ref<Category>({
        title: '',
        description: '',
        points_multiplier_in_percent: ''
      });
      
      const handleFormSubmit = async formData => {
        const data = await store.dispatch('submitStoreCategory', {idLifeArea, formData});
      };

      return {
        formRows,
        idLifeArea,
        category,
        handleFormSubmit
      };
    },
  };
  </script>
  
  <style scoped>
  /* Add your styles here */
  </style>
