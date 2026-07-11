<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-clipboard-list text-blue-500"></i> جرد المخزون</h1>
        <a href="/inventory" class="text-blue-600 hover:text-blue-800 text-sm"><i class="fas fa-arrow-left"></i> العودة</a>
    </div>

    <div class="bg-amber-50 border-r-4 border-amber-500 p-3 rounded-lg mb-4">
        <i class="fas fa-info-circle text-amber-500"></i>
        <span class="text-sm text-amber-700">أدخل الكمية الفعلية لكل صنف بعد عدّه يدوياً، ثم اضغط "تحديث الجرد".</span>
    </div>

    <form method="POST" action="/inventory/count-update">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">#</th>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">الاسم</th>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">الفئة</th>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">المسجل</th>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">الفعلي (عدّ)</th>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">الفرق</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($items as $item): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2"><?php echo $i++; ?></td>
                            <td class="px-4 py-2 font-medium"><?php echo $item['name']; ?></td>
                            <td class="px-4 py-2 text-sm text-gray-500"><?php echo $item['category']; ?></td>
                            <td class="px-4 py-2 font-bold text-blue-600"><?php echo $item['current_quantity']; ?></td>
                            <td class="px-4 py-2">
                                <input type="number" name="count[<?php echo $item['id']; ?>]" 
                                       value="<?php echo $item['current_quantity']; ?>"
                                       class="w-20 border rounded-lg px-2 py-1 text-center">
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-400" id="diff-<?php echo $item['id']; ?>">0</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700">ملاحظات الجرد</label>
            <input type="text" name="notes" placeholder="مثال: جرد شهر يونيو" class="w-full border rounded-lg px-3 py-2">
        </div>

        <div class="mt-4 flex gap-3">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                <i class="fas fa-save"></i> تحديث الجرد
            </button>
            <button type="button" onclick="window.location.reload()" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg transition">
                إعادة تعيين
            </button>
        </div>
    </form>
</div>

<script>
    document.querySelectorAll('input[name^="count"]').forEach(input => {
        input.addEventListener('input', function() {
            const id = this.name.match(/\d+/)[0];
            const oldVal = parseInt(this.closest('tr').querySelector('td:nth-child(4)').textContent) || 0;
            const newVal = parseInt(this.value) || 0;
            const diff = newVal - oldVal;
            document.getElementById('diff-' + id).textContent = diff;
            document.getElementById('diff-' + id).style.color = diff === 0 ? '#94a3b8' : (diff > 0 ? '#22c55e' : '#ef4444');
        });
    });
</script>