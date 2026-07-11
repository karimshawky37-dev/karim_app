<?php
namespace App\Controllers;

use App\Config\Database;

class TrackingController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->db = Database::getInstance()->getConnection();
    }

    public function show($code)
    {
        // البحث عن الجهاز
        $stmt = $this->db->prepare("
            SELECT 
                d.*,
                c.full_name as customer_name,
                c.phone as customer_phone,
                ds.name as status_name,
                ds.slug as status_slug,
                ds.color as status_color,
                u.full_name as technician_name
            FROM devices d
            LEFT JOIN customers c ON d.customer_id = c.id
            LEFT JOIN device_statuses ds ON d.current_status_id = ds.id
            LEFT JOIN users u ON d.assigned_technician_id = u.id
            WHERE d.device_code = ? AND d.deleted_at IS NULL
        ");
        $stmt->execute([$code]);
        $device = $stmt->fetch();

        if (!$device) {
            // صفحة 404
            echo "
            <!DOCTYPE html>
            <html dir='rtl' lang='ar'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>الجهاز غير موجود</title>
                <script src='https://cdn.tailwindcss.com'></script>
                <link href='https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap' rel='stylesheet'>
                <style>body{font-family:'Tajawal',sans-serif;background:#f0f4f8;display:flex;justify-content:center;align-items:center;height:100vh;margin:0;}</style>
            </head>
            <body>
                <div style='background:white;padding:40px;border-radius:16px;text-align:center;max-width:400px;box-shadow:0 4px 20px rgba(0,0,0,0.06);'>
                    <div style='font-size:60px;'>🔍</div>
                    <h1 style='font-size:24px;font-weight:800;color:#1e293b;'>الجهاز غير موجود</h1>
                    <p style='color:#64748b;'>لم نتمكن من العثور على جهاز بهذا الكود</p>
                    <p style='font-size:12px;color:#94a3b8;margin-top:8px;'>الرجاء التأكد من الكود أو الاتصال بالمحل</p>
                    <div style='margin-top:20px;border-top:1px solid #e2e8f0;padding-top:16px;'>
                        <p style='font-size:14px;color:#1e293b;font-weight:600;'>📞 للاستفسار:</p>
                        <p style='font-size:12px;color:#64748b;'>01012345678 | 01087654321</p>
                    </div>
                </div>
            </body>
            </html>
            ";
            return;
        }

        // جلب التحويلات
        $transfers = array();
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    tt.*,
                    u1.full_name as from_name,
                    u2.full_name as to_name,
                    tt.transferred_at
                FROM technician_transfers tt
                LEFT JOIN users u1 ON tt.from_technician_id = u1.id
                LEFT JOIN users u2 ON tt.to_technician_id = u2.id
                WHERE tt.device_id = ?
                ORDER BY tt.transferred_at ASC
            ");
            $stmt->execute([$device['id']]);
            $transfers = $stmt->fetchAll();
        } catch (\Exception $e) {
            $transfers = array();
        }

        // ألوان الحالات
        $statusColors = array(
            'pending' => '#f59e0b',
            'inspection' => '#3b82f6',
            'suspended' => '#ef4444',
            'repairing' => '#8b5cf6',
            'ready' => '#22c55e',
            'ready_for_pickup' => '#22c55e',
            'delivered' => '#22c55e',
            'cancelled' => '#ef4444'
        );
        $statusSlug = $device['status_slug'];
        $statusColor = isset($statusColors[$statusSlug]) ? $statusColors[$statusSlug] : '#94a3b8';

        // نسبة التقدم
        $progressMap = array(
            'pending' => 15,
            'inspection' => 30,
            'suspended' => 45,
            'repairing' => 60,
            'ready' => 80,
            'ready_for_pickup' => 95,
            'delivered' => 100
        );
        $progress = isset($progressMap[$statusSlug]) ? $progressMap[$statusSlug] : 10;

        // خطوات سير العمل
        $steps = array(
            array('label' => 'تم الاستلام', 'slug' => 'pending', 'icon' => '📥'),
            array('label' => 'جاري الفحص', 'slug' => 'inspection', 'icon' => '🔍'),
            array('label' => 'في انتظار قطعة', 'slug' => 'suspended', 'icon' => '⏳'),
            array('label' => 'جاري الإصلاح', 'slug' => 'repairing', 'icon' => '🛠️'),
            array('label' => 'جاهز للتسليم', 'slug' => 'ready_for_pickup', 'icon' => '✅'),
            array('label' => 'تم التسليم', 'slug' => 'delivered', 'icon' => '🏁')
        );

        // ✅ وقت آخر تحديث (معالجة null)
        $updatedAt = !empty($device['updated_at']) ? date('Y-m-d h:i A', strtotime($device['updated_at'])) : '—';

        // عرض الصفحة
        echo "
        <!DOCTYPE html>
        <html dir='rtl' lang='ar'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>تتبع جهازك - {$device['device_code']}</title>
            <script src='https://cdn.tailwindcss.com'></script>
            <link href='https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap' rel='stylesheet'>
            <style>
                * { font-family: 'Tajawal', sans-serif; }
                body { background: #f0f4f8; margin: 0; padding: 20px; min-height: 100vh; display: flex; justify-content: center; align-items: center; }
                .track-card { max-width: 700px; width: 100%; background: white; border-radius: 24px; box-shadow: 0 20px 60px rgba(0,0,0,0.08); overflow: hidden; }
                .header { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); padding: 30px 24px; color: white; text-align: center; }
                .header h1 { font-size: 24px; font-weight: 800; margin: 0; }
                .header p { color: #94a3b8; margin: 4px 0 0; font-size: 14px; }
                .device-info { padding: 20px 24px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
                .device-info .label { color: #94a3b8; font-size: 11px; text-transform: uppercase; }
                .device-info .value { font-weight: 700; color: #0f172a; font-size: 15px; }
                .status-badge { display: inline-block; padding: 4px 16px; border-radius: 50px; font-size: 14px; font-weight: 700; color: white; background: {$statusColor}; }
                .progress-container { padding: 16px 24px; background: white; }
                .progress-bar { width: 100%; height: 8px; background: #e2e8f0; border-radius: 10px; overflow: hidden; }
                .progress-fill { height: 100%; background: {$statusColor}; border-radius: 10px; transition: width 0.8s ease; width: {$progress}%; }
                .progress-labels { display: flex; justify-content: space-between; font-size: 10px; color: #94a3b8; margin-top: 4px; }
                .timeline { padding: 16px 24px 24px; }
                .timeline-title { font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 12px; }
                .timeline-item { display: flex; gap: 12px; padding: 8px 0; border-right: 3px solid #e2e8f0; padding-right: 16px; margin-right: 8px; position: relative; }
                .timeline-item:last-child { border-right: none; }
                .timeline-item .icon { font-size: 20px; width: 32px; text-align: center; }
                .timeline-item .content { flex: 1; }
                .timeline-item .content .title { font-weight: 600; color: #0f172a; font-size: 14px; }
                .timeline-item .content .desc { color: #64748b; font-size: 13px; }
                .timeline-item .content .time { color: #94a3b8; font-size: 11px; }
                .timeline-item.active { border-right-color: {$statusColor}; }
                .timeline-item.active .content .title { color: {$statusColor}; }
                .timeline-item.completed { border-right-color: #22c55e; }
                .timeline-item.completed .content .title { color: #22c55e; }
                .footer { background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 16px 24px; text-align: center; font-size: 13px; color: #64748b; }
                .footer .phone { font-weight: 700; color: #0f172a; }
                @media (max-width: 480px) { .device-info { grid-template-columns: 1fr; } }
            </style>
        </head>
        <body>
            <div class='track-card'>
                <div class='header'>
                    <h1>📱 تتبع جهازك</h1>
                    <p>كود التتبع: <span style='font-family: monospace; background: rgba(255,255,255,0.1); padding: 2px 10px; border-radius: 6px;'>{$device['device_code']}</span></p>
                </div>

                <div class='device-info'>
                    <div><span class='label'>الجهاز</span><div class='value'>{$device['brand']} {$device['model']}</div></div>
                    <div><span class='label'>العميل</span><div class='value'>{$device['customer_name']}</div></div>
                    <div><span class='label'>الحالة</span><div><span class='status-badge'>{$device['status_name']}</span></div></div>
                    <div><span class='label'>الفني المسؤول</span><div class='value'>" . (isset($device['technician_name']) ? $device['technician_name'] : '—') . "</div></div>
                    <div><span class='label'>آخر تحديث</span><div class='value'>{$updatedAt}</div></div>
                </div>

                <div class='progress-container'>
                    <div class='progress-bar'><div class='progress-fill'></div></div>
                    <div class='progress-labels'><span>تم الاستلام</span><span>جاهز للتسليم</span></div>
                </div>

                <div class='timeline'>
                    <div class='timeline-title'>🔄 سير العمل</div>
                    ";

        $currentSlug = $device['status_slug'];
        foreach ($steps as $step) {
            $stepSlug = $step['slug'];
            $isActive = ($stepSlug == $currentSlug);
            $isCompleted = $this->isStepCompleted($stepSlug, $currentSlug);
            $class = '';
            if ($isActive) $class = 'active';
            elseif ($isCompleted) $class = 'completed';
            
            // ✅ وقت المرحلة (معالجة null)
            $stepTime = !empty($device['updated_at']) ? date('Y-m-d h:i A', strtotime($device['updated_at'])) : '';

            echo "
                    <div class='timeline-item {$class}'>
                        <div class='icon'>{$step['icon']}</div>
                        <div class='content'>
                            <div class='title'>{$step['label']}</div>
                            <div class='desc'>" . ($isActive ? 'المرحلة الحالية' : ($isCompleted ? 'تم ✅' : 'في انتظار')) . "</div>
                            <div class='time'>" . ($isActive ? $stepTime : '') . "</div>
                        </div>
                    </div>
                    ";
        }

        echo "
                </div>

                <div class='footer'>
                    <div style='font-weight:700; color:#0f172a; margin-bottom:4px;'>📞 للاستفسار</div>
                    <div><span class='phone'>01012345678</span> | <span class='phone'>01087654321</span></div>
                    <div style='font-size:11px; color:#94a3b8; margin-top:8px;'>© " . date('Y') . " مركز الصيانة - جميع الحقوق محفوظة</div>
                </div>
            </div>
        </body>
        </html>
        ";
    }

    // ✅ الدالة المعدلة (معالجة null)
    private function isStepCompleted($stepSlug, $currentSlug)
    {
        // لو currentSlug فارغ، يرجع false
        if (empty($currentSlug)) {
            return false;
        }

        $order = ['pending', 'inspection', 'suspended', 'repairing', 'ready', 'ready_for_pickup', 'delivered'];
        $stepIndex = array_search($stepSlug, $order);
        $currentIndex = array_search($currentSlug, $order);

        // لو أي من الحالات مش موجودة، يرجع false
        if ($stepIndex === false || $currentIndex === false) {
            return false;
        }

        // الخطوة "جاهز للتسليم" مكتملة لو الجهاز في ready, ready_for_pickup, أو delivered
        if ($stepSlug == 'ready_for_pickup' && in_array($currentSlug, ['ready', 'ready_for_pickup', 'delivered'])) {
            return true;
        }

        // الخطوة "تم التسليم" مكتملة لو الجهاز delivered
        if ($stepSlug == 'delivered' && $currentSlug == 'delivered') {
            return true;
        }

        // باقي الخطوات حسب الترتيب
        return $stepIndex < $currentIndex;
    }
}