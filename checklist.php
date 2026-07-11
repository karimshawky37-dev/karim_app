<div class="max-w-3xl mx-auto bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-clipboard-check text-blue-500"></i> 
            <?php echo $mode == 'before' ? 'الفحص المبدئي' : 'الفحص النهائي'; ?>
            - <?php echo $device['device_code']; ?>
        </h1>
        <a href="/devices/<?php echo $device['id']; ?>" class="text-blue-600 hover:text-blue-800 text-sm">
            <i class="fas fa-arrow-left"></i> العودة
        </a>
    </div>

    <div class="bg-gray-50 p-4 rounded-lg mb-6">
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div><span class="text-gray-500">الجهاز:</span> <strong><?php echo $device['brand'] . ' ' . $device['model']; ?></strong></div>
            <div><span class="text-gray-500">العميل:</span> <strong><?php echo $device['customer_name']; ?></strong></div>
            <div><span class="text-gray-500">العطل المبلغ عنه:</span> <strong><?php echo $device['reported_issue']; ?></strong></div>
            <div><span class="text-gray-500">نوع الفحص:</span> 
                <span class="px-2 py-1 rounded-full text-xs font-medium <?php echo $mode == 'before' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700'; ?>">
                    <?php echo $mode == 'before' ? 'قبل الصيانة' : 'بعد الصيانة'; ?>
                </span>
            </div>
        </div>
    </div>

    <form method="POST" action="/devices/store-checklist">
        <input type="hidden" name="device_id" value="<?php echo $device['id']; ?>">
        <input type="hidden" name="is_after_repair" value="<?php echo $mode == 'after' ? 1 : 0; ?>">

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gray-50 p-3 rounded-lg text-center">
                <label class="block text-sm font-medium text-gray-700 mb-2">واي فاي</label>
                <div class="flex justify-center gap-4">
                    <label><input type="radio" name="wifi" value="1" <?php echo ($checklist && $checklist['wifi_working']) ? 'checked' : ''; ?>> شغال</label>
                    <label><input type="radio" name="wifi" value="0" <?php echo ($checklist && !$checklist['wifi_working']) ? 'checked' : ''; ?>> عطل</label>
                </div>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg text-center">
                <label class="block text-sm font-medium text-gray-700 mb-2">بلوتوث</label>
                <div class="flex justify-center gap-4">
                    <label><input type="radio" name="bluetooth" value="1" <?php echo ($checklist && $checklist['bluetooth_working']) ? 'checked' : ''; ?>> شغال</label>
                    <label><input type="radio" name="bluetooth" value="0" <?php echo ($checklist && !$checklist['bluetooth_working']) ? 'checked' : ''; ?>> عطل</label>
                </div>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg text-center">
                <label class="block text-sm font-medium text-gray-700 mb-2">شبكة</label>
                <div class="flex justify-center gap-4">
                    <label><input type="radio" name="network" value="1" <?php echo ($checklist && $checklist['network_working']) ? 'checked' : ''; ?>> شغال</label>
                    <label><input type="radio" name="network" value="0" <?php echo ($checklist && !$checklist['network_working']) ? 'checked' : ''; ?>> عطل</label>
                </div>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg text-center">
                <label class="block text-sm font-medium text-gray-700 mb-2">كاميرا</label>
                <div class="flex justify-center gap-4">
                    <label><input type="radio" name="camera" value="1" <?php echo ($checklist && $checklist['camera_working']) ? 'checked' : ''; ?>> شغال</label>
                    <label><input type="radio" name="camera" value="0" <?php echo ($checklist && !$checklist['camera_working']) ? 'checked' : ''; ?>> عطل</label>
                </div>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg text-center">
                <label class="block text-sm font-medium text-gray-700 mb-2">بصمة</label>
                <div class="flex justify-center gap-4">
                    <label><input type="radio" name="fingerprint" value="1" <?php echo ($checklist && $checklist['fingerprint_working']) ? 'checked' : ''; ?>> شغال</label>
                    <label><input type="radio" name="fingerprint" value="0" <?php echo ($checklist && !$checklist['fingerprint_working']) ? 'checked' : ''; ?>> عطل</label>
                </div>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg text-center">
                <label class="block text-sm font-medium text-gray-700 mb-2">سماعة</label>
                <div class="flex justify-center gap-4">
                    <label><input type="radio" name="audio" value="1" <?php echo ($checklist && $checklist['audio_working']) ? 'checked' : ''; ?>> شغال</label>
                    <label><input type="radio" name="audio" value="0" <?php echo ($checklist && !$checklist['audio_working']) ? 'checked' : ''; ?>> عطل</label>
                </div>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg text-center">
                <label class="block text-sm font-medium text-gray-700 mb-2">شحن</label>
                <div class="flex justify-center gap-4">
                    <label><input type="radio" name="charging" value="1" <?php echo ($checklist && $checklist['charging_working']) ? 'checked' : ''; ?>> شغال</label>
                    <label><input type="radio" name="charging" value="0" <?php echo ($checklist && !$checklist['charging_working']) ? 'checked' : ''; ?>> عطل</label>
                </div>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg text-center">
                <label class="block text-sm font-medium text-gray-700 mb-2">حالة الشاشة</label>
                <select name="screen_condition" class="w-full border rounded-lg px-2 py-1 text-sm">
                    <option value="good" <?php echo ($checklist && $checklist['screen_condition'] == 'good') ? 'selected' : ''; ?>>جيدة</option>
                    <option value="scratched" <?php echo ($checklist && $checklist['screen_condition'] == 'scratched') ? 'selected' : ''; ?>>خدوش</option>
                    <option value="cracked" <?php echo ($checklist && $checklist['screen_condition'] == 'cracked') ? 'selected' : ''; ?>>مشقوقة</option>
                    <option value="broken" <?php echo ($checklist && $checklist['screen_condition'] == 'broken') ? 'selected' : ''; ?>>مكسورة</option>
                    <option value="not_checked" <?php echo ($checklist && $checklist['screen_condition'] == 'not_checked') ? 'selected' : ''; ?>>لم يتم الفحص</option>
                </select>
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">ملاحظات الفحص</label>
            <textarea name="notes" rows="3" class="w-full border rounded-lg px-3 py-2" placeholder="أي ملاحظات إضافية..."><?php echo $checklist ? $checklist['check_notes'] : ''; ?></textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                <i class="fas fa-save"></i> حفظ الفحص
            </button>
            <a href="/devices/<?php echo $device['id']; ?>" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg transition">
                إلغاء
            </a>
        </div>
    </form>
</div>