@extends('layouts.app')

@section('title', 'Статьи ' . $user->name . ' | ' . config('app.name'))
@section('description', 'Все статьи и публикации от ' . $user->name . '. Полезные материалы, советы и экспертные мнения от ' . $user->username . '.')
@section('keywords', 'статьи, блог, ' . strtolower($user->name) . ', публикации, материалы, ' . strtolower($user->username) . ', авторские статьи')
@section('author', $user->name)

@section('og_type', 'website')
@section('og_title', 'Статьи от ' . $user->name)
@section('og_description', 'Все статьи и публикации от ' . $user->name . '. Полезные материалы и экспертные советы.')
@section('og_url', request()->url())
@section('og_image', $user->avatar ? asset('storage/' . $user->avatar) : ($user->background_image ? asset('storage/' . $user->background_image) : asset('/hero.png')))

@section('twitter_title', 'Статьи от ' . $user->name)
@section('twitter_description', 'Все статьи и публикации от ' . $user->name . '. Полезные материалы и советы.')
@section('twitter_image', $user->avatar ? asset('storage/' . $user->avatar) : asset('/hero.png'))

@section('canonical_url', route('articles.index', $user->username))

@push('head')
<!-- Structured Data (JSON-LD) -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Blog",
    "name": "Блог {{ $user->name }}",
    "description": "Все статьи и публикации от {{ $user->name }}",
    "author": {
        "@type": "Person",
        "name": "{{ $user->name }}",
        "url": "{{ route('user.show', $user->username) }}"
    },
    "url": "{{ route('articles.index', $user->username) }}",
    @if($articles->count() > 0)
    "blogPost": [
        @foreach($articles as $index => $article)
        {
            "@type": "BlogPosting",
            "headline": "{{ $article->title }}",
            "description": "{{ $article->excerpt }}",
            "url": "{{ route('articles.show', ['username' => $user->username, 'slug' => $article->slug]) }}",
            "datePublished": "{{ $article->created_at->toISOString() }}",
            "author": {
                "@type": "Person",
                "name": "{{ $user->name }}"
            }
        }@if($index < $articles->count() - 1),@endif
        @endforeach
    ]
    @endif
}
</script>
@endpush

@section('content')
<div class="container ">
    <!-- Breadcrumb навигация -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('user.show', $user->username) }}">
                    <i class="bi bi-house"></i> {{ $user->name }}
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Статьи</li>
        </ol>
    </nav>

    <!-- Заголовок страницы -->
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto text-center">
            <h1 class="display-5 fw-bold mb-3">Статьи от {{ $user->name }}</h1>
            <p class="lead text-muted">
                Полезные материалы, советы и экспертные мнения
            </p>
            @if($user->bio)
                <p class="text-muted">{{ $user->bio }}</p>
            @endif
        </div>
    </div>

    @if($articles->count() > 0)
        <!-- Статьи -->
        <div class="row g-4">
            @foreach($articles as $article)
                <div class="col-lg-4 col-md-6">
                    <article class="card h-100 article-card" itemscope itemtype="https://schema.org/Article">
                        <a href="{{ route('articles.show', ['username' => $user->username, 'slug' => $article->slug]) }}" class="text-decoration-none">
                            <!-- Изображение статьи -->
                            <div class="card-img-top position-relative" style="height: 200px; overflow: hidden;">
                                @if ($article->image_path)
                                    <img src="{{ asset('storage/' . $article->image_path) }}" 
                                         alt="{{ $article->title }}"
                                         class="w-100 h-100 object-fit-cover"
                                         loading="lazy" 
                                         itemprop="image">
                                @else
                                    <img src="/hero.png" 
                                         alt="{{ $article->title }}" 
                                         class="w-100 h-100 object-fit-cover"
                                         loading="lazy" 
                                         itemprop="image">
                                @endif
                                
                                <!-- Дата публикации -->
                                <div class="position-absolute top-0 end-0 m-3">
                                    <time class="badge bg-dark bg-opacity-75" 
                                          datetime="{{ $article->created_at->toISOString() }}" 
                                          itemprop="datePublished">
                                        {{ $article->created_at->format('d.m.Y') }}
                                    </time>
                                </div>
                            </div>

                            <!-- Содержимое карточки -->
                            <div class="card-body d-flex flex-column">
                                <h3 class="card-title h5 fw-bold text-dark mb-3" itemprop="headline">
                                    {{ $article->title }}
                                </h3>
                                
                                @if($article->excerpt)
                                    <p class="card-text text-muted flex-grow-1" itemprop="description">
                                        {{ Str::limit($article->excerpt, 120) }}
                                    </p>
                                @endif

                                <!-- Мета-информация -->
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center text-muted small">
                                        <span itemprop="author" itemscope itemtype="https://schema.org/Person">
                                            <i class="bi bi-person"></i>
                                            <span itemprop="name">{{ $user->name }}</span>
                                        </span>
                                        @if($article->read_time)
                                            <span>
                                                <i class="bi bi-clock"></i>
                                                {{ $article->read_time }} мин
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    </article>
                </div>
            @endforeach
        </div>

        <!-- Пагинация -->
        <div class="d-flex justify-content-center mt-5">
            {{ $articles->links('pagination.custom') }}
        </div>

    @else
        <!-- Пустое состояние -->
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="bi bi-file-text display-1 text-muted"></i>
            </div>
            <h3 class="h4 text-muted mb-3">Статьи не найдены</h3>
            <p class="text-muted mb-4">У {{ $user->name }} пока нет опубликованных статей</p>
            <a href="{{ route('user.show', $user->username) }}" class="btn 
                <i class="bi bi-arrow-left"></i> Вернуться на главную
            </a>
        </div>
    @endif

    <!-- Действия -->
    <div class="text-center mt-5">
        <div class="row justify-content-center">
            <div class="col-auto">
                <a href="{{ route('user.show', $user->username) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Назад к профилю
                </a>
            </div>
            @if($user->telegram_url || $user->whatsapp_url || $user->vk_url)
                <div class="col-auto">
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-share"></i> Связаться
                        </button>
                        <ul class="dropdown-menu">
                            @if($user->telegram_url)
                                <li>
                                    <a class="dropdown-item" href="{{ $user->telegram_url }}" target="_blank" rel="noopener">
                                        <i class="bi bi-telegram"></i> Telegram
                                    </a>
                                </li>
                            @endif
                            @if($user->whatsapp_url)
                                <li>
                                    <a class="dropdown-item" href="{{ $user->whatsapp_url }}" target="_blank" rel="noopener">
                                        <i class="bi bi-whatsapp"></i> WhatsApp
                                    </a>
                                </li>
                            @endif
                            @if($user->vk_url)
                                <li>
                                    <a class="dropdown-item" href="{{ $user->vk_url }}" target="_blank" rel="noopener">
                                        <i class="bi bi-person-vcard"></i> VK
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.article-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    border: 1px solid rgba(0,0,0,0.125);
}

.article-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
}

.object-fit-cover {
    object-fit: cover;
}

.breadcrumb-item a {
    text-decoration: none;
    color: #6c757d;
}

.breadcrumb-item a:hover {
    color: #495057;
}

/* Адаптивность для карточек */
@media (max-width: 768px) {
    .col-md-6 {
        margin-bottom: 1.5rem;
    }
}

/* Анимации для лучшего UX */
.article-card a {
    color: inherit;
    display: block;
    height: 100%;
}

.article-card .card-title {
    transition: color 0.2s ease-in-out;
}

.article-card:hover .card-title {
    color: #0d6efd !important;
}
</style>
@endpush
