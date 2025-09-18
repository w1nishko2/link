@extends('admin.layout')

@section('title', 'Создание статьи - ' . config('app.name'))
@section('description', 'Создание новой статьи для блога')

@section('content')
    <main class="" role="main">
        <!-- Скрытая форма для отправки данных -->
        <form id="article-form" action="{{ route('admin.articles.store', $currentUserId) }}" method="POST" enctype="multipart/form-data" style="display: none;">
            @csrf
            <input type="hidden" name="title" id="hidden-title">
            <input type="hidden" name="excerpt" id="hidden-excerpt">
            <input type="hidden" name="content" id="hidden-content">
            <input type="hidden" name="read_time" id="hidden-read-time" value="1">
            <input type="hidden" name="is_published" id="hidden-is-published" value="0">
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
                            Опубликовать
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
                            
                        </h1>
                    </div>

                    <!-- Краткое описание -->
                    <div class="mb-4">
                        <p id="article-excerpt" class="editable-excerpt" contenteditable="true" data-placeholder="Краткое описание статьи...">
                            
                        </p>
                    </div>

                    <!-- Область изображения -->
                    <div class="mb-4">
                        <div class="article-image">
                            <div class="image-placeholder">
                                <i class="bi bi-camera"></i>
                                <p class="mb-0">Нажмите, чтобы добавить изображение</p>
                            </div>
                        </div>
                    </div>

                    <!-- Редактор контента -->
                    <div class="mb-4">
                        <div id="content-editor" style="min-height: 400px;">
                            
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
                                    <input class="form-check-input" type="radio" name="status" id="status-draft" value="draft" checked>
                                    <label class="form-check-label" for="status-draft">
                                        Черновик
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="status-published" value="published">
                                    <label class="form-check-label" for="status-published">
                                        Опубликовано
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="read-time" class="form-label">Время чтения (мин)</label>
                                <input type="number" class="form-control" id="read-time" value="1" min="1" max="60">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Предварительный просмотр</label>
                                <button type="button" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-eye me-1"></i>
                                    Просмотр
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Информация о сохранении -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                Информация
                            </h6>
                        </div>
                        <div class="card-body">
                            <small class="text-muted">
                                <i class="bi bi-clock me-1"></i>
                                Автосохранение каждые 30 секунд
                            </small>
                            <br>
                            <small class="text-muted">
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

@section('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
@vite(['resources/css/admin-content.css', 'resources/js/admin-articles-extended.js'])
@endsection