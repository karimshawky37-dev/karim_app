<?php
namespace App\Controllers;

use App\Services\AuditService;

abstract class BaseController
{
    protected $db;
    protected $audit;
    protected $userId;
    protected $userName;
    protected $userRole;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }

        $this->userId = $_SESSION['user_id'];
        $this->userName = $_SESSION['full_name'] ?? 'موظف';
        $this->userRole = $_SESSION['role'] ?? 'guest';

        $this->db = \App\Config\Database::getInstance()->getConnection();
        $this->audit = new AuditService();
    }

    protected function checkPermission(string $permission): bool
    {
        if ($this->userRole === 'admin') return true;
        try {
            $stmt = $this->db->prepare("SELECT 1 FROM permissions WHERE role = ? AND permission = ?");
            $stmt->execute([$this->userRole, $permission]);
            return (bool) $stmt->fetch();
        } catch (\PDOException $e) {
            return true;
        }
    }

    protected function requirePermission(string $permission): void
    {
        // السماح للفنيين بتجاوز بعض الصلاحيات
        if ($this->userRole === 'technician') {
            $allowedForTechnician = ['view_devices', 'edit_devices', 'view_attendance'];
            if (in_array($permission, $allowedForTechnician)) {
                return;
            }
        }

        if (!$this->checkPermission($permission)) {
            http_response_code(403);
            die("<h1>⛔ غير مصرح</h1><p>ليس لديك صلاحية للوصول إلى هذه الصفحة.</p><a href='/'>العودة للرئيسية</a>");
        }
    }

    protected function view(string $view, array $data = []): void
    {
        extract($data);
        extract([
            'userId' => $this->userId,
            'userName' => $this->userName,
            'userRole' => $this->userRole
        ]);

        $viewFile = APP_PATH . "/Views/{$view}.php";
        if (!file_exists($viewFile)) {
            die("⚠️ ملف الـ View غير موجود: {$viewFile}");
        }

        $content = $viewFile;
        $layoutFile = APP_PATH . "/Views/layouts/main.php";
        if (file_exists($layoutFile)) {
            include $layoutFile;
        } else {
            include $content;
        }
    }

    protected function json(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function redirect(string $url, string $message = null, string $type = 'success'): void
    {
        if ($message) {
            $_SESSION['flash_message'] = $message;
            $_SESSION['flash_type'] = $type;
        }
        header("Location: {$url}");
        exit;
    }

    protected function sendNotification(int $userId, string $type, string $title, string $message, string $link = null): void
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO notifications (user_id, type, title, message, link, created_at) 
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([$userId, $type, $title, $message, $link]);
        } catch (\Exception $e) {
            error_log("فشل إرسال الإشعار: " . $e->getMessage());
        }
    }

    protected function sendNotificationToRole(string $role, string $type, string $title, string $message, string $link = null): void
    {
        try {
            $stmt = $this->db->prepare("SELECT id FROM users WHERE role = ? AND is_active = 1");
            $stmt->execute([$role]);
            $users = $stmt->fetchAll();
            foreach ($users as $user) {
                $this->sendNotification($user['id'], $type, $title, $message, $link);
            }
        } catch (\Exception $e) {
            error_log("فشل إرسال الإشعار للمجموعة: " . $e->getMessage());
        }
    }
}