{{-- Секция Услуги --}}
<section class="services" id="services" aria-label="Услуги">
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
        
        <div class="swiper services-swiper">
            <div class="swiper-wrapper">
                {{-- Дефолтный блок для создания услуги (только для владельца) --}}
                @if ($currentUser && $currentUser->id === $pageUser->id)
                    <div class="swiper-slide">
                        <a href="{{ route('admin.services.create', $currentUser->id) }}" class="owner-default-block service-add">
                            <div class="owner-default-icon"></div>
                            <div class="owner-default-text">
                                <div class="owner-default-title">Добавить услугу</div>
                                <div class="owner-default-subtitle">Расскажите о своих услугах</div>
                            </div>
                        </a>
                    </div>
                @endif
               
                @foreach($services as $service)
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
                                         alt="{{ $service->title }}" 
                                         loading="lazy"
                                         width="300"
                                         height="600"
                                         decoding="async">
                                @else
                                    <img src="/hero.png" 
                                         alt="{{ $service->title }}" 
                                         loading="lazy"
                                         width="300"
                                         height="600"
                                         decoding="async">
                                @endif
                            </div>
                            <div class="service-content">
                                <h3>{{ $service->title }}</h3>
                                <p>{{ $service->description }}</p>
                                <div class="service-bottom">
                                    @if ($service->price)
                                        <div class="service-price">{{ $service->formatted_price }}</div>
                                    @endif
                                    @if ($service->button_text)
                                        @if ($service->button_link)
                                            <a href="{{ $service->button_link }}" 
                                               class="service-button btn btn-primary btn-sm"
                                               target="{{ str_starts_with($service->button_link, 'http') ? '_blank' : '_self' }}"
                                               rel="{{ str_starts_with($service->button_link, 'http') ? 'noopener noreferrer' : '' }}">
                                                {{ $service->button_text }}
                                            </a>
                                        @else
                                            <button class="service-button btn btn-primary btn-sm" type="button">
                                                {{ $service->button_text }}
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Показываем информативную пустышку только если нет услуг и пользователь не владелец --}}
                @if($services->count() === 0 && (!$currentUser || $currentUser->id !== $pageUser->id))
                    <div class="swiper-slide">
                        <div class="service-card placeholder-content">
                            <div class="service-image">
                                <img src="/hero.png" 
                                     alt="Скоро здесь будут услуги" 
                                     loading="lazy"
                                     width="300"
                                     height="600"
                                     decoding="async">
                            </div>
                            <div class="service-content">
                                <h3>Услуги скоро появятся</h3>
                                <p>{{ $pageUser->name }} готовит описание своих услуг. Возвращайтесь позже, чтобы узнать о доступных предложениях!</p>
                                <div class="service-bottom">
                                    <div class="service-price">Готовим прайс...</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>


        </div>
    </div>
</section>