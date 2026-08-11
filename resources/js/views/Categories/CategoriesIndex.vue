<template>
    <div class="page">
      <div class="page-head">
        <div>
          <p class="kicker">Übersicht</p>
          <h1 class="page-title">Categories</h1>
        </div>
      </div>
      <div v-if="categories === undefined" class="alert alert-info">
        Loading categories...
      </div>
      <div v-else-if="filteredCategories.length !== 0" class="table-wrap">
        <table class="table">
          <thead>
            <tr>
              <th v-for="thField in thFields" :key="thField.key">
                {{ thField.label }}
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="category in filteredCategories" :key="category.id">
              <td>{{ category.life_area }}</td>
              <td>{{ category.title }}</td>
              <td>{{ category.description }}</td>
              <td class="num">{{ category.points_multiplier_in_percent }}</td>
              <td class="num">{{ category.points }}</td>
              <td>
                <div class="row-actions">
                  <router-link :to="'/categories/' + category.id" class="btn btn-secondary btn-sm">Edit</router-link>
                  <router-link :to="'/categories/' + category.id + '/add_project_to_category'" class="btn btn-ghost btn-sm">Add Project</router-link>
                  <router-link :to="'/categories/' + category.id + '/add_task'" class="btn btn-ghost btn-sm">Add Task</router-link>
                  <router-link :to="'/categories/' + category.id + '/add_habit'" class="btn btn-ghost btn-sm">Add Habit</router-link>
                  <button v-if="category.active" @click="deleteCategory(category)" class="btn btn-danger btn-sm">Delete</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-else class="alert alert-warning">
        No categories found.
      </div>
    </div>
  </template>
  
  <script lang="ts">
  import { ref, onMounted } from 'vue';
  import { useStore } from 'vuex';
  import { ThField } from '../../types/Table';
  import { Category } from '../../types/ModelsIndex';

  export default {
    name: 'CategoriesIndex',
    computed: {
      filteredCategories() {
        return this.categories.filter(category => !category.removed);
      },
    },
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

      const deleteCategory = async (category) => {
        await store.dispatch('deleteCategory', category);
      };

      return {
        thFields,
        categories,
        deleteCategory
      };
    },
  };
  </script>
  
  <style scoped>
  /* Add your styles here */
  </style>
