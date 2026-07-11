<div class="max-w-lg mx-auto bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-clock text-blue-500"></i> إعدادات العمل
        </h1>
        <a href="/" class="text-blue-600 hover:text-blue-800 text-sm"><i class="fas fa-arrow-left"></i> العودة</a>
    </div>

    <div class="bg-blue-50 p-3 rounded-lg border-r-4 border-blue-500 mb-4">
        <p class="text-sm text-blue-700">⚙️ هذه الإعدادات تتحكم في وقت بداية ونهاية العمل اليومي</p>
    </div>

    <form method="POST" action="/settings/update-work">
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">⏰ وقت بداية العمل</label>
                <input type="time" name="work_start_time" value="<?php echo $settings['work_start_time'] ?? '09:00'; ?>" class="w-full border rounded-lg px-3 py-2">
                <p class="text-xs text-gray-400 mt-1">الوقت الذي يبدأ فيه العمل رسمياً</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">⏰ وقت نهاية العمل</label>
                <input type="time" name="work_end_time" value="<?php echo $settings['work_end_time'] ?? '18:00'; ?>" class="w-full border rounded-lg px-3 py-2">
                <p class="text-xs text-gray-400 mt-1">الوقت الذي ينتهي فيه العمل رسمياً</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">📊 عدد ساعات العمل اليومية</label>
                <input type="number" step="0.5" name="work_hours_per_day" value="<?php echo $settings['work_hours_per_day'] ?? '8'; ?>" class="w-full border rounded-lg px-3 py-2">
                <p class="text-xs text-gray-400 mt-1">عدد الساعات المطلوبة يومياً</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">⏳ دقائق السماح (تأخير)</label>
                <input type="number" name="late_grace_minutes" value="<?php echo $settings['late_grace_minutes'] ?? '15'; ?>" class="w-full border rounded-lg px-3 py-2">
                <p class="text-xs text-gray-400 mt-1">عدد الدقائق المسموح بها بعد وقت البدء (يعتبر متأخر وليس غائب)</p>
            </div>
        </div>

        <button type="submit" class="w-full mt-6 bg-blue-<div class="max-w-lg mx-auto bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
         
        <button type="submit" class="w-full mt-6 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition">
            <i class="fas fa-save"></i> حفظ الإعدادات
        </button>
    </form>

  
    <div class="mt-4 text-center">
        <a href="/settings/shifts" class="text-sm text-blue-600 hover:underline">🔄 إعدادات الورديات →</a>
    </div>
</div>