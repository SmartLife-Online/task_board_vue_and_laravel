<template>
    <div>
        <h1>Task-Board</h1>
        <UserPointsDisplay />
        <div class="navbar">
            <router-link to="/life_areas">Life-Areas</router-link>
            <router-link to="/categories">Categories</router-link>
            <router-link to="/projects">Projects</router-link>
            <router-link to="/tasks">Tasks</router-link>
            <router-link to="/subtasks">Subtasks</router-link>
            <router-link to="/habits">Habits</router-link>
        </div>
        <router-view></router-view>
    </div>
</template>

<script lang="ts">
  import UserPointsDisplay from './components/UserPointsDisplay.vue';

  export default {
    components: {
      UserPointsDisplay
    }
  }
</script>

<style scoped>
    .navbar {
        display: flex;
        gap: 10px;
        margin-left: 20px;
        margin-right: 20px;
        margin-bottom: 20px;
    }

    .navbar a {
        text-decoration: none;
        color: blue;
    }

    .navbar a:hover {
        text-decoration: underline;
    }
</style>
