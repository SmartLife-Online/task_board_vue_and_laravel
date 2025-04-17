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
    async fetchProjects({ commit }) {
      try {
        const response = await axios.get(projectsApiString);
        commit('setProjects', response.data);
      } catch (error) {
        console.error('Error fetching Projects:', error);
      }
    },
    async fetchNotCompltedProjects({ commit }) {
      try {
        const response = await axios.get(projectsApiString + 'not_complted');
        commit('setProjects', response.data);
      } catch (error) {
        console.error('Error fetching Projects:', error);
      }
    },
    async fetchCompltedProjects({ commit }) {
      try {
        const response = await axios.get(projectsApiString + 'complted');
        commit('setProjects', response.data);
      } catch (error) {
        console.error('Error fetching Projects:', error);
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
    async submitStoreProject({ commit }, {idCategory, formData}) {
      try {
        const response = await axios.post(projectsApiString + idCategory, formData);
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
    async fetchTasks({ commit }) {
      try {
        const response = await axios.get(tasksApiString);
        commit('setTasks', response.data);
      } catch (error) {
        console.error('Error fetching Tasks:', error);
      }
    },
    async fetchNotCompltedTasks({ commit }) {
      try {
        const response = await axios.get(tasksApiString + 'not_complted');
        commit('setTasks', response.data);
      } catch (error) {
        console.error('Error fetching Tasks:', error);
      }
    },
    async fetchCompltedTasks({ commit }) {
      try {
        const response = await axios.get(tasksApiString + 'complted');
        commit('setTasks', response.data);
      } catch (error) {
        console.error('Error fetching Tasks:', error);
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
    async fetchSubtasks({ commit }, idTask) {
      try {
        const response = await axios.get(subtasksApiString + 'all' (idTask ? ('/' + idTask) : ''));
        commit('setSubtasks', response.data);
      } catch (error) {
        console.error('Error fetching Subtasks:', error);
      }
    },
    async fetchNotCompltedSubtasks({ commit }, idTask) {
      try {
        const response = await axios.get(subtasksApiString + 'not_complted' + (idTask ? ('/' + idTask) : ''));
        commit('setSubtasks', response.data);
      } catch (error) {
        console.error('Error fetching not compltedS Subtasks:', error);
      }
    },
    async fetchCompltedSubtasks({ commit }, idTask) {
      try {
        const response = await axios.get(subtasksApiString + 'complted' + (idTask ? ('/' + idTask) : ''));
        commit('setSubtasks', response.data);
      } catch (error) {
        console.error('Error fetching compltedS Subtasks:', error);
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
    async fetchHabits({ commit }) {
      try {
        const response = await axios.get(habitsApiString);
        commit('setHabits', response.data);
      } catch (error) {
        console.error('Error fetching habits:', error);
      }
    },
    async fetchNotCompltedHabits({ commit }) {
      try {
        const response = await axios.get(habitsApiString + 'not_complted');
        commit('setHabits', response.data);
      } catch (error) {
        console.error('Error fetching Habits:', error);
      }
    },
    async fetchCompltedHabits({ commit }) {
      try {
        const response = await axios.get(habitsApiString + 'complted');
        commit('setHabits', response.data);
      } catch (error) {
        console.error('Error fetching Habits:', error);
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
    getUser: state => state.user,
  }
});

export default store;
