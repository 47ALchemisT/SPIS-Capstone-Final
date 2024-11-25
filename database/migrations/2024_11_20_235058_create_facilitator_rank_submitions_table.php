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
        Schema::create('facilitator_rank_submitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('overall_result_id')->constrained('overall_results')->onDelete('cascade');
            $table->foreignId('facilitator_id')->constrained('student_accounts')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facilitator_rank_submitions');
    }
};
