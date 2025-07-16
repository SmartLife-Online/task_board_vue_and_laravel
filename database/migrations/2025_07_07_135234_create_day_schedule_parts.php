<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('day_schedule_parts', function (Blueprint $table) {
            $table->id();

            $table->mediumInteger('user_id')->unsigned();
            $table->mediumInteger('day_schedule_id')->unsigned();
            $table->mediumInteger('day_schedule_part_template_id')->unsigned()->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->time('from_time')->nullable();
            $table->time('to_time')->nullable();

            $table->boolean('active')->default(1);

            $table->timestamps();
            $table->mediumInteger('created_by')->unsigned()->nullable();
            $table->mediumInteger('updated_by')->unsigned()->nullable();

            $table->index('active');
            $table->index('user_id');
            $table->index('day_schedule_id');
            $table->index('day_schedule_part_template_id');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->mediumInteger('day_schedule_part_id')->unsigned()->nullable()->after('life_area_id');

            $table->index('day_schedule_part_id');
        });

        Schema::table('subtasks', function (Blueprint $table) {
            $table->mediumInteger('day_schedule_part_id')->unsigned()->nullable()->after('life_area_id');

            $table->index('day_schedule_part_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('day_schedule_parts');

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('day_schedule_part_id');
        });

        Schema::table('subtasks', function (Blueprint $table) {
            $table->dropColumn('day_schedule_part_id');
        });
    }
};
