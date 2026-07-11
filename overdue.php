<div class="bg-white rounded-xl shadow-sm p-6 max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-red-600">⚠️ الأقساط المتأخرة</h1>
        <a href="/installments" class="text-blue-600 hover:text-blue-800 text-sm"><i class="fas fa-arrow-left ml-1"></i> العودة</a>
    </div>

    <?php if (empty($installments)): ?>
        <div class="text-center py-8">
            <i class="fas fa-check-circle text-4xl text-green-400 block mb-2"></i>
            <p class="text-green-600 text-lg font-bold">🎉 لا توجد أقساط متأخرة</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">#</th>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">العميل</th>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">الجهاز</th>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">المتبقي</th>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">أقساط متأخرة</th>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">الإجراء</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($installments as $i): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2"><?php echo $i['id']; ?></td>
                            <td class="px-4 py-2">
                                <div class="font-medium"><?php echo $i['customer_name']; ?></div>
                                <div class="text-xs text-gray-400"><?php echo $i['customer_phone']; ?></div>
                            </td>
                            <td class="px-4 py-2"><?php echo $i['device_name']; ?></td>
                            <td class="px-4 py-2 font-bold text-red-600"><?php echo number_format($i['remaining_amount'], 2); ?></td>
                            <td class="px-4 py-2">
                                <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs font-bold"><?php echo $i['overdue_count']; ?></span>
                            </td>
                            <td class="px-4 py-2">
                                <a href="/installments/show/<?php echo $i['id']; ?>" class="text-blue-600 hover:text-blue-800 text-sm">عرض</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>