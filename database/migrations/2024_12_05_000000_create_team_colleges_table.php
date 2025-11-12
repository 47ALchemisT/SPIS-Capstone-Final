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
        Schema::create('team_colleges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('assigned_teams')->onDelete('cascade');
            $table->foreignId('college_id')->constrained('colleges')->onDelete('cascade');
            $table->timestamps();
            
            // Ensure a college can't be added to the same team multiple times
            $table->unique(['team_id', 'college_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_colleges');
    }
};
