<div class="max-w-lg mx-auto bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-exchange-alt text-green-500"></i> إعدادات الورديات
        </h1>
        <a href="/" class="text-blue-600 hover:text-blue-800 text-sm"><i class="fas fa-arrow-left"></i> العودة</a>
    </div>

    <div class="bg-green-50 p-3 rounded-lg border-r-4 border-green-500 mb-4">
        <p class="text-sm text-green-700">🔄 فعّل نظام الورديات لتوزيع الموظفين على فترتين (صباحية ومسائية)</p>
    </div>

    <form method="POST" action="/settings/update-shifts">
        <div class="space-y-4">
            <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg">
                <label class="text-sm font-medium text-gray-700">🔄 تفعيل نظام الورديات</label>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="shift_enabled" value="1" <?php echo ($settings['shift_enabled'] ?? '0') == '1' ? 'checked' : ''; ?> class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>

            <div class="border-t border-gray-200 pt-4">
                <h3 class="font-semibold text-gray-700 mb-3">🌅 الوردية الصباحية</h3>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">بداية</label>
                        <input type="time" name="shift_morning_start" value="<?php echo $settings['shift_morning_start'] ?? '09:00'; ?>" class="w-full border rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">نهاية</label>
                        <input type="time" name="shift_morning_end" value="<?php echo $settings['shift_morning_end'] ?? '14:00'; ?>" class="w-full border rounded-lg px-3 py-2">
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-4">
                <h3 class="font-semibold text-gray-700 mb-3">🌙 الوردية المسائية</h3>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">بداية</label>
                        <input type="time" name="shift_evening_start" value="<?php echo $settings['shift_evening_start'] ?? '14:00'; ?>" class="w-full border rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">نهاية</label>
                        <input type="time" name="shift_evening_end" value="<?php echo $settings['shift_evening_end'] ?? '20:00'; ?>" class="w-full border rounded-lg px-3 py-2">
                    </div>
                </div>
            </div>

            <p class="text-xs text-gray-400 mt-2">💡 عند تفعيل نظام الورديات، سيتم تحديد نوع الوردية تلقائياً عند تسجيل الحضور</p>
        </div>

        <button type="submit" class="w-full mt-6 bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg transition">
            <i class="fas fa-save"></i> حفظ إعدادات الورديات
        </button>
    </form>

    <div class="mt-4 text-center">
        <a href="/settings/work" class="text-sm text-blue-600 hover:underline">⏰ إعدادات العمل الأساسية ←</a>
    </div>
</div>