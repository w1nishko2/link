<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <!-- User ID Meta Tags for JavaScript -->
    <?php if(auth()->guard()->check()): ?>
    <meta name="current-user-id" content="<?php echo e(auth()->id()); ?>">
    <meta name="page-user-id" content="<?php echo e(auth()->id()); ?>">
    <?php endif; ?>
    
    <!-- SEO Meta Tags -->
    <title><?php echo $__env->yieldContent('title', 'Настройки - ' . config('app.name', 'Laravel')); ?></title>
    <meta name="description" content="<?php echo $__env->yieldContent('description', 'Аналитика и управление контентом персонального сайта'); ?>">
    <meta name="robots" content="noindex, nofollow">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('favicon.ico')); ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <?php echo app('Illuminate\Foundation\Vite')([
        'resources/css/app.css', 
        'resources/css/admin.css',
        'resources/js/app.js',
        'resources/js/admin-loading.js',
        'resources/js/admin-toggles.js',
        'resources/js/admin-images.js',
        'resources/js/admin-forms.js',
        'resources/js/admin-articles.js',
        'resources/js/admin-gallery.js',
        'resources/js/admin-banners.js',
    ]); ?>
    
    <style>
        .mobile-back-btn {
            background: none;
            border: none;
            color: #495057;
            font-size: 1.25rem;
            padding: 0.5rem;
            border-radius: 0.375rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        
        .mobile-back-btn:hover {
            background-color: rgba(0, 0, 0, 0.1);
            color: #007bff;
        }
        
        .mobile-back-btn:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
        }
    </style>
</head>
<body>
    <!-- Mobile Header -->
    <div class="mobile-header d-md-none">
        <?php
            $isCreateOrEditPage = request()->routeIs('admin.*.create') || request()->routeIs('admin.*.edit');
        ?>
        
        <?php if($isCreateOrEditPage): ?>
            <button class="mobile-back-btn" type="button" onclick="history.back()">
                <i class="bi bi-arrow-left"></i>
            </button>
        <?php else: ?>
            <button class="mobile-menu-btn" type="button" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
        <?php endif; ?>
        <h5 class="mb-0">
            <?php if(request()->routeIs('admin.analytics*')): ?>
                Аналитика
            <?php elseif(request()->routeIs('admin.profile.edit')): ?>
                Редактирование профиля
            <?php elseif(request()->routeIs('admin.profile*')): ?>
                Управление профилем
            <?php elseif(request()->routeIs('admin.gallery.create')): ?>
                Добавление изображения
            <?php elseif(request()->routeIs('admin.gallery.edit')): ?>
                Редактирование изображения
            <?php elseif(request()->routeIs('admin.gallery*')): ?>
                Галерея
            <?php elseif(request()->routeIs('admin.services.create')): ?>
                Создание услуги
            <?php elseif(request()->routeIs('admin.services.edit')): ?>
                Редактирование услуги
            <?php elseif(request()->routeIs('admin.services*')): ?>
                Услуги
            <?php elseif(request()->routeIs('admin.articles.create')): ?>
                Создание статьи
            <?php elseif(request()->routeIs('admin.articles.edit')): ?>
                Редактирование статьи
            <?php elseif(request()->routeIs('admin.articles*')): ?>
                Статьи
            <?php elseif(request()->routeIs('admin.banners.create')): ?>
                Создание баннера
            <?php elseif(request()->routeIs('admin.banners.edit')): ?>
                Редактирование баннера
            <?php elseif(request()->routeIs('admin.banners*')): ?>
                Баннеры
            <?php elseif(request()->routeIs('super-admin*')): ?>
                Супер Настройки
            <?php else: ?>
                Настройки
            <?php endif; ?>
        </h5>
        <button class="mobile-menu-btn" type="button" onclick="window.open('<?php echo e(route('user.show', ['username' => auth()->user()->username])); ?>', '_blank')">
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
                    <a class="nav-link <?php echo e(request()->routeIs('admin.analytics*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.analytics', $currentUserId)); ?>">
                        <i class="bi bi-graph-up me-2"></i>
                        Аналитика
                    </a>
                    <a class="nav-link <?php echo e(request()->routeIs('admin.profile*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.profile', $currentUserId)); ?>">
                        <i class="bi bi-person-circle me-2"></i>
                        Профиль
                    </a>
                    <?php if(isset($userSectionSettings) && $userSectionSettings->has('gallery')): ?>
                    <a class="nav-link <?php echo e(request()->routeIs('admin.gallery*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.gallery', $currentUserId)); ?>">
                        <i class="bi bi-images me-2"></i>
                        Галерея
                    </a>
                    <?php endif; ?>
                    <?php if(isset($userSectionSettings) && $userSectionSettings->has('services')): ?>
                    <a class="nav-link <?php echo e(request()->routeIs('admin.services*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.services', $currentUserId)); ?>">
                        <i class="bi bi-briefcase me-2"></i>
                        Услуги
                    </a>
                    <?php endif; ?>
                    <?php if(isset($userSectionSettings) && $userSectionSettings->has('articles')): ?>
                    <a class="nav-link <?php echo e(request()->routeIs('admin.articles*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.articles', $currentUserId)); ?>">
                        <i class="bi bi-journal-text me-2"></i>
                        Статьи
                    </a>
                    <?php endif; ?>
                    <?php if(isset($userSectionSettings) && $userSectionSettings->has('banners')): ?>
                    <a class="nav-link <?php echo e(request()->routeIs('admin.banners*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.banners', $currentUserId)); ?>">
                        <i class="bi bi-flag me-2"></i>
                        Баннеры
                    </a>
                    <?php endif; ?>
                    
                    <?php if(auth()->user()->isAdmin()): ?>
                    <hr class="my-3">
                    <a class="nav-link text-warning" href="<?php echo e(route('super-admin.index')); ?>">
                        <i class="bi bi-shield-check me-2"></i>
                        Супер Настройки
                    </a>
                    <?php endif; ?>
                </nav>

                <hr class="my-4">
                
                <div class="nav flex-column">
                    <a class="nav-link" href="<?php echo e(route('user.show', ['username' => auth()->user()->username])); ?>" target="_blank">
                        <i class="bi bi-eye me-2"></i>
                        Посмотреть страницу
                    </a>
                    
                    <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right me-2"></i>
                        Выйти
                    </a>
                </div>

                <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                    <?php echo csrf_field(); ?>
                </form>
            </div>

            <!-- Main content -->
            <div class="col-md-9 col-lg-10 admin-content">
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo e(session('error')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
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
    
    <?php echo $__env->yieldContent('scripts'); ?>
    
   

</body>
</html>
<?php /**PATH C:\OSPanel\domains\link\resources\views/admin/layout.blade.php ENDPATH**/ ?>