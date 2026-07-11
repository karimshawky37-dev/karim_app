<div class="bg-white rounded-xl shadow-sm p-6 max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">📄 تفاصيل القسط</h1>
            <p class="text-sm text-gray-500">#<?php echo $installment['id']; ?></p>
        </div>
        <a href="/installments" class="text-blue-600 hover:text-blue-800 text-sm"><i class="fas fa-arrow-left ml-1"></i> العودة</a>
    </div>

    <!-- عرض رسائل النجاح أو الخطأ -->
    <?php if (isset($_GET['success'])): ?>
        <div class="bg-green-100 border-r-4 border-green-500 text-green-700 p-3 mb-4 rounded-lg">
            <?php echo htmlspecialchars($_GET['success']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="bg-red-100 border-r-4 border-red-500 text-red-700 p-3 mb-4 rounded-lg">
            ⚠️ <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <!-- معلومات القسط -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div>
            <p class="text-xs text-gray-400">العميل</p>
            <p class="font-bold"><?php echo $installment['customer_name']; ?></p>
            <p class="text-sm text-gray-500"><?php echo $installment['customer_phone']; ?></p>
        </div>
        <div>
            <p class="text-xs text-gray-400">الجهاز</p>
            <p class="font-bold"><?php echo $installment['device_name']; ?></p>
        </div>
        <div>
            <p class="text-xs text-gray-400">الحالة</p>
            <p>
                <span class="px-2 py-1 rounded-full text-xs font-medium 
                    <?php echo $installment['status'] == 'active' ? 'bg-blue-100 text-blue-700' : 
                           ($installment['status'] == 'completed' ? 'bg-green-100 text-green-700' : 
                           ($installment['status'] == 'defaulted' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700')); ?>">
                    <?php echo $installment['status'] == 'active' ? 'نشط' : 
                           ($installment['status'] == 'completed' ? 'مكتمل' : 
                           ($installment['status'] == 'defaulted' ? 'متأخر' : 'ملغي')); ?>
                </span>
            </p>
        </div>
        <div>
            <p class="text-xs text-gray-400">المتبقي</p>
            <p class="font-bold <?php echo $installment['remaining_amount'] > 0 ? 'text-red-600' : 'text-green-600'; ?>">
                <?php echo number_format($installment['remaining_amount'], 2); ?> جنيه
            </p>
        </div>
    </div>

    <!-- نموذج إضافة دفعة -->
    <div class="bg-gray-50 p-4 rounded-lg mb-6">
        <h3 class="font-semibold text-gray-700 mb-3"><i class="fas fa-plus-circle text-green-500"></i> تسجيل دفعة</h3>
        <form method="POST" action="/installments/add-payment" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <input type="hidden" name="installment_id" value="<?php echo $installment['id']; ?>">
            <div>
                <label class="text-xs text-gray-500">المبلغ</label>
                <input type="number" step="0.01" name="amount" class="w-full border rounded-lg px-2 py-1 text-sm" required>
            </div>
            <div>
                <label class="text-xs text-gray-500">غرامة (اختياري)</label>
                <input type="number" step="0.01" name="penalty" value="0" class="w-full border rounded-lg px-2 py-1 text-sm">
            </div>
            <div>
                <label class="text-xs text-gray-500">ملاحظات</label>
                <input type="text" name="notes" placeholder="ملاحظات" class="w-full border rounded-lg px-2 py-1 text-sm">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg text-sm transition">
                    <i class="fas fa-check"></i> تسجيل
                </button>
            </div>
        </form>
    </div>

    <!-- جدول الأقساط -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">#</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">تاريخ الاستحقاق</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">المبلغ</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">المدفوع</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">الغرامة</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">الحالة</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">تاريخ السداد</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $today = new \DateTime();
                foreach ($payments as $p):
                    $due = new \DateTime($p['due_date']);
                    $paidAmount = $p['paid_amount'] ?? 0;
                    $rowClass = '';
                    $statusText = '';

                    if ($p['is_paid'] && $paidAmount >= $p['amount']) {
                        $rowClass = 'bg-green-50';
                        $statusText = '✅ مدفوع';
                    } elseif ($paidAmount > 0 && $paidAmount < $p['amount']) {
                        $rowClass = 'bg-yellow-50';
                        $statusText = '⏳ دفعة جزئية';
                    } elseif ($due < $today) {
                        $rowClass = 'bg-red-50';
                        $statusText = '⚠️ متأخر';
                    } else {
                        $statusText = '⏳ مستحق';
                    }
                ?>
                    <tr class="hover:bg-gray-50 transition <?php echo $rowClass; ?>">
                        <td class="px-4 py-2"><?php echo $p['payment_number']; ?></td>
                        <td class="px-4 py-2"><?php echo date('Y-m-d', strtotime($p['due_date'])); ?></td>
                        <td class="px-4 py-2 font-bold"><?php echo number_format($p['amount'], 2); ?></td>
                        <td class="px-4 py-2"><?php echo $paidAmount > 0 ? number_format($paidAmount, 2) : '—'; ?></td>
                        <td class="px-4 py-2"><?php echo $p['penalty'] > 0 ? number_format($p['penalty'], 2) : '—'; ?></td>
                        <td class="px-4 py-2"><?php echo $statusText; ?></td>
                        <td class="px-4 py-2 text-sm text-gray-400"><?php echo $p['payment_date'] ? date('Y-m-d', strtotime($p['payment_date'])) : '—'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- ملخص -->
    <div class="mt-4 text-sm">
        <span class="text-gray-500">المدفوع الكلي:</span>
        <span class="font-bold text-green-600"><?php echo number_format($paidTotal, 2); ?> جنيه</span>
        <span class="mx-2">|</span>
        <span class="text-gray-500">المتبقي:</span>
        <span class="font-bold <?php echo $installment['remaining_amount'] > 0 ? 'text-red-600' : 'text-green-600'; ?>">
            <?php echo number_format($installment['remaining_amount'], 2); ?> جنيه
        </span>
    </div>

    <?php if ($installment['notes']): ?>
        <div class="mt-4 text-sm text-gray-500 border-t pt-4">
            <span class="font-medium">ملاحظات:</span> <?php echo $installment['notes']; ?>
        </div>
    <?php endif; ?>
</div>