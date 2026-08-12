<template>
  <div class="page">
    <div class="page-head">
      <div>
        <p class="kicker">{{ isEditing ? 'Vorlage bearbeiten' : 'Wiederverwendbarer Ablauf' }}</p>
        <h1 class="page-title">{{ isEditing ? 'Task-Vorlage bearbeiten' : 'Neue Task-Vorlage' }}</h1>
      </div>
      <router-link to="/task_templates" class="btn btn-ghost">Zur Übersicht</router-link>
    </div>

    <div v-if="errorMessage" class="alert alert-danger" role="alert">
      {{ errorMessage }}
    </div>
    <div v-if="loading" class="alert alert-info">Vorlage wird geladen …</div>

    <form v-else class="form" @submit.prevent="submitTemplate">
      <div class="form-row">
        <div class="form-field">
          <label for="template-name">Name der Vorlage</label>
          <input id="template-name" v-model.trim="template.name" type="text" class="form-control" required autofocus>
        </div>
        <div class="form-field">
          <label for="task-title">Standard-Titel des Tasks</label>
          <input id="task-title" v-model.trim="template.task_title" type="text" class="form-control" required>
        </div>
      </div>
      <div class="form-row">
        <div class="form-field">
          <label for="task-description">Task-Beschreibung</label>
          <textarea id="task-description" v-model="template.task_description" class="form-control" rows="3"></textarea>
        </div>
        <div class="form-field">
          <label for="task-points">Task-Punkte bei Abschluss</label>
          <input id="task-points" v-model.number="template.task_points_upon_completion" type="number" min="0" class="form-control" required>
        </div>
      </div>

      <section class="subtasks-section">
        <div class="page-head">
          <div>
            <h2 class="section-title">Subtasks der Vorlage</h2>
            <p class="help-text">Die Reihenfolge wird beim Erstellen der Tasks übernommen.</p>
          </div>
          <button type="button" class="btn btn-secondary" @click="addSubtask">Subtask hinzufügen</button>
        </div>

        <div class="card-stack">
          <div v-for="(subtask, index) in template.subtasks" :key="subtask.id ?? index" class="card">
            <div class="card-head">
              <p class="card-title">Subtask {{ index + 1 }}</p>
              <div class="row-actions">
                <button type="button" class="btn btn-ghost btn-sm" :disabled="index === 0" @click="moveSubtask(index, -1)">
                  Nach oben
                </button>
                <button type="button" class="btn btn-ghost btn-sm" :disabled="index === template.subtasks.length - 1" @click="moveSubtask(index, 1)">
                  Nach unten
                </button>
                <button type="button" class="btn btn-danger btn-sm" :disabled="template.subtasks.length === 1" @click="removeSubtask(index)">
                  Entfernen
                </button>
              </div>
            </div>
            <div class="card-body">
              <div class="form-row">
                <div class="form-field">
                  <label :for="`subtask-title-${index}`">Titel</label>
                  <input :id="`subtask-title-${index}`" v-model.trim="subtask.title" type="text" class="form-control" required>
                </div>
                <div class="form-field">
                  <label :for="`subtask-points-${index}`">Punkte bei Abschluss</label>
                  <input :id="`subtask-points-${index}`" v-model.number="subtask.points_upon_completion" type="number" min="0" class="form-control" required>
                </div>
              </div>
              <div class="form-field">
                <label :for="`subtask-description-${index}`">Beschreibung</label>
                <textarea :id="`subtask-description-${index}`" v-model="subtask.description" class="form-control" rows="2"></textarea>
              </div>
            </div>
          </div>
        </div>
      </section>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary" :disabled="submitting">
          {{ submitting ? 'Wird gespeichert …' : 'Vorlage speichern' }}
        </button>
        <router-link to="/task_templates" class="btn btn-ghost">Abbrechen</router-link>
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
    errorMessage.value = 'Die Vorlage konnte nicht geladen werden.';
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
    errorMessage.value = validationMessage(error, 'Die Vorlage konnte nicht gespeichert werden.');
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
