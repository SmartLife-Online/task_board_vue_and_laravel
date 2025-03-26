<template>
    <div>
      <h1>Life-Area {{ idLifeArea }}</h1>
      <TaskBoardFormular :rows="formRows" :entry="lifeArea" @submitForm="handleFormSubmit" />
    </div>
  </template>
  
  <script lang="ts">
  import { ref, onMounted } from 'vue';
  import TaskBoardFormular from '../../components/TaskBoardFormular.vue';
  import { FormField } from '../../types/Form';
  import { LifeArea } from '../../types/ModelsForm';
  import { useRoute } from 'vue-router';
  import { useStore } from 'vuex';

  export default {
    name: 'LifeAreasEdit',
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
      const lifeArea = ref<LifeArea|undefined>(undefined);

      onMounted(async () => {
        await store.dispatch('fetchLifeArea', idLifeArea);
        
        lifeArea.value = store.getters.getLifeArea;
      });

      
      const handleFormSubmit = async formData => {
        await store.dispatch('submitLifeArea', {idLifeArea, formData});
      };

      return {
        formRows,
        idLifeArea,
        lifeArea,
        handleFormSubmit
      };
    },
  };
  </script>
  
  <style scoped>
  /* Add your styles here */
  </style>
