<template>
  <div class="page">
    <div class="page-head">
      <div>
        <p class="kicker">Single or bulk creation</p>
        <h1 class="page-title">Apply template</h1>
      </div>
      <router-link to="/task_templates" class="btn btn-ghost">Back to overview</router-link>
    </div>

    <div v-if="errorMessage" class="alert alert-danger" role="alert">
      {{ errorMessage }}
    </div>
    <div v-if="successMessage" class="alert" role="status">
      {{ successMessage }}
      <router-link to="/tasks">View created tasks</router-link>
    </div>
    <div v-if="loading" class="alert alert-info">Loading template and destinations…</div>

    <template v-else-if="template">
      <div class="card template-summary">
        <div class="card-head">
          <p class="card-title">{{ template.name }}</p>
          <span>{{ template.subtasks.length }} Subtasks pro Task</span>
        </div>
        <div class="card-body">
          <strong>{{ template.task_title }}</strong>
          <span v-if="template.task_description">{{ template.task_description }}</span>
          <span>{{ totalPoints }} total points per fully completed task</span>
        </div>
      </div>

      <form class="form" @submit.prevent="applyTemplate">
        <div class="form-row">
          <div class="form-field">
            <label for="destination">Destination for the new tasks</label>
            <select id="destination" v-model="destination" required>
              <option disabled value="">Select a project or category</option>
              <optgroup v-if="projects.length" label="Projects">
                <option v-for="project in projects" :key="`project-${project.id}`" :value="`project:${project.id}`">
                  {{ project.life_area }} / {{ project.category }} / {{ project.title }}
                </option>
              </optgroup>
              <optgroup v-if="categories.length" label="Categories (without a project)">
                <option v-for="category in categories" :key="`category-${category.id}`" :value="`category:${category.id}`">
                  {{ category.life_area }} / {{ category.title }}
                </option>
              </optgroup>
            </select>
          </div>
          <div class="form-field">
            <label for="day-schedule-part">Day-Schedule-Part (optional)</label>
            <input id="day-schedule-part" v-model.number="daySchedulePartId" type="number" min="1" class="form-control">
          </div>
        </div>

        <div class="form-field">
          <label for="task-titles">Task titles — one per line</label>
          <textarea id="task-titles" v-model="taskTitles" class="form-control task-title-list" rows="8" required></textarea>
          <p class="help-text">
            One line creates a single task; multiple lines create all tasks in one operation.
            Current: {{ parsedTitles.length }} of up to 200 task{{ parsedTitles.length === 1 ? '' : 's' }}.
          </p>
        </div>

        <div class="form-field form-field-check">
          <label for="completed">Mark the task and all its subtasks as completed immediately</label>
          <input id="completed" v-model="completed" type="checkbox" class="form-check-input">
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-primary" :disabled="submitting || parsedTitles.length === 0 || parsedTitles.length > 200">
            {{ submitting ? 'Creating tasks…' : `Create ${parsedTitles.length} task${parsedTitles.length === 1 ? '' : 's'}` }}
          </button>
          <router-link to="/task_templates" class="btn btn-ghost">Cancel</router-link>
        </div>
      </form>
    </template>
  </div>
</template>

<script setup lang="ts">
import axios from 'axios';
import { computed, onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import { Category, Project } from '../../types/ModelsIndex';
import { TaskTemplate } from '../../types/TaskTemplates';

const route = useRoute();
const templateId = Number(route.params.id);
const template = ref<TaskTemplate | null>(null);
const projects = ref<Project[]>([]);
const categories = ref<Category[]>([]);
const destination = ref('');
const taskTitles = ref('');
const daySchedulePartId = ref<number | ''>('');
const completed = ref(false);
const loading = ref(true);
const submitting = ref(false);
const errorMessage = ref('');
const successMessage = ref('');

const parsedTitles = computed(() => taskTitles.value
  .split(/\r?\n/)
  .map(title => title.trim())
  .filter(title => title !== '')
);

const totalPoints = computed(() => {
  if (!template.value) {
    return 0;
  }

  return Number(template.value.task_points_upon_completion)
    + template.value.subtasks.reduce((sum, subtask) => sum + Number(subtask.points_upon_completion), 0);
});

const validationMessage = (error: any, fallback: string): string => {
  const errors = error?.response?.data?.errors;

  if (errors) {
    return Object.values(errors).flat().join(' ');
  }

  return error?.response?.data?.message || fallback;
};

const loadData = async (): Promise<void> => {
  try {
    const [templateResponse, projectsResponse, categoriesResponse] = await Promise.all([
      axios.get(`/api/v1/task_templates/${templateId}`),
      axios.get('/api/v1/projects'),
      axios.get('/api/v1/categories'),
    ]);

    template.value = templateResponse.data;
    projects.value = projectsResponse.data.filter(project => (
      Number(project.active) === 1 && Number(project.completed) === 0
    ));
    categories.value = categoriesResponse.data;
    taskTitles.value = templateResponse.data.task_title;

    if (projects.value.length) {
      destination.value = `project:${projects.value[0].id}`;
    } else if (categories.value.length) {
      destination.value = `category:${categories.value[0].id}`;
    }

    try {
      const currentPartResponse = await axios.get('/api/v1/day_schedules/get_current_day_schedule_part');
      daySchedulePartId.value = currentPartResponse.data?.id || '';
    } catch (error) {
      daySchedulePartId.value = '';
    }
  } catch (error) {
    errorMessage.value = 'The template or available destinations could not be loaded.';
  } finally {
    loading.value = false;
  }
};

const applyTemplate = async (): Promise<void> => {
  const [destinationType, destinationId] = destination.value.split(':');

  if (!destinationType || !destinationId || parsedTitles.value.length === 0 || parsedTitles.value.length > 200) {
    errorMessage.value = 'Select a destination and enter between 1 and 200 task titles.';
    return;
  }

  submitting.value = true;
  errorMessage.value = '';
  successMessage.value = '';

  try {
    const response = await axios.post(`/api/v1/task_templates/${templateId}/instantiate`, {
      destination_type: destinationType,
      destination_id: Number(destinationId),
      tasks: parsedTitles.value.map(title => ({ title })),
      completed: completed.value,
      day_schedule_part_id: daySchedulePartId.value || null,
    });

    successMessage.value = `${response.data.created_count} task${response.data.created_count === 1 ? '' : 's'} and all related subtasks created successfully.`;
  } catch (error) {
    errorMessage.value = validationMessage(error, 'The tasks could not be created.');
  } finally {
    submitting.value = false;
  }
};

onMounted(loadData);
</script>

<style scoped>
.template-summary .card-head span,
.template-summary .card-body span,
.help-text {
  color: var(--color-muted);
  font-size: 14px;
}

.template-summary .card-body strong,
.template-summary .card-body span {
  display: block;
}

.task-title-list {
  min-height: 190px;
}

.help-text {
  margin: 4px 0 0;
}
</style>
