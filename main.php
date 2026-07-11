<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - <?php echo $title ?? 'لوحة التحكم'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Tajawal', sans-serif; margin: 0; padding: 0; box-sizing: border-box; }
        
        /* ===== خلفية Apple المريحة للعين (Light Mode Mesh) ===== */
        body { 
            background-color: #f5f5f7; /* لون أبل الرمادي الفاتح المميز */
            background-image: 
                radial-gradient(at 0% 0%, rgba(255,255,255,1) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(235,244,255,0.7) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(243,232,255,0.5) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(224,242,254,0.6) 0px, transparent 50%);
            background-attachment: fixed;
            color: #1d1d1f; /* لون خط أبل الأساسي */
        }

        /* ===== الكلاس السحري لزجاج Apple (Frosted Glass) ===== */
        .apple-glass {
            background: rgba(255, 255, 255, 0.65);
            backdrop-filter: saturate(180%) blur(25px);
            -webkit-backdrop-filter: saturate(180%) blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04);
        }
        
        /* ===== Sidebar ===== */
        .sidebar {
            position: fixed;
            right: 0;
            top: 0;
            width: 260px;
            height: 100vh;
            z-index: 1000;
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            overflow-y: auto;
            padding-bottom: 20px;
            border-left: 1px solid rgba(255, 255, 255, 0.8);
        }
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-track { background: transparent; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.15); border-radius: 10px; }
        
        .sidebar .logo {
            padding: 24px 20px;
            color: #1d1d1f;
            font-size: 19px;
            font-weight: 800;
            text-align: center;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        .sidebar .logo i { margin-left: 8px; color: #007aff; }
        
        .sidebar a.nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #515154;
            padding: 10px 16px;
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 14.5px;
            font-weight: 500;
            margin: 6px 16px;
            border-radius: 10px;
        }
        .sidebar a.nav-link i {
            width: 20px;
            text-align: center;
            font-size: 16px;
            color: #86868b;
            transition: 0.2s;
        }
        .sidebar a.nav-link:hover {
            background: rgba(0, 0, 0, 0.04);
            color: #1d1d1f;
        }
        .sidebar a.nav-link:hover i { color: #1d1d1f; }
        
        .sidebar a.nav-link.active {
            background: #007aff;
            color: white;
            box-shadow: 0 4px 12px rgba(0, 122, 255, 0.3);
        }
        .sidebar a.nav-link.active i { color: white; }

        .sidebar .user-info {
            padding: 14px 16px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 14px;
            margin: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1px solid rgba(255,255,255,0.8);
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        }
        .sidebar .user-info .avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 18px;
            color: white;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .sidebar .user-info .avatar.admin { background: #007aff; }
        .sidebar .user-info .avatar.technician { background: #34c759; }
        .sidebar .user-info .avatar.accountant { background: #5856d6; }
        .sidebar .user-info .avatar.reception { background: #ff9500; }
        .sidebar .user-info .avatar.manager { background: #ff2d55; }
        .sidebar .user-info .avatar.sales { background: #32ade6; }
        .sidebar .user-info .name { color: #1d1d1f; font-weight: 700; font-size: 14px; }
        .sidebar .user-info .role { color: #86868b; font-size: 12px; }
        
        .sidebar .nav-divider {
            height: 1px;
            background: rgba(0,0,0,0.05);
            margin: 12px 20px;
        }
        .sidebar .logout {
            margin-top: auto;
            color: #ff3b30;
            font-size: 14px;
            font-weight: 600;
        }
        .sidebar .logout:hover {
            background: rgba(255, 59, 48, 0.1);
            color: #ff3b30;
        }
        
        /* ===== Mobile Hamburger ===== */
        .hamburger {
            display: none;
            position: fixed;
            top: 16px;
            right: 16px;
            z-index: 1100;
            color: #1d1d1f;
            padding: 10px 14px;
            border-radius: 12px;
            font-size: 20px;
            cursor: pointer;
            transition: 0.3s;
        }
        
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.2);
            backdrop-filter: blur(4px);
            z-index: 999;
            transition: all 0.3s ease;
        }
        .overlay.show { display: block; }
        
        .main-content {
            margin-right: 260px;
            padding: 24px 32px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }
        
        @media (max-width: 768px) {
            .sidebar { transform: translateX(100%); width: 280px; }
            .sidebar.open { transform: translateX(0); }
            .hamburger { display: block; }
            .main-content { margin-right: 0; padding-top: 80px; padding-left: 16px; padding-right: 16px; }
        }
        
        /* ===== رسائل التنبيه الزجاجية ===== */
        .flash-message {
            padding: 14px 20px;
            border-radius: 14px;
            margin-bottom: 24px;
            font-weight: 500;
            font-size: 14.5px;
        }
        .flash-success { background: rgba(52, 199, 89, 0.15); color: #248a3d; border: 1px solid rgba(52, 199, 89, 0.3); }
        .flash-error { background: rgba(255, 59, 48, 0.15); color: #c93429; border: 1px solid rgba(255, 59, 48, 0.3); }
        .flash-warning { background: rgba(255, 149, 0, 0.15); color: #b26800; border: 1px solid rgba(255, 149, 0, 0.3); }
        .flash-info { background: rgba(0, 122, 255, 0.15); color: #0056b3; border: 1px solid rgba(0, 122, 255, 0.3); }
        
        /* ===== الشريط العلوي (Top Bar) وزراير الحضور ===== */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 16px;
            padding: 14px 24px;
            border-radius: 18px;
        }
        
        .top-bar .action-group {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }

        /* زراير أبل العامة */
        .btn-apple {
            background: rgba(255, 255, 255, 0.8);
            color: #1d1d1f;
            padding: 8px 18px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            cursor: pointer;
        }
        .btn-apple:hover { background: #ffffff; transform: scale(1.02); box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        
        .btn-apple-primary {
            background: #007aff;
            color: white;
            border: none;
        }
        .btn-apple-primary:hover { background: #006ce4; color: white; }

        /* زراير الحضور والانصراف الزجاجية المريحة */
        .btn-attendance {
            background: rgba(52, 199, 89, 0.15);
            color: #248a3d;
            border: 1px solid rgba(52, 199, 89, 0.3);
        }
        .btn-attendance:hover { background: rgba(52, 199, 89, 0.25); color: #1e7533; }

        .btn-departure {
            background: rgba(255, 59, 48, 0.15);
            color: #c93429;
            border: 1px solid rgba(255, 59, 48, 0.3);
        }
        .btn-departure:hover { background: rgba(255, 59, 48, 0.25); color: #a82a21; }

        .time-display {
            font-size: 14px;
            font-weight: 600;
            color: #86868b;
            background: rgba(255,255,255,0.6);
            padding: 6px 14px;
            border-radius: 10px;
            border: 1px solid rgba(0,0,0,0.03);
        }

    </style>
</head>
<body>
    <button class="hamburger apple-glass" id="hamburgerBtn" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
    
    <div class="overlay" id="overlay" onclick="closeSidebar()"></div>
    
    <div class="sidebar apple-glass" id="sidebar">
        <div class="logo"><i class="fab fa-apple" style="font-size: 22px; margin-left: 6px; color:#1d1d1f;"></i> <?php echo APP_NAME; ?></div>
        
        <div class="user-info">
            <div class="avatar <?php echo $userRole; ?>">
                <?php echo mb_substr($userName, 0, 1); ?>
            </div>
            <div>
                <div class="name"><?php echo $userName; ?></div>
                <div class="role">
                    <?php
                    $roles = [
                        'admin' => 'مدير النظام',
                        'technician' => 'فني',
                        'accountant' => 'محاسب',
                        'reception' => 'استقبال',
                        'manager' => 'مشرف',
                        'sales' => 'مبيعات',
                        'guest' => 'زائر'
                    ];
                    echo $roles[$userRole] ?? $userRole;
                    ?>
                </div>
            </div>
        </div>
        
        <a href="/" class="nav-link <?php echo $_SERVER['REQUEST_URI'] == '/' ? 'active' : ''; ?>">
            <i class="fas fa-layer-group"></i> لوحة التحكم
        </a>
        
        <?php if ($userRole === 'admin'): ?>
            <a href="/users" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/users') !== false ? 'active' : ''; ?>">
                <i class="fas fa-user-cog"></i> إدارة المستخدمين
            </a>
            <a href="/audit" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/audit') !== false ? 'active' : ''; ?>">
                <i class="fas fa-shield-check"></i> سجل التدقيق
            </a>
            <a href="/attendance" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/attendance') !== false && strpos($_SERVER['REQUEST_URI'], '/attendance/report') === false ? 'active' : ''; ?>">
                <i class="fas fa-user-clock"></i> سجل الحضور
            </a>
            <a href="/attendance/report" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/attendance/report') !== false ? 'active' : ''; ?>">
                <i class="fas fa-chart-line"></i> تقرير الحضور
            </a>
            <a href="/shifts" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/shifts') !== false ? 'active' : ''; ?>">
                <i class="fas fa-calendar-alt"></i> الورديات
            </a>
            <div class="nav-divider"></div>
        <?php endif; ?>
        
        <a href="/devices" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/devices') !== false && strpos($_SERVER['REQUEST_URI'], '/devices/') === false ? 'active' : ''; ?>">
            <i class="fas fa-laptop-medical"></i> الأجهزة
        </a>
        
        <?php if ($userRole === 'admin' || $userRole === 'reception'): ?>
            <a href="/devices/create" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/devices/create') !== false ? 'active' : ''; ?>">
                <i class="fas fa-plus-circle"></i> استلام جهاز
            </a>
        <?php endif; ?>
        
        <a href="/devices/waiting" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/devices/waiting') !== false ? 'active' : ''; ?>">
            <i class="fas fa-hourglass-half"></i> انتظار قطع غيار
        </a>
        
        <?php if ($userRole === 'technician'): ?>
            <a href="/technician-dashboard" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/technician-dashboard') !== false ? 'active' : ''; ?>">
                <i class="fas fa-wrench"></i> أجهزتي
            </a>
        <?php endif; ?>
        
        <?php if ($userRole === 'admin' || $userRole === 'accountant'): ?>
            <div class="nav-divider"></div>
            <a href="/sales-summary" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/sales-summary') !== false ? 'active' : ''; ?>">
                <i class="fas fa-chart-pie"></i> إجمالي المبيعات
            </a>
            <a href="/sales" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/sales') !== false && strpos($_SERVER['REQUEST_URI'], '/sales/') === false ? 'active' : ''; ?>">
                <i class="fas fa-file-invoice-dollar"></i> الفواتير
            </a>
            <a href="/sales/pending" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/sales/pending') !== false ? 'active' : ''; ?>">
                <i class="fas fa-clock"></i> فواتير معلقة
            </a>
            <a href="/wallets" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/wallets') !== false ? 'active' : ''; ?>">
                <i class="fas fa-wallet"></i> المحافظ
            </a>
            <a href="/installments" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/installments') !== false ? 'active' : ''; ?>">
                <i class="fas fa-money-check-alt"></i> الأقساط
            </a>
            <a href="/expenses" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/expenses') !== false ? 'active' : ''; ?>">
                <i class="fas fa-arrow-trend-down"></i> المصروفات
            </a>
        <?php endif; ?>
        
        <?php if ($userRole !== 'technician'): ?>
            <div class="nav-divider"></div>
            <a href="/inventory" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/inventory') !== false && strpos($_SERVER['REQUEST_URI'], '/inventory/count') === false ? 'active' : ''; ?>">
                <i class="fas fa-box-open"></i> المخزون
            </a>
            <a href="/inventory/count" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/inventory/count') !== false ? 'active' : ''; ?>">
                <i class="fas fa-clipboard-check"></i> جرد المخزون
            </a>
            <a href="/customers" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/customers') !== false ? 'active' : ''; ?>">
                <i class="fas fa-user-friends"></i> العملاء
            </a>
        <?php endif; ?>
        
        <?php if ($userRole === 'admin' || $userRole === 'accountant' || $userRole === 'manager'): ?>
            <div class="nav-divider"></div>
            <a href="/reports" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/reports') !== false ? 'active' : ''; ?>">
                <i class="fas fa-file-signature"></i> التقارير
            </a>
        <?php endif; ?>
        
        <div class="nav-divider"></div>
        <a href="/chat" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/chat') !== false ? 'active' : ''; ?>">
            <i class="fas fa-comment-dots"></i> محادثات
        </a>
        <a href="/notifications" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/notifications') !== false ? 'active' : ''; ?>">
            <i class="fas fa-bell"></i> الإشعارات
            <span id="notificationBadge" class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full hidden" style="margin-right: auto; font-family: sans-serif;">0</span>
        </a>
        
        <?php if ($userRole === 'admin'): ?>
            <div class="nav-divider"></div>
            <a href="/backup" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/backup') !== false ? 'active' : ''; ?>">
                <i class="fas fa-cloud-download-alt"></i> النسخ الاحتياطي
            </a>
        <?php endif; ?>
        
        <a href="/logout" class="nav-link logout">
            <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
        </a>
    </div>
    
    <div class="main-content" id="mainContent">
        
        <div class="top-bar apple-glass">
            <div class="action-group">
                <button onclick="history.back()" class="btn-apple">
                    <i class="fas fa-chevron-right"></i> رجوع
                </button>
                <a href="/" class="btn-apple btn-apple-primary">
                    <i class="fas fa-home"></i> الرئيسية
                </a>
                
                <div style="width: 1px; height: 20px; background: rgba(0,0,0,0.1); margin: 0 4px;"></div>
                
                <a href="/attendance/check-in" class="btn-apple btn-attendance">
                    <i class="fas fa-fingerprint"></i> تسجيل حضور
                </a>
                <a href="/attendance/check-out" class="btn-apple btn-departure">
                    <i class="fas fa-sign-out-alt"></i> تسجيل انصراف
                </a>
            </div>
            
            <div class="time-display">
                <i class="far fa-clock" style="margin-left: 6px;"></i>
                <?php echo date('Y-m-d h:i A'); ?>
            </div>
        </div>
        
        <?php if (isset($_SESSION['flash_message'])): ?>
            <?php $type = $_SESSION['flash_type'] ?? 'success'; ?>
            <div class="flash-message apple-glass flash-<?php echo $type; ?>">
                <?php echo htmlspecialchars($_SESSION['flash_message']); ?>
            </div>
            <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
        <?php endif; ?>
        
        <?php include $content; ?>
    </div>
    
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const hamburger = document.getElementById('hamburgerBtn');
            
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
            
            if (sidebar.classList.contains('open')) {
                hamburger.innerHTML = '<i class="fas fa-times"></i>';
            } else {
                hamburger.innerHTML = '<i class="fas fa-bars"></i>';
            }
        }
        
        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('overlay').classList.remove('show');
            document.getElementById('hamburgerBtn').innerHTML = '<i class="fas fa-bars"></i>';
        }
        
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) { closeSidebar(); }
        });
        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') { closeSidebar(); }
        });

        // تحديث عدد الإشعارات غير المقروءة
        function updateNotificationBadge() {
            fetch('/notifications/unread-count')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('notificationBadge');
                    if (data.unread > 0) {
                        badge.textContent = data.unread;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                })
                .catch(() => {});
        }

        setInterval(updateNotificationBadge, 30000);
        document.addEventListener('DOMContentLoaded', updateNotificationBadge);
    </script>
</body>
</html>