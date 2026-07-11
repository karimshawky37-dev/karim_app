<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="flex justify-between items-center p-4 border-b border-gray-200">
        <h2 class="text-lg font-bold text-gray-800">
            <i class="fas fa-clock text-amber-500"></i> الفواتير المعلقة
        </h2>
        <div class="text-sm text-gray-500">
            إجمالي المتبقي: <span class="font-bold text-red-600"><?php echo number_format($totalRemaining, 2); ?></span>
        </div>
    </div>

    <?php if (empty($sales)): ?>
        <div class="p-8 text-center text-gray-400">
            <i class="fas fa-check-circle text-4xl text-green-400 block mb-2"></i>
            لا توجد فواتير معلقة
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">#</th>
                        <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">رقم الفاتورة</th>
                        <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">العميل</th>
                        <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">الإجمالي</th>
                        <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">المدفوع</th>
                        <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">المتبقي</th>
                        <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">طريقة الدفع</th>
                        <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">التاريخ</th>
                        <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">الإجراء</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sales as $sale): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3"><?php echo $sale['id']; ?></td>
                            <td class="px-4 py-3 font-mono text-sm"><?php echo $sale['invoice_number']; ?></td>
                            <td class="px-4 py-3">
                                <div class="font-medium"><?php echo $sale['customer_name'] ?? 'عميل نقدي'; ?></div>
                                <div class="text-xs text-gray-400"><?php echo $sale['customer_phone'] ?? ''; ?></div>
                            </td>
                            <td class="px-4 py-3 font-bold"><?php echo number_format($sale['total_amount'], 2); ?></td>
                            <td class="px-4 py-3 text-green-600"><?php echo number_format($sale['paid_amount'] ?? 0, 2); ?></td>
                            <td class="px-4 py-3 font-bold text-red-600"><?php echo number_format($sale['remaining_amount'] ?? 0, 2); ?></td>
                            <td class="px-4 py-3 text-sm">
                                <?php
                                $methods = ['cash' => 'كاش', 'card' => 'بطاقة', 'wallet' => 'محفظة', 'bank_transfer' => 'تحويل', 'installment' => 'تقسيط'];
                                echo $methods[$sale['payment_method']] ?? $sale['payment_method'];
                                ?>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-400"><?php echo date('Y-m-d', strtotime($sale['sale_date'])); ?></td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2 flex-wrap">
                                    <button onclick="openPaymentModal(<?php echo $sale['id']; ?>, <?php echo $sale['remaining_amount']; ?>)" 
                                            class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs transition">
                                        <i class="fas fa-hand-holding-usd"></i> دفعة
                                    </button>
                                    <a href="/sales/view/<?php echo $sale['id']; ?>" class="text-blue-600 hover:text-blue-800 text-xs">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Modal تسجيل دفعة -->
<div id="paymentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl p-6 max-w-md w-full">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">تسجيل دفعة</h3>
            <button onclick="closePaymentModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>
        <form method="POST" action="/sales/add-payment">
            <input type="hidden" name="sale_id" id="payment_sale_id">
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">المبلغ <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" name="amount" id="payment_amount" 
                       class="w-full border rounded-lg px-3 py-2" required>
                <p class="text-xs text-gray-400 mt-1">المتبقي: <span id="payment_remaining" class="font-bold text-red-600"></span></p>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">طريقة الدفع</label>
                <select name="payment_method" class="w-full border rounded-lg px-3 py-2">
                    <option value="cash">كاش</option>
                    <option value="card">بطاقة</option>
                    <option value="wallet">محفظة</option>
                    <option value="bank_transfer">تحويل بنكي</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">ملاحظات</label>
                <input type="text" name="notes" placeholder="ملاحظات..." class="w-full border rounded-lg px-3 py-2">
            </div>
            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg transition">
                <i class="fas fa-check"></i> تسجيل الدفعة
            </button>
        </form>
    </div>
</div>

<script>
    function openPaymentModal(saleId, remaining) {
        document.getElementById('payment_sale_id').value = saleId;
        document.getElementById('payment_remaining').textContent = remaining.toFixed(2) + ' جنيه';
        document.getElementById('payment_amount').value = remaining;
        document.getElementById('paymentModal').classList.remove('hidden');
    }
    
    function closePaymentModal() {
        document.getElementById('paymentModal').classList.add('hidden');
    }
    
    document.getElementById('paymentModal').addEventListener('click', function(e) {
        if (e.target === this) closePaymentModal();
    });
</script>