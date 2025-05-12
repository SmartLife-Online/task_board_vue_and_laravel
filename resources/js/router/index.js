import { createRouter, createWebHistory } from 'vue-router';
import LifeAreasIndex from '../views/LifeAreas/LifeAreasIndex.vue';
import LifeAreasCreate from '../views/LifeAreas/LifeAreasCreate.vue';
import LifeAreasEdit from '../views/LifeAreas/LifeAreasEdit.vue';
import CategoriesIndex from '../views/Categories/CategoriesIndex.vue';
import CategoriesCreate from '../views/Categories/CategoriesCreate.vue';
import CategoriesEdit from '../views/Categories/CategoriesEdit.vue';
import ProjectsIndex from '../views/Projects/ProjectsIndex.vue';
import ProjectsCreateToCategory from '../views/Projects/ProjectsCreateToCategory.vue';
import ProjectsCreateToProject from '../views/Projects/ProjectsCreateToProject.vue';
import ProjectsEdit from '../views/Projects/ProjectsEdit.vue';
import TasksIndex from '../views/Tasks/TasksIndex.vue';
import TasksCreateToCategory from '../views/Tasks/TasksCreateToCategory.vue';
import TasksCreateToProject from '../views/Tasks/TasksCreateToProject.vue';
import TasksEdit from '../views/Tasks/TasksEdit.vue';
import SubtasksIndex from '../views/Subtasks/SubtasksIndex.vue';
import SubtasksCreate from '../views/Subtasks/SubtasksCreate.vue';
import SubtasksEdit from '../views/Subtasks/SubtasksEdit.vue';
import HabitsIndex from '../views/Habits/HabitsIndex.vue';
import HabitsCreateToCategory from '../views/Habits/HabitsCreateToCategory.vue';
import HabitsCreateToProject from '../views/Habits/HabitsCreateToProject.vue';
import HabitsEdit from '../views/Habits/HabitsEdit.vue';

const routes = [
  {
    path: '/users/:id/add_life_area',
    name: 'LifeAreasCreate',
    component: LifeAreasCreate
  },
  {
    path: '/life_areas',
    name: 'LifeAreasIndex',
    component: LifeAreasIndex
  },
  {
    path: '/life_areas/:id',
    name: 'LifeAreasEdit',
    component: LifeAreasEdit
  },
  {
    path: '/life_areas/:id/add_category',
    name: 'CategoriesCreate',
    component: CategoriesCreate
  },
  {
    path: '/categories',
    name: 'CategoriesIndex',
    component: CategoriesIndex
  },
  {
    path: '/categories/:id',
    name: 'CategoriesEdit',
    component: CategoriesEdit
  },
  {
    path: '/categories/:id/add_project_to_category',
    name: 'ProjectsCreateToCategory',
    component: ProjectsCreateToCategory
  },
  {
    path: '/projects/:id/add_project_to_project',
    name: 'ProjectsCreateToProject',
    component: ProjectsCreateToProject
  },
  {
    path: '/categories/:id/add_task',
    name: 'TasksCreateToCategory',
    component: TasksCreateToCategory
  },
  {
    path: '/categories/:id/add_habit',
    name: 'HabitsCreateToCategory',
    component: HabitsCreateToCategory
  },
  {
    path: '/projects',
    name: 'ProjectsIndex',
    component: ProjectsIndex
  },
  {
    path: '/projects/:id',
    name: 'ProjectsEdit',
    component: ProjectsEdit
  },
  {
    path: '/projects/:id/add_task',
    name: 'TasksCreateToProject',
    component: TasksCreateToProject
  },
  {
    path: '/projects/:id/add_habit',
    name: 'HabitsCreateToProject',
    component: HabitsCreateToProject
  },
  {
    path: '/tasks',
    name: 'TasksIndex',
    component: TasksIndex
  },
  {
    path: '/tasks/:id',
    name: 'TasksEdit',
    component: TasksEdit
  },
  {
    path: '/tasks/:id/add_subtask',
    name: 'SubtasksCreate',
    component: SubtasksCreate
  },
  {
    path: '/subtasks',
    name: 'SubtasksIndex',
    component: SubtasksIndex
  },
  {
    path: '/subtasks/:id',
    name: 'SubtasksEdit',
    component: SubtasksEdit
  },
  {
    path: '/habits',
    name: 'HabitsIndex',
    component: HabitsIndex
  },
  {
    path: '/habits/:id',
    name: 'HabitsEdit',
    component: HabitsEdit
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
