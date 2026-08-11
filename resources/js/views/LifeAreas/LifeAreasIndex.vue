<template>
    <div class="page">
      <div class="page-head">
        <div>
          <p class="kicker">Übersicht</p>
          <h1 class="page-title">Life-Areas</h1>
        </div>
      </div>
      <div v-if="lifeAreas === undefined" class="alert alert-info">
        Loading life-areas...
      </div>
      <div v-else-if="filteredLifeAreas.length !== 0" class="table-wrap">
        <table class="table">
          <thead>
            <tr>
              <th v-for="thField in thFields" :key="thField.key">
                {{ thField.label }}
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="lifeArea in filteredLifeAreas" :key="lifeArea.id">
              <td>{{ lifeArea.title }}</td>
              <td>{{ lifeArea.description }}</td>
              <td class="num">{{ lifeArea.points_multiplier_in_percent }}</td>
              <td class="num">{{ lifeArea.points }}</td>
              <td class="num">{{ lifeArea.basis_points }}</td>
              <td>
                <div class="row-actions">
                  <router-link :to="'/life_areas/' + lifeArea.id" class="btn btn-secondary btn-sm">Edit</router-link>
                  <router-link :to="'/life_areas/' + lifeArea.id + '/add_category'" class="btn btn-ghost btn-sm">Add Category</router-link>
                  <button v-if="lifeArea.active" @click="deleteLifeArea(lifeArea)" class="btn btn-danger btn-sm">Delete</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-else class="alert alert-warning">
        No life-areas found.
      </div>
    </div>
  </template>
  
  <script lang="ts">
  import { ref, onMounted } from 'vue';
  import { useStore } from 'vuex';
  import { ThField } from '../../types/Table';
  import { LifeArea } from '../../types/ModelsIndex';

  export default {
    name: 'LifeAreasIndex',
    computed: {
      filteredLifeAreas() {
        return this.lifeAreas.filter(lifeArea => !lifeArea.removed);
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
      const lifeAreas = ref<LifeArea[]|undefined>(undefined);

      onMounted(async () => {
        await store.dispatch('fetchLifeAreas');
        
        lifeAreas.value = store.getters.getLifeAreas;
      });

      const deleteLifeArea = async (lifeArea) => {
        await store.dispatch('deleteLifeArea', lifeArea);
      };

      return {
        thFields,
        lifeAreas,
        deleteLifeArea
      };
    },
  };
  </script>
  
  <style scoped>
  /* Add your styles here */
  </style>
