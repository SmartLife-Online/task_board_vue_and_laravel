<template>
  <div>
    <h1>Add Day-Schedual</h1>
    <TaskBoardFormular :rows="formRows" :entry="daySchedule" @submitForm="handleFormSubmit" />
  </div>
</template>

<script lang="ts">
import { ref } from 'vue';
import TaskBoardFormular from '../../components/TaskBoardFormular.vue';
import { FormField } from '../../types/Form';
import { DaySchedule } from '../../types/ModelsForm';
import { useRoute } from 'vue-router';
import { useStore } from 'vuex';

export default {
  name: 'DayScheduleCreate',
  components: {
    TaskBoardFormular
  },
  setup() {
    const route = useRoute();
    const store = useStore();

    const formRows = ref<FormField[][]>([
      [
        { name: 'day', type: 'text', label: 'Day' },
        { name: 'points_upon_success', type: 'text', label: 'Points upon success' },
      ],
      [
        { name: 'title', type: 'text', label: 'Title' },
        { name: 'description', type: 'text', label: 'Description' },
      ]
    ]);

    const daySchedule = ref<DaySchedule>({
      day: 0,
      title: '',
      description: '',
      points_upon_success: 0
    });
    
    const handleFormSubmit = async formData => {
      const data = await store.dispatch('submitEditDaySchedule', {formData});
    };

    return {
      formRows,
      daySchedule,
      handleFormSubmit
    };
  },
};
</script>

<style scoped>
/* Add your styles here */
</style>
