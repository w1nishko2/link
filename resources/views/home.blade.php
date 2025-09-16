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
        {{-- Динамическое отображение секций согласно настройкам пользователя --}}
        @php
            $orderedSections = $sectionSettings->sortBy('order');
        @endphp
        
        @foreach($orderedSections as $section)
            @if($section->is_visible)
                @if($section->section_key === 'hero')
                    @include('sections.hero', ['section' => $section])
                @elseif($section->section_key === 'services')
                    @include('sections.services', ['section' => $section])
                @elseif($section->section_key === 'gallery')
                    @include('sections.gallery', ['section' => $section])
                @elseif($section->section_key === 'articles')
                    @include('sections.articles', ['section' => $section])
                @elseif($section->section_key === 'banners')
                    @include('sections.banners', ['section' => $section])
                @endif
            @endif
        @endforeach

        {{-- Если у пользователя нет настроек секций, показываем всё по умолчанию --}}
        @if($orderedSections->isEmpty())
            @include('sections.hero')
            @include('sections.services')
            @include('sections.gallery')
            @include('sections.banners')
            @include('sections.articles')
        @endif

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
            </div>
        </div>
    </main>

@endsection
