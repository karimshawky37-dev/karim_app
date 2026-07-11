<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-coins text-green-500"></i> تقرير أرباح الصيانة</h1>
        <a href="/reports" class="text-blue-600 hover:text-blue-800 text-sm"><i class="fas fa-arrow-left"></i> العودة</a>
        <button onclick="window.print()" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm transition">
    <i class="fas fa-print"></i> طباعة
</button>
    </div>

    <!-- فلتر التاريخ -->
    <form method="GET" action="/reports/profit" class="flex flex-wrap gap-3 items-end mb-6 bg-gray-50 p-4 rounded-lg">
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

    <!-- جدول الأرباح -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">التاريخ</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">عدد الفواتير</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">إجمالي المبيعات</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">تكلفة قطع الغيار</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">صافي الأرباح</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data)): ?>
                    <tr><td colspan="5" class="px-4 py-4 text-center text-gray-400">لا توجد بيانات في هذه الفترة</td></tr>
                <?php else: ?>
                    <?php 
                    $totalSales = 0;
                    $totalCost = 0;
                    $totalProfit = 0;
                    foreach ($data as $row): 
                        $totalSales += $row['total_sales'];
                        $totalCost += $row['parts_cost'];
                        $totalProfit += $row['profit'];
                    ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2"><?php echo $row['date']; ?></td>
                            <td class="px-4 py-2 text-center"><?php echo $row['orders_count']; ?></td>
                            <td class="px-4 py-2 text-blue-600"><?php echo number_format($row['total_sales'], 2); ?></td>
                            <td class="px-4 py-2 text-red-600"><?php echo number_format($row['parts_cost'], 2); ?></td>
                            <td class="px-4 py-2 font-bold <?php echo $row['profit'] >= 0 ? 'text-green-600' : 'text-red-600'; ?>">
                                <?php echo number_format($row['profit'], 2); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <!-- الإجمالي -->
                    <tr class="bg-gray-100 font-bold">
                        <td class="px-4 py-2">الإجمالي</td>
                        <td class="px-4 py-2 text-center">—</td>
                        <td class="px-4 py-2 text-blue-600"><?php echo number_format($totalSales, 2); ?></td>
                        <td class="px-4 py-2 text-red-600"><?php echo number_format($totalCost, 2); ?></td>
                        <td class="px-4 py-2 <?php echo $totalProfit >= 0 ? 'text-green-600' : 'text-red-600'; ?>">
                            <?php echo number_format($totalProfit, 2); ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>