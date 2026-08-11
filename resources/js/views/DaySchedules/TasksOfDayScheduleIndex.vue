<template>
  <div class="page">
    <div class="page-head">
      <div>
        <p class="kicker">Day-Schedule</p>
        <h1 class="page-title">Day 189</h1>
      </div>
    </div>
    <TasksIndex :idDaySchedule="idDaySchedule" />
  </div>
</template>
  
  <script lang="ts">
  import { useRoute } from 'vue-router';
  import TasksIndex from '../Tasks/TasksIndex.vue';

  export default {
    name: 'TasksOfDayScheduleIndex',
    components: {
      TasksIndex,
    },
    setup() {
      const route = useRoute();

      const idDaySchedule = parseInt(Array.isArray(route.params.idDaySchedule) ? route.params.idDaySchedule[0] : route.params.idDaySchedule);

      return {
        idDaySchedule
      };
    },
  };
  </script>
  
  <style scoped>
  /* Add your styles here */
  </style>
