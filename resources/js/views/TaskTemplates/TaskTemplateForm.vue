<template>
  <div class="page">
    <div class="page-head">
      <div>
        <p class="kicker">{{ isEditing ? 'Edit template' : 'Reusable workflow' }}</p>
        <h1 class="page-title">{{ isEditing ? 'Edit Task-Template' : 'New Task-Template' }}</h1>
      </div>
      <router-link to="/task_templates" class="btn btn-ghost">Back to overview</router-link>
    </div>

    <div v-if="errorMessage" class="alert alert-danger" role="alert">
      {{ errorMessage }}
    </div>
    <div v-if="loading" class="alert alert-info">Loading template…</div>

    <form v-else class="form" @submit.prevent="submitTemplate">
      <div class="form-row">
        <div class="form-field">
          <label for="template-name">Template name</label>
          <input id="template-name" v-model.trim="template.name" type="text" class="form-control" required autofocus>
        </div>
        <div class="form-field">
          <label for="task-title">Default task title</label>
          <input id="task-title" v-model.trim="template.task_title" type="text" class="form-control" required>
        </div>
      </div>
      <div class="form-row">
        <div class="form-field">
          <label for="task-description">Task description</label>
          <textarea id="task-description" v-model="template.task_description" class="form-control" rows="3"></textarea>
        </div>
        <div class="form-field">
          <label for="task-points">Task points upon completion</label>
          <input id="task-points" v-model.number="template.task_points_upon_completion" type="number" min="0" class="form-control" required>
        </div>
      </div>

      <section class="subtasks-section">
        <div class="page-head">
          <div>
            <h2 class="section-title">Template subtasks</h2>
            <p class="help-text">This order is preserved when tasks are created.</p>
          </div>
          <button type="button" class="btn btn-secondary" @click="addSubtask">Add subtask</button>
        </div>

        <div class="card-stack">
          <div v-for="(subtask, index) in template.subtasks" :key="subtask.id ?? index" class="card">
            <div class="card-head">
              <p class="card-title">Subtask {{ index + 1 }}</p>
              <div class="row-actions">
                <button type="button" class="btn btn-ghost btn-sm" :disabled="index === 0" @click="moveSubtask(index, -1)">
                  Move up
                </button>
                <button type="button" class="btn btn-ghost btn-sm" :disabled="index === template.subtasks.length - 1" @click="moveSubtask(index, 1)">
                  Move down
                </button>
                <button type="button" class="btn btn-danger btn-sm" :disabled="template.subtasks.length === 1" @click="removeSubtask(index)">
                  Remove
                </button>
              </div>
            </div>
            <div class="card-body">
              <div class="form-row">
                <div class="form-field">
                  <label :for="`subtask-title-${index}`">Title</label>
                  <input :id="`subtask-title-${index}`" v-model.trim="subtask.title" type="text" class="form-control" required>
                </div>
                <div class="form-field">
                  <label :for="`subtask-points-${index}`">Points upon completion</label>
                  <input :id="`subtask-points-${index}`" v-model.number="subtask.points_upon_completion" type="number" min="0" class="form-control" required>
                </div>
              </div>
              <div class="form-field">
                <label :for="`subtask-description-${index}`">Description</label>
                <textarea :id="`subtask-description-${index}`" v-model="subtask.description" class="form-control" rows="2"></textarea>
              </div>
            </div>
          </div>
        </div>
      </section>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary" :disabled="submitting">
          {{ submitting ? 'Saving…' : 'Save template' }}
        </button>
        <router-link to="/task_templates" class="btn btn-ghost">Cancel</router-link>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
import axios from 'axios';
import { computed, onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { TaskTemplate, TaskTemplateSubtask } from '../../types/TaskTemplates';

const route = useRoute();
const router = useRouter();
const templateId = computed(() => Number(route.params.id || 0));
const isEditing = computed(() => templateId.value > 0);
const loading = ref(isEditing.value);
const submitting = ref(false);
const errorMessage = ref('');

const emptySubtask = (): TaskTemplateSubtask => ({
  title: '',
  description: '',
  points_upon_completion: 0,
});

const template = ref<TaskTemplate>({
  name: '',
  task_title: '',
  task_description: '',
  task_points_upon_completion: 0,
  subtasks: [emptySubtask()],
});

const addSubtask = (): void => {
  template.value.subtasks.push(emptySubtask());
};

const removeSubtask = (index: number): void => {
  if (template.value.subtasks.length > 1) {
    template.value.subtasks.splice(index, 1);
  }
};

const moveSubtask = (index: number, offset: number): void => {
  const target = index + offset;

  if (target < 0 || target >= template.value.subtasks.length) {
    return;
  }

  const [subtask] = template.value.subtasks.splice(index, 1);
  template.value.subtasks.splice(target, 0, subtask);
};

const validationMessage = (error: any, fallback: string): string => {
  const errors = error?.response?.data?.errors;

  if (errors) {
    return Object.values(errors).flat().join(' ');
  }

  return error?.response?.data?.message || fallback;
};

const loadTemplate = async (): Promise<void> => {
  if (!isEditing.value) {
    return;
  }

  try {
    const response = await axios.get(`/api/v1/task_templates/${templateId.value}`);
    template.value = {
      ...response.data,
      task_description: response.data.task_description ?? '',
      subtasks: response.data.subtasks.map(subtask => ({
        ...subtask,
        description: subtask.description ?? '',
      })),
    };
  } catch (error) {
    errorMessage.value = 'The template could not be loaded.';
  } finally {
    loading.value = false;
  }
};

const submitTemplate = async (): Promise<void> => {
  submitting.value = true;
  errorMessage.value = '';

  const payload = {
    ...template.value,
    subtasks: template.value.subtasks.map((subtask, index) => ({
      title: subtask.title.trim(),
      description: subtask.description?.trim() || null,
      points_upon_completion: Number(subtask.points_upon_completion),
      sort_order: index,
    })),
  };

  try {
    if (isEditing.value) {
      await axios.put(`/api/v1/task_templates/${templateId.value}`, payload);
    } else {
      await axios.post('/api/v1/task_templates', payload);
    }

    await router.push('/task_templates');
  } catch (error) {
    errorMessage.value = validationMessage(error, 'The template could not be saved.');
  } finally {
    submitting.value = false;
  }
};

onMounted(loadTemplate);
</script>

<style scoped>
.help-text {
  margin: 5px 0 0;
  color: var(--color-muted);
  font-size: 14px;
}
</style>
