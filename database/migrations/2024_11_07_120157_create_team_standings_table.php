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
        Schema::create('team_standings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assigned_sport_id')->constrained('assigned_sports')->onDelete('cascade'); // Foreign key to assigned_sports
            $table->foreignId('team_id')->constrained('assigned_teams')->onDelete('cascade'); // Foreign key to assigned_sports
            $table->integer('played')->default(0);
            $table->integer('won')->default(0);
            $table->integer('drawn')->default(0);
            $table->integer('lost')->default(0);
            $table->integer('points')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_standings');
    }
};
