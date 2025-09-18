@extends('admin.layout')

@section('title', 'Редактирование статьи - ' . config('app.name'))
@section('description', 'Редактирование статьи: ' . $article->title)

@section('content')
    <main class="" role="main">
        <!-- Скрытая форма для отправки данных -->
        <form id="article-form" action="{{ route('admin.articles.update', [$currentUserId, $article->id]) }}" method="POST" enctype="multipart/form-data" style="display: none;">
            @csrf
            @method('PUT')
            <input type="hidden" name="title" id="hidden-title">
            <input type="hidden" name="excerpt" id="hidden-excerpt">
            <input type="hidden" name="content" id="hidden-content">
            <input type="hidden" name="read_time" id="hidden-read-time" value="{{ $article->read_time ?? 1 }}">
            <input type="hidden" name="is_published" id="hidden-is-published" value="{{ $article->is_published ? '1' : '0' }}">
            <input type="file" name="image" id="image-input" accept="image/*" style="display: none;">
        </form>

        <!-- Индикатор автосохранения -->
        <div class="saving-indicator">
            <i class="bi bi-check-circle me-1"></i>
            Сохранено
        </div>

        <!-- Панель инструментов -->
        <div class="toolbar">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.articles.index', $currentUserId) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>
                            Назад к списку
                        </a>
                        <button type="button" class="btn btn-outline-info btn-sm" id="auto-save">
                            <i class="bi bi-cloud-arrow-up me-1"></i>
                            Автосохранение
                        </button>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-primary btn-sm" id="save-draft">
                            <i class="bi bi-file-earmark me-1"></i>
                            Сохранить черновик
                        </button>
                        <button type="button" class="btn btn-primary btn-sm" id="save-publish">
                            <i class="bi bi-send me-1"></i>
                            {{ $article->is_published ? 'Обновить' : 'Опубликовать' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Основной контент -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8">
                    <!-- Заголовок статьи -->
                    <div class="mb-4">
                        <h1 id="article-title" class="editable-title" contenteditable="true" data-placeholder="Введите заголовок статьи...">
                            {{ $article->title }}
                        </h1>
                    </div>

                    <!-- Краткое описание -->
                    <div class="mb-4">
                        <p id="article-excerpt" class="editable-excerpt" contenteditable="true" data-placeholder="Краткое описание статьи...">
                            {{ $article->excerpt }}
                        </p>
                    </div>

                    <!-- Область изображения -->
                    <div class="mb-4">
                        <div class="article-image">
                            @if($article->image_path)
                                <img src="{{ asset('storage/' . $article->image_path) }}" alt="Изображение статьи">
                                <div class="image-overlay">
                                    <i class="bi bi-camera"></i>
                                    <span>Изменить изображение</span>
                                </div>
                            @else
                                <div class="image-placeholder">
                                    <i class="bi bi-camera"></i>
                                    <p class="mb-0">Нажмите, чтобы добавить изображение</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Редактор контента -->
                    <div class="mb-4">
                        <div id="content-editor" style="min-height: 400px;">
                            {!! $article->content !!}
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Боковая панель с настройками -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-gear me-2"></i>
                                Настройки публикации
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Статус</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="status-draft" value="draft" {{ !$article->is_published ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status-draft">
                                        Черновик
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="status-published" value="published" {{ $article->is_published ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status-published">
                                        Опубликовано
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="read-time" class="form-label">Время чтения (мин)</label>
                                <input type="number" class="form-control" id="read-time" value="{{ $article->read_time ?? 1 }}" min="1" max="60">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Последнее обновление</label>
                                <small class="d-block text-muted">
                                    {{ $article->updated_at->format('d.m.Y H:i') }}
                                </small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Предварительный просмотр</label>
                                <a href="{{ route('articles.show', $article) }}" target="_blank" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-eye me-1"></i>
                                    Просмотр
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Информация о статистике -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-graph-up me-2"></i>
                                Статистика
                            </h6>
                        </div>
                        <div class="card-body">
                            <small class="text-muted d-block">
                                <i class="bi bi-calendar me-1"></i>
                                Создано: {{ $article->created_at->format('d.m.Y') }}
                            </small>
                            <small class="text-muted d-block">
                                <i class="bi bi-clock me-1"></i>
                                Автосохранение каждые 30 секунд
                            </small>
                            <small class="text-muted d-block">
                                <i class="bi bi-shield-check me-1"></i>
                                Данные сохраняются локально
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Модальное окно загрузки -->
    <div id="loading-overlay" class="loading-modal" style="display: none;">
        <div class="loading-content">
            <div class="spinner-border text-primary mb-3" role="status">
                <span class="visually-hidden">Загрузка...</span>
            </div>
            <h5>Сохранение статьи...</h5>
            <p>Пожалуйста, подождите</p>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
<script>
// Данные статьи для редактирования
window.articleData = {
    title: '{{ addslashes($article->title) }}',
    excerpt: '{{ addslashes($article->excerpt) }}',
    content: `{!! addslashes($article->content) !!}`,
    isPublished: {{ $article->is_published ? 'true' : 'false' }},
    imagePath: '{{ $article->image_path }}',
    readTime: {{ $article->read_time ?? 1 }},
    updateUrl: '{{ route("admin.articles.update", [$currentUserId, $article->id]) }}',
    articleId: {{ $article->id }}
};
</script>
@vite(['resources/css/admin-content.css', 'resources/js/admin-articles-extended.js'])
@endsection