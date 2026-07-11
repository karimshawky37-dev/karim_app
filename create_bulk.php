<div class="max-w-6xl mx-auto bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-boxes text-blue-500 ml-2"></i> إضافة صنف جديد</h1>
        <a href="/inventory" class="text-blue-600 hover:text-blue-800 text-sm"><i class="fas fa-arrow-left ml-1"></i> العودة</a>
    </div>

    <div class="bg-blue-50 p-3 rounded-lg border-r-4 border-blue-500 mb-4">
        <i class="fas fa-info-circle text-blue-500"></i>
        <span class="text-sm">أدخل بيانات الفاتورة ثم أضف الأصناف في الجدول. كل الأصناف هتتحفظ دفعة واحدة.</span>
    </div>

    <form method="POST" action="/inventory/store" id="bulkForm">

        <!-- ========================================================== -->
        <!-- 📋 بيانات الفاتورة الأساسية -->
        <!-- ========================================================== -->
        <div class="bg-gray-50 p-4 rounded-lg mb-4 border border-gray-200">
            <h3 class="font-semibold text-gray-700 mb-3"><i class="fas fa-file-invoice text-blue-500 ml-1"></i> بيانات الفاتورة</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">رقم الفاتورة <span class="text-red-500">*</span></label>
                    <input type="text" name="invoice_number" id="invoice_number" 
                           placeholder="INV-2026-001" 
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">اسم المورد <span class="text-red-500">*</span></label>
                    <input type="text" name="supplier_name" id="supplier_name" 
                           placeholder="اسم المورد..." 
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">تاريخ الفاتورة <span class="text-red-500">*</span></label>
                    <input type="date" name="invoice_date" id="invoice_date" 
                           value="<?php echo date('Y-m-d'); ?>" 
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none" required>
                </div>
            </div>
        </div>

        <!-- ========================================================== -->
        <!-- 📦 جدول الأصناف -->
        <!-- ========================================================== -->
        <div class="border border-gray-200 rounded-lg overflow-hidden mb-4">
            <div class="bg-gray-100 px-4 py-2 flex justify-between items-center border-b">
                <h3 class="font-semibold text-gray-700"><i class="fas fa-boxes text-green-500 ml-1"></i> الأصناف</h3>
                <button type="button" onclick="addRow()" 
                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-lg text-sm transition">
                    <i class="fas fa-plus"></i> إضافة صف
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm" id="itemsTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-2 py-2 text-right text-xs text-gray-500 font-medium w-1">#</th>
                            <th class="px-2 py-2 text-right text-xs text-gray-500 font-medium">اسم الصنف *</th>
                            <th class="px-2 py-2 text-right text-xs text-gray-500 font-medium">الفئة *</th>
                            <th class="px-2 py-2 text-center text-xs text-gray-500 font-medium">الكمية</th>
                            <th class="px-2 py-2 text-center text-xs text-gray-500 font-medium">سعر الشراء</th>
                            <th class="px-2 py-2 text-center text-xs text-gray-500 font-medium">سعر البيع</th>
                            <th class="px-2 py-2 text-center text-xs text-gray-500 font-medium">حد التنبيه</th>
                            <th class="px-2 py-2 text-center text-xs text-gray-500 font-medium">الموقع</th>
                            <th class="px-2 py-2 text-center text-xs text-gray-500 font-medium">الباركود</th>
                            <th class="px-2 py-2 text-center text-xs text-gray-500 font-medium w-1">حذف</th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody">
                        <tr class="item-row hover:bg-gray-50">
                            <td class="px-2 py-2 text-center text-gray-400 row-number">1</td>
                            <td class="px-2 py-2">
                                <input type="text" name="items[0][name]" placeholder="اسم الصنف" 
                                       class="w-full border rounded-lg px-2 py-1 text-sm focus:ring-2 focus:ring-blue-500 outline-none" required>
                            </td>
                            <td class="px-2 py-2">
                                <select name="items[0][category]" class="w-full border rounded-lg px-2 py-1 text-sm focus:ring-2 focus:ring-blue-500 outline-none" required>
                                    <option value="شاشة">شاشة</option>
                                    <option value="بطارية">بطارية</option>
                                    <option value="سماعة">سماعة</option>
                                    <option value="كابل">كابل</option>
                                    <option value="شاحن">شاحن</option>
                                    <option value="IC">IC</option>
                                    <option value="بوردة">بوردة</option>
                                    <option value="زجاج">زجاج</option>
                                    <option value="إكسسوار">إكسسوار</option>
                                    <option value="أخرى" selected>أخرى</option>
                                </select>
                            </td>
                            <td class="px-2 py-2">
                                <input type="number" name="items[0][quantity]" value="1" min="1" 
                                       class="w-16 border rounded-lg px-2 py-1 text-sm text-center focus:ring-2 focus:ring-blue-500 outline-none">
                            </td>
                            <td class="px-2 py-2">
                                <input type="number" step="0.01" name="items[0][purchase_price]" placeholder="0.00" 
                                       class="w-24 border rounded-lg px-2 py-1 text-sm text-center focus:ring-2 focus:ring-blue-500 outline-none" required>
                            </td>
                            <td class="px-2 py-2">
                                <input type="number" step="0.01" name="items[0][selling_price]" placeholder="0.00" 
                                       class="w-24 border rounded-lg px-2 py-1 text-sm text-center focus:ring-2 focus:ring-blue-500 outline-none" required>
                            </td>
                            <td class="px-2 py-2">
                                <input type="number" name="items[0][alert_quantity]" value="5" min="1" 
                                       class="w-16 border rounded-lg px-2 py-1 text-sm text-center focus:ring-2 focus:ring-blue-500 outline-none">
                            </td>
                            <td class="px-2 py-2">
                                <input type="text" name="items[0][location]" placeholder="رف 2 - باب 3" 
                                       class="w-24 border rounded-lg px-2 py-1 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                            </td>
                            <td class="px-2 py-2">
                                <input type="text" name="items[0][barcode]" placeholder="باركود" 
                                       class="w-24 border rounded-lg px-2 py-1 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                            </td>
                            <td class="px-2 py-2 text-center">
                                <button type="button" onclick="removeRow(this)" 
                                        class="text-red-400 hover:text-red-600 transition disabled:opacity-50" title="حذف الصف">
                                    <i class="fas fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ========================================================== -->
        <!-- 📊 ملخص الأصناف -->
        <!-- ========================================================== -->
        <div class="bg-gray-50 p-3 rounded-lg mb-4 flex justify-between items-center">
            <span class="text-sm text-gray-600">عدد الأصناف: <span id="itemCount" class="font-bold text-blue-600">1</span></span>
            <span class="text-sm text-gray-600">إجمالي الكمية: <span id="totalQuantity" class="font-bold text-green-600">0</span></span>
        </div>

        <!-- ========================================================== -->
        <!-- 🚀 أزرار الإجراء -->
        <!-- ========================================================== -->
        <div class="flex gap-3">
            <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg text-sm font-semibold transition">
                <i class="fas fa-save ml-1"></i> حفظ الفاتورة والأصناف
            </button>
            <button type="reset" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2.5 rounded-lg text-sm transition">
                <i class="fas fa-undo ml-1"></i> إعادة تعيين
            </button>
        </div>
    </form>
</div>

<!-- ========================================================== -->
<!-- 🧠 JavaScript -->
<!-- ========================================================== -->
<script>
    let rowCount = 1;

    function addRow() {
        const tbody = document.getElementById('itemsBody');
        const newRow = document.createElement('tr');
        newRow.className = 'item-row hover:bg-gray-50';
        newRow.innerHTML = `
            <td class="px-2 py-2 text-center text-gray-400 row-number">${rowCount + 1}</td>
            <td class="px-2 py-2">
                <input type="text" name="items[${rowCount}][name]" placeholder="اسم الصنف" 
                       class="w-full border rounded-lg px-2 py-1 text-sm focus:ring-2 focus:ring-blue-500 outline-none" required>
            </td>
            <td class="px-2 py-2">
                <select name="items[${rowCount}][category]" class="w-full border rounded-lg px-2 py-1 text-sm focus:ring-2 focus:ring-blue-500 outline-none" required>
                    <option value="شاشة">شاشة</option>
                    <option value="بطارية">بطارية</option>
                    <option value="سماعة">سماعة</option>
                    <option value="كابل">كابل</option>
                    <option value="شاحن">شاحن</option>
                    <option value="IC">IC</option>
                    <option value="بوردة">بوردة</option>
                    <option value="زجاج">زجاج</option>
                    <option value="إكسسوار">إكسسوار</option>
                    <option value="أخرى" selected>أخرى</option>
                </select>
            </td>
            <td class="px-2 py-2">
                <input type="number" name="items[${rowCount}][quantity]" value="1" min="1" 
                       class="w-16 border rounded-lg px-2 py-1 text-sm text-center focus:ring-2 focus:ring-blue-500 outline-none">
            </td>
            <td class="px-2 py-2">
                <input type="number" step="0.01" name="items[${rowCount}][purchase_price]" placeholder="0.00" 
                       class="w-24 border rounded-lg px-2 py-1 text-sm text-center focus:ring-2 focus:ring-blue-500 outline-none" required>
            </td>
            <td class="px-2 py-2">
                <input type="number" step="0.01" name="items[${rowCount}][selling_price]" placeholder="0.00" 
                       class="w-24 border rounded-lg px-2 py-1 text-sm text-center focus:ring-2 focus:ring-blue-500 outline-none" required>
            </td>
            <td class="px-2 py-2">
                <input type="number" name="items[${rowCount}][alert_quantity]" value="5" min="1" 
                       class="w-16 border rounded-lg px-2 py-1 text-sm text-center focus:ring-2 focus:ring-blue-500 outline-none">
            </td>
            <td class="px-2 py-2">
                <input type="text" name="items[${rowCount}][location]" placeholder="رف 2 - باب 3" 
                       class="w-24 border rounded-lg px-2 py-1 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </td>
            <td class="px-2 py-2">
                <input type="text" name="items[${rowCount}][barcode]" placeholder="باركود" 
                       class="w-24 border rounded-lg px-2 py-1 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </td>
            <td class="px-2 py-2 text-center">
                <button type="button" onclick="removeRow(this)" 
                        class="text-red-400 hover:text-red-600 transition" title="حذف الصف">
                    <i class="fas fa-times"></i>
                </button>
            </td>
        `;
        tbody.appendChild(newRow);
        rowCount++;
        updateSummary();
    }

    function removeRow(btn) {
        const row = btn.closest('tr');
        const tbody = document.getElementById('itemsBody');
        if (tbody.querySelectorAll('.item-row').length <= 1) {
            alert('⚠️ لابد من وجود صف واحد على الأقل.');
            return;
        }
        row.remove();
        updateRowNumbers();
        updateSummary();
    }

    function updateRowNumbers() {
        const rows = document.querySelectorAll('.item-row');
        rows.forEach((row, index) => {
            const numCell = row.querySelector('.row-number');
            if (numCell) numCell.textContent = index + 1;
        });
    }

    function updateSummary() {
        const rows = document.querySelectorAll('.item-row');
        document.getElementById('itemCount').textContent = rows.length;

        let totalQty = 0;
        rows.forEach(row => {
            const qtyInput = row.querySelector('[name*="[quantity]"]');
            if (qtyInput) totalQty += parseInt(qtyInput.value) || 0;
        });
        document.getElementById('totalQuantity').textContent = totalQty;
    }

    document.addEventListener('input', function(e) {
        if (e.target.name && e.target.name.includes('[quantity]')) {
            updateSummary();
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        updateRowNumbers();
        updateSummary();
    });
</script>