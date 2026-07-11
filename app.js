// تشغيل صوت عند وصول إشعار جديد
function playNotificationSound() {
    const audio = new Audio('/sounds/notification.mp3'); // المسار ده هتضبطه حسب مكان الملف الصوتي
    audio.play().catch(e => console.log('Audio play blocked'));
}

// مثال على استخدامه مع Pusher/Echo (لو شغال)
// لو مش شغال Pusher، ممكن تستدعي الدالة دي عند استقبال الإشعارات من الـ API
window.playNotificationSound = playNotificationSound;

// مثال: استدعاء عند جلب إشعار جديد (لتجربتها)
// fetch('/notifications/unread-count').then(...).then(() => playNotificationSound());