<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-handshake text-amber-500 ml-2"></i> معادلة الاستثمار</h1>
        <a href="/" class="text-blue-600 hover:text-blue-800 text-sm"><i class="fas fa-arrow-right"></i> العودة</a>
    </div>

    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="p-4 rounded-lg mb-4 border-r-4 <?php echo ($_SESSION['flash_type'] ?? 'success') == 'success' ? 'bg-green-50 border-green-500 text-green-700' : 'bg-red-50 border-red-500 text-red-700'; ?>">
            <?php echo $_SESSION['flash_message']; unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
        </div>
    <?php endif; ?>

    <!-- كروت المعادلة -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
        <div class="bg-gradient-to-br from-amber-50 to-amber-100 p-4 rounded-xl border-r-4 border-amber-500 shadow-sm">
            <p class="text-xs text-amber-700 font-bold">💰 معادلة الاستثمار</p>
            <p class="text-xl font-bold text-amber-800"><?php echo number_format($stats->investment ?? 0, 2); ?> ج.م</p>
        </div>
        <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-xl border-r-4 border-green-500 shadow-sm">
            <p class="text-xs text-green-700 font-bold">📈 صافي الأرباح</p>
            <p class="text-xl font-bold text-green-800"><?php echo number_format($stats->net_profit ?? 0, 2); ?> ج.م</p>
        </div>
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-xl border-r-4 border-blue-500 shadow-sm">
            <p class="text-xs text-blue-700 font-bold">🏦 إجمالي الأصول</p>
            <p class="text-xl font-bold text-blue-800"><?php echo number_format($stats->total_assets ?? 0, 2); ?> ج.م</p>
        </div>
        <div class="bg-gradient-to-br from-red-50 to-red-100 p-4 rounded-xl border-r-4 border-red-500 shadow-sm">
            <p class="text-xs text-red-700 font-bold">📊 إجمالي الالتزامات</p>
            <p class="text-xl font-bold text-red-800"><?php echo number_format($stats->total_liabilities ?? 0, 2); ?> ج.م</p>
        </div>
    </div>

    <!-- تفاصيل الحسابات -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mb-6">
        <div class="bg-gray-50 p-2 rounded-lg border-r-4 border-indigo-400 text-center">
            <p class="text-[10px] text-gray-500">💰 الخزنة</p>
            <p class="text-sm font-bold text-indigo-600"><?php echo number_format($stats->cash ?? 0, 2); ?></p>
        </div>
        <div class="bg-gray-50 p-2 rounded-lg border-r-4 border-purple-400 text-center">
            <p class="text-[10px] text-gray-500">🏦 البنك</p>
            <p class="text-sm font-bold text-purple-600"><?php echo number_format($stats->bank ?? 0, 2); ?></p>
        </div>
        <div class="bg-gray-50 p-2 rounded-lg border-r-4 border-teal-400 text-center">
            <p class="text-[10px] text-gray-500">📦 المخزون</p>
            <p class="text-sm font-bold text-teal-600"><?php echo number_format($stats->inventory ?? 0, 2); ?></p>
        </div>
        <div class="bg-gray-50 p-2 rounded-lg border-r-4 border-amber-400 text-center">
            <p class="text-[10px] text-gray-500">👤 ديون العملاء</p>
            <p class="text-sm font-bold text-amber-600"><?php echo number_format($stats->customer_debts ?? 0, 2); ?></p>
        </div>
        <div class="bg-gray-50 p-2 rounded-lg border-r-4 border-red-400 text-center">
            <p class="text-[10px] text-gray-500">🏷️ ديون الموردين</p>
            <p class="text-sm font-bold text-red-600"><?php echo number_format($stats->supplier_debts ?? 0, 2); ?></p>
        </div>
        <div class="bg-gray-50 p-2 rounded-lg border-r-4 border-pink-400 text-center">
            <p class="text-[10px] text-gray-500">🔄 أرصدة مختلطة</p>
            <p class="text-sm font-bold text-pink-600"><?php echo number_format($stats->mixed_balance ?? 0, 2); ?></p>
        </div>
        <div class="bg-gray-50 p-2 rounded-lg border-r-4 border-orange-400 text-center">
            <p class="text-[10px] text-gray-500">📝 المصروفات</p>
            <p class="text-sm font-bold text-orange-600"><?php echo number_format($stats->expenses ?? 0, 2); ?></p>
        </div>
        <div class="bg-gray-50 p-2 rounded-lg border-r-4 border-gray-400 text-center">
            <p class="text-[10px] text-gray-500">💵 رأس المال</p>
            <p class="text-sm font-bold text-gray-600"><?php echo number_format($stats->initial_capital ?? 0, 2); ?></p>
        </div>
    </div>

    <!-- ===== العمليات (أزرار منتظمة) ===== -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

        <!-- العمود 1: رأس المال + مشتريات + مبيعات -->
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-200">
            <h3 class="font-bold text-gray-700 text-sm mb-3"><i class="fas fa-coins text-blue-500 ml-1"></i> رأس المال</h3>
            <?php if ($stats->capital_added): ?>
                <div class="bg-green-50 p-2 rounded-lg text-xs text-green-700 mb-3 text-center">✅ تم الإيداع</div>
            <?php else: ?>
                <form method="POST" action="/investment/initial-capital" class="flex flex-wrap gap-2">
                    <input type="number" step="0.01" name="amount" placeholder="المبلغ" class="flex-1 min-w-[80px] border rounded-lg px-2 py-1.5 text-sm" required>
                    <select name="type" class="border rounded-lg px-2 py-1.5 text-sm"><option value="cash">خزنة</option><option value="bank">بنك</option></select>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded-lg text-sm transition">إيداع</button>
                </form>
            <?php endif; ?>

            <h3 class="font-bold text-gray-700 text-sm mb-3 mt-4"><i class="fas fa-shopping-cart text-teal-500 ml-1"></i> مشتريات</h3>
            <form method="POST" action="/investment/purchase" class="flex flex-wrap gap-2">
                <input type="number" step="0.01" name="amount" placeholder="المبلغ" class="flex-1 min-w-[80px] border rounded-lg px-2 py-1.5 text-sm" required>
                <select name="payment_type" class="border rounded-lg px-2 py-1.5 text-sm"><option value="cash">كاش</option><option value="supplier_credit">آجل</option></select>
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-1.5 rounded-lg text-sm transition">شراء</button>
            </form>

            <h3 class="font-bold text-gray-700 text-sm mb-3 mt-4"><i class="fas fa-tag text-green-500 ml-1"></i> مبيعات</h3>
            <form method="POST" action="/investment/sell" class="flex flex-wrap gap-2">
                <input type="number" step="0.01" name="cost" placeholder="التكلفة" class="flex-1 min-w-[70px] border rounded-lg px-2 py-1.5 text-sm" required>
                <input type="number" step="0.01" name="selling_price" placeholder="سعر البيع" class="flex-1 min-w-[70px] border rounded-lg px-2 py-1.5 text-sm" required>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-1.5 rounded-lg text-sm transition">بيع</button>
            </form>
        </div>

        <!-- العمود 2: مصروفات + ديون العملاء -->
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-200">
            <h3 class="font-bold text-gray-700 text-sm mb-3"><i class="fas fa-receipt text-orange-500 ml-1"></i> مصروفات</h3>
            <form method="POST" action="/investment/expense" class="flex flex-wrap gap-2 mb-4">
                <input type="text" name="description" placeholder="البيان" class="flex-1 min-w-[60px] border rounded-lg px-2 py-1.5 text-sm">
                <input type="number" step="0.01" name="amount" placeholder="المبلغ" class="flex-1 min-w-[70px] border rounded-lg px-2 py-1.5 text-sm" required>
                <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-1.5 rounded-lg text-sm transition">تسجيل</button>
            </form>

            <h3 class="font-bold text-gray-700 text-sm mb-3"><i class="fas fa-user text-amber-500 ml-1"></i> ديون العملاء</h3>
            <form method="POST" action="/investment/customer-debt" class="flex flex-wrap gap-2 mb-2">
                <input type="text" name="customer_name" placeholder="الاسم" class="flex-1 min-w-[60px] border rounded-lg px-2 py-1.5 text-sm">
                <input type="number" step="0.01" name="amount" placeholder="المبلغ" class="flex-1 min-w-[70px] border rounded-lg px-2 py-1.5 text-sm" required>
                <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-1.5 rounded-lg text-sm transition">إضافة</button>
            </form>
            <form method="POST" action="/investment/collect-debt" class="flex flex-wrap gap-2">
                <input type="number" step="0.01" name="amount" placeholder="تحصيل" class="flex-1 min-w-[70px] border rounded-lg px-2 py-1.5 text-sm" required>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-1.5 rounded-lg text-sm transition">تحصيل</button>
            </form>
        </div>

        <!-- العمود 3: موردين + بنك -->
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-200">
            <h3 class="font-bold text-gray-700 text-sm mb-3"><i class="fas fa-truck text-red-500 ml-1"></i> ديون الموردين</h3>
            <form method="POST" action="/investment/pay-supplier" class="flex flex-wrap gap-2 mb-4">
                <input type="number" step="0.01" name="amount" placeholder="مبلغ السداد" class="flex-1 min-w-[70px] border rounded-lg px-2 py-1.5 text-sm" required>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-1.5 rounded-lg text-sm transition">سداد</button>
            </form>

            <h3 class="font-bold text-gray-700 text-sm mb-3"><i class="fas fa-university text-purple-500 ml-1"></i> البنك</h3>
            <form method="POST" action="/investment/bank-deposit" class="flex flex-wrap gap-2">
                <input type="number" step="0.01" name="amount" placeholder="المبلغ" class="flex-1 min-w-[70px] border rounded-lg px-2 py-1.5 text-sm" required>
                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-1.5 rounded-lg text-sm transition">إيداع</button>
            </form>
        </div>
    </div>

    <!-- ===== الشركاء ===== -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
        <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex flex-wrap justify-between items-center">
            <h3 class="font-semibold text-gray-700 text-sm"><i class="fas fa-users text-blue-500 ml-2"></i> توزيع الأرباح على الشركاء</h3>
            <span class="text-xs text-gray-500">صافي الربح: <?php echo number_format($stats->net_profit ?? 0, 2); ?> ج.م</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-right text-xs text-gray-500">#</th>
                        <th class="px-3 py-2 text-right text-xs text-gray-500">اسم الشريك</th>
                        <th class="px-3 py-2 text-right text-xs text-gray-500">المساهمة</th>
                        <th class="px-3 py-2 text-right text-xs text-gray-500">نسبة الملكية</th>
                        <th class="px-3 py-2 text-right text-xs text-gray-500">ربح الشهر</th>
                        <th class="px-3 py-2 text-right text-xs text-gray-500">الدور</th>
                        <th class="px-3 py-2 text-right text-xs text-gray-500">الإجراء</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($partnersData as $id => $data): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2 text-center"><?php echo $i++; ?></td>
                            <td class="px-3 py-2 font-medium"><?php echo $data['name']; ?></td>
                            <td class="px-3 py-2"><?php echo number_format($data['contribution'], 2); ?></td>
                            <td class="px-3 py-2"><?php echo $data['percentage']; ?>%</td>
                            <td class="px-3 py-2 font-bold text-green-600">
                                <?php 
                                $share = 0;
                                foreach ($distributions as $d) {
                                    if ($d->name == $data['name']) { $share = $d->share; break; }
                                }
                                echo number_format($share, 2);
                                ?>
                            </td>
                            <td class="px-3 py-2">
                                <?php if ($data['is_manager']): ?>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-amber-100 text-amber-700">مدير</span>
                                <?php else: ?>
                                    <span class="text-gray-400 text-[10px]">شريك</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-3 py-2">
                                <button onclick="openEditModal(<?php echo $id; ?>)" class="text-blue-600 hover:text-blue-800 text-sm"><i class="fas fa-edit"></i></button>
                                <form action="/investment/partner/<?php echo $id; ?>" method="POST" class="inline-block" onsubmit="return confirm('حذف الشريك؟')">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <!-- إضافة شريك -->
        <div class="px-4 py-3 bg-gray-50 border-t border-gray-100">
            <form method="POST" action="/investment/partner" class="grid grid-cols-1 md:grid-cols-5 gap-2">
                <input type="text" name="name" placeholder="اسم الشريك" class="border rounded-lg px-3 py-1.5 text-sm" required>
                <input type="number" step="0.01" name="contribution" placeholder="المساهمة" class="border rounded-lg px-3 py-1.5 text-sm" required>
                <select name="is_manager" class="border rounded-lg px-3 py-1.5 text-sm"><option value="0">شريك</option><option value="1">مدير</option></select>
                <input type="number" step="0.01" name="monthly_salary" placeholder="الراتب" class="border rounded-lg px-3 py-1.5 text-sm" value="0">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-1.5 rounded-lg text-sm transition">إضافة</button>
            </form>
        </div>
    </div>
</div>

<!-- مودال التعديل -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl p-6 max-w-md w-full">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-user-edit text-blue-500 ml-2"></i> تعديل الشريك</h3>
            <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>
        <form id="editForm" method="POST">
            <input type="hidden" name="_method" value="PUT">
            <div class="mb-3"><label class="block text-sm font-medium text-gray-700">الاسم</label><input type="text" name="name" id="edit_name" class="w-full border rounded-lg px-3 py-2" required></div>
            <div class="mb-3"><label class="block text-sm font-medium text-gray-700">المساهمة</label><input type="number" step="0.01" name="contribution" id="edit_contribution" class="w-full border rounded-lg px-3 py-2" required></div>
            <div class="mb-3 flex items-center gap-2"><input type="checkbox" name="is_manager" id="edit_is_manager" class="w-4 h-4"><label class="text-sm text-gray-700">مدير</label></div>
            <div class="mb-3"><label class="block text-sm font-medium text-gray-700">الراتب الشهري</label><input type="number" step="0.01" name="monthly_salary" id="edit_salary" class="w-full border rounded-lg px-3 py-2"></div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition">تحديث</button>
        </form>
    </div>
</div>

<script>
    function openEditModal(id) {
        fetch('/investment/partner/' + id + '?edit=true')
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('edit_name').value = data.partner.name;
                    document.getElementById('edit_contribution').value = data.partner.contribution;
                    document.getElementById('edit_is_manager').checked = data.partner.is_manager;
                    document.getElementById('edit_salary').value = data.partner.monthly_salary || 0;
                    document.getElementById('editForm').action = '/investment/partner/' + id;
                    document.getElementById('editModal').classList.remove('hidden');
                }
            });
    }
    function closeEditModal() { document.getElementById('editModal').classList.add('hidden'); }
    document.getElementById('editModal').addEventListener('click', function(e) { if (e.target === this) closeEditModal(); });
</script>