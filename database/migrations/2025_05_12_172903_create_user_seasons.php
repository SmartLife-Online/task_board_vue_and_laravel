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
        Schema::create('user_seasons', function (Blueprint $table) {
            $table->id();

            $table->mediumInteger('user_id')->unsigned();
            $table->mediumInteger('season_id')->unsigned();
            $table->mediumInteger('points')->unsigned()->default(0);
            $table->mediumInteger('basis_points')->unsigned()->default(0);
            
            $table->timestamps();
            $table->mediumInteger('created_by')->unsigned()->nullable();
            $table->mediumInteger('updated_by')->unsigned()->nullable();

            $table->index('user_id');
            $table->index('season_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_seasons');
    }
};
