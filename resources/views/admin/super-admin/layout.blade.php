<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- SEO Meta Tags -->
    <title>@yield('title', 'Супер Настройки - ' . config('app.name', 'Laravel'))</title>
    <meta name="description" content="@yield('description', 'Панель управления системой')">
    <meta name="robots" content="noindex, nofollow">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    @vite(['resources/css/app.css'])
    
    <style>
        body {
            background-color: #f8f9fc;
        }
        .admin-sidebar {
            background: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
            min-height: 100vh;
            color: white;
        }
        .admin-sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1rem;
            border-radius: 0.35rem;
            margin-bottom: 0.5rem;
            transition: all 0.3s;
        }
        .admin-sidebar .nav-link:hover,
        .admin-sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .admin-content {
            padding: 2rem;
        }
        .mobile-header {
            background-color: #4e73df;
            padding: 1rem;
            color: white;
            display: flex;
            justify-content: between;
            align-items: center;
        }
        .mobile-menu-btn {
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
        }
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 999;
        }
        @media (max-width: 768px) {
            .admin-sidebar {
                position: fixed;
                top: 0;
                left: -250px;
                width: 250px;
                height: 100vh;
                z-index: 1000;
                transition: left 0.3s ease;
            }
            .admin-sidebar.show {
                left: 0;
            }
            .sidebar-overlay.show {
                display: block;
            }
            .admin-content {
                padding: 1rem;
                margin-top: 70px;
            }
        }
        .badge {
            font-size: 0.75rem;
        }
        .super-admin-badge {
            background: linear-gradient(45deg, #ff6b6b, #feca57);
            color: white;
            font-weight: bold;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.7rem;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Mobile Header -->
    <div class="mobile-header d-md-none">
        <button class="mobile-menu-btn" type="button" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </button>
        <h5 class="mb-0">
            <i class="bi bi-shield-check me-1"></i>
            Супер Настройки
        </h5>
        <button class="mobile-menu-btn" type="button" onclick="window.open('{{ route('user.show', ['username' => auth()->user()->username]) }}', '_blank')">
            <i class="bi bi-eye"></i>
        </button>
    </div>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" onclick="closeSidebar()"></div>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 admin-sidebar p-3" id="adminSidebar">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-shield-check me-2 fs-4"></i>
                        <div>
                            <h5 class="mb-0">Супер Настройки</h5>
                            <small class="super-admin-badge">ADMIN</small>
                        </div>
                    </div>
                    <button class="mobile-menu-btn d-md-none" type="button" onclick="closeSidebar()">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                
                <nav class="nav flex-column">
                  
                    <a class="nav-link {{ request()->routeIs('super-admin.users*') ? 'active' : '' }}" href="{{ route('super-admin.users') }}">
                        <i class="bi bi-people me-2"></i>
                        Пользователи
                    </a>
                    <a class="nav-link {{ request()->routeIs('super-admin.articles*') ? 'active' : '' }}" href="{{ route('super-admin.articles') }}">
                        <i class="bi bi-journal-text me-2"></i>
                        Все статьи
                    </a>
                    <a class="nav-link {{ request()->routeIs('super-admin.settings*') ? 'active' : '' }}" href="{{ route('super-admin.settings') }}">
                        <i class="bi bi-gear me-2"></i>
                        Настройки системы
                    </a>
                </nav>

                <hr class="my-4">
                
                <div class="nav flex-column">
                    <a class="nav-link" href="{{ route('admin.profile', auth()->id()) }}">
                        <i class="bi bi-person-gear me-2"></i>
                        Мои Настройки
                    </a>
                    
                    <a class="nav-link" href="{{ route('user.show', ['username' => auth()->user()->username]) }}" target="_blank">
                        <i class="bi bi-eye me-2"></i>
                        Моя страница
                    </a>
                    
                    <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right me-2"></i>
                        Выйти
                    </a>
                </div>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>

            <!-- Main content -->
            <div class="col-md-9 col-lg-10 admin-content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        }

        function closeSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        }

        // Закрыть сайдбар при изменении размера экрана
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                closeSidebar();
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>