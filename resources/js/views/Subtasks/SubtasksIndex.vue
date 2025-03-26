<template>
  <div>
    <h1>Subtasks</h1>
    <select v-model="filterCompleted" style="margin: 8px;" @change="changeCompletedFilter">
      <option value="fetchNotCompltedSubtasks">
        Not completed
      </option>
      <option value="fetchCompltedSubtasks">
        Completed
      </option>
      <option value="fetchSubtasks">
        All
      </option>
    </select>
    <table id="tableComponent" class="table table-bordered table-striped">
      <th v-for="thField in thFields" :key="thField.key">
        {{ thField.label }}
      </th>
      <tr v-for="subtask in subtasks" :key="subtask.id">
        <td>{{ subtask.task }}</td>
        <td>{{ subtask.title }}</td>
        <td>{{ subtask.points_upon_completion }}</td>
        <td>
          <span v-if="subtask.completed" style="color:green">Yes</span>
          <span v-else style="color:red">No</span>
        </td>
        <td>
          <i class="btn btn-primary" :title="subtask.life_area">L</i>
          <i class="btn btn-primary" :title="subtask.category">C</i>
          <i v-if="subtask.project" class="btn btn-primary" :title="subtask.project">P</i>
          <i v-if="subtask.description" class="btn btn-primary" :title="subtask.description">D</i>
        </td>
        <td>
          <button v-if="!subtask.completed" @click="completeSubtask(subtask)" class="btn btn-primary" style="margin: 8px;">Complete</button>
          <router-link :to="'/subtasks/' + subtask.id" class="btn btn-primary" style="margin: 8px;">Edit</router-link>
          <router-link :to="'/tasks/' + subtask.task_id + '/add_subtask'" class="btn btn-primary" style="margin: 8px;">Add Subtask to same Task</router-link>
        </td>
      </tr>
    </table>
  </div>
</template>

<script lang="ts">
import { ref, onMounted } from 'vue';
import { useStore } from 'vuex';
import { ThField } from '../../types/Table';
import { Subtask } from '../../types/ModelsIndex';

export default {
  name: 'SubtasksIndex',
  setup() {
    const store = useStore();
    const thFields = ref<ThField[]>([
      {
        key: 'title',
        label: 'Title',
      },
      {
        key: 'task',
        label: 'Task',
      },
      {
        key: 'points_upon_completion',
        label: 'Points upon completion',
      },
      {
        key: 'completed',
        label: 'Completed',
      },
      {
        key: 'info',
        label: 'Info',
      },
      {
        key: 'options',
        label: 'Options',
      }
    ]);
    const subtasks = ref<Subtask[]|undefined>(undefined);
    const filterCompleted = ref('fetchNotCompltedSubtasks');

    onMounted(async () => {
      await changeCompletedFilter();
    });

    const changeCompletedFilter = async () => {
      await store.dispatch(filterCompleted.value);
      
      subtasks.value = store.getters.getSubtasks;
    };

    const completeSubtask = async (subtask) => {
      await store.dispatch('completeSubtask', subtask);
    };

    return {
      thFields,
      subtasks,
      filterCompleted,
      changeCompletedFilter,
      completeSubtask
    };
  },
};
</script>

<style scoped>
/* Add your styles here */
</style>
