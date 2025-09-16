{{-- Секция Баннеры --}}
@if($banners->count() > 0 || (isset($section) && ($section->title || $section->subtitle)) || ($currentUser && $currentUser->id === $pageUser->id))
<section class="banners" aria-label="Рекламные блоки">
    <div class="container">
        @if(isset($section) && (!empty(trim($section->title)) || !empty(trim($section->subtitle))))
            <header class="banners-header mb-4 ">
                @if(!empty(trim($section->title)))
                    <h2>{{ $section->title }}</h2>
                @else
                    <h2 class="visually-hidden">Реклама и предложения</h2>
                @endif
                @if(!empty(trim($section->subtitle)))
                    <p class="text-muted">{{ $section->subtitle }}</p>
                @endif
            </header>
        @else
            <h2 class="visually-hidden">Реклама и предложения</h2>
        @endif
        
        <div class="swiper banners-swiper">
            <div class="swiper-wrapper">
                @foreach($banners as $banner)
                    <div class="swiper-slide">
                        <div class="banners-banner" data-analytics="banner" data-analytics-id="{{ $banner->id }}"
                            data-analytics-text="{{ $banner->title }}" data-link-url="{{ $banner->link_url }}" data-link-text="{{ $banner->link_text }}">
                            <div class="banners-banner-block">
                                <h3>{{ $banner->title }}</h3>
                                @if ($banner->description)
                                    <p>{{ $banner->description }}</p>
                                @endif
                            </div>
                            <div class="banners-banner-block-img">
                                @if ($banner->image_path)
                                    <img src="{{ asset('storage/' . $banner->image_path) }}"
                                         alt="{{ $banner->title }}"
                                         loading="lazy"
                                         width="300"
                                         height="200"
                                         decoding="async">
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Дефолтный блок для создания баннера (только для владельца) --}}
                @if ($currentUser && $currentUser->id === $pageUser->id)
                    <div class="swiper-slide">
                        <a href="{{ route('admin.banners', $currentUser->id) }}" class="owner-default-block banner-add">
                            <div class="owner-default-icon"></div>
                            <div class="owner-default-text">
                                <div class="owner-default-title">Добавить баннер</div>
                                <div class="owner-default-subtitle">Разместите рекламу или объявления</div>
                            </div>
                        </a>
                    </div>
                @endif

                {{-- Показываем приветственный блок только если нет баннеров и пользователь не владелец --}}
                @if($banners->count() === 0 && (!$currentUser || $currentUser->id !== $pageUser->id))
                    <div class="swiper-slide">
                        <div class="banners-banner">
                            <div class="banners-banner-block">
                                <h3>Добро пожаловать!</h3>
                                <p>Здесь будут размещены баннеры</p>
                            </div>
                            <div class="banners-banner-block-img">
                                <img src="/hero.png" 
                                     alt="Добро пожаловать"
                                     loading="lazy"
                                     width="300"
                                     height="200"
                                     decoding="async">
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endif