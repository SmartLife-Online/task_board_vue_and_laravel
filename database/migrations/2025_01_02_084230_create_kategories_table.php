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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->mediumInteger('user_id')->unsigned();
            $table->mediumInteger('life_area_id')->unsigned();
            $table->string('title');
            $table->text('description')->nullable();
            $table->mediumInteger('points')->unsigned()->default(0);

            $table->boolean('active')->default(1);

            $table->timestamps();
            $table->mediumInteger('created_by')->unsigned()->nullable();
            $table->mediumInteger('updated_by')->unsigned()->nullable();

            $table->index('user_id');
            $table->index('life_area_id');
            $table->index('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
