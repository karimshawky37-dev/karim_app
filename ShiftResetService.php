<?php

namespace App\Services;

use App\Models\Shift;
use Illuminate\Support\Facades\DB;

class ShiftResetService
{
    /**
     * إنهاء الشيفت الحالية وأرشفة كل الحضور وسجلات التدقيق
     */
    public function closeAndArchive(int $shiftId): void
    {
        DB::transaction(function () use ($shiftId) {
            
            // 1. جلب بيانات الشيفت
            $shift = Shift::findOrFail($shiftId);
            
            // 2. أرشفة سجلات الحضور الخاصة بالشيفت
            DB::statement("
                INSERT INTO attendance_archive (user_id, shift_id, check_in, check_out, status, working_hours, notes, archived_at, created_at, updated_at)
                SELECT user_id, shift_id, check_in, check_out, status, working_hours, notes, NOW(), created_at, updated_at
                FROM attendance
                WHERE shift_id = ?
            ", [$shiftId]);

            // 3. حذف الحضور من الجدول الأساسي (عشان يبدأ من الصفر في الشيفت الجاية)
            DB::table('attendance')->where('shift_id', $shiftId)->delete();

            // 4. أرشفة سجلات التدقيق
            DB::statement("
                INSERT INTO audit_logs_archive (user_id, shift_id, user_name, user_role, action, table_name, record_id, old_data, new_data, ip_address, user_agent, archived_at, created_at, updated_at)
                SELECT user_id, shift_id, user_name, user_role, action, table_name, record_id, old_data, new_data, ip_address, user_agent, NOW(), created_at, updated_at
                FROM audit_logs
                WHERE shift_id = ?
            ", [$shiftId]);

            // 5. حذف سجلات التدقيق من الجدول الأساسي
            DB::table('audit_logs')->where('shift_id', $shiftId)->delete();

            // 6. تحديث حالة الشيفت إلى (مغلقة)
            $shift->update([
                'status' => 'closed',
                'end_time' => now(),
            ]);

            // 7. (اختياري) أرشفة الشيفت نفسها في جدول تاني لو حابب
            // ShiftArchive::create([...]);
        });
    }

    /**
     * بدء شيفت جديدة (تأكد إن مفيش شيفت مفتوحة)
     */
    public function startNewShift(int $userId, string $userName): Shift
    {
        return DB::transaction(function () use ($userId, $userName) {
            
            // لو في شيفت مفتوحة، نقفلها أولاً
            $activeShift = Shift::where('user_id', $userId)->where('status', 'active')->first();
            if ($activeShift) {
                $this->closeAndArchive($activeShift->id);
            }

            // ننشئ الشيفت الجديدة
            return Shift::create([
                'user_id' => $userId,
                'user_name' => $userName,
                'start_time' => now(),
                'status' => 'active',
            ]);
        });
    }
}