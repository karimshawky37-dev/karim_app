<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-calendar-alt text-blue-500"></i> تقويم الحضور</h1>
        <div class="flex gap-2">
            <a href="/attendance" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition">← العودة</a>
        </div>
    </div>

    <!-- التنقل بين الأشهر -->
    <div class="flex justify-between items-center mb-4">
        <a href="?year=<?php echo ($month == 1 ? $year - 1 : $year); ?>&month=<?php echo ($month == 1 ? 12 : $month - 1); ?>" 
           class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg text-sm transition">
            <i class="fas fa-chevron-right"></i> السابق
        </a>
        <h2 class="text-xl font-bold text-gray-800">
            <?php echo date('F Y', strtotime("$year-$month-01")); ?>
        </h2>
        <a href="?year=<?php echo ($month == 12 ? $year + 1 : $year); ?>&month=<?php echo ($month == 12 ? 1 : $month + 1); ?>" 
           class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg text-sm transition">
            التالي <i class="fas fa-chevron-left"></i>
        </a>
    </div>

    <!-- التقويم -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-4 py-2 text-center text-xs text-gray-500 font-medium">الأحد</th>
                    <th class="px-4 py-2 text-center text-xs text-gray-500 font-medium">الإثنين</th>
                    <th class="px-4 py-2 text-center text-xs text-gray-500 font-medium">الثلاثاء</th>
                    <th class="px-4 py-2 text-center text-xs text-gray-500 font-medium">الأربعاء</th>
                    <th class="px-4 py-2 text-center text-xs text-gray-500 font-medium">الخميس</th>
                    <th class="px-4 py-2 text-center text-xs text-gray-500 font-medium">الجمعة</th>
                    <th class="px-4 py-2 text-center text-xs text-gray-500 font-medium">السبت</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $firstDay = date('N', strtotime("$year-$month-01"));
                $daysInMonth = date('t', strtotime("$year-$month-01"));
                $today = date('Y-m-d');
                $currentDay = 1;
                
                // بناء مصفوفة الحضور لكل يوم
                $attendanceMap = [];
                foreach ($records as $r) {
                    if ($r['day']) {
                        $attendanceMap[$r['user_id']][$r['day']] = $r['status'] ?? 'absent';
                    }
                }
                ?>
                <?php for ($row = 0; $row < ceil(($daysInMonth + $firstDay - 1) / 7); $row++): ?>
                    <tr>
                        <?php for ($col = 0; $col < 7; $col++): ?>
                            <?php
                            $dayNumber = ($row * 7 + $col) - ($firstDay - 1);
                            $isCurrentMonth = ($dayNumber >= 1 && $dayNumber <= $daysInMonth);
                            $date = $isCurrentMonth ? "$year-$month-" . str_pad($dayNumber, 2, '0', STR_PAD_LEFT) : '';
                            $isToday = ($date == $today);
                            ?>
                            <td class="px-2 py-3 text-center align-top <?php echo $isToday ? 'bg-blue-50' : ''; ?>" style="min-width: 80px; height: 60px;">
                                <?php if ($isCurrentMonth): ?>
                                    <div class="font-medium <?php echo $isToday ? 'text-blue-600' : 'text-gray-700'; ?>">
                                        <?php echo $dayNumber; ?>
                                    </div>
                                    <div class="mt-1">
                                        <?php
                                        // عرض حالة الحضور لهذا اليوم
                                        $statuses = [];
                                        foreach ($records as $r) {
                                            if ($r['day'] == $dayNumber && $r['status']) {
                                                $statuses[] = $r['status'];
                                            }
                                        }
                                        if (empty($statuses)) {
                                            echo '<span class="text-xs text-gray-300">—</span>';
                                        } else {
                                            $uniqueStatuses = array_unique($statuses);
                                            $colors = [
                                                'present' => 'bg-green-500',
                                                'absent' => 'bg-red-500',
                                                'late' => 'bg-amber-500',
                                                'half_day' => 'bg-purple-500',
                                                'holiday' => 'bg-blue-500'
                                            ];
                                            foreach ($uniqueStatuses as $s) {
                                                $color = $colors[$s] ?? 'bg-gray-400';
                                                echo '<span class="inline-block w-2 h-2 rounded-full ' . $color . ' mx-0.5" title="' . $s . '"></span>';
                                            }
                                        }
                                        ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-gray-300"><?php echo $dayNumber > 0 ? $dayNumber : ''; ?></span>
                                <?php endif; ?>
                            </td>
                        <?php endfor; ?>
                    </tr>
                <?php endfor; ?>
            </tbody>
        </table>
    </div>
</div>