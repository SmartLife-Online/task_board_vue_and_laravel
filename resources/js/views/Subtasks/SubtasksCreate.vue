<template>
  <div>
    <h1>Add Subtask to Task {{ idCategory }}</h1>
    <TaskBoardFormular :rows="formRows" :entry="subtask" @submitForm="handleFormSubmit" />
  </div>
</template>

<script lang="ts">
import { ref } from 'vue';
import TaskBoardFormular from '../../components/TaskBoardFormular.vue';
import { FormField } from '../../types/Form';
import { Subtask } from '../../types/ModelsForm';
import { useRoute } from 'vue-router';
import { useStore } from 'vuex';

export default {
  name: 'SubtasksCreate',
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
        { name: 'points_upon_completion', type: 'text', label: 'Points upon completion' },
      ]
    ]);

    const idTask = parseInt(Array.isArray(route.params.id) ? route.params.id[0] : route.params.id);
    const subtask = ref<Subtask>({
      title: '',
      description: '',
      points_upon_completion: ''
    });
    
    const handleFormSubmit = async formData => {
      const data = await store.dispatch('submitStoreSubtask', {idTask, formData});
    };

    return {
      formRows,
      idTask,
      subtask,
      handleFormSubmit
    };
  },
};
</script>

<style scoped>
/* Add your styles here */
</style>
