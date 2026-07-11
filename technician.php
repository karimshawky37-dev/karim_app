<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-4 border-r-4 border-blue-500">
        <p class="text-sm text-gray-500">أجهزتي</p>
        <p class="text-2xl font-bold text-blue-600"><?php echo count($myDevices); ?></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 border-r-4 border-orange-500">
        <p class="text-sm text-gray-500">تحت الصيانة (عام)</p>
        <p class="text-2xl font-bold text-orange-600"><?php echo $inProgress; ?></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 border-r-4 border-cyan-500">
        <p class="text-sm text-gray-500">أجهزة اليوم</p>
        <p class="text-2xl font-bold text-cyan-600"><?php echo $todayDevices; ?></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 border-r-4 border-purple-500">
        <p class="text-sm text-gray-500">إجمالي الأجهزة</p>
        <p class="text-2xl font-bold text-purple-600"><?php echo $devicesCount; ?></p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
        <h3 class="font-semibold text-gray-700">أجهزتي الحالية</h3>
        <a href="/technician-dashboard" class="text-sm text-blue-600 hover:underline">عرض الكل <i class="fas fa-arrow-left"></i></a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100 text-sm">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-4 py-2 text-right text-xs text-gray-500">الكود</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">الجهاز</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">العميل</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">الحالة</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">الإجراء</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($myDevices)): ?>
                    <tr><td colspan="5" class="px-4 py-6 text-center text-gray-400">لا توجد أجهزة حالياً</td></tr>
                <?php else: ?>
                    <?php foreach ($myDevices as $d): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 font-mono text-sm"><?php echo $d['device_code']; ?></td>
                            <td class="px-4 py-2"><?php echo $d['brand'] . ' ' . $d['model']; ?></td>
                            <td class="px-4 py-2"><?php echo $d['customer_name']; ?></td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 rounded-full text-xs font-medium <?php echo $d['status_name'] == 'تم الإصلاح' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'; ?>">
                                    <?php echo $d['status_name']; ?>
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                <a href="/devices/<?php echo $d['id']; ?>" class="text-blue-600 hover:text-blue-800 text-sm">عرض</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4 text-center text-sm text-gray-400">
    <i class="fas fa-info-circle"></i>
    يمكنك متابعة أجهزتك وتحديث حالتها من خلال لوحة الفني
</div>