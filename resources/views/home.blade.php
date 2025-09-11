@extends('layouts.app')

@section('title', $pageUser->name . ' - ' . $pageUser->username . ' | Персональная страница')
@section('description', $pageUser->bio ? Str::limit(strip_tags($pageUser->bio), 160) : 'Персональная страница ' . $pageUser->name . '. Услуги, статьи, портфолио и контакты.')
@section('keywords', 'персональная страница, ' . strtolower($pageUser->name) . ', услуги, портфолио, контакты, ' . strtolower($pageUser->username))
@section('author', $pageUser->name)

@section('og_type', 'profile')
@section('og_title', $pageUser->name . ' - Персональная страница')
@section('og_description', $pageUser->bio ? Str::limit(strip_tags($pageUser->bio), 200) : 'Персональная страница ' . $pageUser->name . '. Узнайте больше о моих услугах и проектах.')
@section('og_url', request()->url())
@section('og_image', $pageUser->avatar ? asset('storage/' . $pageUser->avatar) : ($pageUser->background_image ? asset('storage/' . $pageUser->background_image) : asset('/hero.png')))

@section('twitter_title', $pageUser->name . ' - ' . $pageUser->username)
@section('twitter_description', $pageUser->bio ? Str::limit(strip_tags($pageUser->bio), 160) : 'Персональная страница с услугами и портфолио')
@section('twitter_image', $pageUser->avatar ? asset('storage/' . $pageUser->avatar) : asset('/hero.png'))

@section('canonical_url', route('user.page', $pageUser->username))

@push('head')
<!-- Structured Data (JSON-LD) -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Person",
    "name": "{{ $pageUser->name }}",
    "alternateName": "{{ $pageUser->username }}",
    "description": "{{ $pageUser->bio ? strip_tags($pageUser->bio) : 'Персональная страница специалиста' }}",
    "url": "{{ route('user.page', $pageUser->username) }}",
    @if($pageUser->avatar)
    "image": "{{ asset('storage/' . $pageUser->avatar) }}",
    @endif
    "sameAs": [
        @if($pageUser->telegram_url)"{{ $pageUser->telegram_url }}"@endif
        @if($pageUser->whatsapp_url && $pageUser->telegram_url), @endif
        @if($pageUser->whatsapp_url)"{{ $pageUser->whatsapp_url }}"@endif
        @if($pageUser->vk_url && ($pageUser->telegram_url || $pageUser->whatsapp_url)), @endif
        @if($pageUser->vk_url)"{{ $pageUser->vk_url }}"@endif
        @if($pageUser->youtube_url && ($pageUser->telegram_url || $pageUser->whatsapp_url || $pageUser->vk_url)), @endif
        @if($pageUser->youtube_url)"{{ $pageUser->youtube_url }}"@endif
        @if($pageUser->ok_url && ($pageUser->telegram_url || $pageUser->whatsapp_url || $pageUser->vk_url || $pageUser->youtube_url)), @endif
        @if($pageUser->ok_url)"{{ $pageUser->ok_url }}"@endif
    ]
}
</script>

@if($services->count() > 0)
<!-- Services Structured Data -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Service",
    "serviceType": "Professional Services",
    "provider": {
        "@type": "Person",
        "name": "{{ $pageUser->name }}",
        "url": "{{ route('user.page', $pageUser->username) }}"
    },
    "areaServed": "Online",
    "availableChannel": {
        "@type": "ServiceChannel",
        "serviceUrl": "{{ route('user.page', $pageUser->username) }}"
    }
}
</script>
@endif

@if(count($galleryBlocks) > 0)
<!-- Gallery/Portfolio Structured Data -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ImageGallery",
    "name": "Портфолио {{ $pageUser->name }}",
    "description": "Галерея работ и проектов",
    "author": {
        "@type": "Person",
        "name": "{{ $pageUser->name }}",
        "url": "{{ route('user.page', $pageUser->username) }}"
    },
    "url": "{{ route('user.page', $pageUser->username) }}#gallery"
}
</script>
@endif

@if($articles->count() > 0)
<!-- Blog/Articles Structured Data -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Blog",
    "name": "Блог {{ $pageUser->name }}",
    "description": "Статьи и полезные материалы",
    "author": {
        "@type": "Person",
        "name": "{{ $pageUser->name }}",
        "url": "{{ route('user.page', $pageUser->username) }}"
    },
    "url": "{{ route('user.page', $pageUser->username) }}#articles",
    "blogPost": [
        @foreach($articles as $index => $article)
        {
            "@type": "BlogPosting",
            "headline": "{{ $article->title }}",
            "description": "{{ $article->excerpt }}",
            "url": "{{ route('articles.show', ['username' => $pageUser->username, 'slug' => $article->slug]) }}",
            "datePublished": "{{ $article->created_at->toISOString() }}",
            "author": {
                "@type": "Person",
                "name": "{{ $pageUser->name }}"
            }
        }@if($index < $articles->count() - 1),@endif
        @endforeach
    ]
}
</script>
@endif
@endpush

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <main role="main">
        <section class="hero"
            style="background-image: url('{{ $pageUser->background_image ? asset('storage/' . $pageUser->background_image) : '/hero.png' }}');"
            aria-label="Главная информация о {{ $pageUser->name }}">
        <div class="container">
            <div class="hero-section">
                <div class="hero-info">
                    <h1>{{ $pageUser->name }}</h1>
                    <p>@ {{ $pageUser->username }}</p>
                    @if ($pageUser->bio)
                        <p>{{ $pageUser->bio }}</p>
                    @else
                        <p>Добро пожаловать на мою страницу!</p>
                    @endif
                    <ul class="hero-links">
                        @if ($pageUser->telegram_url)
                            <a href="{{ $pageUser->telegram_url }}" target="_blank" class="social-link telegram"
                                title="Telegram">
                                <i class="bi bi-telegram"></i>
                            </a>
                        @endif

                        @if ($pageUser->whatsapp_url)
                            <a href="{{ $pageUser->whatsapp_url }}" target="_blank" class="social-link whatsapp"
                                title="WhatsApp">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                        @endif

                        @if ($pageUser->vk_url)
                            <a href="{{ $pageUser->vk_url }}" target="_blank" class="social-link vk" title="ВКонтакте">
                                <i class="bi bi-chat-square-text"></i>
                            </a>
                        @endif

                        @if ($pageUser->youtube_url)
                            <a href="{{ $pageUser->youtube_url }}" target="_blank" class="social-link youtube"
                                title="YouTube">
                                <i class="bi bi-youtube"></i>
                            </a>
                        @endif

                        @if ($pageUser->ok_url)
                            <a href="{{ $pageUser->ok_url }}" target="_blank" class="social-link ok" title="Одноклассники">
                                <i class="bi bi-people-fill"></i>
                            </a>
                        @endif
                    </ul>
                </div>
                <div class="hero-logo">
                    @if($pageUser->avatar)
                        <img src="{{ asset('storage/' . $pageUser->avatar) }}" alt="Фотография {{ $pageUser->name }}" loading="lazy">
                    @else
                        <img src="/hero.png" alt="Изображение профиля" loading="lazy">
                    @endif
                </div>

            </div>
        </div>
    </section>

   <section class="services" aria-label="Услуги">
        <div class="container">
        
            <div class="swiper services-swiper" dir="rtl">
                <div class="swiper-wrapper">
                      <div class="swiper-slide">
                            <div class="service-card">
                                <div class="service-image">
                                        <img src="{{ $pageUser->background_image ? asset('storage/' . $pageUser->background_image) : '/hero.png' }}" alt="Услуги {{ $pageUser->name }}" loading="lazy">
                                </div>
                                <div class="service-content">
                                    <h3>Мои услуги</h3>
                                    <p>Листай в право чтобы увидеть все мои услуги!</p>
                                   
                                </div>
                            </div>
                        </div>
                    @forelse($services as $service)
                        <div class="swiper-slide">
                            <div class="service-card">
                                <div class="service-image">
                                    @if ($service->image_path)
                                        <img src="{{ asset('storage/' . $service->image_path) }}"
                                            alt="{{ $service->title }}" loading="lazy">
                                    @else
                                        <img src="/hero.png" alt="{{ $service->title }}" loading="lazy">
                                    @endif
                                </div>
                                <div class="service-content">
                                    <h3>{{ $service->title }}</h3>
                                    <p>{{ $service->description }}</p>
                                    @if ($service->price)
                                        <div class="service-price">{{ $service->formatted_price }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="swiper-slide">
                            <div class="service-card text-center">
                                <h3>Услуги не найдены</h3>
                                <p>Здесь будут отображены ваши услуги</p>
                                @if ($currentUser && $currentUser->id === $pageUser->id)
                                    <a href="{{ route('admin.services.create', $currentUser->id) }}" class="btn btn-primary">Добавить
                                        услугу</a>
                                @endif
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Навигационные кнопки для слайдера услуг -->
                <div class="swiper-button-next services-button-next"></div>
                <div class="swiper-button-prev services-button-prev"></div>
            </div>
        </div>
    </section>
    
    <section class="gallery" aria-label="Галерея работ">
        <div class="container">
            <header class="gallery-header mb-4">
                <h2>{{ $currentUser && $currentUser->id === $pageUser->id ? 'Моя галерея' : 'Галерея работ ' . $pageUser->name }}</h2>
                <p class="text-muted">Портфолио и примеры работ</p>
            </header>
         
            <div class="gallery-wrapper">
                <div class="gallery-grid" id="galleryGrid" role="region" aria-label="Галерея изображений">
                    @forelse($galleryBlocks as $index => $block)
                        <div class="gallery-block type-{{ $block['type'] }}">
                            @foreach ($block['images'] as $image)
                                <figure class="gallery-item" data-bs-toggle="modal" data-bs-target="#galleryModal"
                                    data-image="{{ $image['src'] }}" data-alt="{{ $image['alt'] }}">
                                    <img src="{{ $image['src'] }}" alt="{{ $image['alt'] ?: 'Работа из портфолио ' . $pageUser->name }}" loading="lazy" itemscope itemtype="https://schema.org/ImageObject">
                                    <div class="gallery-item-overlay">
                                        <figcaption class="gallery-item-text">{{ $image['alt'] ?: 'Портфолио' }}</figcaption>
                                    </div>
                                </figure>
                            @endforeach
                        </div>
                    @empty
                        <div class="text-center">
                            <p class="text-muted">Галерея пуста</p>
                        </div>
                    @endforelse
                </div>

                <!-- Навигационные кнопки -->
                <button class="gallery-nav gallery-nav-left" id="galleryPrev" type="button">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>
                <button class="gallery-nav gallery-nav-right" id="galleryNext" type="button">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Модальное окно для просмотра изображений -->
        <div class="modal fade" id="galleryModal" tabindex="-1" aria-labelledby="galleryModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="galleryModalLabel">Просмотр изображения</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img id="modalImage" src="" alt="" class="img-fluid rounded">
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($banners->count() > 0)
    <section class="banners" aria-label="Рекламные блоки">
        <div class="container">
            <h2 class="visually-hidden">Реклама и предложения</h2>
            <div class="swiper banners-swiper">
                <div class="swiper-wrapper">
                    @forelse($banners as $banner)
                        <div class="swiper-slide">
                            <div class="banners-banner" data-analytics="banner" data-analytics-id="{{ $banner->id }}"
                                data-analytics-text="{{ $banner->title }}">
                                <div class="banners-banner-block">
                                    <h3>{{ $banner->title }}</h3>
                                    @if ($banner->description)
                                        <p>{{ $banner->description }}</p>
                                    @endif
                                    {{-- @if ($banner->link_url && $banner->link_text)
                                        <a href="{{ $banner->link_url }}" class="btn btn-primary">{{ $banner->link_text }}</a>
                                    @endif --}}
                                </div>
                                <div class="banners-banner-block-img">
                                    @if ($banner->image_path)
                                        <img src="{{ asset('storage/' . $banner->image_path) }}"
                                            alt="{{ $banner->title }}">
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="swiper-slide">
                            <div class="banners-banner">
                                <div class="banners-banner-block">
                                    <h3>Добро пожаловать!</h3>
                                    <p>Здесь будут размещены ваши баннеры</p>
                                    @if ($currentUser && $currentUser->id === $pageUser->id)
                                        <a href="{{ route('admin.banners', $currentUser->id) }}" class="btn btn-primary">Добавить
                                            баннер</a>
                                    @endif
                                </div>
                                <div class="banners-banner-block-img">
                                    <img src="/hero.png" alt="Добро пожаловать">
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
    @endif
    
    <section class="articles" aria-label="Статьи блога">
        <div class="container">
            <header class="articles-header">
                <h2>{{ $currentUser && $currentUser->id === $pageUser->id ? 'Мои статьи' : 'Статьи от ' . $pageUser->name }}
                </h2>
                <p class="text-muted">Полезные материалы и советы</p>
            </header>


            <div class="articles-list">
                @forelse($articles as $article)
                    <article class="article-preview" itemscope itemtype="https://schema.org/Article">
                        <a href="{{ route('articles.show', ['username' => $pageUser->username, 'slug' => $article->slug]) }}"
                            class="article-item">
                            <div class="article-image">
                                @if ($article->image_path)
                                    <img src="{{ asset('storage/' . $article->image_path) }}" alt="{{ $article->title }}"
                                        loading="lazy" itemprop="image">
                                @else
                                    <img src="/hero.png" alt="{{ $article->title }}" loading="lazy" itemprop="image">
                                @endif
                                <time class="article-date" datetime="{{ $article->created_at->toISOString() }}" itemprop="datePublished">
                                    <span>{{ $article->created_at->format('d') }}</span>
                                    <span>{{ $article->created_at->format('M') }}</span>
                                </time>
                            </div>
                            <div class="article-content">
                                
                                <h3 class="article-title" itemprop="headline">
                                    {{ $article->title }}
                                </h3>
                                <p class="article-excerpt" itemprop="description">
                                    {{ $article->excerpt }}
                                </p>
                                <div class="article-meta">
                                    <span class="article-author" itemprop="author" itemscope itemtype="https://schema.org/Person">
                                        <span itemprop="name">Автор: {{ $pageUser->name }}</span>
                                    </span>
                                    <span class="article-read-time">{{ $article->read_time }} мин чтения</span>
                                </div>
                            </div>
                        </a>
                    </article>
                @empty
                    <div class="text-center py-5">
                        <h4>Статьи не найдены</h4>
                        <p class="text-muted">Здесь будут отображаться ваши статьи</p>
                        @if ($currentUser && $currentUser->id === $pageUser->id)
                            <a href="{{ route('admin.articles.create', $currentUser->id) }}" class="btn btn-primary">Создать статью</a>
                        @endif
                    </div>
                @endforelse
            </div>

            @if($articles->count() > 0)
            <div class="articles-footer text-center mt-5">
                <a href="{{ route('articles.index', ['username' => $pageUser->username]) }}" class="btn btn-outline-primary">Все статьи</a>
            </div>
            @endif
        </div>
    </section>

    <!-- Фиксированная кнопка социальных сетей -->
    <div class="social-floating-button">
        <button class="social-main-btn" id="socialMainBtn">
            <i class="bi bi-share-fill"></i>
        </button>

        <div class="social-links" id="socialLinks">
            @if ($pageUser->telegram_url)
                <a href="{{ $pageUser->telegram_url }}" target="_blank" class="social-link telegram" title="Telegram">
                    <i class="bi bi-telegram"></i>
                </a>
            @endif

            @if ($pageUser->whatsapp_url)
                <a href="{{ $pageUser->whatsapp_url }}" target="_blank" class="social-link whatsapp" title="WhatsApp">
                    <i class="bi bi-whatsapp"></i>
                </a>
            @endif

            @if ($pageUser->vk_url)
                <a href="{{ $pageUser->vk_url }}" target="_blank" class="social-link vk" title="ВКонтакте">
                    <i class="bi bi-chat-square-text"></i>
                </a>
            @endif

            @if ($pageUser->youtube_url)
                <a href="{{ $pageUser->youtube_url }}" target="_blank" class="social-link youtube" title="YouTube">
                    <i class="bi bi-youtube"></i>
                </a>
            @endif

            @if ($pageUser->ok_url)
                <a href="{{ $pageUser->ok_url }}" target="_blank" class="social-link ok" title="Одноклассники">
                    <i class="bi bi-people-fill"></i>
                </a>
            @endif
        </div>
    </div>
    </main>



@endsection
