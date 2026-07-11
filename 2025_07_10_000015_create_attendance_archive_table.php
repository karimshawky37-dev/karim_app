<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('attendance_archive', function (Blueprint $table) {
            $table->id();
            // نفس هيكل attendance مع archived_at
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('shift_id')->nullable()->constrained('shifts')->nullOnDelete();
            $table->dateTime('check_in');
            $table->dateTime('check_out')->nullable();
            $table->string('status')->nullable();
            $table->decimal('working_hours', 5, 2)->nullable();
            $table->boolean('is_modified')->default(false);
            $table->foreignId('modified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('modified_at')->nullable();
            $table->text('modification_reason')->nullable();
            $table->string('shift_type')->nullable();
            $table->string('check_in_ip')->nullable();
            $table->text('check_in_device')->nullable();
            $table->string('check_out_ip')->nullable();
            $table->text('check_out_device')->nullable();
            $table->timestamp('archived_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendance_archive');
    }
};