<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // First, create a temporary column
        Schema::table('ffo_facilitator_submits', function (Blueprint $table) {
            $table->longText('signature_new')->nullable();
        });

        // Copy data from old to new column
        DB::table('ffo_facilitator_submits')->update([
            'signature_new' => DB::raw('signature')
        ]);

        // Drop the old column
        Schema::table('ffo_facilitator_submits', function (Blueprint $table) {
            $table->dropColumn('signature');
        });

        // Rename the new column to signature
        Schema::table('ffo_facilitator_submits', function (Blueprint $table) {
            $table->renameColumn('signature_new', 'signature');
        });

        // Make the column not nullable
        Schema::table('ffo_facilitator_submits', function (Blueprint $table) {
            $table->longText('signature')->nullable(false)->change();
        });
    }

    public function down()
    {
        Schema::table('ffo_facilitator_submits', function (Blueprint $table) {
            $table->mediumText('signature')->change();
        });
    }
};
