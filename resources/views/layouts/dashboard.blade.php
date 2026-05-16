<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'Inventory App') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary: #0071e3;
            --primary-hover: #0077ed;
            --bg-light: #f5f5f7;
            --text-primary: #1d1d1f;
            --text-secondary: #86868b;
            --border: #e5e5e7;
            --sidebar-width: 260px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #ffffff;
            color: var(--text-primary);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif;
            font-size: 15px;
            line-height: 1.6;
            font-weight: 400;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: #ffffff;
            border-right: 1px solid var(--border);
            padding: 24px 0;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #d0d0d2;
        }

        .sidebar-brand {
            padding: 0 20px 28px 20px;
            border-bottom: 1px solid var(--border);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-brand-icon {
            width: 36px;
            height: 36px;
            background: var(--primary);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            flex-shrink: 0;
        }

        .sidebar-brand-text {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        .nav-section {
            margin-bottom: 8px;
        }

        .nav-section-title {
            padding: 8px 20px;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 12px;
            margin-bottom: 4px;
        }

        .nav-link {
            padding: 10px 20px;
            color: var(--text-primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            border-left: 3px solid transparent;
            margin: 0 8px;
            border-radius: 0 8px 8px 0;
            transition: all 0.2s ease;
            font-size: 15px;
        }

        .nav-link i {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .nav-link:hover {
            background-color: var(--bg-light);
            color: var(--primary);
            border-left-color: var(--primary);
        }

        .nav-link.active {
            background-color: var(--bg-light);
            color: var(--primary);
            border-left-color: var(--primary);
            font-weight: 500;
        }

        /* Collapsed sidebar styles */
        .sidebar.collapsed {
            width: 80px;
        }

        .main-content.collapsed {
            margin-left: 80px;
        }

        /* smooth transitions */
        .sidebar {
            transition: width 200ms ease, padding 200ms ease;
            will-change: width;
        }

        .main-content {
            transition: margin-left 200ms ease;
        }

        /* hide labels smoothly */
        .nav-link span,
        .sidebar-brand-text,
        .nav-section-title {
            transition: opacity 150ms ease, max-width 200ms ease;
            display: inline-block;
            vertical-align: middle;
            white-space: nowrap;
            overflow: hidden;
            max-width: 1000px;
        }

        .sidebar.collapsed .sidebar-brand-text,
        .sidebar.collapsed .nav-section-title,
        .sidebar.collapsed .nav-link span {
            opacity: 0;
            max-width: 0;
        }

        .sidebar.collapsed .sidebar-brand {
            justify-content: center;
        }

        .sidebar.collapsed .nav-link {
            margin: 6px 8px;
            justify-content: center;
        }

        /* collapse button inside brand */
        /* collapse button styling (topbar) */
        #sidebarCollapse {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: transparent;
            padding: 0;
            cursor: pointer;
            transition: background 150ms ease, transform 150ms ease;
            margin-left: 8px;
        }

        #sidebarCollapse:hover {
            background: var(--bg-light);
        }

        /* rotate icon when collapsed */
        #sidebarCollapseIcon {
            transition: transform 200ms ease;
            transform-origin: center;
        }

        .sidebar.collapsed #sidebarCollapseIcon {
            transform: rotate(180deg);
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Top Bar */
        .topbar {
            background: #ffffff;
            border-bottom: 1px solid var(--border);
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .topbar-search {
            position: relative;
        }

        .topbar-search input {
            background: var(--bg-light);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 8px 12px 8px 36px;
            width: 200px;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .topbar-search input::placeholder {
            color: var(--text-secondary);
        }

        .topbar-search input:focus {
            outline: none;
            background: white;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 113, 227, 0.1);
        }

        .topbar-search i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            pointer-events: none;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .topbar-avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--primary), #0095ff);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }

        .topbar-user-info {
            display: none;
        }

        .topbar-user-info.show {
            display: block;
        }

        .topbar-user-name {
            font-size: 14px;
            font-weight: 500;
            color: var(--text-primary);
        }

        .topbar-user-role {
            font-size: 12px;
            color: var(--text-secondary);
        }

        .topbar-icon-btn {
            width: 36px;
            height: 36px;
            background: transparent;
            border: none;
            color: var(--text-primary);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.2s ease;
            font-size: 18px;
        }

        .topbar-icon-btn:hover {
            background: var(--bg-light);
            color: var(--primary);
        }

        /* Content Area */
        .content {
            flex: 1;
            padding: 32px;
        }

        .content-header {
            margin-bottom: 32px;
        }

        .content-title {
            font-size: 28px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .content-subtitle {
            font-size: 15px;
            color: var(--text-secondary);
        }

        /* Cards */
        .card {
            border: 1px solid var(--border);
            border-radius: 12px;
            background: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            transition: all 0.2s ease;
            overflow: hidden;
        }

        .card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border-color: rgba(0, 113, 227, 0.2);
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border);
            padding: 20px 24px;
        }

        .card-body {
            padding: 24px;
        }

        /* Stats Card */
        .stat-card {
            background: linear-gradient(135deg, #ffffff 0%, #fafbfc 100%);
            padding: 24px;
            border-radius: 12px;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            background: var(--bg-light);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: var(--primary);
            flex-shrink: 0;
        }

        .stat-content h3 {
            font-size: 24px;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0 0 4px 0;
        }

        .stat-content p {
            font-size: 13px;
            color: var(--text-secondary);
            margin: 0;
        }

        /* Button */
        .btn-primary {
            background: var(--primary);
            border: none;
            color: white;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            font-size: 15px;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            color: white;
            box-shadow: 0 4px 12px rgba(0, 113, 227, 0.3);
        }

        .btn-primary:active {
            transform: scale(0.98);
        }

        .btn-secondary {
            background: var(--bg-light);
            border: 1px solid var(--border);
            color: var(--text-primary);
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            font-size: 15px;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .btn-secondary:hover {
            background: #efefef;
            color: var(--primary);
        }

        /* Table */
        .table {
            font-size: 14px;
            margin-bottom: 0;
        }

        .table thead th {
            background: var(--bg-light);
            border: 1px solid var(--border);
            color: var(--text-primary);
            font-weight: 600;
            padding: 12px 16px;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .table tbody td {
            border: 1px solid var(--border);
            padding: 14px 16px;
            color: var(--text-primary);
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: var(--bg-light);
        }

        /* Badge */
        .badge {
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-success {
            background: #34c759;
            color: white;
        }

        .badge-warning {
            background: #ff9500;
            color: white;
        }

        .badge-danger {
            background: #ff3b30;
            color: white;
        }

        .badge-info {
            background: var(--primary);
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                position: absolute;
                left: -100%;
                transition: left 0.3s ease;
                width: 100%;
                z-index: 2000;
            }

            .sidebar.show {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .topbar {
                padding: 12px 16px;
            }

            .topbar-search input {
                display: none;
            }

            .topbar-user-info {
                display: none;
            }

            .content {
                padding: 16px;
            }

            .content-title {
                font-size: 24px;
            }

            .stat-card {
                flex-direction: column;
                text-align: center;
            }
        }

        /* Utility */
        .d-flex {
            display: flex;
        }

        .gap-3 {
            gap: 16px;
        }

        .gap-4 {
            gap: 24px;
        }

        .flex-wrap {
            flex-wrap: wrap;
        }

        .justify-content-between {
            justify-content: space-between;
        }

        .align-items-center {
            align-items: center;
        }
    </style>
    @stack('styles')
</head>

<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div>
                <img src="{{ asset('images/invenstore.png') }}" alt="Logo" width="50px" height="50px" class="img-thumbnails">
            </div>
            <h5 class="sidebar-brand-text">Inventory</h5>
        </div>

        <nav class="nav-section">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </nav>

        <div class="nav-section-title">MENU UTAMA</div>
        <nav class="nav-section">
            <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <i class="bi bi-box2"></i>
                <span>Produk</span>
            </a>
            <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                <i class="bi bi-tags"></i>
                <span>Kategori</span>
            </a>
            <a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                <i class="bi bi-truck"></i>
                <span>Supplier</span>
            </a>
        </nav>

        <div class="nav-section-title">TRANSAKSI</div>
        <nav class="nav-section">
            <a href="{{ route('transactions.index') }}" class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                <i class="bi bi-arrow-left-right"></i>
                <span>Transaksi</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="topbar">
            <div class="topbar-left">
                <button class="topbar-icon-btn d-md-none" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <button class="topbar-icon-btn d-none d-md-inline" id="sidebarCollapse" title="Toggle sidebar" aria-expanded="true">
                    <i class="bi bi-chevron-left" id="sidebarCollapseIcon"></i>
                </button>
                <div class="topbar-search">
                    <i class="bi bi-search"></i>
                    <input type="text" class="form-control" placeholder="Cari...">
                </div>
            </div>

            <div class="topbar-right">
                <button class="topbar-icon-btn" title="Notifikasi">
                    <i class="bi bi-bell"></i>
                </button>
                <div class="topbar-user">
                    <div class="topbar-avatar">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="topbar-user-info show">
                        <div class="topbar-user-name">{{ Auth::user()->name }}</div>
                        <div class="topbar-user-role">{{ ucfirst(Auth::user()->role) }}</div>
                    </div>
                </div>
                <button class="topbar-icon-btn" onclick="document.getElementById('logout-form').submit(); return false;" title="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            @yield('content')
        </div>
    </div>

    <!-- Hidden logout form for POST request -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    @flasher_render
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar toggle on mobile
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }

            // Close sidebar when clicking on a link (mobile)
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 769) {
                        sidebar.classList.remove('show');
                    }
                });
            });

            // Desktop collapse/expand
            const sidebarCollapse = document.getElementById('sidebarCollapse');
            const sidebarCollapseIcon = document.getElementById('sidebarCollapseIcon');
            const mainContent = document.querySelector('.main-content');

            function setCollapsed(collapsed) {
                sidebar.classList.toggle('collapsed', collapsed);
                mainContent.classList.toggle('collapsed', collapsed);
                sidebarCollapse.setAttribute('aria-expanded', !collapsed);
                sidebarCollapseIcon.classList.toggle('bi-chevron-left', !collapsed);
                sidebarCollapseIcon.classList.toggle('bi-chevron-right', collapsed);
                try {
                    localStorage.setItem('sidebar-collapsed', collapsed ? '1' : '0');
                } catch (e) {}
            }

            if (sidebarCollapse) {
                // initialize from localStorage
                const stored = (function() {
                    try {
                        return localStorage.getItem('sidebar-collapsed');
                    } catch (e) {
                        return null;
                    }
                })();
                setCollapsed(stored === '1');
                sidebarCollapse.addEventListener('click', function() {
                    const isCollapsed = sidebar.classList.contains('collapsed');
                    setCollapsed(!isCollapsed);
                });
            }
        });
    </script>
    @stack('scripts')
</body>

</html>