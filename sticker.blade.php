<!DOCTYPE html>
<html>
<head>
    <style>
        body { margin: 0; padding: 0; background: white; }
        .sticker {
            width: 80mm;
            padding: 6px 8px;
            font-family: monospace;
            font-size: 10px;
            border: 1px dashed #ccc;
            margin: 0 auto;
        }
        .sticker .logo { font-weight: bold; font-size: 14px; color: #2563eb; }
        .sticker .row { display: flex; justify-content: space-between; padding: 2px 0; border-bottom: 1px dotted #eee; }
        .sticker .label { color: #64748b; }
        .sticker .value { font-weight: 600; }
        @media print { body { margin: 0; padding: 0; } .sticker { border: none; } }
    </style>
</head>
<body>
    <div class="sticker">
        <div class="logo">🔧 مركز الصيانة</div>
        <div class="row"><span class="label">التاريخ:</span><span class="value">{{ now()->format('Y-m-d') }}</span></div>
        <div class="row"><span class="label">التسليم المتوقع:</span><span class="value">{{ now()->addDays(3)->format('Y-m-d') }}</span></div>
        <div class="row"><span class="label">العميل:</span><span class="value">{{ $device->customer->name ?? '' }}</span></div>
        <div class="row"><span class="label">الجهاز:</span><span class="value">{{ $device->brand }} {{ $device->model }}</span></div>
        <div class="row"><span class="label">العطل:</span><span class="value">{{ Str::limit($device->reported_issue, 30) }}</span></div>
        <div class="row" style="border-bottom: none; margin-top: 4px; font-size: 8px; color: #94a3b8;">كود: {{ $device->device_code }}</div>
    </div>
    <div class="no-print text-center mt-2">
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded text-sm">🖨️ طباعة الملصق</button>
    </div>
</body>
</html>