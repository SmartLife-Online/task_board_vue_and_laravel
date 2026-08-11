<template>
  <div class="page">
    <div class="page-head">
      <div>
        <p class="kicker">Übersicht</p>
        <h1 class="page-title">Day-Schedules</h1>
      </div>
      <router-link :to="'/day_schedules/create'" class="btn btn-primary">
        Create new Day-Schedule
      </router-link>
    </div>
    <div class="toolbar">
      <div>
        <label for="day-schedules-filter" class="field-label">Filter</label>
        <select id="day-schedules-filter" v-model="filterCompleted" @change="changeCompletedFilter">
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
      </div>
    </div>
    <div v-if="daySchedules === undefined" class="alert alert-info">
      Loading day-schedules...
    </div>
    <div v-else-if="daySchedules.length !== 0" class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th v-for="thField in thFields" :key="thField.key">
              {{ thField.label }}
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="daySchedule in filteredDaySchedules" :key="daySchedule.id">
            <td class="num">{{ daySchedule.day }}</td>
            <td>{{ daySchedule.title }}</td>
            <td>{{ daySchedule.description }}</td>
            <td class="num">{{ daySchedule.points_upon_success }}</td>
            <td>
              <span class="state" :class="statusClass(daySchedule)">{{ statusText(daySchedule) }}</span>
            </td>
            <td>
              <div class="row-actions">
                <button v-if="daySchedule.status_id === 0" @click="activateDaySchedule(daySchedule)" class="btn btn-primary btn-sm">
                  Activate
                </button>
                <button v-if="daySchedule.status_id === 10" @click="completeDaySchedule(daySchedule)" class="btn btn-primary btn-sm">
                  Complete
                </button>
                <router-link :to="'/day_schedules/' + daySchedule.id + '/tasks'" class="btn btn-secondary btn-sm">Show</router-link>
                <router-link :to="'/day_schedules/' + daySchedule.id" class="btn btn-secondary btn-sm">Edit</router-link>
                <router-link :to="'/day_schedules/' + daySchedule.id + '/add_subtask'" class="btn btn-ghost btn-sm">Add task</router-link>
                <button v-if="daySchedule.active" @click="deleteDaySchedule(daySchedule)" class="btn btn-danger btn-sm">Delete</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
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
    statusClass(daySchedule) {
      switch(daySchedule.status_id) {
        case 10:
          return 'state-progress';
        case 100:
          return 'state-yes';
        case 200:
          return 'state-no';
        case 0:
        default:
          return 'state-pending';
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
