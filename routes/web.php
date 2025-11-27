<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\LifeAreasController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\SubtasksController;
use App\Http\Controllers\HabitController;
use App\Http\Controllers\DaySchedulesController;

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
Route::get('api/v1/projects/not_completed', [ProjectsController::class, 'indexNotCompleted'])->name('api.projects.indexNotCompleted');
Route::get('api/v1/projects/completed', [ProjectsController::class, 'indexCompleted'])->name('api.projects.indexCompleted');
Route::get('api/v1/projects/deleted', [ProjectsController::class, 'indexDeleted'])->name('api.projects.indexDeleted');
Route::get('api/v1/projects/{idProject}', [ProjectsController::class, 'get'])->name('api.projects.get');
Route::post('api/v1/projects/store_to_category/{idCategory}', [ProjectsController::class, 'storeToCategory'])->name('api.projects.storeToCategory');
Route::post('api/v1/projects/store_to_parent_project/{idProject}', [ProjectsController::class, 'storeToProject'])->name('api.projects.store_to_parent_project');
Route::put('api/v1/projects/{idProject}', [ProjectsController::class, 'update'])->name('api.projects.update');
Route::patch('api/v1/projects/{idProject}/complete', [ProjectsController::class, 'complete'])->name('api.projects.complete');
Route::patch('api/v1/projects/{idProject}/recalc_task', [ProjectsController::class, 'recalcTask'])->name('api.projects.recalcTask');
Route::delete('api/v1/projects/{idProject}', [ProjectsController::class, 'delete'])->name('api.projects.delete');

Route::get('api/v1/tasks/all/{idProject?}', [TasksController::class, 'index'])->name('api.tasks.index');
Route::get('api/v1/tasks/not_completed/{idProject?}', [TasksController::class, 'indexNotCompleted'])->name('api.tasks.indexNotCompleted');
Route::get('api/v1/tasks/completed/{idProject?}', [TasksController::class, 'indexCompleted'])->name('api.tasks.indexCompleted');
Route::get('api/v1/tasks/deleted/{idProject?}', [TasksController::class, 'indexDeleted'])->name('api.tasks.indexDeleted');
Route::get('api/v1/tasks/{idTask}', [TasksController::class, 'get'])->name('api.tasks.get');
Route::post('api/v1/tasks/to_category/{idCategory}', [TasksController::class, 'storeToCategory'])->name('api.tasks.to_category.store');
Route::post('api/v1/tasks/to_project/{idProject}', [TasksController::class, 'storeToProject'])->name('api.tasks.to_project.store');
Route::put('api/v1/tasks/{idTask}', [TasksController::class, 'update'])->name('api.tasks.update');
Route::patch('api/v1/tasks/{idTask}/complete', [TasksController::class, 'complete'])->name('api.tasks.complete');
Route::patch('api/v1/tasks/{idTask}/recalc_task', [TasksController::class, 'recalcTask'])->name('api.tasks.recalcTask');
Route::delete('api/v1/tasks/{idTask}', [TasksController::class, 'delete'])->name('api.tasks.delete');

Route::get('api/v1/day_schedules/all/{idDaySchedule}', [TasksController::class, 'indexFromDaySchedule'])->name('api.tasks.indexFromDaySchedule');
Route::get('api/v1/day_schedules/not_completed/{idDaySchedule}', [TasksController::class, 'indexNotCompletedFromDaySchedule'])->name('api.tasks.indexNotCompletedFromDaySchedule');
Route::get('api/v1/day_schedules/completed/{idDaySchedule}', [TasksController::class, 'indexCompletedFromDaySchedule'])->name('api.tasks.indexCompletedFromDaySchedule');
Route::get('api/v1/day_schedules/deleted/{idDaySchedule}', [TasksController::class, 'indexDeletedFromDaySchedule'])->name('api.tasks.indexDeletedFromDaySchedule');

Route::get('api/v1/day_schedules/get_all', [DaySchedulesController::class, 'index'])->name('api.day_schedules.index');
Route::get('api/v1/day_schedules/get_in_progress', [DaySchedulesController::class, 'indexInProgress'])->name('api.day_schedules.indexInProgress');
Route::get('api/v1/day_schedules/get_pending', [DaySchedulesController::class, 'indexPending'])->name('api.day_schedules.indexPending');
Route::get('api/v1/day_schedules/get_successful', [DaySchedulesController::class, 'indexSuccessful'])->name('api.day_schedules.indexSuccessful');
Route::get('api/v1/day_schedules/get_failed', [DaySchedulesController::class, 'indexFailed'])->name('api.day_schedules.indexFailed');
Route::get('api/v1/day_schedules/get_deleted', [DaySchedulesController::class, 'indexDeleted'])->name('api.day_schedules.indexDeleted');
Route::get('api/v1/day_schedules/get_current_day_schedule', [DaySchedulesController::class, 'getCurrentDaySchedule'])->name('api.day_schedules.getCurrentDaySchedule');
Route::get('api/v1/day_schedules/get_current_day_schedule_part', [DaySchedulesController::class, 'getCurrentDaySchedulePart'])->name('api.day_schedules.getCurrentDaySchedulePart');
Route::get('api/v1/day_schedules/{idDaySchedulePart}', [DaySchedulesController::class, 'get'])->name('api.day_schedules.get');
Route::post('api/v1/day_schedules', [DaySchedulesController::class, 'store'])->name('api.day_schedules.store');
Route::put('api/v1/day_schedules/{idDaySchedulePart}', [DaySchedulesController::class, 'update'])->name('api.day_schedules.get');
Route::get('api/v1/day_schedules/{idDaySchedulePart}/activate', [DaySchedulesController::class, 'activate'])->name('api.day_schedules.activate');
Route::get('api/v1/day_schedules/{idDaySchedulePart}/complete', [DaySchedulesController::class, 'complete'])->name('api.day_schedules.complete');
Route::delete('api/v1/day_schedules/{idDaySchedulePart}', [DaySchedulesController::class, 'delete'])->name('api.day_schedules.delete');

Route::get('api/v1/day_schedules_part/all/{idDaySchedulePart}', [TasksController::class, 'indexFromDaySchedulePart'])->name('api.tasks.indexFromDaySchedulePart');
Route::get('api/v1/day_schedules_part/not_completed/{idDaySchedulePart}', [TasksController::class, 'indexNotCompletedFromDaySchedulePart'])->name('api.tasks.indexNotCompletedFromDaySchedulePart');
Route::get('api/v1/day_schedules_part/completed/{idDaySchedulePart}', [TasksController::class, 'indexCompletedFromDaySchedulePart'])->name('api.tasks.indexCompletedFromDaySchedulePart');
Route::get('api/v1/day_schedules_part/deleted/{idDaySchedulePart}', [TasksController::class, 'indexDeletedFromDaySchedulePart'])->name('api.tasks.indexDeletedFromDaySchedulePart');

Route::get('api/v1/subtasks/all/{idTask?}', [SubtasksController::class, 'index'])->name('api.subtasks.index');
Route::get('api/v1/subtasks/not_completed/{idTask?}', [SubtasksController::class, 'indexNotCompleted'])->name('api.subtasks.indexNotCompleted');
Route::get('api/v1/subtasks/completed/{idTask?}', [SubtasksController::class, 'indexCompleted'])->name('api.subtasks.indexCompleted');
Route::get('api/v1/subtasks/deleted/{idTask?}', [SubtasksController::class, 'indexDeleted'])->name('api.subtasks.indexDeleted');
Route::get('api/v1/subtasks/{idSubtask}', [SubtasksController::class, 'get'])->name('api.subtasks.get');
Route::post('api/v1/subtasks/{idTask}', [SubtasksController::class, 'store'])->name('api.subtasks.store');
Route::put('api/v1/subtasks/{idSubtask}', [SubtasksController::class, 'update'])->name('api.subtasks.update');
Route::patch('api/v1/subtasks/{idSubtask}/complete', [SubtasksController::class, 'complete'])->name('api.subtasks.complete');
Route::delete('api/v1/subtasks/{idSubtask}', [SubtasksController::class, 'delete'])->name('api.subtasks.delete');

Route::get('api/v1/habits', [HabitController::class, 'index'])->name('api.habits.index');
Route::get('api/v1/habits/not_completed', [HabitController::class, 'indexNotCompleted'])->name('api.habits.indexNotCompleted');
Route::get('api/v1/habits/completed', [HabitController::class, 'indexCompleted'])->name('api.habits.indexCompleted');
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

