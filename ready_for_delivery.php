<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="flex justify-between items-center p-4 border-b border-gray-200">
        <h2 class="text-lg font-bold text-gray-800"><i class="fas fa-box text-green-500"></i> الأجهزة الجاهزة للتسليم</h2>
        <a href="/devices" class="text-blue-600 hover:text-blue-800 text-sm"><i class="fas fa-arrow-left"></i> العودة</a>
    </div>

    <?php if (empty($devices)): ?>
        <div class="p-8 text-center text-gray-400">
            <i class="fas fa-check-circle text-4xl text-green-400 block mb-2"></i>
            لا توجد أجهزة جاهزة للتسليم
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
                        <th class="px-4 py-2 text-right text-xs text-gray-500">تاريخ الإصلاح</th>
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
                            <td class="px-4 py-2 text-sm text-gray-400"><?php echo date('Y-m-d', strtotime($d['updated_at'])); ?></td>
                            <td class="px-4 py-2">
                                <form method="POST" action="/devices/prepare-delivery" class="inline">
                                    <input type="hidden" name="device_id" value="<?php echo $d['id']; ?>">
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition">
                                        📦 تجهيز للتسليم
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="p-4 text-center text-sm text-gray-400">
            💡 بعد تجهيز الجهاز، سيظهر في صفحة تفاصيل الجهاز زر "تسليم الجهاز للعميل"
        </div>
    <?php endif; ?>
</div>