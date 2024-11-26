<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ffo_facilitator_submits', function (Blueprint $table) {
            // First drop the existing signature column
            $table->dropColumn('signature');
        });

        Schema::table('ffo_facilitator_submits', function (Blueprint $table) {
            // Then recreate it as longText
            $table->longText('signature')->after('official_name');
        });
    }

    public function down()
    {
        Schema::table('ffo_facilitator_submits', function (Blueprint $table) {
            $table->dropColumn('signature');
            $table->text('signature')->after('official_name');
        });
    }
};
