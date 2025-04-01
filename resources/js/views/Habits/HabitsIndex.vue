<template>
  <div>
    <h1>Habits</h1>
    <select v-model="filterCompleted" style="margin: 8px;" @change="changeCompletedFilter">
      <option value="fetchNotCompltedHabits">
        Not completed
      </option>
      <option value="fetchCompltedHabits">
        Completed
      </option>
      <option value="fetchHabits">
        All
      </option>
    </select>
    <table id="tableComponent" class="table table-bordered table-striped">
      <th v-for="thField in thFields" :key="thField.key">
        {{ thField.label }}
      </th>
      <tr v-for="habit in habits" :key="habit.id">
        <td @click="countUpCompletedInHabit(habit)" style="cursor: cell;">{{ habit.title }}</td>
        <td>{{ habit.category }}</td>
        <td>{{ habit.points_per_completion }}</td>
        <td>{{ habit.count_completed }}</td>
        <td>{{ habit.points }}</td>
        <td>{{ habit.points_upon_completion }}</td>
        <td>
          <i class="btn btn-primary" :title="habit.life_area">L</i>
          <i class="btn btn-primary" :title="habit.category">C</i>
          <i v-if="habit.project" class="btn btn-primary" :title="habit.project">P</i>
          <i v-if="habit.description" class="btn btn-primary" :title="habit.description">D</i>
        </td>
        <td>
          <button @click="countUpCompletedInHabit(habit)" class="btn btn-primary" style="margin: 8px;">+</button>
          <button @click="countDownCompletedInHabit(habit)" class="btn btn-primary" style="margin: 8px;">-</button>
          <button v-if="!habit.completed" @click="completeHabit(habit)" class="btn btn-primary" style="margin: 8px;">Complete</button>
          <router-link :to="'/habits/' + habit.id" class="btn btn-primary" style="margin: 8px;">Edit</router-link>
          <router-link :to="'/categories/' + habit.category_id + '/add_habit'" class="btn btn-primary" style="margin: 8px;">Add habit to same category</router-link>
        </td>
      </tr>
    </table>
  </div>
</template>

<script lang="ts">
import { ref, onMounted } from 'vue';
import { useStore } from 'vuex';
import { ThField } from '../../types/Table';
import { Habit } from '../../types/ModelsIndex';

export default {
  name: 'HabitsIndex',
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
    const filterCompleted = ref('fetchNotCompltedHabits');

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

    return {
      thFields,
      habits,
      filterCompleted,
      changeCompletedFilter,
      countUpCompletedInHabit,
      countDownCompletedInHabit,
      completeHabit,
    };
  },
};
</script>

<style scoped>
/* Add your styles here */
</style>
