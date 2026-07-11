<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('device_checklist', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained()->cascadeOnDelete();
            
            // الأنواع: before (قبل الصيانة), after (بعد الصيانة), final (تسليم نهائي)
            $table->enum('check_type', ['before', 'after', 'final'])->default('before');
            
            // العناصر الأساسية (boolean: 1 شغال، 0 عطل)
            $table->boolean('wifi_working')->nullable();
            $table->boolean('bluetooth_working')->nullable();
            $table->boolean('network_working')->nullable();
            $table->boolean('camera_working')->nullable();
            $table->boolean('fingerprint_working')->nullable();
            $table->boolean('audio_working')->nullable();
            $table->boolean('charging_working')->nullable();
            
            // حالة الشاشة (اختيارات محددة)
            $table->enum('screen_condition', ['good', 'scratched', 'cracked', 'broken', 'not_checked'])->default('not_checked');
            
            // ملاحظات وتوقيع العميل
            $table->text('notes')->nullable();
            $table->string('customer_signature')->nullable();
            
            // مين عمل الفحص
            $table->foreignId('checked_by')->constrained('users');
            $table->timestamp('checked_at')->useCurrent();
            
            $table->timestamps();
            
            // فهرس سريع للبحث عن أجهزة معينة
            $table->index(['device_id', 'check_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('device_checklist');
    }
};