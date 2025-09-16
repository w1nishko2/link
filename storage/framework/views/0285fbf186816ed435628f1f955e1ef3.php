<!-- Админская навигация для владельца страницы -->
<?php
    $currentUserId = $pageUser->id;
    $userSectionSettings = \App\Models\UserSectionSettings::where('user_id', $currentUserId)->get()->keyBy('section_key');
?>

<div class="admin-navigation-bar bg-light border-bottom py-2">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <nav class="nav">
                    <a class="navbar-brand fw-bold me-3" href="<?php echo e(route('home')); ?>">
                        <?php echo e(config('app.name', 'Персональные Сайты')); ?>

                    </a>
                    
                    <a class="nav-link admin-nav-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('admin.dashboard', $currentUserId)); ?>">
                        <i class="bi bi-speedometer2 me-1"></i>
                        <span class="d-none d-md-inline">Главная</span>
                    </a>
                    <a class="nav-link admin-nav-link <?php echo e(request()->routeIs('admin.profile*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.profile', $currentUserId)); ?>">
                        <i class="bi bi-person-circle me-1"></i>
                        <span class="d-none d-md-inline">Профиль</span>
                    </a>
                    <?php if(isset($userSectionSettings) && $userSectionSettings->has('gallery')): ?>
                    <a class="nav-link admin-nav-link <?php echo e(request()->routeIs('admin.gallery*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.gallery', $currentUserId)); ?>">
                        <i class="bi bi-images me-1"></i>
                        <span class="d-none d-md-inline">Галерея</span>
                    </a>
                    <?php endif; ?>
                    <?php if(isset($userSectionSettings) && $userSectionSettings->has('services')): ?>
                    <a class="nav-link admin-nav-link <?php echo e(request()->routeIs('admin.services*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.services', $currentUserId)); ?>">
                        <i class="bi bi-briefcase me-1"></i>
                        <span class="d-none d-md-inline">Услуги</span>
                    </a>
                    <?php endif; ?>
                    <?php if(isset($userSectionSettings) && $userSectionSettings->has('articles')): ?>
                    <a class="nav-link admin-nav-link <?php echo e(request()->routeIs('admin.articles*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.articles', $currentUserId)); ?>">
                        <i class="bi bi-journal-text me-1"></i>
                        <span class="d-none d-md-inline">Статьи</span>
                    </a>
                    <?php endif; ?>
                    <?php if(isset($userSectionSettings) && $userSectionSettings->has('banners')): ?>
                    <a class="nav-link admin-nav-link <?php echo e(request()->routeIs('admin.banners*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.banners', $currentUserId)); ?>">
                        <i class="bi bi-flag me-1"></i>
                        <span class="d-none d-md-inline">Баннеры</span>
                    </a>
                    <?php endif; ?>
                </nav>
            </div>
            
            <div class="col-md-4 text-end">
                <div class="navbar-nav d-inline-flex">
                    <a class="nav-link" href="<?php echo e(route('articles.all')); ?>">
                        <i class="bi bi-collection me-1"></i>
                        <span class="d-none d-md-inline">Мир линка</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Скрытая форма для выхода -->
<form id="admin-logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
    <?php echo csrf_field(); ?>
</form>

<style>
.admin-navigation-bar {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.admin-nav-link {
    color: #495057;
    text-decoration: none;
    padding: 0.5rem 0.75rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    font-weight: 500;
    font-size: 0.9rem;
}

.admin-nav-link:hover {
    color: #007bff;
    background-color: rgba(0, 123, 255, 0.1);
    transform: translateY(-1px);
}

.admin-nav-link.active {
    color: #007bff;
    background-color: rgba(0, 123, 255, 0.15);
    font-weight: 600;
}

.admin-nav-link i {
    font-size: 1rem;
}

.navbar-brand {
    font-weight: bold;
    padding: 0.5rem 0;
    color: #212529;
    text-decoration: none;
}

.navbar-nav {
    display: flex;
    padding-left: 0;
    margin-bottom: 0;
    list-style: none;
}

.nav-link {
    display: block;
    padding: 0.5rem 1rem;
    color: #495057;
    text-decoration: none;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
}

.nav-link:hover, .nav-link:focus {
    color: #0056b3;
}

.nowrap {
    flex-wrap: nowrap;
}

@media (max-width: 768px) {
    .admin-navigation-bar .container .row {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .admin-navigation-bar .nav {
        justify-content: center;
        gap: 0.25rem;
    }
    
    .admin-nav-link {
        padding: 0.4rem 0.6rem;
        font-size: 0.85rem;
    }
    
    .col-md-4.text-end {
        text-align: center !important;
    }
}

@media (max-width: 576px) {
    .admin-navigation-bar .nav {
        flex-wrap: wrap;
    }
    
    .admin-nav-link {
        flex: 1;
        text-align: center;
        min-width: auto;
    }
}
</style>

<script>
// Функции для управления мобильным сайдбаром (если используется)
if (typeof toggleSidebar === 'undefined') {
    function toggleSidebar() {
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.querySelector('.sidebar-overlay');
        
        if (sidebar) {
            sidebar.classList.add('show');
            if (overlay) overlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
    }
}

if (typeof closeSidebar === 'undefined') {
    function closeSidebar() {
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.querySelector('.sidebar-overlay');
        
        if (sidebar) {
            sidebar.classList.remove('show');
            if (overlay) overlay.classList.remove('show');
            document.body.style.overflow = '';
        }
    }
}

// Обработчики событий для админской навигации
document.addEventListener('DOMContentLoaded', function() {
    // Закрыть сайдбар при изменении размера экрана
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768 && typeof closeSidebar === 'function') {
            closeSidebar();
        }
    });
    
    // Автозакрытие мобильного меню при клике на ссылки
    const navLinks = document.querySelectorAll('.admin-nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth < 768 && typeof closeSidebar === 'function') {
                setTimeout(closeSidebar, 150);
            }
        });
    });
    
    // Предотвращение зума на iOS при фокусе на инпуты
    if (/iPad|iPhone|iPod/.test(navigator.userAgent)) {
        const viewport = document.querySelector('meta[name=viewport]');
        if (viewport) {
            viewport.setAttribute('content', 'width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no');
        }
    }
});
</script>
</style>

<script>
// Функции для управления мобильным сайдбаром (если используется)
if (typeof toggleSidebar === 'undefined') {
    function toggleSidebar() {
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.querySelector('.sidebar-overlay');
        
        if (sidebar) {
            sidebar.classList.add('show');
            if (overlay) overlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
    }
}

if (typeof closeSidebar === 'undefined') {
    function closeSidebar() {
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.querySelector('.sidebar-overlay');
        
        if (sidebar) {
            sidebar.classList.remove('show');
            if (overlay) overlay.classList.remove('show');
            document.body.style.overflow = '';
        }
    }
}

// Обработчики событий для админской навигации
document.addEventListener('DOMContentLoaded', function() {
    // Закрыть сайдбар при изменении размера экрана
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768 && typeof closeSidebar === 'function') {
            closeSidebar();
        }
    });
    
    // Автозакрытие мобильного меню при клике на ссылки
    const navLinks = document.querySelectorAll('.admin-nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth < 768 && typeof closeSidebar === 'function') {
                setTimeout(closeSidebar, 150);
            }
        });
    });
    
    // Предотвращение зума на iOS при фокусе на инпуты
    if (/iPad|iPhone|iPod/.test(navigator.userAgent)) {
        const viewport = document.querySelector('meta[name=viewport]');
        if (viewport) {
            viewport.setAttribute('content', 'width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no');
        }
    }
});
</script><?php /**PATH C:\OSPanel\domains\link\resources\views/components/admin-navigation.blade.php ENDPATH**/ ?>