<div class="max-w-6xl mx-auto bg-white rounded-xl shadow-sm p-6">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">📱 تفاصيل الجهاز</h1>
        <div class="flex gap-2 flex-wrap">
            <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'accountant'): ?>
                <a href="/sales/create?device_id=<?php echo $device['id']; ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">
                    <i class="fas fa-file-invoice"></i> إنشاء فاتورة
                </a>
            <?php endif; ?>
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <?php if (in_array($device['status_slug'], ['suspended', 'delivered'])): ?>
                <a href="/devices/delete/<?php echo $device['id']; ?>" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm transition" onclick="return confirm('⚠️ هل أنت متأكد من حذف هذا الجهاز؟')">
                    <i class="fas fa-trash"></i> حذف
                </a>
                <?php endif; ?>
            <?php endif; ?>
            <!-- زر طباعة الفاتورة (لو موجودة) -->
            <?php if (!empty($sale)): ?>
                <a href="/sales/print/<?php echo $sale['id']; ?>" target="_blank" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm transition">
                    <i class="fas fa-print"></i> طباعة الفاتورة
                </a>
            <?php endif; ?>
            <a href="/devices" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition">
                <i class="fas fa-arrow-right"></i> العودة
            </a>
        </div>
    </div>

    <!-- معلومات الجهاز (نفسه) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-gray-50 p-4 rounded-lg mb-6">
        <div><p class="text-xs text-gray-400">الكود</p><p class="font-mono text-lg font-bold text-blue-600"><?php echo $device['device_code']; ?></p></div>
        <div>
            <p class="text-xs text-gray-400">الحالة</p>
            <span class="px-3 py-1 rounded-full text-sm 
                <?php 
                if ($device['status_slug'] == 'delivered') echo 'bg-gray-200 text-gray-600';
                elseif ($device['status_slug'] == 'ready_for_pickup') echo 'bg-green-100 text-green-800';
                elseif ($device['status_slug'] == 'cancelled') echo 'bg-red-200 text-red-800';
                elseif ($device['status_slug'] == 'suspended') echo 'bg-red-100 text-red-800';
                else echo 'bg-blue-100 text-blue-800';
                ?>">
                <?php echo $device['status_name']; ?>
            </span>
            <?php if (!empty($device['rejection_reason'])): ?>
                <div class="text-xs text-red-600 mt-1">⚠️ سبب الإلغاء: <?php echo htmlspecialchars($device['rejection_reason']); ?></div>
            <?php endif; ?>
        </div>
        <div><p class="text-xs text-gray-400">الفني المسؤول</p><p class="font-medium"><?php echo $device['technician_name'] ?? 'لم يتم التوزيع بعد'; ?></p></div>
        <div><p class="text-xs text-gray-400">الجهاز</p><p class="font-medium"><?php echo $device['brand']; ?> - <?php echo $device['model']; ?></p></div>
        <div><p class="text-xs text-gray-400">العميل</p><p class="font-medium"><?php echo $device['customer_name']; ?></p><p class="text-sm text-gray-500"><?php echo $device['customer_phone']; ?></p></div>
        <div><p class="text-xs text-gray-400">تاريخ الاستلام</p><p class="text-sm"><?php echo date('Y-m-d h:i A', strtotime($device['received_at'])); ?></p></div>
    </div>

    <!-- العطل والتشخيص -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="border rounded-lg p-3"><p class="text-xs text-gray-400">العطل المبلغ عنه</p><p class="bg-gray-100 p-2 rounded mt-1"><?php echo $device['reported_issue']; ?></p></div>
        <div class="border rounded-lg p-3"><p class="text-xs text-gray-400">التشخيص</p><p class="bg-gray-100 p-2 rounded mt-1"><?php echo $device['diagnosed_issue'] ?? 'لم يتم التشخيص بعد'; ?></p></div>
        <?php if (!empty($device['waiting_for_part'])): ?>
            <div class="border rounded-lg p-3 border-amber-500 bg-amber-50 col-span-2"><p class="text-xs text-gray-400">⏳ في انتظار قطعة غيار</p><p class="font-bold text-amber-700"><?php echo $device['waiting_for_part']; ?></p></div>
        <?php endif; ?>
    </div>

    <!-- الوقت -->
    <?php if ($_SESSION['role'] !== 'technician'): ?>
    <div class="bg-blue-50 p-3 rounded-lg mb-6">
        <p class="text-sm text-gray-600"><i class="fas fa-clock text-blue-500"></i> الوقت المستغرق الفعلي: <strong><?php echo $actualHours; ?> ساعة <?php echo $actualMinutes; ?> دقيقة <?php echo $actualSeconds; ?> ثانية</strong></p>
    </div>
    <?php endif; ?>

    <!-- الأزرار -->
    <?php
    $statusSlug = $device['status_slug'] ?? 'pending';
    $userRole = $_SESSION['role'] ?? 'guest';

    $actions = [
        'pending' => ['technician' => ['url' => '/technician/start-inspection', 'label' => '🔍 بدء الفحص']],
        'inspection' => ['technician' => ['url' => '/technician/request-part', 'label' => '⏳ طلب قطعة غيار', 'input' => 'part_name']],
        'suspended' => ['technician' => ['url' => '/technician/start-repair', 'label' => '🔧 استئناف الإصلاح']],
        'repairing' => ['technician' => ['url' => '/technician/complete-repair', 'label' => '✅ تم الإصلاح']],
        'ready' => [
            'admin' => ['url' => '/devices/deliver', 'label' => '🚪 تسليم للعميل'],
            'accountant' => ['url' => '/devices/deliver', 'label' => '🚪 تسليم للعميل']
        ],
        'ready_for_pickup' => [
            'admin' => ['url' => '/devices/deliver', 'label' => '🚪 تسليم للعميل'],
            'accountant' => ['url' => '/devices/deliver', 'label' => '🚪 تسليم للعميل']
        ],
    ];

    $allowedAction = null;
    if (isset($actions[$statusSlug])) {
        foreach ($actions[$statusSlug] as $role => $action) {
            if ($role === $userRole) {
                $allowedAction = $action;
                break;
            }
        }
    }
    ?>

    <?php if ($allowedAction): ?>
        <div class="mt-4 bg-gray-50 p-4 rounded-lg border border-gray-200">
            <form method="POST" action="<?php echo $allowedAction['url']; ?>" class="flex flex-wrap gap-2 items-center">
                <input type="hidden" name="device_id" value="<?php echo $device['id']; ?>">
                <?php if (isset($allowedAction['input'])): ?>
                    <input type="text" name="<?php echo $allowedAction['input']; ?>" placeholder="اسم القطعة..." class="border rounded-lg px-3 py-2 text-sm flex-1 min-w-[150px] focus:ring-2 focus:ring-blue-500 outline-none" required>
                <?php endif; ?>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg text-sm transition font-bold shadow-md">
                    <?php echo $allowedAction['label']; ?>
                </button>
            </form>
        </div>
    <?php else: ?>
        <?php if ($statusSlug === 'delivered'): ?>
            <p class="text-green-600 font-bold text-center py-2">✅ تم تسليم الجهاز</p>
        <?php elseif ($statusSlug === 'cancelled'): ?>
            <p class="text-red-600 font-bold text-center py-2">❌ تم إلغاء التصليح حسب رغبة العميل</p>
        <?php elseif ($statusSlug === 'ready_for_pickup' && $userRole === 'technician'): ?>
            <p class="text-gray-500 text-center py-2">⏳ في انتظار تسليم المدير</p>
        <?php else: ?>
            <p class="text-gray-400 text-center py-2">لا توجد إجراءات متاحة</p>
        <?php endif; ?>
    <?php endif; ?>

    <!-- ❌ إلغاء التصليح (يظهر فقط للجهاز المعلق Suspended) -->
    <?php if (in_array($userRole, ['admin', 'accountant']) && $statusSlug === 'suspended'): ?>
    <div class="mt-4 bg-red-50 p-4 rounded-lg border border-red-200">
        <h4 class="font-semibold text-red-700 mb-2"><i class="fas fa-times-circle text-red-500"></i> إلغاء التصليح (حسب رغبة العميل)</h4>
        <form method="POST" action="/devices/cancel-repair" class="flex flex-wrap gap-2 items-center">
            <input type="hidden" name="device_id" value="<?php echo $device['id']; ?>">
            <input type="text" name="rejection_reason" placeholder="سبب الإلغاء..." class="flex-1 border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 outline-none min-w-[200px]" required>
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm transition" onclick="return confirm('⚠️ هل أنت متأكد من إلغاء تصليح هذا الجهاز حسب رغبة العميل؟')">
                <i class="fas fa-times"></i> إلغاء التصليح
            </button>
        </form>
        <p class="text-xs text-red-600 mt-1">💡 سيتم تغيير حالة الجهاز إلى "ملغي" وتسجيل سبب الإلغاء</p>
    </div>
    <?php endif; ?>

    <!-- تشخيص للفني -->
    <?php if ($userRole === 'technician' && !in_array($statusSlug, ['delivered', 'cancelled'])): ?>
    <div class="mt-4 bg-gray-50 p-4 rounded-lg border border-gray-200">
        <h4 class="font-semibold text-gray-700 mb-2"><i class="fas fa-stethoscope text-blue-500"></i> تشخيص العطل</h4>
        <form method="POST" action="/technician/diagnose" class="flex flex-wrap gap-2 items-center">
            <input type="hidden" name="device_id" value="<?php echo $device['id']; ?>">
            <input type="text" name="diagnosis" placeholder="اكتب التشخيص..." class="flex-1 border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none" value="<?php echo htmlspecialchars($device['diagnosed_issue'] ?? ''); ?>">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition"><i class="fas fa-save"></i> حفظ التشخيص</button>
        </form>
        <p class="text-xs text-gray-400 mt-1">💡 التشخيص سيظهر للعميل في صفحة التتبع وللمدير</p>
    </div>
    <?php endif; ?>

    <!-- سجل الصيانة -->
    <div class="mt-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-3"><i class="fas fa-clipboard-list text-purple-500"></i> سجل الصيانة</h3>
        <?php if (empty($maintenanceLog)): ?>
            <p class="text-sm text-gray-400 text-center py-4">📭 لا توجد سجلات صيانة</p>
        <?php else: ?>
            <div class="space-y-2 max-h-60 overflow-y-auto">
                <?php foreach ($maintenanceLog as $log): ?>
                    <div class="flex justify-between items-start border-r-4 border-blue-400 bg-gray-50 p-3 rounded-lg">
                        <div>
                            <span class="font-bold text-sm text-blue-700"><?php echo htmlspecialchars($log['action']); ?></span>
                            <p class="text-sm text-gray-600"><?php echo htmlspecialchars($log['description']); ?></p>
                            <p class="text-xs text-gray-400">بواسطة: <?php echo $log['performed_by_name'] ?? 'نظام'; ?></p>
                        </div>
                        <span class="text-xs text-gray-400 whitespace-nowrap"><?php echo date('Y-m-d h:i A', strtotime($log['performed_at'])); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- QR Code -->
    <?php if (!empty($qr)): ?>
        <div class="text-center border-t pt-4 mt-4">
            <p class="text-sm text-gray-500 mb-2">📸 امسح QR Code لمتابعة الجهاز</p>
            <img src="<?php echo $qr['qr_image_path']; ?>" alt="QR Code" class="mx-auto border rounded-lg" style="max-width: 150px;">
            <p class="text-xs text-blue-600 mt-2"><a href="http://localhost:8000/track/<?php echo $device['device_code']; ?>" target="_blank">http://localhost:8000/track/<?php echo $device['device_code']; ?></a></p>
        </div>
    <?php endif; ?>

    <!-- أزرار إضافية -->
    <div class="mt-4 flex flex-wrap gap-2">
        <form method="POST" action="/send-whatsapp" class="flex-1 min-w-[150px]">
            <input type="hidden" name="device_id" value="<?php echo $device['id']; ?>">
            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white py-2 rounded-lg transition flex items-center justify-center gap-2">
                <i class="fab fa-whatsapp"></i> إرسال رابط التتبع
            </button>
        </form>

        <?php if ($device['status_slug'] == 'ready_for_pickup' && $_SESSION['role'] === 'admin'): ?>
            <a href="/checklist/print/<?php echo $device['id']; ?>" target="_blank" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm transition flex items-center gap-2">
                <i class="fas fa-print"></i> طباعة الفحص
            </a>
        <?php endif; ?>

        <a href="/devices" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition">
            <i class="fas fa-arrow-right"></i> العودة
        </a>
    </div>
    <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'technician'): ?>
    <div class="mt-4 bg-gray-50 p-4 rounded-lg border border-gray-200">
        <h4 class="font-semibold text-gray-700 mb-2">
            <i class="fas fa-robot text-blue-500"></i> تحليل العطل آلياً
        </h4>
        <p class="text-sm text-gray-500 mb-2">
            سيقوم النظام بتحليل العطل المبلغ عنه وتحديد إذا كان محتاج قطعة غيار، 
            ثم يبحث عن القطعة في المخزون ويغير حالة الجهاز تلقائياً.
        </p>
        <a href="/devices/analyze/<?php echo $device['id']; ?>" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition inline-block"
           onclick="return confirm('سيتم تحليل العطل وتحديث حالة الجهاز تلقائياً. هل أنت متأكد؟')">
            <i class="fas fa-microchip"></i> تحليل العطل
        </a>
    </div>
<?php endif; ?>
</div>