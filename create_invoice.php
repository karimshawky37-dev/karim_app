<div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-file-invoice text-blue-500"></i> فاتورة استلام بضاعة</h1>
        <a href="/inventory" class="text-blue-600 hover:text-blue-800 text-sm"><i class="fas fa-arrow-left"></i> العودة</a>
    </div>

    <form method="POST" action="/inventory/store-invoice" id="invoiceForm">
        <div class="grid grid-cols-3 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">المورد</label>
                <input type="text" name="supplier_name" placeholder="اسم المورد..." class="w-full border rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">رقم الفاتورة <span class="text-red-500">*</span></label>
                <input type="text" name="invoice_number" placeholder="INV-2026-001" class="w-full border rounded-lg px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">تاريخ الفاتورة <span class="text-red-500">*</span></label>
                <input type="date" name="invoice_date" value="<?php echo date('Y-m-d'); ?>" class="w-full border rounded-lg px-3 py-2" required>
            </div>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg mb-4">
            <h3 class="font-semibold text-gray-700 mb-3">📦 الأصناف</h3>
            <div id="itemsContainer">
                <div class="grid grid-cols-6 gap-2 mb-2 item-row">
                    <input type="text" placeholder="الاسم" class="item-name border rounded px-2 py-1 text-sm" required>
                    <input type="text" placeholder="الفئة" class="item-category border rounded px-2 py-1 text-sm" required>
                    <input type="number" placeholder="الكمية" class="item-quantity border rounded px-2 py-1 text-sm" required>
                    <input type="number" step="0.01" placeholder="سعر الشراء" class="item-purchase border rounded px-2 py-1 text-sm" required>
                    <input type="number" step="0.01" placeholder="سعر البيع" class="item-selling border rounded px-2 py-1 text-sm" required>
                    <button type="button" onclick="removeRow(this)" class="bg-red-500 text-white px-2 py-1 rounded text-sm">✖</button>
                </div>
            </div>
            <button type="button" onclick="addRow()" class="mt-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded-lg text-sm transition">
                <i class="fas fa-plus"></i> إضافة صنف
            </button>
        </div>

        <input type="hidden" name="items_json" id="items_json">

        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg transition">
            <i class="fas fa-save"></i> حفظ الفاتورة
        </button>
    </form>
</div>

<script>
    function addRow() {
        const container = document.getElementById('itemsContainer');
        const row = document.querySelector('.item-row').cloneNode(true);
        row.querySelectorAll('input').forEach(input => input.value = '');
        container.appendChild(row);
    }

    function removeRow(btn) {
        const rows = document.querySelectorAll('.item-row');
        if (rows.length > 1) {
            btn.closest('.item-row').remove();
        } else {
            alert('يجب أن يكون هناك صنف واحد على الأقل');
        }
    }

    document.getElementById('invoiceForm').addEventListener('submit', function(e) {
        const rows = document.querySelectorAll('.item-row');
        const items = [];
        let valid = true;

        rows.forEach(row => {
            const name = row.querySelector('.item-name').value.trim();
            const category = row.querySelector('.item-category').value.trim();
            const quantity = parseInt(row.querySelector('.item-quantity').value);
            const purchase_price = parseFloat(row.querySelector('.item-purchase').value);
            const selling_price = parseFloat(row.querySelector('.item-selling').value);

            if (!name || !category || !quantity || !purchase_price || !selling_price) {
                valid = false;
                return;
            }

            items.push({
                name: name,
                category: category,
                quantity: quantity,
                purchase_price: purchase_price,
                selling_price: selling_price,
                alert_quantity: 5
            });
        });

        if (!valid || items.length === 0) {
            e.preventDefault();
            alert('⚠️ جميع الحقول مطلوبة لكل صنف');
            return;
        }

        document.getElementById('items_json').value = JSON.stringify(items);
    });
</script>