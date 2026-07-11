<div class="bg-white rounded-xl shadow-sm p-6 max-w-4xl mx-auto">
    <!-- رأس الفاتورة -->
    <div class="flex justify-between items-start border-b border-gray-200 pb-4 mb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">فاتورة</h1>
            <p class="text-sm text-gray-500">#<?php echo $sale['invoice_number']; ?></p>
        </div>
        <div class="text-left">
            <div class="text-sm text-gray-500">التاريخ: <?php echo date('Y-m-d h:i A', strtotime($sale['sale_date'])); ?></div>
            <div>
                <span class="px-3 py-1 rounded-full text-sm font-medium 
                    <?php echo $sale['status'] == 'completed' ? 'bg-green-100 text-green-700' : 
                           ($sale['status'] == 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700'); ?>">
                    <?php echo $sale['status'] == 'completed' ? '✅ مكتملة' : 
                           ($sale['status'] == 'pending' ? '⏳ معلقة' : '💰 مدفوع جزئي'); ?>
                </span>
            </div>
        </div>
    </div>

    <!-- معلومات العميل -->
    <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
        <div><span class="text-gray-500">العميل:</span> <strong><?php echo $sale['customer_name'] ?? 'عميل نقدي'; ?></strong></div>
        <div><span class="text-gray-500">الهاتف:</span> <?php echo $sale['customer_phone'] ?? '—'; ?></div>
        <div><span class="text-gray-500">طريقة الدفع:</span> 
            <?php
            $methods = ['cash' => 'كاش', 'card' => 'بطاقة', 'wallet' => 'محفظة', 'bank_transfer' => 'تحويل', 'installment' => 'تقسيط'];
            echo $methods[$sale['payment_method']] ?? $sale['payment_method'];
            ?>
        </div>
        <div><span class="text-gray-500">بواسطة:</span> <?php echo $sale['created_by_name']; ?></div>
    </div>

    <!-- جدول الأصناف -->
    <div class="overflow-x-auto mb-4">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">#</th>
                    <th class="px-4 py-2 text-right text-xs text-gray-500">الوصف</th>
                    <th class="px-4 py-2 text-center text-xs text-gray-500">الكمية</th>
                    <th class="px-4 py-2 text-center text-xs text-gray-500">سعر الوحدة</th>
                    <th class="px-4 py-2 text-center text-xs text-gray-500">الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; foreach ($items as $item): ?>
                    <tr>
                        <td class="px-4 py-2"><?php echo $i++; ?></td>
                        <td class="px-4 py-2">
                            <?php echo $item['description']; ?>
                            <?php if ($item['item_type'] == 'part'): ?>
                                <span class="text-xs text-blue-600">(قطعة غيار)</span>
                            <?php else: ?>
                                <span class="text-xs text-purple-600">(خدمة)</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-2 text-center"><?php echo $item['quantity']; ?></td>
                        <td class="px-4 py-2 text-center"><?php echo number_format($item['unit_price'], 2); ?></td>
                        <td class="px-4 py-2 text-center font-bold"><?php echo number_format($item['total_price'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- الأجهزة المرتبطة -->
    <?php if (!empty($devices)): ?>
        <div class="mt-4 pt-4 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-3"><i class="fas fa-mobile-alt text-green-500"></i> الأجهزة المرتبطة</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-right text-xs text-gray-500">#</th>
                            <th class="px-4 py-2 text-right text-xs text-gray-500">الكود</th>
                            <th class="px-4 py-2 text-right text-xs text-gray-500">الجهاز</th>
                            <th class="px-4 py-2 text-right text-xs text-gray-500">الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($devices as $d): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2"><?php echo $d['id']; ?></td>
                                <td class="px-4 py-2 font-mono text-sm"><?php echo $d['device_code']; ?></td>
                                <td class="px-4 py-2"><?php echo $d['brand'] . ' ' . $d['model']; ?></td>
                                <td class="px-4 py-2">
                                    <a href="/devices/<?php echo $d['id']; ?>" class="text-blue-600 hover:text-blue-800 text-sm">عرض</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <!-- الإجماليات -->
    <div class="border-t border-gray-200 pt-4 space-y-1 text-sm">
        <div class="flex justify-between">
            <span class="text-gray-500">المجموع الفرعي</span>
            <span><?php echo number_format($sale['subtotal'], 2); ?></span>
        </div>
        <?php if ($sale['discount'] > 0): ?>
            <div class="flex justify-between">
                <span class="text-gray-500">الخصم</span>
                <span class="text-red-600">-<?php echo number_format($sale['discount'], 2); ?></span>
            </div>
        <?php endif; ?>
        <div class="flex justify-between text-lg font-bold pt-2 border-t border-gray-200">
            <span>الإجمالي</span>
            <span><?php echo number_format($sale['total_amount'], 2); ?> جنيه</span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-500">المدفوع</span>
            <span class="text-green-600"><?php echo number_format($sale['paid_amount'] ?? 0, 2); ?></span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-500">المتبقي</span>
            <span class="<?php echo ($sale['remaining_amount'] ?? 0) > 0 ? 'text-red-600 font-bold' : 'text-green-600'; ?>">
                <?php echo number_format($sale['remaining_amount'] ?? 0, 2); ?>
            </span>
        </div>
    </div>

    <?php if ($sale['notes']): ?>
        <div class="mt-4 text-sm text-gray-500 border-t border-gray-200 pt-4">
            <span class="font-medium">ملاحظات:</span> <?php echo $sale['notes']; ?>
        </div>
    <?php endif; ?>

    <!-- ===== أزرار الإجراء (مع زر التعديل) ===== -->
    <div class="flex gap-2 flex-wrap mt-4 no-print">
        <?php if ($sale['status'] != 'completed'): ?>
            <a href="/sales/edit/<?php echo $sale['id']; ?>" 
               class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-2 rounded-lg text-sm transition">
                <i class="fas fa-edit"></i> تعديل الفاتورة
            </a>
        <?php endif; ?>
        
        <a href="/sales/print/<?php echo $sale['id']; ?>" target="_blank" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm transition">
            <i class="fas fa-print"></i> طباعة
        </a>
        
        <a href="/sales/whatsapp/<?php echo $sale['id']; ?>" target="_blank" 
           class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg text-sm transition">
            <i class="fab fa-whatsapp"></i> واتساب
        </a>
        
        <?php if ($sale['status'] != 'completed'): ?>
            <a href="/sales/pending" class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-2 rounded-lg text-sm transition">
                <i class="fas fa-hand-holding-usd"></i> تسجيل دفعة
            </a>
        <?php endif; ?>
        
        <a href="/sales" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg text-sm transition">
            <i class="fas fa-arrow-right"></i> العودة
        </a>
    </div>

    <div class="mt-4 text-center text-xs text-gray-400 border-t border-gray-200 pt-4">
        شكراً لتعاملك معنا
    </div>
</div>