<?php
namespace App\Controllers;

use App\Services\AuditService;

class AuditController
{
    private AuditService $audit;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header("Location: /login");
            exit;
        }
        $this->audit = new AuditService();
    }

    // عرض صفحة سجل التدقيق
    public function index()
    {
        // ====== إحصائيات سريعة ======
        $db = \App\Config\Database::getInstance()->getConnection();
        
        $stmt = $db->query("SELECT COUNT(*) as total FROM audit_logs");
        $totalLogs = $stmt->fetch()['total'] ?? 0;

        $stmt = $db->query("SELECT COUNT(*) as today FROM audit_logs WHERE DATE(created_at) = CURDATE()");
        $todayLogs = $stmt->fetch()['today'] ?? 0;

// بدلاً من $this->audit->getRecent(200);
// نجيب الوردية النشطة للمستخدم
$userId = $_SESSION['user_id'];
$stmt = $db->prepare("SELECT id FROM shifts WHERE user_id = ? AND status = 'active'");
$stmt->execute([$userId]);
$activeShift = $stmt->fetch();

if ($activeShift) {
    // عرض سجلات الوردية الحالية فقط
    $stmt = $db->prepare("SELECT * FROM audit_logs WHERE shift_id = ? ORDER BY created_at DESC LIMIT 200");
    $stmt->execute([$activeShift['id']]);
    $logs = $stmt->fetchAll();
} else {
    // لو مفيش وردية نشطة، عرض رسالة "ابدأ ورديتك"
    $logs = [];
}

// جلب الورديات المؤرشفة (للمدير)
if ($_SESSION['role'] === 'admin') {
    $stmt = $db->query("SELECT * FROM audit_logs_archive ORDER BY archived_at DESC LIMIT 100");
    $archivedLogs = $stmt->fetchAll();
}

        $stmt = $db->query("
            SELECT user_name, COUNT(*) as count 
            FROM audit_logs 
            GROUP BY user_name 
            ORDER BY count DESC 
            LIMIT 1
        ");
        $topUser = $stmt->fetch();

        $stmt = $db->query("
            SELECT action, COUNT(*) as count 
            FROM audit_logs 
            GROUP BY action 
            ORDER BY count DESC 
            LIMIT 1
        ");
        $topAction = $stmt->fetch();

        // جلب السجلات مع الفلترة
        $logs = $this->audit->getRecent(200);

        // ترجمة الأحداث والجداول
        $actionNames = [
            'create' => 'إنشاء',
            'update' => 'تعديل',
            'delete' => 'حذف',
            'login' => 'تسجيل دخول',
            'logout' => 'تسجيل خروج',
            'payment' => 'دفعة'
        ];

        $tableNames = [
            'devices' => 'الأجهزة',
            'sales' => 'الفواتير',
            'installments' => 'الأقساط',
            'inventory' => 'المخزون',
            'customers' => 'العملاء',
            'users' => 'المستخدمين',
            'wallets' => 'المحافظ',
            'installment_payments' => 'مدفوعات الأقساط',
            'repair_jobs' => 'مهام الإصلاح'

        ];

        ?>
        <!DOCTYPE html>
        <html dir="rtl" lang="ar">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>سجل التدقيق</title>
            <script src="https://cdn.tailwindcss.com"></script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
            <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
            <style>
                * { font-family: 'Tajawal', sans-serif; }
                body { background: #f0f4f8; padding: 20px; }
                .card { background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); padding: 24px; max-width: 1200px; margin: 0 auto; }
                .action-badge { padding: 2px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; }
                .action-create { background: #d1fae5; color: #059669; }
                .action-update { background: #dbeafe; color: #2563eb; }
                .action-delete { background: #fee2e2; color: #dc2626; }
                .action-login { background: #fef3c7; color: #d97706; }
                .action-logout { background: #f3e8ff; color: #7c3aed; }
                .action-payment { background: #fce7f3; color: #db2777; }
                .table-row:hover { background: #f8fafc; }
                .stat-box { background: #f8fafc; border-radius: 10px; padding: 12px 16px; text-align: center; }
                .stat-number { font-size: 24px; font-weight: 800; }
            </style>
        </head>
        <body>
            <div class="card">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-shield-alt text-blue-500 ml-2"></i> سجل التدقيق</h1>
                        <p class="text-sm text-gray-500">كل حركة في النظام مسجلة هنا</p>
                    </div>
                    <a href="/" class="text-blue-600 hover:text-blue-800 text-sm"><i class="fas fa-arrow-left ml-1"></i> العودة</a>
                </div>

<div class="flex gap-2 mb-4">
    <a href="/shift/start" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition">
        <i class="fas fa-play"></i> بدء وردية جديدة
    </a>
    <a href="/shift/end" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm transition" onclick="return confirm('هل أنت متأكد من إنهاء الوردية؟')">
        <i class="fas fa-stop"></i> إنهاء الوردية
    </a>
    <a href="/shifts" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">
        <i class="fas fa-clock"></i> كل الورديات
    </a>
</div>

                <!-- إحصائيات سريعة -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
                    <div class="stat-box border-r-4 border-blue-500">
                        <p class="text-xs text-gray-400">إجمالي السجلات</p>
                        <p class="stat-number text-blue-600"><?php echo number_format($totalLogs); ?></p>
                    </div>
                    <div class="stat-box border-r-4 border-green-500">
                        <p class="text-xs text-gray-400">اليوم</p>
                        <p class="stat-number text-green-600"><?php echo number_format($todayLogs); ?></p>
                    </div>
                    <div class="stat-box border-r-4 border-purple-500">
                        <p class="text-xs text-gray-400">أكثر مستخدم نشاط</p>
                        <p class="stat-number text-purple-600"><?php echo $topUser['user_name'] ?? '—'; ?></p>
                        <p class="text-xs text-gray-400">(<?php echo $topUser['count'] ?? 0; ?> حركة)</p>
                    </div>
                    <div class="stat-box border-r-4 border-orange-500">
                        <p class="text-xs text-gray-400">أكثر حدث</p>
                        <p class="stat-number text-orange-600"><?php echo isset($topAction['action']) ? ($actionNames[$topAction['action']] ?? $topAction['action']) : '—'; ?></p>
                        <p class="text-xs text-gray-400">(<?php echo $topAction['count'] ?? 0; ?> مرة)</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-right text-xs text-gray-500 font-medium">#</th>
                                <th class="px-3 py-2 text-right text-xs text-gray-500 font-medium">المستخدم</th>
                                <th class="px-3 py-2 text-right text-xs text-gray-500 font-medium">الدور</th>
                                <th class="px-3 py-2 text-right text-xs text-gray-500 font-medium">الحدث</th>
                                <th class="px-3 py-2 text-right text-xs text-gray-500 font-medium">الجدول</th>
                                <th class="px-3 py-2 text-right text-xs text-gray-500 font-medium">الرقم</th>
                                <th class="px-3 py-2 text-right text-xs text-gray-500 font-medium">التفاصيل</th>
                                <th class="px-3 py-2 text-right text-xs text-gray-500 font-medium">IP</th>
                                <th class="px-3 py-2 text-right text-xs text-gray-500 font-medium">التاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($logs)): ?>
                                <tr><td colspan="9" class="px-6 py-8 text-center text-gray-400">📭 لا توجد سجلات</td></tr>
                            <?php else: ?>
                                <?php foreach ($logs as $log): ?>
                                    <?php
                                    $actionClass = 'action-' . $log['action'];
                                    $actionText = $actionNames[$log['action']] ?? $log['action'];
                                    $tableText = $tableNames[$log['table_name']] ?? $log['table_name'];
                                    
                                    // عرض تفاصيل مفيدة
                                    $details = '';
                                    if ($log['new_data']) {
                                        $data = json_decode($log['new_data'], true);
                                        if (isset($data['customer_name'])) {
                                            $details = '👤 ' . $data['customer_name'];
                                        } elseif (isset($data['device_name'])) {
                                            $details = '📱 ' . $data['device_name'];
                                        } elseif (isset($data['invoice_number'])) {
                                            $details = '🧾 ' . $data['invoice_number'];
                                        } elseif (isset($data['name'])) {
                                            $details = '📦 ' . $data['name'];
                                        } elseif (isset($data['wallet_name'])) {
                                            $details = '💳 ' . $data['wallet_name'];
                                        } elseif (isset($data['total_amount'])) {
                                            $details = '💰 ' . number_format($data['total_amount'], 2) . ' ج';
                                        } elseif (isset($data['brand'])) {
                                            $details = '📱 ' . $data['brand'] . ' ' . ($data['model'] ?? '');
                                        }
                                    }
                                    ?>
                                    <tr class="table-row transition">
                                        <td class="px-3 py-2"><?php echo $log['id']; ?></td>
                                        <td class="px-3 py-2 font-medium"><?php echo $log['user_name']; ?></td>
                                        <td class="px-3 py-2 text-sm text-gray-500"><?php echo $log['user_role']; ?></td>
                                        <td class="px-3 py-2"><span class="action-badge <?php echo $actionClass; ?>"><?php echo $actionText; ?></span></td>
                                        <td class="px-3 py-2 text-sm text-gray-500"><?php echo $tableText ?? '—'; ?></td>
                                        <td class="px-3 py-2 text-sm font-mono"><?php echo $log['record_id'] ?? '—'; ?></td>
                                        <td class="px-3 py-2 text-sm text-gray-600"><?php echo $details ?: '—'; ?></td>
                                        <td class="px-3 py-2 text-sm text-gray-400 font-mono"><?php echo $log['ip_address'] ?? '—'; ?></td>
                                        <td class="px-3 py-2 text-sm text-gray-400"><?php echo date('Y-m-d H:i', strtotime($log['created_at'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 text-center text-xs text-gray-400">
                    🔒 جميع الحركات مسجلة ولا يمكن حذفها أو تعديلها
                </div>
            </div>
        </body>
        </html>
        <?php
    }
}