import { createStore } from 'vuex';
import axios from 'axios';
import router from '../router';

const apiString = '/api/v1/';
const lifeAreaApiString = apiString + 'life_areas/';
const categoriesApiString = apiString + 'categories/';
const projectsApiString = apiString + 'projects/';
const tasksApiString = apiString + 'tasks/';
const subtasksApiString = apiString + 'subtasks/';
const habitsApiString = apiString + 'habits/';
const daySchedulesApiString = apiString + 'day_schedules/';
const usersApiString = apiString + 'users/';

const store = createStore({
  state: {
    lifeAreas: [],
    lifeArea: [],
    categories: [],
    category: [],
    projects: [],
    project: [],
    tasks: [],
    task: [],
    subtasks: [],
    subtask: [],
    habits: [],
    habit: [],
    daySchedule: [],
    daySchedules: [],
    currentDaySchedule: [],
    currentDaySchedulePart: [],
    user: [],
  },
  mutations: {
    setLifeAreas(state, lifeAreas) {
      state.lifeAreas = lifeAreas;
    },
    setLifeArea(state, lifeArea) {
      state.lifeArea = lifeArea;
    },
    setCategories(state, categories) {
      state.categories = categories;
    },
    setCategory(state, category) {
      state.category = category;
    },
    setProjects(state, projects) {
      state.projects = projects;
    },
    setProject(state, project) {
      state.project = project;
    },
    setTasks(state, tasks) {
      state.tasks = tasks;
    },
    setTask(state, task) {
      state.task = task;
    },
    setSubtasks(state, subtasks) {
      state.subtasks = subtasks;
    },
    setSubtask(state, subtask) {
      state.subtask = subtask;
    },
    setHabits(state, habits) {
      state.habits = habits;
    },
    setHabit(state, habit) {
      state.habit = habit;
    },
    setDaySchedules(state, daySchedules) {
      state.daySchedules = daySchedules;
    },
    setDaySchedule(state, daySchedule) {
      state.daySchedule = daySchedule;
    },
    setCurrentDaySchedule(state, currentDaySchedule) {
      state.currentDaySchedule = currentDaySchedule;
    },
    setCurrentDaySchedulePart(state, currentDaySchedulePart) {
      state.currentDaySchedulePart = currentDaySchedulePart;
    },
    setUser(state, user) {
      state.user = user;
    },
  },
  actions: {
    async fetchLifeAreas({ commit }) {
      try {
        const response = await axios.get(lifeAreaApiString);
        commit('setLifeAreas', response.data);
      } catch (error) {
        console.error('Error fetching life-areas:', error);
      }
    },
    async fetchLifeArea({ commit }, idLifeArea) {
      try {
        const response = await axios.get(lifeAreaApiString + idLifeArea);
        commit('setLifeArea', response.data);
      } catch (error) {
        console.error('Error fetching life-area:', error);
      }
    },
    async submitLifeArea({ commit }, {idLifeArea, formData}) {
      try {
        const response = await axios.post(lifeAreaApiString + idLifeArea, formData);
        commit('setLifeArea', response.data);
        router.push({ name: 'LifeAreasIndex' });
      } catch (error) {
        console.error('Error submitting life-area form data:', error);
      }
    },
    async deleteLifeArea({ commit }, lifeArea) {
      try {
        const response = await axios.delete(lifeAreaApiString + lifeArea.id);
        
        if(response.data.success) {
          lifeArea.active = false;
          lifeArea.removed = true;
        }
      } catch (error) {
        console.error('Error deleting life-area:', error);
      }
    },
    async fetchCategories({ commit }) {
      try {
        const response = await axios.get(categoriesApiString);
        commit('setCategories', response.data);
      } catch (error) {
        console.error('Error fetching Categories:', error);
      }
    },
    async fetchCategory({ commit }, idCategory) {
      try {
        const response = await axios.get(categoriesApiString + idCategory);
        commit('setCategory', response.data);
      } catch (error) {
        console.error('Error fetching Category:', error);
      }
    },
    async submitStoreCategory({ commit }, {idLifeArea, formData}) {
      try {
        const response = await axios.post(categoriesApiString + idLifeArea, formData);
        commit('setCategory', response.data);
        router.push({ name: 'CategoriesIndex' });
      } catch (error) {
        console.error('Error submitting Category form data:', error);
      }
    },
    async submitEditCategory({ commit }, {idCategory, formData}) {
      try {
        const response = await axios.put(categoriesApiString + idCategory, formData);
        commit('setCategory', response.data);
        router.push({ name: 'CategoriesIndex' });
      } catch (error) {
        console.error('Error submitting Category form data:', error);
      }
    },
    async deleteCategory({ commit }, category) {
      try {
        const response = await axios.delete(categoriesApiString + category.id);
        
        if(response.data.success) {
          category.active = false;
          category.removed = true;
        }
      } catch (error) {
        console.error('Error deleting Category:', error);
      }
    },
    async fetchProjects({ commit }) {
      try {
        const response = await axios.get(projectsApiString);
        commit('setProjects', response.data);
      } catch (error) {
        console.error('Error fetching Projects:', error);
      }
    },
    async fetchNotCompletedProjects({ commit }) {
      try {
        const response = await axios.get(projectsApiString + 'not_completed');
        commit('setProjects', response.data);
      } catch (error) {
        console.error('Error fetching Projects:', error);
      }
    },
    async fetchCompletedProjects({ commit }) {
      try {
        const response = await axios.get(projectsApiString + 'completed');
        commit('setProjects', response.data);
      } catch (error) {
        console.error('Error fetching Projects:', error);
      }
    },
    async fetchDeletedProjects({ commit }) {
      try {
        const response = await axios.get(projectsApiString + 'deleted');
        commit('setProjects', response.data);
      } catch (error) {
        console.error('Error fetching deleted Projects:', error);
      }
    },
    async fetchProject({ commit }, idProject) {
      try {
        const response = await axios.get(projectsApiString + idProject);
        commit('setProject', response.data);
      } catch (error) {
        console.error('Error fetching Project:', error);
      }
    },
    async submitStoreProjectToCategory({ commit }, {idCategory, formData}) {
      try {
        const response = await axios.post(projectsApiString + 'store_to_category/' + idCategory, formData);
        commit('setProject', response.data);
        router.push({ name: 'ProjectsIndex' });
      } catch (error) {
        console.error('Error submitting Project form data:', error);
      }
    },
    async submitStoreProjectToProject({ commit }, {idProject, formData}) {
      try {
        const response = await axios.post(projectsApiString + 'store_to_parent_project/' + idProject, formData);
        commit('setProject', response.data);
        router.push({ name: 'ProjectsIndex' });
      } catch (error) {
        console.error('Error submitting Project form data:', error);
      }
    },
    async submitEditProject({ commit }, {idProject, formData}) {
      try {
        const response = await axios.put(projectsApiString + idProject, formData);
        commit('setProject', response.data);
        router.push({ name: 'ProjectsIndex' });
      } catch (error) {
        console.error('Error submitting Project form data:', error);
      }
    },
    async completeProject({ commit }, project) {
      try {
        const response = await axios.patch(projectsApiString + project.id + '/complete');
        
        project.completed = true;
      } catch (error) {
        console.error('Error completing project:', error);
      }
    },
    async recalcProject({ commit }, project) {
      try {
        const response = await axios.patch(projectsApiString + project.id + '/recalc_task');
        
        project.points = response.data.points;
      } catch (error) {
        console.error('Error deleting Project:', error);
      }
    },
    async deleteProject({ commit }, project) {
      try {
        const response = await axios.delete(projectsApiString + project.id);
        
        if(response.data.success) {
          project.active = false;
          project.removed = true;
        }
      } catch (error) {
        console.error('Error deleting Project:', error);
      }
    },
    async fetchTasks({ commit }, {idProject, idDaySchedule}) {
      try {
        const urlString = idDaySchedule ? (daySchedulesApiString + 'all/' + idDaySchedule) : (tasksApiString + 'all/' + (idProject ? (idProject) : ''));

        const response = await axios.get(urlString);

        commit('setTasks', response.data);
      } catch (error) {
        console.error('Error fetching Tasks:', error);
      }
    },
    async fetchNotCompletedTasks({ commit }, {idProject, idDaySchedule}) {
      try {
        const urlString = idDaySchedule ? (daySchedulesApiString + 'not_completed/' + idDaySchedule) : (tasksApiString + 'not_completed' + (idProject ? ('/' + idProject) : ''));

        const response = await axios.get(urlString);

        commit('setTasks', response.data);
      } catch (error) {
        console.error('Error fetching Tasks:', error);
      }
    },
    async fetchCompletedTasks({ commit }, {idProject, idDaySchedule}) {
      try {
        const urlString = idDaySchedule ? (daySchedulesApiString + 'completed/' + idDaySchedule) : (tasksApiString + 'completed' + (idProject ? ('/' + idProject) : ''));

        const response = await axios.get(urlString);
        
        commit('setTasks', response.data);
      } catch (error) {
        console.error('Error fetching Tasks:', error);
      }
    },
    async fetchDeletedTasks({ commit }, {idProject, idDaySchedule}) {
      try {
        const urlString = idDaySchedule ? (daySchedulesApiString + 'deleted/' + idDaySchedule) : (tasksApiString + 'deleted' + (idProject ? ('/' + idProject) : ''));

        const response = await axios.get(urlString);
        
        commit('setTasks', response.data);
      } catch (error) {
        console.error('Error fetching deleted Tasks:', error);
      }
    },
    async fetchTask({ commit }, idTask) {
      try {
        const response = await axios.get(tasksApiString + idTask);
        commit('setTask', response.data);
      } catch (error) {
        console.error('Error fetching Task:', error);
      }
    },
    async submitStoreTaskToCategory({ commit }, {idCategory, formData}) {
      try {
        const response = await axios.post(tasksApiString + 'to_category/' + idCategory, formData);
        commit('setTask', response.data);
        router.push({ name: 'TasksIndex' });
      } catch (error) {
        console.error('Error submitting Task form data:', error);
      }
    },
    async submitStoreTaskToProject({ commit }, {idProject, formData}) {
      try {
        const response = await axios.post(tasksApiString + 'to_project/' + idProject, formData);
        commit('setTask', response.data);
        router.push({ name: 'TasksIndex' });
      } catch (error) {
        console.error('Error submitting Task form data:', error);
      }
    },
    async submitEditTask({ commit }, {idTask, formData}) {
      try {
        const response = await axios.put(tasksApiString + idTask, formData);
        commit('setTask', response.data);
        router.push({ name: 'TasksIndex' });
      } catch (error) {
        console.error('Error submitting Task form data:', error);
      }
    },
    async completeTask({ commit }, task) {
      try {
        const response = await axios.patch(tasksApiString + task.id + '/complete');
        
        task.completed = true;
      } catch (error) {
        console.error('Error completing Task:', error);
      }
    },
    async recalcTask({ commit }, task) {
      try {
        const response = await axios.patch(tasksApiString + task.id + '/recalc_task');
        
        task.points = response.data.points;
      } catch (error) {
        console.error('Error deleting Task:', error);
      }
    },
    async deleteTask({ commit }, task) {
      try {
        const response = await axios.delete(tasksApiString + task.id);
        
        if(response.data.success) {
          task.active = false;
          task.removed = true;
        }
      } catch (error) {
        console.error('Error deleting Task:', error);
      }
    },
    async fetchSubtasks({ commit }, idTask) {
      try {
        const response = await axios.get(subtasksApiString + 'all' + (idTask ? ('/' + idTask) : ''));
        commit('setSubtasks', response.data);
      } catch (error) {
        console.error('Error fetching Subtasks:', error);
      }
    },
    async fetchNotCompletedSubtasks({ commit }, idTask) {
      try {
        const response = await axios.get(subtasksApiString + 'not_completed' + (idTask ? ('/' + idTask) : ''));
        commit('setSubtasks', response.data);
      } catch (error) {
        console.error('Error fetching not completed Subtasks:', error);
      }
    },
    async fetchCompletedSubtasks({ commit }, idTask) {
      try {
        const response = await axios.get(subtasksApiString + 'completed' + (idTask ? ('/' + idTask) : ''));
        commit('setSubtasks', response.data);
      } catch (error) {
        console.error('Error fetching completed Subtasks:', error);
      }
    },
    async fetchDeletedSubtasks({ commit }, idTask) {
      try {
        const response = await axios.get(subtasksApiString + 'deleted' + (idTask ? ('/' + idTask) : ''));
        commit('setSubtasks', response.data);
      } catch (error) {
        console.error('Error fetching deleted Subtasks:', error);
      }
    },
    async fetchSubtask({ commit }, idSubtask) {
      try {
        const response = await axios.get(subtasksApiString + idSubtask);
        commit('setSubtask', response.data);
      } catch (error) {
        console.error('Error fetching Subtask:', error);
      }
    },
    async submitStoreSubtask({ commit }, {idTask, formData}) {
      try {
        const response = await axios.post(subtasksApiString + idTask, formData);
        commit('setSubtask', response.data);
        router.push({ name: 'SubtasksIndex' });
      } catch (error) {
        console.error('Error submitting Subtask form data:', error);
      }
    },
    async submitEditSubtask({ commit }, {idSubtask, formData}) {
      try {
        const response = await axios.put(subtasksApiString + idSubtask, formData);
        commit('setSubtask', response.data);
        router.push({ name: 'SubtasksIndex' });
      } catch (error) {
        console.error('Error submitting Subtask form data:', error);
      }
    },
    async completeSubtask({ commit }, subtask) {
      try {
        const response = await axios.patch(subtasksApiString + subtask.id + '/complete');
        
        subtask.completed = true;
      } catch (error) {
        console.error('Error completing Subtask:', error);
      }
    },
    async deleteSubtask({ commit }, subtask) {
      try {
        const response = await axios.delete(subtasksApiString + subtask.id);
        
        if(response.data.success) {
          subtask.active = false;
          subtask.removed = true;
        }
      } catch (error) {
        console.error('Error deleting Subtask:', error);
      }
    },
    async fetchHabits({ commit }) {
      try {
        const response = await axios.get(habitsApiString);
        commit('setHabits', response.data);
      } catch (error) {
        console.error('Error fetching habits:', error);
      }
    },
    async fetchNotCompletedHabits({ commit }) {
      try {
        const response = await axios.get(habitsApiString + 'not_completed');
        commit('setHabits', response.data);
      } catch (error) {
        console.error('Error fetching Habits:', error);
      }
    },
    async fetchCompletedHabits({ commit }) {
      try {
        const response = await axios.get(habitsApiString + 'completed');
        commit('setHabits', response.data);
      } catch (error) {
        console.error('Error fetching Habits:', error);
      }
    },
    async fetchDeletedHabits({ commit }) {
      try {
        const response = await axios.get(habitsApiString + 'deleted');
        commit('setHabits', response.data);
      } catch (error) {
        console.error('Error fetching deleted Habits:', error);
      }
    },
    async fetchHabit({ commit }, idHabit) {
      try {
        const response = await axios.get(habitsApiString + idHabit);
        commit('setHabit', response.data);
      } catch (error) {
        console.error('Error fetching habit:', error);
      }
    },
    async submitStoreHabitToCategory({ commit }, {idCategory, formData}) {
      try {
        const response = await axios.post(habitsApiString + 'to_category/' + idCategory, formData);
        commit('setHabit', response.data);
        router.push({ name: 'HabitsIndex' });
      } catch (error) {
        console.error('Error submitting habit form data:', error);
      }
    },
    async submitStoreHabitToProject({ commit }, {idProject, formData}) {
      try {
        const response = await axios.post(habitsApiString + 'to_project/' + idProject, formData);
        commit('setHabit', response.data);
        router.push({ name: 'HabitsIndex' });
      } catch (error) {
        console.error('Error submitting Habit form data:', error);
      }
    },
    async submitEditHabit({ commit }, {idHabit, formData}) {
      try {
        const response = await axios.put(habitsApiString + idHabit, formData);
        commit('setHabit', response.data);
        router.push({ name: 'HabitsIndex' });
      } catch (error) {
        console.error('Error submitting habit form data:', error);
      }
    },
    async countUpCompletedInHabit({ commit }, habit) {
      try {
        const response = await axios.patch(habitsApiString + habit.id + '/count_up_completed');
        
        habit.count_completed = response.data.count_completed;
        habit.points = response.data.points;
      } catch (error) {
        console.error('Error add one to count_completed in habit:', error);
      }
    },
    async countDownCompletedInHabit({ commit }, habit) {
      try {
        const response = await axios.patch(habitsApiString + habit.id + '/count_down_completed');
        
        habit.count_completed = response.data.count_completed;
        habit.points = response.data.points;
      } catch (error) {
        console.error('Error sub one to count_completed in habit:', error);
      }
    },
    async completeHabit({ commit }, habit) {
      try {
        const response = await axios.patch(habitsApiString + habit.id + '/complete');
        
        habit.completed = true;
      } catch (error) {
        console.error('Error completing Habit:', error);
      }
    },
    async deleteHabit({ commit }, habit) {
      try {
        const response = await axios.delete(habitsApiString + habit.id);
        
        if(response.data.success) {
          habit.active = false;
          habit.removed = true;
        }
      } catch (error) {
        console.error('Error deleting Habit:', error);
      }
    },
    async fetchDaySchedule({ commit }, idDaySchedule) {
      try {
        const response = await axios.get(daySchedulesApiString + idDaySchedule);
        commit('setDaySchedule', response.data);
      } catch (error) {
        console.error('Error fetching Day-Schedule:', error);
      }
    },
    async fetchCurrentDaySchedule({ commit }) {
      try {
        const response = await axios.get(daySchedulesApiString + 'get_current_day_schedule');
        commit('setCurrentDaySchedule', response.data);
      } catch (error) {
        console.error('Error fetching current Day-Schedule:', error);
      }
    },
    async fetchCurrentDaySchedulePart({ commit }) {
      try {
        const response = await axios.get(daySchedulesApiString + 'get_current_day_schedule_part');
        commit('setCurrentDaySchedulePart', response.data);
      } catch (error) {
        console.error('Error fetching current Day-Schedule-Part:', error);
      }
    },
    async fetchInProgressDaySchedules({ commit }) {
      try {
        const response = await axios.get(daySchedulesApiString + 'get_in_progress');
        
        commit('setDaySchedules', response.data);
      } catch (error) {
        console.error('Error fetching "In progress" Day-Schedules:', error);
      }
    },
    async fetchPendingDaySchedules({ commit }) {
      try {
        const response = await axios.get(daySchedulesApiString + 'get_pending');
        
        commit('setDaySchedules', response.data);
      } catch (error) {
        console.error('Error fetching "Pending" Day-Schedules:', error);
      }
    },
    async fetchSuccessfulDaySchedules({ commit }) {
      try {
        const response = await axios.get(daySchedulesApiString + 'get_successful');
        
        commit('setDaySchedules', response.data);
      } catch (error) {
        console.error('Error fetching "Successful" Day-Schedules:', error);
      }
    },
    async fetchFailedDaySchedules({ commit }) {
      try {
        const response = await axios.get(daySchedulesApiString + 'get_failed');
        
        commit('setDaySchedules', response.data);
      } catch (error) {
        console.error('Error fetching "Failed" Day-Schedules:', error);
      }
    },
    async fetchDaySchedules({ commit }) {
      try {
        const response = await axios.get(daySchedulesApiString + 'get_all');
        
        commit('setDaySchedules', response.data);
      } catch (error) {
        console.error('Error fetching all Day-Schedules:', error);
      }
    },
    async fetchDeletedDaySchedules({ commit }) {
      try {
        const response = await axios.get(daySchedulesApiString + 'get_deleted');
        
        commit('setDaySchedules', response.data);
      } catch (error) {
        console.error('Error fetching "Deleted" Day-Schedules:', error);
      }
    },
    async activateDaySchedule({ commit }, daySchedule) {
      try {
        const response = await axios.get(daySchedulesApiString + daySchedule.id + '/activate');
        
        daySchedule.status_id = response.data.status_id;
      } catch (error) {
        console.error('Error fetching "Deleted" Day-Schedules:', error);
      }
    },
    async completeDaySchedule({ commit }, daySchedule) {
      try {
        const response = await axios.get(daySchedulesApiString + daySchedule.id + '/complete');
        
        daySchedule.status_id = response.data.status_id;
      } catch (error) {
        console.error('Error fetching "Deleted" Day-Schedules:', error);
      }
    },
    async completeDayScheduleById({ commit }, idDaySchedule) {
      try {
        const response = await axios.get(daySchedulesApiString + idDaySchedule + '/complete');
        
        router.push({ name: 'DaySchedulesIndex' });
      } catch (error) {
        console.error('Error fetching "Deleted" Day-Schedules:', error);
      }
    },
    async submitStoreDaySchedule({ commit }, {formData}) {
      try {
        const response = await axios.post(daySchedulesApiString, formData);
        commit('setDaySchedule', response.data);
        router.push({ name: 'DaySchedulesIndex' });
      } catch (error) {
        console.error('Error submitting Day-Schedule form data:', error);
      }
    },
    async submitEditDaySchedule({ commit }, {idDaySchedule, formData}) {
      try {
        const response = await axios.put(daySchedulesApiString + idDaySchedule, formData);
        commit('setDaySchedule', response.data);
        router.push({ name: 'DaySchedulesIndex' });
      } catch (error) {
        console.error('Error submitting Day-Schedule form data:', error);
      }
    },
    async deleteDaySchedule({ commit }, daySchedule) {
      try {
        const response = await axios.delete(daySchedulesApiString + daySchedule.id + '/delete');
        
        if(response.data.success) {
          daySchedule.active = false;
          daySchedule.removed = true;
        }
      } catch (error) {
        console.error('Error fetching "Deleted" Day-Schedules:', error);
      }
    },
    async fetchUser({ commit }, idUser) {
      try {
        const response = await axios.get(usersApiString + idUser);
        commit('setUser', response.data);
      } catch (error) {
        console.error('Error fetching User:', error);
      }
    },
    async recalcUserPoints({ commit }, user) {
      try {
        const response = await axios.patch(usersApiString + user.id + '/recalc_user_points');
        
        user.points = response.data.points;
      } catch (error) {
        console.error('Error recalc user points:', error);
      }
    },
  },
  getters: {
    getLifeAreas: state => state.lifeAreas,
    getLifeArea: state => state.lifeArea,
    getCategories: state => state.categories,
    getCategory: state => state.category,
    getProjects: state => state.projects,
    getProject: state => state.project,
    getTasks: state => state.tasks,
    getTask: state => state.task,
    getSubtasks: state => state.subtasks,
    getSubtask: state => state.subtask,
    getHabits: state => state.habits,
    getHabit: state => state.habit,
    getDaySchedules: state => state.daySchedules,
    getDaySchedule: state => state.daySchedule,
    getCurrentDaySchedule: state => state.currentDaySchedule,
    getCurrentDaySchedulePart: state => state.currentDaySchedulePart,
    getUser: state => state.user,
  }
});

export default store;
