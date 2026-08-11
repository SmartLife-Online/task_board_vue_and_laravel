<template>
    <div class="page">
      <TaksOfProjectModal :title="'Tasks of &quot;' + tasksOfProjectModalNameProject + '&quot;'" :idProject="tasksOfProjectModalIdProject" @modalClosed="onModalClosed" />
      <div class="page-head">
        <div>
          <p class="kicker">Übersicht</p>
          <h1 class="page-title">Projects</h1>
        </div>
      </div>
      <div class="toolbar">
        <div>
          <label for="projects-filter" class="field-label">Filter</label>
          <select id="projects-filter" v-model="filterCompleted" @change="changeCompletedFilter">
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
        </div>
      </div>
      <div v-if="projects === undefined" class="alert alert-info">
        Loading projects...
      </div>
      <div v-else-if="filteredProjects.length !== 0" class="table-wrap">
        <table class="table">
          <thead>
            <tr>
              <th v-for="thField in thFields" :key="thField.key">
                {{ thField.label }}
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="project in filteredProjects" :key="project.id">
              <td>{{ project.life_area }}</td>
              <td>{{ project.category }}</td>
              <td>{{ project.title }}</td>
              <td>{{ project.description }}</td>
              <td class="num">{{ project.points_multiplier_in_percent }}</td>
              <td class="num">{{ project.points_upon_completion }}</td>
              <td class="num">{{ project.points }}</td>
              <td>
                <div class="row-actions">
                  <button v-if="!project.completed" @click="completeProject(project)" class="btn btn-primary btn-sm">Complete</button>
                  <button type="button" class="btn btn-secondary btn-sm" @click="tasksOfProjectModalIdProject = project.id;tasksOfProjectModalNameProject = project.title">
                    Tasks
                  </button>
                  <router-link :to="'/projects/' + project.id" class="btn btn-secondary btn-sm">Edit</router-link>
                  <router-link :to="'/projects/' + project.id + '/add_project_to_project'" class="btn btn-ghost btn-sm">Add Sub-Project</router-link>
                  <router-link :to="'/projects/' + project.id + '/add_task'" class="btn btn-ghost btn-sm">Add Task</router-link>
                  <router-link :to="'/projects/' + project.id + '/add_habit'" class="btn btn-ghost btn-sm">Add Habit</router-link>
                  <button v-if="project.active" @click="recalcProject(project)" class="btn btn-ghost btn-sm">Recalc</button>
                  <button v-if="project.active" @click="deleteProject(project)" class="btn btn-danger btn-sm">Delete</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
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

      const recalcProject = async (project) => {
        await store.dispatch('recalcProject', project);
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
        recalcProject,
        deleteProject
      };
    },
  };
  </script>
  
  <style scoped>
  /* Add your styles here */
  </style>
