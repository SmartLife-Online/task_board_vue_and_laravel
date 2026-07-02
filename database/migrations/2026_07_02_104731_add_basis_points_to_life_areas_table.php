<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('life_areas', function (Blueprint $table) {
            $table->mediumInteger('basis_points')->unsigned()->default(0)->after('description');
        });

        DB::table('life_areas')->update([
            'basis_points' => DB::raw('points'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('life_areas', function (Blueprint $table) {
            $table->dropColumn('basis_points');
        });
    }
};
