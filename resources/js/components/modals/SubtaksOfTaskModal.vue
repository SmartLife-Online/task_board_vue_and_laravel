<template>
  <div
    id="exampleModal"
    class="modal fade"
    tabindex="-1"
    :style="idTask ? 'display: flex;opacity: 1;' : ''"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 id="exampleModalLabel" class="modal-title">{{ title }}</h5>
          <button
            type="button"
            class="btn-close"
            @click="closeModal"
          ></button>
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
import { ref, watch } from 'vue';
import { useStore } from 'vuex';
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
    const store = useStore();
    
    watch(() => props.idTask, (newIdTask) => {
      if(newIdTask === 0) return;
      console.log('ID Task changed:', newIdTask);
        //await store.dispatch('fetchTask', idTask);
        
        //task.value = store.getters.getTask;
    }, { deep: true });
    
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
.modal-dialog {
  margin-top: auto;
  margin-bottom: auto;
}

.modal {
    --bs-modal-width: 1200px;
}
</style>
