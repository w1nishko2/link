<!doctype >
    <title><?php echo $__env->yieldContent('title', config('app.name', 'Laravel')); ?></title>
    <meta name="description" content="<?php echo $__env->yieldContent('description', 'Персональный сайт-визитка. Узнайте больше о моих услугах, статьях и проектах.'); ?>">
    <meta name="keywords" content="<?php echo $__env->yieldContent('keywords', 'персональный сайт, визитка, услуги, портфолио, контакты'); ?>">
    <meta name="author" content="<?php echo $__env->yieldContent('author', config('app.name')); ?>">
    <meta name="robots" content="<?php echo $__env->yieldContent('robots', 'index, follow'); ?>">
    
    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#2A5885">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Linkink">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <meta name="msapplication-TileColor" content="#2A5885">
    <meta name="msapplication-tap-highlight" content="no">
    
    <!-- Apple Touch Icons -->
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo e(asset('icons/icon-152x152.svg')); ?>">
    <link rel="apple-touch-icon" sizes="192x192" href="<?php echo e(asset('icons/icon-192x192.svg')); ?>">
    
    <!-- Splash Screen for iOS -->
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-startup-image" href="<?php echo e(asset('hero.png')); ?>">
    
    <!-- Preconnect для улучшения производительности -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="preconnect" href="https://cdn.jsdelivr.net"><html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover, shrink-to-fit=no">
    
    <!-- Дополнительные мета-теги для мобильных устройств -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="format-detection" content="telephone=no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#2A5885">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <!-- User Data for JavaScript -->
    <?php if(isset($currentUser) && $currentUser): ?>
        <meta name="current-user-id" content="<?php echo e($currentUser->id); ?>">
    <?php endif; ?>
    <?php if(isset($pageUser) && $pageUser): ?>
        <meta name="page-user-id" content="<?php echo e($pageUser->id); ?>">
    <?php endif; ?>

    <!-- SEO Meta Tags -->
    <title><?php echo $__env->yieldContent('title', config('app.name', 'Laravel')); ?></title>
    <meta name="description" content="<?php echo $__env->yieldContent('description', 'Персональный сайт-визитка. Узнайте больше о моих услугах, статьях и проектах.'); ?>">
    <meta name="keywords" content="<?php echo $__env->yieldContent('keywords', 'персональный сайт, визитка, услуги, портфолио, контакты'); ?>">
    <meta name="author" content="<?php echo $__env->yieldContent('author', config('app.name')); ?>">
    <meta name="robots" content="<?php echo $__env->yieldContent('robots', 'index, follow'); ?>">

    <!-- Open Graph Meta Tags -->
    <meta property="og:type" content="<?php echo $__env->yieldContent('og_type', 'website'); ?>">
    <meta property="og:site_name" content="<?php echo e(config('app.name')); ?>">
    <meta property="og:title" content="<?php echo $__env->yieldContent('og_title', config('app.name')); ?>">
    <meta property="og:description" content="<?php echo $__env->yieldContent('og_description', 'Персональный сайт-визитка'); ?>">
    <meta property="og:url" content="<?php echo $__env->yieldContent('og_url', request()->url()); ?>">
    <meta property="og:image" content="<?php echo $__env->yieldContent('og_image', asset('/hero.png')); ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $__env->yieldContent('twitter_title'); ?>">
    <meta name="twitter:description" content="<?php echo $__env->yieldContent('twitter_description'); ?>">
    <meta name="twitter:image" content="<?php echo $__env->yieldContent('twitter_image', asset('/hero.png')); ?>">

    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo $__env->yieldContent('canonical_url', request()->url()); ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('favicon.ico')); ?>">
    <link rel="apple-touch-icon" href="<?php echo e(asset('favicon.ico')); ?>">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="<?php echo e(asset('manifest.json')); ?>">

    <!-- DNS Prefetch for Performance -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">

    <!-- Fonts -->
    <link rel="preload" href="https://fonts.bunny.net/css?family=Nunito" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.bunny.net/css?family=Nunito"></noscript>
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"></noscript>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- QR Code Library with round dots -->
    <script src="https://unpkg.com/qrcode-generator@1.4.4/qrcode.js"></script>

    <!-- iOS Safari Fixes -->
    <style>
        /* Фиксы для iPhone - предотвращение плавающего эффекта */
        html, body {
            max-width: 100vw;
            overflow-x: hidden;
            overscroll-behavior-x: none;
            touch-action: pan-y;
            position: relative;
            -webkit-overflow-scrolling: touch;
        }

        body {
            /* Для iOS Safari: предотвращает bounce-эффект */
            overscroll-behavior: contain;
            /* Включаем аппаратное ускорение */
            transform: translate3d(0,0,0);
            -webkit-transform: translate3d(0,0,0);
        }

        /* Фикс для 100vh на iOS */
        :root {
            --vh: 1vh;
        }
        
        @media (max-width: 768px) {
            .full-vh {
                height: calc(var(--vh, 1vh) * 100);
            }
            
            /* Дополнительные фиксы для iOS */
            * {
                -webkit-touch-callout: none;
                -webkit-tap-highlight-color: transparent;
            }
            
            /* Предотвращаем горизонтальное перетаскивание */
            .container, .container-fluid {
                max-width: 100%;
                overflow-x: hidden;
            }
        }
    </style>

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/css/services-reels.css', 'resources/css/pwa.css', 'resources/js/app.js', 'resources/js/modal-scroll-lock.js', 'resources/js/pwa-installer.js']); ?>
    
    
    <?php if(isset($pageUser) && isset($currentUser) && $currentUser && $currentUser->id === $pageUser->id): ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/mobile-navigation.css', 'resources/js/mobile-navigation.js']); ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/owner-defaults.css']); ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/photo-editor.css', 'resources/js/photo-editor.js']); ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/js/long-press-editor.js']); ?>
    <?php endif; ?>

    <!-- Additional Head Content -->
    <?php echo $__env->yieldPushContent('head'); ?>
    
    <!-- PWA Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js')
                    .then(function(registration) {
                        console.log('ServiceWorker зарегистрирован успешно: ', registration.scope);
                    }, function(err) {
                        console.log('ServiceWorker регистрация не удалась: ', err);
                    });
            });
        }
    </script>
</head>

<body>
    <div id="app">
        <?php if(isset($pageUser) && isset($currentUser) && $currentUser && $currentUser->id === $pageUser->id): ?>
            
            <?php
                $userSectionSettings = \App\Models\UserSectionSettings::where('user_id', $currentUser->id)->get()->keyBy('section_key');
            ?>
            <?php if (isset($component)) { $__componentOriginal0d3b2b7a1a27f99acddd84d2b2599baa = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0d3b2b7a1a27f99acddd84d2b2599baa = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.mobile-navigation','data' => ['currentUserId' => $currentUser->id,'userSectionSettings' => $userSectionSettings,'currentUser' => $currentUser]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('mobile-navigation'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['currentUserId' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($currentUser->id),'userSectionSettings' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($userSectionSettings),'currentUser' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($currentUser)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0d3b2b7a1a27f99acddd84d2b2599baa)): ?>
<?php $attributes = $__attributesOriginal0d3b2b7a1a27f99acddd84d2b2599baa; ?>
<?php unset($__attributesOriginal0d3b2b7a1a27f99acddd84d2b2599baa); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0d3b2b7a1a27f99acddd84d2b2599baa)): ?>
<?php $component = $__componentOriginal0d3b2b7a1a27f99acddd84d2b2599baa; ?>
<?php unset($__componentOriginal0d3b2b7a1a27f99acddd84d2b2599baa); ?>
<?php endif; ?>
        <?php else: ?>
            
            <!-- Навигация для всех пользователей -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="container nowrap">
                    <a class="navbar-brand fw-bold" href="<?php echo e(route('home')); ?>">
                        <?php echo e(config('app.name', 'Персональные Сайты')); ?>

                    </a>
                    <!-- Общие ссылки для всех пользователей -->
                    <div class="navbar-nav ">
                        <a class="nav-link" href="<?php echo e(route('articles.all')); ?>">
                            <i class="bi bi-collection me-1"></i>Мир линка
                        </a>
                    </div>
                    <?php if(auth()->guard()->check()): ?>
                        <!-- Меню для авторизованных пользователей -->
                        <div class="navbar-nav ms-auto">
                            <a class="nav-link" href="<?php echo e(route('admin.analytics', ['user' => auth()->id()])); ?>" title="Мой кабинет">
                                <i class="bi bi-gear me-1"></i>
                                <span class="d-none d-md-inline">Мой кабинет</span>
                            </a>
                            <a class="nav-link" href="<?php echo e(route('user.show', ['username' => auth()->user()->username])); ?>" title="Моя страница">
                                <i class="bi bi-person me-1"></i>
                                <span class="d-none d-md-inline">Моя страница</span>
                            </a>
                        </div>
                    <?php else: ?>
                        <!-- Меню для неавторизованных пользователей -->
                        <div class="navbar-nav ms-auto">
                            <a class="nav-link" href="<?php echo e(route('login')); ?>" title="Вход">
                                <i class="bi bi-box-arrow-in-right me-1"></i>
                                <span class="d-none d-md-inline">Вход</span>
                            </a>
                            <a class="nav-link" href="<?php echo e(route('register')); ?>" title="Регистрация">
                                <i class="bi bi-person-plus me-1"></i>
                                <span class="d-none d-md-inline">Регистрация</span>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </nav>
        <?php endif; ?>
       
            <?php echo $__env->yieldContent('content'); ?>
        
    </div>

    <!-- Banner Modal -->
    <div class="modal fade" id="bannerModal" tabindex="-1" aria-labelledby="bannerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-sm-down modal-xl">
            <div class="modal-content banner-modal-content">
                <!-- Кнопка закрытия -->
                <button type="button" class="banner-close-btn" data-bs-dismiss="modal" aria-label="Закрыть">
                    <i class="bi bi-x-lg"></i>
                </button>

                <div class="modal-body p-0">
                    <div class="banner-post-container">
                        <!-- Изображение поста -->
                        <div class="banner-post-image">
                            <img id="bannerModalImage" src="" alt="" class="img-fluid">
                        </div>

                        <!-- Контент поста -->
                        <div class="banner-post-content">
                            <!-- Заголовок и мета-информация -->
                            <div class="banner-post-header">
                                <div class="banner-post-author">
                                    <div class="banner-author-avatar">
                                        <img id="bannerAuthorAvatar" src="" alt="Автор"
                                            class="rounded-circle">
                                    </div>
                                    <div class="banner-author-info">
                                        <h5 id="bannerAuthorName" class="mb-0"><?php echo e($pageUser->name ?? 'Автор'); ?>

                                        </h5>
                                        <small id="bannerPostDate" class="text-muted">Сегодня</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Основной контент -->
                            <div class="banner-post-body">
                                <h2 id="bannerModalTitle" class="banner-post-title"></h2>
                                <div id="bannerModalDescription" class="banner-post-description"></div>
                                <!-- Кнопка ссылки баннера -->
                                <div id="bannerLinkContainer" class="mt-3" style="display: none;">
                                    <a id="bannerLink" href="#" class="btn btn-primary" target="_blank"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Modal -->
    <div class="modal fade" id="serviceModal" tabindex="-1" aria-labelledby="serviceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-sm-down modal-xl">
            <div class="modal-content service-modal-content">
                <!-- Кнопка закрытия -->
                <button type="button" class="service-close-btn" data-bs-dismiss="modal" aria-label="Закрыть">
                    <i class="bi bi-x-lg"></i>
                </button>

                <div class="modal-body p-0">
                    <div class="service-post-container">
                        <!-- Изображение услуги -->
                        <div class="service-post-image">
                            <img id="serviceModalImage" src="" alt="" class="img-fluid">
                        </div>

                        <!-- Контент услуги -->
                        <div class="service-post-content">
                            <!-- Заголовок и мета-информация -->
                            <div class="service-post-header">
                                <div class="service-post-author">
                                    <div class="service-author-avatar">
                                        <img id="serviceAuthorAvatar" src="" alt="Автор"
                                            class="rounded-circle">
                                    </div>
                                    <div class="service-author-info">
                                        <h5 id="serviceAuthorName" class="mb-0"><?php echo e($pageUser->name ?? 'Автор'); ?>

                                        </h5>
                                        <small id="servicePostDate" class="text-muted">Сегодня</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Основной контент -->
                            <div class="service-post-body">
                                <h2 id="serviceModalTitle" class="service-post-title"></h2>
                                <div id="serviceModalDescription" class="service-post-description"></div>

                                <!-- Цена услуги -->
                                <div id="servicePriceContainer" class="mt-3" style="display: none;">
                                    <div class="service-price-block">
                                        <h4 id="servicePrice" class=""></h4>
                                    </div>
                                </div>

                                <!-- Кнопка действия -->
                                <div id="serviceButtonContainer" class="mt-3" style="display: none;">
                                    <a id="serviceActionButton" href="#" class="btn btn-primary btn-lg w-100" target="_blank" rel="noopener noreferrer">
                                        <span id="serviceButtonText">Связаться</span>
                                    </a>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    <!-- Передача данных о владельце страницы в JavaScript -->
    <script>
        // Данные пользователя для JavaScript
        window.isOwner = <?php echo json_encode(isset($pageUser) && isset($currentUser) && $currentUser && $currentUser->id === $pageUser->id, 15, 512) ?>;
        window.pageUserId = <?php echo json_encode(isset($pageUser) ? $pageUser->id : null, 15, 512) ?>;
        window.currentUserId = <?php echo json_encode(isset($currentUser) ? $currentUser->id : null, 15, 512) ?>;
    </script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM загружен, инициализация...', 'isOwner:', window.isOwner);

            const bannersSwiper = new Swiper('.banners-swiper', {
                slidesPerView: 2.4,
                spaceBetween: 20,
                loop: false, // Отключаем цикличность
                initialSlide: window.isOwner ? 1 : 0, // Пропускаем первый дефолтный блок для владельца
                navigation: {
                    nextEl: '.banners-swiper .swiper-button-next',
                    prevEl: '.banners-swiper .swiper-button-prev',
                },
                breakpoints: {
                    320: {
                        slidesPerView: 1.1,
                        spaceBetween: 20,
                    },
                    768: {
                        slidesPerView: 2.2,
                        spaceBetween: 20,
                    },
                    1024: {
                        slidesPerView: 2.3,
                        spaceBetween: 20,
                    }
                }
            });

            // Инициализация слайдера услуг
            const servicesSwiper = new Swiper('.services-swiper', {
                slidesPerView: 2.4,
                spaceBetween: 20,
                loop: false, // Отключаем цикличность
                initialSlide: window.isOwner ? 1 : 0, // Пропускаем первый дефолтный блок для владельца
                navigation: {
                    nextEl: '.services-swiper .swiper-button-next',
                    prevEl: '.services-swiper .swiper-button-prev',
                },
                breakpoints: {
                    320: {
                        slidesPerView: 1.4,
                        spaceBetween: 20,
                    },
                    480: {
                        slidesPerView: 2.2,
                        spaceBetween:20,
                    },
                    700: {
                          slidesPerView: 3.4,
                        spaceBetween: 20,
                    },
                    768: {
                        slidesPerView: 2.8,
                        spaceBetween: 20,
                    },
                    1024: {
                        slidesPerView: 2.7,
                        spaceBetween: 20,
                    },
                    1200: {
                        slidesPerView: 3.5,
                        spaceBetween: 20,
                    }
                }
            });

            console.log('Swiper инициализирован');

            initializeBannerModal();
            initializeServiceModal();

            initializeSocialWidget();

            // Инициализация галереи
            initializeGallery();

            console.log('Все модули инициализированы');
        });

        // Функция инициализации галереи с Swiper
        function initializeGallery() {
            // Инициализация модального окна (оставляем как есть)
            const galleryModal = document.getElementById('galleryModal');
            const modalImage = document.getElementById('modalImage');
            const modalTitle = document.getElementById('galleryModalLabel');

            if (galleryModal && modalImage) {
                galleryModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const imageSrc = button.getAttribute('data-image');
                    const imageAlt = button.getAttribute('data-alt');

                    modalImage.src = imageSrc;
                    modalImage.alt = imageAlt;
                    modalTitle.textContent = imageAlt || 'Просмотр изображения';
                });
            }

            // Инициализация Swiper галереи
            const gallerySwiper = document.querySelector('.gallery-swiper');
            if (gallerySwiper) {
                const swiper = new Swiper('.gallery-swiper', {
                    slidesPerView: 1.4,
                    spaceBetween: 20,
                    loop: false, // Отключаем цикличность
                    initialSlide: window.isOwner ? 1 : 0, // Пропускаем первый дефолтный блок для владельца
                    navigation: {
                        nextEl: '.gallery-swiper .swiper-button-next',
                        prevEl: '.gallery-swiper .swiper-button-prev',
                    },
                    pagination: {
                        el: '.gallery-swiper .swiper-pagination',
                        clickable: true,
                        dynamicBullets: true,
                    },
                    breakpoints: {
                        320: {
                            slidesPerView: 1.2,
                            spaceBetween: 20,
                            centeredSlides: false, // Убираем центрирование для лучшего доступа к первому слайду
                        },
                        480: {
                            slidesPerView: 1.6,
                            spaceBetween: 20,
                            centeredSlides: false,
                        },
                        640: {
                             slidesPerView: 2.2,
                            spaceBetween: 20,
                        },
                        768: {
                            slidesPerView: 2.2,
                            spaceBetween: 20,
                        },
                        1024: {
                            slidesPerView: 3.2,
                            spaceBetween: 20,
                        },
                        1200: {
                            slidesPerView: 3.2,
                            spaceBetween: 20,
                        }
                    },
                    // Настройки для мобильных устройств
                    touchEventsTarget: 'container',
                    touchRatio: 1,
                    touchAngle: 45,
                    grabCursor: true,
                    // Отключаем автовоспроизведение для лучшего UX
                    autoplay: false,
                    // Плавная анимация
                    speed: 300,
                    effect: 'slide',
                });

                console.log('Gallery Swiper инициализирован');
            }
        }

        // Инициализация модального окна баннеров
        function initializeBannerModal() {
            console.log('Banner modal initialized');

            // Добавляем обработчики кликов для всех баннеров
            document.querySelectorAll('.banners-banner').forEach(banner => {
                banner.style.cursor = 'pointer';

                banner.addEventListener('click', function() {
                    const title = this.querySelector('h3') ? this.querySelector('h3').textContent :
                        'Заголовок';
                    const description = this.querySelector('p') ? this.querySelector('p').textContent : '';
                    const imageElement = this.querySelector('img');
                    const imageSrc = imageElement ? imageElement.src : '';
                    const imageAlt = imageElement ? imageElement.alt : title;

                    // Получаем данные ссылки из data-атрибутов
                    const linkUrl = this.getAttribute('data-link-url');
                    const linkText = this.getAttribute('data-link-text');

                    // Заполняем модальное окно данными
                    const modalTitle = document.getElementById('bannerModalTitle');
                    const modalDescription = document.getElementById('bannerModalDescription');

                    if (modalTitle) {
                        modalTitle.textContent = title;
                    }
                    if (modalDescription) {
                        modalDescription.textContent = description;
                    }

                    const modalImage = document.getElementById('bannerModalImage');
                    if (modalImage) {
                        if (imageSrc) {
                            modalImage.src = imageSrc;
                            modalImage.alt = imageAlt;
                            modalImage.style.display = 'block';
                        } else {
                            modalImage.style.display = 'none';
                        }
                    }

                    // Обрабатываем кнопку ссылки
                    const bannerLinkContainer = document.getElementById('bannerLinkContainer');
                    const bannerLink = document.getElementById('bannerLink');

                    if (linkUrl && linkText && bannerLinkContainer && bannerLink) {
                        bannerLink.href = linkUrl;
                        bannerLink.textContent = linkText;
                        bannerLinkContainer.style.display = 'block';
                    } else if (bannerLinkContainer) {
                        bannerLinkContainer.style.display = 'none';
                    }

                    // Устанавливаем информацию об авторе
                    const authorAvatar = document.getElementById('bannerAuthorAvatar');
                    const authorName = document.getElementById('bannerAuthorName');
                    const postDate = document.getElementById('bannerPostDate');

                    <?php if(isset($pageUser)): ?>
                        if (authorAvatar) {
                            authorAvatar.src =
                                "<?php echo e($pageUser->avatar ? asset('storage/' . $pageUser->avatar) : asset('/hero.png')); ?>";
                        }
                        if (authorName) {
                            authorName.textContent = "<?php echo e($pageUser->name); ?>";
                        }
                    <?php endif; ?>

                    if (postDate) {
                        postDate.textContent = new Date().toLocaleDateString('ru-RU');
                    }

                    // Настраиваем ссылки для социальных сетей
                    const currentUrl = window.location.href;
                    const shareText = encodeURIComponent(`${title} - ${description}`);

                    const shareTelegram = document.getElementById('shareTelegram');
                    const shareWhatsApp = document.getElementById('shareWhatsApp');
                    const shareVK = document.getElementById('shareVK');
                    const shareOK = document.getElementById('shareOK');

                    if (shareTelegram) {
                        shareTelegram.href =
                            `https://t.me/share/url?url=${encodeURIComponent(currentUrl)}&text=${shareText}`;
                    }
                    if (shareWhatsApp) {
                        shareWhatsApp.href =
                            `https://wa.me/?text=${shareText} ${encodeURIComponent(currentUrl)}`;
                    }
                    if (shareVK) {
                        shareVK.href =
                            `https://vk.com/share.php?url=${encodeURIComponent(currentUrl)}&title=${shareText}`;
                    }
                    if (shareOK) {
                        shareOK.href =
                            `https://connect.ok.ru/dk?st.cmd=WidgetSharePreview&st.shareUrl=${encodeURIComponent(currentUrl)}`;
                    }

                    // Показываем модальное окно
                    const modal = document.getElementById('bannerModal');
                    if (modal) {
                        const bootstrapModal = new bootstrap.Modal(modal);
                        bootstrapModal.show();
                    }
                });
            });
        }

        // Инициализация модального окна услуг
        function initializeServiceModal() {
            console.log('Service modal initialized');

            // Добавляем обработчики кликов для всех услуг
            document.querySelectorAll('.service-card').forEach(service => {
                service.addEventListener('click', function() {
                    const title = this.getAttribute('data-service-title') || 'Название услуги';
                    const description = this.getAttribute('data-service-description') || '';
                    const price = this.getAttribute('data-service-price') || '';
                    const imageSrc = this.getAttribute('data-service-image') || '';

                    // Заполняем модальное окно данными
                    const modalTitle = document.getElementById('serviceModalTitle');
                    const modalDescription = document.getElementById('serviceModalDescription');

                    if (modalTitle) {
                        modalTitle.textContent = title;
                    }
                    if (modalDescription) {
                        modalDescription.textContent = description;
                    }

                    const modalImage = document.getElementById('serviceModalImage');
                    if (modalImage) {
                        if (imageSrc) {
                            modalImage.src = imageSrc;
                            modalImage.alt = title;
                            modalImage.style.display = 'block';
                        } else {
                            modalImage.style.display = 'none';
                        }
                    }

                    // Обрабатываем цену услуги
                    const servicePriceContainer = document.getElementById('servicePriceContainer');
                    const servicePrice = document.getElementById('servicePrice');

                    if (price && servicePriceContainer && servicePrice) {
                        servicePrice.textContent = price;
                        servicePriceContainer.style.display = 'block';
                    } else if (servicePriceContainer) {
                        servicePriceContainer.style.display = 'none';
                    }

                    // Обрабатываем кнопку действия
                    const buttonText = this.getAttribute('data-service-button-text') || '';
                    const buttonLink = this.getAttribute('data-service-button-link') || '';
                    const serviceButtonContainer = document.getElementById('serviceButtonContainer');
                    const serviceActionButton = document.getElementById('serviceActionButton');
                    const serviceButtonTextEl = document.getElementById('serviceButtonText');

                    // Отладочная информация
                    console.log('Button debug:', {
                        buttonText: buttonText,
                        buttonLink: buttonLink,
                        hasContainer: !!serviceButtonContainer,
                        hasButton: !!serviceActionButton,
                        hasTextEl: !!serviceButtonTextEl
                    });

                    if (buttonText && buttonLink && serviceButtonContainer && serviceActionButton && serviceButtonTextEl) {
                        serviceButtonTextEl.textContent = buttonText;
                        serviceActionButton.href = buttonLink;
                        serviceButtonContainer.style.display = 'block';
                        console.log('Button displayed successfully');
                    } else if (serviceButtonContainer) {
                        serviceButtonContainer.style.display = 'none';
                        console.log('Button hidden - missing data or elements');
                    }

                    // Устанавливаем информацию об авторе
                    const authorAvatar = document.getElementById('serviceAuthorAvatar');
                    const authorName = document.getElementById('serviceAuthorName');
                    const postDate = document.getElementById('servicePostDate');

                    <?php if(isset($pageUser)): ?>
                        if (authorAvatar) {
                            authorAvatar.src =
                                "<?php echo e($pageUser->avatar ? asset('storage/' . $pageUser->avatar) : asset('/hero.png')); ?>";
                        }
                        if (authorName) {
                            authorName.textContent = "<?php echo e($pageUser->name); ?>";
                        }
                    <?php endif; ?>

                    if (postDate) {
                        postDate.textContent = new Date().toLocaleDateString('ru-RU');
                    }

                    // Настраиваем ссылки для социальных сетей
                    const currentUrl = window.location.href;
                    const shareText = encodeURIComponent(`${title} - ${description}`);

                    const shareServiceTelegram = document.getElementById('shareServiceTelegram');
                    const shareServiceWhatsApp = document.getElementById('shareServiceWhatsApp');
                    const shareServiceVK = document.getElementById('shareServiceVK');
                    const shareServiceOK = document.getElementById('shareServiceOK');

                    if (shareServiceTelegram) {
                        shareServiceTelegram.href =
                            `https://t.me/share/url?url=${encodeURIComponent(currentUrl)}&text=${shareText}`;
                    }
                    if (shareServiceWhatsApp) {
                        shareServiceWhatsApp.href =
                            `https://wa.me/?text=${shareText} ${encodeURIComponent(currentUrl)}`;
                    }
                    if (shareServiceVK) {
                        shareServiceVK.href =
                            `https://vk.com/share.php?url=${encodeURIComponent(currentUrl)}&title=${shareText}`;
                    }
                    if (shareServiceOK) {
                        shareServiceOK.href =
                            `https://connect.ok.ru/dk?st.cmd=WidgetSharePreview&st.shareUrl=${encodeURIComponent(currentUrl)}`;
                    }

                    // Показываем модальное окно
                    const modal = document.getElementById('serviceModal');
                    if (modal) {
                        const bootstrapModal = new bootstrap.Modal(modal);
                        bootstrapModal.show();
                    }
                });
            });
        }

        function initializeSocialWidget() {
            console.log('Social widget initialized');

            const socialMainBtn = document.getElementById('socialMainBtn');
            const socialLinks = document.getElementById('socialLinks');

            if (socialMainBtn && socialLinks) {
                let isOpen = false;

                socialMainBtn.addEventListener('click', function(e) {
                    e.preventDefault();

                    if (isOpen) {
                        // Закрываем меню
                        socialLinks.classList.remove('show');
                        socialMainBtn.classList.remove('active');
                        isOpen = false;
                    } else {
                        // Открываем меню
                        socialLinks.classList.add('show');
                        socialMainBtn.classList.add('active');
                        isOpen = true;
                    }
                });

                // Закрытие при клике вне кнопки
                document.addEventListener('click', function(e) {
                    if (!socialMainBtn.contains(e.target) && !socialLinks.contains(e.target)) {
                        socialLinks.classList.remove('show');
                        socialMainBtn.classList.remove('active');
                        isOpen = false;
                    }
                });
            }
        }

        // Функция копирования URL страницы
        function copyPageUrl() {
            const pageUrl = document.getElementById('pageUrl');
            if (pageUrl) {
                const textToCopy = pageUrl.textContent;

                // Используем современный Clipboard API
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(textToCopy).then(() => {
                        showCopyMessage('Ссылка скопирована!');
                    }).catch(err => {
                        console.error('Ошибка при копировании:', err);
                        fallbackCopyTextToClipboard(textToCopy);
                    });
                } else {
                    // Fallback для старых браузеров
                    fallbackCopyTextToClipboard(textToCopy);
                }
            }
        }

        // Fallback функция для копирования
        function fallbackCopyTextToClipboard(text) {
            const textArea = document.createElement("textarea");
            textArea.value = text;
            textArea.style.top = "0";
            textArea.style.left = "0";
            textArea.style.position = "fixed";

            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    showCopyMessage('Ссылка скопирована!');
                } else {
                    showCopyMessage('Не удалось скопировать ссылку');
                }
            } catch (err) {
                console.error('Fallback: Ошибка при копировании', err);
                showCopyMessage('Ошибка при копировании');
            }

            document.body.removeChild(textArea);
        }

        // Функция показа сообщения о копировании
        function showCopyMessage(message) {
            // Создаем временное уведомление
            const notification = document.createElement('div');
            notification.className = 'alert alert-success position-fixed';
            notification.style.top = '20px';
            notification.style.right = '20px';
            notification.style.zIndex = '9999';
            notification.style.minWidth = '200px';
            notification.textContent = message;

            document.body.appendChild(notification);

            // Удаляем уведомление через 2 секунды
            setTimeout(() => {
                if (document.body.contains(notification)) {
                    document.body.removeChild(notification);
                }
            }, 2000);
        }

        <?php if(auth()->guard()->check()): ?>
        // QR Code Functions
        function openQRModal() {
            const modal = new bootstrap.Modal(document.getElementById('qrModal'));
            generateQRCode();
            modal.show();
        }

        function generateQRCode() {
            const qrContainer = document.getElementById('qrcode');
            const pageUrl = "<?php echo e(route('user.show', ['username' => auth()->user()->username])); ?>";
            
            // Очищаем контейнер
            qrContainer.innerHTML = '';
            
            // Создаем canvas для QR кода
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            qrContainer.appendChild(canvas);
            
            // Генерируем QR код
            const qr = qrcode(0, 'M');
            qr.addData(pageUrl);
            qr.make();
            
            const moduleCount = qr.getModuleCount();
            const size = 320;
            const border = 20;
            const cellSize = Math.floor((size - 2 * border) / moduleCount);
            
            canvas.width = size;
            canvas.height = size;
            
            // Заливаем фон белым
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, size, size);
            
            // Функция для проверки, является ли пиксель частью finder pattern
            function isFinderPattern(row, col) {
                return (
                    (row < 9 && col < 9) || 
                    (row < 9 && col >= moduleCount - 8) || 
                    (row >= moduleCount - 8 && col < 9)
                );
            }
            
            // Функция для проверки соседей
            function hasNeighbor(row, col, direction) {
                let checkRow = row, checkCol = col;
                switch(direction) {
                    case 'top': checkRow--; break;
                    case 'bottom': checkRow++; break;
                    case 'left': checkCol--; break;
                    case 'right': checkCol++; break;
                }
                
                if (checkRow < 0 || checkRow >= moduleCount || checkCol < 0 || checkCol >= moduleCount) {
                    return false;
                }
                return qr.isDark(checkRow, checkCol);
            }
            
            // Массив для отслеживания уже нарисованных ячеек
            const drawn = Array(moduleCount).fill().map(() => Array(moduleCount).fill(false));
            
            ctx.fillStyle = '#2A5885';
            
            // Рисуем связанные группы
            for (let row = 0; row < moduleCount; row++) {
                for (let col = 0; col < moduleCount; col++) {
                    if (!qr.isDark(row, col) || drawn[row][col]) continue;
                    
                    if (isFinderPattern(row, col)) {
                        // Для finder patterns рисуем как обычно
                        const x = border + col * cellSize;
                        const y = border + row * cellSize;
                        const radius = cellSize * 0.3;
                        ctx.beginPath();
                        ctx.roundRect(x + cellSize * 0.05, y + cellSize * 0.05, 
                                     cellSize * 0.9, cellSize * 0.9, radius);
                        ctx.fill();
                        drawn[row][col] = true;
                    } else {
                        // Ищем горизонтальные группы
                        let endCol = col;
                        while (endCol < moduleCount && qr.isDark(row, endCol) && !isFinderPattern(row, endCol)) {
                            endCol++;
                        }
                        endCol--;
                        
                        if (endCol > col) {
                            // Рисуем горизонтальную полоску
                            const x = border + col * cellSize;
                            const y = border + row * cellSize;
                            const width = (endCol - col + 1) * cellSize;
                            const height = cellSize;
                            const radius = cellSize * 0.45;
                            
                            ctx.beginPath();
                            ctx.roundRect(x + cellSize * 0.05, y + cellSize * 0.05, 
                                         width - cellSize * 0.1, height - cellSize * 0.1, radius);
                            ctx.fill();
                            
                            // Отмечаем все ячейки как нарисованные
                            for (let c = col; c <= endCol; c++) {
                                drawn[row][c] = true;
                            }
                        } else {
                            // Ищем вертикальные группы
                            let endRow = row;
                            while (endRow < moduleCount && qr.isDark(endRow, col) && !isFinderPattern(endRow, col)) {
                                endRow++;
                            }
                            endRow--;
                            
                            if (endRow > row) {
                                // Рисуем вертикальную полоску
                                const x = border + col * cellSize;
                                const y = border + row * cellSize;
                                const width = cellSize;
                                const height = (endRow - row + 1) * cellSize;
                                const radius = cellSize * 0.45;
                                
                                ctx.beginPath();
                                ctx.roundRect(x + cellSize * 0.05, y + cellSize * 0.05, 
                                             width - cellSize * 0.1, height - cellSize * 0.1, radius);
                                ctx.fill();
                                
                                // Отмечаем все ячейки как нарисованные
                                for (let r = row; r <= endRow; r++) {
                                    drawn[r][col] = true;
                                }
                            } else {
                                // Одиночная ячейка - рисуем как круг
                                const x = border + col * cellSize;
                                const y = border + row * cellSize;
                                const centerX = x + cellSize / 2;
                                const centerY = y + cellSize / 2;
                                const radius = cellSize * 0.42;
                                
                                ctx.beginPath();
                                ctx.arc(centerX, centerY, radius, 0, 2 * Math.PI);
                                ctx.fill();
                                
                                drawn[row][col] = true;
                            }
                        }
                    }
                }
            }
        }

        function copyToClipboard() {
            const urlInput = document.getElementById('pageUrl');
            const copyBtn = document.querySelector('.qr-copy-btn');
            const copyText = copyBtn.querySelector('.copy-text');
            
            urlInput.select();
            urlInput.setSelectionRange(0, 99999);
            
            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    copyText.textContent = 'Скопировано!';
                    copyBtn.classList.add('btn-success');
                    copyBtn.classList.remove('btn-outline-primary');
                    
                    setTimeout(() => {
                        copyText.textContent = 'Копировать';
                        copyBtn.classList.remove('btn-success');
                        copyBtn.classList.add('btn-outline-primary');
                    }, 2000);
                }
            } catch (err) {
                console.error('Ошибка при копировании', err);
            }
        }

        function downloadQRCode() {
            const canvas = document.querySelector('#qrcode canvas');
            const downloadBtn = document.querySelector('.qr-download-btn');
            const downloadText = downloadBtn.querySelector('.download-text');
            
            if (!canvas) {
                console.error('QR код не найден');
                return;
            }
            
            try {
                // Создаем ссылку для скачивания
                const link = document.createElement('a');
                link.download = 'qr-code-<?php echo e(auth()->user()->username); ?>.png';
                link.href = canvas.toDataURL('image/png');
                
                // Симулируем клик по ссылке
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                
                // Показываем обратную связь пользователю
                downloadText.textContent = 'Скачано!';
                downloadBtn.classList.add('btn-success');
                downloadBtn.classList.remove('btn-outline-success');
                
                setTimeout(() => {
                    downloadText.textContent = 'Скачать QR';
                    downloadBtn.classList.remove('btn-success');
                    downloadBtn.classList.add('btn-outline-success');
                }, 2000);
                
            } catch (err) {
                console.error('Ошибка при скачивании QR-кода:', err);
                downloadText.textContent = 'Ошибка!';
                downloadBtn.classList.add('btn-danger');
                downloadBtn.classList.remove('btn-outline-success');
                
                setTimeout(() => {
                    downloadText.textContent = 'Скачать QR';
                    downloadBtn.classList.remove('btn-danger');
                    downloadBtn.classList.add('btn-outline-success');
                }, 2000);
            }
        }
        <?php endif; ?>

        // Функция для поддержания активности сессии
        function keepSessionActive() {
            <?php if(auth()->guard()->check()): ?>
            // Отправляем ping каждые 10 минут для поддержания сессии
            setInterval(function() {
                fetch('/session-ping', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({})
                }).catch(error => {
                    console.log('Session ping failed:', error);
                });
            }, 10 * 60 * 1000); // каждые 10 минут
        <?php endif; ?>
        }

        // Запускаем функцию поддержания сессии
        document.addEventListener('DOMContentLoaded', function() {
            keepSessionActive();
        });
    </script>

    <?php if(auth()->guard()->check()): ?>
    <!-- QR Code Modal -->
    <div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content qr-modal-content">
               
                   
                    <button type="button" class="btn-close qr-close-btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
               
                <div class="modal-body qr-modal-body">
                    <div class="qr-container">
                        <div class="qr-code-wrapper">
                            <div id="qrcode" class="qr-code-area"></div>
                            <div class="qr-avatar-container">
                                <img src="<?php echo e(auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('/hero.png')); ?>" 
                                     alt="Avatar" class="qr-avatar" id="qrAvatar">
                            </div>
                        </div>
                       
                    </div>
                     <div class="qr-info">
                            <h3 class="qr-user-name"><?php echo e(auth()->user()->name); ?></h3>
                         
                            <div class="qr-url">
                                <input type="text" class="form-control qr-url-input" 
                                       value="<?php echo e(route('user.show', ['username' => auth()->user()->username])); ?>" 
                                       readonly id="pageUrl">
                                <div class="qr-buttons">
                                    <button class="btn btn-outline-primary qr-copy-btn" onclick="copyToClipboard()">
                                        <i class="bi bi-copy"></i>
                                        <span class="copy-text">Копировать</span>
                                    </button>
                                    <button class="btn btn-outline-success qr-download-btn" onclick="downloadQRCode()">
                                        <i class="bi bi-download"></i>
                                        <span class="download-text">Скачать QR</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Защита от заблокированного скролла на мобильных устройствах -->
    <script>
        // Проверяем и исправляем скролл при загрузке страницы
        document.addEventListener('DOMContentLoaded', function() {
            // Если на мобильном устройстве и нет активного сайдбара, убираем блокировку скролла
            if (window.innerWidth < 768) {
                const sidebar = document.getElementById('mobileAdminSidebar') || document.getElementById('adminSidebar');
                if (!sidebar || !sidebar.classList.contains('show')) {
                    document.body.style.overflow = '';
                    document.body.classList.remove('mobile-sidebar-open');
                }
            }
        });
        
        // Дополнительная проверка при изменении размера окна
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                document.body.style.overflow = '';
                document.body.classList.remove('mobile-sidebar-open');
            }
        });
    </script>

    <!-- iOS Safari Touch Fixes -->
    <script>
        // Фикс для 100vh на iOS
        function setVh() {
            const vh = window.innerHeight * 0.01;
            document.documentElement.style.setProperty('--vh', `${vh}px`);
        }
        
        // Устанавливаем при загрузке и изменении размера
        setVh();
        window.addEventListener('resize', setVh);
        window.addEventListener('orientationchange', function() {
            setTimeout(setVh, 100); // Небольшая задержка для корректного расчета
        });

        // Предотвращаем горизонтальный скролл на iOS
        let lastTouchX = 0;
        let preventHorizontalScroll = false;

        document.addEventListener('touchstart', function(e) {
            if (e.touches.length === 1) {
                lastTouchX = e.touches[0].clientX;
                preventHorizontalScroll = false;
            }
        }, { passive: true });

        document.addEventListener('touchmove', function(e) {
            if (e.touches.length === 1) {
                const touchX = e.touches[0].clientX;
                const diffX = Math.abs(touchX - lastTouchX);
                const diffY = Math.abs(e.touches[0].clientY - (window.lastTouchY || 0));
                
                // Если горизонтальное движение больше вертикального
                if (diffX > diffY && diffX > 10) {
                    // Проверяем, не находимся ли мы в элементе, который должен скроллиться горизонтально
                    const target = e.target.closest('.swiper, .swiper-container, .horizontal-scroll');
                    if (!target) {
                        e.preventDefault();
                        preventHorizontalScroll = true;
                    }
                }
                
                lastTouchX = touchX;
                window.lastTouchY = e.touches[0].clientY;
            }
        }, { passive: false });

        // Дополнительная защита от случайных горизонтальных свайпов
        document.addEventListener('touchend', function(e) {
            preventHorizontalScroll = false;
        }, { passive: true });

        // Предотвращаем двойное касание для зума (если нужно)
        let lastTouchEnd = 0;
        document.addEventListener('touchend', function(event) {
            const now = (new Date()).getTime();
            if (now - lastTouchEnd <= 300) {
                event.preventDefault();
            }
            lastTouchEnd = now;
        }, false);

        // Фикс для Safari: предотвращаем резиновое поведение при скролле
        document.addEventListener('touchmove', function(e) {
            // Разрешаем скролл только если пользователь не на границе контента
            const target = e.target;
            const scrollableParent = target.closest('.scrollable, body');
            
            if (scrollableParent === document.body) {
                const scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
                const scrollHeight = document.documentElement.scrollHeight || document.body.scrollHeight;
                const clientHeight = document.documentElement.clientHeight || window.innerHeight;
                
                // Предотвращаем overscroll в начале и конце страницы
                if ((scrollTop <= 0 && e.touches[0].clientY > window.lastTouchY) ||
                    (scrollTop + clientHeight >= scrollHeight && e.touches[0].clientY < window.lastTouchY)) {
                    // Не блокируем, так как может помешать нормальному скроллу
                }
            }
        }, { passive: true });
    </script>

    <?php echo $__env->yieldContent('scripts'); ?>
</body>

</html>
<?php /**PATH C:\OSPanel\domains\link\resources\views/layouts/app.blade.php ENDPATH**/ ?>