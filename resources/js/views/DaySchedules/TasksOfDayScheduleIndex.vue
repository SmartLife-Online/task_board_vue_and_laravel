<template>
  <div>
    <h1>Day 189</h1>
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
