<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-4 border-r-4 border-emerald-500">
        <p class="text-sm text-gray-500">إجمالي المبيعات</p>
        <p class="text-2xl font-bold text-emerald-600"><?php echo number_format($totalSales, 2); ?></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 border-r-4 border-amber-500">
        <p class="text-sm text-gray-500">مبيعات اليوم</p>
        <p class="text-2xl font-bold text-amber-600"><?php echo number_format($todaySales, 2); ?></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 border-r-4 border-rose-500">
        <p class="text-sm text-gray-500">مصروفات اليوم</p>
        <p class="text-2xl font-bold text-rose-600"><?php echo number_format($todayExpenses, 2); ?></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 border-r-4 border-blue-500">
        <p class="text-sm text-gray-500">صافي الأرباح</p>
        <p class="text-2xl font-bold text-blue-600"><?php echo number_format($netProfit, 2); ?></p>
    </div>
</div>

<!-- الفواتير المعلقة -->
<div class="bg-white rounded-xl shadow-sm p-4 border-r-4 border-amber-500 mb-6">
    <div class="flex justify-between items-center">
        <div>
            <p class="text-sm text-gray-500">الفواتير المعلقة</p>
            <p class="text-2xl font-bold text-amber-600"><?php echo $pendingCount; ?></p>
        </div>
        <a href="/sales/pending" class="bg-amber-100 hover:bg-amber-200 text-amber-700 px-3 py-1.5 rounded-lg text-sm transition">
            عرض الكل <i class="fas fa-arrow-left"></i>
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
        <h3 class="font-semibold text-gray-700">آخر الفواتير</h3>
        <a href="/sales" class="text-sm text-blue-600 hover:underline">عرض الكل</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100 text-sm">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-4 py-2 text-right text-xs text-gray-500">#</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">العميل</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">المبلغ</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">طريقة الدفع</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">الحالة</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">التاريخ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentInvoices as $inv): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2"><?php echo $inv['id']; ?></td>
                        <td class="px-4 py-2"><?php echo $inv['customer_name'] ?? 'عميل نقدي'; ?></td>
                        <td class="px-4 py-2 font-bold"><?php echo number_format($inv['total_amount'], 2); ?></td>
                        <td class="px-4 py-2">
                            <?php
                            $methods = ['cash' => 'كاش', 'card' => 'بطاقة', 'wallet' => 'محفظة', 'bank_transfer' => 'تحويل', 'installment' => 'تقسيط'];
                            echo $methods[$inv['payment_method']] ?? $inv['payment_method'];
                            ?>
                        </td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded-full text-xs font-medium <?php echo $inv['status'] == 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'; ?>">
                                <?php echo $inv['status'] == 'completed' ? 'مكتملة' : 'معلقة'; ?>
                            </span>
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-400"><?php echo date('Y-m-d', strtotime($inv['sale_date'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4 text-center">
    <a href="/expenses" class="text-blue-600 hover:underline text-sm">عرض المصروفات</a>
    <span class="mx-2 text-gray-300">|</span>
    <a href="/wallets" class="text-blue-600 hover:underline text-sm">عرض المحافظ</a>
    <span class="mx-2 text-gray-300">|</span>
    <a href="/installments/overdue" class="text-red-600 hover:underline text-sm">الأقساط المتأخرة</a>
</div>