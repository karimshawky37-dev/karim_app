<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('device_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('is_final')->default(false);
            $table->string('color')->default('#3b82f6');
            $table->string('icon')->default('fa-circle');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('device_statuses');
    }
};