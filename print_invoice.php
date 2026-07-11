<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة - <?php echo $sale['invoice_number']; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Tajawal', 'Segoe UI', Arial, sans-serif;
            background: #f0f2f5;
            padding: 20px;
            direction: rtl;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        .controls {
            max-width: 80mm;
            width: 100%;
            margin-bottom: 12px;
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            justify-content: center;
            background: #fff;
            padding: 8px 10px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .controls label {
            font-size: 11px;
            color: #475569;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .controls select {
            padding: 3px 6px;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            font-size: 11px;
            background: #fff;
        }
        .controls button,
        .controls a {
            padding: 5px 12px;
            border: none;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: 0.2s;
        }
        .controls .btn-print {
            background: #22c55e;
            color: #fff;
        }
        .controls .btn-print:hover {
            background: #16a34a;
        }
        .controls .btn-back {
            background: #64748b;
            color: #fff;
        }
        .controls .btn-back:hover {
            background: #475569;
        }

        .invoice-container {
            max-width: 80mm;
            width: 100%;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 6px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.05);
            font-size: 9px;
            padding: 6px 6px 10px 6px;
        }

        .header {
            text-align: center;
            border-bottom: 2px dashed #1e293b;
            padding-bottom: 5px;
            margin-bottom: 6px;
        }
        .header img {
            max-width: 250px;
            height: auto;
            margin-bottom: 2px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .header h1 {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
        }
        .header h1 span {
            color: #2563eb;
        }
        .header .sub {
            font-size: 8px;
            color: #64748b;
            margin-top: 1px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2px 8px;
            background: #f8fafc;
            padding: 4px 6px;
            border-radius: 4px;
            margin-bottom: 5px;
            border-right: 3px solid #2563eb;
        }
        .info-grid .item .label {
            font-size: 7px;
            color: #94a3b8;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.3px;
        }
        .info-grid .item .value {
            font-weight: 700;
            color: #0f172a;
            font-size: 9px;
        }
        .status-badge {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 8px;
            font-size: 7px;
            font-weight: 700;
        }
        .status-badge.completed {
            background: #dcfce7;
            color: #16a34a;
        }
        .status-badge.pending {
            background: #fef3c7;
            color: #d97706;
        }
        .status-badge.partially {
            background: #dbeafe;
            color: #2563eb;
        }

        table.invoice-items {
            width: 100%;
            border-collapse: collapse;
            margin: 4px 0;
            font-size: 8px;
            border: 1px solid #e2e8f0;
        }
        table.invoice-items thead th {
            padding: 4px 3px;
            text-align: center;
            font-size: 7px;
            font-weight: 700;
            color: #1e293b;
            background: #f1f5f9;
            border-bottom: 2px solid #1e293b;
            border-left: 1px solid #e2e8f0;
            text-transform: uppercase;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        table.invoice-items thead th:last-child {
            border-left: none;
        }
        table.invoice-items tbody td {
            padding: 4px 3px;
            text-align: center;
            border-bottom: 1px dashed #e2e8f0;
            border-left: 1px solid #e2e8f0;
            vertical-align: middle;
            font-size: 8px;
        }
        table.invoice-items tbody td:last-child {
            border-left: none;
        }
        table.invoice-items tbody tr:last-child td {
            border-bottom: none;
        }
        .desc-cell {
            text-align: right;
            font-size: 8px;
            font-weight: 500;
            word-break: break-word;
            white-space: normal;
            padding-right: 4px;
        }
        .type-tag {
            font-size: 5px;
            padding: 1px 4px;
            border-radius: 2px;
            font-weight: 700;
            display: inline-block;
            margin-right: 2px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .type-tag.part {
            background: #dbeafe;
            color: #2563eb;
        }
        .type-tag.service {
            background: #f3e8ff;
            color: #7c3aed;
        }

        .totals {
            margin-top: 6px;
            padding-top: 4px;
            border-top: 2px dashed #cbd5e1;
        }
        .totals .row {
            display: flex;
            justify-content: space-between;
            padding: 2px 0;
            font-size: 9px;
        }
        .totals .row .lbl {
            color: #64748b;
            font-weight: 500;
        }
        .totals .row .val {
            font-weight: 700;
        }
        .totals .row.grand-total {
            font-size: 12px;
            font-weight: 800;
            border-top: 2px solid #1e293b;
            border-bottom: 2px solid #1e293b;
            padding: 4px 0;
            margin: 3px 0;
        }
        .totals .val.paid {
            color: #16a34a;
        }
        .totals .val.remaining {
            color: #dc2626;
        }
        .totals .val.settled {
            color: #16a34a;
        }

        .notes-box {
            margin-top: 5px;
            padding: 4px 6px;
            background: #f8fafc;
            border-radius: 3px;
            font-size: 7px;
            border-right: 2px solid #94a3b8;
        }
        .notes-box .lbl {
            font-size: 6px;
            color: #94a3b8;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 2px;
        }
        .notes-box div:last-child {
            font-size: 8px;
            color: #1e293b;
        }

        .footer {
            text-align: center;
            margin-top: 8px;
            padding-top: 5px;
            border-top: 2px dashed #e2e8f0;
            font-size: 7px;
            color: #94a3b8;
        }
        .footer .thanks {
            font-size: 11px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 2px;
        }
        .footer .phone {
            font-size: 7px;
            color: #64748b;
            margin-top: 1px;
        }

        .qr-section {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 8px;
            padding-top: 6px;
            border-top: 2px dashed #e2e8f0;
        }
        .qr-item {
            text-align: center;
        }
        .qr-item img {
            width: 50px;
            height: 50px;
            display: block;
            margin: 0 auto 2px auto;
        }
        .qr-item .label {
            font-size: 7px;
            color: #475569;
            font-weight: 600;
        }

        .size-58mm {
            max-width: 58mm;
        }
        .size-80mm {
            max-width: 80mm;
        }
        .size-100mm {
            max-width: 100mm;
        }
        .size-full {
            max-width: 100%;
        }

        @media print {
            body * {
                visibility: hidden !important;
            }
            .invoice-container,
            .invoice-container * {
                visibility: visible !important;
            }
            body {
                background: #fff !important;
                padding: 0 !important;
                margin: 0 !important;
                display: flex !important;
                justify-content: center !important;
                align-items: center !important;
                min-height: 100vh !important;
            }
            .invoice-container {
                max-width: 80mm !important;
                width: 100% !important;
                margin: 0 auto !important;
                padding: 4px 4px 8px 4px !important;
                border: none !important;
                border-radius: 0 !important;
                box-shadow: none !important;
                background: #fff !important;
                page-break-inside: avoid !important;
                page-break-after: avoid !important;
            }
            .controls,
            .controls * {
                display: none !important;
                visibility: hidden !important;
            }
            @page {
                size: 80mm auto !important;
                margin: 0mm !important;
                padding: 0mm !important;
            }
            html,
            body {
                margin: 0 !important;
                padding: 0 !important;
                background: #fff !important;
            }
            table.invoice-items thead th {
                background: #f1f5f9 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .status-badge {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .info-grid {
                background: #f8fafc !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .type-tag {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .qr-item img {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .header img {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>
<body>

    <div class="controls">
        <label>
            📏 المقاس:
            <select id="sizeSelector" onchange="changeSize()">
                <option value="size-58mm">58mm</option>
                <option value="size-80mm" selected>80mm (ورقة فاتورة)</option>
                <option value="size-100mm">100mm</option>
                <option value="size-full">كامل الشاشة</option>
            </select>
        </label>
        <button class="btn-print" onclick="window.print()">🖨️ طباعة</button>
        <a href="/sales/view/<?php echo $sale['id']; ?>" class="btn-back">← العودة</a>
    </div>

    <div class="invoice-container size-80mm" id="invoiceContainer">

        <!-- ===== الهيدر مع اللوجو (Base64) ===== -->
        <div class="header">
            <!-- ✅ ضع الكود Base64 هنا بدلاً من <?php echo $logoImg; ?> -->
<img src="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEASABIAAD//gA8Q1JFQVRPUjogZ2QtanBlZyB2MS4wICh1c2luZyBJSkcgSlBFRyB2ODApLCBxdWFsaXR5ID0gMTAwCv/bAEMAAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAf/bAEMBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAf/AABEIBQAFAAMBIgACEQEDEQH/xAAfAAABBQEBAQEBAQAAAAAAAAAAAQIDBAUGBwgJCgv/xAC1EAACAQMDAgQDBQUEBAAAAX0BAgMABBEFEiExQQYTUWEHInEUMoGRoQgjQrHBFVLR8CQzYnKCCQoWFxgZGiUmJygpKjQ1Njc4OTpDREVGR0hJSlNUVVZXWFlaY2RlZmdoaWpzdHV2d3h5eoOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4eLj5OXm5+jp6vHy8/T19vf4+fr/xAAfAQADAQEBAQEBAQEBAAAAAAAAAQIDBAUGBwgJCgv/xAC1EQACAQIEBAMEBwUEBAABAncAAQIDEQQFITEGEkFRB2FxEyIygQgUQpGhscEJIzNS8BVictEKFiQ04SXxFxgZGiYnKCkqNTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqCg4SFhoeIiYqSk5SVlpeYmZqio6Slpqeoqaqys7S1tre4ubrCw8TFxsfIycrS09TV1tfY2dri4+Tl5ufo6ery8/T19vf4+fr/2gAMAwEAAhEDEQA/AP7+KKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooyD0OaTI6ZGfTNAC0UUUAFFFGR6/5/yR+dABRRRQAUUZHr/n/JH50UAFFFFABRRRQAUUUUAFFFFABRRkHoQaKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAoooo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APX/AJ//AK6p1+5Ev/BAL9tkp+58U/AQdwG8Z+KVJx2APwxBPORgdOR1r80P2lv2QP2gf2Stct9F+N/gC58OW2qTG30LxTZ3llrXhnXjgErZ61YYaNwCNyOFdSQGUHirp4qjUbVHR6b7penS7/q5w574X+IHDODeY5/wrmuBwK1lmf8AZzfLtpJ6pPzbte3Y+Ynf0PuT/wDX/n/+uo6Kpu/ofcn/AOv/AD//AF1ofI0qOz7Weq9Gm/mvh+/qf1K/8G9X7Sup6nB8VP2XNfvRc2mg2dl8Ufh4ZAN8NhfXzaf4rsWIAYiLVJLORQThQ0pUZck/0+nD9SQFHXvhx9QBg+/ev4jf+CCsxT9vm0tw+5Jfg/47IGev+ng8nPpzz7/Wv7dCqkMRkZCjp7jB/QZGBXzuM0rN7dNvTy7Jr5W7H+p30Zs5x2ceFeVxxrcpZfmeY5ZBybcngoOFSndvdr61a29kTjoMdMDFLSDgD6ClrhP6G2CiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooA4X4j/8AIheNx2/4RPxFx9NKusflX+djX+id8R/+RC8b/wDYp+I//TVdV/nUu/Tj6D+fP+f517WV7Vr+X3ctL/gn+Zf0/b/X/DnfXL+I7+f+25Z94O/Tj6D+fP8An+dfoR/wS4+C3h746ftifD/w/wCLbOPUfDnhjT9b8e6hpt3CLm11W68LSLHodvfhjtIi1FvMIP3lUqASRX531+vP/BEH/k9cf9kp8b5+n9p6Zn9M16Fb+HiutuW1/kfyL4F5bhMy8WuAMtzTCrH4LG8T5ZeMrWfvRa5k90mk2no7Lsf2ORxoiKqqoAVV4AGQAPT86dgeg/IUDoMdMDFLXyzbbu9Wz/eyMYwioxSjGKSSSSSSVlotNhMD0H5CghcHgdPT/CloPQ0ij5x/aB/ae+CP7LvhNPFvxp8dad4Q025mNvpNpLHd6hrmu3ZAP2LQ9D05JdT1GUg5IWNhkjdhSmfhof8ABbL9gMLt/wCE98YjngD4Z+NCPXknSM5zj1r8nv8Agv54V8fxfHP4T+NL+3vpPhfd/Dp/D2g3kBDaXB4wstd1LUdbtdQx/f0s6e3P3c7TjGB+Adexh8DRq0nWe+j+flZ7dPR9Nn/n74wfSi8ReC/EHPOFclybLMFl2RpRhLMsszDMMdmcWov69GUcbgoez1ulG6suqR/ayP8Agtt+wQOnj/xp/wCG38WY/L+ysd6P+H3P7AuP+SgeNfp/wrbxZj/00g1/E1J2/H+lV5O34/0rpWWYTTXXTZPfTz9Pv8z85p/TH8Vn8WB4a6Xf9m1F1TvZY926qy19Ln9tKf8ABbf9gJUwfiB40B54X4aeKtvPqTpxP6dee1faf7N/7Xv7P/7WOgX+ufA/x7aeLBpD28Ou6U9re6Pr2hPdjMY1LRdSjh1LTyU3Y3IFXbwwO0n/ADt6/bn/AIIM+F/H2oftfax4u0O01GP4f6B8NNbsvHt25A0pbrXHR/CekDIO5olUyoBlsx8AcVzYjAUadL2y1be2m7tvvps9enq2fpXhX9J/xA4u42yHhzOsnyvF5fnOZf2Y3lmW4+njqUUk/wC0ZS+u42nGnHdt2SjdtpXa/s3pMDqQPXP9aBjAx0wMfSlIyCPUYrxz+/v6/q5Hgkt74I6evBz9M/XPrXyX+2d8BfCH7Sv7OfxV+FnivT7W8XUfC2tal4dupYVuG0fxdpFle3nh/WrIDpf6TqgSWMj5vldOCSK+s84AAPXcD6DI+nbOcn+uK5PxeP8Aik/EQP3/AOwNYyfYaddHB59OP84raj+6altrFa3XZXdtEmlbXa6afU8TiDA4XMsjzfL8bhljMJi8qzGM4SSaadJtJb2abVne6a0to1/mKO/ofcn/AOv/AD//AF1Xk7fj/SpKpydvx/pX1Mdl6L8j/FeUVGtUilZRq1Ul2SnJL8LH7Sf8EGf+T/dM/wCyU/En/wBHR1/cJJ2/H+lfw6/8EFf+T/NG/wCyR/En/wBKI6/uKk7fj/SvAzH+N9/5RP8ASr6Kr/41c124kzT/ANJy9foPHQfQUtIvQfQfypa88/pkKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigDhfiJ/yIPjf/sU/EH/AKZ7/wDwH5V/nQ1/ot/Eb/kn/jf/ALE7xD/6Zb2v86CTt+P9K9jJlv5tv8JL9D/NT6feuP8ADd205eI7dlplun5/iRu/Tj6D+fP+f51+vX/BDz/k9v8A7pT42/8ATjptfj7X7Af8EPP+T3v+6T+Nv/Tnptelif8AdMV/25+h/KXgB/yejw3X/VUZZp/4Vn9k69B9B/KlpF6D6D+VLXyp/uwFB6HPTvRRQBwvjXwL4L+IuhXfhnx14T8P+M/Dl8oF7oPinR7HW9KugAcCSy1OOWxYjkg7CQSeQSSfnn/hgr9jDt+yv8Cl5/i+Gnhds/QrZJ/L86+ucDnnH4f1BP8An1pxDYGeBxjkDB7dOf8AOe1a+1qKyj0295vt1V77f0zwcw4a4fzar7fNMmyrMJ2s55llmAx8mu3Ni8LOVl0V9PkfI3/DA37F/wD0a38C/wDw2vhb/wCV5pv/AAwF+xWOf+GWvgXx/wBU08K/0sK+uz1PI/JP6mj8R+Sf40va1V9pdPtO/wBn+75L7mcH+ovBX/RI8Mf+I7lnl/1B2/TR+d/j4fsC/sWYOf2WfgWfTHw08MJ+rWTj9PSvfvh98Mvh78LNAi8K/DjwT4V8CeHYpDNHofhHQ9P8P6WJ2ILXH2HTYoohllXJZSxxgk4XHoZyQMjaPp3+n54zj60hVTjcfoCCOP8AgJ4Hp0p+1qS3WnSzb1VuyXRdLbLsd+A4byDKq6r5Zk2VYDFLVTy7LMBl8kna65sJhYzSa1d3rpfR3Jh0GKKOtFZHvDFJwOT94/8AoJrlvGfPhDxQT1/4R/Wef+4Xd11CdB/vH/0E1y/jL/kUPFH/AGL+s/8AprvKcd16r8zgx7f1PH6/8uKn40tfvP8AMDd+nH0H8+f8/wA6r0VXr66Oy9F+R/ixV/3jEf8AX6p/6XI/aX/ggj/yf5pf/ZKviV/6Njr+45+31b+dfw3f8EDv+UgWmf8AZKfiR/6Ojr+5F+31b+deBmP8b7/yif6Q/RZ/5Nm+3+sOa/8ApOAJF6D6D+VLSL0H0H8qWvPP6VCiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooA4P4jf8AJPvHP/Ym+If/AEzXlf5y8nb8f6V/o0fEb/kn3jn/ALE3xD/6Zryv85OvYyf/AD/9uP8ANz6ev+++G3pxH/6Tl5HJ2/H+lfsB/wAEO/8Ak+Af9kp8c/8Apy02vx7r9gf+CGv/ACe7/wB0o8cf+nLS69LEf7piv+3P0P5S8Af+T0eG99v9aMs37f7Yf2XL0H0H8qWkXoPoP5UtfKn+6AUUUUAFFFFABRRRQAUEA9QD9aKKACiiigCNOg/3j/6Ca5fxl/yKHij/ALF/Wf8A013ldQvQf7x/9BNcv4y/5FDxR/2L+s/+mu8px3XqvzODH/7nj/8ArxP/ANNH+X3VN39D7k//AF/5/wD66JO34/0qOvro7L0X5H+NFSj/ALRXv/z+qt/+By3/AEX39T9o/wDggb/ykC0f/sk3xN/9DSv7mZO34/0r+GH/AIIE/wDKQXS/+yT/ABM/9DWv7npO34/0r5/Mf94fof6LfRf/AOTZy/7KTNPywA9eg+g/lS0i9B9B/KlrgP6QCiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooA4P4k/8k+8b/8AYn+JP/TVd1/nF1/o8/EX/knvjb/sUvEX/prua/zfq9jJnv5Nr8JP9T/OD6eWuYeHFv8AoX8SL/1XoHfpx9B/Pn/P86/YL/ghj/ye+f8AskPi/wDTUNNNfji7+h9yf/r/AM//ANdfsZ/wQz/5Pki/7JD44/8AThpteniHbC4l9nTf4o/lrwEp/wDG4PDh3enE+Wa9v973/pfgf2bjkA+oFLSL0H0H8qWvlD/cQKKKKACiiigAooooAKKKKACiiigBF6D6CuR8a/8AIoeJ/wDsXtc/9NV5XXL0H0Fcj41/5FDxP/2L2uf+mq8qofHH/FH80cWY/wDIuzD/AK8T/wDSD/Lqk7fj/Sq7v6H3J/8Ar/z/AP11JVevtY7L0X5H+N1X/eMR/wBfqn/pcz9rf+CAv/KQbTP+yQfEf/0uFf3OHt9P6mv4XP8AggB/ykE0X/skfxM/9GpX90ZB447H9Cc/lXzOZv8A2tL+7N/fBf5H+iP0Yv8Ak2kv+yjzLT5YL+vkTL0H0H8qWkHAGeOB1pa84/o0KKTI9R+dGR6j86Auu/n8u4tFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFGQehBoA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bOyvu7J8j+wp+wd8Jv2EfhengjwDaf2x4u1mO1vPHvxC1GFP7e8WasmctK22Q2WmabuMekaVGxjgjHQyBnH3oD684APPUHJOPXP+e9VxNEWdVeLeg5yeRxkdeAcnoP5VOcAHrjIz6sf5gd+f514FSbqtuXxNJffbve1raavf1P8AQTKcsy7JMuwuWZThoYPA4OKjFQsk7KKfw/FJtatttu+rkz8If+CvX/BV6z/Y20W5+BnwceHUf2kfFmi2122o3Fu1xpfwr8M66ZIrXxPqKP8AJqGvScPoOkKpEp2uzEAA/wANviTxDrnifW9Y8UeJ9Yv9c8SeI9Yutb17WNevP7S1TUNUv73+0L+9v7/Uf+QjqWr/AJDBxX7Lf8F/8/8ADxrxlnr/AMK1+FP/AKanx+lfiTJ2/H+lfSZfSo06Ce7aV7a9F339PnorI/gXxk4ozbiDjLNsBjMXbAZJmjy3LcrWila15tbO+7b0v2SR+sX/AAQ/eNf+Cmf7OoGfu/FEf+Yj8Y+nTnnpmv8AQvBO8jPGOn5V/nef8ES/+UnH7Mn/AF8fEj/1UXxBr/RDH32+n+FeVmn8dej/AEP6J+jmv+MPzJPpmzVu1svwI+iiivMP6GCiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKMA9RmiigAwB0GKKKKAslsrBgDoMUh6H6GlooA5jW9D0vxLpt3ouvabY65o2pwNb6hpmrWVrqGnX9s3/LteWV+HjdWODh1IyMvnAWv55f28f+CK2ia7Z6v8Tv2Q7ZPDXiOKK71LWPg7PMp0HXmVd2PBN/e/J4Y1VuSCAImbgFGeNG/o7LoACQBnoRkA84/hzznj+tOOCAe57dc9sen6fgTXTSxFWi1yWSvazbWytokt9LbW6aa3/OuP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BH9oFx93Ff3BHCr8vHTH6Vni4UqNX2VLXl1u/NK9+l777nrcK4/HZxlyzLGWSxl3GCVkkrWae9tF66dLD6KKK5D6vYKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooyD0OaTI9R+dADfl2nk4zye+ePb6dqYTgY+8Oo69en+PH5kU4kBRgcEnOcnnj34/ya4T4gfEPwP8LvC+p+NfiH4o0HwZ4V0O3NzqfiLxHqVppemadb8ZeW+vnRBu6YJ3Oc4yBVJNtaNu+t3bRWvd7q3foctfEYfCUZYjE1KdLCQjeU6jSjFKzblKbSUUtW29Pkdzn7pOFAHvwTnGSfQ/X+lflx+3p/wVA+DH7FumXPhuCeD4kfG+5tDcaT8MdIulL2KkYTV/Fd+pK+HNLUHLOwZ3IwAud1fkR+3b/wXN1/xYNb+GX7IAvfC+h7bqw1L4x3sTQeJdQBwf8AihLA4/sHGONX1lc45CqSc/zl6xrGqa3quoaxrOqX+qaxql5d32paxqV5e6lqmoXV+D9vvb++1H6denrXrYXLL2r1lZW2te9/VaPV23fXmP4y8XvpVZdlf1vh3w8Tx+Ps4VeKH/yLcteibwCuvr876c9lhVa6+sdPav2iv2k/i9+1P8Q7/wCJ3xk8Ty6/r9zmDTdM5t9C8MaYzEto/hSyJJsNPZiWZizO7szMxYknwF39D7k//X/n/wDrok7fj/SvrH9jj9jP4vftn/E238AfDiwFto9hNazeNfiDqVmP7C8E6GFLC7vc4zqpAJ0LSMZIH1r137Ciui+5LTTyVtl20P4ewmF4g434iVKk80z7O88zRXm7yx+OTa1u7qMY3beySW3Ul/Yw/Y5+Jf7aXxe0/wCHHgO2lsNAsPst98RvHV5aKNM8FeGcZF1ebmVTqrdNB0ksOT1Aya/ve+APwH+Hf7Nfws8K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eEvGHijwB4n8N+OPBOuap4X8YeF9f0vxH4V17R7z+ztU8P6pYXv9oWF9YX/5/wD66wxOFpYmlorW8rdVp6s+p4czWrkeL9rS1g7Jpv7P4fPVfqf7ISkYwpz3546n8f0oBz7Y6j+XP68fQ1+Cv/BGX/gsT4N/4KB+B7L4U/FC/wBL8M/taeCtHM/iXw0AtrpfxL0KwQg/EHwICRvhcgNrukKDLoc+Cd0THyf3pwQpxnP643f4V8xVo1KNV0aq30v12T2Xbv39LL+gMDj6OPwkcXQ1Ulqlq1prrvp28t+o+iiipO8KKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAoPAJ9KKCMgj1GKAIiQ3J4HQAdT/wDqzzSlsc9z26gDt+J6/wCeW45UHOACxx0PtnvkY6cZFcD8SfH+gfDDwD4u+IXiq9Gm+G/Bvh7WPE2uXpH/AB66Xodg+oX0mP4sRRsQMjcQASBzWijdpL7W7fRbq/old9dH0OLGYqjgsNi8fiJKGFwNKdWpJ2SjGEbylJ62UVfpbTyO7EqltqEMQ2CASdhIzg545Gevoce8rHoccke/H4fj3r8Gv+CM/wC0V4g/aH8ZftpeK/F+oXL674g8eeC/GdnoM0/2i18PaDrVp4usNOstP7bANPcN+AHANfvHnaQARluRwcgL1P4c/UfWnUp+yqeyTs0tPPq79Ele/r3ep8xwFxnl/iBwvl/FWV0msHjquYQgm1zJ5fmGMwDbel+Z4PnXlJO73GAMh3ckDC+u73IHp7Z9c+vPeIvEWgeFNHvNd8Sa7pfh3RdNge51DV9Yv7PT9NsbdcZe9vL90RFwQoZ2QF2AyCefzY/4Ko/te/GH9jn4KeFPHfwj8P8Ah2/1DxP41TwfqeseI4L6/tPC/wDaOjapf2F+NP08h78m+08BkLBR055I/jv+On7Ufx+/aO1VNU+M/wAUvFnjeQz/AGm10i71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fqH+2+yXfT7162dmlq9Ntj+0v/gnF+y3bfsZfsR/s4/s5LHFHq/w/wDhtpSeM54trC6+IuvlvEfj+9UgkYl8Zazrbqc/d2HAJIH3A4PXt/KpKK+dbu2+59TTiqSs9NredvTboFFFFI2CiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKADAHQYowB0AFFFABRRRQAUYB6jNFFABRRRQBGGI4PI7EdP8j86/OT/AIKEf8FJvg7/AME5PDXww8VfGLwv488U6Z8TvGFz4Us4vh/YaTqep6R9hsP7RvNZ1Cx1LVdHDadGm0M8UvLk524wf0aCgjAOSCTk5HXt+lcP49+HvgX4n+FdV8EfEjwf4e8d+D9dtzBrHhvxRo9lruhajagElL2w1GOTT5AOTypbuoI6aQcU3qnqrNrbz1+53077o4cbSxdXDNYPErCYqycZNXjfTdfJrul10R8L/s6/8FZv2AP2nhaWfw1/aX8BWniS9UGDwV8Qbw/DfxiSq4IXQ/GX9jy35ZvmH9lGcY4JycH9GElSVFkjaNk/vD5gfTjnGORjjkDmv5j/ANsn/g2b/Zn+Lo1TxZ+yv4svf2bvGkxM1v4Vuftviv4TXF1tQhRYu3/CT+EeS5abR9Y1IHC7YBk1+BXxB/Z+/wCC33/BJl59R8PeKPjnonww0Xa0Hi74QeL734o/AlMggX2oeEzhhkbgP+FjeDAeo65x2LDUaq/dYhp6aS0d7J23a0WnXt5L5GtxBnuUSSznIpY3BrT+08t95bLWUW3Jd2rryP8ARu2g87AR74zj8/c/p6U0Bcj5B2/h45x/if8Ax2v88r4Y/wDBzf8A8FGvBUNnaeOrf4J/GA9Jr3xV8P38N6rP2+Wy+HmqeENMB9duB+NfU1l/wdkfHiOIJffsj/C25uR3tPiD4j022+mdTjLe3Wl/ZuM6S/rTz80b0uOOH6iTb5bWtzwtZ6Xvvt+L9D+5MAc5UD8jTGZ/QdecemfX8DxwetfwkeKf+DsH9p+7tZYvCf7MPwO0Kf8A5+9a8SePtaX/AL50tUU/iDgjjHf4E+NH/BxJ/wAFQfi5Bc2Wm/F7wt8F9J1ADyYfgr4E0bRrm2xkfJ4p8aHxj4mTI6hAN3fOMgjlmL0v5dNtuzVrfPby0K3HOR09MO5Ytvb2dlrppdt23X/Dan+hp8e/2nfgD+zB4Rm8c/H/AOLfgf4V+GkVhFd+MNes9LuNQfZ/x6aJYPI2qa7fk/L5WkQzSIzAlcI1fyW/t+f8HQl1fW+ufDz/AIJ++EpLJx+4k/aB+JulD7GoByD4F8EsXk1SUcqX1dkVhyIlbk/yIfEX4o/Ej4weJLzxp8VPiB4y+InjC/I87xV8QvFWteJNeuPQi+8RZ+np6nrXnbv04+g/nz/n+dejRyylSs677NfK3y8r+m9j5jMOMswxbdHBpYLpr8TTt16b+tjtPiX8S/iB8YPGeufEP4neMPEfxA8eeKLwX2veMPFWpXuta9qF0P8An/vtRH44/wD1V9ff8E0v2FfGf/BQX9q34f8AwN0KO+0/wnDOvij4v+MLMgWvhD4d6IwTW7rLFQdU1pyvhPQQSMswHWvlH4M/Bn4l/tB/E3wh8HPhB4P1Pxn8Q/Heo22l+HNC0g/6TPdDrfX+eNN0zSOf7d1b8a/05P8Agkt/wTN8Ef8ABNj9nq38GLLp/ij42+PPsviL40fEWCIq2s6+UVrLw1o25VePwb4RR20rQIQAQgMjkl1WG8VilhaNk1drydlpr6LT79bmPD2T4jN8Z9ZxF/qid23fdWa1b3/H0R+lHw98BeFPhd4K8J/DjwJo9p4c8GeCNB0zwv4X0LTl22ek6Fodklhp9kgJOBFGgUdyVGfmDGu4oor5w/YUkkklolZegVxPxB8aaB8NfAvjT4i+Kroaf4Y+H/hXxD438R3u0EWGgeFdG1DX9cvtpI4TTbG9J55A68k121fPX7V3wz1T41fsv/tG/CDQ5RBrfxU+BvxZ+HOjzjHGqeL/AAD4h8P6eWJxgLqGoJk9hk8jq47r1X5mdXRfKX6H+Vl/wUI/b/8AjV/wUK+POv8AxV+J+uapbeEhqV3B8Mfhj9sJ8MfDDwc2TZaPZWXVnZiW1/V2JZ3YsSSTXwj9pktnjktpJbe4in8+GaH29c9+Px6VqeIdE1jwlreseF/Eml3+h+JPDmsanoevaPqVn9l1TR9U0+9Nhf2V/Y9tS0jp/wDX6c3X1tGiuVXS2S6dl3+V2z855q9Su/atrVtWfbr6tdu+vc/0Iv8Ag2e/4KafEv8Aax+G3xC/Za+PHiC58X/En4BaBoXiLwV421m8Nzr/AIp+F1/eHQBY66wP+man4R1P+z9KeQAFhfKrE7Ux/VYWGCQemPX1r+Cn/g0U+C/iy9/aF/ah/aHa3kt/Avhf4N2vwYgvSV+z6v4m8deNPB/j9mVTyf7J0zwQImI5US574P8AemFIDD1Kj26183j0oYtro97edtul9Fto992z7vLJ1KuDoOtukrW7f8Na233HwZ/wUS/YL+G3/BRj9niP9nn4oaxqvh/QE+I/gPx9b67oUdl/aVhd+EtazfWdmbwOif234b1DX/DTOFJC6mWIwpFfVXwk+Evw6+A/w18GfCH4T+FtL8F/Df4faDZ+HPB/hTR4vs+maNpFipFlY2S9Ni5J6tyc8Dr6XtBx7cHPfHA/l+NLtC5OCfQDt2/GuRS0VFN26L1t/Xbptodfs6fN7W2vfXv29R1FFFM1CiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKADAPUZpCoIwQMfQcfSlooE0mrNabWPyv/AGpv+COH/BPv9rqXUdU+I3wJ0Pwz4y1AqsvxF+E5X4ceM5gqHP22/wDDyrp2pZOC51nSbkk8KQMmv52f2jv+DUz4i6a13qn7Kn7RmieKbMwL5PhD446ZfaNqTOVJZU8b+DMTYXBy0kUeeDgV/bmGA6tn8MU7ORwevQ10UsXWp2d9e3TTr110Wz/yXz+N4YyfHP8Ae4WK842WujurW1ve7tqf5XHxy/4I8f8ABSn4ANqEvjb9k34l6zpEdut0fEXwtgs/ivotqHGVElz8Phq0kbYPKyIrKSVZQQQPzj17w34k8MahJpfifw/r3hvVIj++03WdMvdNuv8AwB1Hp/nsDX+y+ATxkH1yCePqRn8jXMa/4O8K+KYfI8R+GNA1+3xgQa3o9lqi8Y6C+ikC/l2rsWaV1ZS6Wv1089mfNVOAMHp9TxLh/ddtL2W61/Dffe5/jT/vH/dw+oOef8//AKq/T39jP/gjv+3l+2rrGlXHgX4Ma94G+HF3MTd/GT4s2V94L8DQ2o63unJfA6v8R8AgsNGB9SK/069A+CHwU8M3v9p+GfhF8MfD9+RxqGg+APC+l3ef+v3TtJikA78Px+Jr1jbj7hCj0GQc8/n0/njvVzzVvSkraatu9vPT7/ka4XgWjSq82KxLntZJWdla+9uvZNWfXQ/KH/gmd/wSZ/Z6/wCCa3gi4/4RGH/hOvjP4n061g+I3xq8SWlkfEGthQrHR9DXyS3hzwfE4Pk6PC7KQvmM5bbGn6u5yHPT7tBwcZfp/smpAARgdPx9fz6149as6ur1b9dNu/pofc4XC0cJSVGjHlivx0Sv+AtFFFM6AoPQ9/b1oooA/nU/4Kf/APBvJ+z5+35421T45/Djxfdfs+fH7Xys3izxBpujWniLwN8QLg9b3xj4VlCDUNUU/wDMUDNIwOTHkHP5L/Cj/g0K8YJ4rtJfjj+1/wCG5PAtrKZrvTPhZ4FvB4o1iDn5dQ13xiHxzwSobAycHgV/cgWUgjPX2P8AhTRgHO78x19fX6VvHGYqFL2UZXXe/nr5387+RwTy3B1aqrOglLt/Xn/Wp86fsu/st/BT9jj4MeFPgN8A/B1v4N+HvhOLdaWXFzqOqapeADUdb13UNinU/EOrODJrGquoaVsnaI+E+jGUk5HP9P1oViTg9/061JWB2pRhGy2Wi/r0QUUUUFBRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAYA6DFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFAH//2Q==" alt="" width="5000" height="200" />
            <h1>نظام <span>الصيانة</span></h1>
            <div class="sub">مركز صيانة المحمول</div>
        </div>

        <div class="info-grid">
            <div class="item">
                <div class="label">رقم الفاتورة</div>
                <div class="value"><?php echo htmlspecialchars($sale['invoice_number']); ?></div>
            </div>
            <div class="item">
                <div class="label">التاريخ</div>
                <div class="value"><?php echo date('d/m/Y h:i A', strtotime($sale['sale_date'])); ?></div>
            </div>
            <div class="item">
                <div class="label">العميل</div>
                <div class="value"><?php echo htmlspecialchars($sale['customer_name'] ?? 'عميل نقدي'); ?></div>
            </div>
            <div class="item">
                <div class="label">الحالة</div>
                <div class="value">
                    <span class="status-badge <?php echo $sale['status']; ?>">
                        <?php
                        if ($sale['status'] == 'completed') echo 'مدفوعة';
                        elseif ($sale['status'] == 'pending') echo 'معلقة';
                        else echo 'دفعة جزئية';
                        ?>
                    </span>
                </div>
            </div>
            <div class="item">
                <div class="label">طريقة الدفع</div>
                <div class="value"><?php
                    $methods = [
                        'cash'          => 'كاش',
                        'card'          => 'بطاقة',
                        'wallet'        => 'محفظة',
                        'bank_transfer' => 'تحويل بنكي',
                        'installment'   => 'تقسيط',
                        'fawry'         => 'فوري',
                        'vodafone_cash' => 'فودافون كاش',
                        'instapay'      => 'انستاباي',
                    ];
                    echo $methods[$sale['payment_method']] ?? htmlspecialchars($sale['payment_method']);
                ?></div>
            </div>
            <div class="item">
                <div class="label">بواسطة</div>
                <div class="value"><?php echo htmlspecialchars($sale['created_by_name'] ?? '---'); ?></div>
            </div>
        </div>

        <table class="invoice-items">
            <thead>
                <tr>
                    <th style="width:10%;">#</th>
                    <th style="width:42%; text-align:right;">الوصف</th>
                    <th style="width:14%;">الكمية</th>
                    <th style="width:16%;">السعر</th>
                    <th style="width:18%;">الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1;
                foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td class="desc-cell">
                            <?php echo htmlspecialchars($item['description']); ?>
                            <?php if ($item['item_type'] == 'part'): ?>
                                <span class="type-tag part">قطعة</span>
                            <?php else: ?>
                                <span class="type-tag service">خدمة</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo intval($item['quantity']); ?></td>
                        <td><?php echo number_format($item['unit_price'], 0); ?></td>
                        <td style="font-weight:700;"><?php echo number_format($item['total_price'], 0); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="totals">
            <div class="row">
                <span class="lbl">المجموع الفرعي</span>
                <span class="val"><?php echo number_format($sale['subtotal'], 0); ?> ج</span>
            </div>
            <?php if (floatval($sale['discount']) > 0): ?>
                <div class="row">
                    <span class="lbl">الخصم</span>
                    <span class="val" style="color:#dc2626;">- <?php echo number_format($sale['discount'], 0); ?> ج</span>
                </div>
            <?php endif; ?>
            <div class="row grand-total">
                <span class="lbl">الإجمالي</span>
                <span class="val"><?php echo number_format($sale['total_amount'], 0); ?> ج</span>
            </div>
            <div class="row">
                <span class="lbl">المدفوع</span>
                <span class="val paid"><?php echo number_format(floatval($sale['paid_amount'] ?? 0), 0); ?> ج</span>
            </div>
            <div class="row">
                <span class="lbl">المتبقي</span>
                <?php $rem = floatval($sale['remaining_amount'] ?? 0); ?>
                <span class="val <?php echo $rem > 0 ? 'remaining' : 'settled'; ?>">
                    <?php echo number_format($rem, 0); ?> ج
                </span>
            </div>
        </div>

        <?php if (!empty($sale['notes'])): ?>
            <div class="notes-box">
                <div class="lbl">ملاحظات</div>
                <div><?php echo nl2br(htmlspecialchars($sale['notes'])); ?></div>
            </div>
        <?php endif; ?>

        <div class="qr-section">
            <div class="qr-item">
                <img src="https://api.qrserver.com/v1/create-qr-code/?data=https%3A%2F%2Fwww.facebook.com%2Fshare%2F1CBsxFmocL%2F&size=100x100&margin=10" alt="فيسبوك">
                <div class="label">فيسبوك</div>
            </div>
            <div class="qr-item">
                <img src="https://api.qrserver.com/v1/create-qr-code/?data=https%3A%2F%2Fwa.me%2F201119653555&size=100x100&margin=10" alt="واتساب">
                <div class="label">واتساب</div>
            </div>
        </div>

        <div class="footer">
            <div class="thanks">🙏 شكراً لثقتكم بنا</div>
            <div>&copy; <?php echo date('Y'); ?> نظام الصيانة</div>
            <div class="phone">📞 01119653555</div>
        </div>

    </div>

    <script>
        function changeSize() {
            const container = document.getElementById('invoiceContainer');
            const sel = document.getElementById('sizeSelector').value;
            container.classList.remove('size-58mm', 'size-80mm', 'size-100mm', 'size-full');
            container.classList.add(sel);
        }
    </script>

</body>
</html>