<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;
use App\User;
use App\DaySchedule;
use App\DaySchedulePart;
use App\DaySchedulePartTemplate;

class DaySchedulesController extends Controller
{

    public function index(): JsonResponse
    {
        return self::indexJSON(DaySchedule::allActive());
    }

    public function indexPending(): JsonResponse
    {
        return self::indexJSON(DaySchedule::allPending());
    }

    public function indexInProgress(): JsonResponse
    {
        return self::indexJSON(DaySchedule::allInProgress());
    }

    public function indexSuccessful(): JsonResponse
    {
        return self::indexJSON(DaySchedule::allSuccessful());
    }

    public function indexFailed(): JsonResponse
    {
        return self::indexJSON(DaySchedule::allFailed());
    }

    public function indexDeleted(): JsonResponse
    {
        return self::indexJSON(DaySchedule::allDeleted());
    }

    public function indexJSON(Collection $daySchedules): JsonResponse
    {
        $daySchedulesJSON = [];
        
        foreach($daySchedules as $daySchedule) {
            $daySchedulesJSON[] = [
                'id' => $daySchedule->id,
                'day' => $daySchedule->day,
                'title' => $daySchedule->title,
                'description' => $daySchedule->description,
                'points_upon_success' => $daySchedule->points_upon_success,
                'status_id' => $daySchedule->status_id,
                'active' => $daySchedule->active,
            ];
        }

        return response()->json($daySchedulesJSON);
    }

    public function get(int $idDaySchedule): JsonResponse
    {
        $daySchedule = DaySchedule::findActive($idDaySchedule);
        if(!$daySchedule) {
            return response()->json(['error' => 'Day-Schedule not found'], 404);
        }

        return response()->json($daySchedule);
    }

    public function getCurrentDaySchedule(): JsonResponse
    {
        $daySchedule = DaySchedule::where('user_id', 1)->where('status_id', DS_STATUS_IN_PROGRESS)->first();
        if(!$daySchedule) {
            return response()->json(['error' => 'Day-Schedule not found'], 404);
        }

        return response()->json($daySchedule);
    }

    public function getCurrentDaySchedulePart(): JsonResponse
    {
        $daySchedule = DaySchedule::where('user_id', 1)->where('status_id', DS_STATUS_IN_PROGRESS)->first();
        if(!$daySchedule) {
            return response()->json(['error' => 'Day-Schedule not found'], 404);
        }
        
        if(!count($daySchedule->dayScheduleParts)) {
            return response()->json(['error' => 'No Day-Schedule-Part found'], 404);
        }

        return response()->json($daySchedule->dayScheduleParts->first());
    }

    public function store(Request $request): JsonResponse
    {
        $daySchedule = new DaySchedule();

        return self::createOrUpdate($daySchedule, $request);

    }

    public function update(int $idDaySchedule, Request $request): JsonResponse
    {
        $daySchedule = DaySchedule::findActive($idDaySchedule);
        if(!$daySchedule) {
            return response()->json(['error' => 'Day-Schedule not found'], 404);
        }

        return self::createOrUpdate($daySchedule, $request);

    }

    public function createOrUpdate(DaySchedule $daySchedule, Request $request): JsonResponse
    {
        $isNew = !$daySchedule->id;

        $daySchedule->day = $request->day ?: (DaySchedule::max('day') + 1);
        $daySchedule->title = $request->title;
        $daySchedule->description = $request->description;
        $daySchedule->points_upon_success = $request->points_upon_success ?? 0;

        if($isNew) {
            $daySchedule->status_id = DS_STATUS_PENDING;
        }

        $daySchedule->save();

        if($isNew) {
            $daySchedulePart = new DaySchedulePart();

            $daySchedulePart->day_schedule_id = $daySchedule->id;
            $daySchedulePart->day_schedule_part_template_id = null;

            $daySchedulePart->title = 'Morgens';
            $daySchedulePart->description = null;
            $daySchedulePart->from_time = $request->from_time ?? '06:00:00';
            $daySchedulePart->to_time = $request->to_time ?? '12:00:00';

            $daySchedulePart->save();

            $daySchedulePart = new DaySchedulePart();

            $daySchedulePart->day_schedule_id = $daySchedule->id;
            $daySchedulePart->day_schedule_part_template_id = null;

            $daySchedulePart->title = 'Mittags';
            $daySchedulePart->description = null;
            $daySchedulePart->from_time = $request->from_time ?? '12:00:00';
            $daySchedulePart->to_time = $request->to_time ?? '18:00:00';

            $daySchedulePart->save();

            $daySchedulePart = new DaySchedulePart();

            $daySchedulePart->day_schedule_id = $daySchedule->id;
            $daySchedulePart->day_schedule_part_template_id = null;

            $daySchedulePart->title = 'Abends';
            $daySchedulePart->description = null;
            $daySchedulePart->from_time = $request->from_time ?? '18:00:00';
            $daySchedulePart->to_time = $request->to_time ?? '22:00:00';

            $daySchedulePart->save();
        }

        return response()->json($daySchedule);

    }

    public function addTaskToDaySchedule(int $idDaySchedule, int $idDaySchedulePart, int $idTask): JsonResponse
    {
        $daySchedule = DaySchedule::findActive($idDaySchedule);
        $daySchedulePart = DaySchedulePart::findActive($idDaySchedulePart);
        $task = Task::findActive($idTask);

        if (!$daySchedule || !$daySchedulePart || !$task) {
            return response()->json(['message' => 'Day-Schedule, Day-Schedule-Part or Task not found'], 404);
        }

        if ($daySchedule->status_id !== DS_STATUS_PENDING && $daySchedule->status_id !== DS_STATUS_IN_PROGRESS) {
            return response()->json(['message' => 'Day-Schedule is not in pending status'], 400);
        }

        $task->day_schedule_part_id = $daySchedulePart->id;
        $task->update();

        return response()->json(['message' => 'Task added to Day Schedule successfully']);

    }

    public function activate(int $idDaySchedule): JsonResponse
    {
        $daySchedule = DaySchedule::findActive($idDaySchedule);
        if(!$daySchedule) {
            return response()->json(['error' => 'Day-Schedule not found'], 404);
        }

        $daySchedule->status_id = DS_STATUS_IN_PROGRESS;

        $daySchedule->update();

        return response()->json(['status_id' => $daySchedule->status_id, 'success' => true]);
    }

    public function complete(int $idDaySchedule): JsonResponse
    {
        $daySchedule = DaySchedule::findActive($idDaySchedule);
        if(!$daySchedule) {
            return response()->json(['error' => 'Day-Schedule not found'], 404);
        }

        $daySchedule->status_id = $daySchedule->checkIfAllTasksAreCompleted() ? DS_STATUS_SUCCESSFUL : DS_STATUS_FAILED;

        $daySchedule->update();

        UsersController::recalcUserPoints($daySchedule->user_id);
        
        $user = User::find($daySchedule->user_id);

        $daySchedule->basis_points_at_end = $user->basis_points;
        $daySchedule->points_at_end = $user->points;

        $daySchedule->update();

        $nextDaySchedule = DaySchedule::where('user_id', $daySchedule->user_id)
            ->where('status_id', DS_STATUS_PENDING)
            ->where('day', ($daySchedule->day + 1))
            ->first();

        if($nextDaySchedule) {
            $nextDaySchedule->status_id = DS_STATUS_IN_PROGRESS;

            $nextDaySchedule->update();
        }

        return response()->json(['status_id' => $daySchedule->status_id, 'success' => true]);
    }

    public function delete(int $idDaySchedule): JsonResponse
    {
        $daySchedule = DaySchedule::findActive($idDaySchedule);
        if(!$daySchedule) {
            return response()->json(['error' => 'Day-Schedule not found'], 404);
        }

        $daySchedule->active = 0;

        $daySchedule->update();

        return response()->json(['success' => true]);
    }
}
