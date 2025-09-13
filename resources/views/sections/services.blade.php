{{-- Секция Услуги --}}
<section class="services" aria-label="Услуги">
    <div class="container">
        @if(isset($section) && (!empty(trim($section->title)) || !empty(trim($section->subtitle))))
            <header class="services-header mb-4 ">
                @if(!empty(trim($section->title)))
                    <h2>{{ $section->title }}</h2>
                @endif
                @if(!empty(trim($section->subtitle)))
                    <p class="text-muted">{{ $section->subtitle }}</p>
                @endif
            </header>
        @endif
        
        <div class="swiper services-swiper" dir="rtl">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="service-card">
                        <div class="service-image">
                            <img src="{{ $pageUser->background_image ? asset('storage/' . $pageUser->background_image) : '/hero.png' }}" alt="Услуги {{ $pageUser->name }}" loading="lazy">
                        </div>
                        <div class="service-content">
                            <h3>
                                @if(isset($section) && $section->title)
                                    {{ $section->title }}
                                @else
                                    Мои услуги
                                @endif
                            </h3>
                            <p>Листай в право чтобы увидеть все мои услуги!</p>
                        </div>
                    </div>
                </div>
                @forelse($services as $service)
                    <div class="swiper-slide">
                        <div class="service-card" data-analytics="service" data-analytics-id="{{ $service->id }}"
                            data-analytics-text="{{ $service->title }}" data-service-title="{{ $service->title }}"
                            data-service-description="{{ $service->description }}" data-service-price="{{ $service->formatted_price ?? '' }}"
                            data-service-image="{{ $service->image_path ? asset('storage/' . $service->image_path) : '/hero.png' }}"
                            data-service-button-text="{{ $service->button_text ?? '' }}"
                            data-service-button-link="{{ $service->button_link ?? '' }}"
                            style="cursor: pointer;">
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
                                <a href="{{ route('admin.services.create', $currentUser->id) }}" class="btn btn-primary">Добавить услугу</a>
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