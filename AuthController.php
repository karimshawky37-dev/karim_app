<?php
namespace App\Controllers;

use App\Config\Database;
use App\Services\AuditService;

class AuthController extends BaseController
{
    protected $db; // ✅ تم التعديل من private إلى protected

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->db = Database::getInstance()->getConnection();
        // لا نستدعي parent::__construct() هنا لأننا لا نريد التحقق من تسجيل الدخول في صفحة login
    }

    public function login()
    {
        if (isset($_SESSION['user_id'])) {
            header("Location: /");
            exit;
        }

        // عرض صفحة تسجيل الدخول (بدون Layout)
        include VIEWS_PATH . '/auth/login.php';
        exit;
    }

    public function loginSubmit()
    {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ? AND is_active = 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user) {
            // التحقق من كلمة المرور (نص عادي أو مشفرة)
            if ($password === $user['password_hash'] || password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['full_name'] = $user['full_name'];
                
                // تحديث آخر تسجيل دخول
                $stmt = $this->db->prepare("
                    UPDATE users 
                    SET last_login = NOW(), 
                        last_login_ip = ?, 
                        last_login_device = ? 
                    WHERE id = ?
                ");
                $stmt->execute([
                    $_SERVER['REMOTE_ADDR'] ?? null,
                    $_SERVER['HTTP_USER_AGENT'] ?? null,
                    $user['id']
                ]);
                
                // تسجيل في سجل التدقيق
                $audit = new AuditService();
                $audit->logLogin($user['id'], $user['full_name']);

                header("Location: /");
                exit;
            }
        }

        header("Location: /login?error=1");
        exit;
    }

    public function logout()
    {
        // تسجيل الخروج في سجل التدقيق
        if (isset($_SESSION['user_id'])) {
            $audit = new AuditService();
            $audit->logLogout();
        }
        
        session_destroy();
        header("Location: /login?logout=1");
        exit;
    }
}