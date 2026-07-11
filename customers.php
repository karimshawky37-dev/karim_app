<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-users text-blue-500"></i> تقرير العملاء المتكررين</h1>
        <a href="/reports" class="text-blue-600 hover:text-blue-800 text-sm"><i class="fas fa-arrow-left"></i> العودة</a>
        <button onclick="window.print()" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm transition">
    <i class="fas fa-print"></i> طباعة
</button>
    </div>

    <!-- فلتر التاريخ -->
    <form method="GET" action="/reports/customers" class="flex flex-wrap gap-3 items-end mb-6 bg-gray-50 p-4 rounded-lg">
        <div>
            <label class="block text-xs text-gray-500">من تاريخ</label>
            <input type="date" name="start_date" value="<?php echo $start_date; ?>" class="border rounded-lg px-3 py-1.5 text-sm">
        </div>
        <div>
            <label class="block text-xs text-gray-500">إلى تاريخ</label>
            <input type="date" name="end_date" value="<?php echo $end_date; ?>" class="border rounded-lg px-3 py-1.5 text-sm">
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded-lg text-sm transition">
            <i class="fas fa-search"></i> عرض
        </button>
    </form>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">#</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">العميل</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">الهاتف</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">عدد الأجهزة</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">عدد الطلبات</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">إجمالي المشتريات</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">آخر طلب</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">أيام بدون طلب</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data)): ?>
                    <tr><td colspan="8" class="px-4 py-4 text-center text-gray-400">لا يوجد عملاء متكررون في هذه الفترة</td></tr>
                <?php else: ?>
                    <?php $i = 1; foreach ($data as $c): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2"><?php echo $i++; ?></td>
                            <td class="px-4 py-2 font-medium"><?php echo $c['full_name'] ?? '—'; ?></td>
                            <td class="px-4 py-2"><?php echo $c['phone'] ?? '—'; ?></td>
                            <td class="px-4 py-2 text-center"><?php echo $c['devices_count']; ?></td>
                            <td class="px-4 py-2 text-center font-bold text-blue-600"><?php echo $c['orders_count']; ?></td>
                            <td class="px-4 py-2 font-bold text-green-600"><?php echo number_format($c['total_spent'], 2); ?></td>
                            <td class="px-4 py-2 text-sm text-gray-500"><?php echo $c['last_order_date'] ? date('Y-m-d', strtotime($c['last_order_date'])) : '—'; ?></td>
                            <td class="px-4 py-2 text-center <?php echo ($c['days_since_last_order'] ?? 999) > 30 ? 'text-red-600 font-bold' : 'text-gray-500'; ?>">
                                <?php echo $c['days_since_last_order'] ?? '—'; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>