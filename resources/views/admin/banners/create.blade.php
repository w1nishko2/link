@extends('admin.layout')

@section('title', 'Создание баннера - ' . config('app.name'))
@section('description', 'Добавление нового баннера в каталог')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="bi bi-flag me-2"></i>
            Создание баннера
        </h1>
        <a href="{{ route('admin.banners.index', $currentUserId) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Назад к списку
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <!-- Предварительный просмотр -->
            <div class="banner-preview mb-4">
                <h5 class="mb-3">
                    <i class="bi bi-eye me-2"></i>
                    Предварительный просмотр
                </h5>
                <div class="edit-banner">
                    <div class="swiper" id="edit-banner-swiper">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="banner-slide">
                                    <div class="banner-image">
                                        <div class="image-placeholder">
                                            <i class="bi bi-image"></i>
                                            <p>Загрузите изображение</p>
                                        </div>
                                    </div>
                                    <div class="banner-content">
                                        <h2 class="banner-title">Заголовок баннера</h2>
                                        <p class="banner-description">Описание баннера</p>
                                        <a href="#" class="banner-link" style="display: none;">Перейти</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Форма создания -->
            <div class="card banner-form">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-pencil me-2"></i>
                        Настройки баннера
                    </h5>
                </div>
                <div class="card-body">
                    <form id="banner-form" action="{{ route('admin.banners.store', $currentUserId) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Загрузка изображения -->
                        <div class="form-section">
                            <label class="form-label">Изображение баннера *</label>
                            <div class="upload-area">
                                <i class="bi bi-cloud-upload"></i>
                                <p class="mb-1">Перетащите изображение сюда</p>
                                <p class="text-muted small">или нажмите для выбора файла</p>
                                <small class="text-muted">Рекомендуемый размер: 1920x600px</small>
                            </div>
                            <input type="file" id="image-upload" name="image" accept="image/*" style="display: none;" required>
                        </div>

                        <!-- Заголовок -->
                        <div class="form-section">
                            <label class="form-label">Заголовок баннера *</label>
                            <div id="banner-title" 
                                 class="editable-field" 
                                 contenteditable="true" 
                                 data-placeholder="Введите заголовок баннера...">{{ old('title') }}</div>
                            <input type="hidden" name="title" value="{{ old('title') }}">
                        </div>

                        <!-- Описание -->
                        <div class="form-section">
                            <label class="form-label">Описание</label>
                            <div id="banner-description" 
                                 class="editable-field" 
                                 contenteditable="true" 
                                 data-placeholder="Введите описание баннера...">{{ old('description') }}</div>
                            <input type="hidden" name="description" value="{{ old('description') }}">
                        </div>

                        <!-- Порядок отображения -->
                        <div class="form-section">
                            <label for="order-index" class="form-label">Порядок отображения</label>
                            <input type="number" class="form-control" id="order-index" name="order_index" 
                                   value="{{ old('order_index', 0) }}" min="0">
                            <small class="text-muted">Чем меньше число, тем раньше отображается баннер</small>
                        </div>

                        <!-- Настройки ссылки -->
                        <div class="form-section">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="has-link-toggle" name="has_link" 
                                       value="1" {{ old('has_link') ? 'checked' : '' }}>
                                <label class="form-check-label" for="has-link-toggle">
                                    Добавить ссылку к баннеру
                                </label>
                            </div>

                            <div class="link-fields" style="{{ old('has_link') ? 'display: block;' : 'display: none;' }}">
                                <label class="form-label mt-3">URL ссылки</label>
                                <div id="banner-link-url" 
                                     class="editable-field" 
                                     contenteditable="true" 
                                     data-placeholder="https://example.com">{{ old('link_url') }}</div>
                                <input type="hidden" name="link_url" value="{{ old('link_url') }}">

                                <label class="form-label mt-3">Текст кнопки</label>
                                <div id="banner-link-text" 
                                     class="editable-field" 
                                     contenteditable="true" 
                                     data-placeholder="Перейти">{{ old('link_text', 'Перейти') }}</div>
                                <input type="hidden" name="link_text" value="{{ old('link_text', 'Перейти') }}">
                            </div>
                        </div>

                        <!-- Кнопки действий -->
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-primary flex-fill" id="save-banner">
                                <i class="bi bi-check me-1"></i>
                                Создать баннер
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Справочная информация -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Справка
                    </h6>
                </div>
                <div class="card-body">
                    <small class="text-muted d-block mb-2">
                        <strong>Рекомендации по изображениям:</strong>
                    </small>
                    <small class="text-muted d-block">• Размер: 1920x600px</small>
                    <small class="text-muted d-block">• Формат: JPG, PNG, WebP</small>
                    <small class="text-muted d-block">• Размер файла: до 2 МБ</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Swiper CSS и JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- Подключение через Vite -->
@vite(['resources/css/admin-banners.css', 'resources/js/admin-banners.js'])
@endsection