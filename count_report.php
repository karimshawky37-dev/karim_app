<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-file-alt text-green-500"></i> تقرير الجرد</h1>
        <div class="flex gap-2">
            <button onclick="window.print()" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm transition">
                <i class="fas fa-print"></i> طباعة
            </button>
            <a href="/inventory/count" class="text-blue-600 hover:text-blue-800 text-sm"><i class="fas fa-arrow-left"></i> العودة</a>
        </div>
    </div>

    <?php if (empty($items)): ?>
        <div class="text-center py-8 text-gray-400">
            <i class="fas fa-check-circle text-4xl text-green-400 block mb-2"></i>
            لا توجد فروقات في الجرد
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">#</th>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">الاسم</th>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">الكمية القديمة</th>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">الكمية الجديدة</th>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">الفرق</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($items as $item): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2"><?php echo $i++; ?></td>
                            <td class="px-4 py-2 font-medium"><?php echo $item['name']; ?></td>
                            <td class="px-4 py-2 text-blue-600"><?php echo $item['old']; ?></td>
                            <td class="px-4 py-2 text-green-600"><?php echo $item['new']; ?></td>
                            <td class="px-4 py-2 font-bold <?php echo $item['diff'] > 0 ? 'text-green-600' : ($item['diff'] < 0 ? 'text-red-600' : 'text-gray-400'); ?>">
                                <?php echo $item['diff'] > 0 ? '+' : ''; ?><?php echo $item['diff']; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>