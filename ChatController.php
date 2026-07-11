<?php
namespace App\Controllers;

use App\Config\Database;

class ChatController
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

    public function index()
    {
        $userId = $_SESSION['user_id'];
        $userName = $_SESSION['full_name'];
        $userRole = $_SESSION['role'];

        // جلب جهات الاتصال مع عدد الرسائل غير المقروءة لكل جهة
        if ($userRole === 'admin') {
            $stmt = $this->db->prepare("
                SELECT 
                    u.id, 
                    u.full_name, 
                    u.role,
                    (SELECT COUNT(*) FROM internal_messages m 
                     WHERE m.sender_id = u.id 
                       AND (m.receiver_id = ? OR m.receiver_id IS NULL) 
                       AND m.is_read = 0) as unread_count
                FROM users u
                WHERE u.role IN ('technician', 'sales', 'accountant') AND u.id != ?
                ORDER BY u.full_name
            ");
            $stmt->execute([$userId, $userId]);
            $contacts = $stmt->fetchAll();
        } else {
            // الفني يشوف المدير فقط
            $stmt = $this->db->prepare("
                SELECT 
                    u.id, 
                    u.full_name, 
                    u.role,
                    (SELECT COUNT(*) FROM internal_messages m 
                     WHERE m.sender_id = u.id 
                       AND (m.receiver_id = ? OR m.receiver_id IS NULL) 
                       AND m.is_read = 0) as unread_count
                FROM users u
                WHERE u.role = 'admin' AND u.id != ?
                ORDER BY u.full_name
            ");
            $stmt->execute([$userId, $userId]);
            $contacts = $stmt->fetchAll();
        }

        // إجمالي الرسائل غير المقروءة
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM internal_messages 
            WHERE (receiver_id = ? OR receiver_id IS NULL) AND is_read = 0 AND sender_id != ?
        ");
        $stmt->execute([$userId, $userId]);
        $totalUnread = $stmt->fetch()['count'] ?? 0;

        echo '
        <!DOCTYPE html>
        <html dir="rtl" lang="ar">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>💬 الشات الداخلي</title>
            <script src="https://cdn.tailwindcss.com"></script>
            <style>
                body { margin: 0; background: #e5e7eb; font-family: sans-serif; }
                .chat-app { display: flex; height: 100vh; }
                .sidebar-chat { width: 260px; background: #0f172a; color: #e2e8f0; display: flex; flex-direction: column; flex-shrink: 0; }
                .sidebar-header { padding: 16px; border-bottom: 1px solid #1e293b; }
                .sidebar-header h2 { font-size: 18px; font-weight: bold; margin: 0; }
                .sidebar-header p { font-size: 12px; color: #94a3b8; margin: 0; }
                .contact-list { flex: 1; overflow-y: auto; padding: 8px 0; }
                .contact-item { padding: 10px 16px; cursor: pointer; transition: 0.2s; border-right: 3px solid transparent; display: flex; align-items: center; gap: 10px; }
                .contact-item:hover { background: #1e293b; }
                .contact-item.active { background: #1e293b; border-right-color: #3b82f6; }
                .contact-avatar { width: 36px; height: 36px; border-radius: 50%; background: #334155; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 14px; color: white; flex-shrink: 0; }
                .chat-main { flex: 1; display: flex; flex-direction: column; background: #f8fafc; }
                .chat-header { padding: 12px 20px; background: white; border-bottom: 1px solid #e2e8f0; }
                .chat-header h3 { margin: 0; font-size: 16px; font-weight: 600; }
                .chat-header p { margin: 0; font-size: 12px; color: #64748b; }
                .chat-messages { flex: 1; overflow-y: auto; padding: 20px; display: flex; flex-direction: column; gap: 8px; }
                .message-bubble { max-width: 70%; padding: 10px 14px; border-radius: 12px; word-wrap: break-word; }
                .message-sent { background: #2563eb; color: white; align-self: flex-end; border-bottom-left-radius: 4px; }
                .message-received { background: white; color: #1e293b; align-self: flex-start; border-bottom-right-radius: 4px; border: 1px solid #e2e8f0; }
                .message-info { font-size: 10px; opacity: 0.7; margin-top: 4px; }
                .message-sent .message-info { color: #bfdbfe; }
                .message-received .message-info { color: #94a3b8; }
                .chat-input-area { padding: 12px 20px; background: white; border-top: 1px solid #e2e8f0; display: flex; gap: 10px; align-items: center; }
                .chat-input-area input { flex: 1; border: 1px solid #e2e8f0; border-radius: 24px; padding: 8px 16px; font-size: 14px; outline: none; }
                .chat-input-area input:focus { border-color: #2563eb; }
                .chat-input-area button { background: #2563eb; color: white; border: none; border-radius: 24px; padding: 8px 24px; font-size: 14px; font-weight: 600; cursor: pointer; }
                .chat-input-area button:hover { background: #1d4ed8; }
                .empty-state { text-align: center; padding: 40px; color: #94a3b8; }
                .empty-state span { font-size: 48px; display: block; margin-bottom: 12px; }
                .sidebar-footer { padding: 12px 16px; border-top: 1px solid #1e293b; font-size: 12px; color: #64748b; text-align: center; }
                .sidebar-footer a { color: #94a3b8; text-decoration: none; }
                .badge-unread { background: #ef4444; color: white; font-size: 10px; padding: 1px 8px; border-radius: 50%; margin-right: 6px; font-weight: bold; }
                .badge-unread-empty { display: none; }
            </style>
        </head>
        <body>
        <div class="chat-app">
            <div class="sidebar-chat">
                <div class="sidebar-header">
                    <h2>💬 الشات <span class="badge-unread" id="totalBadge">' . ($totalUnread > 0 ? $totalUnread : '') . '</span></h2>
                    <p>' . $userName . ' · ' . ($userRole == 'admin' ? 'مدير' : 'فني') . '</p>
                </div>
                <div class="contact-list" id="contactList">
                    <div class="contact-item active" data-id="0" onclick="selectContact(0, \'الجميع\')">
                        <div class="contact-avatar" style="background:#475569;">📢</div>
                        <div>
                            <div class="contact-name">الجميع</div>
                            <div style="font-size:11px;color:#94a3b8;">قناة عامة</div>
                        </div>
                        <span class="badge-unread" id="badge-0">0</span>
                    </div>
        ';

        foreach ($contacts as $c) {
            $roleMap = ['technician' => 'فني', 'sales' => 'مبيعات', 'accountant' => 'محاسب', 'admin' => 'مدير'];
            $role = $roleMap[$c['role']] ?? $c['role'];
            $initial = mb_substr($c['full_name'], 0, 1);
            $unread = $c['unread_count'] ?? 0;
            echo '
                    <div class="contact-item" data-id="' . $c['id'] . '" onclick="selectContact(' . $c['id'] . ', \'' . addslashes($c['full_name']) . '\')">
                        <div class="contact-avatar">' . $initial . '</div>
                        <div>
                            <div class="contact-name">' . $c['full_name'] . '</div>
                            <div style="font-size:11px;color:#94a3b8;">' . $role . '</div>
                        </div>
                        <span class="badge-unread" id="badge-' . $c['id'] . '" ' . ($unread == 0 ? 'style="display:none;"' : '') . '>' . $unread . '</span>
                    </div>
            ';
        }

        echo '
                </div>
                <div class="sidebar-footer"><a href="/">← العودة للرئيسية</a></div>
            </div>

            <div class="chat-main">
                <div class="chat-header">
                    <h3 id="chatTitle">📢 القناة العامة</h3>
                    <p id="chatSubtitle">جميع الموظفين</p>
                </div>
                <div class="chat-messages" id="messageContainer">
                    <div class="empty-state"><span>💬</span> اختر محادثة لعرض الرسائل</div>
                </div>
                <div class="chat-input-area">
                    <input type="text" id="chatInput" placeholder="اكتب رسالتك..." onkeypress="if(event.key===\'Enter\') sendMessage()">
                    <button onclick="sendMessage()">إرسال</button>
                </div>
            </div>
        </div>

        <script>
        var currentReceiver = 0;
        var currentReceiverName = "الجميع";
        var lastMessageId = 0;

        // طلب الإذن للإشعارات
        if (window.Notification && Notification.permission !== "granted") {
            Notification.requestPermission();
        }

        function playNotificationSound() {
            try {
                var audio = new Audio("data:audio/wav;base64,UklGRlQAAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoAAACFhYWFhYWFhYWFhYWFhYWFhYWFhYWFhYWFhYWFhYWFhYWFhYWFhYWFhYWFhYWFhYWFhYWFhQ==");
                audio.play();
            } catch(e) {}
        }

        function showNotification(title, body) {
            if (window.Notification && Notification.permission === "granted") {
                new Notification(title, { body: body.substring(0, 100), icon: "🔔" });
            } else {
                var toast = document.createElement("div");
                toast.style.cssText = "position:fixed; bottom:80px; right:20px; background:#2563eb; color:white; padding:12px 20px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.3); z-index:9999; font-size:14px; animation: slideUp 0.3s;";
                toast.innerText = "📩 " + title + ": " + body;
                document.body.appendChild(toast);
                setTimeout(function() { toast.remove(); }, 5000);
            }
            playNotificationSound();
        }

        function selectContact(id, name) {
            currentReceiver = id;
            currentReceiverName = name;
            document.getElementById("chatTitle").innerText = name;
            document.getElementById("chatSubtitle").innerText = id === 0 ? "جميع الموظفين" : "محادثة خاصة";
            var items = document.querySelectorAll(".contact-item");
            for (var i = 0; i < items.length; i++) {
                items[i].classList.remove("active");
            }
            var activeItem = document.querySelector(".contact-item[data-id=\"" + id + "\"]");
            if (activeItem) activeItem.classList.add("active");
            // إزالة البادج عند فتح المحادثة
            var badge = document.getElementById("badge-" + id);
            if (badge) {
                badge.style.display = "none";
                badge.innerText = "0";
            }
            loadMessages();
        }

        function loadMessages() {
            var container = document.getElementById("messageContainer");
            fetch("/chat/messages?receiver=" + currentReceiver)
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    if (!data || data.length === 0) {
                        container.innerHTML = "<div class=\"empty-state\"><span>📭</span> لا توجد رسائل بعد</div>";
                        return;
                    }
                    var html = "";
                    for (var i = 0; i < data.length; i++) {
                        var msg = data[i];
                        var isMine = msg.sender_id == ' . $_SESSION['user_id'] . ';
                        var senderName = msg.sender_name || "نظام";
                        // إشعار إذا كانت رسالة جديدة وليس مني
                        if (msg.id > lastMessageId && !isMine) {
                            showNotification(senderName, msg.message);
                        }
                        html += "<div class=\"message-bubble " + (isMine ? "message-sent" : "message-received") + "\">";
                        html += "<div style=\"font-weight:600; font-size:12px; margin-bottom:2px;\">" + (isMine ? "أنت" : senderName) + "</div>";
                        html += msg.message;
                        html += "<div class=\"message-info\"><span>" + (msg.subject || "عام") + "</span><span>" + new Date(msg.created_at).toLocaleTimeString("ar-EG") + "</span></div>";
                        html += "</div>";
                        if (msg.id > lastMessageId) lastMessageId = msg.id;
                    }
                    container.innerHTML = html;
                    container.scrollTop = container.scrollHeight;
                    // تحديث كل البادجات
                    updateAllBadges();
                });
        }

        function updateAllBadges() {
            fetch("/chat/unread-all")
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    // تحديث البادج الإجمالي
                    var total = data.total_unread || 0;
                    var totalBadge = document.getElementById("totalBadge");
                    if (totalBadge) {
                        totalBadge.innerText = total > 0 ? total : "";
                        totalBadge.style.display = total > 0 ? "inline" : "none";
                    }
                    // تحديث عنوان الصفحة
                    document.title = total > 0 ? "💬 (" + total + ") رسائل جديدة" : "💬 الشات الداخلي";
                    // تحديث بادجات كل جهة اتصال
                    var contacts = data.per_contact || {};
                    for (var id in contacts) {
                        var badge = document.getElementById("badge-" + id);
                        if (badge) {
                            var count = contacts[id] || 0;
                            badge.innerText = count;
                            badge.style.display = count > 0 ? "inline" : "none";
                        }
                    }
                });
        }

        function sendMessage() {
            var input = document.getElementById("chatInput");
            var text = input.value.trim();
            if (!text) return;
            
            fetch("/chat/send", {
                method: "POST",
                headers: {"Content-Type": "application/json"},
                body: JSON.stringify({
                    receiver_id: currentReceiver,
                    subject: "رسالة فورية",
                    message: text
                })
            })
            .then(function(response) { return response.json(); })
            .then(function(data) {
                if (data.success) {
                    input.value = "";
                    loadMessages();
                } else {
                    alert("فشل الإرسال: " + data.message);
                }
            });
        }

        // تحديث دوري
        setInterval(loadMessages, 5000);
        setInterval(updateAllBadges, 10000);

        window.onload = function() {
            selectContact(0, "الجميع");
            updateAllBadges();
        };
        </script>
        </body>
        </html>
        ';
    }

    public function getMessages()
    {
        $userId = $_SESSION['user_id'];
        $receiver = isset($_GET['receiver']) ? (int) $_GET['receiver'] : 0;

        if ($receiver > 0) {
            $sql = "
                SELECT m.*, u.full_name as sender_name
                FROM internal_messages m
                LEFT JOIN users u ON m.sender_id = u.id
                WHERE (m.sender_id = ? AND m.receiver_id = ?)
                   OR (m.sender_id = ? AND m.receiver_id = ?)
                   OR (m.sender_id = ? AND m.receiver_id IS NULL)
                ORDER BY m.created_at ASC LIMIT 100
            ";
            $params = [$userId, $receiver, $receiver, $userId, $userId];
        } else {
            $sql = "
                SELECT m.*, u.full_name as sender_name
                FROM internal_messages m
                LEFT JOIN users u ON m.sender_id = u.id
                WHERE m.receiver_id IS NULL 
                   OR (m.receiver_id = ? AND m.sender_id = ?)
                ORDER BY m.created_at ASC LIMIT 100
            ";
            $params = [$userId, $userId];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $messages = $stmt->fetchAll();

        // تعيين الرسائل كمقروءة إذا كانت موجهة للمستخدم الحالي
        foreach ($messages as $msg) {
            if (($msg['receiver_id'] == $userId || $msg['receiver_id'] === null) && $msg['sender_id'] != $userId) {
                if (!$msg['is_read']) {
                    $upd = $this->db->prepare("UPDATE internal_messages SET is_read = 1, read_at = NOW() WHERE id = ?");
                    $upd->execute([$msg['id']]);
                }
            }
        }

        header('Content-Type: application/json');
        echo json_encode($messages);
    }

    public function send()
    {
        $userId = $_SESSION['user_id'];
        $json = json_decode(file_get_contents('php://input'), true);
        $receiver_id = isset($json['receiver_id']) && !empty($json['receiver_id']) ? (int) $json['receiver_id'] : null;
        $subject = isset($json['subject']) ? trim($json['subject']) : 'رسالة فورية';
        $message = isset($json['message']) ? trim($json['message']) : '';

        if (empty($message)) {
            echo json_encode(['success' => false, 'message' => 'الرسالة فارغة']);
            return;
        }

        $stmt = $this->db->prepare("
            INSERT INTO internal_messages (sender_id, receiver_id, subject, message, created_at)
            VALUES (?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$userId, $receiver_id, $subject, $message]);

        echo json_encode(['success' => true]);
    }

    // ============================================================
    // دالة لجلب عدد الرسائل غير المقروءة لكل جهة اتصال
    // ============================================================
    public function unreadAll()
    {
        $userId = $_SESSION['user_id'];

        // إجمالي غير المقروء
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM internal_messages 
            WHERE (receiver_id = ? OR receiver_id IS NULL) AND is_read = 0 AND sender_id != ?
        ");
        $stmt->execute([$userId, $userId]);
        $total = $stmt->fetch()['count'] ?? 0;

        // لكل مرسل
        $stmt = $this->db->prepare("
            SELECT 
                sender_id,
                COUNT(*) as count
            FROM internal_messages
            WHERE (receiver_id = ? OR receiver_id IS NULL) AND is_read = 0 AND sender_id != ?
            GROUP BY sender_id
        ");
        $stmt->execute([$userId, $userId]);
        $perContact = [];
        while ($row = $stmt->fetch()) {
            $perContact[$row['sender_id']] = $row['count'];
        }

        header('Content-Type: application/json');
        echo json_encode([
            'total_unread' => $total,
            'per_contact' => $perContact
        ]);
    }

    public function unreadCount()
    {
        $userId = $_SESSION['user_id'];
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM internal_messages 
            WHERE (receiver_id = ? OR receiver_id IS NULL) AND is_read = 0 AND sender_id != ?
        ");
        $stmt->execute([$userId, $userId]);
        $count = $stmt->fetch()['count'] ?? 0;
        header('Content-Type: application/json');
        echo json_encode(['unread' => $count]);
    }
}