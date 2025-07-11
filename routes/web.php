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
Route::patch('api/v1/users/{idUser}/recalc_user_points', [UsersController::class, 'recalcUserPoints'])->name('users.recalcUserPoints');
Route::get('api/v1/users/{idUser}', [UsersController::class, 'get'])->name('api.users.get');

Route::get('/refresh-csrf', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});

Route::get('api/v1/life_areas', [LifeAreasController::class, 'index'])->name('api.life_areas.index');
Route::get('api/v1/life_areas/{idLifeArea}', [LifeAreasController::class, 'get'])->name('api.life_areas.get');
Route::post('api/v1/life_areas/{idLifeArea}', [LifeAreasController::class, 'update'])->name('api.life_areas.update');
Route::delete('api/v1/life_areas/{idLifeArea}', [LifeAreasController::class, 'delete'])->name('api.life_areas.delete');

Route::get('api/v1/categories', [CategoriesController::class, 'index'])->name('api.categories.index');
Route::get('api/v1/categories/{idCategory}', [CategoriesController::class, 'get'])->name('api.categories.get');
Route::post('api/v1/categories/{idLifeArea}', [CategoriesController::class, 'store'])->name('api.categories.store');
Route::put('api/v1/categories/{idCategory}', [CategoriesController::class, 'update'])->name('api.categories.update');
Route::delete('api/v1/categories/{idCategory}', [CategoriesController::class, 'delete'])->name('api.categories.delete');

Route::get('api/v1/projects', [ProjectsController::class, 'index'])->name('api.projects.index');
Route::get('api/v1/projects/not_complted', [ProjectsController::class, 'indexNotComplted'])->name('api.projects.indexNotComplted');
Route::get('api/v1/projects/complted', [ProjectsController::class, 'indexComplted'])->name('api.projects.indexComplted');
Route::get('api/v1/projects/deleted', [ProjectsController::class, 'indexDeleted'])->name('api.projects.indexDeleted');
Route::get('api/v1/projects/{idProject}', [ProjectsController::class, 'get'])->name('api.projects.get');
Route::post('api/v1/projects/store_to_category/{idCategory}', [ProjectsController::class, 'storeToCategory'])->name('api.projects.storeToCategory');
Route::post('api/v1/projects/store_to_parent_project/{idProject}', [ProjectsController::class, 'storeToProject'])->name('api.projects.store_to_parent_project');
Route::put('api/v1/projects/{idProject}', [ProjectsController::class, 'update'])->name('api.projects.update');
Route::patch('api/v1/projects/{idProject}/complete', [ProjectsController::class, 'complete'])->name('api.projects.complete');
Route::delete('api/v1/projects/{idProject}', [ProjectsController::class, 'delete'])->name('api.projects.delete');

Route::get('api/v1/tasks/all/{idProject?}', [TasksController::class, 'index'])->name('api.tasks.index');
Route::get('api/v1/tasks/not_complted/{idProject?}', [TasksController::class, 'indexNotComplted'])->name('api.tasks.indexNotComplted');
Route::get('api/v1/tasks/complted/{idProject?}', [TasksController::class, 'indexComplted'])->name('api.tasks.indexComplted');
Route::get('api/v1/tasks/deleted/{idProject?}', [TasksController::class, 'indexDeleted'])->name('api.tasks.indexDeleted');
Route::get('api/v1/tasks/{idTask}', [TasksController::class, 'get'])->name('api.tasks.get');
Route::post('api/v1/tasks/to_category/{idCategory}', [TasksController::class, 'storeToCategory'])->name('api.tasks.to_category.store');
Route::post('api/v1/tasks/to_project/{idProject}', [TasksController::class, 'storeToProject'])->name('api.tasks.to_project.store');
Route::put('api/v1/tasks/{idTask}', [TasksController::class, 'update'])->name('api.tasks.update');
Route::patch('api/v1/tasks/{idTask}/complete', [TasksController::class, 'complete'])->name('api.tasks.complete');
Route::patch('api/v1/tasks/{idTask}/recalc_task', [TasksController::class, 'recalcTask'])->name('api.tasks.recalcTask');
Route::delete('api/v1/tasks/{idTask}', [TasksController::class, 'delete'])->name('api.tasks.delete');

Route::get('api/v1/subtasks/all/{idTask?}', [SubtasksController::class, 'index'])->name('api.subtasks.index');
Route::get('api/v1/subtasks/not_complted/{idTask?}', [SubtasksController::class, 'indexNotComplted'])->name('api.subtasks.indexNotComplted');
Route::get('api/v1/subtasks/complted/{idTask?}', [SubtasksController::class, 'indexComplted'])->name('api.subtasks.indexComplted');
Route::get('api/v1/subtasks/deleted/{idTask?}', [SubtasksController::class, 'indexDeleted'])->name('api.subtasks.indexDeleted');
Route::get('api/v1/subtasks/{idSubtask}', [SubtasksController::class, 'get'])->name('api.subtasks.get');
Route::post('api/v1/subtasks/{idTask}', [SubtasksController::class, 'store'])->name('api.subtasks.store');
Route::put('api/v1/subtasks/{idSubtask}', [SubtasksController::class, 'update'])->name('api.subtasks.update');
Route::patch('api/v1/subtasks/{idSubtask}/complete', [SubtasksController::class, 'complete'])->name('api.subtasks.complete');
Route::delete('api/v1/subtasks/{idSubtask}', [SubtasksController::class, 'delete'])->name('api.subtasks.delete');

Route::get('api/v1/habits', [HabitController::class, 'index'])->name('api.habits.index');
Route::get('api/v1/habits/not_complted', [HabitController::class, 'indexNotComplted'])->name('api.habits.indexNotComplted');
Route::get('api/v1/habits/complted', [HabitController::class, 'indexComplted'])->name('api.habits.indexComplted');
Route::get('api/v1/habits/deleted', [HabitController::class, 'indexDeleted'])->name('api.habits.indexDeleted');
Route::get('api/v1/habits/{idHabit}', [HabitController::class, 'get'])->name('api.habits.get');
Route::post('api/v1/habits/to_category/{idCategory}', [HabitController::class, 'storeToCategory'])->name('api.habits.to_category.store');
Route::post('api/v1/habits/to_project/{idProject}', [HabitController::class, 'storeToProject'])->name('api.habits.to_project.store');
Route::put('api/v1/habits/{idHabit}', [HabitController::class, 'update'])->name('api.habits.update');
Route::patch('api/v1/habits/{idHabit}/count_up_completed', [HabitController::class, 'countUpCompleted'])->name('api.habits.count_up_completed');
Route::patch('api/v1/habits/{idHabit}/count_down_completed', [HabitController::class, 'countDownCompleted'])->name('api.habits.countDownCompleted');
Route::patch('api/v1/habits/{idHabit}/complete', [HabitController::class, 'complete'])->name('api.habits.complete');
Route::delete('api/v1/habits/{idHabit}', [HabitController::class, 'delete'])->name('api.habits.delete');

Route::get('/{vue_capture?}', function () {
    return view('app');
})->where('vue_capture', '[\/\w\.-]*');

