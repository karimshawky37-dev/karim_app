<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-user-cog text-purple-500"></i> تقرير أداء الفنيين</h1>
        <a href="/reports" class="text-blue-600 hover:text-blue-800 text-sm"><i class="fas fa-arrow-left"></i> العودة</a>
        <button onclick="window.print()" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm transition">
    <i class="fas fa-print"></i> طباعة
</button>
    </div>

    <!-- فلتر التاريخ -->
    <form method="GET" action="/reports/technicians" class="flex flex-wrap gap-3 items-end mb-6 bg-gray-50 p-4 rounded-lg">
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
                    <th class="px-4 py-2 text-right text-xs text-gray-500">اسم الفني</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">إجمالي المهام</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">مكتملة</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">معلقة</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">متوسط الوقت (ساعات)</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">أجهزة حالية</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">نسبة الإنجاز</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data)): ?>
                    <tr><td colspan="8" class="px-4 py-4 text-center text-gray-400">لا توجد بيانات</td></tr>
                <?php else: ?>
                    <?php $i = 1; foreach ($data as $t): ?>
                        <?php 
                        $total = $t['total_jobs'] + $t['pending_jobs'];
                        $percentage = $total > 0 ? round(($t['completed_jobs'] / $total) * 100) : 0;
                        ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2"><?php echo $i++; ?></td>
                            <td class="px-4 py-2 font-medium"><?php echo $t['full_name']; ?></td>
                            <td class="px-4 py-2 text-center"><?php echo $total; ?></td>
                            <td class="px-4 py-2 text-center text-green-600"><?php echo $t['completed_jobs']; ?></td>
                            <td class="px-4 py-2 text-center text-amber-600"><?php echo $t['pending_jobs']; ?></td>
                            <td class="px-4 py-2 text-center"><?php echo $t['avg_hours'] ? number_format($t['avg_hours'], 1) : '—'; ?></td>
                            <td class="px-4 py-2 text-center text-blue-600"><?php echo $t['current_devices']; ?></td>
                            <td class="px-4 py-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-600 h-2 rounded-full" style="width: <?php echo $percentage; ?>%"></div>
                                    </div>
                                    <span class="text-xs font-medium"><?php echo $percentage; ?>%</span>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>