<div class="max-w-lg mx-auto bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-clock text-blue-500"></i> إعدادات الحضور
        </h1>
        <a href="/" class="text-blue-600 hover:text-blue-800 text-sm">← العودة</a>
    </div>

    <form method="POST" action="/settings/update-attendance">
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">وقت بدء الدوام الرسمي</label>
            <input type="time" name="work_start_time" value="<?php echo $workStartTime; ?>" 
                   class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            <p class="text-xs text-gray-400 mt-1">التوقيت الذي يعتبر الموظف بعده "متأخراً"</p>
        </div>
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition">
            <i class="fas fa-save"></i> حفظ الإعدادات
        </button>
    </form>

    <div class="mt-4 p-3 bg-yellow-50 border-r-4 border-yellow-400 rounded">
        <p class="text-sm text-yellow-700">
            <i class="fas fa-info-circle"></i>
            التعديل سيؤثر على تسجيل الحضور من الآن فصاعداً فقط.
        </p>
    </div>
</div>