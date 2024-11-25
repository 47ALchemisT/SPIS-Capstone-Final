<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ffo_facilitator_submits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facilitator_id')->constrained('student_accounts');
            $table->foreignId('match_id')->constrained('sports_variations');
            $table->string('official_name');
            $table->mediumText('signature');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ffo_facilitator_submits');
    }
};
