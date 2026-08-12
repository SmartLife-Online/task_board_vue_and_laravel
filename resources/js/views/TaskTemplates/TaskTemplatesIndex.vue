<template>
  <div class="page">
    <div class="page-head">
      <div>
        <p class="kicker">Reusable workflows</p>
        <h1 class="page-title">Task-Templates</h1>
      </div>
      <router-link to="/task_templates/create" class="btn btn-primary">New template</router-link>
    </div>

    <div v-if="errorMessage" class="alert alert-danger" role="alert">
      {{ errorMessage }}
    </div>
    <div v-if="loading" class="alert alert-info">Loading templates…</div>
    <div v-else-if="templates.length" class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th>Template</th>
            <th>Default task</th>
            <th>Task points</th>
            <th>Subtasks</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="template in templates" :key="template.id">
            <td>{{ template.name }}</td>
            <td>
              {{ template.task_title }}
              <span v-if="template.task_description" class="template-description">
                {{ template.task_description }}
              </span>
            </td>
            <td class="num">{{ template.task_points_upon_completion }}</td>
            <td>
              <ol class="subtask-preview">
                <li v-for="subtask in template.subtasks" :key="subtask.id ?? subtask.sort_order">
                  {{ subtask.title }} ({{ subtask.points_upon_completion }})
                </li>
              </ol>
            </td>
            <td>
              <div class="row-actions">
                <router-link :to="`/task_templates/${template.id}/apply`" class="btn btn-primary btn-sm">
                  Apply
                </router-link>
                <router-link :to="`/task_templates/${template.id}/edit`" class="btn btn-secondary btn-sm">
                  Edit
                </router-link>
                <button type="button" class="btn btn-danger btn-sm" @click="removeTemplate(template)">
                  Delete
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div v-else class="alert alert-warning">
      No Task-Templates found.
    </div>
  </div>
</template>

<script setup lang="ts">
import axios from 'axios';
import { onMounted, ref } from 'vue';
import { TaskTemplate } from '../../types/TaskTemplates';

const templates = ref<TaskTemplate[]>([]);
const loading = ref(true);
const errorMessage = ref('');

const loadTemplates = async (): Promise<void> => {
  loading.value = true;
  errorMessage.value = '';

  try {
    const response = await axios.get('/api/v1/task_templates');
    templates.value = response.data;
  } catch (error) {
    errorMessage.value = 'The templates could not be loaded.';
  } finally {
    loading.value = false;
  }
};

const removeTemplate = async (template: TaskTemplate): Promise<void> => {
  if (!template.id || !window.confirm(`Do you really want to delete the template “${template.name}”?`)) {
    return;
  }

  errorMessage.value = '';

  try {
    await axios.delete(`/api/v1/task_templates/${template.id}`);
    templates.value = templates.value.filter(item => item.id !== template.id);
  } catch (error) {
    errorMessage.value = 'The template could not be deleted.';
  }
};

onMounted(loadTemplates);
</script>

<style scoped>
.template-description {
  display: block;
  margin-top: 4px;
  color: var(--color-muted);
  font-size: 13px;
  font-weight: 400;
}

.subtask-preview {
  margin: 0;
  padding-left: 20px;
}
</style>
