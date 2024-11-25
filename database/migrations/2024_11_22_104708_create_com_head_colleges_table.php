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
        Schema::create('com_head_colleges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('com_head_id')->constrained('student_accounts')->onDelete('cascade');
            $table->foreignId('assigned_team_id')->constrained('assigned_teams')->onDelete('cascade');
            $table->foreignId('palakasan_sport_id')->constrained('palakasans')->onDelete('cascade');
            $table->timestamps();
            
            // Ensure a com head can only be assigned to one team per palakasan
            $table->unique(['com_head_id', 'palakasan_sport_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('com_head_colleges');
    }
};
