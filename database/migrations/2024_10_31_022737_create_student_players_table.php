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
        Schema::create('student_players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id');
            $table->foreignId('student_assigned_sport_id');
            //$table->foreignId('assigned_team_id')->constrained('assigned_teams')->onDelete('cascade'); 
            $table->timestamps();
        });
    } 

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_players');
    }
};
