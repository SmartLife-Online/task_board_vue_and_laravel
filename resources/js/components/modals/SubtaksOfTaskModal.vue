<template>
  <div
    class="modal"
    :class="{ 'is-open': idTask }"
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
          <SubtasksIndex :idTask="idTask" :modal="true" />
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
import SubtasksIndex from '../../views/Subtasks/SubtasksIndex.vue';

export default {
  name: 'SubtaksOfTaskModal',
  components: {
    SubtasksIndex
  },
  props: {
    title: {
      type: String,
      default: '',
    },
    idTask: {
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
