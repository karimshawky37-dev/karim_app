<?php

namespace App\Listeners;

use App\Events\PartRestocked;
use App\Models\Device;
use App\Models\DeviceStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateWaitingDevices
{
    public function handle(PartRestocked $event): void
    {
        $partName = $event->inventory->name;

        // جلب الأجهزة المنتظرة لهذه القطعة
        $devices = Device::where('waiting_for_part', 'LIKE', "%{$partName}%")
            ->whereHas('status', function ($q) {
                $q->where('slug', 'suspended');
            })
            ->get();

        if ($devices->isEmpty()) {
            return;
        }

        DB::transaction(function () use ($devices, $partName) {
            $repairingStatus = DeviceStatus::where('slug', 'repairing')->first();

            foreach ($devices as $device) {
                // 1. تحديث حالة الجهاز
                $device->update([
                    'current_status_id' => $repairingStatus->id,
                    'waiting_for_part' => null,
                ]);

                // 2. تسجيل في سجل الصيانة
                $device->maintenanceLogs()->create([
                    'action' => 'parts_arrived',
                    'description' => "قطعة غيار متوفرة: {$partName}",
                    'performed_by' => 1, // النظام
                    'performed_at' => now(),
                ]);

                // 3. إشعار للفني المسؤول (Event تاني)
                // Notification::send($device->technician, new PartAvailableNotification($device, $partName));
            }
        });
    }
}