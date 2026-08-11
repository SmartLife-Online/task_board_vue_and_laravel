<template>
  <div
    class="modal"
    :class="{ 'is-open': idProject }"
    tabindex="-1"
    role="dialog"
    aria-modal="true"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">{{ title }}</h2>
          <button
            type="button"
            class="btn-close"
            aria-label="Schließen"
            @click="closeModal"
          >&times;</button>
        </div>
        <div class="modal-body">
          <TasksIndex :idProject="idProject" :modal="true" />
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            @click="closeModal"
          >
            Schließen
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import TasksIndex from '../../views/Tasks/TasksIndex.vue';

export default {
  name: 'TaksOfProjectModal',
  components: {
    TasksIndex
  },
  props: {
    title: {
      type: String,
      default: '',
    },
    idProject: {
      type: Number,
      default: 0,
    },
  },
  setup(props, { emit }) {
    const closeModal = () => {
      emit('modalClosed', true); 
    };

    return {
      closeModal,
    };
  },
};
</script>

<style scoped>
</style>
