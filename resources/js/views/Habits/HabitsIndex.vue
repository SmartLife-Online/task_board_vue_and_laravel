<template>
  <div class="page">
    <div class="page-head">
      <div>
        <p class="kicker">Übersicht</p>
        <h1 class="page-title">Habits</h1>
      </div>
    </div>
    <div class="toolbar">
      <div>
        <label for="habits-filter" class="field-label">Filter</label>
        <select id="habits-filter" v-model="filterCompleted" @change="changeCompletedFilter">
          <option value="fetchNotCompletedHabits">
            Not completed
          </option>
          <option value="fetchCompletedHabits">
            Completed
          </option>
          <option value="fetchHabits">
            All
          </option>
          <option value="fetchDeletedHabits">
            Deleted
          </option>
        </select>
      </div>
    </div>
    <div v-if="habits === undefined" class="alert alert-info">
      Loading habits...
    </div>
    <div v-else-if="filtereHabits.length !== 0" class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th v-for="thField in thFields" :key="thField.key">
              {{ thField.label }}
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="habit in filtereHabits" :key="habit.id">
            <td class="cell-clickable" @click="countUpCompletedInHabit(habit)">{{ habit.title }}</td>
            <td>{{ habit.category }}</td>
            <td class="num">{{ habit.points_per_completion }}</td>
            <td class="num">{{ habit.count_completed }}</td>
            <td class="num">{{ habit.points }}</td>
            <td class="num">{{ habit.points_upon_completion }}</td>
            <td>
              <div class="cell-info">
                <i class="info-badge" :title="habit.life_area">L</i>
                <i class="info-badge" :title="habit.category">C</i>
                <i v-if="habit.project" class="info-badge" :title="habit.project">P</i>
                <i v-if="habit.description" class="info-badge" :title="habit.description">D</i>
              </div>
            </td>
            <td>
              <div class="row-actions">
                <button @click="countUpCompletedInHabit(habit)" class="btn btn-primary btn-sm btn-icon" title="Count up">+</button>
                <button @click="countDownCompletedInHabit(habit)" class="btn btn-secondary btn-sm btn-icon" title="Count down">-</button>
                <button v-if="!habit.completed" @click="completeHabit(habit)" class="btn btn-primary btn-sm">Complete</button>
                <router-link :to="'/habits/' + habit.id" class="btn btn-secondary btn-sm">Edit</router-link>
                <router-link :to="'/categories/' + habit.category_id + '/add_habit'" class="btn btn-ghost btn-sm">Add habit to same category</router-link>
                <button v-if="habit.active" @click="deleteHabit(habit)" class="btn btn-danger btn-sm">Delete</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div v-else class="alert alert-warning">
      No habits found.
    </div>
  </div>
</template>

<script lang="ts">
import { ref, onMounted } from 'vue';
import { useStore } from 'vuex';
import { ThField } from '../../types/Table';
import { Habit } from '../../types/ModelsIndex';

export default {
  name: 'HabitsIndex',
  computed: {
    filtereHabits() {
      return this.habits.filter(habit => !habit.removed);
    },
  },
  setup() {
    const store = useStore();
    const thFields = ref<ThField[]>([
      {
        key: 'title',
        label: 'Title',
      },
      {
        key: 'category',
        label: 'Category',
      },
      {
        key: 'points_per_completion',
        label: 'Points per completion',
      },
      {
        key: 'count_completed',
        label: 'Count completed',
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
        key: 'info',
        label: 'Info',
      },
      {
        key: 'options',
        label: 'Options',
      }
    ]);
    const habits = ref<Habit[]|undefined>(undefined);
    const filterCompleted = ref('fetchNotCompletedHabits');

    onMounted(async () => {
      await changeCompletedFilter();
    });

    const changeCompletedFilter = async () => {
      await store.dispatch(filterCompleted.value);
      
      habits.value = store.getters.getHabits;
    };

    const countUpCompletedInHabit = async (habit) => {
      await store.dispatch('countUpCompletedInHabit', habit);
    };

    const countDownCompletedInHabit = async (habit) => {
      await store.dispatch('countDownCompletedInHabit', habit);
    };

    const completeHabit = async (habit) => {
      await store.dispatch('completeHabit', habit);
    };

    const deleteHabit = async (habit) => {
      await store.dispatch('deleteHabit', habit);
    };

    return {
      thFields,
      habits,
      filterCompleted,
      changeCompletedFilter,
      countUpCompletedInHabit,
      countDownCompletedInHabit,
      completeHabit,
      deleteHabit
    };
  },
};
</script>

<style scoped>
/* Add your styles here */
</style>
