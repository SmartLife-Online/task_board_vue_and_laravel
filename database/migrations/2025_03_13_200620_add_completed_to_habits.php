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
        Schema::table('habits', function (Blueprint $table) {
            $table->mediumInteger('points_upon_completion')->unsigned()->default(0)->after('count_completed');
            $table->boolean('completed')->default(false)->after('points_upon_completion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('habits', function (Blueprint $table) {
            $table->dropColumn('points_upon_completion');
            $table->dropColumn('completed');
        });
    }
};
