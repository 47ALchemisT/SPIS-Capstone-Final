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
        Schema::create('overall_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assigned_sport_id')->constrained('assigned_sports')->onDelete('cascade'); // Foreign key to assigned_sports
            $table->foreignId('assigned_team_id')->constrained('assigned_teams')->onDelete('cascade'); // Foreign key to assigned_sports
            $table->string('rank');
            $table->integer('points');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('overall_results');
    }
};