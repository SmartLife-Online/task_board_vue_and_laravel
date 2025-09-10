<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelTrait;

class Task extends Model
{
    use ModelTrait;

    protected $guarded = ['id', 'created_at', 'updated_at', 'created_by', 'updated_by'];

    protected $fillable = ['user_id', 'project_id', 'category_id', 'life_area_id', 'day_schedule_part_id', 'title', 'description', 'points', 'points_upon_completion', 'completed', 'completed_at', 'active'];

    public static function findActive(int $idTask)
    {
        $task = self::where('id', $idTask)->where('active', 1)->first();

        if(!$task) abort(response()->json(['message' => 'Task not found'], 404));

        return $task;
    }

    public static function allActive(?int $idProject = null)
    {
        $query = self::where('active', 1);

        if ($idProject) {
            $query = $query->where('project_id', $idProject);
        }
        
        return $query->get();
    }

    public static function allNotCompleted(?int $idProject = null)
    {
        $query = self::where('active', 1)->where('completed', 0);

        if ($idProject) {
            $query = $query->where('project_id', $idProject);
        }
        
        return $query->get();
    }

    public static function allCompleted(?int $idProject = null)
    {
        $query = self::where('active', 1)->where('completed', 1);

        if ($idProject) {
            $query = $query->where('project_id', $idProject);
        }
        
        return $query->get();
    }

    public static function allDeleted(?int $idProject = null)
    {
        $query = self::where('active', 0);

        if ($idProject) {
            $query = $query->where('project_id', $idProject);
        }
        
        return $query->get();
    }

    public static function allSortedBylifeAreaAndCategoryAndProject() {
        return self::where('active', 1)
            ->orderBy('life_area_id')
            ->orderBy('category_id')
            ->orderBy('project_id')
            ->get();
    }

    // ~~ Day-Schedule ~~

    public static function allActivFromDaySchedule(?int $idDaySchedule)
    {
        return self::where('active', 1)
            ->whereIn('day_schedule_part_id', DaySchedule::getIdsdayScheduleParts($idDaySchedule))
            ->get();
    }

    public static function allNotCompletedFromDaySchedule(?int $idDaySchedule)
    {
        return self::where('active', 1)
            ->where('completed', 0)
            ->whereIn('day_schedule_part_id', DaySchedule::getIdsdayScheduleParts($idDaySchedule))
            ->get();
    }


    public static function allCompletedFromDaySchedule(?int $idDaySchedule)
    {
        return self::where('active', 1)
            ->where('completed', 1)
            ->whereIn('day_schedule_part_id', DaySchedule::getIdsdayScheduleParts($idDaySchedule))
            ->get();
    }


    public static function allDeletedFromDaySchedule(?int $idDaySchedule)
    {
        return self::where('active', 0)
            ->whereIn('day_schedule_part_id', DaySchedule::getIdsdayScheduleParts($idDaySchedule))
            ->get();
    }

    // ~~ Day-Schedule-Part ~~

    public static function allActiveFromDaySchedulePart(?int $idDaySchedulePart)
    {
        return self::where('active', 1)
            ->where('day_schedule_part_id', $idDaySchedulePart)
            ->get();
    }

    public static function allNotCompletedFromDaySchedulePart(?int $idDaySchedule)
    {
        return self::where('active', 1)
            ->where('completed', 0)
            ->where('day_schedule_part_id', $idDaySchedulePart)
            ->get();
    }

    public static function allCompletedFromDaySchedulePart(?int $idDaySchedulePart)
    {
        return self::where('active', 1)
            ->where('completed', 1)
            ->where('day_schedule_part_id', $idDaySchedulePart)
            ->get();
    }

    public static function allDeletedFromDaySchedulePart(?int $idDaySchedulePart)
    {
        return self::where('active', 0)
            ->where('day_schedule_part_id', $idDaySchedulePart)
            ->get();
    }

    public function lifeArea()
    {
        return $this->belongsTo(LifeArea::class)->where('active', 1);
    }

    public function category()
    {
        return $this->belongsTo(Category::class)->where('active', 1);
    }

    public function project()
    {
        return $this->belongsTo(Project::class)->where('active', 1);
    }

    public function subtasks()
    {
        return $this->hasMany(Subtask::class)->where('active', 1);
    }

    public function createSubtasksByJSONString(string $jsonString): void
    {
        $data = json_decode($jsonString, true);

        if (isset($data['subtasks']) && is_array($data['subtasks'])) {
            foreach ($data['subtasks'] as $subtaskData) {
                $this->subtasks()->create([
                    'life_area_id' => $this->life_area_id,
                    'category_id' => $this->category_id,
                    'project_id' => $this->project_id,
                    'task_id' => $this->id,
                    'life_area_id' => $this->life_area_id,
                    'day_schedule_part_id' => $this->day_schedule_part_id,
                    'title' => $subtaskData['title'] ?? null,
                    'description' => $subtaskData['description'] ?? null,
                    'points_upon_completion' => $subtaskData['points_upon_completion'] ?? 0,
                ]);
            }
        }
    }
    
    public function recalcPoints() {
        $this->points = 0;

        foreach ($this->subtasks as $subtask) {
            if(!$subtask->completed) continue;

            $this->points += $subtask->points_upon_completion;
        }

        if($this->completed) {
            $this->points += $this->points_upon_completion;
        }

        if($this->isDirty('points')) {
            $this->update();
        }

        return $this->points;
    }

}
