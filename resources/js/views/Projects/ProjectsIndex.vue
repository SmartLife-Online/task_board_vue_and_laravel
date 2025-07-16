<template>
    <div>
      <TaksOfProjectModal :title="'Tasks of &quot;' + tasksOfProjectModalNameProject + '&quot;'" :idProject="tasksOfProjectModalIdProject" @modalClosed="onModalClosed" />
      <h1>Projects</h1>
      <select v-model="filterCompleted" style="margin: 8px;" @change="changeCompletedFilter">
        <option value="fetchNotCompletedProjects">
          Not completed
        </option>
        <option value="fetchCompletedProjects">
          Completed
        </option>
        <option value="fetchProjects">
          All
        </option>
      <option value="fetchDeletedProjects">
        Deleted
      </option>
      </select>
      <div v-if="projects === undefined" class="alert alert-info">
        Loading projects...
      </div>
      <table v-else-if="filteredProjects.length !== 0" class="table table-bordered table-striped">
        <th v-for="thField in thFields" :key="thField.key">
          {{ thField.label }}
        </th>
        <tr v-for="project in filteredProjects" :key="project.id"> 
          <td>{{ project.life_area }}</td>
          <td>{{ project.category }}</td>
          <td>{{ project.title }}</td>
          <td>{{ project.description }}</td>
          <td>{{ project.points_multiplier_in_percent }}</td>
          <td>{{ project.points_upon_completion }}</td>
          <td>{{ project.points }}</td>
          <td>
            <button v-if="!project.completed" @click="completeProject(project)" class="btn btn-primary" style="margin: 8px;">Complete</button>
            <button id="modalButton" type="button" class="btn btn-primary" style="margin: 8px;" @click="tasksOfProjectModalIdProject = project.id;tasksOfProjectModalNameProject = project.title">
              Tasks
            </button>
            <router-link :to="'/projects/' + project.id" class="btn btn-primary" style="margin: 8px;">Edit</router-link>
            <router-link :to="'/projects/' + project.id + '/add_project_to_project'" class="btn btn-primary" style="margin: 8px;">Add Sub-Project</router-link>
            <router-link :to="'/projects/' + project.id + '/add_task'" class="btn btn-primary" style="margin: 8px;">Add Task</router-link>
            <router-link :to="'/projects/' + project.id + '/add_habit'" class="btn btn-primary" style="margin: 8px;">Add Habit</router-link>
            <button v-if="project.active" @click="deleteProject(project)" class="btn btn-primary" style="margin: 8px;">Delete</button>
          </td>
        </tr>
      </table>
      <div v-else class="alert alert-warning">
        No projects found.
      </div>
    </div>
  </template>
  
  <script lang="ts">
  import { ref, onMounted } from 'vue';
  import { useStore } from 'vuex';
  import { ThField } from '../../types/Table';
  import { Project } from '../../types/ModelsIndex';
  import TaksOfProjectModal from '../../components/modals/TaksOfProjectModal.vue';

  export default {
    name: 'ProjectsIndex',
    components: {
      TaksOfProjectModal,
    },
    computed: {
      filteredProjects() {
        return this.projects.filter(project => !project.removed);
      },
    },
    setup() {
      const store = useStore();
      const tasksOfProjectModalIdProject = ref<number>(0);
      const tasksOfProjectModalNameProject = ref<string>('');
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
      const filterCompleted = ref('fetchNotCompletedProjects');

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

      const onModalClosed = () => {
        tasksOfProjectModalIdProject.value = 0;
        tasksOfProjectModalNameProject.value = '';
      };

      const deleteProject = async (project) => {
        await store.dispatch('deleteProject', project);
      };

      return {
        thFields,
        tasksOfProjectModalIdProject,
        tasksOfProjectModalNameProject,
        onModalClosed,
        projects,
        filterCompleted,
        changeCompletedFilter,
        completeProject,
        deleteProject
      };
    },
  };
  </script>
  
  <style scoped>
  /* Add your styles here */
  </style>
