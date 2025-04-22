<template>
    <div>
      <h1>Life-Areas</h1>
      <div v-if="lifeAreas === undefined" class="alert alert-info">
        Loading life-areas...
      </div>
      <table v-else-if="lifeAreas.length !== 0" class="table table-bordered table-striped">
        <th v-for="thField in thFields" :key="thField.key">
          {{ thField.label }}
        </th>
        <tr v-for="lifeArea in lifeAreas" :key="lifeArea.id"> 
          <td>{{ lifeArea.title }}</td>
          <td>{{ lifeArea.description }}</td>
          <td>{{ lifeArea.points_multiplier_in_percent }}</td>
          <td>{{ lifeArea.points }}</td>
          <td>
            <router-link :to="'/life_areas/' + lifeArea.id" class="btn btn-primary" style="margin: 8px;">Edit</router-link>
            <router-link :to="'/life_areas/' + lifeArea.id + '/add_category'" class="btn btn-primary" style="margin: 8px;">Add Category</router-link>
            <button v-if="lifeArea.active" @click="deleteLifeArea(lifeArea)" class="btn btn-primary" style="margin: 8px;">Delete</button>
          </td>
        </tr>
      </table>
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
