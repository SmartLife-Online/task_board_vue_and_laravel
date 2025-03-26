<template>
  <div>
    <h1>Tasks</h1>
    <select v-model="filterCompleted" style="margin: 8px;" @change="changeCompletedFilter">
      <option value="fetchNotCompltedTasks">
        Not completed
      </option>
      <option value="fetchCompltedTasks">
        Completed
      </option>
      <option value="fetchTasks">
        All
      </option>
    </select>
    <table id="tableComponent" class="table table-bordered table-striped">
      <th v-for="thField in thFields" :key="thField.key">
        {{ thField.label }}
      </th>
      <tr v-for="task in tasks" :key="task.id">
        <td>{{ task.category }}</td>
        <td>{{ task.project }}</td>
        <td>{{ task.title }}</td>
        <td>{{ task.points }}</td>
        <td>{{ task.points_upon_completion }}</td>
        <td>
          <span v-if="task.completed" style="color:green">Yes</span>
          <span v-else style="color:red">No</span>
        </td>
        <td>
          <i class="btn btn-primary" :title="task.life_area">L</i>
          <i v-if="task.description" class="btn btn-primary" :title="task.description">D</i>
        </td>
        <td>
          <button v-if="!task.completed" @click="completeTask(task)" class="btn btn-primary" style="margin: 8px;">Complete</button>
          <router-link :to="'/tasks/' + task.id" class="btn btn-primary" style="margin: 8px;">Edit</router-link>
          <router-link :to="'/tasks/' + task.id + '/add_subtask'" class="btn btn-primary" style="margin: 8px;">Add subtask</router-link>
          <router-link :to="'/categories/' + task.category_id + '/add_task'" class="btn btn-primary" style="margin: 8px;">+ to same category</router-link>
          <router-link :to="'/projects/' + task.project_id + '/add_task'" class="btn btn-primary" style="margin: 8px;">+ to same project</router-link>
        </td>
      </tr>
    </table>
  </div>
</template>

<script lang="ts">
import { ref, onMounted } from 'vue';
import { useStore } from 'vuex';
import { ThField } from '../../types/Table';
import { Task } from '../../types/ModelsIndex';

export default {
  name: 'TasksIndex',
  setup() {
    const store = useStore();
    const thFields = ref<ThField[]>([
      {
        key: 'category',
        label: 'Category',
      },
      {
        key: 'project',
        label: 'Project',
      },
      {
        key: 'title',
        label: 'Title',
      },
      {
        key: 'points',
        label: 'Points',
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
    const tasks = ref<Task[]|undefined>(undefined);
    const filterCompleted = ref('fetchNotCompltedTasks');

    const changeCompletedFilter = async () => {
      await store.dispatch(filterCompleted.value);
      
      tasks.value = store.getters.getTasks;
    };

    onMounted(async () => {
      await changeCompletedFilter();
    });

    const completeTask = async (task) => {
      await store.dispatch('completeTask', task);
    };

    return {
      thFields,
      tasks,
      filterCompleted,
      changeCompletedFilter,
      completeTask
    };
  },
};
</script>

<style scoped>
/* Add your styles here */
</style>
