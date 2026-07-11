<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-theme="{{ session('theme', 'light') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300..700&family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Tailwind -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ===== Global Theme Variables ===== */
        :root {
            --bg-primary: #f1f5f9;
            --bg-secondary: #ffffff;
            --bg-glass: rgba(255, 255, 255, 0.55);
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            --border-color: rgba(0, 0, 0, 0.06);
            --shadow-color: rgba(0, 0, 0, 0.04);
            --accent-color: #3b82f6;
            --accent-hover: #2563eb;
            --sidebar-width: 260px;
        }

        [data-theme="dark"] {
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-glass: rgba(15, 23, 42, 0.65);
            --text-primary: #f1f5f9;
            --text-secondary: #cbd5e1;
            --text-muted: #64748b;
            --border-color: rgba(255, 255, 255, 0.06);
            --shadow-color: rgba(0, 0, 0, 0.2);
            --accent-color: #60a5fa;
            --accent-hover: #3b82f6;
        }

        /* ===== Base Styles ===== */
        * {
            font-family: '{{ app()->getLocale() === 'ar' ? 'Tajawal' : 'Inter' }}', sans-serif;
            transition: background-color 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                        color 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                        border-color 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            margin: 0;
            -webkit-font-smoothing: antialiased;
        }

        /* ===== Glassmorphism Helpers ===== */
        .glass {
            background: var(--bg-glass);
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 8px 32px var(--shadow-color);
        }

        .glass-card {
            background: var(--bg-glass);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            box-shadow: 0 4px 24px var(--shadow-color);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 48px var(--shadow-color);
        }

        /* ===== Sidebar ===== */
        .sidebar {
            position: fixed;
            top: 0;
            right: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--bg-glass);
            backdrop-filter: blur(24px) saturate(180%);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            border-left: 1px solid var(--border-color);
            box-shadow: 4px 0 32px var(--shadow-color);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            overflow: hidden;
            transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-scroll {
            flex: 1;
            overflow-y: auto;
            padding: 8px 0;
            scroll-behavior: smooth;
            overscroll-behavior: contain;
        }

        .sidebar-scroll::-webkit-scrollbar { width: 3px; }
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: var(--text-muted);
            border-radius: 10px;
            opacity: 0.3;
        }

        .sidebar-brand {
            padding: 20px 24px 16px 24px;
            border-bottom: 1px solid var(--border-color);
            flex-shrink: 0;
        }

        .sidebar-brand h1 {
            font-size: 17px;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -0.3px;
        }

        .sidebar-brand h1 i {
            color: var(--accent-color);
            margin-left: 10px;
        }

        .sidebar-brand p {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 10px 20px;
            margin: 2px 12px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            border-radius: 12px;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-link i {
            width: 22px;
            text-align: center;
            font-size: 15px;
            opacity: 0.5;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-link:hover {
            background: var(--border-color);
            color: var(--text-primary);
            transform: translateX(-4px);
        }

        .sidebar-link:hover i {
            opacity: 1;
            color: var(--accent-color);
        }

        .sidebar-link.active {
            background: rgba(59, 130, 246, 0.08);
            color: var(--accent-color);
            font-weight: 600;
        }

        .sidebar-link.active i {
            opacity: 1;
            color: var(--accent-color);
        }

        .sidebar-divider {
            height: 1px;
            background: var(--border-color);
            margin: 8px 20px;
        }

        .sidebar-footer {
            flex-shrink: 0;
            padding: 16px 20px 20px 20px;
            border-top: 1px solid var(--border-color);
            background: var(--bg-glass);
            backdrop-filter: blur(24px) saturate(180%);
        }

        /* ===== Main Content ===== */
        .main-content {
            margin-right: var(--sidebar-width);
            padding: 28px 32px 32px 32px;
            min-height: 100vh;
            transition: margin 0.3s ease;
        }

        /* ===== Responsive ===== */
        .hamburger {
            display: none;
            background: var(--bg-glass);
            backdrop-filter: blur(8px);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 10px 14px;
            color: var(--text-primary);
            font-size: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(4px);
            z-index: 999;
        }

        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(100%);
                width: 290px;
            }
            .sidebar.open { transform: translateX(0); }
            .sidebar-overlay.show { display: block; }
            .main-content { margin-right: 0; padding: 20px; }
            .hamburger { display: inline-flex; }
        }

        @media (max-width: 640px) {
            .main-content { padding: 16px; }
        }

        /* ===== Scroll Persistence (JS handles the rest) ===== */
        .sidebar-scroll {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body>

    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- ============================================================ -->
    <!-- 🧭 SIDEBAR -->
    <!-- ============================================================ -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <h1><i class="fas fa-cogs"></i> {{ config('app.name') }}</h1>
            <p>{{ auth()->user()->full_name ?? 'Guest' }} · {{ auth()->user()->role ?? 'visitor' }}</p>
        </div>

        <nav class="sidebar-scroll" id="sidebarScroll">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-pie"></i> Dashboard
            </a>

            <!-- Devices -->
            <a href="{{ route('devices.index') }}" class="sidebar-link {{ request()->routeIs('devices.*') ? 'active' : '' }}">
                <i class="fas fa-mobile-alt"></i> Devices
            </a>

            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'reception')
            <a href="{{ route('devices.create') }}" class="sidebar-link {{ request()->routeIs('devices.create') ? 'active' : '' }}">
                <i class="fas fa-plus-circle"></i> New Device
            </a>
            @endif

            @if(auth()->user()->role === 'technician')
            <a href="{{ route('technician.dashboard') }}" class="sidebar-link {{ request()->routeIs('technician.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tools"></i> My Devices
            </a>
            @endif

            <div class="sidebar-divider"></div>

            <!-- Financial -->
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'accountant')
            <a href="{{ route('invoices.index') }}" class="sidebar-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                <i class="fas fa-file-invoice"></i> Invoices
            </a>
            <a href="{{ route('invoices.pending') }}" class="sidebar-link {{ request()->routeIs('invoices.pending') ? 'active' : '' }}">
                <i class="fas fa-clock"></i> Pending Invoices
            </a>
            <a href="{{ route('wallets.index') }}" class="sidebar-link {{ request()->routeIs('wallets.*') ? 'active' : '' }}">
                <i class="fas fa-wallet"></i> Wallets
            </a>
            <a href="{{ route('installments.index') }}" class="sidebar-link {{ request()->routeIs('installments.*') ? 'active' : '' }}">
                <i class="fas fa-hand-holding-usd"></i> Installments
            </a>
            @endif

            <div class="sidebar-divider"></div>

            <!-- Inventory -->
            @if(auth()->user()->role !== 'technician')
            <a href="{{ route('inventory.index') }}" class="sidebar-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
                <i class="fas fa-boxes"></i> Inventory
            </a>
            <a href="{{ route('inventory.count') }}" class="sidebar-link {{ request()->routeIs('inventory.count') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list"></i> Stocktaking
            </a>
            <a href="{{ route('customers.index') }}" class="sidebar-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Customers
            </a>
            @endif

            <div class="sidebar-divider"></div>

            <!-- Reports & Tools -->
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'accountant' || auth()->user()->role === 'manager')
            <a href="{{ route('reports.index') }}" class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <i class="fas fa-file-alt"></i> Reports
            </a>
            @endif

            @if(auth()->user()->role === 'admin')
            <a href="{{ route('attendance.index') }}" class="sidebar-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-check"></i> Attendance
            </a>
            @endif

            <a href="{{ route('investment.index') }}" class="sidebar-link {{ request()->routeIs('investment.*') ? 'active' : '' }}">
                <i class="fas fa-handshake"></i> Investment Equation
            </a>

            <a href="{{ route('chat.index') }}" class="sidebar-link {{ request()->routeIs('chat.index') ? 'active' : '' }}">
                <i class="fas fa-comment-dots"></i> Chat
            </a>

            <a href="{{ route('notifications.index') }}" class="sidebar-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                <i class="fas fa-bell"></i> Notifications
                <span id="notificationBadge" class="bg-red-500 text-white text-[10px] font-bold rounded-full px-2 py-0.5 ml-auto hidden">0</span>
            </a>

            <div class="sidebar-divider"></div>

            <!-- Admin Only -->
            @if(auth()->user()->role === 'admin')
            <a href="{{ route('users.index') }}" class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <i class="fas fa-users-cog"></i> Users
            </a>
            <a href="{{ route('audit.index') }}" class="sidebar-link {{ request()->routeIs('audit.*') ? 'active' : '' }}">
                <i class="fas fa-shield-alt"></i> Audit Log
            </a>
            <a href="{{ route('shifts.index') }}" class="sidebar-link {{ request()->routeIs('shifts.*') ? 'active' : '' }}">
                <i class="fas fa-clock"></i> Shifts
            </a>
            <a href="{{ route('settings.work') }}" class="sidebar-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <i class="fas fa-cog"></i> Settings
            </a>
            @endif
        </nav>

        <!-- Sidebar Footer -->
        <div class="sidebar-footer">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm text-[var(--text-secondary)]">
                    <i class="fas {{ session('theme', 'light') === 'dark' ? 'fa-sun' : 'fa-moon' }}"></i>
                    {{ session('theme', 'light') === 'dark' ? 'Light' : 'Dark' }}
                </span>
                <button class="theme-toggle" onclick="toggleTheme()">
                    <span class="ball"></span>
                </button>
            </div>

            <div class="flex items-center justify-between mb-3">
                <span class="text-sm text-[var(--text-secondary)]">
                    <i class="fas fa-globe"></i> Language
                </span>
                <div class="flex gap-1">
                    <a href="{{ route('language.switch', 'ar') }}" class="px-3 py-1 rounded-lg text-sm transition {{ app()->getLocale() === 'ar' ? 'bg-accent-light text-accent-color font-semibold' : 'text-[var(--text-muted)] hover:text-[var(--text-primary)]' }}">
                        عربي
                    </a>
                    <a href="{{ route('language.switch', 'en') }}" class="px-3 py-1 rounded-lg text-sm transition {{ app()->getLocale() === 'en' ? 'bg-accent-light text-accent-color font-semibold' : 'text-[var(--text-muted)] hover:text-[var(--text-primary)]' }}">
                        EN
                    </a>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center justify-center gap-2 w-full py-2.5 rounded-lg text-sm text-red-400 hover:bg-red-500/10 transition">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- ============================================================ -->
    <!-- 📄 MAIN CONTENT -->
    <!-- ============================================================ -->
    <main class="main-content" id="mainContent">

        <!-- Top Bar -->
        <div class="flex items-center justify-between mb-6">
            <button onclick="toggleSidebar()" class="hamburger">
                <i class="fas fa-bars"></i>
            </button>
            <h1 class="text-xl font-bold text-[var(--text-primary)]">@yield('page_title', 'Dashboard')</h1>
            <div class="w-10 h-10 rounded-full glass flex items-center justify-center text-sm font-bold text-[var(--text-primary)]">
                {{ strtoupper(substr(auth()->user()->full_name ?? 'G', 0, 1)) }}
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
        <div class="glass-card p-4 mb-6 border-r-4 border-success text-success">
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="glass-card p-4 mb-6 border-r-4 border-danger text-danger">
            {{ $errors->first() }}
        </div>
        @endif

        <!-- Page Content -->
        @yield('content')
    </main>

    <!-- ============================================================ -->
    <!-- 📜 SCRIPTS -->
    <!-- ============================================================ -->
    <script>
        // ============================================================
        // 🌙 THEME TOGGLE
        // ============================================================
        function toggleTheme() {
            const html = document.documentElement;
            const current = html.getAttribute('data-theme');
            const next = current === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);

            // Update UI
            const icon = document.querySelector('.theme-toggle i');
            if (icon) {
                icon.className = `fas ${next === 'dark' ? 'fa-sun' : 'fa-moon'}`;
            }
            const label = document.querySelector('.theme-toggle').closest('.flex-between').querySelector('span');
            if (label) {
                label.innerHTML = `<i class="fas ${next === 'dark' ? 'fa-sun' : 'fa-moon'}"></i> ${next === 'dark' ? 'Light' : 'Dark'}`;
            }
        }

        // Load saved theme on page load
        document.addEventListener('DOMContentLoaded', function() {
            const saved = localStorage.getItem('theme');
            if (saved) {
                document.documentElement.setAttribute('data-theme', saved);
            } else {
                // Auto-detect system preference
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.documentElement.setAttribute('data-theme', 'dark');
                    localStorage.setItem('theme', 'dark');
                }
            }
            // Update UI to match
            const theme = document.documentElement.getAttribute('data-theme') || 'light';
            const icon = document.querySelector('.theme-toggle i');
            if (icon) {
                icon.className = `fas ${theme === 'dark' ? 'fa-sun' : 'fa-moon'}`;
            }
            const label = document.querySelector('.theme-toggle').closest('.flex-between').querySelector('span');
            if (label) {
                label.innerHTML = `<i class="fas ${theme === 'dark' ? 'fa-sun' : 'fa-moon'}"></i> ${theme === 'dark' ? 'Light' : 'Dark'}`;
            }
        });

        // ============================================================
        // 📱 SIDEBAR TOGGLE (Mobile)
        // ============================================================
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }

        // Close sidebar on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.getElementById('sidebar').classList.remove('open');
                document.getElementById('sidebarOverlay').classList.remove('show');
            }
        });

        // Close sidebar when clicking outside (mobile)
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const isClickInside = sidebar.contains(e.target);
            const isHamburger = e.target.closest('.hamburger');
            if (!isClickInside && !isHamburger && window.innerWidth <= 1024) {
                sidebar.classList.remove('open');
                overlay.classList.remove('show');
            }
        });

        // ============================================================
        // 🧭 SIDEBAR SCROLL PERSISTENCE
        // ============================================================
        (function() {
            const scrollContainer = document.getElementById('sidebarScroll');
            if (!scrollContainer) return;

            // Save scroll position before navigating
            document.addEventListener('click', function(e) {
                const link = e.target.closest('.sidebar-link');
                if (link && link.href) {
                    // Save current scroll position
                    sessionStorage.setItem('sidebarScrollPosition', scrollContainer.scrollTop);
                }
            });

            // Restore scroll position after page load
            window.addEventListener('load', function() {
                const savedPosition = sessionStorage.getItem('sidebarScrollPosition');
                if (savedPosition !== null) {
                    scrollContainer.scrollTop = parseInt(savedPosition, 10);
                    // Clear after restoring to prevent unwanted jumps
                    sessionStorage.removeItem('sidebarScrollPosition');
                }
            });

            // Also save on scroll (for safety)
            scrollContainer.addEventListener('scroll', function() {
                sessionStorage.setItem('sidebarScrollPosition', scrollContainer.scrollTop);
            });
        })();

        // ============================================================
        // 🔔 NOTIFICATION BADGE
        // ============================================================
        function updateNotificationBadge() {
            fetch('/api/notifications/unread-count')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('notificationBadge');
                    if (badge) {
                        if (data.count > 0) {
                            badge.textContent = data.count;
                            badge.classList.remove('hidden');
                        } else {
                            badge.classList.add('hidden');
                        }
                    }
                })
                .catch(() => {});
        }

        // Update notification badge every 30 seconds
        setInterval(updateNotificationBadge, 30000);
        document.addEventListener('DOMContentLoaded', updateNotificationBadge);
    </script>

</body>
</html>