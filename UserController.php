<?php
namespace App\Controllers;

class UserController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->requirePermission('manage_users');
    }

    // ============================================================
    // 📋 قائمة المستخدمين
    // ============================================================
    public function index()
    {
        $stmt = $this->db->query("
            SELECT id, username, full_name, email, phone, role, is_active, last_login, created_at
            FROM users
            ORDER BY id ASC
        ");
        $users = $stmt->fetchAll();

        $this->view('users/index', [
            'title' => 'إدارة المستخدمين',
            'users' => $users
        ]);
    }

    // ============================================================
    // ➕ نموذج إضافة مستخدم جديد
    // ============================================================
    public function create()
    {
        $this->view('users/create', [
            'title' => 'إضافة مستخدم جديد'
        ]);
    }

    // ============================================================
    // 💾 حفظ المستخدم الجديد
    // ============================================================
    public function store()
    {
        $username = trim($_POST['username']);
        $full_name = trim($_POST['full_name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $role = $_POST['role'];
        $password = trim($_POST['password']);

        if (empty($username) || empty($full_name) || empty($email) || empty($phone) || empty($password) || empty($role)) {
            $this->redirect('/users/create', 'جميع الحقول مطلوبة', 'error');
            return;
        }

        // التحقق من تكرار اسم المستخدم أو البريد
        $stmt = $this->db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $this->redirect('/users/create', 'اسم المستخدم أو البريد الإلكتروني مستخدم بالفعل', 'error');
            return;
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare("
            INSERT INTO users (username, full_name, email, phone, role, password_hash, is_active, created_at)
            VALUES (?, ?, ?, ?, ?, ?, 1, NOW())
        ");
        $stmt->execute([$username, $full_name, $email, $phone, $role, $hashed]);

        $userId = $this->db->lastInsertId();

        $this->audit->logCreate('users', $userId, [
            'username' => $username,
            'full_name' => $full_name,
            'role' => $role
        ]);

        $this->redirect('/users', 'تم إضافة المستخدم بنجاح', 'success');
    }

    // ============================================================
    // ✏️ نموذج تعديل مستخدم
    // ============================================================
    public function edit($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch();

        if (!$user) {
            die("<h1>المستخدم غير موجود</h1><a href='/users'>العودة</a>");
        }

        $this->view('users/edit', [
            'title' => 'تعديل مستخدم',
            'user' => $user
        ]);
    }

    // ============================================================
    // 🔄 تحديث بيانات المستخدم
    // ============================================================
    public function update()
    {
        $id = (int) $_POST['id'];
        $full_name = trim($_POST['full_name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $role = $_POST['role'];
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';

        if (empty($full_name) || empty($email) || empty($phone) || empty($role)) {
            $this->redirect('/users/edit/' . $id, 'جميع الحقول مطلوبة', 'error');
            return;
        }

        // التحقق من البريد غير مستخدم من قبل مستخدم آخر
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $id]);
        if ($stmt->fetch()) {
            $this->redirect('/users/edit/' . $id, 'البريد الإلكتروني مستخدم من قبل', 'error');
            return;
        }

        $sql = "UPDATE users SET full_name = ?, email = ?, phone = ?, role = ?, is_active = ?, updated_at = NOW()";
        $params = [$full_name, $email, $phone, $role, $is_active];

        if (!empty($password)) {
            $sql .= ", password_hash = ?";
            $params[] = password_hash($password, PASSWORD_DEFAULT);
        }

        $sql .= " WHERE id = ?";
        $params[] = $id;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $this->audit->logUpdate('users', $id, [], [
            'full_name' => $full_name,
            'role' => $role,
            'is_active' => $is_active
        ]);

        $this->redirect('/users', 'تم تحديث المستخدم بنجاح', 'success');
    }

    // ============================================================
    // 🗑️ حذف مستخدم (ناعم أو نهائي)
    // ============================================================
    public function delete($id)
    {
        if ($id == $this->userId) {
            $this->redirect('/users', 'لا يمكنك حذف حسابك الخاص', 'error');
            return;
        }

        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);

        $this->audit->logDelete('users', $id, ['reason' => 'حذف يدوي']);

        $this->redirect('/users', 'تم حذف المستخدم بنجاح', 'success');
    }

    // ============================================================
    // 🔄 تغيير حالة المستخدم (تفعيل/تعطيل)
    // ============================================================
    public function toggle($id)
    {
        $stmt = $this->db->prepare("SELECT is_active FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch();

        if (!$user) {
            $this->redirect('/users', 'المستخدم غير موجود', 'error');
            return;
        }

        $newStatus = $user['is_active'] ? 0 : 1;
        $stmt = $this->db->prepare("UPDATE users SET is_active = ? WHERE id = ?");
        $stmt->execute([$newStatus, $id]);

        $this->audit->logUpdate('users', $id, ['is_active' => $user['is_active']], ['is_active' => $newStatus]);

        $this->redirect('/users', 'تم تغيير حالة المستخدم', 'success');
    }
    // ============================================================
// 🔐 إدارة صلاحيات المستخدم
// ============================================================
public function permissions($id)
{
    $this->requirePermission('manage_roles');
    
    $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch();
    
    if (!$user) {
        $this->redirect('/users', 'المستخدم غير موجود', 'error');
        return;
    }
    
    // جلب جميع الصلاحيات المتاحة
    $stmt = $this->db->query("SELECT DISTINCT permission FROM permissions ORDER BY permission");
    $allPermissions = $stmt->fetchAll();
    
    // جلب صلاحيات المستخدم الحالية
    $stmt = $this->db->prepare("SELECT permission FROM permissions WHERE role = ?");
    $stmt->execute([$user['role']]);
    $userPermissions = $stmt->fetchAll();
    $userPerms = array_column($userPermissions, 'permission');
    
    $this->view('users/permissions', [
        'title' => 'صلاحيات المستخدم',
        'user' => $user,
        'allPermissions' => $allPermissions,
        'userPerms' => $userPerms
    ]);
}

public function updatePermissions()
{
    $this->requirePermission('manage_roles');
    
    $userId = (int) $_POST['user_id'];
    $permissions = isset($_POST['permissions']) ? $_POST['permissions'] : [];
    
    // جلب دور المستخدم
    $stmt = $this->db->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    
    if (!$user) {
        $this->redirect('/users', 'المستخدم غير موجود', 'error');
        return;
    }
    
    // حذف الصلاحيات القديمة
    $stmt = $this->db->prepare("DELETE FROM permissions WHERE role = ?");
    $stmt->execute([$user['role']]);
    
    // إضافة الصلاحيات الجديدة
    foreach ($permissions as $permission) {
        $stmt = $this->db->prepare("
            INSERT INTO permissions (role, permission) VALUES (?, ?)
        ");
        $stmt->execute([$user['role'], $permission]);
    }
    
    $this->audit->logUpdate('users', $userId, ['action' => 'update_permissions'], ['permissions' => $permissions]);
    $this->redirect('/users', 'تم تحديث صلاحيات المستخدم بنجاح', 'success');
}
}