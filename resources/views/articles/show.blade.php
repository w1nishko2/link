@extends('layouts.app')

@section('title', $article->title . ' | ' . $article->user->name)
@section('description', $article->excerpt ? Str::limit(strip_tags($article->excerpt), 160) : Str::limit(strip_tags($article->content), 160))
@section('keywords', 'статья, ' . strtolower($article->user->name) . ', блог, ' . strtolower(str_replace(' ', ', ', $article->title)))
@section('author', $article->user->name)

@section('og_type', 'article')
@section('og_title', $article->title)
@section('og_description', $article->excerpt ? Str::limit(strip_tags($article->excerpt), 200) : Str::limit(strip_tags($article->content), 200))
@section('og_url', route('articles.show', ['username' => $article->user->username, 'slug' => $article->slug]))
@section('og_image', $article->image_path ? asset('storage/' . $article->image_path) : ($article->user->avatar ? asset('storage/' . $article->user->avatar) : asset('/hero.png')))

@section('twitter_title', $article->title)
@section('twitter_description', $article->excerpt ? Str::limit(strip_tags($article->excerpt), 160) : Str::limit(strip_tags($article->content), 160))
@section('twitter_image', $article->image_path ? asset('storage/' . $article->image_path) : asset('/hero.png'))

@section('canonical_url', route('articles.show', ['username' => $article->user->username, 'slug' => $article->slug]))

@push('head')
<!-- Article Structured Data (JSON-LD) -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Article",
    "headline": "{{ $article->title }}",
    "description": "{{ $article->excerpt ? strip_tags($article->excerpt) : Str::limit(strip_tags($article->content), 200) }}",
    "datePublished": "{{ $article->created_at->toISOString() }}",
    "dateModified": "{{ $article->updated_at->toISOString() }}",
    "author": {
        "@type": "Person",
        "name": "{{ $article->user->name }}",
        "url": "{{ route('user.page', $article->user->username) }}"
    },
    "publisher": {
        "@type": "Person",
        "name": "{{ $article->user->name }}",
        "url": "{{ route('user.page', $article->user->username) }}"
    },
    @if($article->image_path)
    "image": "{{ asset('storage/' . $article->image_path) }}",
    @endif
    "url": "{{ route('articles.show', ['username' => $article->user->username, 'slug' => $article->slug]) }}",
    "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "{{ route('articles.show', ['username' => $article->user->username, 'slug' => $article->slug]) }}"
    },
    "wordCount": "{{ str_word_count(strip_tags($article->content)) }}",
    "timeRequired": "PT{{ $article->read_time }}M",
    "inLanguage": "ru-RU",
    "copyrightHolder": {
        "@type": "Person",
        "name": "{{ $article->user->name }}"
    },
    "copyrightYear": "{{ $article->created_at->format('Y') }}"
}
</script>

<!-- Breadcrumb Structured Data -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {
            "@type": "ListItem",
            "position": 1,
            "name": "{{ $article->user->name }}",
            "item": "{{ route('user.page', $article->user->username) }}"
        },
        {
            "@type": "ListItem",
            "position": 2,
            "name": "{{ $article->title }}",
            "item": "{{ route('articles.show', ['username' => $article->user->username, 'slug' => $article->slug]) }}"
        }
    ]
}
</script>

@if($relatedArticles->count() > 0)
<!-- Related Articles Structured Data -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ItemList",
    "name": "Похожие статьи",
    "itemListElement": [
        @foreach($relatedArticles as $index => $related)
        {
            "@type": "Article",
            "position": {{ $index + 1 }},
            "headline": "{{ $related->title }}",
            "description": "{{ $related->excerpt }}",
            "url": "{{ route('articles.show', ['username' => $related->user->username, 'slug' => $related->slug]) }}",
            "datePublished": "{{ $related->created_at->toISOString() }}",
            "author": {
                "@type": "Person",
                "name": "{{ $related->user->name }}"
            }
        }@if($index < $relatedArticles->count() - 1),@endif
        @endforeach
    ]
}
</script>
@endif
@endpush

@section('content')
    <main class="article-page" role="main">
        <!-- Основной контент -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('user.page', $article->user->username) }}" class="text-decoration-none">
                                    <i class="bi bi-house-door me-1"></i>{{ $article->user->name }}
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $article->title }}</li>
                        </ol>
                    </nav>
                    
                    <article class="article-wrapper" itemscope itemtype="https://schema.org/Article">
                        <!-- Заголовок и мета-информация -->
                        <header class="article-header">
                            <h1 class="article-title" itemprop="headline">{{ $article->title }}</h1>

                            <div class="article-meta">
                                <div class="author-section">
                                    <div class="author-avatar">
                                        @if($article->user->avatar)
                                            <img src="{{ asset('storage/' . $article->user->avatar) }}" alt="Аватар {{ $article->user->name }}" class="rounded-circle" width="40" height="40">
                                        @else
                                            <i class="bi bi-person-circle"></i>
                                        @endif
                                    </div>
                                    <div class="author-info">
                                        <span class="author-name" itemprop="author" itemscope itemtype="https://schema.org/Person">
                                            <span itemprop="name">{{ $article->user->name }}</span>
                                        </span>
                                        <div class="article-date">
                                            <time datetime="{{ $article->created_at->toISOString() }}" itemprop="datePublished">
                                                {{ $article->created_at->format('d.m.Y') }}
                                            </time>
                                            @if($article->read_time)
                                                <span class="read-time">{{ $article->read_time }} мин чтения</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="article-actions">
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="copyArticleUrl()">
                                        <i class="bi bi-share me-1"></i>Поделиться
                                    </button>
                                </div>
                            </div>
                        </header>

                        <!-- Изображение статьи -->
                        @if($article->image_path)
                            <div class="article-image mb-4">
                                <img src="{{ asset('storage/' . $article->image_path) }}" alt="{{ $article->title }}" class="img-fluid rounded" itemprop="image">
                            </div>
                        @endif

                        <!-- Краткое описание -->
                        @if($article->excerpt)
                            <div class="article-excerpt">
                                <p class="lead" itemprop="description">{{ $article->excerpt }}</p>
                            </div>
                        @endif

                        <!-- Содержание статьи -->
                        <div class="article-content" itemprop="articleBody">
                            {!! $article->content !!}
                        </div>

                        <!-- Мета-информация в конце -->
                        <footer class="article-footer mt-5">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="article-tags">
                                        <small class="text-muted">
                                            Опубликовано: <time datetime="{{ $article->created_at->toISOString() }}">{{ $article->created_at->format('d.m.Y H:i') }}</time>
                                        </small>
                                        @if($article->updated_at != $article->created_at)
                                            <br>
                                            <small class="text-muted">
                                                Обновлено: <time datetime="{{ $article->updated_at->toISOString() }}" itemprop="dateModified">{{ $article->updated_at->format('d.m.Y H:i') }}</time>
                                            </small>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 text-end">
                                    <button type="button" class="btn btn-outline-success btn-sm" onclick="copyArticleUrl()">
                                        <i class="bi bi-link-45deg me-1"></i>Копировать ссылку
                                    </button>
                                </div>
                            </div>
                        </footer>
                    </article>
                </div>
            </div>

            <!-- Информация об авторе -->
            <aside class="row mt-5" aria-label="Информация об авторе">
                <div class="col-lg-10 mx-auto">
                    <div class="card" itemscope itemtype="https://schema.org/Person">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2 text-center">
                                    @if($article->user->avatar)
                                        <img src="{{ asset('storage/' . $article->user->avatar) }}" alt="Аватар {{ $article->user->name }}" class="rounded-circle" width="80" height="80" itemprop="image">
                                    @else
                                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                            <i class="bi bi-person-fill fs-2 text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-8">
                                    <h5 class="card-title" itemprop="name">{{ $article->user->name }}</h5>
                                    @if($article->user->bio)
                                        <p class="card-text" itemprop="description">{{ $article->user->bio }}</p>
                                    @endif
                                    <meta itemprop="url" content="{{ route('user.page', $article->user->username) }}">
                                </div>
                                <div class="col-md-2 text-end">
                                    <a href="{{ route('user.page', $article->user->username) }}" class="btn btn-primary">
                                        Все статьи автора
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Похожие статьи -->
            @if($relatedArticles->count() > 0)
                <section class="row mt-5" aria-label="Похожие статьи">
                    <div class="col-lg-10 mx-auto">
                        <h3>Другие статьи автора</h3>
                        <div class="row">
                            @foreach($relatedArticles as $related)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <article class="card h-100" itemscope itemtype="https://schema.org/Article">
                                        @if($related->image_path)
                                            <img src="{{ asset('storage/' . $related->image_path) }}" class="card-img-top" style="height: 150px; object-fit: cover;" alt="{{ $related->title }}" itemprop="image">
                                        @endif
                                        <div class="card-body">
                                            <h6 class="card-title" itemprop="headline">
                                                <a href="{{ route('articles.show', ['username' => $related->user->username, 'slug' => $related->slug]) }}" class="text-decoration-none" itemprop="url">
                                                    {{ Str::limit($related->title, 50) }}
                                                </a>
                                            </h6>
                                            @if($related->excerpt)
                                                <p class="card-text small" itemprop="description">{{ Str::limit($related->excerpt, 100) }}</p>
                                            @endif
                                            <small class="text-muted">
                                                <time datetime="{{ $related->created_at->toISOString() }}" itemprop="datePublished">{{ $related->created_at->format('d.m.Y') }}</time>
                                            </small>
                                            <meta itemprop="author" content="{{ $related->user->name }}">
                                        </div>
                                    </article>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif
        </div>
    </main>

    <script>
        function copyArticleUrl() {
            const url = window.location.href;
            
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(url).then(() => {
                    showCopyMessage('✅ Ссылка скопирована в буфер обмена!');
                }).catch(() => {
                    fallbackCopyTextToClipboard(url);
                });
            } else {
                fallbackCopyTextToClipboard(url);
            }
        }

        function fallbackCopyTextToClipboard(text) {
            const textArea = document.createElement("textarea");
            textArea.value = text;
            textArea.style.position = "fixed";
            textArea.style.left = "-999999px";
            textArea.style.top = "-999999px";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            
            try {
                document.execCommand('copy');
                showCopyMessage('✅ Ссылка скопирована!');
            } catch (err) {
                showCopyMessage('❌ Не удалось скопировать ссылку');
            }
            
            document.body.removeChild(textArea);
        }

        function showCopyMessage(message) {
            // Создаем уведомление
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show position-fixed';
            alert.style.top = '20px';
            alert.style.right = '20px';
            alert.style.zIndex = '9999';
            alert.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            document.body.appendChild(alert);

            // Автоматически скрываем через 3 секунды
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            }, 3000);
        }
    </script>
@endsection
