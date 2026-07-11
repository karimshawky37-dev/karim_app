<div class="max-w-3xl mx-auto bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-clipboard-check text-purple-500"></i> 
            فحص بعد الإصلاح
        </h1>
        <a href="/technician-dashboard" class="text-blue-600 hover:text-blue-800 text-sm">
            <i class="fas fa-arrow-left"></i> العودة
        </a>
    </div>

    <!-- ✅ معلومات الجهاز (مع التحقق من وجود البيانات) -->
    <div class="bg-blue-50 p-4 rounded-lg mb-6">
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <span class="text-gray-500">الجهاز:</span> 
                <strong><?php echo ($device['brand'] ?? '') . ' ' . ($device['model'] ?? ''); ?></strong>
            </div>
            <div>
                <span class="text-gray-500">الكود:</span> 
                <strong class="font-mono"><?php echo $device['device_code'] ?? '—'; ?></strong>
            </div>
            <div>
                <span class="text-gray-500">العميل:</span> 
                <strong><?php echo $device['customer_name'] ?? 'غير معروف'; ?></strong>
            </div>
            <div>
                <span class="text-gray-500">العطل:</span> 
                <strong><?php echo $device['reported_issue'] ?? '—'; ?></strong>
            </div>
            <div>
                <span class="text-gray-500">رقم الهاتف:</span> 
                <strong><?php echo $device['customer_phone'] ?? '—'; ?></strong>
            </div>
            <div>
                <span class="text-gray-500">الحالة:</span> 
                <strong><?php echo $device['status_name'] ?? '—'; ?></strong>
            </div>
        </div>
    </div>

    <form method="POST" action="/technician/save-after-repair-checklist">
        <input type="hidden" name="device_id" value="<?php echo $device['id']; ?>">

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gray-50 p-3 rounded-lg text-center">
                <label class="block text-sm font-medium text-gray-700 mb-2">واي فاي</label>
                <div class="flex justify-center gap-4">
                    <label><input type="radio" name="checklist[wifi]" value="1" checked> شغال</label>
                    <label><input type="radio" name="checklist[wifi]" value="0"> عطل</label>
                </div>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg text-center">
                <label class="block text-sm font-medium text-gray-700 mb-2">بلوتوث</label>
                <div class="flex justify-center gap-4">
                    <label><input type="radio" name="checklist[bluetooth]" value="1" checked> شغال</label>
                    <label><input type="radio" name="checklist[bluetooth]" value="0"> عطل</label>
                </div>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg text-center">
                <label class="block text-sm font-medium text-gray-700 mb-2">شبكة</label>
                <div class="flex justify-center gap-4">
                    <label><input type="radio" name="checklist[network]" value="1" checked> شغال</label>
                    <label><input type="radio" name="checklist[network]" value="0"> عطل</label>
                </div>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg text-center">
                <label class="block text-sm font-medium text-gray-700 mb-2">كاميرا</label>
                <div class="flex justify-center gap-4">
                    <label><input type="radio" name="checklist[camera]" value="1" checked> شغال</label>
                    <label><input type="radio" name="checklist[camera]" value="0"> عطل</label>
                </div>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg text-center">
                <label class="block text-sm font-medium text-gray-700 mb-2">بصمة</label>
                <div class="flex justify-center gap-4">
                    <label><input type="radio" name="checklist[fingerprint]" value="1" checked> شغال</label>
                    <label><input type="radio" name="checklist[fingerprint]" value="0"> عطل</label>
                </div>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg text-center">
                <label class="block text-sm font-medium text-gray-700 mb-2">صوت</label>
                <div class="flex justify-center gap-4">
                    <label><input type="radio" name="checklist[audio]" value="1" checked> شغال</label>
                    <label><input type="radio" name="checklist[audio]" value="0"> عطل</label>
                </div>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg text-center">
                <label class="block text-sm font-medium text-gray-700 mb-2">شحن</label>
                <div class="flex justify-center gap-4">
                    <label><input type="radio" name="checklist[charging]" value="1" checked> شغال</label>
                    <label><input type="radio" name="checklist[charging]" value="0"> عطل</label>
                </div>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg text-center">
                <label class="block text-sm font-medium text-gray-700 mb-2">حالة الشاشة</label>
                <select name="checklist[screen]" class="w-full border rounded-lg px-2 py-1 text-sm">
                    <option value="good">سليمة</option>
                    <option value="scratched">مخدوشة</option>
                    <option value="cracked">مشرخة</option>
                    <option value="broken">مكسورة</option>
                </select>
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">ملاحظات الفحص</label>
            <textarea name="checklist[notes]" rows="2" placeholder="أي ملاحظات عن حالة الجهاز بعد الإصلاح..." 
                      class="w-full border rounded-lg px-3 py-2"></textarea>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">ما تم إصلاحه (تفاصيل الإصلاح)</label>
            <textarea name="checklist[repair_notes]" rows="3" placeholder="وصف ما تم إصلاحه في الجهاز..." 
                      class="w-full border rounded-lg px-3 py-2" required></textarea>
        </div>

        <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2 rounded-lg transition">
            <i class="fas fa-save"></i> حفظ الفحص
        </button>
    </form>
</div>