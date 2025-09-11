@extends('layouts.app')

@section('title', 'Мир линка | Блог ')
@section('description', 'Мир линка и публикации от участников нашего . Найдите интересные материалы, советы и экспертные мнения.')
@section('keywords', 'статьи, блог, сообщество, публикации, материалы, поиск')

@section('og_type', 'website')
@section('og_title', 'Мир линка ')
@section('og_description', 'Мир линка и публикации от участников нашего . Найдите интересные материалы и экспертные советы.')
@section('og_url', request()->url())
@section('og_image', asset('/hero.png'))

@section('twitter_title', 'Мир линка ')
@section('twitter_description', 'Мир линка и публикации от участников нашего .')
@section('twitter_image', asset('/hero.png'))

@section('canonical_url', route('articles.all'))

@push('head')
<!-- Structured Data (JSON-LD) -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Blog",
    "name": "Блог ",
    "description": "Мир линка и публикации от участников нашего ",
    "url": "{{ route('articles.all') }}",
    @if($articles->count() > 0)
    "blogPost": [
        @foreach($articles as $index => $article)
        {
            "@type": "BlogPosting",
            "headline": "{{ $article->title }}",
            "description": "{{ $article->excerpt }}",
            "url": "{{ route('articles.show', ['username' => $article->user->username, 'slug' => $article->slug]) }}",
            "datePublished": "{{ $article->created_at->toISOString() }}",
            "author": {
                "@type": "Person",
                "name": "{{ $article->user->name }}"
            }
        }@if($index < $articles->count() - 1),@endif
        @endforeach
    ]
    @endif
}
</script>
@endpush

@section('content')
<div class="container py-5">
    <!-- Заголовок и статистика -->
    <div class="row mb-5">
        <div class="col-lg-12 mx-auto ">
            <h1 class="display-5 fw-bold mb-3">
                <i class="bi bi-collection text-primary"></i>
                Мир линка 
            </h1>
            <p class="lead text-muted mb-4">
                Полезные материалы, советы и экспертные мнения от наших авторов
            </p>
            
            <!-- Отладочная информация о пагинации -->
            @if(config('app.debug'))
            <div class="alert alert-info small">
                <strong>Debug Info:</strong>
                Текущая страница: {{ $articles->currentPage() }} / {{ $articles->lastPage() }} | 
                Всего статей: {{ $articles->total() }} | 
                На странице: {{ $articles->count() }} | 
                Есть еще страницы: {{ $articles->hasMorePages() ? 'Да' : 'Нет' }}
            </div>
            @endif
        
        </div>
    </div>

    <!-- Поисковая форма -->
    <div class="row mb-5">
        <div class="col-lg-12 mx-auto">
            <form method="GET" action="{{ route('articles.all') }}" class="search-form">
                <div class="input-group input-group-lg shadow-sm">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" 
                           class="form-control border-start-0 border-end-0" 
                           name="search" 
                           value="{{ $search }}" 
                           placeholder="Поиск статей, авторов, тем..." 
                           id="searchInput"
                           autocomplete="off">
                    <button class="btn btn-primary px-4" type="submit">
                        <i class="bi bi-search me-1"></i>
                        Найти
                    </button>
                </div>
                
                <!-- Автодополнение -->
                <div id="searchSuggestions" class="position-absolute w-100 bg-white border rounded-bottom shadow-sm" style="z-index: 1000; display: none; margin-top: -1px;">
                </div>
            </form>
            
            @if($search)
                <div class="mt-3 d-flex align-items-center justify-content-between">
                    <small class="text-muted">
                        Результаты поиска по запросу: <strong>"{{ $search }}"</strong>
                        (найдено: {{ $articles->total() }} {{ trans_choice('статья|статьи|статей', $articles->total()) }})
                    </small>
                    <a href="{{ route('articles.all') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-x"></i> Сбросить
                    </a>
                </div>
            @endif
        </div>
    </div>

    @if($articles->count() > 0)
        <!-- Статьи -->
        <div class="row g-4 mb-5" id="articlesContainer">
            @include('articles.partials.articles-grid', ['articles' => $articles])
        </div>

        <!-- Индикатор загрузки -->
        <div class="text-center mt-4" id="loadingIndicator" style="display: none;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Загрузка...</span>
            </div>
            <p class="mt-2 text-muted">Загружаем еще статьи...</p>
        </div>

        <!-- Сообщение об окончании контента -->
        <div class="text-center mt-4" id="endMessage" style="display: none;">
            <p class="text-muted">Все статьи загружены</p>
        </div>

        <!-- Кнопка для ручного тестирования (только в debug режиме) -->
        @if(config('app.debug') && $articles->hasMorePages())
        <div class="text-center mt-4">
            <button type="button" class="btn btn-outline-primary" id="loadMoreBtn">
                Загрузить еще (тест)
            </button>
        </div>
        @endif

        <!-- Обычная пагинация для fallback -->
        <div class="d-flex justify-content-center mt-5">
            {{ $articles->appends(request()->query())->links('pagination.custom') }}
        </div>

    @else
        <!-- Пустое состояние -->
        <div class="text-center py-5">
            <div class="mb-4">
                @if($search)
                    <i class="bi bi-search display-1 text-muted"></i>
                @else
                    <i class="bi bi-file-text display-1 text-muted"></i>
                @endif
            </div>
            
            @if($search)
                <h3 class="h4 text-muted mb-3">Ничего не найдено</h3>
                <p class="text-muted mb-4">
                    По запросу <strong>"{{ $search }}"</strong> статьи не найдены.<br>
                    Попробуйте изменить поисковый запрос или <a href="{{ route('articles.all') }}">посмотреть Мир линка</a>.
                </p>
                <a href="{{ route('articles.all') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Показать Мир линка
                </a>
            @else
                <h3 class="h4 text-muted mb-3">Статьи не найдены</h3>
                <p class="text-muted mb-4">Пока что нет опубликованных статей</p>
            @endif
        </div>
    @endif
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

/* Стили для поисковой формы */
.search-form {
    position: relative;
}

.search-form .form-control:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.search-form .input-group-text {
    border-color: #ced4da;
}

.search-form .btn-primary {
    border-color: #0d6efd;
}

/* Стили для автодополнения */
#searchSuggestions {
    max-height: 300px;
    overflow-y: auto;
    border-top: none !important;
    border-top-left-radius: 0 !important;
    border-top-right-radius: 0 !important;
}

#searchSuggestions .suggestion-item {
    padding: 0.75rem 1rem;
    cursor: pointer;
    border-bottom: 1px solid #f0f0f0;
}

#searchSuggestions .suggestion-item:hover {
    background-color: #f8f9fa;
}

#searchSuggestions .suggestion-item:last-child {
    border-bottom: none;
}

/* Подсветка найденного текста */
mark {
    background-color: #fff3cd;
    padding: 0.125rem 0.25rem;
}

/* Стили для автора */
.author-avatar img,
.author-avatar > div {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
}

/* Адаптивность */
@media (min-width: 1200px) {
    .col-xl-3 {
        flex: 0 0 auto;
        width: 25%;
    }
}

@media (max-width: 1199px) and (min-width: 992px) {
    .col-lg-4 {
        flex: 0 0 auto;
        width: 33.333333%;
    }
}

@media (max-width: 991px) and (min-width: 768px) {
    .col-md-6 {
        flex: 0 0 auto;
        width: 50%;
    }
}

@media (max-width: 768px) {
    .col-sm-6 {
        flex: 0 0 auto;
        width: 50%;
    }
}

@media (max-width: 576px) {
    .col-sm-6 {
        flex: 0 0 auto;
        width: 100%;
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

/* Стили для статистики */
.bg-light {
    background-color: #f8f9fa !important;
}

.text-primary {
    color: #0d6efd !important;
}

.text-success {
    color: #198754 !important;
}

/* Стили для индикатора загрузки */
#loadingIndicator {
    padding: 2rem 0;
}

#endMessage {
    padding: 2rem 0;
    font-style: italic;
}

.spinner-border {
    width: 2rem;
    height: 2rem;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== INFINITE SCROLL INIT ===');
    
    // Элементы
    const articlesContainer = document.getElementById('articlesContainer');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const endMessage = document.getElementById('endMessage');
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    
    // Проверяем наличие необходимых элементов
    if (!articlesContainer) {
        console.error('articlesContainer не найден!');
        return;
    }
    
    // Переменные для пагинации
    let currentPage = {{ $articles->currentPage() }};
    let lastPage = {{ $articles->lastPage() }};
    let isLoading = false;
    const searchQuery = @json($search ?? '');
    
    console.log('Настройки:', {
        currentPage,
        lastPage,
        searchQuery,
        hasMorePages: {{ $articles->hasMorePages() ? 'true' : 'false' }}
    });

    // Функция загрузки
    function loadMore() {
        if (isLoading || currentPage >= lastPage) {
            console.log('Загрузка отменена:', { isLoading, currentPage, lastPage });
            return;
        }
        
        isLoading = true;
        currentPage++;
        
        console.log('Загружаем страницу:', currentPage);
        
        if (loadingIndicator) loadingIndicator.style.display = 'block';
        
        // Формируем URL
        let url = '/articles?page=' + currentPage;
        if (searchQuery) {
            url += '&search=' + encodeURIComponent(searchQuery);
        }
        
        // AJAX запрос с помощью XMLHttpRequest для надежности
        const xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader('Accept', 'application/json');
        
        // Добавляем CSRF токен если есть
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken.getAttribute('content'));
        }
        
        xhr.onload = function() {
            console.log('Ответ получен:', xhr.status);
            
            if (loadingIndicator) loadingIndicator.style.display = 'none';
            
            if (xhr.status === 200) {
                try {
                    const data = JSON.parse(xhr.responseText);
                    console.log('JSON данные:', data);
                    
                    if (data.html) {
                        articlesContainer.insertAdjacentHTML('beforeend', data.html);
                        console.log('HTML добавлен');
                    }
                    
                    if (!data.hasMore || currentPage >= lastPage) {
                        if (endMessage) endMessage.style.display = 'block';
                        console.log('Конец списка');
                    }
                    
                    if (data.debug) {
                        lastPage = Math.ceil(data.debug.total / data.debug.perPage);
                    }
                } catch (e) {
                    console.error('Ошибка парсинга JSON:', e);
                    console.log('Ответ сервера:', xhr.responseText.substring(0, 500));
                }
            } else {
                console.error('HTTP ошибка:', xhr.status);
                currentPage--; // откатываем
            }
            
            isLoading = false;
        };
        
        xhr.onerror = function() {
            console.error('Ошибка сети');
            if (loadingIndicator) loadingIndicator.style.display = 'none';
            currentPage--;
            isLoading = false;
        };
        
        xhr.send();
    }

    // Обработчик прокрутки
    let scrollTimer;
    window.addEventListener('scroll', function() {
        clearTimeout(scrollTimer);
        scrollTimer = setTimeout(function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const windowHeight = window.innerHeight;
            const documentHeight = Math.max(
                document.body.scrollHeight,
                document.body.offsetHeight,
                document.documentElement.clientHeight,
                document.documentElement.scrollHeight,
                document.documentElement.offsetHeight
            );
            
            if (scrollTop + windowHeight >= documentHeight - 1000) {
                console.log('Прокрутка триггер');
                loadMore();
            }
        }, 100);
    });

    // Кнопка тестирования
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            console.log('Ручная загрузка');
            loadMore();
        });
    }

    // Глобальная функция для тестирования
    window.loadMoreArticles = loadMore;
    
    console.log('=== INFINITE SCROLL READY ===');
});
</script>
@endpush
