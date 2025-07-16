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
        Schema::create('day_schedule_part_templates', function (Blueprint $table) {
            $table->id();

            $table->string('user_id')->nullable();
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
            $table->index('from_time');
            $table->index('to_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('day_schedule_part_templates');
    }
};
