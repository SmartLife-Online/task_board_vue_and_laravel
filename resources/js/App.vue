<template>
    <nav class="sl-nav">
        <router-link to="/day_schedules" class="sl-brand">Task-Board</router-link>
        <div class="nav-links">
            <router-link to="/day_schedules" class="nav-link">Day-Schedule</router-link>
            <router-link to="/life_areas" class="nav-link">Life-Areas</router-link>
            <router-link to="/categories" class="nav-link">Categories</router-link>
            <router-link to="/projects" class="nav-link">Projects</router-link>
            <router-link to="/tasks" class="nav-link">Tasks</router-link>
            <router-link to="/task_templates" class="nav-link">Task-Vorlagen</router-link>
            <router-link to="/subtasks" class="nav-link">Subtasks</router-link>
            <router-link to="/habits" class="nav-link">Habits</router-link>
        </div>
        <div class="nav-actions">
            <button
                type="button"
                class="btn btn-secondary btn-icon"
                aria-label="Punkte in die Zwischenablage kopieren"
                title="Punkte kopieren"
                :disabled="!user?.id"
                @click="copyPoints"
            >
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
            </button>
            <button type="button" class="btn btn-secondary btn-icon" aria-label="Design umschalten" title="Hell / Dunkel" @click="toggleTheme">
                <svg class="icon-sun" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="4"></circle><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"></path></svg>
                <svg class="icon-moon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"></path></svg>
            </button>
        </div>
    </nav>
    <main>
        <div class="stat-shell">
            <UserPointsDisplay />
        </div>
        <router-view></router-view>
    </main>
    <footer class="site-footer">
        <div class="footer-inner">
            <p class="footer-brand">SmartLife — Online</p>
            <nav class="footer-links">
                <a href="https://www.smartlife-online.de">Startseite</a>
                <a href="https://www.smartlife-online.de/?apps">Web Apps</a>
                <a href="https://www.smartlife-online.de/verzeichnis">Verzeichnis</a>
            </nav>
        </div>
    </footer>
    <div v-if="toastMessage" class="toastr" role="status" aria-live="polite">
        {{ toastMessage }}
    </div>
</template>

<script lang="ts">
  import { computed, onMounted, ref } from 'vue';
  import { useStore } from 'vuex';
  import UserPointsDisplay from './components/UserPointsDisplay.vue';

  export default {
    components: {
      UserPointsDisplay
    },
    setup() {
      const store = useStore();
      const toastMessage = ref('');
      let toastTimeout: ReturnType<typeof setTimeout> | undefined;
      const user = computed(() => {
        const storedUser = store.getters.getUser;

        return storedUser?.id ? storedUser : undefined;
      });

      onMounted(async () => {
        await store.dispatch('fetchUser', 1);
      });

      const toggleTheme = (): void => {
        const next = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';

        document.documentElement.setAttribute('data-theme', next);

        try {
          localStorage.setItem('sl-theme', next);
        } catch (error) {}
      };

      const showToast = (message: string): void => {
        toastMessage.value = message;

        if (toastTimeout) {
          clearTimeout(toastTimeout);
        }

        toastTimeout = setTimeout(() => {
          toastMessage.value = '';
        }, 3000);
      };

      const copyPoints = async (): Promise<void> => {
        if (!user.value) {
          return;
        }

        const points = `${user.value.points} | ${user.value.seasonPoints} | ${Math.floor((user.value.seasonBasisPoints || 0) / 50 - 1867)} | ${Math.floor((user.value.seasonBasisPoints || 0) / 50 - 10657)}`;

        try {
          if (navigator.clipboard?.writeText) {
            await navigator.clipboard.writeText(points);
          } else {
            const textarea = document.createElement('textarea');
            textarea.value = points;
            document.body.appendChild(textarea);
            textarea.select();
            const copied = document.execCommand('copy');
            textarea.remove();

            if (!copied) {
              throw new Error('Clipboard API is unavailable');
            }
          }

          showToast('Punkte wurden in die Zwischenablage kopiert.');
        } catch (error) {
          showToast('Punkte konnten nicht in die Zwischenablage kopiert werden.');
        }
      };

      return {
        toggleTheme,
        user,
        copyPoints,
        toastMessage
      };
    }
  }
</script>
