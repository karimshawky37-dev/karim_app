<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>معادلة الاستثمار والشراكة</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Cairo', 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }
        .glass-container {
            max-width: 1400px;
            width: 100%;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 40px;
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, 0.25);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #fff;
            font-size: 28px;
            font-weight: 800;
            text-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header h1 i { margin-left: 12px; color: #ffd700; }
        .month-selector form {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .month-selector input[type="month"] {
            padding: 10px 16px;
            border: none;
            border-radius: 16px;
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(4px);
            color: #fff;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            border: 1px solid rgba(255,255,255,0.3);
        }
        .month-selector input[type="month"]::-webkit-calendar-picker-indicator { filter: invert(1); }
        .btn-glass {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #fff;
            padding: 10px 20px;
            border-radius: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s ease;
            font-family: 'Cairo', sans-serif;
        }
        .btn-glass:hover { background: rgba(255, 255, 255, 0.35); transform: translateY(-2px); }
        .btn-primary-glass { background: #ffd700; color: #1e1e2f; border: none; }
        .btn-primary-glass:hover { background: #ffe44d; }
        .btn-danger-glass { background: rgba(255, 82, 82, 0.8); color: #fff; border: none; }
        .btn-danger-glass:hover { background: #ff5252; }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 24px;
            padding: 20px;
            color: #fff;
            transition: 0.3s ease;
        }
        .stat-card:hover { transform: translateY(-5px); background: rgba(255, 255, 255, 0.2); }
        .stat-card .label { font-size: 14px; opacity: 0.8; font-weight: 400; }
        .stat-card .value { font-size: 28px; font-weight: 800; font-family: 'Inter', sans-serif; margin-top: 8px; }
        .stat-card .value i { font-size: 20px; margin-left: 8px; }
        .stat-card.gold .value { color: #ffd700; }
        .stat-card.green .value { color: #4ade80; }
        .stat-card.blue .value { color: #60a5fa; }
        .stat-card.pink .value { color: #f472b6; }
        .section-title { color: #fff; font-size: 20px; font-weight: 700; margin-bottom: 16px; display: flex; align-items: center; gap: 10px; }
        .section-title i { color: #ffd700; }
        .glass-table-wrapper {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(4px);
            border-radius: 20px;
            padding: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            overflow-x: auto;
            margin-bottom: 30px;
        }
        table { width: 100%; border-collapse: collapse; color: #fff; font-size: 14px; }
        table th { text-align: right; padding: 12px 16px; background: rgba(255, 255, 255, 0.05); font-weight: 700; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
        table td { padding: 12px 16px; border-bottom: 1px solid rgba(255, 255, 255, 0.05); }
        table tr:hover td { background: rgba(255, 255, 255, 0.05); }
        .badge-manager { background: rgba(255, 215, 0, 0.2); border: 1px solid rgba(255, 215, 0, 0.3); padding: 2px 12px; border-radius: 50px; font-size: 12px; color: #ffd700; }
        .glass-form {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(4px);
            border-radius: 20px;
            padding: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 10px;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: end;
        }
        .glass-form .group { flex: 1; min-width: 150px; }
        .glass-form label { color: rgba(255, 255, 255, 0.8); font-size: 13px; display: block; margin-bottom: 4px; }
        .glass-form input, .glass-form select {
            width: 100%;
            padding: 10px 14px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            font-family: 'Inter', 'Cairo', sans-serif;
            transition: 0.3s;
        }
        .glass-form input:focus, .glass-form select:focus { outline: none; border-color: #ffd700; background: rgba(255, 255, 255, 0.2); }
        .glass-form input::placeholder { color: rgba(255, 255, 255, 0.5); }
        .glass-form .checkbox-group { display: flex; align-items: center; gap: 8px; color: #fff; }
        .glass-form .checkbox-group input { width: 18px; height: 18px; accent-color: #ffd700; }
        .alert-success { background: rgba(74, 222, 128, 0.2); border: 1px solid rgba(74, 222, 128, 0.3); color: #4ade80; padding: 12px 20px; border-radius: 16px; margin-bottom: 20px; backdrop-filter: blur(4px); }
        .btn-back { background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2); color: #fff; padding: 8px 16px; border-radius: 12px; text-decoration: none; font-size: 14px; transition: 0.3s; display: inline-flex; align-items: center; gap: 8px; }
        .btn-back:hover { background: rgba(255,255,255,0.25); }
        @media (max-width: 768px) {
            .glass-container { padding: 16px; }
            .header { flex-direction: column; align-items: stretch; gap: 15px; }
            .month-selector form { flex-wrap: wrap; }
        }
    </style>
</head>
<body>
<div class="glass-container">
    <div style="margin-bottom: 20px;">
        <a href="/" class="btn-back"><i class="fas fa-arrow-right"></i> العودة للرئيسية</a>
    </div>

    @if(session('success'))
        <div class="alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    <div class="header">
        <h1><i class="fas fa-handshake"></i> معادلة الاستثمار والشراكة</h1>
        <div class="month-selector">
            <form action="{{ route('investment.dashboard') }}" method="GET">
                <input type="month" name="month" value="{{ $monthYear }}">
                <button type="submit" class="btn-glass"><i class="fas fa-calendar-alt"></i> عرض</button>
            </form>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card gold"><div class="label"><i class="fas fa-coins"></i> إجمالي رأس المال</div><div class="value">{{ number_format($stats->total_capital, 2) }} ج.م</div></div>
        <div class="stat-card green"><div class="label"><i class="fas fa-chart-line"></i> صافي أرباح الشهر</div><div class="value">{{ number_format($stats->net_profit, 2) }} ج.م</div></div>
        <div class="stat-card blue"><div class="label"><i class="fas fa-user-cog"></i> راتب الإدارة المستقطع</div><div class="value">{{ number_format($stats->manager_salaries, 2) }} ج.م</div></div>
        <div class="stat-card pink"><div class="label"><i class="fas fa-file-invoice-dollar"></i> إجمالي المصروفات</div><div class="value">{{ number_format($stats->total_expenses, 2) }} ج.م</div></div>
    </div>

    <!-- تحديث الأرقام المالية -->
    <div class="section-title"><i class="fas fa-edit"></i> تحديث أرقام الشهر المالي</div>
    <form action="{{ route('investment.financials.update') }}" method="POST" class="glass-form">
        @csrf
        <input type="hidden" name="month_year" value="{{ $monthYear }}">
        <div class="group">
            <label>إجمالي الإيرادات (مبيعات)</label>
            <input type="number" step="0.01" name="total_revenue" value="{{ old('total_revenue', $summary->total_revenue) }}" placeholder="0.00">
        </div>
        <div class="group">
            <label>المصاريف التشغيلية</label>
            <input type="number" step="0.01" name="total_operational_expenses" value="{{ old('total_operational_expenses', $summary->total_operational_expenses) }}" placeholder="0.00">
        </div>
        <div class="group">
            <label>تكلفة البضاعة / قطع الغيار</label>
            <input type="number" step="0.01" name="total_cost_of_goods" value="{{ old('total_cost_of_goods', $summary->total_cost_of_goods) }}" placeholder="0.00">
        </div>
        <div class="group" style="flex: 0 0 auto;">
            <button type="submit" class="btn-glass btn-primary-glass"><i class="fas fa-sync-alt"></i> تحديث</button>
        </div>
    </form>

    <!-- توزيع الأرباح -->
    <div class="section-title" style="margin-top: 30px;"><i class="fas fa-users"></i> توزيع الأرباح على الشركاء</div>
    <div class="glass-table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>اسم الشريك</th>
                    <th>المساهمة (ج.م)</th>
                    <th>نسبة الملكية</th>
                    <th>ربح الشهر المستحق</th>
                    <th>الدور</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($partnersData as $id => $data)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $data['name'] }}</td>
                        <td>{{ number_format($data['contribution'], 2) }}</td>
                        <td>{{ $data['percentage'] }}%</td>
                        <td>
                            @php
                                $share = collect($distributions)->firstWhere('name', $data['name']);
                            @endphp
                            {{ $share ? number_format($share->share, 2) : '0.00' }} ج.م
                        </td>
                        <td>
                            @if($data['is_manager'])
                                <span class="badge-manager"><i class="fas fa-crown"></i> مدير</span>
                            @else
                                <span style="opacity:0.5;">شريك</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn-glass" style="padding:4px 12px; font-size:12px;" onclick="editPartner({{ $id }}, '{{ $data['name'] }}', {{ $data['contribution'] }}, {{ $data['is_manager'] ? 'true' : 'false' }}, {{ $data['monthly_salary'] ?? 0 }})"><i class="fas fa-edit"></i></button>
                            <form action="{{ route('investment.partner.delete', $id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('حذف الشريك؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-glass btn-danger-glass" style="padding:4px 12px; font-size:12px;"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="text-align:center; opacity:0.6;">لا يوجد شركاء حتى الآن. أضف شريكاً جديداً.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- إضافة شريك -->
    <div class="section-title"><i class="fas fa-user-plus"></i> إضافة شريك جديد</div>
    <form action="{{ route('investment.partner.store') }}" method="POST" class="glass-form">
        @csrf
        <div class="group">
            <label>اسم الشريك</label>
            <input type="text" name="name" placeholder="مثال: أحمد محمد" required>
        </div>
        <div class="group">
            <label>المساهمة (ج.م)</label>
            <input type="number" step="0.01" name="contribution" placeholder="0.00" required>
        </div>
        <div class="group" style="flex: 0 0 auto;">
            <div class="checkbox-group">
                <input type="checkbox" name="is_manager" id="is_manager">
                <label for="is_manager">مدير (راتب شهري)</label>
            </div>
        </div>
        <div class="group" style="flex: 1;">
            <label>الراتب الشهري للمدير</label>
            <input type="number" step="0.01" name="monthly_salary" placeholder="0.00" value="0">
        </div>
        <div class="group" style="flex: 0 0 auto;">
            <button type="submit" class="btn-glass btn-primary-glass"><i class="fas fa-save"></i> إضافة</button>
        </div>
    </form>
</div>

<!-- مودال التعديل -->
<div id="editModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); backdrop-filter:blur(6px); justify-content:center; align-items:center; z-index:999;">
    <div style="background: rgba(255,255,255,0.1); backdrop-filter:blur(16px); padding:30px; border-radius:30px; width:90%; max-width:500px; border:1px solid rgba(255,255,255,0.2); color:#fff;">
        <h3 style="margin-bottom:20px;"><i class="fas fa-user-edit"></i> تعديل بيانات الشريك</h3>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div style="margin-bottom:15px;">
                <label style="display:block; opacity:0.8; margin-bottom:5px;">الاسم</label>
                <input type="text" name="name" id="edit_name" style="width:100%; padding:12px; border-radius:12px; border:1px solid rgba(255,255,255,0.2); background:rgba(255,255,255,0.1); color:#fff;">
            </div>
            <div style="margin-bottom:15px;">
                <label style="display:block; opacity:0.8; margin-bottom:5px;">المساهمة (ج.م)</label>
                <input type="number" step="0.01" name="contribution" id="edit_contribution" style="width:100%; padding:12px; border-radius:12px; border:1px solid rgba(255,255,255,0.2); background:rgba(255,255,255,0.1); color:#fff;">
            </div>
            <div style="margin-bottom:15px; display:flex; gap:10px; align-items:center;">
                <input type="checkbox" name="is_manager" id="edit_is_manager">
                <label for="edit_is_manager">مدير</label>
            </div>
            <div style="margin-bottom:20px;">
                <label style="display:block; opacity:0.8; margin-bottom:5px;">الراتب الشهري للمدير</label>
                <input type="number" step="0.01" name="monthly_salary" id="edit_salary" style="width:100%; padding:12px; border-radius:12px; border:1px solid rgba(255,255,255,0.2); background:rgba(255,255,255,0.1); color:#fff;">
            </div>
            <div style="display:flex; gap:10px;">
                <button type="submit" class="btn-glass btn-primary-glass" style="flex:1;"><i class="fas fa-save"></i> حفظ</button>
                <button type="button" class="btn-glass" style="flex:1;" onclick="document.getElementById('editModal').style.display='none';">إلغاء</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editPartner(id, name, contribution, isManager, salary) {
        const modal = document.getElementById('editModal');
        modal.style.display = 'flex';
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_contribution').value = contribution;
        document.getElementById('edit_is_manager').checked = isManager;
        document.getElementById('edit_salary').value = salary || 0;
        document.getElementById('editForm').action = '/investment/partner/' + id;
    }
    window.onclick = function(event) {
        const modal = document.getElementById('editModal');
        if (event.target == modal) modal.style.display = "none";
    }
</script>

</body>
</html>