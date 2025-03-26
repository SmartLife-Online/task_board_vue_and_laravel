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
        Schema::table('users', function (Blueprint $table) {
            $table->mediumInteger('points_multiplier_in_percent')->unsigned()->default(1)->after('points');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->mediumInteger('points_multiplier_in_percent')->unsigned()->default(1)->after('points');
        });

        Schema::table('life_areas', function (Blueprint $table) {
            $table->mediumInteger('points_multiplier_in_percent')->unsigned()->default(1)->after('points');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->mediumInteger('points_multiplier_in_percent')->unsigned()->default(1)->after('points');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('life_areas', function (Blueprint $table) {
            $table->dropColumn('points_multiplier_in_percent');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('points_multiplier_in_percent');
        });

        Schema::table('life_areas', function (Blueprint $table) {
            $table->dropColumn('points_multiplier_in_percent');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('points_multiplier_in_percent');
        });
    }
};
