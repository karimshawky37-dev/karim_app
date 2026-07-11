<?php
namespace App\Controllers;

class NotificationsController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $userId = $this->userId;
        $stmt = $this->db->prepare("SELECT * FROM notifications WHERE user_id = ? OR user_id IS NULL ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        $notifications = $stmt->fetchAll();

        $unreadCount = 0;
        foreach ($notifications as $n) if (!$n['is_read']) $unreadCount++;

        $this->view('notifications/index', [
            'title' => 'الإشعارات',
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ]);
    }

    public function markRead($id)
    {
        $stmt = $this->db->prepare("UPDATE notifications SET is_read = 1 WHERE id = ? AND (user_id = ? OR user_id IS NULL)");
        $stmt->execute([$id, $this->userId]);
        $this->redirect('/notifications', 'تم تحديث الإشعار', 'success');
    }

    public function markAllRead()
    {
        $stmt = $this->db->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ? OR user_id IS NULL");
        $stmt->execute([$this->userId]);
        $this->redirect('/notifications', 'تم تحديث جميع الإشعارات', 'success');
    }

    public function unreadCount()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM notifications WHERE (user_id = ? OR user_id IS NULL) AND is_read = 0");
        $stmt->execute([$this->userId]);
        $count = $stmt->fetch()['count'] ?? 0;
        header('Content-Type: application/json');
        echo json_encode(['unread' => $count]);
        exit;
    }
}