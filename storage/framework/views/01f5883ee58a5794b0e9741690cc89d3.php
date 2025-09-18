
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
    'currentUserId' => null,
    'userSectionSettings' => null,
    'currentUser' => null
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
    'currentUserId' => null,
    'userSectionSettings' => null,
    'currentUser' => null
]); ?>
<?php foreach (array_filter(([
    'currentUserId' => null,
    'userSectionSettings' => null,
    'currentUser' => null
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<?php
    // Если не передан пользователь, используем текущего авторизованного
    $user = $currentUser ?? auth()->user();
    $userId = $currentUserId ?? $user->id;
    
    // Определяем, находимся ли мы в админке или на пользовательской странице
    $isAdminArea = request()->routeIs('admin*') || request()->routeIs('super-admin*');
    
    // Определяем заголовок по текущему маршруту
    $pageTitle = '';
    if(request()->routeIs('admin.profile.edit')) {
        $pageTitle = 'Редактирование профиля';
    } elseif(request()->routeIs('admin.profile*')) {
        $pageTitle = 'Управление профилем';
    } elseif(request()->routeIs('admin.gallery.create')) {
        $pageTitle = 'Добавление изображения';
    } elseif(request()->routeIs('admin.gallery.edit')) {
        $pageTitle = 'Редактирование изображения';
    } elseif(request()->routeIs('admin.gallery*')) {
        $pageTitle = 'Галерея';
    } elseif(request()->routeIs('admin.services.create')) {
        $pageTitle = 'Создание услуги';
    } elseif(request()->routeIs('admin.services.edit')) {
        $pageTitle = 'Редактирование услуги';
    } elseif(request()->routeIs('admin.services*')) {
        $pageTitle = 'Услуги';
    } elseif(request()->routeIs('admin.articles.create')) {
        $pageTitle = 'Создание статьи';
    } elseif(request()->routeIs('admin.articles.edit')) {
        $pageTitle = 'Редактирование статьи';
    } elseif(request()->routeIs('admin.articles*')) {
        $pageTitle = 'Статьи';
    } elseif(request()->routeIs('admin.banners.create')) {
        $pageTitle = 'Создание баннера';
    } elseif(request()->routeIs('admin.banners.edit')) {
        $pageTitle = 'Редактирование баннера';
    } elseif(request()->routeIs('admin.banners*')) {
        $pageTitle = 'Баннеры';
    } elseif(request()->routeIs('super-admin*')) {
        $pageTitle = 'Супер Настройки';
    } elseif(!$isAdminArea) {
        $pageTitle = 'Моя страница';
    } else {
        $pageTitle = 'Настройки';
    }
?>

<!-- Mobile Header -->
<div class="mobile-header ">
    <button class="mobile-menu-btn" type="button" onclick="toggleMobileSidebar()">
        <i class="bi bi-list"></i>
    </button>
    <h5 class="mb-0"><?php echo e($pageTitle); ?></h5>
    <?php if($isAdminArea): ?>
        
        <button class="mobile-menu-btn" type="button" onclick="window.open('<?php echo e(route('user.page', ['username' => $user->username])); ?>', '_blank')">
            <i class="bi bi-eye"></i>
        </button>
    <?php else: ?>
        
        <button class="mobile-menu-btn" type="button" onclick="openQRModal()">
            <i class="bi bi-qr-code"></i>
        </button>
    <?php endif; ?>
</div>

<!-- Sidebar Overlay -->
<div class="mobile-sidebar-overlay" onclick="closeMobileSidebar()"></div>

<!-- Mobile Sidebar -->
<div class="mobile-admin-sidebar" id="mobileAdminSidebar">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center">
            <i class="bi bi-gear-fill me-2 fs-4"></i>
            <h5 class="mb-0">Настройки</h5>
        </div>
        <button class="mobile-menu-btn" type="button" onclick="closeMobileSidebar()">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    
    <nav class="nav flex-column">
        <a class="nav-link <?php echo e(request()->routeIs('admin.analytics*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.analytics', $userId)); ?>">
            <i class="bi bi-graph-up me-2"></i>
            Аналитика
        </a>
        
        <a class="nav-link " href="<?php echo e(route('articles.all')); ?>">
            <i class="bi bi-collection me-2"></i>
            Мир Линка
        </a>
      
        <a class="nav-link <?php echo e(request()->routeIs('admin.profile*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.profile', $userId)); ?>">
            <i class="bi bi-person-circle me-2"></i>
            Профиль
        </a>
        
        <?php if(isset($userSectionSettings) && $userSectionSettings->has('gallery')): ?>
        <a class="nav-link <?php echo e(request()->routeIs('admin.gallery*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.gallery', $userId)); ?>">
            <i class="bi bi-images me-2"></i>
            Галерея
        </a>
        <?php endif; ?>
        
        <?php if(isset($userSectionSettings) && $userSectionSettings->has('services')): ?>
        <a class="nav-link <?php echo e(request()->routeIs('admin.services*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.services', $userId)); ?>">
            <i class="bi bi-briefcase me-2"></i>
            Услуги
        </a>
        <?php endif; ?>
        
        <?php if(isset($userSectionSettings) && $userSectionSettings->has('articles')): ?>
        <a class="nav-link <?php echo e(request()->routeIs('admin.articles*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.articles', $userId)); ?>">
            <i class="bi bi-journal-text me-2"></i>
            Статьи
        </a>
        <?php endif; ?>
        
        <?php if(isset($userSectionSettings) && $userSectionSettings->has('banners')): ?>
        <a class="nav-link <?php echo e(request()->routeIs('admin.banners*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.banners', $userId)); ?>">
            <i class="bi bi-flag me-2"></i>
            Баннеры
        </a>
        <?php endif; ?>
        
        <?php if($user->isAdmin()): ?>
        <hr class="my-3">
        <a class="nav-link text-warning" href="<?php echo e(route('super-admin.index')); ?>">
            <i class="bi bi-shield-check me-2"></i>
            Супер Настройки
        </a>
        <?php endif; ?>
    </nav>

    <hr class="my-4">
    
    <div class="nav flex-column">
        <a class="nav-link" href="<?php echo e(route('user.page', ['username' => $user->username])); ?>" target="_blank">
            <i class="bi bi-eye me-2"></i>
            Посмотреть страницу
        </a>
        
        <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('mobile-logout-form').submit();">
            <i class="bi bi-box-arrow-right me-2"></i>
            Выйти
        </a>
    </div>

    <form id="mobile-logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
        <?php echo csrf_field(); ?>
    </form>
</div><?php /**PATH C:\OSPanel\domains\link\resources\views/components/mobile-navigation.blade.php ENDPATH**/ ?>