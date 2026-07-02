<template>
  <div>
    <h1>Add Subtasks to Task {{ idTask }}</h1>
    <TaskBoardFormular :rows="formRows" :entry="subtaskForm" @submitForm="handleFormSubmit">
      <div class="subtasks-section">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
          <h2 class="mb-0">Subtasks</h2>
          <button type="button" class="btn btn-primary" @click="addSubtask">Add subtask</button>
        </div>
        <div v-if="subtaskForm.subtasks.length">
          <div v-for="(subtask, index) in subtaskForm.subtasks" :key="index" class="card mb-3">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <strong>Subtask {{ index + 1 }}</strong>
                <button type="button" class="btn btn-danger btn-sm" @click="removeSubtask(index)">Remove</button>
              </div>
              <div class="row">
                <div class="col-md-6 form-group">
                  <label :for="`subtask-title-${index}`">Title</label>
                  <input :id="`subtask-title-${index}`" v-model="subtask.title" type="text" class="form-control">
                </div>
                <div class="col-md-6 form-group">
                  <label :for="`subtask-description-${index}`">Description</label>
                  <input :id="`subtask-description-${index}`" v-model="subtask.description" type="text" class="form-control">
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 form-group">
                  <label :for="`subtask-points-${index}`">Points upon completion</label>
                  <input :id="`subtask-points-${index}`" v-model="subtask.points_upon_completion" type="text" class="form-control">
                </div>
                <div class="col-md-6 form-group d-flex align-items-center">
                  <div>
                    <label :for="`subtask-completed-${index}`" class="me-2">Completed?</label>
                    <input :id="`subtask-completed-${index}`" v-model="subtask.completed" type="checkbox" class="form-check-input">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </TaskBoardFormular>
  </div>
</template>

<script lang="ts">
import { ref } from 'vue';
import TaskBoardFormular from '../../components/TaskBoardFormular.vue';
import { FormField } from '../../types/Form';
import { SubtaskCreateForm, TaskSubtaskDraft } from '../../types/ModelsForm';
import { useRoute } from 'vue-router';
import { useStore } from 'vuex';

const createEmptySubtask = (): TaskSubtaskDraft => ({
  title: '',
  description: '',
  points_upon_completion: '',
  completed: false
});

export default {
  name: 'SubtasksCreate',
  components: {
    TaskBoardFormular
  },
  setup() {
    const route = useRoute();
    const store = useStore();

    const formRows = ref<FormField[][]>([]);

    const idTask = parseInt(Array.isArray(route.params.id) ? route.params.id[0] : route.params.id);
    const subtaskForm = ref<SubtaskCreateForm>({
      subtasks: [createEmptySubtask()]
    });

    const addSubtask = () => {
      subtaskForm.value.subtasks.push(createEmptySubtask());
    };

    const removeSubtask = (index: number) => {
      subtaskForm.value.subtasks.splice(index, 1);
    };

    const sanitizeSubtasks = (subtasks: TaskSubtaskDraft[] = []) => subtasks
      .filter(subtask => subtask.title.trim() !== '')
      .map(subtask => ({
        title: subtask.title.trim(),
        description: subtask.description.trim(),
        points_upon_completion: subtask.points_upon_completion,
        completed: subtask.completed
      }));
    
    const handleFormSubmit = async formData => {
      await store.dispatch('submitStoreSubtask', {
        idTask,
        formData: {
          subtasks: sanitizeSubtasks(formData.subtasks)
        }
      });
    };

    return {
      formRows,
      idTask,
      subtaskForm,
      addSubtask,
      removeSubtask,
      handleFormSubmit
    };
  },
};
</script>

<style scoped>
/* Add your styles here */
</style>
