{{-- Независимый компонент мобильной навигации для админки --}}
@props([
    'currentUserId' => null,
    'userSectionSettings' => null,
    'currentUser' => null
])

@php
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
@endphp

<!-- Mobile Header -->
<div class="mobile-header ">
    <button class="mobile-menu-btn" type="button" onclick="toggleMobileSidebar()">
        <i class="bi bi-list"></i>
    </button>
    <h5 class="mb-0">{{ $pageTitle }}</h5>
    @if($isAdminArea)
        {{-- В админке показываем кнопку просмотра страницы --}}
        <button class="mobile-menu-btn" type="button" onclick="window.open('{{ route('user.show', ['username' => $user->username]) }}', '_blank')">
            <i class="bi bi-eye"></i>
        </button>
    @else
        {{-- На пользовательской странице показываем кнопку QR --}}
        <button class="mobile-menu-btn" type="button" onclick="openQRModal()">
            <i class="bi bi-qr-code"></i>
        </button>
    @endif
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
        <a class="nav-link {{ request()->routeIs('admin.analytics*') ? 'active' : '' }}" href="{{ route('admin.analytics', $userId) }}">
            <i class="bi bi-graph-up me-2"></i>
            Аналитика
        </a>
        
        <a class="nav-link " href="{{ route('articles.all') }}">
            <i class="bi bi-collection me-2"></i>
            Мир Линка
        </a>
      
        <a class="nav-link {{ request()->routeIs('admin.profile*') ? 'active' : '' }}" href="{{ route('admin.profile', $userId) }}">
            <i class="bi bi-person-circle me-2"></i>
            Профиль
        </a>
        
        @if(isset($userSectionSettings) && $userSectionSettings->has('gallery'))
        <a class="nav-link {{ request()->routeIs('admin.gallery*') ? 'active' : '' }}" href="{{ route('admin.gallery', $userId) }}">
            <i class="bi bi-images me-2"></i>
            Галерея
        </a>
        @endif
        
        @if(isset($userSectionSettings) && $userSectionSettings->has('services'))
        <a class="nav-link {{ request()->routeIs('admin.services*') ? 'active' : '' }}" href="{{ route('admin.services', $userId) }}">
            <i class="bi bi-briefcase me-2"></i>
            Услуги
        </a>
        @endif
        
        @if(isset($userSectionSettings) && $userSectionSettings->has('articles'))
        <a class="nav-link {{ request()->routeIs('admin.articles*') ? 'active' : '' }}" href="{{ route('admin.articles', $userId) }}">
            <i class="bi bi-journal-text me-2"></i>
            Статьи
        </a>
        @endif
        
        @if(isset($userSectionSettings) && $userSectionSettings->has('banners'))
        <a class="nav-link {{ request()->routeIs('admin.banners*') ? 'active' : '' }}" href="{{ route('admin.banners', $userId) }}">
            <i class="bi bi-flag me-2"></i>
            Баннеры
        </a>
        @endif
        
        @if($user->isAdmin())
        <hr class="my-3">
        <a class="nav-link text-warning" href="{{ route('super-admin.index') }}">
            <i class="bi bi-shield-check me-2"></i>
            Супер Настройки
        </a>
        @endif
    </nav>

    <hr class="my-4">
    
    <div class="nav flex-column">
        <a class="nav-link" href="{{ route('user.show', ['username' => $user->username]) }}" target="_blank">
            <i class="bi bi-eye me-2"></i>
            Посмотреть страницу
        </a>
        
        <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('mobile-logout-form').submit();">
            <i class="bi bi-box-arrow-right me-2"></i>
            Выйти
        </a>
    </div>

    <form id="mobile-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
</div>
