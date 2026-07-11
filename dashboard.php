<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم الفني</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Tajawal', sans-serif; }
        body { background: #f0f4f8; }
        .sidebar { position: fixed; right: 0; top: 0; width: 220px; height: 100vh; background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%); z-index: 1000; overflow-y: auto; padding-bottom: 20px; }
        .sidebar a { display: flex; align-items: center; gap: 10px; color: #94a3b8; padding: 10px 18px; text-decoration: none; transition: 0.3s; border-right: 3px solid transparent; font-size: 14px; }
        .sidebar a:hover { background: rgba(255,255,255,0.05); color: white; border-right-color: #3b82f6; }
        .sidebar a.active { background: rgba(255,255,255,0.08); color: white; border-right-color: #3b82f6; }
        .sidebar .logo { padding: 16px; color: white; font-size: 18px; font-weight: 800; border-bottom: 1px solid rgba(255,255,255,0.06); text-align: center; }
        .main-content { margin-right: 220px; padding: 20px; background: #f1f5f9; min-height: 100vh; }
        .table-wrapper { overflow-x: auto; }
        .table-wrapper table { min-width: 1000px; }
        .table-row:hover { background: #f8fafc; }
        .status-badge { padding: 2px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; white-space: nowrap; }
        .status-pending { background: #fef3c7; color: #d97706; }
        .status-inspection { background: #dbeafe; color: #2563eb; }
        .status-suspended { background: #fee2e2; color: #dc2626; }
        .status-repairing { background: #fef3c7; color: #d97706; }
        .status-ready { background: #d1fae5; color: #059669; }
        .status-ready_for_pickup { background: #d1fae5; color: #059669; }
        .status-delivered { background: #e2e8f0; color: #64748b; }
        .status-cancelled { background: #fee2e2; color: #dc2626; }
        .btn { padding: 4px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; border: none; cursor: pointer; transition: 0.2s; white-space: nowrap; }
        .btn-yellow { background: #f59e0b; color: white; }
        .btn-yellow:hover { background: #d97706; }
        .btn-green { background: #22c55e; color: white; }
        .btn-green:hover { background: #16a34a; }
        .btn-amber { background: #f59e0b; color: white; }
        .btn-amber:hover { background: #d97706; }
        .btn-purple { background: #8b5cf6; color: white; }
        .btn-purple:hover { background: #7c3aed; }
        .btn-blue { background: #3b82f6; color: white; }
        .btn-blue:hover { background: #2563eb; }
        .input-part { border: 1px solid #e2e8f0; border-radius: 6px; padding: 2px 8px; font-size: 12px; width: 120px; }
        .input-part:focus { outline: none; border-color: #3b82f6; }
        @media (max-width: 768px) { .sidebar { display: none; } .main-content { margin-right: 0; } }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="logo">🔧 نظام الصيانة</div>
        <a href="/technician-dashboard" class="active">📋 أجهزتي</a>
        <a href="/chat">💬 الشات</a>
        <div style="border-top:1px solid rgba(255,255,255,0.06); margin:12px 16px 0;"></div>
        <div style="padding:12px 18px; color:#94a3b8; font-size:13px;">👤 <?php echo $_SESSION['full_name'] ?? 'فني'; ?><span style="font-size:10px; color:#64748b;">(فني)</span></div>
        <a href="/logout" style="color:#64748b; font-size:13px;">🚪 خروج</a>
    </div>

    <div class="main-content">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">🔧 لوحة تحكم الفني</h1>
                <p class="text-sm text-gray-500">مرحباً، <?php echo $technician['full_name'] ?? $_SESSION['full_name'] ?? 'فني'; ?> | <?php echo date('Y-m-d h:i A'); ?></p>
            </div>
            <a href="/" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition">← الرئيسية</a>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="table-wrapper">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">#</th>
                            <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">الكود</th>
                            <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">الجهاز</th>
                            <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">العميل</th>
                            <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">العطل</th>
                            <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">الحالة</th>
                            <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($devices)): ?>
                            <tr><td colspan="7" class="px-6 py-8 text-center text-gray-400">🎉 مفيش أجهزة حالياً</td></tr>
                        <?php else: ?>
                            <?php foreach ($devices as $device): ?>
                                <?php
                                $statusClass = 'status-' . ($device['status_slug'] ?? 'pending');
                                $statusText = $device['status_name'] ?? 'معلق';
                                $diagnosed = $device['diagnosed_issue'] ?? 'لم يتم التشخيص بعد';
                                ?>
                                <tr class="table-row transition">
                                    <td class="px-4 py-3 text-center"><?php echo $device['id']; ?></td>
                                    <td class="px-4 py-3 font-mono text-sm"><?php echo $device['device_code']; ?></td>
                                    <td class="px-4 py-3"><span class="font-medium"><?php echo $device['brand'] ?? '؟'; ?></span> <span class="text-gray-500 text-xs"><?php echo $device['model'] ?? ''; ?></span></td>
                                    <td class="px-4 py-3"><div class="font-medium"><?php echo $device['customer_name'] ?? '—'; ?></div><div class="text-xs text-gray-500"><?php echo $device['customer_phone'] ?? '—'; ?></div></td>
                                    <td class="px-4 py-3"><div class="text-sm truncate max-w-[120px]" title="<?php echo htmlspecialchars($device['reported_issue'] ?? ''); ?>"><?php echo $device['reported_issue'] ?? '—'; ?></div><div class="text-xs text-blue-600 truncate max-w-[120px]" title="<?php echo htmlspecialchars($diagnosed); ?>">التشخيص: <?php echo $diagnosed; ?></div></td>
                                    <td class="px-4 py-3"><span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span><?php if (!empty($device['waiting_for_part'])): ?><div class="text-xs text-amber-600 mt-1">⏳ <?php echo $device['waiting_for_part']; ?></div><?php endif; ?></td>
                                    <td class="px-4 py-3">
                                        <?php if ($device['status_slug'] == 'pending'): ?>
                                            <form method="POST" action="/technician/start-inspection" class="inline"><input type="hidden" name="device_id" value="<?php echo $device['id']; ?>"><button type="submit" class="btn btn-yellow">🔍 بدء الفحص</button></form>
                                        <?php elseif ($device['status_slug'] == 'inspection'): ?>
                                            <form method="POST" action="/technician/request-part" class="flex items-center gap-1"><input type="hidden" name="device_id" value="<?php echo $device['id']; ?>"><input type="text" name="part_name" placeholder="اسم القطعة..." class="input-part" required><button type="submit" class="btn btn-amber">⏳ طلب</button></form>
                                        <?php elseif ($device['status_slug'] == 'suspended'): ?>
                                            <span class="text-sm text-amber-600">⏳ في انتظار: <?php echo $device['waiting_for_part'] ?? ''; ?></span>
                                            <form method="POST" action="/technician/start-repair" class="inline"><input type="hidden" name="device_id" value="<?php echo $device['id']; ?>"><button type="submit" class="btn btn-blue">🔧 استئناف</button></form>
                                        <?php elseif ($device['status_slug'] == 'repairing'): ?>
                                            <form method="POST" action="/technician/complete-repair" class="inline"><input type="hidden" name="device_id" value="<?php echo $device['id']; ?>"><button type="submit" class="btn btn-green">✅ تم الإصلاح</button></form>
                                            <a href="/technician/checklist/<?php echo $device['id']; ?>" class="btn btn-purple inline-block mt-1"><i class="fas fa-clipboard-check"></i> فحص</a>
                                        <?php elseif ($device['status_slug'] == 'ready' || $device['status_slug'] == 'ready_for_pickup'): ?>
                                            <span class="text-sm text-green-600">✅ في انتظار المدير</span>
                                        <?php elseif ($device['status_slug'] == 'delivered'): ?>
                                            <span class="text-sm text-gray-400">✅ تم التسليم</span>
                                        <?php elseif ($device['status_slug'] == 'cancelled'): ?>
                                            <span class="text-sm text-red-600">❌ ملغي</span>
                                        <?php else: ?>
                                            <span class="text-sm text-gray-400">—</span>
                                        <?php endif; ?>
                                        <div class="mt-1"><a href="/devices/<?php echo $device['id']; ?>" class="text-blue-600 hover:text-blue-800 text-xs"><i class="fas fa-eye"></i> عرض</a></div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4 text-center text-sm text-gray-400">💡 اضغط "بدء الفحص" لتشخيص العطل، أو "طلب قطع غيار" إذا كنت محتاج قطعة، أو "تم الإصلاح" بعد الانتهاء</div>
    </div>
</body>
</html>