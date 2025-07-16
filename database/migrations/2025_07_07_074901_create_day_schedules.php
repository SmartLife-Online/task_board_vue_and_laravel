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
        Schema::create('day_schedules', function (Blueprint $table) {
            $table->id();

            $table->mediumInteger('day')->unsigned();
            $table->mediumInteger('user_id')->unsigned();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->mediumInteger('points_upon_success')->unsigned()->default(0);
            $table->mediumInteger('basis_points_at_start')->unsigned()->default(0);
            $table->mediumInteger('basis_points_at_end')->unsigned()->default(0);
            $table->mediumInteger('points_at_start')->unsigned()->default(0);
            $table->mediumInteger('points_at_end')->unsigned()->default(0);
            $table->tinyInteger('status_id')->unsigned()->default(0);                            // config.day_schedules.php

            $table->boolean('active')->default(1);

            $table->timestamps();
            $table->mediumInteger('created_by')->unsigned()->nullable();
            $table->mediumInteger('updated_by')->unsigned()->nullable();

            $table->index('day');
            $table->index('user_id');
            $table->index('status_id');
            $table->index('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('day_schedules');
    }
};
