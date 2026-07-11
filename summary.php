<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-chart-bar text-blue-500"></i> إجمالي المبيعات</h1>
        <a href="/" class="text-blue-600 hover:text-blue-800 text-sm"><i class="fas fa-arrow-left"></i> العودة</a>
    </div>

    <!-- إحصائيات سريعة -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gray-50 p-4 rounded-lg border-r-4 border-blue-500">
            <p class="text-xs text-gray-500">إجمالي المبيعات</p>
            <p class="text-2xl font-bold text-blue-600"><?php echo number_format($stats['total_sales'] ?? 0, 2); ?></p>
        </div>
        <div class="bg-gray-50 p-4 rounded-lg border-r-4 border-green-500">
            <p class="text-xs text-gray-500">المدفوع</p>
            <p class="text-2xl font-bold text-green-600"><?php echo number_format($stats['total_paid'] ?? 0, 2); ?></p>
        </div>
        <div class="bg-gray-50 p-4 rounded-lg border-r-4 border-red-500">
            <p class="text-xs text-gray-500">المتبقي</p>
            <p class="text-2xl font-bold text-red-600"><?php echo number_format($stats['total_remaining'] ?? 0, 2); ?></p>
        </div>
        <div class="bg-gray-50 p-4 rounded-lg border-r-4 border-purple-500">
            <p class="text-xs text-gray-500">عدد الفواتير</p>
            <p class="text-2xl font-bold text-purple-600"><?php echo $stats['total_orders'] ?? 0; ?></p>
        </div>
    </div>

    <!-- مبيعات اليوم والشهر -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-blue-50 p-4 rounded-lg border-r-4 border-blue-400">
            <p class="text-sm text-gray-600">مبيعات اليوم</p>
            <p class="text-2xl font-bold text-blue-700"><?php echo number_format($today['today_sales'] ?? 0, 2); ?></p>
            <p class="text-xs text-gray-400">عدد الفواتير: <?php echo $today['today_orders'] ?? 0; ?></p>
        </div>
        <div class="bg-green-50 p-4 rounded-lg border-r-4 border-green-400">
            <p class="text-sm text-gray-600">مبيعات هذا الشهر</p>
            <p class="text-2xl font-bold text-green-700"><?php echo number_format($month['month_sales'] ?? 0, 2); ?></p>
            <p class="text-xs text-gray-400">عدد الفواتير: <?php echo $month['month_orders'] ?? 0; ?></p>
        </div>
    </div>

    <!-- أفضل 5 عملاء -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
            <h3 class="font-semibold text-gray-700"><i class="fas fa-trophy text-yellow-500"></i> أفضل 5 عملاء</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-2 text-right text-xs text-gray-500">#</th>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">العميل</th>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">عدد الطلبات</th>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">إجمالي المشتريات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($topCustomers as $c): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2"><?php echo $i++; ?></td>
                            <td class="px-4 py-2">
                                <?php echo $c['full_name'] ?? 'عميل نقدي'; ?>
                                <span class="text-xs text-gray-400"><?php echo $c['phone'] ?? ''; ?></span>
                            </td>
                            <td class="px-4 py-2"><?php echo $c['orders']; ?></td>
                            <td class="px-4 py-2 font-bold text-green-600"><?php echo number_format($c['total'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6 flex justify-center gap-4">
        <a href="/reports/sales" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
            <i class="fas fa-file-alt"></i> تقرير المبيعات
        </a>
        <a href="/sales/pending" class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-2 rounded-lg transition">
            <i class="fas fa-clock"></i> الفواتير المعلقة
        </a>
    </div>
</div>