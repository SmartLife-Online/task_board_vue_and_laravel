<template>
    <div>
        User-Points: {{ user?.points }} | {{ user?.seasonPoints }}
        <button @click="recalcUserPoints(user)" class="btn btn-primary" style="margin-right: 12px;">Recalc</button>
        <a v-if="user?.day" :href="'/day_schedules/' + user.day_id + '/tasks'" class="btn btn-primary" style="margin-right: 12px;">Show Day {{ user.day }}</a>
        <button v-if="user?.day" @click="completeDayScheduleById(user.day_id)" class="btn btn-primary">
        Complete
        </button>
    </div>
</template>
    
<script lang="ts">
import { ref, onMounted } from 'vue';
import { User } from '../types/ModelsIndex';
import { useStore } from 'vuex';
  
export default {
    name: 'UserPointsDisplay',
    setup() {
        const store = useStore();

        const user = ref<User|undefined>(undefined);

        onMounted(async () => {
            await store.dispatch('fetchUser', 1);
            
            user.value = store.getters.getUser;
        });

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
  