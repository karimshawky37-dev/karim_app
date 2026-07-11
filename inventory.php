<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-boxes text-blue-500"></i> تقرير حركة المخزون</h1>
        <a href="/reports" class="text-blue-600 hover:text-blue-800 text-sm"><i class="fas fa-arrow-left"></i> العودة</a>
        <button onclick="window.print()" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm transition">
    <i class="fas fa-print"></i> طباعة
</button>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">الاسم</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">الفئة</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">الكمية الحالية</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">حد التنبيه</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">وارد</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">منصرف (مبيعات)</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">مستخدم في صيانة</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">الحالة</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data)): ?>
                    <tr><td colspan="8" class="px-4 py-4 text-center text-gray-400">لا توجد بيانات</td></tr>
                <?php else: ?>
                    <?php foreach ($data as $item): ?>
                        <?php $isLow = $item['current_quantity'] <= $item['alert_quantity']; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 font-medium"><?php echo $item['name']; ?></td>
                            <td class="px-4 py-2 text-sm text-gray-500"><?php echo $item['category']; ?></td>
                            <td class="px-4 py-2 font-bold <?php echo $isLow ? 'text-red-600' : 'text-gray-800'; ?>"><?php echo $item['current_quantity']; ?></td>
                            <td class="px-4 py-2"><?php echo $item['alert_quantity']; ?></td>
                            <td class="px-4 py-2 text-green-600"><?php echo $item['total_in']; ?></td>
                            <td class="px-4 py-2 text-red-600"><?php echo $item['total_out']; ?></td>
                            <td class="px-4 py-2 text-amber-600"><?php echo $item['total_repair']; ?></td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 rounded-full text-xs font-medium <?php echo $isLow ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'; ?>">
                                    <?php echo $isLow ? '⚠️ منخفض' : '✅ متوفر'; ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>