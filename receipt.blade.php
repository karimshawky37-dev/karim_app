<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>إيصال استلام جهاز</title>
    <style>
        body { font-family: 'Tajawal', sans-serif; padding: 20px; background: white; color: #1e293b; }
        .receipt { max-width: 210mm; margin: 0 auto; border: 1px solid #e2e8f0; padding: 20px; border-radius: 8px; }
        .header { text-align: center; border-bottom: 2px solid #1e293b; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { font-size: 24px; font-weight: 800; }
        .header h1 span { color: #2563eb; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; background: #f8fafc; padding: 12px; border-radius: 6px; margin-bottom: 16px; }
        .info-grid .label { color: #64748b; font-size: 12px; }
        .info-grid .value { font-weight: 700; }
        .checklist { margin: 16px 0; }
        .checklist table { width: 100%; border-collapse: collapse; }
        .checklist td { padding: 4px 8px; border-bottom: 1px dashed #e2e8f0; }
        .checklist .status { font-weight: 700; }
        .checklist .ok { color: #16a34a; }
        .checklist .fail { color: #dc2626; }
        .waiver { margin-top: 20px; padding: 12px; background: #fef3c7; border-radius: 6px; font-size: 12px; color: #78350f; }
        .signature { display: flex; justify-content: space-between; margin-top: 30px; padding-top: 16px; border-top: 2px dashed #e2e8f0; }
        .signature .box { width: 45%; text-align: center; }
        .signature .box .line { border-bottom: 2px solid #1e293b; height: 40px; margin-top: 8px; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #94a3b8; }
        @media print { body { padding: 0; } .receipt { border: none; border-radius: 0; padding: 10px; } }
    </style>
</head>
<body>
    <div class="receipt print-area">
        <div class="header">
            <h1>نظام <span>الصيانة</span></h1>
            <p>مركز صيانة المحمول المتكامل</p>
            <p class="text-sm text-slate-500">إيصال استلام جهاز</p>
        </div>

        <div class="info-grid">
            <div><span class="label">كود الجهاز</span><div class="value">{{ $device->device_code }}</div></div>
            <div><span class="label">التاريخ</span><div class="value">{{ now()->format('Y-m-d h:i A') }}</div></div>
            <div><span class="label">العميل</span><div class="value">{{ $device->customer->name ?? '' }}</div></div>
            <div><span class="label">الهاتف</span><div class="value">{{ $device->customer->phone ?? '' }}</div></div>
            <div><span class="label">الجهاز</span><div class="value">{{ $device->brand }} {{ $device->model }}</div></div>
            <div><span class="label">اللون/السعة</span><div class="value">{{ $device->color ?? '' }} - {{ $device->storage_capacity ?? '' }}</div></div>
            <div class="col-span-2"><span class="label">العطل المبلغ عنه</span><div class="value">{{ $device->reported_issue }}</div></div>
        </div>

        <div class="checklist">
            <h3 class="text-md font-semibold mb-2">📋 فحص ما قبل الاستلام</h3>
            <table>
                <tr><td>الشاشة</td><td class="status ok">✅ سليمة</td></tr>
                <tr><td>الشبكة</td><td class="status ok">✅ شغالة</td></tr>
                <tr><td>السماعة</td><td class="status ok">✅ شغالة</td></tr>
                <tr><td>الميكرفون</td><td class="status ok">✅ شغال</td></tr>
                <tr><td>خدوش الجسم</td><td class="status ok">✅ لا توجد</td></tr>
                <tr><td>الشحن</td><td class="status ok">✅ شغال</td></tr>
            </table>
        </div>

        <div class="waiver">
            <p><strong>إخلاء مسؤولية:</strong> يقر العميل بأن الجهاز تم استلامه بالحالة المذكورة أعلاه، وأن المركز غير مسؤول عن أي بيانات أو ملفات موجودة على الجهاز. كما يقر بأن مدة الصيانة تقدر بـ ... يوم عمل، وقد تختلف حسب توفر قطع الغيار.</p>
        </div>

        <div class="signature">
            <div class="box">
                <div class="label">توقيع العميل</div>
                <div class="line"></div>
            </div>
            <div class="box">
                <div class="label">توقيع المسؤول</div>
                <div class="line"></div>
            </div>
        </div>

        <div class="footer">
            <p>شكراً لثقتكم بنا</p>
            <p>© {{ date('Y') }} نظام الصيانة</p>
        </div>
    </div>

    <div class="no-print text-center mt-4">
        <button onclick="window.print()" class="bg-blue-600 text-white px-6 py-2 rounded-lg">🖨️ طباعة</button>
        <a href="{{ route('devices.index') }}" class="bg-slate-600 text-white px-6 py-2 rounded-lg">← العودة</a>
    </div>
</body>
</html>