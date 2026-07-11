<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-box text-blue-500 ml-2"></i> إضافة صنف جديد</h1>
        <a href="/inventory" class="text-blue-600 hover:text-blue-800 text-sm"><i class="fas fa-arrow-left ml-1"></i> العودة</a>
    </div>

    <form method="POST" action="/inventory/store">
        <div class="grid grid-cols-2 gap-4">
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">اسم الصنف <span class="text-red-500">*</span></label>
                <input type="text" name="name" placeholder="مثل: شاشة Samsung A51" class="w-full border rounded-lg px-3 py-2" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">الفئة <span class="text-red-500">*</span></label>
                <select name="category" class="w-full border rounded-lg px-3 py-2" required>
                    <option value="">اختر الفئة...</option>
                    <option value="شاشة">شاشة</option>
                    <option value="بطارية">بطارية</option>
                    <option value="سماعة">سماعة</option>
                    <option value="كابل">كابل</option>
                    <option value="شاحن">شاحن</option>
                    <option value="IC">IC</option>
                    <option value="بوردة">بوردة</option>
                    <option value="زجاج">زجاج</option>
                    <option value="إكسسوار">إكسسوار</option>
                    <option value="أخرى">أخرى</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">سعر الشراء <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" name="purchase_price" placeholder="0.00" class="w-full border rounded-lg px-3 py-2" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">سعر البيع <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" name="selling_price" placeholder="0.00" class="w-full border rounded-lg px-3 py-2" required>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">الكمية المستلمة <span class="text-red-500">*</span></label>
                <input type="number" name="quantity" placeholder="0" class="w-full border rounded-lg px-3 py-2" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">حد التنبيه <span class="text-red-500">*</span></label>
                <input type="number" name="alert_quantity" placeholder="5" class="w-full border rounded-lg px-3 py-2" required>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">الباركود</label>
                <input type="text" name="barcode" placeholder="رقم الباركود" class="w-full border rounded-lg px-3 py-2">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">المورد</label>
                <input type="text" name="supplier_name" placeholder="اسم المورد..." class="w-full border rounded-lg px-3 py-2">
            </div>
        </div>

        <div class="mb-3">
            <label class="block text-sm font-medium text-gray-700">موقع التخزين</label>
            <input type="text" name="location" placeholder="رف 2 - باب 3" class="w-full border rounded-lg px-3 py-2">
        </div>

        <div class="bg-blue-50 p-4 rounded-lg border-r-4 border-blue-500 mb-4">
            <h3 class="font-semibold text-blue-800 mb-3"><i class="fas fa-file-invoice ml-2"></i> فاتورة استلام البضاعة</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700">رقم الفاتورة <span class="text-red-500">*</span></label>
                    <input type="text" name="invoice_number" placeholder="INV-2026-001" class="w-full border rounded-lg px-3 py-2" required>
                </div>
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700">تاريخ الفاتورة <span class="text-red-500">*</span></label>
                    <input type="date" name="invoice_date" value="<?php echo date('Y-m-d'); ?>" class="w-full border rounded-lg px-3 py-2" required>
                </div>
            </div>
            <div class="mb-2">
                <label class="block text-sm font-medium text-gray-700">الكمية في الفاتورة <span class="text-red-500">*</span></label>
                <input type="number" name="invoice_quantity" placeholder="نفس الكمية المستلمة أو أقل" class="w-full border rounded-lg px-3 py-2" required>
                <p class="text-xs text-red-400 mt-1">⚠️ ان كانت الكمية التي في الفاتورة أقل من المستلمة، سيظهر تنبيه للمدير فوراً</p>
            </div>
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition">
            <i class="fas fa-save ml-2"></i> إضافة الصنف
        </button>
    </form>
</div>