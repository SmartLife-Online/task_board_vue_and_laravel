<template>
    <div>
      <h1>Categories</h1>
      <table id="tableComponent" class="table table-bordered table-striped">
        <th v-for="thField in thFields" :key="thField.key">
          {{ thField.label }}
        </th>
        <tr v-for="category in categories" :key="category.id"> 
          <td>{{ category.life_area }}</td>
          <td>{{ category.title }}</td>
          <td>{{ category.description }}</td>
          <td>{{ category.points_multiplier_in_percent }}</td>
          <td>{{ category.points }}</td>
          <td>
            <router-link :to="'/categories/' + category.id" class="btn btn-primary" style="margin: 8px;">Edit</router-link>
            <router-link :to="'/categories/' + category.id + '/add_project'" class="btn btn-primary" style="margin: 8px;">Add Project</router-link>
            <router-link :to="'/categories/' + category.id + '/add_task'" class="btn btn-primary" style="margin: 8px;">Add Task</router-link>
            <router-link :to="'/categories/' + category.id + '/add_habit'" class="btn btn-primary" style="margin: 8px;">Add Habit</router-link>
          </td>
        </tr>
      </table>
    </div>
  </template>
  
  <script lang="ts">
  import { ref, onMounted } from 'vue';
  import { useStore } from 'vuex';
  import { ThField } from '../../types/Table';
  import { Category } from '../../types/ModelsIndex';

  export default {
    name: 'CategoriesIndex',
    setup() {
      const store = useStore();
      const thFields = ref<ThField[]>([
        {
          key: 'life_area',
          label: 'Life-Area',
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
          key: 'points_multiplier_in_percent',
          label: 'Points-Multiplier (in percent)',
        },
        {
          key: 'points',
          label: 'Points',
        },
        {
          key: 'options',
          label: 'Options',
        }
      ]);
      const categories = ref<Category[]|undefined>(undefined);

      onMounted(async () => {
        await store.dispatch('fetchCategories');
        
        categories.value = store.getters.getCategories;
      });

      return {
        thFields,
        categories,
      };
    },
  };
  </script>
  
  <style scoped>
  /* Add your styles here */
  </style>
