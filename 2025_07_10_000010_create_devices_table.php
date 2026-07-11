<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('device_code')->unique();
            $table->foreignId('customer_id')->constrained('entities');
            $table->string('brand');
            $table->string('model');
            $table->string('color')->nullable();
            $table->string('storage_capacity')->nullable();
            $table->string('imei_1')->nullable();
            $table->string('imei_2')->nullable();
            $table->text('reported_issue');
            $table->text('diagnosed_issue')->nullable();
            $table->foreignId('current_status_id')->constrained('device_statuses');
            $table->foreignId('assigned_technician_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('received_by')->constrained('users');
            $table->dateTime('received_at');
            $table->string('waiting_for_part')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->foreignId('sale_id')->nullable()->constrained('invoices')->nullOnDelete();
            $table->boolean('is_paid')->default(false);
            $table->dateTime('pickup_date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('devices');
    }
};