<?php
namespace App\Controllers;

use App\Config\Database;

class EmployeeController
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
    // 🟢 الحضور والانصراف
    // ============================================================

    public function checkIn()
    {
        $userId = $_SESSION['user_id'];
        $today = date('Y-m-d');

        $stmt = $this->db->prepare("SELECT id FROM attendance WHERE user_id = ? AND DATE(check_in) = ?");
        $stmt->execute([$userId, $today]);
        $exists = $stmt->fetch();

        if ($exists) {
            echo json_encode(['success' => false, 'message' => 'تم تسجيل الحضور اليوم بالفعل']);
            return;
        }

        $stmt = $this->db->prepare("INSERT INTO attendance (user_id, check_in, status) VALUES (?, NOW(), 'present')");
        $stmt->execute([$userId]);

        echo json_encode(['success' => true, 'message' => '✅ تم تسجيل الحضور']);
    }

    public function checkOut()
    {
        $userId = $_SESSION['user_id'];
        $today = date('Y-m-d');

        $stmt = $this->db->prepare("SELECT id, check_in FROM attendance WHERE user_id = ? AND DATE(check_in) = ? ORDER BY id DESC LIMIT 1");
        $stmt->execute([$userId, $today]);
        $record = $stmt->fetch();

        if (!$record) {
            echo json_encode(['success' => false, 'message' => 'لا يوجد سجل حضور اليوم']);
            return;
        }

        if ($record['check_out']) {
            echo json_encode(['success' => false, 'message' => 'تم تسجيل الانصراف بالفعل']);
            return;
        }

        $stmt = $this->db->prepare("UPDATE attendance SET check_out = NOW() WHERE id = ?");
        $stmt->execute([$record['id']]);

        echo json_encode(['success' => true, 'message' => '✅ تم تسجيل الانصراف']);
    }

    // عرض الحضور للمستخدم
    public function myAttendance()
    {
        $userId = $_SESSION['user_id'];
        $today = date('Y-m-d');

        // جلب سجل اليوم
        $stmt = $this->db->prepare("SELECT * FROM attendance WHERE user_id = ? AND DATE(check_in) = ?");
        $stmt->execute([$userId, $today]);
        $todayRecord = $stmt->fetch();

        // جلب آخر 30 يوم
        $stmt = $this->db->prepare("SELECT * FROM attendance WHERE user_id = ? ORDER BY check_in DESC LIMIT 30");
        $stmt->execute([$userId]);
        $records = $stmt->fetchAll();

        $isCheckedIn = $todayRecord && !$todayRecord['check_out'];
        $isCheckedOut = $todayRecord && $todayRecord['check_out'];

        // بناء التقويم
        $days = [];
        foreach ($records as $r) {
            $days[date('d', strtotime($r['check_in']))] = $r['check_out'] ? 'حاضر' : 'جزئي';
        }

        echo '
        <div class="bg-white rounded-xl shadow-sm p-4">
            <h3 class="font-semibold text-gray-700 text-sm mb-3">⏰ الحضور والانصراف</h3>
            <div class="flex flex-wrap gap-2 mb-3">
                <button onclick="checkIn()" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg text-xs transition ' . ($isCheckedIn ? 'opacity-50 cursor-not-allowed' : '') . '" ' . ($isCheckedIn ? 'disabled' : '') . '>✅ تسجيل حضور</button>
                <button onclick="checkOut()" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg text-xs transition ' . ($isCheckedOut || !$isCheckedIn ? 'opacity-50 cursor-not-allowed' : '') . '" ' . ($isCheckedOut || !$isCheckedIn ? 'disabled' : '') . '>🚪 تسجيل انصراف</button>
            </div>
            <div id="attendanceMessage" class="text-xs text-gray-600 mb-2"></div>
            <div class="text-xs text-gray-400">
                ' . ($todayRecord ? "اليوم: حضور " . date('h:i A', strtotime($todayRecord['check_in'])) . ($todayRecord['check_out'] ? " | انصراف " . date('h:i A', strtotime($todayRecord['check_out'])) : " | لم ينصرف بعد") : "لم يتم تسجيل حضور اليوم") . '
            </div>
            <div class="mt-2 flex flex-wrap gap-1">
                ' . $this->renderCalendar($days) . '
            </div>
        </div>
        <script>
            function checkIn() {
                fetch("/employee/checkin", { method: "POST" })
                    .then(r => r.json())
                    .then(data => {
                        document.getElementById("attendanceMessage").innerHTML = data.message;
                        if (data.success) setTimeout(() => location.reload(), 800);
                    });
            }
            function checkOut() {
                fetch("/employee/checkout", { method: "POST" })
                    .then(r => r.json())
                    .then(data => {
                        document.getElementById("attendanceMessage").innerHTML = data.message;
                        if (data.success) setTimeout(() => location.reload(), 800);
                    });
            }
        </script>
        ';
    }

    private function renderCalendar($days)
    {
        $html = '';
        for ($d = 1; $d <= 31; $d++) {
            $day = str_pad($d, 2, '0', STR_PAD_LEFT);
            if (isset($days[$day])) {
                $color = $days[$day] == 'حاضر' ? 'bg-green-200 text-green-800' : 'bg-yellow-200 text-yellow-800';
                $html .= "<span class='w-6 h-6 text-center text-xs font-medium rounded $color'>$day</span>";
            } else {
                $html .= "<span class='w-6 h-6 text-center text-xs text-gray-300'>$day</span>";
            }
        }
        return $html;
    }

    // ============================================================
    // 🟢 الرسائل الداخلية (شات)
    // ============================================================

    public function myMessages()
    {
        $userId = $_SESSION['user_id'];

        $stmt = $this->db->prepare("
            SELECT m.*, u.full_name as sender_name
            FROM internal_messages m
            LEFT JOIN users u ON m.sender_id = u.id
            WHERE m.receiver_id IS NULL OR m.receiver_id = ?
            ORDER BY m.created_at DESC
        ");
        $stmt->execute([$userId]);
        $messages = $stmt->fetchAll();

        $unreadCount = 0;
        foreach ($messages as $m) {
            if (!$m['is_read']) $unreadCount++;
        }

        echo '
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex justify-between items-center mb-2">
                <h3 class="font-semibold text-gray-700 text-sm">💬 الرسائل</h3>
                <span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full">' . $unreadCount . ' غير مقروءة</span>
                <button onclick="location.reload()" class="text-xs text-blue-600 hover:underline">تحديث</button>
            </div>
            <div class="max-h-48 overflow-y-auto space-y-2 text-xs">
        ';

        if (empty($messages)) {
            echo '<p class="text-gray-400 text-center py-2">📭 لا توجد رسائل</p>';
        } else {
            foreach ($messages as $msg) {
                $isUnread = !$msg['is_read'];
                $bg = $isUnread ? 'bg-blue-50 border-r-2 border-blue-500' : 'bg-white';
                echo '
                <div class="' . $bg . ' p-2 rounded-lg">
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-800">' . ($msg['sender_name'] ?? 'النظام') . '</span>
                        <span class="text-[10px] text-gray-400">' . date('h:i A', strtotime($msg['created_at'])) . '</span>
                    </div>
                    <div class="font-semibold text-gray-700">' . htmlspecialchars($msg['subject']) . '</div>
                    <div class="text-gray-600">' . nl2br(htmlspecialchars($msg['message'])) . '</div>
                    ' . ($isUnread ? '<button onclick="markRead(' . $msg['id'] . ')" class="text-[10px] text-blue-600 hover:underline">تحديد كمقروء</button>' : '') . '
                </div>
                ';
            }
        }

        echo '
            </div>
        </div>
        <script>
            function markRead(id) {
                fetch("/employee/markread", {
                    method: "POST",
                    headers: {"Content-Type": "application/json"},
                    body: JSON.stringify({id: id})
                })
                .then(r => r.json())
                .then(data => { if (data.success) location.reload(); });
            }
        </script>
        ';
    }

    public function messagesJson()
    {
        $userId = $_SESSION['user_id'];
        $stmt = $this->db->prepare("
            SELECT m.*, u.full_name as sender_name
            FROM internal_messages m
            LEFT JOIN users u ON m.sender_id = u.id
            WHERE m.receiver_id IS NULL OR m.receiver_id = ?
            ORDER BY m.created_at DESC
        ");
        $stmt->execute([$userId]);
        $messages = $stmt->fetchAll();
        header('Content-Type: application/json');
        echo json_encode($messages);
    }

    public function markRead()
    {
        $json = json_decode(file_get_contents('php://input'), true);
        $id = $json['id'] ?? 0;
        $stmt = $this->db->prepare("UPDATE internal_messages SET is_read = 1, read_at = NOW() WHERE id = ? AND (receiver_id = ? OR receiver_id IS NULL)");
        $stmt->execute([$id, $_SESSION['user_id']]);
        echo json_encode(['success' => true]);
    }

    // ============================================================
    // 🟢 نموذج إرسال رسالة للمدير
    // ============================================================

    public function sendMessageForm()
    {
        if ($_SESSION['role'] !== 'admin') {
            return '<div class="bg-white rounded-xl shadow-sm p-4 text-gray-400 text-sm">❌ غير مصرح</div>';
        }

        $stmt = $this->db->query("SELECT id, full_name, username, role FROM users WHERE role IN ('technician', 'sales', 'accountant') ORDER BY full_name");
        $employees = $stmt->fetchAll();

        echo '
        <div class="bg-white rounded-xl shadow-sm p-4">
            <h3 class="font-semibold text-gray-700 text-sm mb-3">✉️ إرسال رسالة (شات)</h3>
            <div class="space-y-2">
                <select id="chat_receiver" class="w-full border rounded-lg px-2 py-1 text-sm">
                    <option value="">📢 الجميع</option>
                    ' . $this->renderEmployeeOptions($employees) . '
                </select>
                <input type="text" id="chat_subject" placeholder="الموضوع" class="w-full border rounded-lg px-2 py-1 text-sm">
                <textarea id="chat_message" rows="2" placeholder="اكتب رسالتك..." class="w-full border rounded-lg px-2 py-1 text-sm"></textarea>
                <button onclick="sendChatMessage()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded-lg text-sm transition w-full">📤 إرسال</button>
                <div id="chatResult" class="text-xs text-gray-600"></div>
            </div>
        </div>
        <script>
            function sendChatMessage() {
                const receiver = document.getElementById("chat_receiver").value;
                const subject = document.getElementById("chat_subject").value.trim();
                const message = document.getElementById("chat_message").value.trim();
                if (!subject || !message) {
                    document.getElementById("chatResult").innerHTML = "⚠️ اكتب الموضوع والرسالة";
                    return;
                }
                fetch("/employee/send", {
                    method: "POST",
                    headers: {"Content-Type": "application/json"},
                    body: JSON.stringify({receiver_id: receiver, subject: subject, message: message})
                })
                .then(r => r.json())
                .then(data => {
                    document.getElementById("chatResult").innerHTML = data.success ? "✅ تم الإرسال" : "❌ فشل";
                    if (data.success) {
                        document.getElementById("chat_subject").value = "";
                        document.getElementById("chat_message").value = "";
                        alert("✅ تم إرسال الرسالة بنجاح!");
                    }
                });
            }
        </script>
        ';
    }

    private function renderEmployeeOptions($employees)
    {
        $html = '';
        foreach ($employees as $e) {
            $roleMap = ['technician' => 'فني', 'sales' => 'مبيعات', 'accountant' => 'محاسب'];
            $role = $roleMap[$e['role']] ?? $e['role'];
            $html .= "<option value='{$e['id']}'>{$e['full_name']} ({$role})</option>";
        }
        return $html;
    }

    public function sendMessage()
    {
        if ($_SESSION['role'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'غير مصرح']);
            return;
        }

        $json = json_decode(file_get_contents('php://input'), true);
        $receiver_id = isset($json['receiver_id']) && !empty($json['receiver_id']) ? (int) $json['receiver_id'] : null;
        $subject = trim($json['subject']);
        $message = trim($json['message']);

        if (empty($subject) || empty($message)) {
            echo json_encode(['success' => false, 'message' => 'الموضوع والرسالة مطلوبان']);
            return;
        }

        $stmt = $this->db->prepare("
            INSERT INTO internal_messages (sender_id, receiver_id, subject, message, created_at)
            VALUES (?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$_SESSION['user_id'], $receiver_id, $subject, $message]);

        echo json_encode(['success' => true]);
    }

    // ============================================================
    // 🟢 تقرير الحضور للمدير
    // ============================================================

        public function attendanceReport()
    {
        if ($_SESSION['role'] !== 'admin') {
            return '<div class="bg-white rounded-xl shadow-sm p-4 text-gray-400 text-sm">❌ غير مصرح</div>';
        }

        $today = date('Y-m-d');
        $stmt = $this->db->prepare("
            SELECT 
                u.id,
                u.full_name,
                u.role,
                a.check_in,
                a.check_out,
                CASE WHEN a.id IS NOT NULL THEN 1 ELSE 0 END as attended_today
            FROM users u
            LEFT JOIN attendance a ON u.id = a.user_id AND DATE(a.check_in) = ?
            WHERE u.role IN ('technician', 'sales', 'accountant', 'admin')
            ORDER BY u.full_name
        ");
        $stmt->execute([$today]);
        $records = $stmt->fetchAll();

        $present = 0;
        $absent = 0;
        foreach ($records as $r) {
            if ($r['attended_today']) $present++;
            else $absent++;
        }

        echo '
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex justify-between items-center mb-2">
                <h3 class="font-semibold text-gray-700 text-sm">📊 حضور اليوم (' . date('Y-m-d') . ')</h3>
                <div class="flex gap-3 text-xs">
                    <span class="text-green-600">✅ حاضر: ' . $present . '</span>
                    <span class="text-red-600">❌ غائب: ' . $absent . '</span>
                    <a href="/attendance-report" class="text-blue-600 hover:underline text-xs">عرض الكل →</a>
                </div>
            </div>
            <div class="overflow-x-auto max-h-60 overflow-y-auto">
                <table class="min-w-full divide-y divide-gray-100 text-xs">
                    <thead>
                        <tr class="bg-gray-50 sticky top-0">
                            <th class="px-2 py-1 text-right text-gray-500 font-medium">الموظف</th>
                            <th class="px-2 py-1 text-right text-gray-500 font-medium">الدور</th>
                            <th class="px-2 py-1 text-right text-gray-500 font-medium">الحضور</th>
                            <th class="px-2 py-1 text-right text-gray-500 font-medium">الانصراف</th>
                        </tr>
                    </thead>
                    <tbody>
        ';

        foreach ($records as $r) {
            $status = $r['attended_today'] ? '🟢 حاضر' : '🔴 غائب';
            $statusColor = $r['attended_today'] ? 'text-green-600' : 'text-red-600';
            $checkIn = $r['check_in'] ? date('h:i A', strtotime($r['check_in'])) : '—';
            $checkOut = $r['check_out'] ? date('h:i A', strtotime($r['check_out'])) : '—';
            $roleMap = ['technician' => 'فني', 'sales' => 'مبيعات', 'accountant' => 'محاسب', 'admin' => 'مدير'];
            $role = $roleMap[$r['role']] ?? $r['role'];
            echo "
            <tr class='hover:bg-gray-50 transition'>
                <td class='px-2 py-1 font-medium text-gray-800'>" . $r['full_name'] . "</td>
                <td class='px-2 py-1 text-gray-500'>$role</td>
                <td class='px-2 py-1 $statusColor font-medium'>$status</td>
                <td class='px-2 py-1 text-gray-600'>$checkIn | $checkOut</td>
            </tr>
            ";
        }

        echo '
                    </tbody>
                </table>
            </div>
            <div class="mt-2 text-center">
                <a href="/attendance-report" class="text-xs text-blue-600 hover:underline">عرض تقرير الحضور الكامل →</a>
            </div>
        </div>
        ';
    }
}