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
