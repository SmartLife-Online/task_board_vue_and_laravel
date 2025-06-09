<template>
    <div>
        User-Points: {{ user?.points }} | {{ user?.seasonPoints }}
        <button @click="recalcUserPoints(user)" class="btn btn-primary">Recalc</button>
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

        return {
            user,
            recalcUserPoints
        };
    },

};
</script>
    
<style scoped>
</style>
  