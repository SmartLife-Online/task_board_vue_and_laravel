<template>
  <div class="page">
    <div v-if="!modal" class="page-head">
      <div>
        <p class="kicker">Übersicht</p>
        <h1 class="page-title">Subtasks</h1>
      </div>
    </div>
    <div class="toolbar">
      <div>
        <label for="subtasks-filter" class="field-label">Filter</label>
        <select id="subtasks-filter" v-model="filterCompleted" @change="changeCompletedFilter">
          <option value="fetchNotCompletedSubtasks">
            Not completed
          </option>
          <option value="fetchCompletedSubtasks">
            Completed
          </option>
          <option value="fetchSubtasks">
            All
          </option>
          <option value="fetchDeletedSubtasks">
            Deleted
          </option>
        </select>
      </div>
    </div>
    <div v-if="subtasks === undefined" class="alert alert-info">
      Loading subtasks...
    </div>
    <div v-else-if="subtasks && filteredSubtasks.length !== 0" class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th v-for="thField in thFields" :key="thField.key">
              {{ thField.label }}
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="subtask in filteredSubtasks" :key="subtask.id">
            <td>{{ subtask.task }}</td>
            <td>{{ subtask.title }}</td>
            <td class="num">{{ subtask.points_upon_completion }}</td>
            <td>
              <span v-if="subtask.completed" class="state state-yes">Yes</span>
              <span v-else class="state state-no">No</span>
            </td>
            <td>
              <div class="cell-info">
                <i class="info-badge" :title="subtask.life_area">L</i>
                <i class="info-badge" :title="subtask.category">C</i>
                <i v-if="subtask.project" class="info-badge" :title="subtask.project">P</i>
                <i v-if="subtask.description" class="info-badge" :title="subtask.description">D</i>
              </div>
            </td>
            <td>
              <div class="row-actions">
                <button v-if="!subtask.completed" @click="completeSubtask(subtask)" class="btn btn-primary btn-sm">Complete</button>
                <router-link :to="'/subtasks/' + subtask.id" class="btn btn-secondary btn-sm">Edit</router-link>
                <router-link :to="'/tasks/' + subtask.task_id + '/add_subtask'" class="btn btn-ghost btn-sm">Add Subtasks to same Task</router-link>
                <button v-if="subtask.active" @click="deleteSubtask(subtask)" class="btn btn-danger btn-sm">Delete</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div v-else class="alert alert-warning">
      No subtasks found.
    </div>
  </div>
</template>

<script lang="ts">
import { ref, onMounted, watch } from 'vue';
import { useStore } from 'vuex';
import { ThField } from '../../types/Table';
import { Subtask } from '../../types/ModelsIndex';

export default {
  name: 'SubtasksIndex',
  props: {
    modal: {
      type: Boolean,
      default: false,
    },
    idTask: {
      type: Number,
      default: 0,
    },
  },
  computed: {
    filteredSubtasks() {
      return this.subtasks.filter(subtask => !subtask.removed);
    },
  },
  setup(props, { emit }) {
    const store = useStore();
    const thFields = ref<ThField[]>([
      {
        key: 'task',
        label: 'Task',
      },
      {
        key: 'title',
        label: 'Title',
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
    const filterCompleted = ref('fetchNotCompletedSubtasks');

    onMounted(async () => {
      await changeCompletedFilter();
    });

    watch(() => props.idTask, (newIdTask) => {
      if(newIdTask === 0) return;

      changeCompletedFilter();
    }, { deep: true });

    const changeCompletedFilter = async () => {
      if(props.modal && !props.idTask) return;

      await store.dispatch(filterCompleted.value, props.idTask || 0);
      
      subtasks.value = store.getters.getSubtasks;
    };

    const completeSubtask = async (subtask) => {
      await store.dispatch('completeSubtask', subtask);
    };

    const deleteSubtask = async (subtask) => {
      await store.dispatch('deleteSubtask', subtask);
    };

    return {
      thFields,
      subtasks,
      filterCompleted,
      changeCompletedFilter,
      completeSubtask,
      deleteSubtask
    };
  },
};
</script>

<style scoped>
/* Add your styles here */
</style>
