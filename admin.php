<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-4 border-r-4 border-blue-500">
        <p class="text-sm text-gray-500">الأجهزة</p>
        <p class="text-2xl font-bold text-blue-600"><?php echo $devicesCount; ?></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 border-r-4 border-green-500">
        <p class="text-sm text-gray-500">العملاء</p>
        <p class="text-2xl font-bold text-green-600"><?php echo $customersCount; ?></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 border-r-4 border-purple-500">
        <p class="text-sm text-gray-500">الفنيين</p>
        <p class="text-2xl font-bold text-purple-600"><?php echo $techniciansCount; ?></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 border-r-4 border-orange-500">
        <p class="text-sm text-gray-500">تحت الصيانة</p>
        <p class="text-2xl font-bold text-orange-600"><?php echo $inProgress; ?></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 border-r-4 border-red-500">
        <p class="text-sm text-gray-500">نواقص المخزون</p>
        <p class="text-2xl font-bold text-red-600"><?php echo $lowStock; ?></p>
    </div>
</div>

<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-4 border-r-4 border-cyan-500">
        <p class="text-sm text-gray-500">أجهزة اليوم</p>
        <p class="text-2xl font-bold text-cyan-600"><?php echo $todayDevices; ?></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 border-r-4 border-pink-500">
        <p class="text-sm text-gray-500">جاهزة للاستلام</p>
        <p class="text-2xl font-bold text-pink-600"><?php echo $readyForPickup; ?></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 border-r-4 border-emerald-500">
        <p class="text-sm text-gray-500">صافي الأرباح</p>
        <p class="text-2xl font-bold text-emerald-600"><?php echo number_format($netProfit, 2); ?></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 border-r-4 border-red-500">
        <p class="text-sm text-gray-500">أقساط متأخرة</p>
        <p class="text-2xl font-bold text-red-600"><?php echo $overdueInstallments; ?></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 border-r-4 border-amber-500">
        <p class="text-sm text-gray-500">أجهزة راكدة > 3 أيام</p>
        <p class="text-2xl font-bold text-amber-600"><?php echo $staleDevices; ?></p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-semibold text-gray-700">آخر الفواتير</h3>
            <a href="/sales" class="text-sm text-blue-600 hover:underline">عرض الكل</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-2 text-right text-xs text-gray-500">العميل</th>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">المبلغ</th>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">التاريخ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentInvoices as $inv): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2"><?php echo $inv['customer_name'] ?? 'عميل نقدي'; ?></td>
                            <td class="px-4 py-2 font-bold"><?php echo number_format($inv['total_amount'], 2); ?></td>
                            <td class="px-4 py-2 text-sm text-gray-400"><?php echo date('Y-m-d', strtotime($inv['sale_date'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-semibold text-gray-700">آخر الأجهزة</h3>
            <a href="/devices" class="text-sm text-blue-600 hover:underline">عرض الكل</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-2 text-right text-xs text-gray-500">الكود</th>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">الجهاز</th>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">العميل</th>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentDevices as $d): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 font-mono text-sm"><?php echo $d['device_code']; ?></td>
                            <td class="px-4 py-2"><?php echo $d['brand'] . ' ' . $d['model']; ?></td>
                            <td class="px-4 py-2"><?php echo $d['customer_name']; ?></td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                    <?php echo $d['status_name']; ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if ($overdueInstallments > 0): ?>
    <div class="bg-red-50 border-r-4 border-red-500 p-4 rounded-lg mb-4">
        <div class="flex items-center gap-2">
            <i class="fas fa-exclamation-triangle text-red-500"></i>
            <span class="text-red-700 font-medium">تنبيه: يوجد <?php echo $overdueInstallments; ?> أقساط متأخرة!</span>
            <a href="/installments/overdue" class="text-red-600 underline text-sm">عرض التفاصيل</a>
        </div>
    </div>
<?php endif; ?>

<?php if ($staleDevices > 0): ?>
    <div class="bg-amber-50 border-r-4 border-amber-500 p-4 rounded-lg mb-4">
        <div class="flex items-center gap-2">
            <i class="fas fa-clock text-amber-500"></i>
            <span class="text-amber-700 font-medium">تنبيه: <?php echo $staleDevices; ?> أجهزة مكثت أكثر من 3 أيام دون تحديث!</span>
            <a href="/devices" class="text-amber-600 underline text-sm">عرض التفاصيل</a>
        </div>
    </div>

<?php endif; ?>
<!-- الإشعارات -->
<div class="bg-white rounded-xl shadow-sm p-4 mb-6">
    <div class="flex justify-between items-center mb-3">
        <h3 class="font-semibold text-gray-700">
            <i class="fas fa-bell text-blue-500"></i> 
            الإشعارات
            <?php if ($unreadNotifications > 0): ?>
                <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full"><?php echo $unreadNotifications; ?> جديدة</span>
            <?php endif; ?>
        </h3>
        <a href="/notifications" class="text-sm text-blue-600 hover:underline">عرض الكل</a>
    </div>
    
    <?php if (empty($recentNotifications)): ?>
        <p class="text-sm text-gray-400 text-center py-4">لا توجد إشعارات</p>
    <?php else: ?>
        <div class="space-y-2 max-h-60 overflow-y-auto">
            <?php foreach ($recentNotifications as $n): ?>
                <div class="flex items-start gap-3 p-2 rounded-lg hover:bg-gray-50 transition <?php echo !$n['is_read'] ? 'bg-blue-50 border-r-4 border-blue-500' : ''; ?>">
                    <div class="text-lg"><?php echo explode(' ', $n['title'])[0] ?? '📌'; ?></div>
                    <div class="flex-1">
                        <div class="font-medium text-sm text-gray-800"><?php echo $n['title']; ?></div>
                        <div class="text-xs text-gray-500"><?php echo $n['message']; ?></div>
                        <div class="text-[10px] text-gray-400 mt-1"><?php echo date('h:i A', strtotime($n['created_at'])); ?></div>
                    </div>
                    <?php if ($n['link']): ?>
                        <a href="<?php echo $n['link']; ?>" class="text-blue-600 hover:text-blue-800 text-xs">عرض</a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>
<!-- ===== الرسوم البيانية ===== -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- مبيعات -->
    <div class="bg-white rounded-xl shadow-sm p-4">
        <div class="flex justify-between items-center mb-3">
            <h3 class="font-semibold text-gray-700">📈 اتجاه المبيعات</h3>
            <div class="flex gap-1">
                <button onclick="loadSalesChart('week')" class="text-xs px-2 py-1 rounded bg-gray-200 hover:bg-gray-300 transition">أسبوع</button>
                <button onclick="loadSalesChart('month')" class="text-xs px-2 py-1 rounded bg-blue-600 text-white hover:bg-blue-700 transition">شهر</button>
                <button onclick="loadSalesChart('year')" class="text-xs px-2 py-1 rounded bg-gray-200 hover:bg-gray-300 transition">سنة</button>
            </div>
        </div>
        <canvas id="salesChart" height="150"></canvas>
    </div>

    <!-- الأرباح -->
    <div class="bg-white rounded-xl shadow-sm p-4">
        <div class="flex justify-between items-center mb-3">
            <h3 class="font-semibold text-gray-700">💰 المبيعات مقابل المصروفات</h3>
            <div class="flex gap-1">
                <button onclick="loadProfitChart('week')" class="text-xs px-2 py-1 rounded bg-gray-200 hover:bg-gray-300 transition">أسبوع</button>
                <button onclick="loadProfitChart('month')" class="text-xs px-2 py-1 rounded bg-blue-600 text-white hover:bg-blue-700 transition">شهر</button>
                <button onclick="loadProfitChart('year')" class="text-xs px-2 py-1 rounded bg-gray-200 hover:bg-gray-300 transition">سنة</button>
            </div>
        </div>
        <canvas id="profitChart" height="150"></canvas>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- حالة الأجهزة -->
    <div class="bg-white rounded-xl shadow-sm p-4">
        <h3 class="font-semibold text-gray-700 mb-3">📱 حالة الأجهزة</h3>
        <canvas id="deviceStatusChart" height="150"></canvas>
    </div>

    <!-- أداء الفنيين -->
    <div class="bg-white rounded-xl shadow-sm p-4">
        <h3 class="font-semibold text-gray-700 mb-3">👨‍🔧 أداء الفنيين</h3>
        <canvas id="technicianChart" height="150"></canvas>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- أكثر الأجهزة شيوعاً -->
    <div class="bg-white rounded-xl shadow-sm p-4">
        <h3 class="font-semibold text-gray-700 mb-3">📱 أكثر الأجهزة شيوعاً</h3>
        <canvas id="popularDevicesChart" height="150"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // ===== متغيرات لتخزين الرسوم البيانية =====
    let salesChart = null;
    let profitChart = null;
    let deviceStatusChart = null;
    let technicianChart = null;
    let popularDevicesChart = null;

    // ===== ألوان =====
    const colors = [
        '#3b82f6', '#22c55e', '#f59e0b', '#ef4444', '#8b5cf6',
        '#ec4899', '#14b8a6', '#f97316', '#6366f1', '#06b6d4'
    ];

    // ===== 1. رسم مبيعات =====
    function loadSalesChart(period) {
        fetch('/reports/chart-sales?period=' + period)
            .then(r => r.json())
            .then(data => {
                if (salesChart) salesChart.destroy();
                const ctx = document.getElementById('salesChart').getContext('2d');
                salesChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.map(d => d.label),
                        datasets: [{
                            label: 'المبيعات',
                            data: data.map(d => d.value),
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59,130,246,0.1)',
                            fill: true,
                            tension: 0.3,
                            pointRadius: 4,
                            pointBackgroundColor: '#3b82f6'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true } }
                    }
                });
            });
    }

    // ===== 2. رسم الأرباح =====
    function loadProfitChart(period) {
        fetch('/reports/chart-profit?period=' + period)
            .then(r => r.json())
            .then(data => {
                if (profitChart) profitChart.destroy();
                const ctx = document.getElementById('profitChart').getContext('2d');
                profitChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.map(d => d.label),
                        datasets: [
                            {
                                label: 'المبيعات',
                                data: data.map(d => d.sales),
                                borderColor: '#3b82f6',
                                backgroundColor: 'rgba(59,130,246,0.1)',
                                fill: true,
                                tension: 0.3
                            },
                            {
                                label: 'المصروفات',
                                data: data.map(d => d.expenses),
                                borderColor: '#ef4444',
                                backgroundColor: 'rgba(239,68,68,0.1)',
                                fill: true,
                                tension: 0.3
                            },
                            {
                                label: 'صافي الأرباح',
                                data: data.map(d => d.profit),
                                borderColor: '#22c55e',
                                backgroundColor: 'rgba(34,197,94,0.1)',
                                fill: true,
                                tension: 0.3
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: { legend: { position: 'top', labels: { font: { size: 10 } } } },
                        scales: { y: { beginAtZero: true } }
                    }
                });
            });
    }

    // ===== 3. رسم حالة الأجهزة =====
    function loadDeviceStatusChart() {
        fetch('/reports/chart-device-status')
            .then(r => r.json())
            .then(data => {
                if (deviceStatusChart) deviceStatusChart.destroy();
                const ctx = document.getElementById('deviceStatusChart').getContext('2d');
                deviceStatusChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: data.map(d => d.label),
                        datasets: [{
                            data: data.map(d => d.value),
                            backgroundColor: colors.slice(0, data.length),
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: { position: 'bottom', labels: { font: { size: 10 } } }
                        }
                    }
                });
            });
    }

    // ===== 4. رسم أداء الفنيين =====
    function loadTechnicianChart() {
        fetch('/reports/chart-technicians')
            .then(r => r.json())
            .then(data => {
                if (technicianChart) technicianChart.destroy();
                const ctx = document.getElementById('technicianChart').getContext('2d');
                technicianChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.map(d => d.label),
                        datasets: [
                            {
                                label: 'مكتملة',
                                data: data.map(d => d.completed),
                                backgroundColor: '#22c55e'
                            },
                            {
                                label: 'إجمالي المهام',
                                data: data.map(d => d.value),
                                backgroundColor: '#3b82f6'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: { legend: { position: 'top', labels: { font: { size: 10 } } } },
                        scales: { y: { beginAtZero: true } }
                    }
                });
            });
    }

    // ===== 5. رسم أكثر الأجهزة شيوعاً =====
    function loadPopularDevicesChart() {
        fetch('/reports/chart-popular-devices?limit=8')
            .then(r => r.json())
            .then(data => {
                if (popularDevicesChart) popularDevicesChart.destroy();
                const ctx = document.getElementById('popularDevicesChart').getContext('2d');
                popularDevicesChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.map(d => d.label),
                        datasets: [{
                            label: 'عدد الأجهزة',
                            data: data.map(d => d.value),
                            backgroundColor: colors.slice(0, data.length),
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true } },
                        indexAxis: 'y'
                    }
                });
            });
    }

    // ===== تحميل جميع الرسوم عند فتح الصفحة =====
    document.addEventListener('DOMContentLoaded', function() {
        loadSalesChart('month');
        loadProfitChart('month');
        loadDeviceStatusChart();
        loadTechnicianChart();
        loadPopularDevicesChart();
    });
</script>