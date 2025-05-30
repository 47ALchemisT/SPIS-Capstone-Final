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
        Schema::create('assigned_sports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('palakasan_sport_id')->constrained('palakasans')->onDelete('cascade'); // Add foreign key constraint
            $table->foreignId('sport_id')->constrained('sports')->onDelete('cascade'); // Add foreign key constraint
            $table->string('categories');
            $table->string('setup');
            $table->string('type');
            //facilitator_id
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assigned_sports');
    }
};
