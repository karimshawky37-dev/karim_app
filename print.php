<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الفحص النهائي - <?php echo $device['device_code']; ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Tajawal', 'Arial', sans-serif;
            background: white;
            padding: 20px;
            direction: rtl;
        }
        .print-container {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #1e293b;
            border-radius: 12px;
            padding: 30px;
            background: white;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #1e293b;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            color: #0f172a;
        }
        .header h1 span { color: #2563eb; }
        .header p {
            color: #64748b;
            font-size: 14px;
            margin-top: 4px;
        }
        .sub-header {
            display: flex;
            justify-content: space-between;
            background: #f8fafc;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
        }
        .sub-header .item .label {
            font-size: 11px;
            color: #94a3b8;
            text-transform: uppercase;
        }
        .sub-header .item .value {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
        }
        .checklist-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px 20px;
            background: #f8fafc;
            padding: 16px 20px;
            border-radius: 8px;
            margin: 16px 0;
        }
        .checklist-grid .check-item {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            border-bottom: 1px dashed #e2e8f0;
            font-size: 14px;
        }
        .checklist-grid .check-item .label {
            color: #475569;
        }
        .checklist-grid .check-item .value {
            font-weight: 600;
            color: #0f172a;
        }
        .checklist-grid .check-item .value.pass { color: #16a34a; }
        .checklist-grid .check-item .value.fail { color: #dc2626; }
        
        .notes-section {
            background: #f8fafc;
            padding: 12px 16px;
            border-radius: 8px;
            margin: 12px 0;
        }
        .notes-section .label {
            font-size: 11px;
            color: #94a3b8;
            text-transform: uppercase;
        }
        .notes-section .text {
            font-size: 14px;
            color: #1e293b;
            margin-top: 2px;
        }
        
        .signature-area {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid #e2e8f0;
        }
        .signature-area .sign-box {
            text-align: center;
            width: 45%;
        }
        .signature-area .sign-box .line {
            border-bottom: 2px solid #1e293b;
            height: 40px;
            margin-top: 8px;
        }
        .signature-area .sign-box .label {
            font-size: 12px;
            color: #94a3b8;
        }
        
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            color: #94a3b8;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 16px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 700;
            background: #dcfce7;
            color: #16a34a;
        }
        .status-badge.delivered {
            background: #e2e8f0;
            color: #475569;
        }
        
        @media print {
            body { padding: 0; }
            .print-container { border: none; border-radius: 0; padding: 20px; }
            .no-print { display: none !important; }
        }
        @media (max-width: 600px) {
            .checklist-grid { grid-template-columns: 1fr; }
            .sub-header { flex-direction: column; gap: 4px; }
            .signature-area { flex-direction: column; gap: 20px; align-items: center; }
            .signature-area .sign-box { width: 100%; }
        }
    </style>
</head>
<body>
    <div class="print-container">
        <!-- ===== HEADER ===== -->
        <div class="header">
            <h1>نظام <span>الصيانة</span></h1>
            <p>مركز صيانة المحمول المتكامل</p>
            <div style="margin-top:8px;">
                <span class="status-badge <?php echo $device['status_slug'] == 'delivered' ? 'delivered' : ''; ?>">
                    <?php echo $device['status_name']; ?>
                </span>
            </div>
        </div>

        <!-- ===== معلومات الجهاز ===== -->
        <div class="sub-header">
            <div class="item">
                <div class="label">الجهاز</div>
                <div class="value"><?php echo $device['brand']; ?> <?php echo $device['model']; ?></div>
            </div>
            <div class="item">
                <div class="label">الكود</div>
                <div class="value"><?php echo $device['device_code']; ?></div>
            </div>
            <div class="item">
                <div class="label">العميل</div>
                <div class="value"><?php echo $device['customer_name']; ?></div>
            </div>
            <div class="item">
                <div class="label">التاريخ</div>
                <div class="value"><?php echo date('Y-m-d'); ?></div>
            </div>
        </div>

        <!-- ===== الفحص النهائي ===== -->
        <h2 style="font-size:16px; color:#0f172a; margin-bottom:8px;">📋 الفحص النهائي للتسليم</h2>
        
        <div class="checklist-grid">
            <div class="check-item">
                <span class="label">الواي فاي</span>
                <span class="value <?php echo ($checklist && $checklist['wifi_working']) ? 'pass' : 'fail'; ?>">
                    <?php echo ($checklist && $checklist['wifi_working']) ? '✅ يعمل' : '❌ لا يعمل'; ?>
                </span>
            </div>
            <div class="check-item">
                <span class="label">البلوتوث</span>
                <span class="value <?php echo ($checklist && $checklist['bluetooth_working']) ? 'pass' : 'fail'; ?>">
                    <?php echo ($checklist && $checklist['bluetooth_working']) ? '✅ يعمل' : '❌ لا يعمل'; ?>
                </span>
            </div>
            <div class="check-item">
                <span class="label">الشبكة</span>
                <span class="value <?php echo ($checklist && $checklist['network_working']) ? 'pass' : 'fail'; ?>">
                    <?php echo ($checklist && $checklist['network_working']) ? '✅ يعمل' : '❌ لا يعمل'; ?>
                </span>
            </div>
            <div class="check-item">
                <span class="label">الكاميرا</span>
                <span class="value <?php echo ($checklist && $checklist['camera_working']) ? 'pass' : 'fail'; ?>">
                    <?php echo ($checklist && $checklist['camera_working']) ? '✅ تعمل' : '❌ لا تعمل'; ?>
                </span>
            </div>
            <div class="check-item">
                <span class="label">البصمة</span>
                <span class="value <?php echo ($checklist && $checklist['fingerprint_working']) ? 'pass' : 'fail'; ?>">
                    <?php echo ($checklist && $checklist['fingerprint_working']) ? '✅ تعمل' : '❌ لا تعمل'; ?>
                </span>
            </div>
            <div class="check-item">
                <span class="label">الصوت</span>
                <span class="value <?php echo ($checklist && $checklist['audio_working']) ? 'pass' : 'fail'; ?>">
                    <?php echo ($checklist && $checklist['audio_working']) ? '✅ يعمل' : '❌ لا يعمل'; ?>
                </span>
            </div>
            <div class="check-item">
                <span class="label">الشحن</span>
                <span class="value <?php echo ($checklist && $checklist['charging_working']) ? 'pass' : 'fail'; ?>">
                    <?php echo ($checklist && $checklist['charging_working']) ? '✅ يعمل' : '❌ لا يعمل'; ?>
                </span>
            </div>
            <div class="check-item">
                <span class="label">الشاشة</span>
                <span class="value">
                    <?php 
                        $screenStatus = [
                            'good' => '✅ سليمة',
                            'scratched' => '⚠️ مخدوشة',
                            'cracked' => '⚠️ مشرخة',
                            'broken' => '❌ مكسورة'
                        ];
                        echo $screenStatus[$checklist['screen_condition'] ?? 'good'] ?? '✅ سليمة';
                    ?>
                </span>
            </div>
        </div>

        <!-- ===== الملاحظات ===== -->
        <?php if ($checklist && $checklist['check_notes']): ?>
        <div class="notes-section">
            <div class="label">ملاحظات التسليم</div>
            <div class="text"><?php echo $checklist['check_notes']; ?></div>
        </div>
        <?php endif; ?>

        <!-- ===== التوقيعات ===== -->
        <div class="signature-area">
            <div class="sign-box">
                <div class="label">توقيع المستلم</div>
                <div class="line">
                    <?php if ($checklist && $checklist['repair_notes']): ?>
                        <span style="font-size:16px; font-weight:600;"><?php echo $checklist['repair_notes']; ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="sign-box">
                <div class="label">توقيع المسؤول</div>
                <div class="line"></div>
            </div>
        </div>

        <!-- ===== FOOTER ===== -->
        <div class="footer">
            <p>تم تسليم الجهاز بحالة جيدة بعد الفحص النهائي</p>
            <p style="margin-top:4px;">© <?php echo date('Y'); ?> نظام إدارة وصيانة المحمول</p>
        </div>

        <!-- ===== زر الطباعة ===== -->
        <div style="text-align:center; margin-top:20px;" class="no-print">
            <button onclick="window.print()" style="background:#2563eb; color:white; border:none; padding:10px 30px; border-radius:8px; font-size:16px; cursor:pointer;">
                🖨️ طباعة الفحص
            </button>
            <a href="/devices/<?php echo $device['id']; ?>" style="display:inline-block; margin-right:12px; background:#64748b; color:white; padding:10px 30px; border-radius:8px; text-decoration:none; font-size:16px;">
                ← العودة
            </a>
            <a href="/checklist/whatsapp/<?php echo $device['id']; ?>" target="_blank" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition inline-block">
    <i class="fab fa-whatsapp"></i> إرسال الفحص عبر واتساب
</a>
        </div>
    </div>
</body>
</html>