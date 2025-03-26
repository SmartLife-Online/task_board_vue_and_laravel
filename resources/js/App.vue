<template>
    <div>
        <h1>Task-Board</h1>
        <router-link to="/life_areas">Life-Areas</router-link>
        <br>
        <router-link to="/categories">Categories</router-link>
        <br>
        <router-link to="/projects">Projects</router-link>
        <br>
        <router-link to="/tasks">Tasks</router-link>
        <br>
        <router-link to="/subtasks">Subtasks</router-link>
        <br>
        <router-link to="/habits">Habits</router-link>
        <router-view></router-view>
    </div>
</template>
