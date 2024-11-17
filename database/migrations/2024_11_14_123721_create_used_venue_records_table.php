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
        Schema::create('used_venue_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('palakasan_id')->constrained('palakasans')->onDelete('cascade'); 
            $table->foreignId('venue_id')->constrained('venues')->onDelete('cascade');
            $table->date('date'); 
            $table->string('time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('used_venue_records');
    }
};
