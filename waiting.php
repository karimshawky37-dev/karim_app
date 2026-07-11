<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="flex justify-between items-center p-4 border-b border-gray-200">
        <h2 class="text-lg font-bold text-gray-800"><i class="fas fa-clock text-amber-500"></i> الأجهزة في انتظار قطع غيار</h2>
        <a href="/devices" class="text-blue-600 hover:text-blue-800 text-sm"><i class="fas fa-arrow-left"></i> العودة</a>
    </div>

    <?php if (empty($devices)): ?>
        <div class="p-8 text-center text-gray-400">
            <i class="fas fa-check-circle text-4xl text-green-400 block mb-2"></i>
            لا توجد أجهزة في انتظار قطع غيار
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">#</th>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">الكود</th>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">الجهاز</th>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">العميل</th>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">القطعة المطلوبة</th>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">التاريخ</th>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">الإجراء</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($devices as $d): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2"><?php echo $d['id']; ?></td>
                            <td class="px-4 py-2 font-mono text-sm"><?php echo $d['device_code']; ?></td>
                            <td class="px-4 py-2"><?php echo $d['brand'] . ' ' . $d['model']; ?></td>
                            <td class="px-4 py-2"><?php echo $d['customer_name']; ?></td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                    <?php echo $d['waiting_for_part']; ?>
                                </span>
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-400"><?php echo date('Y-m-d', strtotime($d['received_at'])); ?></td>
                            <td class="px-4 py-2">
                                <a href="/devices/<?php echo $d['id']; ?>" class="text-blue-600 hover:text-blue-800">عرض</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>