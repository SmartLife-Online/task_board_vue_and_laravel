<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_templates', function (Blueprint $table) {
            $table->id();
            $table->mediumInteger('user_id')->unsigned();
            $table->string('name');
            $table->string('task_title');
            $table->text('task_description')->nullable();
            $table->mediumInteger('task_points_upon_completion')->unsigned()->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->mediumInteger('created_by')->unsigned()->nullable();
            $table->mediumInteger('updated_by')->unsigned()->nullable();

            $table->index(['user_id', 'active']);
        });

        Schema::create('task_template_subtasks', function (Blueprint $table) {
            $table->id();
            $table->mediumInteger('user_id')->unsigned();
            $table->unsignedBigInteger('task_template_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->mediumInteger('points_upon_completion')->unsigned()->default(0);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
            $table->mediumInteger('created_by')->unsigned()->nullable();
            $table->mediumInteger('updated_by')->unsigned()->nullable();

            $table->index(['task_template_id', 'sort_order']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_template_subtasks');
        Schema::dropIfExists('task_templates');
    }
};
