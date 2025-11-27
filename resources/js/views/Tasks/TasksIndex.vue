<template>
  <div>
    <SubtaksOfTaskModal :title="'Subtasks of &quot;' + subtasksOfTaskModalNameTask + '&quot;'" :idTask="subtasksOfTaskModalIdTask" @modalClosed="onModalClosed" />
    <h1>Tasks</h1>
    <select v-model="filterCompleted" style="margin: 8px;" @change="changeCompletedFilter">
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
    <div v-if="tasks === undefined" class="alert alert-info">
      Loading tasks...
    </div>
    <table v-else-if="filteredTasks.length !== 0" class="table table-bordered table-striped">
      <th v-for="thField in thFields" :key="thField.key">
        {{ thField.label }}
      </th>
      <tr v-for="task in filteredTasks" :key="task.id">
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
          <button v-if="!task.completed" @click="completeTask(task)" class="btn btn-primary" style="margin: 8px;">
            Complete
          </button>
          <button id="modalButton" type="button" class="btn btn-primary" style="margin: 8px;" @click="subtasksOfTaskModalIdTask = task.id;subtasksOfTaskModalNameTask = task.title">
            Subtasks
          </button>
          <router-link :to="'/tasks/' + task.id" class="btn btn-primary" style="margin: 8px;">Edit</router-link>
          <router-link :to="'/tasks/' + task.id + '/add_subtask'" class="btn btn-primary" style="margin: 8px;">Add subtask</router-link>
          <router-link :to="'/categories/' + task.category_id + '/add_task'" class="btn btn-primary" style="margin: 8px;">+ to same category</router-link>
          <router-link v-if="task.project_id" :to="'/projects/' + task.project_id + '/add_task'" class="btn btn-primary" style="margin: 8px;">+ to same project</router-link>
          <button v-if="task.active" @click="recalcTask(task)" class="btn btn-primary" style="margin: 8px;">Recalc</button> 
          <button v-if="task.active" @click="deleteTask(task)" class="btn btn-primary" style="margin: 8px;">Delete</button>
        </td>
      </tr>
    </table>
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
