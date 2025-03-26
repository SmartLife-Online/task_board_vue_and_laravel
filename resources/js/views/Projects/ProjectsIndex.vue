<template>
    <div>
      <h1>Projects</h1>
      <select v-model="filterCompleted" style="margin: 8px;" @change="changeCompletedFilter">
        <option value="fetchNotCompltedProjects">
          Not completed
        </option>
        <option value="fetchCompltedProjects">
          Completed
        </option>
        <option value="fetchProjects">
          All
        </option>
      </select>
      <table id="tableComponent" class="table table-bordered table-striped">
        <th v-for="thField in thFields" :key="thField.key">
          {{ thField.label }}
        </th>
        <tr v-for="project in projects" :key="project.id"> 
          <td>{{ project.life_area }}</td>
          <td>{{ project.category }}</td>
          <td>{{ project.title }}</td>
          <td>{{ project.description }}</td>
          <td>{{ project.points_multiplier_in_percent }}</td>
          <td>{{ project.points_upon_completion }}</td>
          <td>{{ project.points }}</td>
          <td>
            <button v-if="!project.completed" @click="completeProject(project)" class="btn btn-primary" style="margin: 8px;">Complete</button>
            <router-link :to="'/projects/' + project.id" class="btn btn-primary" style="margin: 8px;">Edit</router-link>
            <router-link :to="'/projects/' + project.id + '/add_task'" class="btn btn-primary" style="margin: 8px;">Add Task</router-link>
            <router-link :to="'/projects/' + project.id + '/add_habit'" class="btn btn-primary" style="margin: 8px;">Add Habit</router-link>
          </td>
        </tr>
      </table>
    </div>
  </template>
  
  <script lang="ts">
  import { ref, onMounted } from 'vue';
  import { useStore } from 'vuex';
  import { ThField } from '../../types/Table';
  import { Project } from '../../types/ModelsIndex';

  export default {
    name: 'ProjectsIndex',
    setup() {
      const store = useStore();
      const thFields = ref<ThField[]>([
        {
          key: 'life_area',
          label: 'Life-Area',
        },
        {
          key: 'category',
          label: 'Category',
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
          key: 'points_upon_completion',
          label: 'Points upon completion',
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
      const projects = ref<Project[]|undefined>(undefined);
      const filterCompleted = ref('fetchNotCompltedProjects');

      onMounted(async () => {
        await changeCompletedFilter();
      });

      const changeCompletedFilter = async () => {
        await store.dispatch(filterCompleted.value);
        
        projects.value = store.getters.getProjects;
      };

      const completeProject = async (project) => {
        await store.dispatch('completeProject', project);
      };

      return {
        thFields,
        projects,
        filterCompleted,
        changeCompletedFilter,
        completeProject
      };
    },
  };
  </script>
  
  <style scoped>
  /* Add your styles here */
  </style>
