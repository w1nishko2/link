<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <!-- SEO Meta Tags -->
    <title><?php echo $__env->yieldContent('title', 'Настройки - ' . config('app.name', 'Laravel')); ?></title>
    <meta name="description" content="<?php echo $__env->yieldContent('description', 'Панель управления контентом персонального сайта'); ?>">
    <meta name="robots" content="noindex, nofollow">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('favicon.ico')); ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/css/admin.css']); ?>
    
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
            <?php if(request()->routeIs('admin.dashboard')): ?>
                Главная
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
        <button class="mobile-menu-btn" type="button" onclick="window.open('<?php echo e(route('user.page', ['username' => auth()->user()->username])); ?>', '_blank')">
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
                    <a class="nav-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('admin.dashboard', $currentUserId)); ?>">
                        <i class="bi bi-speedometer2 me-2"></i>
                        Главная
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
                    <a class="nav-link" href="<?php echo e(route('user.page', ['username' => auth()->user()->username])); ?>" target="_blank">
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
            }
        });
        
        // Улучшения для форм на мобильных устройствах
        document.addEventListener('DOMContentLoaded', function() {
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