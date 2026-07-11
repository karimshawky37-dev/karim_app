<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-red-600"><i class="fas fa-exclamation-triangle ml-2"></i> نواقص المخزون</h1>
        <a href="/inventory" class="text-blue-600 hover:text-blue-800 text-sm"><i class="fas fa-arrow-left ml-1"></i> العودة</a>
    </div>

    <?php if (empty($items)): ?>
        <div class="text-center py-8">
            <i class="fas fa-check-circle text-4xl text-green-400 block mb-2"></i>
            <p class="text-green-600 text-lg font-bold">🎉 مفيش نواقص! كل الأصناف متوفرة</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">#</th>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">الاسم</th>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">الفئة</th>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">الكمية</th>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">حد التنبيه</th>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">الإجراء</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-2"><?php echo $item['id']; ?></td>
                            <td class="px-4 py-2 font-medium"><?php echo htmlspecialchars($item['name']); ?></td>
                            <td class="px-4 py-2 text-sm text-gray-500"><?php echo $item['category']; ?></td>
                            <td class="px-4 py-2 font-bold text-red-600"><?php echo $item['current_quantity']; ?></td>
                            <td class="px-4 py-2"><?php echo $item['alert_quantity']; ?></td>
                            <td class="px-4 py-2">
                                <a href="/inventory/edit/<?php echo $item['id']; ?>" class="text-blue-600 hover:text-blue-800 text-sm">
                                    <i class="fas fa-edit"></i> تعديل
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>