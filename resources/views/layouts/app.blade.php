<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- SEO Meta Tags -->
    <title>@yield('title', config('app.name', 'Laravel'))</title>
    <meta name="description" content="@yield('description', 'Персональный сайт-визитка. Узнайте больше о моих услугах, статьях и проектах.')">
    <meta name="keywords" content="@yield('keywords', 'персональный сайт, визитка, услуги, портфолио, контакты')">
    <meta name="author" content="@yield('author', config('app.name'))">
    <meta name="robots" content="@yield('robots', 'index, follow')">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:title" content="@yield('og_title', config('app.name'))">
    <meta property="og:description" content="@yield('og_description', 'Персональный сайт-визитка')">
    <meta property="og:url" content="@yield('og_url', request()->url())">
    <meta property="og:image" content="@yield('og_image', asset('/hero.png'))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title')">
    <meta name="twitter:description" content="@yield('twitter_description')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('/hero.png'))">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="@yield('canonical_url', request()->url())">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- DNS Prefetch for Performance -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">
    
    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Additional Head Content -->
    @stack('head')
</head>
<body>
    <div id="app">
        <!-- Навигация для всех пользователей -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
            <div class="container">
                <a class="navbar-brand fw-bold" href="{{ route('home') }}">
                    {{ config('app.name', 'Персональные Сайты') }}
                </a>
                
                <!-- Общие ссылки для всех пользователей -->
                <div class="navbar-nav me-auto">
                    <a class="nav-link" href="{{ route('articles.all') }}">
                        <i class="bi bi-collection me-1"></i>Мир линка
                    </a>
                </div>
                
                @auth
                <!-- Меню для авторизованных пользователей -->
                <div class="navbar-nav ms-auto">
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="{{ route('user.page', ['username' => auth()->user()->username]) }}">Моя страница</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.dashboard', auth()->user()->id) }}">Админка</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Выйти
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
                @else
                <!-- Меню для неавторизованных пользователей -->
                <div class="navbar-nav ms-auto">
                    <a class="nav-link" href="{{ route('login') }}">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Вход
                    </a>
                    <a class="nav-link" href="{{ route('register') }}">
                        <i class="bi bi-person-plus me-1"></i>Регистрация
                    </a>
                </div>
                @endauth
            </div>
        </nav>
        
        <main class="">
            @yield('content')
        </main>
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
                                        <img id="bannerAuthorAvatar" src="" alt="Автор" class="rounded-circle">
                                    </div>
                                    <div class="banner-author-info">
                                        <h5 id="bannerAuthorName" class="mb-0">{{ $pageUser->name ?? 'Автор' }}</h5>
                                        <small id="bannerPostDate" class="text-muted">Сегодня</small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Основной контент -->
                            <div class="banner-post-body">
                                <h2 id="bannerModalTitle" class="banner-post-title"></h2>
                                <div id="bannerModalDescription" class="banner-post-description"></div>
                            </div>
                            
                            <!-- Действия поста -->
                            <div class="banner-post-actions">
                                <!-- Социальные сети -->
                                <div class="banner-social-links">
                                    <h6 class="mb-2">Поделиться в соцсетях:</h6>
                                    <div class="social-buttons">
                                        <a href="#" class="social-btn telegram-btn" id="shareTelegram" target="_blank">
                                            <i class="bi bi-telegram"></i>
                                        </a>
                                        <a href="#" class="social-btn whatsapp-btn" id="shareWhatsApp" target="_blank">
                                            <i class="bi bi-whatsapp"></i>
                                        </a>
                                        <a href="#" class="social-btn vk-btn" id="shareVK" target="_blank">
                                            <i class="bi bi-share"></i>
                                        </a>
                                        <a href="#" class="social-btn ok-btn" id="shareOK" target="_blank">
                                            <i class="bi bi-globe"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM загружен, инициализация...');
    
    const bannersSwiper = new Swiper('.banners-swiper', {
        slidesPerView: 2.4,
        spaceBetween: 20,
        loop: true,
        // autoplay: {
        //     delay: 5000,
        //     disableOnInteraction: false,
        // },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            320: {
                slidesPerView: 1.1,
                spaceBetween: 10,
            },
            768: {
                slidesPerView: 2.1,
                spaceBetween: 15,
            },
            1024: {
                slidesPerView: 2.1,
                spaceBetween: 20,
            }
        }
    });

    // Инициализация слайдера услуг
    const servicesSwiper = new Swiper('.services-swiper', {
        slidesPerView: 2.4,
        spaceBetween: 20,
        loop: true,
        // autoplay: {
        //     delay: 5000,
        //     disableOnInteraction: false,
        // },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            320: {
                slidesPerView: 1.3,
                spaceBetween: 15,
            },
            480: {
                slidesPerView:2.5,
                spaceBetween: 15,
            },
            700: {
                slidesPerView: 2.5,
                spaceBetween: 20,
            },
            768: {  
                slidesPerView:2.5,
                spaceBetween: 20,
            },
            1024: {
                slidesPerView: 2.8,
                spaceBetween: 25,
            },
            1200: {
                slidesPerView: 3.2,
                spaceBetween: 30,
            }
        }
    });

    console.log('Swiper инициализирован');

    initializeBannerModal();
    
   
    initializeSocialWidget();
    
    // Инициализация галереи
    initializeGallery();
    
    console.log('Все модули инициализированы');
});

// Функция инициализации галереи
function initializeGallery() {
    const galleryModal = document.getElementById('galleryModal');
    const modalImage = document.getElementById('modalImage');
    const modalTitle = document.getElementById('galleryModalLabel');
    
    if (galleryModal && modalImage) {
        galleryModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const imageSrc = button.getAttribute('data-image');
            const imageAlt = button.getAttribute('data-alt');
            
            modalImage.src = imageSrc;
            modalImage.alt = imageAlt;
            modalTitle.textContent = imageAlt || 'Просмотр изображения';
        });
    }
    
    // Инициализация навигации галереи
    const galleryGrid = document.getElementById('galleryGrid');
    const prevButton = document.getElementById('galleryPrev');
    const nextButton = document.getElementById('galleryNext');
    
    if (galleryGrid && prevButton && nextButton) {
        const scrollAmount = 320; // Размер прокрутки
        
        prevButton.addEventListener('click', function() {
            galleryGrid.scrollBy({
                left: -scrollAmount,
                behavior: 'smooth'
            });
        });
        
        nextButton.addEventListener('click', function() {
            galleryGrid.scrollBy({
                left: scrollAmount,
                behavior: 'smooth'
            });
        });
        
        // Обновление состояния кнопок при прокрутке
        function updateNavButtons() {
            const scrollLeft = galleryGrid.scrollLeft;
            const scrollWidth = galleryGrid.scrollWidth;
            const clientWidth = galleryGrid.clientWidth;
            
            prevButton.disabled = scrollLeft <= 0;
            nextButton.disabled = scrollLeft >= scrollWidth - clientWidth;
        }
        
        galleryGrid.addEventListener('scroll', updateNavButtons);
        updateNavButtons(); // Инициализация состояния кнопок
        
        // Обработка свайпов на мобильных устройствах
        let startX = 0;
        let scrollLeftStart = 0;
        
        galleryGrid.addEventListener('touchstart', function(e) {
            startX = e.touches[0].pageX;
            scrollLeftStart = galleryGrid.scrollLeft;
        });
        
        galleryGrid.addEventListener('touchmove', function(e) {
            e.preventDefault();
            const x = e.touches[0].pageX;
            const walk = (startX - x) * 2;
            galleryGrid.scrollLeft = scrollLeftStart + walk;
        });
    }
}

// Инициализация модального окна баннеров
function initializeBannerModal() {
    console.log('Banner modal initialized');
    
    // Добавляем обработчики кликов для всех баннеров
    document.querySelectorAll('.banners-banner').forEach(banner => {
        banner.style.cursor = 'pointer';
        
        banner.addEventListener('click', function() {
            const title = this.querySelector('h3') ? this.querySelector('h3').textContent : 'Заголовок';
            const description = this.querySelector('p') ? this.querySelector('p').textContent : '';
            const imageElement = this.querySelector('img');
            const imageSrc = imageElement ? imageElement.src : '';
            const imageAlt = imageElement ? imageElement.alt : title;
            
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
            
            // Устанавливаем информацию об авторе
            const authorAvatar = document.getElementById('bannerAuthorAvatar');
            const authorName = document.getElementById('bannerAuthorName');
            const postDate = document.getElementById('bannerPostDate');
            
            @if(isset($pageUser))
            if (authorAvatar) {
                authorAvatar.src = "{{ $pageUser->avatar ? asset('storage/' . $pageUser->avatar) : asset('/hero.png') }}";
            }
            if (authorName) {
                authorName.textContent = "{{ $pageUser->name }}";
            }
            @endif
            
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
                shareTelegram.href = `https://t.me/share/url?url=${encodeURIComponent(currentUrl)}&text=${shareText}`;
            }
            if (shareWhatsApp) {
                shareWhatsApp.href = `https://wa.me/?text=${shareText} ${encodeURIComponent(currentUrl)}`;
            }
            if (shareVK) {
                shareVK.href = `https://vk.com/share.php?url=${encodeURIComponent(currentUrl)}&title=${shareText}`;
            }
            if (shareOK) {
                shareOK.href = `https://connect.ok.ru/dk?st.cmd=WidgetSharePreview&st.shareUrl=${encodeURIComponent(currentUrl)}`;
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

// Функция для поддержания активности сессии
function keepSessionActive() {
    @auth
    // Отправляем ping каждые 10 минут для поддержания сессии
    setInterval(function() {
        fetch('/session-ping', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({})
        }).catch(error => {
            console.log('Session ping failed:', error);
        });
    }, 10 * 60 * 1000); // каждые 10 минут
    @endauth
}

// Запускаем функцию поддержания сессии
document.addEventListener('DOMContentLoaded', function() {
    keepSessionActive();
});
</script>
</body>
</html>
