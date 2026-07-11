<div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-robot text-blue-500 ml-2"></i> 
            تحليل العطل الذكي
        </h1>
        <a href="/devices/<?php echo $device['id']; ?>" class="text-blue-600 hover:text-blue-800 text-sm">
            <i class="fas fa-arrow-right"></i> العودة للجهاز
        </a>
    </div>

    <!-- ===== معلومات الجهاز ===== -->
    <div class="bg-gray-50 p-4 rounded-lg mb-6 border-r-4 border-blue-500">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div>
                <span class="text-gray-500">الكود:</span>
                <strong class="font-mono"><?php echo $device['device_code']; ?></strong>
            </div>
            <div>
                <span class="text-gray-500">الجهاز:</span>
                <strong><?php echo $device['brand'] . ' ' . $device['model']; ?></strong>
            </div>
            <div>
                <span class="text-gray-500">العميل:</span>
                <strong><?php echo $device['customer_name']; ?></strong>
            </div>
            <div>
                <span class="text-gray-500">الحالة:</span>
                <span class="px-2 py-1 rounded-full text-xs font-medium 
                    <?php echo ($device['status_slug'] ?? '') == 'suspended' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700'; ?>">
                    <?php echo $device['status_name'] ?? 'معلق'; ?>
                </span>
            </div>
        </div>
    </div>

    <!-- ===== العطل المبلغ عنه ===== -->
    <div class="bg-yellow-50 p-4 rounded-lg mb-6 border-r-4 border-yellow-500">
        <h3 class="font-semibold text-yellow-800 mb-2">📋 العطل المبلغ عنه</h3>
        <p class="text-gray-700 text-lg">"<?php echo htmlspecialchars($device['reported_issue']); ?>"</p>
    </div>

    <!-- ===== نتيجة التحليل ===== -->
    <?php if (isset($partInfo) && $partInfo): ?>
        <div class="bg-white border rounded-lg overflow-hidden mb-6">
            <div class="bg-gray-50 px-4 py-3 border-b">
                <h3 class="font-semibold text-gray-700">
                    <i class="fas fa-microchip text-blue-500"></i> نتيجة التحليل
                </h3>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-400">الكلمة المفتاحية</p>
                        <p class="font-bold text-blue-600"><?php echo $partInfo['keyword'] ?? '—'; ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">الفئة</p>
                        <p class="font-bold"><?php echo $partInfo['category'] ?? '—'; ?></p>
                    </div>
                    <?php if (isset($partInfo['name']) && $partInfo['name']): ?>
                        <div>
                            <p class="text-xs text-gray-400">القطعة في المخزون</p>
                            <p class="font-bold text-green-600"><?php echo $partInfo['name']; ?></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">الكمية</p>
                            <p class="font-bold <?php echo ($partInfo['quantity'] ?? 0) > 0 ? 'text-green-600' : 'text-red-600'; ?>">
                                <?php echo $partInfo['quantity'] ?? 0; ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- ===== حالة التوفر ===== -->
                <div class="mt-4 p-4 rounded-lg <?php echo ($partInfo['is_available'] ?? false) ? 'bg-green-50 border-r-4 border-green-500' : 'bg-red-50 border-r-4 border-red-500'; ?>">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl"><?php echo ($partInfo['is_available'] ?? false) ? '✅' : '⏳'; ?></span>
                        <div>
                            <p class="font-bold <?php echo ($partInfo['is_available'] ?? false) ? 'text-green-700' : 'text-red-700'; ?>">
                                <?php echo ($partInfo['is_available'] ?? false) ? 'القطعة متوفرة في المخزون' : 'القطعة غير متوفرة - سيتم تعليق الجهاز'; ?>
                            </p>
                            <?php if (!($partInfo['is_available'] ?? false)): ?>
                                <p class="text-sm text-red-600">
                                    القطعة المطلوبة: <strong><?php echo ($partInfo['keyword'] ?? '') . ' (' . ($partInfo['category'] ?? '') . ')'; ?></strong>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== الإجراء المتخذ ===== -->
        <div class="bg-blue-50 p-4 rounded-lg border-r-4 border-blue-500 mb-6">
            <h3 class="font-semibold text-blue-800 mb-2">⚡ الإجراء المتخذ</h3>
            <p class="text-gray-700">
                <?php if ($partInfo['is_available'] ?? false): ?>
                    ✅ تم تغيير حالة الجهاز إلى <strong>"معلق"</strong> وسيبدأ الفني في الصيانة قريباً.
                <?php else: ?>
                    ⏳ تم تغيير حالة الجهاز إلى <strong>"في انتظار قطعة غيار"</strong>.
                    سيتم إشعارك تلقائياً عند توفر القطعة في المخزون.
                <?php endif; ?>
            </p>
        </div>

    <?php else: ?>
        <!-- ===== لو مفيش قطعة مطلوبة ===== -->
        <div class="bg-green-50 p-6 rounded-lg border-r-4 border-green-500 mb-6">
            <div class="flex items-center gap-3">
                <span class="text-3xl">🔧</span>
                <div>
                    <h3 class="font-bold text-green-800">العطل لا يحتاج قطعة غيار</h3>
                    <p class="text-gray-700">الجهاز جاهز للفحص المبدئي من قبل الفني.</p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- ===== أزرار إضافية ===== -->
    <div class="flex gap-3 flex-wrap">
        <a href="/devices/<?php echo $device['id']; ?>" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg text-sm transition">
            <i class="fas fa-arrow-right"></i> العودة للجهاز
        </a>
        <?php if (!isset($partInfo) || !($partInfo['is_available'] ?? false)): ?>
            <a href="/inventory/create" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm transition">
                <i class="fas fa-box"></i> إضافة قطعة للمخزون
            </a>
        <?php endif; ?>
        <button onclick="window.location.reload()" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg text-sm transition">
            <i class="fas fa-sync"></i> إعادة التحليل
        </button>
    </div>

    <div class="mt-6 text-center text-xs text-gray-400 border-t border-gray-200 pt-4">
        <i class="fas fa-info-circle"></i>
        النظام يحلل العطل تلقائياً ويبحث عن القطعة المناسبة في المخزون.
    </div>
</div>