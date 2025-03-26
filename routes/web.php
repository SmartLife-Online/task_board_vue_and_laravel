<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\LifeAreasController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\SubtasksController;
use App\Http\Controllers\HabitController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('users/recalc_points', [UsersController::class, 'recalcPoints'])->name('users.recalcPoints');

Route::get('/refresh-csrf', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});

Route::get('api/v1/life_areas', [LifeAreasController::class, 'index'])->name('api.life_areas.index');
Route::get('api/v1/life_areas/{idLifeArea}', [LifeAreasController::class, 'get'])->name('api.life_areas.get');
Route::post('api/v1/life_areas/{idLifeArea}', [LifeAreasController::class, 'update'])->name('api.life_areas.update');

Route::get('api/v1/categories', [CategoriesController::class, 'index'])->name('api.categories.index');
Route::get('api/v1/categories/{idCategory}', [CategoriesController::class, 'get'])->name('api.categories.get');
Route::post('api/v1/categories/{idLifeArea}', [CategoriesController::class, 'store'])->name('api.categories.store');
Route::put('api/v1/categories/{idCategory}', [CategoriesController::class, 'update'])->name('api.categories.update');

Route::get('api/v1/projects', [ProjectsController::class, 'index'])->name('api.projects.index');
Route::get('api/v1/projects/not_complted', [ProjectsController::class, 'indexNotComplted'])->name('api.projects.indexNotComplted');
Route::get('api/v1/projects/complted', [ProjectsController::class, 'indexComplted'])->name('api.projects.indexComplted');
Route::get('api/v1/projects/{idProject}', [ProjectsController::class, 'get'])->name('api.projects.get');
Route::post('api/v1/projects/{idCategory}', [ProjectsController::class, 'store'])->name('api.projects.store');
Route::put('api/v1/projects/{idProject}', [ProjectsController::class, 'update'])->name('api.projects.update');
Route::patch('api/v1/projects/{idProject}/complete', [ProjectsController::class, 'complete'])->name('api.projects.complete');

Route::get('api/v1/tasks', [TasksController::class, 'index'])->name('api.tasks.index');
Route::get('api/v1/tasks/not_complted', [TasksController::class, 'indexNotComplted'])->name('api.tasks.indexNotComplted');
Route::get('api/v1/tasks/complted', [TasksController::class, 'indexComplted'])->name('api.tasks.indexComplted');
Route::get('api/v1/tasks/{idTask}', [TasksController::class, 'get'])->name('api.tasks.get');
Route::post('api/v1/tasks/to_category/{idCategory}', [TasksController::class, 'storeToCategory'])->name('api.tasks.to_category.store');
Route::post('api/v1/tasks/to_project/{idProject}', [TasksController::class, 'storeToProject'])->name('api.tasks.to_project.store');
Route::put('api/v1/tasks/{idTask}', [TasksController::class, 'update'])->name('api.tasks.update');
Route::patch('api/v1/tasks/{idTask}/complete', [TasksController::class, 'complete'])->name('api.tasks.complete');

Route::get('api/v1/subtasks', [SubtasksController::class, 'index'])->name('api.subtasks.index');
Route::get('api/v1/subtasks/not_complted', [SubtasksController::class, 'indexNotComplted'])->name('api.subtasks.indexNotComplted');
Route::get('api/v1/subtasks/complted', [SubtasksController::class, 'indexComplted'])->name('api.subtasks.indexComplted');
Route::get('api/v1/subtasks/{idSubtask}', [SubtasksController::class, 'get'])->name('api.subtasks.get');
Route::post('api/v1/subtasks/{idTask}', [SubtasksController::class, 'store'])->name('api.subtasks.store');
Route::put('api/v1/subtasks/{idSubtask}', [SubtasksController::class, 'update'])->name('api.subtasks.update');
Route::patch('api/v1/subtasks/{idSubtask}/complete', [SubtasksController::class, 'complete'])->name('api.subtasks.complete');

Route::get('api/v1/habits', [HabitController::class, 'index'])->name('api.habits.index');
Route::get('api/v1/habits/not_complted', [HabitController::class, 'indexNotComplted'])->name('api.habits.indexNotComplted');
Route::get('api/v1/habits/complted', [HabitController::class, 'indexComplted'])->name('api.habits.indexComplted');
Route::get('api/v1/habits/{ididHabit}', [HabitController::class, 'get'])->name('api.habits.get');
Route::post('api/v1/habits/to_category/{idCategory}', [HabitController::class, 'storeToCategory'])->name('api.habits.to_category.store');
Route::post('api/v1/habits/to_project/{idProject}', [HabitController::class, 'storeToProject'])->name('api.habits.to_project.store');
Route::put('api/v1/habits/{idHabit}', [HabitController::class, 'update'])->name('api.habits.update');
Route::patch('api/v1/habits/{idHabit}/count_up_completed', [HabitController::class, 'countUpCompleted'])->name('api.habits.count_up_completed');
Route::patch('api/v1/habits/{idHabit}/count_down_completed', [HabitController::class, 'countDownCompleted'])->name('api.habits.countDownCompleted');
Route::patch('api/v1/habits/{idHabit}/complete', [HabitController::class, 'complete'])->name('api.habits.complete');

Route::get('/{vue_capture?}', function () {
    return view('app');
})->where('vue_capture', '[\/\w\.-]*');

