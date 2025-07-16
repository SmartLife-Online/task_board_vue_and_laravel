<template>
  <div>
    <h1>
      Day-Schedules
      <router-link :to="'/day_schedules/create'" class="btn btn-primary" style="margin-left: 12px;">
        Create new Day-Schedule
      </router-link>
    </h1>
    <select v-model="filterCompleted" style="margin: 8px;" @change="changeCompletedFilter">
      <option value="fetchInProgressDaySchedules">
        In progress
      </option>
      <option value="fetchPendingDaySchedules">
        Pending
      </option>
      <option value="fetchSuccessfulDaySchedules">
        Successful
      </option>
      <option value="fetchFailedDaySchedules">
        Failed
      </option>
      <option value="fetchDaySchedules">
        All
      </option>
      <option value="fetchDeletedDaySchedules">
        Deleted
      </option>
    </select>
    <div v-if="daySchedules === undefined" class="alert alert-info">
      Loading day-schedules...
    </div>
    <table v-else-if="daySchedules.length !== 0" class="table table-bordered table-striped">
      <th v-for="thField in thFields" :key="thField.key">
        {{ thField.label }}
      </th>
      <tr v-for="daySchedule in filteredDaySchedules" :key="daySchedule.id">
        <td>{{ daySchedule.day }}</td>
        <td>{{ daySchedule.title }}</td>
        <td>{{ daySchedule.description }}</td>
        <td>{{ daySchedule.points_upon_success }}</td>
        <td>
          <span :style="'color:' + statusColor(daySchedule)">{{ statusText(daySchedule) }}</span>
        </td>
        <td>
          <button v-if="daySchedule.status_id === 0" @click="activateDaySchedule(daySchedule)" class="btn btn-primary" style="margin: 8px;">
            Activate
          </button>
          <button v-if="daySchedule.status_id === 10" @click="completeDaySchedule(daySchedule)" class="btn btn-primary" style="margin: 8px;">
            Complete
          </button>
          <router-link :to="'/day_schedules/' + daySchedule.id + '/tasks'" class="btn btn-primary" style="margin: 8px;">Show</router-link>
          <router-link :to="'/day_schedules/' + daySchedule.id" class="btn btn-primary" style="margin: 8px;">Edit</router-link>
          <router-link :to="'/day_schedules/' + daySchedule.id + '/add_subtask'" class="btn btn-primary" style="margin: 8px;">Add task</router-link>
          <button v-if="daySchedule.active" @click="deleteDaySchedule(daySchedule)" class="btn btn-primary" style="margin: 8px;">Delete</button>
        </td>
      </tr>
    </table>
    <div v-else class="alert alert-warning">
      No day-schedules found.
    </div>
  </div>
</template>

<script lang="ts">
import { ref, onMounted } from 'vue';
import { useStore } from 'vuex';
import { ThField } from '../../types/Table';
import { DaySchedules } from '../../types/ModelsIndex';
import SubtaksOfTaskModal from '../../components/modals/SubtaksOfTaskModal.vue';

export default {
  name: 'DaySchedulesIndex',
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
  },
  computed: {
    filteredDaySchedules() {
      return this.daySchedules.filter(daySchedule => !daySchedule.removed);
    },
  },
  methods: {
    statusText(daySchedule) {
      switch(daySchedule.status_id) {
        case 10:
          return 'In progress';
        case 100:
          return 'Successful';
        case 200:
          return 'Failed';
        case 0:
        default:
          return 'Pending';
      }
    },
    statusColor(daySchedule) {
      switch(daySchedule.status_id) {
        case 10:
          return 'orange';
        case 100:
          return 'green';
        case 200:
          return 'red';
        case 0:
        default:
          return 'gray';
      }
    },
  },
  setup(props, { emit }) {
    const store = useStore();
    const thFields = ref<ThField[]>([
      {
        key: 'day',
        label: 'Day',
      },
      {
        key: 'title',
        label: 'Title',
      },
      {
        key: 'description',
        label: 'Description',
      },
      {
        key: 'points_upon_completion',
        label: 'Points upon completion',
      },
      {
        key: 'status',
        label: 'Status',
      },
      {
        key: 'options',
        label: 'Options',
      }
    ]);
    const daySchedules = ref<DaySchedules[]|undefined>(undefined);
    const filterCompleted = ref('fetchInProgressDaySchedules');

    const changeCompletedFilter = async () => {
      //if(props.modal && !props.idProject) return;

      await store.dispatch(filterCompleted.value);
      
      daySchedules.value = store.getters.getDaySchedules;
    };

    onMounted(async () => {
      await changeCompletedFilter();
    });

    const activateDaySchedule = async (daySchedule) => {
      await store.dispatch('activateDaySchedule', daySchedule);
    };

    const completeDaySchedule = async (daySchedule) => {
      await store.dispatch('completeDaySchedule', daySchedule);
    };

    const deleteDaySchedule = async (daySchedule) => {
      await store.dispatch('deleteDaySchedule', daySchedule);
    };

    return {
      thFields,
      daySchedules,
      filterCompleted,
      changeCompletedFilter,
      activateDaySchedule,
      completeDaySchedule,
      deleteDaySchedule
    };
  },
};
</script>

<style scoped>
/* Add your styles here */
</style>
