<?php
namespace App\Controllers;

use App\Config\Database;

class WalletController
{
    private $db;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }
        $this->db = Database::getInstance()->getConnection();
    }

    // ============================================================
    // 📋 قائمة المحافظ
    // ============================================================
    public function index()
    {
        $stmt = $this->db->query("SELECT * FROM wallets ORDER BY wallet_type, wallet_name");
        $wallets = $stmt->fetchAll();

        // حساب إجمالي الرصيد
        $totalBalance = 0;
        foreach ($wallets as $w) {
            $totalBalance += $w['current_balance'];
        }

        ?>
        <!DOCTYPE html>
        <html dir="rtl" lang="ar">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>المحافظ الإلكترونية</title>
            <script src="https://cdn.tailwindcss.com"></script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
            <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
            <style>
                * { font-family: 'Tajawal', sans-serif; }
                body { background: #f0f4f8; }
                .sidebar { background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%); min-height: 100vh; width: 220px; position: fixed; right: 0; top: 0; z-index: 50; }
                .sidebar a { display: flex; align-items: center; gap: 10px; color: #94a3b8; padding: 10px 18px; text-decoration: none; transition: 0.3s; border-right: 3px solid transparent; font-size: 14px; }
                .sidebar a:hover { background: rgba(255,255,255,0.05); color: white; border-right-color: #3b82f6; padding-right: 24px; }
                .sidebar a.active { background: rgba(255,255,255,0.08); color: white; border-right-color: #3b82f6; }
                .sidebar .logo { padding: 16px; color: white; font-size: 18px; font-weight: 800; border-bottom: 1px solid rgba(255,255,255,0.06); text-align: center; }
                .main-content { margin-right: 220px; padding: 20px; background: #f1f5f9; min-height: 100vh; }
                .card { background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); transition: all 0.3s cubic-bezier(0.4,0,0.2,1); }
                .card:hover { transform: translateY(-3px); box-shadow: 0 12px 40px rgba(0,0,0,0.08); }
                .stat-number { font-size: 28px; font-weight: 800; }
                .type-badge { padding: 2px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; }
                .type-mobile { background: #dbeafe; color: #2563eb; }
                .type-bank { background: #d1fae5; color: #059669; }
                .type-cash { background: #fef3c7; color: #d97706; }
                .table-row:hover { background: #f8fafc; }
            </style>
        </head>
        <body>
            <div class="sidebar">
                <div class="logo">💰 نظام الصيانة</div>
                <a href="/">🏠 الرئيسية</a>
                <a href="/devices">📱 الأجهزة</a>
                <a href="/inventory">📦 المخزون</a>
                <a href="/sales">💰 الفواتير</a>
                <a href="/wallets" class="active">💳 المحافظ</a>
                <a href="/chat">💬 الشات</a>
                <div style="border-top:1px solid rgba(255,255,255,0.06); margin-top:20px;"></div>
                <div style="padding:12px 18px; color:#94a3b8; font-size:13px;">👤 <?php echo $_SESSION['full_name']; ?></div>
                <a href="/logout" style="color:#64748b;">🚪 خروج</a>
            </div>

            <div class="main-content">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">💳 المحافظ الإلكترونية</h1>
                        <p class="text-sm text-gray-500"><?php echo $_SESSION['full_name']; ?> | <?php echo date('Y-m-d h:i A'); ?></p>
                    </div>
                    <div class="flex gap-2">
                        <a href="/wallets/create" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition flex items-center gap-2">
                            <i class="fas fa-plus"></i> إضافة محفظة
                        </a>
                        <a href="/" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition">← الرئيسية</a>

                        <a href="/wallet/deposit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition flex items-center gap-2">
    <i class="fas fa-hand-holding-usd"></i> استلام دفعة
</a>
                    </div>
                </div>

                <!-- إجمالي الرصيد -->
                <div class="card p-4 mb-6 border-r-4 border-blue-500">
                    <p class="text-sm text-gray-500">إجمالي الرصيد في جميع المحافظ</p>
                    <p class="stat-number text-blue-600"><?php echo number_format($totalBalance, 2); ?> جنيه</p>
                </div>

                <!-- جدول المحافظ -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">#</th>
                                    <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">اسم المحفظة</th>
                                    <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">النوع</th>
                                    <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">رقم الحساب</th>
                                    <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">المالك</th>
                                    <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">الرصيد الحالي</th>
                                    <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">الحالة</th>
                                    <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">الإجراء</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($wallets)): ?>
                                    <tr><td colspan="8" class="px-6 py-8 text-center text-gray-400">📭 لا توجد محافظ</td></tr>
                                <?php else: ?>
                                    <?php foreach ($wallets as $w): ?>
                                        <?php
                                        $typeClass = 'type-cash';
                                        $typeText = 'كاش';
                                        if ($w['wallet_type'] == 'mobile_wallet') { $typeClass = 'type-mobile'; $typeText = 'محفظة جوال'; }
                                        elseif ($w['wallet_type'] == 'bank_account') { $typeClass = 'type-bank'; $typeText = 'حساب بنكي'; }
                                        ?>
                                        <tr class="table-row transition">
                                            <td class="px-4 py-3"><?php echo $w['id']; ?></td>
                                            <td class="px-4 py-3 font-medium"><?php echo $w['wallet_name']; ?></td>
                                            <td class="px-4 py-3"><span class="type-badge <?php echo $typeClass; ?>"><?php echo $typeText; ?></span></td>
                                            <td class="px-4 py-3 text-sm"><?php echo $w['account_number'] ?? '—'; ?></td>
                                            <td class="px-4 py-3 text-sm"><?php echo $w['owner_name'] ?? '—'; ?></td>
                                            <td class="px-4 py-3 font-bold <?php echo $w['current_balance'] >= 0 ? 'text-green-600' : 'text-red-600'; ?>">
                                                <?php echo number_format($w['current_balance'], 2); ?>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="px-2 py-1 rounded-full text-xs font-medium <?php echo $w['is_active'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                                                    <?php echo $w['is_active'] ? '🟢 نشط' : '🔴 غير نشط'; ?>
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 flex items-center gap-2">
                                                <a href="/wallets/transactions/<?php echo $w['id']; ?>" class="text-blue-600 hover:text-blue-800 text-sm" title="الحركات"><i class="fas fa-history"></i></a>
                                                <a href="/wallets/edit/<?php echo $w['id']; ?>" class="text-amber-600 hover:text-amber-800 text-sm" title="تعديل"><i class="fas fa-edit"></i></a>
                                                <a href="/wallets/delete/<?php echo $w['id']; ?>" class="text-red-500 hover:text-red-700 text-sm transition" onclick="return confirm('⚠️ هل أنت متأكد من حذف هذه المحفظة؟')" title="حذف"><i class="fas fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-4 text-center text-xs text-gray-400">
                    💡 يمكنك إضافة محافظ جديدة وإدارة حركاتها المالية
                </div>
            </div>
        </body>
        </html>
        <?php
    }

    // ============================================================
    // ➕ نموذج إضافة محفظة جديدة
    // ============================================================
    public function create()
    {
        ?>
        <!DOCTYPE html>
        <html dir="rtl" lang="ar">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>إضافة محفظة جديدة</title>
            <script src="https://cdn.tailwindcss.com"></script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
            <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
            <style>
                * { font-family: 'Tajawal', sans-serif; }
                body { background: #f0f4f8; padding: 20px; }
                .card { background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); padding: 24px; max-width: 600px; margin: 0 auto; }
                .input-field { border: 2px solid #e2e8f0; border-radius: 10px; padding: 8px 14px; width: 100%; font-size: 14px; transition: 0.3s; }
                .input-field:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); outline: none; }
                .label { font-size: 13px; font-weight: 600; color: #334155; display: block; margin-bottom: 4px; }
                .btn { padding: 10px 20px; border-radius: 10px; font-weight: 600; font-size: 14px; transition: 0.3s; border: none; cursor: pointer; }
                .btn-primary { background: #3b82f6; color: white; width: 100%; }
                .btn-primary:hover { background: #2563eb; transform: translateY(-2px); box-shadow: 0 4px 15px rgba(37,99,235,0.3); }
                .btn-secondary { background: #e2e8f0; color: #1e293b; }
                .btn-secondary:hover { background: #cbd5e1; }
            </style>
        </head>
        <body>
            <div class="card">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-wallet text-blue-500 ml-2"></i> إضافة محفظة جديدة</h1>
                    <a href="/wallets" class="text-blue-600 hover:text-blue-800 text-sm"><i class="fas fa-arrow-left ml-1"></i> العودة</a>
                </div>

                <form method="POST" action="/wallets/store">
                    <div class="mb-3">
                        <label class="label">اسم المحفظة <span class="text-red-500">*</span></label>
                        <input type="text" name="wallet_name" placeholder="مثل: فودافون كاش" class="input-field" required>
                    </div>

                    <div class="mb-3">
                        <label class="label">النوع <span class="text-red-500">*</span></label>
                        <select name="wallet_type" class="input-field" required>
                            <option value="mobile_wallet">محفظة جوال</option>
                            <option value="bank_account">حساب بنكي</option>
                            <option value="cash">كاش (خزينة)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="label">رقم الحساب</label>
                        <input type="text" name="account_number" placeholder="رقم الحساب أو رقم المحفظة" class="input-field">
                    </div>

                    <div class="mb-3">
                        <label class="label">اسم المالك</label>
                        <input type="text" name="owner_name" placeholder="اسم صاحب الحساب" class="input-field">
                    </div>

                    <div class="mb-3">
                        <label class="label">الرصيد الابتدائي</label>
                        <input type="number" step="0.01" name="initial_balance" value="0" class="input-field">
                    </div>

                    <div class="mb-4">
                        <label class="label">ملاحظات</label>
                        <textarea name="notes" class="input-field" rows="2" placeholder="ملاحظات إضافية..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="fas fa-save ml-2"></i> إضافة المحفظة</button>
                </form>
            </div>
        </body>
        </html>
        <?php
    }

    // ============================================================
    // 💾 حفظ المحفظة
    // ============================================================
    public function store()
    {
        $wallet_name = trim($_POST['wallet_name']);
        $wallet_type = $_POST['wallet_type'];
        $account_number = isset($_POST['account_number']) ? trim($_POST['account_number']) : null;
        $owner_name = isset($_POST['owner_name']) ? trim($_POST['owner_name']) : null;
        $initial_balance = isset($_POST['initial_balance']) ? (float) $_POST['initial_balance'] : 0;
        $notes = isset($_POST['notes']) ? trim($_POST['notes']) : '';

        if (empty($wallet_name) || empty($wallet_type)) {
            die("<h1>خطأ</h1><p>اسم المحفظة والنوع مطلوبان</p><a href='/wallets/create'>العودة</a>");
        }

        $stmt = $this->db->prepare("
            INSERT INTO wallets (wallet_name, wallet_type, account_number, owner_name, current_balance, notes)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$wallet_name, $wallet_type, $account_number, $owner_name, $initial_balance, $notes]);

        header("Location: /wallets");
        exit;
    }

    // ============================================================
    // ✏️ تعديل محفظة
    // ============================================================
    public function edit($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM wallets WHERE id = ?");
        $stmt->execute([$id]);
        $wallet = $stmt->fetch();

        if (!$wallet) {
            die("<h1>المحفظة غير موجودة</h1><a href='/wallets'>العودة</a>");
        }

        ?>
        <!DOCTYPE html>
        <html dir="rtl" lang="ar">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>تعديل محفظة</title>
            <script src="https://cdn.tailwindcss.com"></script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
            <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
            <style>
                * { font-family: 'Tajawal', sans-serif; }
                body { background: #f0f4f8; padding: 20px; }
                .card { background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); padding: 24px; max-width: 600px; margin: 0 auto; }
                .input-field { border: 2px solid #e2e8f0; border-radius: 10px; padding: 8px 14px; width: 100%; font-size: 14px; transition: 0.3s; }
                .input-field:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); outline: none; }
                .label { font-size: 13px; font-weight: 600; color: #334155; display: block; margin-bottom: 4px; }
                .btn { padding: 10px 20px; border-radius: 10px; font-weight: 600; font-size: 14px; transition: 0.3s; border: none; cursor: pointer; }
                .btn-primary { background: #3b82f6; color: white; width: 100%; }
                .btn-primary:hover { background: #2563eb; transform: translateY(-2px); box-shadow: 0 4px 15px rgba(37,99,235,0.3); }
            </style>
        </head>
        <body>
            <div class="card">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-edit text-amber-500 ml-2"></i> تعديل محفظة: <?php echo $wallet['wallet_name']; ?></h1>
                    <a href="/wallets" class="text-blue-600 hover:text-blue-800 text-sm"><i class="fas fa-arrow-left ml-1"></i> العودة</a>
                </div>

                <form method="POST" action="/wallets/update">
                    <input type="hidden" name="id" value="<?php echo $wallet['id']; ?>">

                    <div class="mb-3">
                        <label class="label">اسم المحفظة <span class="text-red-500">*</span></label>
                        <input type="text" name="wallet_name" value="<?php echo $wallet['wallet_name']; ?>" class="input-field" required>
                    </div>

                    <div class="mb-3">
                        <label class="label">النوع <span class="text-red-500">*</span></label>
                        <select name="wallet_type" class="input-field" required>
                            <option value="mobile_wallet" <?php echo ($wallet['wallet_type'] == 'mobile_wallet') ? 'selected' : ''; ?>>محفظة جوال</option>
                            <option value="bank_account" <?php echo ($wallet['wallet_type'] == 'bank_account') ? 'selected' : ''; ?>>حساب بنكي</option>
                            <option value="cash" <?php echo ($wallet['wallet_type'] == 'cash') ? 'selected' : ''; ?>>كاش (خزينة)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="label">رقم الحساب</label>
                        <input type="text" name="account_number" value="<?php echo $wallet['account_number']; ?>" class="input-field">
                    </div>

                    <div class="mb-3">
                        <label class="label">اسم المالك</label>
                        <input type="text" name="owner_name" value="<?php echo $wallet['owner_name']; ?>" class="input-field">
                    </div>

                    <div class="mb-3">
                        <label class="label">الحالة</label>
                        <select name="is_active" class="input-field">
                            <option value="1" <?php echo $wallet['is_active'] ? 'selected' : ''; ?>>🟢 نشط</option>
                            <option value="0" <?php echo !$wallet['is_active'] ? 'selected' : ''; ?>>🔴 غير نشط</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="label">ملاحظات</label>
                        <textarea name="notes" class="input-field" rows="2"><?php echo $wallet['notes']; ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="fas fa-save ml-2"></i> تحديث المحفظة</button>
                </form>
            </div>
        </body>
        </html>
        <?php
    }

    // ============================================================
    // 🔄 تحديث المحفظة
    // ============================================================
    public function update()
    {
        $id = (int) $_POST['id'];
        $wallet_name = trim($_POST['wallet_name']);
        $wallet_type = $_POST['wallet_type'];
        $account_number = isset($_POST['account_number']) ? trim($_POST['account_number']) : null;
        $owner_name = isset($_POST['owner_name']) ? trim($_POST['owner_name']) : null;
        $is_active = isset($_POST['is_active']) ? (int) $_POST['is_active'] : 1;
        $notes = isset($_POST['notes']) ? trim($_POST['notes']) : '';

        $stmt = $this->db->prepare("
            UPDATE wallets 
            SET wallet_name = ?, wallet_type = ?, account_number = ?, owner_name = ?, 
                is_active = ?, notes = ?, updated_at = NOW()
            WHERE id = ?
        ");
        $stmt->execute([$wallet_name, $wallet_type, $account_number, $owner_name, $is_active, $notes, $id]);

        header("Location: /wallets");
        exit;
    }

    // ============================================================
    // 🗑️ حذف محفظة
    // ============================================================
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM wallets WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: /wallets");
        exit;
    }

    // ============================================================
    // 📊 حركات محفظة
    // ============================================================
    public function transactions($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM wallets WHERE id = ?");
        $stmt->execute([$id]);
        $wallet = $stmt->fetch();

        if (!$wallet) {
            die("<h1>المحفظة غير موجودة</h1><a href='/wallets'>العودة</a>");
        }

        $stmt = $this->db->prepare("
            SELECT wt.*, u.full_name as created_by_name
            FROM wallet_transactions wt
            LEFT JOIN users u ON wt.created_by = u.id
            WHERE wt.wallet_id = ?
            ORDER BY wt.id DESC
        ");
        $stmt->execute([$id]);
        $transactions = $stmt->fetchAll();

        $typeNames = [
            'deposit' => 'إيداع',
            'withdraw' => 'سحب',
            'transfer' => 'تحويل',
            'payment' => 'دفع',
            'fawry' => 'خدمة فوري'
        ];

        ?>
        <!DOCTYPE html>
        <html dir="rtl" lang="ar">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>حركات المحفظة</title>
            <script src="https://cdn.tailwindcss.com"></script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
            <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
            <style>
                * { font-family: 'Tajawal', sans-serif; }
                body { background: #f0f4f8; padding: 20px; }
                .card { background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); padding: 24px; max-width: 1000px; margin: 0 auto; }
                .input-field { border: 2px solid #e2e8f0; border-radius: 10px; padding: 8px 14px; width: 100%; font-size: 14px; transition: 0.3s; }
                .input-field:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); outline: none; }
                .label { font-size: 13px; font-weight: 600; color: #334155; display: block; margin-bottom: 4px; }
                .btn { padding: 8px 16px; border-radius: 10px; font-weight: 600; font-size: 14px; transition: 0.3s; border: none; cursor: pointer; }
                .btn-primary { background: #3b82f6; color: white; }
                .btn-primary:hover { background: #2563eb; transform: translateY(-2px); box-shadow: 0 4px 15px rgba(37,99,235,0.3); }
                .btn-success { background: #22c55e; color: white; }
                .btn-success:hover { background: #16a34a; transform: translateY(-2px); }
                .type-badge { padding: 2px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; }
                .type-deposit { background: #d1fae5; color: #059669; }
                .type-withdraw { background: #fee2e2; color: #dc2626; }
                .type-transfer { background: #dbeafe; color: #2563eb; }
                .type-payment { background: #fef3c7; color: #d97706; }
                .type-fawry { background: #f3e8ff; color: #7c3aed; }
                .table-row:hover { background: #f8fafc; }
            </style>
        </head>
        <body>
            <div class="card">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">💳 <?php echo $wallet['wallet_name']; ?></h1>
                        <p class="text-sm text-gray-500">الرصيد الحالي: <span class="font-bold <?php echo $wallet['current_balance'] >= 0 ? 'text-green-600' : 'text-red-600'; ?>"><?php echo number_format($wallet['current_balance'], 2); ?> جنيه</span></p>
                    </div>
                    <div class="flex gap-2">
                        <a href="#addTransaction" class="btn btn-success" onclick="document.getElementById('addForm').style.display='block'; this.style.display='none';">
                            <i class="fas fa-plus"></i> إضافة حركة
                        </a>
                        <a href="/wallets" class="text-blue-600 hover:text-blue-800 text-sm"><i class="fas fa-arrow-left ml-1"></i> العودة</a>
                    </div>
                </div>

                <!-- نموذج إضافة حركة -->
                <div id="addForm" style="display:none;" class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="font-semibold text-gray-700 mb-3"><i class="fas fa-plus-circle text-green-500"></i> إضافة حركة جديدة</h3>
                    <form method="POST" action="/wallets/add-transaction" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                        <input type="hidden" name="wallet_id" value="<?php echo $wallet['id']; ?>">
                        <div>
                            <label class="label">النوع</label>
                            <select name="transaction_type" class="input-field" required>
                                <option value="deposit">إيداع</option>
                                <option value="withdraw">سحب</option>
                                <option value="transfer">تحويل</option>
                                <option value="payment">دفع</option>
                                <option value="fawry">فوري</option>
                            </select>
                        </div>
                        <div>
                            <label class="label">المبلغ</label>
                            <input type="number" step="0.01" name="amount" class="input-field" required>
                        </div>
                        <div>
                            <label class="label">العمولة (يدوي)</label>
                            <input type="number" step="0.01" name="fee" value="0" class="input-field">
                        </div>
                        <div>
                            <label class="label">الوصف</label>
                            <input type="text" name="description" placeholder="وصف الحركة" class="input-field">
                        </div>
                        <div class="md:col-span-4">
                            <button type="submit" class="btn btn-primary w-full">✅ إضافة الحركة</button>
                            <button type="button" onclick="document.getElementById('addForm').style.display='none'; document.querySelector('.btn-success').style.display='inline-block';" class="btn btn-secondary mt-2">إلغاء</button>
                        </div>
                    </form>
                </div>

                <!-- جدول الحركات -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">#</th>
                                <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">النوع</th>
                                <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">المبلغ</th>
                                <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">العمولة</th>
                                <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">الرصيد بعد</th>
                                <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">الوصف</th>
                                <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">بواسطة</th>
                                <th class="px-4 py-3 text-right text-xs text-gray-500 font-medium">التاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($transactions)): ?>
                                <tr><td colspan="8" class="px-6 py-8 text-center text-gray-400">📭 لا توجد حركات</td></tr>
                            <?php else: ?>
                                <?php foreach ($transactions as $t): ?>
                                    <?php
                                    $typeClass = 'type-' . $t['transaction_type'];
                                    $typeText = $typeNames[$t['transaction_type']] ?? $t['transaction_type'];
                                    $amountColor = ($t['transaction_type'] == 'deposit' || $t['transaction_type'] == 'payment' || $t['transaction_type'] == 'fawry') ? 'text-green-600' : 'text-red-600';
                                    ?>
                                    <tr class="table-row transition">
                                        <td class="px-4 py-3"><?php echo $t['id']; ?></td>
                                        <td class="px-4 py-3"><span class="type-badge <?php echo $typeClass; ?>"><?php echo $typeText; ?></span></td>
                                        <td class="px-4 py-3 font-bold <?php echo $amountColor; ?>"><?php echo number_format($t['amount'], 2); ?></td>
                                        <td class="px-4 py-3 text-sm text-gray-500"><?php echo number_format($t['fee'], 2); ?></td>
                                        <td class="px-4 py-3 font-bold"><?php echo number_format($t['balance_after'], 2); ?></td>
                                        <td class="px-4 py-3 text-sm"><?php echo $t['description'] ?? '—'; ?></td>
                                        <td class="px-4 py-3 text-sm"><?php echo $t['created_by_name'] ?? '—'; ?></td>
                                        <td class="px-4 py-3 text-sm text-gray-400"><?php echo date('Y-m-d H:i', strtotime($t['transaction_date'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </body>
        </html>
        <?php
    }

    // ============================================================
    // ➕ إضافة حركة مالية
    // ============================================================
    public function addTransaction()
    {
        $wallet_id = (int) $_POST['wallet_id'];
        $transaction_type = $_POST['transaction_type'];
        $amount = (float) $_POST['amount'];
        $fee = isset($_POST['fee']) ? (float) $_POST['fee'] : 0;
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $userId = $_SESSION['user_id'];

        if ($amount <= 0) {
            die("<h1>خطأ</h1><p>المبلغ يجب أن يكون أكبر من صفر</p><a href='/wallets/transactions/$wallet_id'>العودة</a>");
        }

        // جلب الرصيد الحالي
        $stmt = $this->db->prepare("SELECT current_balance FROM wallets WHERE id = ?");
        $stmt->execute([$wallet_id]);
        $wallet = $stmt->fetch();

        if (!$wallet) {
            die("<h1>خطأ</h1><p>المحفظة غير موجودة</p><a href='/wallets'>العودة</a>");
        }

        $currentBalance = $wallet['current_balance'];
        $newBalance = $currentBalance;

        // حساب الرصيد الجديد حسب نوع الحركة
        $isIncoming = in_array($transaction_type, ['deposit', 'payment', 'fawry']);
        if ($isIncoming) {
            $newBalance = $currentBalance + $amount - $fee;
        } else {
            $newBalance = $currentBalance - $amount - $fee;
        }

        try {
            $this->db->beginTransaction();

            // تسجيل الحركة
            $stmt = $this->db->prepare("
                INSERT INTO wallet_transactions 
                (wallet_id, transaction_type, amount, fee, balance_after, description, created_by, transaction_date)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([$wallet_id, $transaction_type, $amount, $fee, $newBalance, $description, $userId]);

            // تحديث رصيد المحفظة
            $stmt = $this->db->prepare("UPDATE wallets SET current_balance = ? WHERE id = ?");
            $stmt->execute([$newBalance, $wallet_id]);

            $this->db->commit();
            header("Location: /wallets/transactions/$wallet_id");
            exit;

        } catch (\Exception $e) {
            $this->db->rollBack();
            echo "<h1>خطأ</h1>";
            echo "<p>" . $e->getMessage() . "</p>";
            echo "<a href='/wallets/transactions/$wallet_id'>العودة</a>";
        }
    }
    // نموذج استلام دفعة
public function depositForm()
{
    $stmt = $this->db->query("SELECT id, wallet_name FROM wallets WHERE is_active = 1");
    $wallets = $stmt->fetchAll();
    ?>
    <!DOCTYPE html>
    <html dir="rtl" lang="ar">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>استلام دفعة</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    </head>
    <body class="bg-gray-100 p-8">
        <div class="max-w-lg mx-auto bg-white rounded-xl shadow-sm p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-4"><i class="fas fa-hand-holding-usd text-green-500 ml-2"></i> استلام دفعة من عميل</h1>
            <form method="POST" action="/wallet/deposit-store">
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">اسم العميل</label>
                    <input type="text" name="customer_name" placeholder="اسم العميل" class="w-full border rounded-lg px-3 py-2" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">المبلغ</label>
                    <input type="number" step="0.01" name="amount" placeholder="0.00" class="w-full border rounded-lg px-3 py-2" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">المحفظة المستلمة</label>
                    <select name="wallet_id" class="w-full border rounded-lg px-3 py-2" required>
                        <option value="">اختر المحفظة...</option>
                        <?php foreach ($wallets as $w): ?>
                            <option value="<?php echo $w['id']; ?>"><?php echo $w['wallet_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">الوصف</label>
                    <input type="text" name="description" placeholder="سبب الدفعة" class="w-full border rounded-lg px-3 py-2">
                </div>
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg transition">✅ تسجيل الدفعة</button>
                <a href="/wallets" class="block text-center text-sm text-blue-600 mt-3">← العودة للمحافظ</a>
            </form>
        </div>
    </body>
    </html>
    <?php
}

public function depositStore()
{
    $wallet_id = (int) $_POST['wallet_id'];
    $amount = (float) $_POST['amount'];
    $customer_name = trim($_POST['customer_name']);
    $description = isset($_POST['description']) ? trim($_POST['description']) : 'دفعة من عميل';

    if ($amount <= 0) {
        die("<h1>خطأ</h1><p>المبلغ يجب أن يكون أكبر من صفر</p>");
    }

    // جلب الرصيد الحالي
    $stmt = $this->db->prepare("SELECT current_balance FROM wallets WHERE id = ?");
    $stmt->execute([$wallet_id]);
    $wallet = $stmt->fetch();
    $newBalance = $wallet['current_balance'] + $amount;

    try {
        $this->db->beginTransaction();

        // تسجيل الحركة
        $stmt = $this->db->prepare("
            INSERT INTO wallet_transactions 
            (wallet_id, transaction_type, amount, fee, balance_after, description, created_by, transaction_date)
            VALUES (?, 'deposit', ?, 0, ?, ?, ?, NOW())
        ");
        $stmt->execute([$wallet_id, $amount, $newBalance, "دفعة من $customer_name - $description", $_SESSION['user_id']]);

        // تحديث الرصيد
        $stmt = $this->db->prepare("UPDATE wallets SET current_balance = ? WHERE id = ?");
        $stmt->execute([$newBalance, $wallet_id]);

        $this->db->commit();
        header("Location: /wallets/transactions/$wallet_id?success=1");
        exit;

    } catch (\Exception $e) {
        $this->db->rollBack();
        echo "<h1>خطأ</h1><p>" . $e->getMessage() . "</p>";
    }
}
}