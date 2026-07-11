<?php
namespace App\Services;

use App\Config\Database;
use PDO;

class AuditService
{
    private PDO $db;
    private ?int $userId = null;
    private ?string $userName = null;
    private ?string $userRole = null;
    private ?string $ipAddress = null;
    private ?string $userAgent = null;
    private ?int $shiftId = null;

    private array $criticalActions = [
        'delete', 'update_permissions', 'role_change', 'login_failed',
        'mass_delete', 'settings_change', 'backup_restore', 'user_ban'
    ];

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->userId = $_SESSION['user_id'] ?? null;
        $this->userName = $_SESSION['full_name'] ?? 'نظام';
        $this->userRole = $_SESSION['role'] ?? 'guest';
        $this->ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
        $this->userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;

        if ($this->userId) {
            $stmt = $this->db->prepare("SELECT id FROM shifts WHERE user_id = ? AND status = 'active'");
            $stmt->execute([$this->userId]);
            $shift = $stmt->fetch();
            $this->shiftId = $shift ? (int) $shift['id'] : null;
        }
    }

    /**
     * دالة التسجيل الأساسية (تحل محل activity())
     */
    public function log(
        string $action,
        ?string $tableName = null,
        ?int $recordId = null,
        ?array $oldData = null,
        ?array $newData = null
    ): bool {
        if (!$this->userId) {
            $this->userId = 0;
            $this->userName = 'نظام';
            $this->userRole = 'system';
        }

        try {
            $stmt = $this->db->prepare("
                INSERT INTO audit_logs 
                (user_id, shift_id, user_name, user_role, action, table_name, record_id, 
                 old_data, new_data, ip_address, user_agent, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ");

            $success = $stmt->execute([
                $this->userId,
                $this->shiftId,
                $this->userName,
                $this->userRole,
                $action,
                $tableName,
                $recordId,
                $oldData ? json_encode($oldData, JSON_UNESCAPED_UNICODE) : null,
                $newData ? json_encode($newData, JSON_UNESCAPED_UNICODE) : null,
                $this->ipAddress,
                $this->userAgent
            ]);

            if ($success && $this->isCriticalAction($action, $tableName)) {
                $this->sendAlertToAdmin($action, $tableName, $recordId);
            }

            return $success;
        } catch (\Exception $e) {
            error_log("Audit log error: " . $e->getMessage());
            return false;
        }
    }

    // ===== دوال مساعدة (اختصارات) =====
    public function logCreate(string $tableName, int $recordId, array $data): void
    {
        $this->log('create', $tableName, $recordId, null, $data);
    }

    public function logUpdate(string $tableName, int $recordId, array $oldData, array $newData): void
    {
        $this->log('update', $tableName, $recordId, $oldData, $newData);
    }

    public function logDelete(string $tableName, int $recordId, array $data): void
    {
        $this->log('delete', $tableName, $recordId, $data, null);
    }

    public function logLogin(int $userId, string $userName): void
    {
        $this->userId = $userId;
        $this->userName = $userName;
        $this->log('login', 'users', $userId, null, ['status' => 'success']);
    }

    public function logLogout(): void
    {
        $this->log('logout', 'users', $this->userId, null, ['status' => 'success']);
    }

    public function logLoginFailed(string $username): void
    {
        $this->userId = 0;
        $this->userName = $username;
        $this->userRole = 'guest';
        $this->log('login_failed', 'users', null, null, ['username' => $username]);
    }

    private function isCriticalAction(string $action, ?string $tableName): bool
    {
        if (in_array($action, $this->criticalActions)) return true;
        if ($action === 'delete' && in_array($tableName, ['users', 'sales', 'devices', 'inventory', 'customers'])) {
            return true;
        }
        if ($action === 'update' && date('H') >= 22) return true;
        return false;
    }

    private function sendAlertToAdmin(string $action, ?string $tableName, ?int $recordId): void
    {
        try {
            $message = "🚨 تحذير أمني!\n";
            $message .= "الإجراء: " . $action . "\n";
            $message .= "الجدول: " . ($tableName ?? 'غير محدد') . "\n";
            $message .= "الرقم: " . ($recordId ?? 'غير محدد') . "\n";
            $message .= "المستخدم: " . $this->userName . " (" . $this->userRole . ")\n";
            $message .= "IP: " . $this->ipAddress . "\n";
            $message .= "الوقت: " . date('Y-m-d H:i:s');

            $stmt = $this->db->prepare("
                INSERT INTO notifications (user_id, type, title, message, link, created_at)
                VALUES (1, 'security', '🚨 تنبيه أمني', ?, NULL, NOW())
            ");
            $stmt->execute([$message]);
        } catch (\Exception $e) {
            error_log("Alert error: " . $e->getMessage());
        }
    }

    public function getRecent(int $limit = 200): array
    {
        $limit = (int) $limit;
        $stmt = $this->db->prepare("SELECT * FROM audit_logs ORDER BY created_at DESC LIMIT {$limit}");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getCriticalLogs(int $limit = 50): array
    {
        $limit = (int) $limit;
        $placeholders = implode(',', array_fill(0, count($this->criticalActions), '?'));
        $stmt = $this->db->prepare("
            SELECT * FROM audit_logs 
            WHERE action IN ({$placeholders})
            ORDER BY created_at DESC 
            LIMIT {$limit}
        ");
        $stmt->execute($this->criticalActions);
        return $stmt->fetchAll();
    }
}