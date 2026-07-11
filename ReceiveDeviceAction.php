<?php

namespace App\Actions\Devices;

use App\Models\Customer;
use App\Models\Device;
use App\Models\DeviceChecklist;
use App\Models\DeviceStatus;
use App\Services\AuditService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReceiveDeviceAction
{
    /**
     * استلام جهاز جديد (معاملة متكاملة)
     */
    public function handle(array $data): Device
    {
        // نبدأ المعاملة
        return DB::transaction(function () use ($data) {

            // 1. البحث عن العميل أو إنشاؤه
            $customer = Customer::firstOrCreate(
                ['phone' => $data['customer_phone']],
                [
                    'full_name' => $data['customer_name'],
                    'phone' => $data['customer_phone'],
                    'email' => $data['customer_email'] ?? null,
                    'address' => $data['customer_address'] ?? null,
                ]
            );

            // 2. توليد كود الجهاز الفريد
            $deviceCode = 'DEV-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

            // 3. جلب حالة "معلق" (Pending) كحالة افتراضية
            $pendingStatus = DeviceStatus::where('slug', 'pending')->firstOrFail();

            // 4. إنشاء الجهاز
            $device = Device::create([
                'device_code' => $deviceCode,
                'customer_id' => $customer->id,
                'brand' => $data['brand'],
                'model' => $data['model'],
                'color' => $data['color'] ?? null,
                'storage_capacity' => $data['storage'] ?? null,
                'imei_1' => $data['imei_1'] ?? null,
                'imei_2' => $data['imei_2'] ?? null,
                'reported_issue' => $data['issue'],
                'current_status_id' => $pendingStatus->id,
                'assigned_technician_id' => $data['technician_id'] ?? null,
                'received_by' => auth()->id(),
                'intake_checklist' => $data['checklist'] ?? [], // JSON
                'waiting_for_part' => $data['waiting_for_part'] ?? null,
                'received_at' => now(),
            ]);

            // 5. حفظ الفحص المبدئي في جدول الـ Checklist (الجديد)
            if (isset($data['checklist']) && is_array($data['checklist'])) {
                DeviceChecklist::create([
                    'device_id' => $device->id,
                    'check_type' => 'before',
                    'wifi_working' => $data['checklist']['wifi'] ?? null,
                    'bluetooth_working' => $data['checklist']['bluetooth'] ?? null,
                    'network_working' => $data['checklist']['network'] ?? null,
                    'camera_working' => $data['checklist']['camera'] ?? null,
                    'fingerprint_working' => $data['checklist']['fingerprint'] ?? null,
                    'audio_working' => $data['checklist']['audio'] ?? null,
                    'charging_working' => $data['checklist']['charging'] ?? null,
                    'screen_condition' => $data['checklist']['screen'] ?? 'not_checked',
                    'notes' => $data['checklist']['notes'] ?? null,
                    'checked_by' => auth()->id(),
                    'checked_at' => now(),
                ]);
            }

            // 6. ✅ تسجيل حدث في سجل التدقيق (باستخدام AuditService بدلاً من activity())
            $audit = new AuditService();
            $audit->log(
                'device_received',
                'devices',
                $device->id,
                null,
                [
                    'customer' => $customer->full_name,
                    'device_code' => $deviceCode,
                    'technician' => $data['technician_id'] ?? 'غير محدد'
                ]
            );

            // 7. إشعار للمدير (Event هيتشغل لوحده برضه، بس هنضيفه هنا احتياطي)
            // event(new DeviceReceived($device));

            return $device;
        });
    }
}