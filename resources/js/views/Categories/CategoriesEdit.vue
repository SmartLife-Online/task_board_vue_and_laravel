<template>
    <div class="page">
      <div class="page-head">
        <div>
          <p class="kicker">Bearbeiten</p>
          <h1 class="page-title">Category {{ idCategory }}</h1>
        </div>
      </div>
      <TaskBoardFormular :rows="formRows" :entry="category" @submitForm="handleFormSubmit" />
    </div>
  </template>
  
  <script lang="ts">
  import { ref, onMounted } from 'vue';
  import TaskBoardFormular from '../../components/TaskBoardFormular.vue';
  import { FormField } from '../../types/Form';
  import { Category } from '../../types/ModelsForm';
  import { useRoute } from 'vue-router';
  import { useStore } from 'vuex';

  export default {
    name: 'CategoriesEdit',
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
      const idCategory = parseInt(Array.isArray(route.params.id) ? route.params.id[0] : route.params.id);
      const category = ref<Category|undefined>(undefined);

      onMounted(async () => {
        await store.dispatch('fetchCategory', idCategory);
        
        category.value = store.getters.getCategory;
      });

      
      const handleFormSubmit = async formData => {
        await store.dispatch('submitEditCategory', {idCategory, formData});
      };

      return {
        formRows,
        idCategory,
        category,
        handleFormSubmit
      };
    },
  };
  </script>
  
  <style scoped>
  /* Add your styles here */
  </style>
