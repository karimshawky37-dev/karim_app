<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('shifts_archive', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shift_id')->constrained('shifts');
            $table->foreignId('user_id')->constrained('users');
            $table->string('user_name');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->integer('total_actions')->default(0);
            $table->timestamp('archived_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shifts_archive');
    }
};