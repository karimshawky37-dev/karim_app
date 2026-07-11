<div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">📱 تفاصيل الجهاز</h1>
        <div class="flex gap-2 flex-wrap">
            <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'accountant'): ?>
                <a href="/sales/create?device_id=<?php echo $device['id']; ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">
                    <i class="fas fa-file-invoice"></i> إنشاء فاتورة للجهاز
                </a>
            <?php endif; ?>
            <a href="/devices" class="text-blue-600 hover:text-blue-800 text-sm"><i class="fas fa-arrow-left"></i> العودة</a>
            <a href="/devices/delete/<?php echo $device['id']; ?>" class="text-red-500 hover:text-red-700 text-sm transition" onclick="return confirm('⚠️ هل أنت متأكد من حذف هذا الجهاز؟')"><i class="fas fa-trash"></i> حذف</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- ... معلومات الجهاز ... -->
        <div><p class="text-sm text-gray-500">الكود</p><p class="font-mono text-lg"><?php echo $device['device_code']; ?></p></div>
        <div><p class="text-sm text-gray-500">الحالة</p><p><span class="px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800"><?php echo $device['status_name']; ?></span></p></div>
        <div><p class="text-sm text-gray-500">الشركة / الموديل</p><p class="font-medium"><?php echo $device['brand']; ?> - <?php echo $device['model']; ?></p></div>
        <div><p class="text-sm text-gray-500">اللون / السعة</p><p><?php echo $device['color']; ?> - <?php echo $device['storage_capacity']; ?></p></div>
        <div class="col-span-2"><p class="text-sm text-gray-500">العميل</p><p class="font-medium"><?php echo $device['customer_name']; ?></p><p class="text-sm text-gray-500"><?php echo $device['customer_phone']; ?></p></div>
        <div class="col-span-2"><p class="text-sm text-gray-500">العطل المبلغ عنه</p><p class="bg-gray-50 p-3 rounded-lg"><?php echo $device['reported_issue']; ?></p></div>
        <div class="col-span-2"><p class="text-sm text-gray-500">IMEI 1 / IMEI 2</p><p class="font-mono text-sm"><?php echo $device['imei_1']; ?> / <?php echo $device['imei_2']; ?></p></div>
        <?php if (!empty($device['waiting_for_part'])): ?>
            <div class="col-span-2"><p class="text-sm text-gray-500">⏳ في انتظار قطعة غيار</p><p class="bg-amber-50 p-3 rounded-lg border-r-4 border-amber-500"><?php echo $device['waiting_for_part']; ?></p></div>
        <?php endif; ?>
        <div class="col-span-2 mt-2">
            <form method="POST" action="/send-whatsapp">
                <input type="hidden" name="device_id" value="<?php echo $device['id']; ?>">
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition flex items-center justify-center gap-2 w-full">
                    <span>📱</span> إرسال رابط التتبع للعميل عبر واتساب
                </button>
            </form>
            <p class="text-xs text-gray-400 mt-1 text-center">سيتم فتح واتساب برسالة جاهزة تحتوي على رابط التتبع</p>
        </div>

        <div class="col-span-2 mt-4 pt-4 border-t border-gray-200 text-center">
            <p class="text-sm text-gray-500 mb-2">📸 امسح QR Code بكاميرا موبايلك لمتابعة الجهاز</p>
            <div class="flex justify-center">
                <?php if ($qr): ?>
                    <img src="<?php echo $qr['qr_image_path']; ?>" alt="QR Code" class="mx-auto border rounded-lg" style="max-width: 200px;">
                <?php else: ?>
                    <p class="text-gray-400">لم يتم توليد QR Code</p>
                <?php endif; ?>
            </div>
            <p class="text-xs text-blue-600 mt-3"><a href="http://localhost:8000/track/<?php echo $device['device_code']; ?>" target="_blank">رابط التتبع: http://localhost:8000/track/<?php echo $device['device_code']; ?></a></p>
        </div>

        <div class="col-span-2 mt-2 text-center border-t border-gray-100 pt-3">
            <p class="text-xs text-gray-400">📊 باركود الجهاز</p>
            <img src="https://barcode.tec-it.com/barcode.ashx?data=<?php echo $device['device_code']; ?>&code=Code128&dpi=96" alt="Barcode" class="mx-auto" style="max-width: 200px; height: auto;">
            <p class="text-xs text-gray-400 mt-1"><?php echo $device['device_code']; ?></p>
        </div>

        <div class="col-span-2 mt-4 pt-4 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">🔄 سجل تحويلات الفنيين</h3>
            <?php if (empty($transfers)): ?>
                <p class="text-sm text-gray-500">لا توجد تحويلات بعد</p>
            <?php else: ?>
                <ul class="space-y-2">
                    <?php foreach ($transfers as $t): ?>
                        <li class="text-sm border-r-4 border-blue-400 pr-3 py-1">
                            من <span class="font-medium"><?php echo $t['from_name'] ?? 'بداية'; ?></span> → إلى <span class="font-medium text-blue-600"><?php echo $t['to_name']; ?></span>
                            <span class="text-gray-400 text-xs">(<?php echo date('Y-m-d H:i', strtotime($t['transferred_at'])); ?>)</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <!-- الفاتورة المرتبطة -->
        <?php if ($sale): ?>
            <div class="col-span-2 mt-4 pt-4 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-3"><i class="fas fa-file-invoice text-blue-500"></i> الفاتورة المرتبطة</h3>
                <div class="bg-blue-50 p-4 rounded-lg border-r-4 border-blue-500">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div><p class="text-xs text-gray-500">رقم الفاتورة</p><p class="font-bold text-blue-600"><?php echo $sale['invoice_number']; ?></p></div>
                        <div><p class="text-xs text-gray-500">المبلغ</p><p class="font-bold"><?php echo number_format($sale['total_amount'], 2); ?></p></div>
                        <div><p class="text-xs text-gray-500">الحالة</p><p><span class="px-2 py-1 rounded-full text-xs font-medium <?php echo $sale['status'] == 'completed' ? 'bg-green-100 text-green-700' : ($sale['status'] == 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700'); ?>"><?php echo $sale['status'] == 'completed' ? '✅ مكتملة' : ($sale['status'] == 'pending' ? '⏳ معلقة' : '💰 مدفوع جزئي'); ?></span></p></div>
                        <div><p class="text-xs text-gray-500">التاريخ</p><p class="text-sm"><?php echo date('Y-m-d', strtotime($sale['sale_date'])); ?></p></div>
                    </div>
                    <div class="mt-3"><a href="/sales/view/<?php echo $sale['id']; ?>" class="text-blue-600 hover:text-blue-800 text-sm"><i class="fas fa-eye"></i> عرض تفاصيل الفاتورة</a></div>
                </div>
            </div>
        <?php endif; ?>

        <!-- ===== عرض نتائج الفحص ===== -->
        <?php if (isset($checklist['before']) && $checklist['before']): ?>
            <div class="col-span-2 mt-4 pt-4 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-3"><i class="fas fa-clipboard-list text-green-500"></i> الفحص قبل الصيانة</h3>
                <div class="bg-green-50 p-4 rounded-lg border-r-4 border-green-500"><!-- تفاصيل الفحص --></div>
            </div>
        <?php endif; ?>

        <?php if (isset($checklist['after']) && $checklist['after']): ?>
            <div class="col-span-2 mt-4 pt-4 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-3"><i class="fas fa-check-circle text-blue-500"></i> الفحص بعد الصيانة</h3>
                <div class="bg-blue-50 p-4 rounded-lg border-r-4 border-blue-500"><!-- تفاصيل الفحص --></div>
            </div>
        <?php endif; ?>

        <?php if (isset($checklist['final']) && $checklist['final']): ?>
            <div class="col-span-2 mt-4 pt-4 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-3"><i class="fas fa-check-double text-purple-500"></i> الفحص النهائي للتسليم</h3>
                <div class="bg-purple-50 p-4 rounded-lg border-r-4 border-purple-500">
                    <!-- تفاصيل الفحص -->
                </div>
                <div class="mt-2">
                    <a href="/checklist/print/<?php echo $device['id']; ?>" target="_blank" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm transition inline-block">
                        <i class="fas fa-print"></i> طباعة الفحص
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <!-- ===== الفحص النهائي للتسليم (نموذج) ===== -->
        <?php if ($_SESSION['role'] == 'admin' && $device['status_slug'] == 'ready_for_pickup'): ?>
            <div class="col-span-2 mt-4 pt-4 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-3"><i class="fas fa-clipboard-check text-purple-500"></i> الفحص النهائي للتسليم</h3>
                <div class="mb-3">
                    <a href="/checklist/print/<?php echo $device['id']; ?>" target="_blank" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm transition inline-block">
                        <i class="fas fa-print"></i> طباعة الفحص (فارغ)
                    </a>
                </div>
                <form method="POST" action="/devices/deliver" onsubmit="return confirm('⚠️ هل أنت متأكد من تسليم هذا الجهاز للعميل؟')">
                    <input type="hidden" name="device_id" value="<?php echo $device['id']; ?>">
                    <div class="bg-purple-50 p-4 rounded-lg border-r-4 border-purple-500">
                        <p class="text-sm text-gray-600 mb-3">قم بفحص الجهاز مرة أخيرة قبل التسليم</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <div class="flex items-center gap-2"><input type="checkbox" name="final_checklist[wifi]" value="1" checked class="w-4 h-4"><label class="text-sm text-gray-700">الواي فاي</label></div>
                            <div class="flex items-center gap-2"><input type="checkbox" name="final_checklist[bluetooth]" value="1" checked class="w-4 h-4"><label class="text-sm text-gray-700">البلوتوث</label></div>
                            <div class="flex items-center gap-2"><input type="checkbox" name="final_checklist[network]" value="1" checked class="w-4 h-4"><label class="text-sm text-gray-700">الشبكة</label></div>
                            <div class="flex items-center gap-2"><input type="checkbox" name="final_checklist[camera]" value="1" checked class="w-4 h-4"><label class="text-sm text-gray-700">الكاميرا</label></div>
                            <div class="flex items-center gap-2"><input type="checkbox" name="final_checklist[fingerprint]" value="1" checked class="w-4 h-4"><label class="text-sm text-gray-700">البصمة</label></div>
                            <div class="flex items-center gap-2"><input type="checkbox" name="final_checklist[audio]" value="1" checked class="w-4 h-4"><label class="text-sm text-gray-700">الصوت</label></div>
                            <div class="flex items-center gap-2"><input type="checkbox" name="final_checklist[charging]" value="1" checked class="w-4 h-4"><label class="text-sm text-gray-700">الشحن</label></div>
                            <div><select name="final_checklist[screen]" class="w-full border rounded-lg px-2 py-1 text-sm"><option value="good">سليمة</option><option value="scratched">مخدوشة</option><option value="cracked">مشرخة</option><option value="broken">مكسورة</option></select></div>
                        </div>
                        <div class="mt-3"><label class="block text-sm font-medium text-gray-700">ملاحظات التسليم</label><textarea name="final_checklist[notes]" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm"></textarea></div>
                        <div class="mt-3"><label class="block text-sm font-medium text-gray-700">توقيع المستلم (اختياري)</label><input type="text" name="final_checklist[signature]" placeholder="اسم المستلم" class="w-full border rounded-lg px-3 py-2 text-sm"></div>
                    </div>
                    <button type="submit" class="mt-4 w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition text-lg">✅ تسليم الجهاز للعميل</button>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <div class="mt-6 pt-6 border-t border-gray-200 text-center text-sm text-gray-500">
        تاريخ الاستلام: <?php echo date('Y-m-d H:i', strtotime($device['received_at'])); ?>
    </div>
</div>