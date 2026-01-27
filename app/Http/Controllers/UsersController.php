<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;
use App\User;
use App\DaySchedule;
use App\Task;

class UsersController extends Controller
{

    public function get(int $idUser): JsonResponse
    {
        $user = User::find($idUser);

        $daySchedule = DaySchedule::where('user_id', $idUser)->where('status_id', DS_STATUS_IN_PROGRESS)->first();

        $user->day = $daySchedule->day ?? null;
        $user->day_id = $daySchedule->id ?? null;

        $user->setSeasonPoints();

        return response()->json($user);
    }

    public static function recalcUserPoints($idUser): JsonResponse{
        $user = User::find($idUser);
        
        $user->recalcPoints();
        
        return response()->json(['success' => true, 'points' => $user->points]);
    }

    public function recalcPoints() {
        $users = User::with('lifeAreas.categories.projects.tasks.subtasks', 'lifeAreas.categories.projects.habits', 'daySchedulesSuccessful')->get();
        /*$users = User::with([
              'lifeAreas' => function($q)
               {
                    $q->select('id', 'points', 'points_multiplier_in_percent');
               },
               'categories' => function($q)
               {
                    $q->select('id', 'life_area_id', 'points', 'points_multiplier_in_percent');
               },
               'projects' => function($q)
               {
                    $q->select('id', 'life_area_id', 'category_id', 'id_parent_project', 'points', 'points_multiplier_in_percent', 'points_upon_completion', 'completed');
               },
               'tasks' => function($q)
               {
                    $q->select('id', 'life_area_id', 'category_id', 'project_id', 'points', 'points_upon_completion', 'completed');
               },
               'subtasks' => function($q)
               {
                    $q->select('id', 'life_area_id', 'category_id', 'project_id', 'task_id', 'points_upon_completion', 'completed');
               }
          ])->get();*/
        
        $totalPoints = 0;
        $timeStart = microtime(true);
        
        foreach ($users as $user) {
            $user->recalcPoints();

            $totalPoints += $user->points;
        }

        $timeEnd = microtime(true);

        return 'Points recalculated: ' . $totalPoints . ' <br>In ' . ($timeEnd - $timeStart) . ' seconds';
    }

    public function seedSubtasktoTask(): JsonResponse
    {
     return false; // Deaktiviert, damit es nicht versehentlich ausgefuehrt wird.
        // Gemeinsame IDs fuer alle Tasks und Subtasks.
        $lifeAreaId = 6;
        $categoryId = 40;
        $projectId = 154;
        $daySchedulePartId = null;

        // Tasks, die erstellt werden sollen.
        $tasks = [
            ['title' => '2. Mission', 'points_upon_completion' => 100],
            ['title' => '3. Mission', 'points_upon_completion' => 100],
            ['title' => '4. Mission', 'points_upon_completion' => 100],
            ['title' => '5. Mission', 'points_upon_completion' => 100],
            ['title' => '6. Mission', 'points_upon_completion' => 100],
            ['title' => '7. Mission', 'points_upon_completion' => 100],
            ['title' => '8. Mission', 'points_upon_completion' => 100],
            ['title' => '9. Mission', 'points_upon_completion' => 100],
            ['title' => '10. Mission', 'points_upon_completion' => 100],
            ['title' => '11. Mission', 'points_upon_completion' => 100],
            ['title' => '12. Mission', 'points_upon_completion' => 100],
        ];

        // Subtasks, die fuer jeden Task angelegt werden sollen.
        $subtasks = [
            ['title' => 'Die Mission spielen', 'points_upon_completion' => 45],
            ['title' => 'Die Ork-Mission schauen', 'points_upon_completion' => 15],
            ['title' => 'Die eigene Story ausdenken', 'points_upon_completion' => 40],
        ];

        if ($lifeAreaId === null || $categoryId === null || $projectId === null) {
            return response()->json([
                'success' => false,
                'message' => 'Bitte $lifeAreaId, $categoryId und $projectId in UsersController::seedSubtasktoTask setzen.',
            ], 422);
        }

        if (empty($tasks) || empty($subtasks)) {
            return response()->json([
                'success' => false,
                'message' => 'Bitte $tasks und $subtasks in UsersController::seedSubtasktoTask befuellen.',
            ], 422);
        }

        $createdTasks = [];
        $createdSubtasks = [];
        $skippedTasks = [];
        $skippedSubtasks = [];

        foreach ($tasks as $taskData) {
            $taskTitle = $taskData['title'] ?? null;
            $taskPoints = (int) ($taskData['points_upon_completion'] ?? 0);

            if (!$taskTitle) {
                $skippedTasks[] = [
                    'reason' => 'missing_title',
                ];
                continue;
            }

            $task = Task::create([
                'life_area_id' => $lifeAreaId,
                'category_id' => $categoryId,
                'project_id' => $projectId,
                'day_schedule_part_id' => $daySchedulePartId,
                'title' => $taskTitle,
                'description' => $taskData['description'] ?? null,
                'points_upon_completion' => $taskPoints,
                'active' => 1,
            ]);

            $createdTasks[] = [
                'task_id' => $task->id,
                'title' => $task->title,
                'points_upon_completion' => $task->points_upon_completion,
            ];

            foreach ($subtasks as $subtaskData) {
                $subtaskTitle = $subtaskData['title'] ?? null;
                $subtaskPoints = (int) ($subtaskData['points_upon_completion'] ?? 0);

                if (!$subtaskTitle) {
                    $skippedSubtasks[] = [
                        'task_id' => $task->id,
                        'reason' => 'missing_title',
                    ];
                    continue;
                }

                // Doppelte aktive Subtasks mit gleichem Titel vermeiden.
                $alreadyExists = $task->subtasks()->where('title', $subtaskTitle)->exists();
                if ($alreadyExists) {
                    $skippedSubtasks[] = [
                        'task_id' => $task->id,
                        'title' => $subtaskTitle,
                        'reason' => 'already_exists',
                    ];
                    continue;
                }

                $subtask = $task->subtasks()->create([
                    'life_area_id' => $task->life_area_id,
                    'category_id' => $task->category_id,
                    'project_id' => $task->project_id,
                    'task_id' => $task->id,
                    'day_schedule_part_id' => $task->day_schedule_part_id,
                    'title' => $subtaskTitle,
                    'description' => $subtaskData['description'] ?? null,
                    'points_upon_completion' => $subtaskPoints,
                    'active' => 1,
                ]);

                $createdSubtasks[] = [
                    'task_id' => $task->id,
                    'subtask_id' => $subtask->id,
                    'title' => $subtask->title,
                    'points_upon_completion' => $subtask->points_upon_completion,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'shared_ids' => [
                'life_area_id' => $lifeAreaId,
                'category_id' => $categoryId,
                'project_id' => $projectId,
                'day_schedule_part_id' => $daySchedulePartId,
            ],
            'tasks_template_count' => count($tasks),
            'subtasks_template_count' => count($subtasks),
            'created_tasks_count' => count($createdTasks),
            'created_subtasks_count' => count($createdSubtasks),
            'skipped_tasks_count' => count($skippedTasks),
            'skipped_subtasks_count' => count($skippedSubtasks),
            'created_tasks' => $createdTasks,
            'created_subtasks' => $createdSubtasks,
            'skipped_tasks' => $skippedTasks,
            'skipped_subtasks' => $skippedSubtasks,
        ]);
    }

}
