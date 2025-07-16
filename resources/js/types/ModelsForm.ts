export interface LifeArea {
    title: string;
    description: string;
}

export interface Category {
    title: string;
    description: string;
    points_multiplier_in_percent: string;
}

export interface Project {
    title: string;
    description: string;
    points_multiplier_in_percent: string;
    points_upon_completion: string;
}

export interface Task {
    title: string;
    description: string;
    points_upon_completion: string;
    day_schedule_part_id: number | null;
}

export interface Subtask {
    title: string;
    description: string;
    points_upon_completion: string;
}

export interface Habit {
    title: string;
    description: string;
    points_per_completion: string;
}

export interface DaySchedule {
    day: number;
    title: string;
    description: string;
    points_upon_success: number;
}
