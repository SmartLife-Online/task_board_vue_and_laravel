export interface LifeArea {
    id: number;
    title: string;
    description: string;
    points: number;
    points_multiplier_in_percent: number;
}

export interface Category {
    id: number;
    life_area: string;
    life_area_id: number;
    title: string;
    description: string;
    points: number;
    points_multiplier_in_percent: number;
}

export interface Project {
    id: number;
    life_area: string;
    life_area_id: number;
    category: string;
    category_id: number;
    title: string;
    description: string;
    points: number;
    points_upon_completion: number;
    points_multiplier_in_percent: number;
    completed: number;
}

export interface Task {
    id: number;
    life_area: string;
    life_area_id: number;
    category: string;
    category_id: number;
    project: string;
    project_id: number;
    title: string;
    description: string;
    points: number;
    points_upon_completion: number;
    completed: number;
}

export interface Subtask {
    id: number;
    life_area: string;
    life_area_id: number;
    category: string;
    category_id: number;
    project: string;
    project_id: number;
    task: string;
    task_id: number;
    title: string;
    description: string;
    points: number;
    points_upon_completion: number;
    completed: number;
}

export interface Habit {
    id: number;
    life_area: string;
    life_area_id: number;
    category: string;
    category_id: number;
    project: string;
    project_id: number;
    title: string;
    description: string;
    count_completed: number;
    points_per_completion: number;
    points: number;
    completed: number;
    points_upon_completion: number;
}

export interface User {
    id: number;
    points: number;
}
