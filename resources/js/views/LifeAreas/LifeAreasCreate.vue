<template>
    <div>
      <h1>Add Life-Area to user {{ idUser }}</h1>
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
    name: 'LifeAreasIndex',
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
      const idUser = parseInt(Array.isArray(route.params.id) ? route.params.id[0] : route.params.id);
      const lifeArea = ref<LifeArea>({
        title: '',
        description: ''
      });

      onMounted(async () => {
        await store.dispatch('fetchLifeArea', idUser);
        
        lifeArea.value = store.getters.getLifeArea;
      });

      
      const handleFormSubmit = async formData => {
        await store.dispatch('submitLifeArea', {idUser, formData});
      };

      return {
        formRows,
        idUser,
        lifeArea,
        handleFormSubmit
      };
    },
  };
  </script>
  
  <style scoped>
  /* Add your styles here */
  </style>
