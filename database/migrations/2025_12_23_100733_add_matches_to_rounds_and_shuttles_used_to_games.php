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
        // Add 'matches' column to rounds table
        Schema::table('rounds', function (Blueprint $table) {
            $table->integer('matches')->default(0)->after('id'); // adjust 'after' as needed
        });

        // Add 'shuttles_used' column to games table
        Schema::table('games', function (Blueprint $table) {
            $table->integer('shuttles_used')->default(0)->after('id'); // adjust 'after' as needed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('rounds', function (Blueprint $table) {
            $table->dropColumn('matches');
        });

        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('shuttles_used');
        });
    }
};
