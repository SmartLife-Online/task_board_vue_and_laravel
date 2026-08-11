<template>
  <div class="page">
    <SubtaksOfTaskModal :title="'Subtasks of &quot;' + subtasksOfTaskModalNameTask + '&quot;'" :idTask="subtasksOfTaskModalIdTask" @modalClosed="onModalClosed" />
    <div v-if="!modal && !idDaySchedule" class="page-head">
      <div>
        <p class="kicker">Übersicht</p>
        <h1 class="page-title">Tasks</h1>
      </div>
    </div>
    <div class="toolbar">
      <div>
        <label for="tasks-filter" class="field-label">Filter</label>
        <select id="tasks-filter" v-model="filterCompleted" @change="changeCompletedFilter">
          <option value="fetchNotCompletedTasks">
            Not completed
          </option>
          <option value="fetchCompletedTasks">
            Completed
          </option>
          <option value="fetchTasks">
            All
          </option>
          <option value="fetchDeletedTasks">
            Deleted
          </option>
        </select>
      </div>
    </div>
    <div v-if="tasks === undefined" class="alert alert-info">
      Loading tasks...
    </div>
    <div v-else-if="filteredTasks.length !== 0" class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th v-for="thField in thFields" :key="thField.key">
              {{ thField.label }}
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="task in filteredTasks" :key="task.id">
            <td>{{ task.category }}</td>
            <td>{{ task.project }}</td>
            <td>{{ task.title }}</td>
            <td class="num">{{ task.points }}</td>
            <td class="num">{{ task.points_upon_completion }}</td>
            <td>
              <span v-if="task.completed" class="state state-yes">Yes</span>
              <span v-else class="state state-no">No</span>
            </td>
            <td>
              <div class="cell-info">
                <i class="info-badge" :title="task.life_area">L</i>
                <i v-if="task.description" class="info-badge" :title="task.description">D</i>
              </div>
            </td>
            <td>
              <div class="row-actions">
                <button v-if="!task.completed" @click="completeTask(task)" class="btn btn-primary btn-sm">
                  Complete
                </button>
                <button type="button" class="btn btn-secondary btn-sm" @click="subtasksOfTaskModalIdTask = task.id;subtasksOfTaskModalNameTask = task.title">
                  Subtasks
                </button>
                <router-link :to="'/tasks/' + task.id" class="btn btn-secondary btn-sm">Edit</router-link>
                <router-link :to="'/tasks/' + task.id + '/add_subtask'" class="btn btn-ghost btn-sm">Add subtask</router-link>
                <router-link :to="'/categories/' + task.category_id + '/add_task'" class="btn btn-ghost btn-sm">+ to same category</router-link>
                <router-link v-if="task.project_id" :to="'/projects/' + task.project_id + '/add_task'" class="btn btn-ghost btn-sm">+ to same project</router-link>
                <button v-if="task.active" @click="recalcTask(task)" class="btn btn-ghost btn-sm">Recalc</button>
                <button v-if="task.active" @click="deleteTask(task)" class="btn btn-danger btn-sm">Delete</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div v-else class="alert alert-warning">
      No tasks found.
    </div>
  </div>
</template>

<script lang="ts">
import { ref, onMounted, watch } from 'vue';
import { useStore } from 'vuex';
import { ThField } from '../../types/Table';
import { Task } from '../../types/ModelsIndex';
import SubtaksOfTaskModal from '../../components/modals/SubtaksOfTaskModal.vue';

export default {
  name: 'TasksIndex',
  components: {
    SubtaksOfTaskModal,
  },
  props: {
    modal: {
      type: Boolean,
      default: false,
    },
    idProject: {
      type: Number,
      default: 0,
    },
    idDaySchedule: {
      type: Number,
      default: 0,
    },
  },
  computed: {
    filteredTasks() {
      return this.tasks.filter(task => !task.removed);
    },
  },
  setup(props) {
    const store = useStore();
    const subtasksOfTaskModalIdTask = ref<number>(0);
    const subtasksOfTaskModalNameTask = ref<string>('');
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
    const filterCompleted = ref('fetchNotCompletedTasks');

    watch(() => props.idProject, (newIdProject) => {
      if(newIdProject === 0) return;

      changeCompletedFilter();
    }, { deep: true });

    const changeCompletedFilter = async () => {
      if(props.modal && !props.idProject) return;
      
      const idProject = props.idProject || 0;
      const idDaySchedule = props.idDaySchedule || 0;

      await store.dispatch(filterCompleted.value, {idProject, idDaySchedule});
      
      tasks.value = store.getters.getTasks;
    };

    onMounted(async () => {
      await changeCompletedFilter();
    });

    const completeTask = async (task) => {
      await store.dispatch('completeTask', task);
    };

    const onModalClosed = () => {
      subtasksOfTaskModalIdTask.value = 0;
      subtasksOfTaskModalNameTask.value = '';
    };

    const recalcTask = async (task) => {
      await store.dispatch('recalcTask', task);
    };


    const deleteTask = async (task) => {
      await store.dispatch('deleteTask', task);
    };

    return {
      thFields,
      subtasksOfTaskModalIdTask,
      subtasksOfTaskModalNameTask,
      onModalClosed,
      tasks,
      filterCompleted,
      changeCompletedFilter,
      completeTask,
      recalcTask,
      deleteTask
    };
  },
};
</script>

<style scoped>
/* Add your styles here */
</style>
