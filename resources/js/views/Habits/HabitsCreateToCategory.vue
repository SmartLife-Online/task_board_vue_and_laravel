<template>
  <div>
    <h1>Add habit to category {{ idCategory }}</h1>
    <TaskBoardFormular :rows="formRows" :entry="habit" @submitForm="handleFormSubmit" />
  </div>
</template>

<script lang="ts">
import { ref } from 'vue';
import TaskBoardFormular from '../../components/TaskBoardFormular.vue';
import { FormField } from '../../types/Form';
import { Habit } from '../../types/ModelsForm';
import { useRoute } from 'vue-router';
import { useStore } from 'vuex';

export default {
  name: 'HabitsCreateToCategory',
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
        { name: 'points_per_completion', type: 'text', label: 'Points per completion' },
        { name: 'points_upon_completion', type: 'text', label: 'Points upon completion' },
      ]
    ]);

    const idCategory = parseInt(Array.isArray(route.params.id) ? route.params.id[0] : route.params.id);
    const habit = ref<Habit>({
      title: '',
      description: '',
      points_per_completion: ''
    });
    
    const handleFormSubmit = async formData => {
      const data = await store.dispatch('submitStoreHabitToCategory', {idCategory, formData});
    };

    return {
      formRows,
      idCategory,
      habit,
      handleFormSubmit
    };
  },
};
</script>

<style scoped>
/* Add your styles here */
</style>
