<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('audit_logs_archive', function (Blueprint $table) {
            $table->id();
            // نفس هيكل audit_logs ولكن مع إضافة archived_at
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('shift_id')->nullable()->constrained('shifts')->nullOnDelete();
            $table->string('user_name');
            $table->string('user_role');
            $table->string('action');
            $table->string('table_name')->nullable();
            $table->unsignedBigInteger('record_id')->nullable();
            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('archived_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('audit_logs_archive');
    }
};