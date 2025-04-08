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
        Schema::table('projects', function (Blueprint $table) {
            $table->dateTime('completed_at')->nullable()->after('completed');
        });
        Schema::table('tasks', function (Blueprint $table) {
            $table->dateTime('completed_at')->nullable()->after('completed');
        });
        Schema::table('subtasks', function (Blueprint $table) {
            $table->dateTime('completed_at')->nullable()->after('completed');
        });
        Schema::table('habits', function (Blueprint $table) {
            $table->dateTime('completed_at')->nullable()->after('completed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('completed_at');
        });
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('completed_at');
        });
        Schema::table('subtasks', function (Blueprint $table) {
            $table->dropColumn('completed_at');
        });
        Schema::table('habits', function (Blueprint $table) {
            $table->dropColumn('completed_at');
        });
    }
};
