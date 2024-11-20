<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('points', function (Blueprint $table) {
            // First, create a new integer column
            $table->integer('rank_int')->nullable()->after('rank');

            // Update the new column with converted values from the old column
            DB::statement('UPDATE points SET rank_int = CAST(rank AS SIGNED)');

            // Drop the old column
            $table->dropColumn('rank');

            // Rename the new column to 'rank'
            $table->renameColumn('rank_int', 'rank');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('points', function (Blueprint $table) {
            // Reverse the process if you need to rollback
            $table->string('rank_string')->nullable()->after('rank');
            DB::statement('UPDATE points SET rank_string = CAST(rank AS CHAR)');
            $table->dropColumn('rank');
            $table->renameColumn('rank_string', 'rank');
        });
    }
};
