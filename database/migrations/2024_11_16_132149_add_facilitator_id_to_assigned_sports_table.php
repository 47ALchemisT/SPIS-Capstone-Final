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
        Schema::table('assigned_sports', function (Blueprint $table) {
            $table->foreignId('facilitator_id')->nullable()->constrained('student_accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assigned_sports', function (Blueprint $table) {
            $table->dropForeign(['facilitator_id']);
            $table->dropColumn('facilitator_id');
        });
    }
};
