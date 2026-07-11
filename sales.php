<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-chart-line text-blue-500"></i> تقرير المبيعات</h1>
        <div class="flex gap-2">
            <a href="/reports" class="text-blue-600 hover:text-blue-800 text-sm"><i class="fas fa-arrow-left"></i> العودة</a>
            <button onclick="window.print()" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm transition">
    <i class="fas fa-print"></i> طباعة
</button>
            <a href="/reports/sales/export-csv?start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>&status=<?php echo $status_filter; ?>" 
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition">
                <i class="fas fa-file-csv"></i> تصدير CSV
            </a>
            <button onclick="window.print()" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm transition">
                <i class="fas fa-print"></i> طباعة
            </button>
        </div>
    </div>

    <!-- فلتر التاريخ والحالة -->
    <form method="GET" action="/reports/sales" class="flex flex-wrap gap-3 items-end mb-6 bg-gray-50 p-4 rounded-lg">
        <div>
            <label class="block text-xs text-gray-500">من تاريخ</label>
            <input type="date" name="start_date" value="<?php echo $start_date; ?>" class="border rounded-lg px-3 py-1.5 text-sm">
        </div>
        <div>
            <label class="block text-xs text-gray-500">إلى تاريخ</label>
            <input type="date" name="end_date" value="<?php echo $end_date; ?>" class="border rounded-lg px-3 py-1.5 text-sm">
        </div>
        <div>
            <label class="block text-xs text-gray-500">حالة الفاتورة</label>
            <select name="status" class="border rounded-lg px-3 py-1.5 text-sm">
                <option value="all" <?php echo $status_filter == 'all' ? 'selected' : ''; ?>>الجميع</option>
                <option value="completed" <?php echo $status_filter == 'completed' ? 'selected' : ''; ?>>مكتملة</option>
                <option value="pending" <?php echo $status_filter == 'pending' ? 'selected' : ''; ?>>معلقة</option>
                <option value="partially" <?php echo $status_filter == 'partially' ? 'selected' : ''; ?>>مدفوع جزئي</option>
            </select>
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded-lg text-sm transition">
            <i class="fas fa-search"></i> عرض
        </button>
    </form>

    <!-- الإحصائيات -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-6">
        <div class="bg-gray-50 p-3 rounded-lg border-r-4 border-blue-500">
            <p class="text-xs text-gray-500">إجمالي المبيعات</p>
            <p class="text-xl font-bold text-blue-600"><?php echo number_format($stats['total_amount'] ?? 0, 2); ?></p>
        </div>
        <div class="bg-gray-50 p-3 rounded-lg border-r-4 border-green-500">
            <p class="text-xs text-gray-500">المدفوع</p>
            <p class="text-xl font-bold text-green-600"><?php echo number_format($stats['total_paid'] ?? 0, 2); ?></p>
        </div>
        <div class="bg-gray-50 p-3 rounded-lg border-r-4 border-red-500">
            <p class="text-xs text-gray-500">المتبقي</p>
            <p class="text-xl font-bold text-red-600"><?php echo number_format($stats['total_remaining'] ?? 0, 2); ?></p>
        </div>
        <div class="bg-gray-50 p-3 rounded-lg border-r-4 border-purple-500">
            <p class="text-xs text-gray-500">عدد الفواتير</p>
            <p class="text-xl font-bold text-purple-600"><?php echo $stats['total_count'] ?? 0; ?></p>
        </div>
        <div class="bg-gray-50 p-3 rounded-lg border-r-4 border-amber-500">
            <p class="text-xs text-gray-500">متوسط الفاتورة</p>
            <p class="text-xl font-bold text-amber-600"><?php echo number_format($stats['avg_amount'] ?? 0, 2); ?></p>
        </div>
    </div>

    <!-- رسم بياني -->
    <div class="mb-6">
        <canvas id="salesChart" height="80"></canvas>
    </div>

    <!-- جدول المبيعات اليومية -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">التاريخ</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">عدد الفواتير</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">الإجمالي</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">المدفوع</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">المتبقي</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">الحالة (الفلتر)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data)): ?>
                    <tr><td colspan="6" class="px-4 py-4 text-center text-gray-400">لا توجد بيانات في هذه الفترة</td></tr>
                <?php else: ?>
                    <?php foreach ($data as $row): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2"><?php echo $row['date']; ?></td>
                            <td class="px-4 py-2 text-center"><?php echo $row['count']; ?></td>
                            <td class="px-4 py-2 font-bold text-blue-600"><?php echo number_format($row['total'], 2); ?></td>
                            <td class="px-4 py-2 text-green-600"><?php echo number_format($row['paid'], 2); ?></td>
                            <td class="px-4 py-2 <?php echo $row['remaining'] > 0 ? 'text-red-600' : 'text-gray-400'; ?>"><?php echo number_format($row['remaining'], 2); ?></td>
                            <td class="px-4 py-2">
                                <?php
                                $statusLabels = [
                                    'all' => 'الجميع',
                                    'completed' => 'مكتملة',
                                    'pending' => 'معلقة',
                                    'partially' => 'مدفوع جزئي'
                                ];
                                $statusColors = [
                                    'all' => 'bg-gray-100 text-gray-700',
                                    'completed' => 'bg-green-100 text-green-700',
                                    'pending' => 'bg-amber-100 text-amber-700',
                                    'partially' => 'bg-blue-100 text-blue-700'
                                ];
                                $label = $statusLabels[$status_filter] ?? $status_filter;
                                $color = $statusColors[$status_filter] ?? 'bg-gray-100 text-gray-700';
                                ?>
                                <span class="px-2 py-1 rounded-full text-xs font-medium <?php echo $color; ?>">
                                    <?php echo $label; ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- أفضل 10 عملاء -->
    <div class="mt-6">
        <h3 class="font-semibold text-gray-700 mb-3"><i class="fas fa-trophy text-yellow-500"></i> أفضل 10 عملاء</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
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
                            <td class="px-4 py-2 text-center"><?php echo $c['orders_count']; ?></td>
                            <td class="px-4 py-2 font-bold text-green-600"><?php echo number_format($c['total_spent'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesChart').getContext('2d');
        const data = <?php echo json_encode($data); ?>;
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(d => d.date),
                datasets: [{
                    label: 'المبيعات',
                    data: data.map(d => d.total),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toFixed(0);
                            }
                        }
                    }
                }
            }
        });
    });
</script>