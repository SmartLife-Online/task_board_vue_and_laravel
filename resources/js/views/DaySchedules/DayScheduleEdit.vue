<template>
  <div>
    <h1>Day-Schedual {{ idDaySchedule }}</h1>
    <TaskBoardFormular :rows="formRows" :entry="daySchedule" @submitForm="handleFormSubmit" />
  </div>
</template>

<script lang="ts">
import { ref, onMounted } from 'vue';
import TaskBoardFormular from '../../components/TaskBoardFormular.vue';
import { FormField } from '../../types/Form';
import { DaySchedule } from '../../types/ModelsForm';
import { useRoute } from 'vue-router';
import { useStore } from 'vuex';

export default {
  name: 'DayScheduleEdit',
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

    const idDaySchedule = parseInt(Array.isArray(route.params.id) ? route.params.id[0] : route.params.id);
    const daySchedule = ref<DaySchedule|undefined>(undefined);
    
    onMounted(async () => {
      await store.dispatch('fetchDaySchedule', idDaySchedule);
      
      daySchedule.value = store.getters.getDaySchedule;
    });
    
    const handleFormSubmit = async formData => {
      const data = await store.dispatch('submitEditDaySchedule', {idDaySchedule, formData});
    };

    return {
      formRows,
      idDaySchedule,
      daySchedule,
      handleFormSubmit
    };
  },
};
</script>

<style scoped>
/* Add your styles here */
</style>
