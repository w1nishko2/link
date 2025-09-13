{{-- Секция Hero --}}
<section class="hero"
    style="background-image: url('{{ $pageUser->background_image ? asset('storage/' . $pageUser->background_image) : '/hero.png' }}');"
    aria-label="Главная информация о {{ $pageUser->name }}">
    <div class="container">
        <div class="hero-section">
            <div class="hero-info">
                @if(isset($section) && !empty(trim($section->title)))
                    <h1>{{ $section->title }}</h1>
                @else
                    <h1>{{ $pageUser->name }}</h1>
                @endif
                
                <p>@ {{ $pageUser->username }}</p>
                
                @if(isset($section) && !empty(trim($section->subtitle)))
                    <p>{{ $section->subtitle }}</p>
                @elseif ($pageUser->bio)
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

                    {{-- Дополнительные социальные ссылки --}}
                    @if($socialLinks && $socialLinks->count() > 0)
                        @foreach($socialLinks as $link)
                            @php
                                $serviceClass = '';
                                $serviceName = strtolower($link->service_name);
                                if (str_contains($serviceName, 'instagram')) $serviceClass = 'instagram';
                                elseif (str_contains($serviceName, 'github')) $serviceClass = 'github';
                                elseif (str_contains($serviceName, 'linkedin')) $serviceClass = 'linkedin';
                                elseif (str_contains($serviceName, 'facebook')) $serviceClass = 'facebook';
                                elseif (str_contains($serviceName, 'twitter')) $serviceClass = 'twitter';
                                elseif (str_contains($serviceName, 'discord')) $serviceClass = 'discord';
                                elseif (str_contains($serviceName, 'tiktok')) $serviceClass = 'tiktok';
                                elseif (str_contains($serviceName, 'pinterest')) $serviceClass = 'pinterest';
                                elseif (str_contains($serviceName, 'email') || str_contains($serviceName, 'mail')) $serviceClass = 'email';
                                elseif (str_contains($serviceName, 'портфолио') || str_contains($serviceName, 'portfolio')) $serviceClass = 'portfolio';
                                elseif (str_contains($serviceName, 'сайт') || str_contains($serviceName, 'website') || str_contains($serviceName, 'ссылка')) $serviceClass = 'website';
                            @endphp
                            <a href="{{ $link->url }}" target="_blank" class="social-link custom {{ $serviceClass }}" title="{{ $link->service_name }}">
                                <i class="bi {{ $link->icon_class }}"></i>
                            </a>
                        @endforeach
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