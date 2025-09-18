<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- User ID Meta Tags for JavaScript -->
    @auth
    <meta name="current-user-id" content="{{ auth()->id() }}">
    <meta name="page-user-id" content="{{ auth()->id() }}">
    @endauth
    
    <!-- SEO Meta Tags -->
    <title>@yield('title', 'Настройки - ' . config('app.name', 'Laravel'))</title>
    <meta name="description" content="@yield('description', 'Аналитика и управление контентом персонального сайта')">
    <meta name="robots" content="noindex, nofollow">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    @vite([
        'resources/css/app.css', 
        'resources/css/admin.css',
        'resources/js/app.js',
        'resources/js/admin-loading.js',
        'resources/js/admin-toggles.js',
        'resources/js/admin-images.js',
        'resources/js/admin-forms.js',
        'resources/js/admin-services.js',
        'resources/js/admin-gallery.js',
        'resources/js/admin-banners.js',
        'resources/js/admin-articles.js'
    ])
    
    <style>
       
    </style>
</head>
<body>
    <!-- Mobile Header -->
    <div class="mobile-header d-md-none">
        <button class="mobile-menu-btn" type="button" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </button>
        <h5 class="mb-0">
            @if(request()->routeIs('admin.analytics*'))
                Аналитика
            @elseif(request()->routeIs('admin.profile.edit'))
                Редактирование профиля
            @elseif(request()->routeIs('admin.profile*'))
                Управление профилем
            @elseif(request()->routeIs('admin.gallery.create'))
                Добавление изображения
            @elseif(request()->routeIs('admin.gallery.edit'))
                Редактирование изображения
            @elseif(request()->routeIs('admin.gallery*'))
                Галерея
            @elseif(request()->routeIs('admin.services.create'))
                Создание услуги
            @elseif(request()->routeIs('admin.services.edit'))
                Редактирование услуги
            @elseif(request()->routeIs('admin.services*'))
                Услуги
            @elseif(request()->routeIs('admin.articles.create'))
                Создание статьи
            @elseif(request()->routeIs('admin.articles.edit'))
                Редактирование статьи
            @elseif(request()->routeIs('admin.articles*'))
                Статьи
            @elseif(request()->routeIs('admin.banners.create'))
                Создание баннера
            @elseif(request()->routeIs('admin.banners.edit'))
                Редактирование баннера
            @elseif(request()->routeIs('admin.banners*'))
                Баннеры
            @elseif(request()->routeIs('super-admin*'))
                Супер Настройки
            @else
                Настройки
            @endif
        </h5>
        <button class="mobile-menu-btn" type="button" onclick="window.open('{{ route('user.page', ['username' => auth()->user()->username]) }}', '_blank')">
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
                        <i class="bi bi-gear-fill me-2 fs-4"></i>
                        <h5 class="mb-0">Настройки</h5>
                    </div>
                    <button class="mobile-menu-btn d-md-none" type="button" onclick="closeSidebar()">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link {{ request()->routeIs('admin.analytics*') ? 'active' : '' }}" href="{{ route('admin.analytics', $currentUserId) }}">
                        <i class="bi bi-graph-up me-2"></i>
                        Аналитика
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.profile*') ? 'active' : '' }}" href="{{ route('admin.profile', $currentUserId) }}">
                        <i class="bi bi-person-circle me-2"></i>
                        Профиль
                    </a>
                    @if(isset($userSectionSettings) && $userSectionSettings->has('gallery'))
                    <a class="nav-link {{ request()->routeIs('admin.gallery*') ? 'active' : '' }}" href="{{ route('admin.gallery', $currentUserId) }}">
                        <i class="bi bi-images me-2"></i>
                        Галерея
                    </a>
                    @endif
                    @if(isset($userSectionSettings) && $userSectionSettings->has('services'))
                    <a class="nav-link {{ request()->routeIs('admin.services*') ? 'active' : '' }}" href="{{ route('admin.services', $currentUserId) }}">
                        <i class="bi bi-briefcase me-2"></i>
                        Услуги
                    </a>
                    @endif
                    @if(isset($userSectionSettings) && $userSectionSettings->has('articles'))
                    <a class="nav-link {{ request()->routeIs('admin.articles*') ? 'active' : '' }}" href="{{ route('admin.articles', $currentUserId) }}">
                        <i class="bi bi-journal-text me-2"></i>
                        Статьи
                    </a>
                    @endif
                    @if(isset($userSectionSettings) && $userSectionSettings->has('banners'))
                    <a class="nav-link {{ request()->routeIs('admin.banners*') ? 'active' : '' }}" href="{{ route('admin.banners', $currentUserId) }}">
                        <i class="bi bi-flag me-2"></i>
                        Баннеры
                    </a>
                    @endif
                    
                    @if(auth()->user()->isAdmin())
                    <hr class="my-3">
                    <a class="nav-link text-warning" href="{{ route('super-admin.index') }}">
                        <i class="bi bi-shield-check me-2"></i>
                        Супер Настройки
                    </a>
                    @endif
                </nav>

                <hr class="my-4">
                
                <div class="nav flex-column">
                    <a class="nav-link" href="{{ route('user.page', ['username' => auth()->user()->username]) }}" target="_blank">
                        <i class="bi bi-eye me-2"></i>
                        Посмотреть страницу
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
            
            sidebar.classList.add('show');
            overlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
        
        function closeSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
            document.body.style.overflow = '';
        }
        
        // Закрыть сайдбар при изменении размера экрана
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                closeSidebar();
                // Дополнительная проверка - убираем overflow:hidden с body на больших экранах
                document.body.style.overflow = '';
            }
        });
        
        // Дополнительная проверка при загрузке страницы - если мы на больших экранах, убираем блокировку скролла
        function checkScrollLock() {
            if (window.innerWidth >= 768) {
                const sidebar = document.getElementById('adminSidebar');
                const overlay = document.querySelector('.sidebar-overlay');
                
                if (sidebar) sidebar.classList.remove('show');
                if (overlay) overlay.classList.remove('show');
                document.body.style.overflow = '';
            }
        }
        
        // Улучшения для форм на мобильных устройствах
        document.addEventListener('DOMContentLoaded', function() {
            // Проверяем и исправляем скролл при загрузке страницы
            checkScrollLock();
            
            // Если это мобильное устройство, убеждаемся что скролл не заблокирован
            if (window.innerWidth < 768) {
                document.body.style.overflow = '';
            }
            // Prevent zoom on iOS when focusing inputs
            if (/iPad|iPhone|iPod/.test(navigator.userAgent)) {
                const viewport = document.querySelector('meta[name=viewport]');
                viewport.setAttribute('content', 'width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no');
            }
            
            // Auto-close mobile menu when clicking on links
            const navLinks = document.querySelectorAll('.admin-sidebar .nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 768) {
                        setTimeout(closeSidebar, 150);
                    }
                });
            });
        });
    </script>
    
    @yield('scripts')
    
   

</body>
</html>
