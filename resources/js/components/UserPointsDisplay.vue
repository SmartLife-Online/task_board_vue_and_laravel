<template>
    <div class="stat-bar">
        <div class="stat">
            <p class="stat-label">User-Points</p>
            <p class="stat-value">{{ user?.points }}</p>
        </div>
        <div class="stat">
            <p class="stat-label">Season-Points</p>
            <p class="stat-value">{{ user?.seasonPoints }}</p>
        </div>
        <div class="stat">
            <p class="stat-label">Season-Basis</p>
            <p class="stat-value">{{ Math.floor((user?.seasonBasisPoints || 0) / 50 - 1867) }} | {{ Math.floor((user?.seasonBasisPoints || 0) / 50 - 14278) }}</p>
        </div>
        <div class="stat-actions">
            <button @click="recalcUserPoints(user)" class="btn btn-secondary btn-sm">Recalc</button>
            <a v-if="user?.day" :href="'/day_schedules/' + user.day_id + '/tasks'" class="btn btn-secondary btn-sm">Show Day {{ user.day }}</a>
            <button v-if="user?.day" @click="completeDayScheduleById(user.day_id)" class="btn btn-primary btn-sm">
                Complete
            </button>
        </div>
    </div>
</template>
    
<script lang="ts">
import { computed } from 'vue';
import { User } from '../types/ModelsIndex';
import { useStore } from 'vuex';
  
export default {
    name: 'UserPointsDisplay',
    setup() {
        const store = useStore();

        const user = computed<User|undefined>(() => store.getters.getUser);

        const recalcUserPoints = async (user) => {
            await store.dispatch('recalcUserPoints', user);
        };

        const completeDayScheduleById = async (idDaySchedule) => {
            await store.dispatch('completeDayScheduleById', idDaySchedule);
        };

        return {
            user,
            recalcUserPoints,
            completeDayScheduleById
        };
    },

};
</script>
    
<style scoped>
</style>
