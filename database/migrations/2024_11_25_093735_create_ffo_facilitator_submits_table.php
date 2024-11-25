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
        Schema::create('ffo_facilitator_submits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facilitator_id')->constrained('student_accounts')->onDelete('cascade');
            $table->foreignId('match_id')->constrained('match_results')->onDelete('cascade');
            $table->string('official_name');
            $table->longText('signature');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ffo_facilitator_submits');
    }
};
