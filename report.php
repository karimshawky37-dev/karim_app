<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-file-alt text-blue-500"></i> تقرير الحضور</h1>
        <div class="flex gap-2">
            <a href="/attendance/export-csv?start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>" 
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition">
                <i class="fas fa-file-csv"></i> تصدير CSV
            </a>
            <a href="/attendance" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition">← العودة</a>
        </div>
    </div>

    <!-- فلتر التاريخ -->
    <form method="GET" action="/attendance/report" class="flex flex-wrap gap-3 items-end mb-6 bg-gray-50 p-4 rounded-lg">
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
                    <th class="px-4 py-2 text-right text-xs text-gray-500">الموظف</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">الدور</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">أيام العمل</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">حاضر</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">متأخر</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">غائب</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">نصف يوم</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">إجازة</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">إجمالي ساعات</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">متوسط ساعات</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($report)): ?>
                    <tr><td colspan="11" class="px-4 py-8 text-center text-gray-400">لا توجد بيانات</td></tr>
                <?php else: ?>
                    <?php $i = 1; foreach ($report as $r): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2"><?php echo $i++; ?></td>
                            <td class="px-4 py-2 font-medium"><?php echo $r['full_name']; ?></td>
                            <td class="px-4 py-2 text-sm text-gray-500"><?php echo $r['role']; ?></td>
                            <td class="px-4 py-2 text-center"><?php echo $r['days_worked'] ?? 0; ?></td>
                            <td class="px-4 py-2 text-center text-green-600"><?php echo $r['present_days'] ?? 0; ?></td>
                            <td class="px-4 py-2 text-center text-amber-600"><?php echo $r['late_days'] ?? 0; ?></td>
                            <td class="px-4 py-2 text-center text-red-600"><?php echo $r['absent_days'] ?? 0; ?></td>
                            <td class="px-4 py-2 text-center text-purple-600"><?php echo $r['half_days'] ?? 0; ?></td>
                            <td class="px-4 py-2 text-center text-blue-600"><?php echo $r['holiday_days'] ?? 0; ?></td>
                            <td class="px-4 py-2 text-center font-bold"><?php echo number_format($r['total_hours'] ?? 0, 2); ?></td>
                            <td class="px-4 py-2 text-center"><?php echo number_format($r['avg_hours'] ?? 0, 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>