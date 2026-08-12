export interface TaskTemplateSubtask {
  id?: number;
  title: string;
  description: string;
  points_upon_completion: number;
  sort_order?: number;
}

export interface TaskTemplate {
  id?: number;
  name: string;
  task_title: string;
  task_description: string;
  task_points_upon_completion: number;
  active?: boolean;
  subtasks: TaskTemplateSubtask[];
}
