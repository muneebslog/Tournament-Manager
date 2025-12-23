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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('round_id')->constrained()->onDelete('cascade');
            $table->string('name')->nullable(); // e.g., Match 1, QF 2
            $table->foreignId('team1_id')->nullable()->constrained('teams')->onDelete('set null');
            $table->foreignId('team2_id')->nullable()->constrained('teams')->onDelete('set null');
            $table->foreignId('winner_team_id')->nullable()->constrained('teams')->onDelete('set null');
            $table->enum('status', ['pending','ready','ongoing', 'completed', 'cancelled'])->default('pending');
            $table->boolean('is_doubles')->default(false);
            $table->integer('bestof')->default(3);
            $table->string('service_judge_name')->nullable();
            $table->string('empire_name')->nullable();
            $table->dateTime('expected_start_time')->nullable();
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->date('match_date')->nullable();
            $table->integer('team1_points')->default(0);
            $table->integer('team2_points')->default(0);
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
