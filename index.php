<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">📦 المخزون</h1>
        <div class="flex gap-2 flex-wrap">
            <a href="/inventory/create" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">
                <i class="fas fa-plus"></i> إضافة صنف
            </a>
            <a href="/inventory/low-stock" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm transition">
                <i class="fas fa-exclamation-triangle"></i> النواقص
            </a>
            <a href="/" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition">← الرئيسية</a>
        </div>
    </div>

    <!-- بحث -->
    <div class="bg-gray-50 p-4 rounded-lg mb-4 border border-gray-200">
        <form method="GET" action="/inventory" class="flex gap-3 items-center flex-wrap">
            <div class="relative flex-1 min-w-[250px]">
                <input type="text" name="search" value="<?php echo htmlspecialchars($searchTerm ?? ''); ?>" placeholder="🔍 ابحث باسم الصنف، الفئة، الباركود، أو المورد..." class="w-full border rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none" onkeyup="if(this.value.length >= 2 || this.value.length == 0) this.form.submit()">
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg text-sm transition"><i class="fas fa-search"></i> بحث</button>
            <a href="/inventory" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2.5 rounded-lg text-sm transition"><i class="fas fa-times"></i> إلغاء</a>
        </form>
        <?php if (!empty($searchTerm)): ?>
            <p class="text-sm text-gray-500 mt-2">نتائج البحث عن: <strong>"<?php echo htmlspecialchars($searchTerm); ?>"</strong></p>
        <?php endif; ?>
        <p class="text-xs text-gray-400 mt-1">💡 اكتب حرفين على الأقل للبحث</p>
    </div>

    <!-- إحصائيات -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-gray-50 p-3 rounded-lg border-r-4 border-blue-500"><p class="text-xs text-gray-500">إجمالي الأصناف</p><p class="text-xl font-bold text-blue-600"><?php echo count($items); ?></p></div>
        <div class="bg-gray-50 p-3 rounded-lg border-r-4 border-green-500"><p class="text-xs text-gray-500">قيمة المخزون</p><p class="text-xl font-bold text-green-600"><?php echo number_format($totalValue, 2); ?> جنيه</p></div>
        <div class="bg-gray-50 p-3 rounded-lg border-r-4 border-red-500"><p class="text-xs text-gray-500">نواقص</p><p class="text-xl font-bold text-red-600"><?php echo $lowStockCount; ?></p></div>
    </div>

    <!-- تحديث الأجهزة المنتظرة -->
    <div class="bg-amber-50 p-3 rounded-lg border-r-4 border-amber-500 mb-4">
        <form method="POST" action="/inventory/force-update-waiting" class="flex gap-2 items-center flex-wrap">
            <input type="text" name="part_name" placeholder="اسم القطعة المضافة..." class="border rounded-lg px-3 py-2 text-sm flex-1 min-w-[200px] focus:ring-2 focus:ring-amber-500 outline-none" required>
            <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg text-sm transition"><i class="fas fa-sync"></i> تحديث الأجهزة المنتظرة</button>
        </form>
        <p class="text-xs text-amber-600 mt-1">💡 لو مش جالك إشعار تلقائي، استخدم هذا الزر لتحديث الأجهزة المنتظرة لهذه القطعة</p>
    </div>

    <!-- جدول -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">#</th>
                    <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">الاسم</th>
                    <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">الفئة</th>
                    <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">الكمية</th>
                    <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">سعر الشراء</th>
                    <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">سعر البيع</th>
                    <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">المورد</th>
                    <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">الإجراء</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($items)): ?>
                    <tr><td colspan="8" class="px-6 py-8 text-center text-gray-400">
                        <?php if (!empty($searchTerm) && strlen($searchTerm) >= 2): ?>
                            🔍 لا توجد نتائج تطابق "<strong><?php echo htmlspecialchars($searchTerm); ?></strong>"
                        <?php elseif (!empty($searchTerm) && strlen($searchTerm) < 2): ?>
                            ⚠️ اكتب حرفين على الأقل للبحث
                        <?php else: ?>
                            📭 لا توجد أصناف في المخزون
                        <?php endif; ?>
                    </td></tr>
                <?php else: ?>
                    <?php foreach ($items as $item): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3"><?php echo $item['id']; ?></td>
                            <td class="px-4 py-3 font-medium"><?php echo htmlspecialchars($item['name']); ?></td>
                            <td class="px-4 py-3 text-sm text-gray-500"><?php echo $item['category']; ?></td>
                            <td class="px-4 py-3"><span class="font-bold <?php echo $item['current_quantity'] <= $item['alert_quantity'] ? 'text-red-600' : 'text-gray-800'; ?>"><?php echo $item['current_quantity']; ?></span><?php if ($item['current_quantity'] <= $item['alert_quantity']): ?><span class="text-xs text-red-500">⚠️</span><?php endif; ?></td>
                            <td class="px-4 py-3 text-sm"><?php echo number_format($item['purchase_price'], 2); ?></td>
                            <td class="px-4 py-3 text-sm text-green-600"><?php echo number_format($item['selling_price'], 2); ?></td>
                            <td class="px-4 py-3 text-sm text-gray-500"><?php echo $item['supplier_name'] ?? '—'; ?></td>
                            <td class="px-4 py-3 flex gap-2">
                                <a href="/inventory/edit/<?php echo $item['id']; ?>" class="text-blue-600 hover:text-blue-800 text-sm" title="تعديل"><i class="fas fa-edit"></i></a>
                                <a href="/inventory/delete/<?php echo $item['id']; ?>" class="text-red-500 hover:text-red-700 text-sm transition" onclick="return confirm('⚠️ هل أنت متأكد من حذف هذا الصنف؟')" title="حذف"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>