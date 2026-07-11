<div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">📥 استلام جهاز جديد (متطور)</h1>
    
    <form method="POST" action="/devices/store" id="deviceForm">
        <!-- خطوة 1: بيانات العميل -->
        <div class="bg-blue-50 p-4 rounded-lg mb-4">
            <h3 class="font-semibold text-blue-800 mb-3">👤 بيانات العميل</h3>
            <div class="grid grid-cols-2 gap-4">
                <input type="text" name="customer_name" placeholder="اسم العميل" class="border rounded-lg px-3 py-2" required>
                <input type="text" name="customer_phone" placeholder="رقم الهاتف" class="border rounded-lg px-3 py-2" required>
            </div>
        </div>

        <!-- خطوة 2: بيانات الجهاز -->
        <div class="bg-gray-50 p-4 rounded-lg mb-4">
            <h3 class="font-semibold text-gray-800 mb-3">📱 بيانات الجهاز</h3>
            <div class="grid grid-cols-3 gap-4">
                <input type="text" name="brand" placeholder="الشركة" class="border rounded-lg px-3 py-2" required>
                <input type="text" name="model" placeholder="الموديل" class="border rounded-lg px-3 py-2" required>
                <input type="text" name="color" placeholder="اللون" class="border rounded-lg px-3 py-2">
                <input type="text" name="storage" placeholder="السعة (مثل 128GB)" class="border rounded-lg px-3 py-2">
                <input type="text" name="imei1" placeholder="IMEI 1" class="border rounded-lg px-3 py-2">
                <input type="text" name="imei2" placeholder="IMEI 2" class="border rounded-lg px-3 py-2">
            </div>
            <div class="mt-3">
                <textarea name="issue" rows="3" placeholder="العطل المبلغ عنه بالتفصيل..." class="w-full border rounded-lg px-3 py-2" required></textarea>
            </div>
        </div>

        <!-- خطوة 3: الفحص المبدئي -->
        <div class="bg-green-50 p-4 rounded-lg mb-4">
            <h3 class="font-semibold text-green-800 mb-3">🔍 الفحص المبدئي</h3>
            <div class="grid grid-cols-4 gap-2">
                <label><input type="checkbox" name="checklist[wifi]" value="1"> واي فاي</label>
                <label><input type="checkbox" name="checklist[bluetooth]" value="1"> بلوتوث</label>
                <label><input type="checkbox" name="checklist[network]" value="1"> شبكة</label>
                <label><input type="checkbox" name="checklist[camera]" value="1"> كاميرا</label>
                <label><input type="checkbox" name="checklist[fingerprint]" value="1"> بصمة</label>
                <label><input type="checkbox" name="checklist[audio]" value="1"> صوت</label>
                <label><input type="checkbox" name="checklist[charging]" value="1"> شحن</label>
                <select name="checklist[screen]" class="border rounded px-2 py-1 text-sm">
                    <option value="good">شاشة سليمة</option>
                    <option value="scratched">مخدوشة</option>
                    <option value="cracked">مشرخة</option>
                    <option value="broken">مكسورة</option>
                </select>
            </div>
            <div class="mt-2">
                <input type="text" name="checklist[notes]" placeholder="ملاحظات الفحص..." class="w-full border rounded-lg px-3 py-2">
            </div>
        </div>

        <!-- خطوة 4: توزيع على فني -->
        <div class="bg-amber-50 p-4 rounded-lg mb-4">
            <h3 class="font-semibold text-amber-800 mb-3">🔧 توزيع الجهاز</h3>
            <select name="assigned_technician_id" class="w-full border rounded-lg px-3 py-2" required>
                <option value="">-- اختر الفني المسؤول --</option>
                <?php foreach ($technicians as $t): ?>
                    <option value="<?php echo $t['id']; ?>"><?php echo $t['full_name']; ?></option>
                <?php endforeach; ?>
            </select>
            <div class="mt-2">
                <input type="text" name="waiting_for_part" placeholder="قطعة غيار مطلوبة (اختياري)" class="w-full border rounded-lg px-3 py-2">
            </div>
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg text-lg transition">
            ✅ استلام الجهاز
        </button>
    </form>
</div>