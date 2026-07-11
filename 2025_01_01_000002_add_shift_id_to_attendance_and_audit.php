<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // 1. إضافة shift_id في جدول الحضور
        Schema::table('attendance', function (Blueprint $table) {
            if (!Schema::hasColumn('attendance', 'shift_id')) {
                $table->foreignId('shift_id')->nullable()->constrained('shifts')->nullOnDelete();
            }
        });

        // 2. إضافة shift_id في جدول سجل التدقيق (لو موجود بالفعل نعديه)
        Schema::table('audit_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('audit_logs', 'shift_id')) {
                $table->foreignId('shift_id')->nullable()->constrained('shifts')->nullOnDelete();
            }
        });

        // 3. إنشاء جدول أرشيف للحضور (لو مش موجود)
        if (!Schema::hasTable('attendance_archive')) {
            Schema::create('attendance_archive', function (Blueprint $table) {
                $table->id();
                // بنفس هيكل جدول الحضور ولكن مع إضافة archived_at
                $table->foreignId('user_id')->constrained();
                $table->foreignId('shift_id')->nullable();
                $table->datetime('check_in');
                $table->datetime('check_out')->nullable();
                $table->string('status')->nullable();
                $table->decimal('working_hours', 5, 2)->nullable();
                $table->text('notes')->nullable();
                $table->timestamp('archived_at')->useCurrent();
                $table->timestamps();
            });
        }

        // 4. إنشاء جدول أرشيف للتدقيق (لو مش موجود)
        if (!Schema::hasTable('audit_logs_archive')) {
            Schema::create('audit_logs_archive', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained();
                $table->foreignId('shift_id')->nullable();
                $table->string('user_name');
                $table->string('user_role');
                $table->string('action');
                $table->string('table_name')->nullable();
                $table->unsignedBigInteger('record_id')->nullable();
                $table->json('old_data')->nullable();
                $table->json('new_data')->nullable();
                $table->string('ip_address')->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamp('archived_at')->useCurrent();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::table('attendance', function (Blueprint $table) {
            $table->dropForeign(['shift_id']);
            $table->dropColumn('shift_id');
        });
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropForeign(['shift_id']);
            $table->dropColumn('shift_id');
        });
        Schema::dropIfExists('attendance_archive');
        Schema::dropIfExists('audit_logs_archive');
    }
};