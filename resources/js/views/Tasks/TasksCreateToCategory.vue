<template>
  <div>
    <h1>Add Task to Category {{ idCategory }}</h1>
    <TaskBoardFormular :rows="formRows" :entry="task" @submitForm="handleFormSubmit">
      <div class="subtasks-section">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
          <h2 class="mb-0">Subtasks</h2>
          <button type="button" class="btn btn-primary" @click="addSubtask">Add subtask</button>
        </div>
        <div v-if="task.subtasks && task.subtasks.length">
          <div v-for="(subtask, index) in task.subtasks" :key="index" class="card mb-3">
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
import { ref, onMounted } from 'vue';
import TaskBoardFormular from '../../components/TaskBoardFormular.vue';
import { FormField } from '../../types/Form';
import { Task, TaskSubtaskDraft } from '../../types/ModelsForm';
import { useRoute } from 'vue-router';
import { useStore } from 'vuex';

const createEmptySubtask = (): TaskSubtaskDraft => ({
  title: '',
  description: '',
  points_upon_completion: '',
  completed: false
});

export default {
  name: 'ProjectsCreate',
  components: {
    TaskBoardFormular
  },
  setup() {
    const route = useRoute();
    const store = useStore();

    const formRows = ref<FormField[][]>([
      [
        { name: 'title', type: 'text', label: 'Title' },
        { name: 'description', type: 'text', label: 'Description' },
      ],
      [
        { name: 'points_upon_completion', type: 'text', label: 'Points upon completion' },
        { name: 'day_schedule_part_id', type: 'text', label: 'Day-Schedule-Part' },
      ],
      [
        { name: 'completed', type: 'checkbox', label: 'Completed?' },
      ],
      [
        { name: 'with_ai', type: 'checkbox', label: 'Create subtasks by AI?' },
      ]
    ]);

    const idCategory = parseInt(Array.isArray(route.params.id) ? route.params.id[0] : route.params.id);
    const task = ref<Task>({
      title: '',
      description: '',
      points_upon_completion: '',
      day_schedule_part_id: null,
      completed: false,
      with_ai: false,
      subtasks: []
    });

    onMounted(async () => {
      await store.dispatch('fetchCurrentDaySchedulePart');
      
      const currentDaySchedulePart = store.getters.getCurrentDaySchedulePart;

      task.value.day_schedule_part_id = currentDaySchedulePart.id ?? null;
    });
    
    const addSubtask = () => {
      task.value.subtasks?.push(createEmptySubtask());
    };

    const removeSubtask = (index: number) => {
      task.value.subtasks?.splice(index, 1);
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
      const data = await store.dispatch('submitStoreTaskToCategory', {
        idCategory,
        formData: {
          ...formData,
          subtasks: sanitizeSubtasks(formData.subtasks)
        }
      });
    };

    return {
      formRows,
      idCategory,
      task,
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
