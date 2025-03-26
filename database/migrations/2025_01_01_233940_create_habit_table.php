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
        Schema::create('habits', function (Blueprint $table) {
            $table->id();
            $table->mediumInteger('user_id')->unsigned();
            $table->mediumInteger('project_id')->unsigned()->nullable();
            $table->mediumInteger('category_id')->unsigned();
            $table->mediumInteger('life_area_id')->unsigned();
            $table->string('title');
            $table->text('description')->nullable();
            $table->mediumInteger('points_per_completion')->unsigned();
            $table->mediumInteger('count_completed')->default(0);

            $table->boolean('active')->default(1);

            $table->timestamps();
            $table->mediumInteger('created_by')->unsigned()->nullable();
            $table->mediumInteger('updated_by')->unsigned()->nullable();

            $table->index('user_id');
            $table->index('project_id');
            $table->index('category_id');
            $table->index('life_area_id');
            $table->index('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habits');
    }
};
