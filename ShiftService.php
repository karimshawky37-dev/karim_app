<?php

namespace App\Services;

use App\Models\Shift;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ShiftService
{
    /**
     * بدء وردية جديدة تلقائياً (يُستدعى من Kernel)
     */
    public function autoStartShifts()
    {
        // جلب جميع المستخدمين النشطين الذين ليس لديهم وردية مفتوحة
        $users = User::where('is_active', true)
            ->whereDoesntHave('currentShift')
            ->get();

        foreach ($users as $user) {
            $this->startShift($user->id, $user->full_name);
        }
    }

    /**
     * إنهاء الورديات المفتوحة تلقائياً (في نهاية اليوم)
     */
    public function autoEndShifts()
    {
        $activeShifts = Shift::where('status', 'active')->get();
        foreach ($activeShifts as $shift) {
            $this->endShift($shift->id);
        }
    }

    /**
     * بدء وردية لمستخدم معين
     */
    public function startShift($userId, $userName)
    {
        // إنهاء أي وردية نشطة سابقة
        Shift::where('user_id', $userId)->where('status', 'active')->update(['status' => 'closed']);

        // إنشاء وردية جديدة
        $shift = Shift::create([
            'user_id' => $userId,
            'user_name' => $userName,
            'start_time' => now(),
            'status' => 'active',
        ]);

        return $shift;
    }

    /**
     * إنهاء وردية وأرشفتها
     */
    public function endShift($shiftId)
    {
        DB::transaction(function () use ($shiftId) {
            $shift = Shift::findOrFail($shiftId);
            $shift->end_time = now();
            $shift->status = 'closed';
            $shift->save();

            // أرشفة سجلات الحضور
            $this->archiveAttendance($shiftId);

            // أرشفة سجلات التدقيق
            $this->archiveAuditLogs($shiftId);

            // أرشفة الوردية نفسها في جدول الأرشفة
            \App\Models\ShiftArchive::create([
                'shift_id' => $shift->id,
                'user_id' => $shift->user_id,
                'user_name' => $shift->user_name,
                'start_time' => $shift->start_time,
                'end_time' => $shift->end_time,
                'total_actions' => $shift->total_actions,
                'archived_at' => now(),
            ]);

            // تحديث حالة الوردية إلى مؤرشفة
            $shift->status = 'archived';
            $shift->archived_at = now();
            $shift->save();
        });
    }

    private function archiveAttendance($shiftId)
    {
        $attendances = Attendance::where('shift_id', $shiftId)->get();
        foreach ($attendances as $attendance) {
            \App\Models\AttendanceArchive::create([
                'user_id' => $attendance->user_id,
                'shift_id' => $attendance->shift_id,
                'check_in' => $attendance->check_in,
                'check_out' => $attendance->check_out,
                'status' => $attendance->status,
                'working_hours' => $attendance->working_hours,
                'is_modified' => $attendance->is_modified,
                'modification_reason' => $attendance->modification_reason,
                'modified_by' => $attendance->modified_by,
                'modified_at' => $attendance->modified_at,
                'check_in_ip' => $attendance->check_in_ip,
                'check_in_device' => $attendance->check_in_device,
                'check_out_ip' => $attendance->check_out_ip,
                'check_out_device' => $attendance->check_out_device,
                'shift_type' => $attendance->shift_type,
                'created_at' => $attendance->created_at,
                'archived_at' => now(),
            ]);
            $attendance->delete();
        }
    }

    private function archiveAuditLogs($shiftId)
    {
        $logs = \App\Models\AuditLog::where('shift_id', $shiftId)->get();
        foreach ($logs as $log) {
            \App\Models\AuditLogArchive::create([
                'user_id' => $log->user_id,
                'shift_id' => $log->shift_id,
                'user_name' => $log->user_name,
                'user_role' => $log->user_role,
                'action' => $log->action,
                'table_name' => $log->table_name,
                'record_id' => $log->record_id,
                'old_data' => $log->old_data,
                'new_data' => $log->new_data,
                'ip_address' => $log->ip_address,
                'user_agent' => $log->user_agent,
                'created_at' => $log->created_at,
                'archived_at' => now(),
            ]);
            $log->delete();
        }
    }
}