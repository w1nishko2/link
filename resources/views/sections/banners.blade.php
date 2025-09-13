{{-- Секция Баннеры --}}
@if($banners->count() > 0 || (isset($section) && ($section->title || $section->subtitle)))
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
                @forelse($banners as $banner)
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
                                    <a href="{{ route('admin.banners', $currentUser->id) }}" class="btn btn-primary">Добавить баннер</a>
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