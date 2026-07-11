<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-edit text-amber-500 ml-2"></i> تعديل صنف: <?php echo htmlspecialchars($item['name']); ?></h1>
        <a href="/inventory" class="text-blue-600 hover:text-blue-800 text-sm"><i class="fas fa-arrow-left ml-1"></i> العودة</a>
    </div>

    <form method="POST" action="/inventory/update">
        <input type="hidden" name="id" value="<?php echo $item['id']; ?>">

        <div class="grid grid-cols-2 gap-4">
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">الاسم <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($item['name']); ?>" class="w-full border rounded-lg px-3 py-2" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">الفئة <span class="text-red-500">*</span></label>
                <select name="category" class="w-full border rounded-lg px-3 py-2" required>
                    <option value="شاشة" <?php echo ($item['category'] == 'شاشة') ? 'selected' : ''; ?>>شاشة</option>
                    <option value="بطارية" <?php echo ($item['category'] == 'بطارية') ? 'selected' : ''; ?>>بطارية</option>
                    <option value="سماعة" <?php echo ($item['category'] == 'سماعة') ? 'selected' : ''; ?>>سماعة</option>
                    <option value="كابل" <?php echo ($item['category'] == 'كابل') ? 'selected' : ''; ?>>كابل</option>
                    <option value="شاحن" <?php echo ($item['category'] == 'شاحن') ? 'selected' : ''; ?>>شاحن</option>
                    <option value="IC" <?php echo ($item['category'] == 'IC') ? 'selected' : ''; ?>>IC</option>
                    <option value="بوردة" <?php echo ($item['category'] == 'بوردة') ? 'selected' : ''; ?>>بوردة</option>
                    <option value="زجاج" <?php echo ($item['category'] == 'زجاج') ? 'selected' : ''; ?>>زجاج</option>
                    <option value="إكسسوار" <?php echo ($item['category'] == 'إكسسوار') ? 'selected' : ''; ?>>إكسسوار</option>
                    <option value="أخرى" <?php echo ($item['category'] == 'أخرى') ? 'selected' : ''; ?>>أخرى</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">سعر الشراء <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" name="purchase_price" value="<?php echo $item['purchase_price']; ?>" class="w-full border rounded-lg px-3 py-2" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">سعر البيع <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" name="selling_price" value="<?php echo $item['selling_price']; ?>" class="w-full border rounded-lg px-3 py-2" required>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">الكمية <span class="text-red-500">*</span></label>
                <input type="number" name="quantity" value="<?php echo $item['current_quantity']; ?>" class="w-full border rounded-lg px-3 py-2" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">حد التنبيه <span class="text-red-500">*</span></label>
                <input type="number" name="alert_quantity" value="<?php echo $item['alert_quantity']; ?>" class="w-full border rounded-lg px-3 py-2" required>
            </div>
        </div>

        <!-- ===== الموقع والباركود ===== -->
        <div class="grid grid-cols-2 gap-4">
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">موقع التخزين</label>
                <input type="text" name="location" value="<?php echo htmlspecialchars($item['location'] ?? ''); ?>" class="w-full border rounded-lg px-3 py-2" placeholder="مثل: رف 2 - باب 3">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">الباركود</label>
                <input type="text" name="barcode" value="<?php echo htmlspecialchars($item['barcode'] ?? ''); ?>" class="w-full border rounded-lg px-3 py-2" placeholder="رقم الباركود">
            </div>
        </div>

        <!-- ===== المورد (يدوي - مربع نص) ===== -->
        <div class="mb-3">
            <label class="block text-sm font-medium text-gray-700">المورد</label>
            <input type="text" name="supplier_name" value="<?php echo htmlspecialchars($item['supplier_name'] ?? ''); ?>" 
                   class="w-full border rounded-lg px-3 py-2" placeholder="اكتب اسم المورد يدوياً...">
            <p class="text-xs text-gray-400 mt-1">💡 يمكنك كتابة اسم المورد الجديد أو تعديل الاسم الحالي</p>
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition">
            <i class="fas fa-save ml-2"></i> تحديث الصنف
        </button>
    </form>
</div>