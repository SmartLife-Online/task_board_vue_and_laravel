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
            $table->mediumInteger('points')->unsigned()->default(0)->after('remember_token');
            $table->mediumInteger('created_by')->unsigned()->nullable()->after('updated_at');
            $table->mediumInteger('updated_by')->unsigned()->nullable()->after('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('points');
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
        });
    }
};
