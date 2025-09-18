@extends('admin.layout')

@section('title', 'Редактирование баннера - ' . config('app.name'))
@section('description', 'Редактирование баннера в каталоге')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
   
    <a href="{{ route('admin.banners', $currentUserId) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>
        <span class="d-none d-sm-inline">Назад к баннерам</span>
        <span class="d-sm-none">Назад</span>
    </a>
</div>

<!-- Скрытая форма для отправки данных -->
<form id="banner-form" action="{{ route('admin.banners.update', [$currentUserId, $banner]) }}" method="POST" enctype="multipart/form-data" style="display: none;">
    @csrf
    @method('PUT')
    <input type="hidden" name="title" id="hidden-title">
    <input type="hidden" name="description" id="hidden-description">
    <input type="hidden" name="link_url" id="hidden-link-url">
    <input type="hidden" name="link_text" id="hidden-link-text">
    <input type="hidden" name="order_index" id="hidden-order-index">
    <input type="file" name="image" id="hidden-image" accept="image/*">
</form>

<div class="row justify-content-center">
    <div class="col-lg-8 col-xl-6">
        <div class="d-flex flex-column">
            <div class="swiper banners-swiper" id="edit-banners-swiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="banners-banner">
                            <div class="banners-banner-block" style="justify-content: space-between">
                                <div class="banners-banner-block-title">
                                    <h3 class="editable-title" contenteditable="true" placeholder="Название баннера" onclick="selectText(this); event.stopPropagation();">{{ $banner->title }}</h3>
                                    <p class="editable-description" contenteditable="true" placeholder="Описание баннера. Нажмите, чтобы редактировать." onclick="selectText(this); event.stopPropagation();">{{ $banner->description }}</p>
                                </div>
                              
                                <div class="banner-bottom">
                                    <div class="banner-link" id="banner-link-display" style="{{ $banner->link_url ? 'display: block;' : 'display: none;' }}">
                                        <a href="{{ $banner->link_url ?: '#' }}" class="btn btn-primary btn-sm" id="banner-link-button">{{ $banner->link_text ?: 'Перейти' }}</a>
                                    </div>
                                    
                                    <div class="banner-buttons">
                                        <button type="button" class="btn btn-sm add-link-button" id="add-link-card-btn" onclick="addLinkInCard(); event.stopPropagation();" style="{{ $banner->link_url ? 'display: none;' : 'display: inline-block;' }}">
                                            <i class="bi bi-plus"></i> Добавить ссылку
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="banners-banner-block-img" onclick="selectImage()">
                                @if($banner->image_path)
                                    <img id="banner-preview-image" src="{{ asset('storage/' . $banner->image_path) }}" alt="Предпросмотр изображения" style="display: block; width: 100%; height: 100%; object-fit: cover; border-radius: 15px;">
                                    <div class="banner-no-image" id="banner-no-image" style="display: none;">
                                        <i class="bi bi-image" style="font-size: 3rem; color: #ccc;"></i>
                                        <p>Нажмите, чтобы выбрать изображение</p>
                                    </div>
                                @else
                                    <div class="banner-no-image" id="banner-no-image">
                                        <i class="bi bi-image" style="font-size: 3rem; color: #ccc;"></i>
                                        <p>Нажмите, чтобы выбрать изображение</p>
                                    </div>
                                    <img id="banner-preview-image" src="" alt="Предпросмотр изображения" style="display: none; width: 100%; height: 100%; object-fit: cover; border-radius: 15px;">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Дополнительные настройки (скрыты по умолчанию) -->
            <div class="mt-3">
                <button type="button" class="btn btn-outline-info btn-sm" onclick="toggleAdvanced()">
                    <i class="bi bi-sliders"></i> Дополнительные настройки
                </button>
            </div>
            
            <div id="advanced-settings" style="display: none;" class="mt-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="order-input" class="form-label">Порядок отображения</label>
                                    <input type="number" class="form-control" id="order-input" placeholder="1" min="1" value="{{ $banner->order_index }}">
                                    <div class="form-text">Чем меньше число, тем выше баннер в списке</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    
                                        <input class="form-check-input" style="position: relative"type="checkbox" value="1" id="has-link-checkbox" onchange="toggleLinkSettings()" {{ $banner->link_url ? 'checked' : '' }}>
                                        <label class="form-check-label" for="has-link-checkbox">
                                            Добавить кнопку-ссылку
                                        </label>
                                    
                                </div>
                            </div>
                        </div>
                        
                        <div id="link-settings" style="{{ $banner->link_url ? 'display: block;' : 'display: none;' }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="link-url-input" class="form-label">URL ссылки</label>
                                        <input type="url" class="form-control" id="link-url-input" placeholder="https://example.com" value="{{ $banner->link_url }}">
                                        <div class="form-text">Введите полный URL включая https://</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="link-text-select" class="form-label">Текст кнопки</label>
                                        <select class="form-select" id="link-text-select" onchange="updateLinkText()">
                                            <option value="Перейти" {{ ($banner->link_text ?? 'Перейти') == 'Перейти' ? 'selected' : '' }}>Перейти</option>
                                            <option value="Подробнее" {{ ($banner->link_text ?? '') == 'Подробнее' ? 'selected' : '' }}>Подробнее</option>
                                            <option value="Читать далее" {{ ($banner->link_text ?? '') == 'Читать далее' ? 'selected' : '' }}>Читать далее</option>
                                            <option value="Узнать больше" {{ ($banner->link_text ?? '') == 'Узнать больше' ? 'selected' : '' }}>Узнать больше</option>
                                            <option value="Заказать" {{ ($banner->link_text ?? '') == 'Заказать' ? 'selected' : '' }}>Заказать</option>
                                            <option value="Купить" {{ ($banner->link_text ?? '') == 'Купить' ? 'selected' : '' }}>Купить</option>
                                            <option value="Оформить" {{ ($banner->link_text ?? '') == 'Оформить' ? 'selected' : '' }}>Оформить</option>
                                            <option value="Записаться" {{ ($banner->link_text ?? '') == 'Записаться' ? 'selected' : '' }}>Записаться</option>
                                            <option value="Связаться" {{ ($banner->link_text ?? '') == 'Связаться' ? 'selected' : '' }}>Связаться</option>
                                            <option value="Смотреть" {{ ($banner->link_text ?? '') == 'Смотреть' ? 'selected' : '' }}>Смотреть</option>
                                            <option value="Открыть" {{ ($banner->link_text ?? '') == 'Открыть' ? 'selected' : '' }}>Открыть</option>
                                            <option value="Скачать" {{ ($banner->link_text ?? '') == 'Скачать' ? 'selected' : '' }}>Скачать</option>
                                        </select>
                                        <div class="form-text">Выберите подходящий текст для кнопки</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Кнопка сохранения -->
            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-primary" onclick="saveBanner()">
                    <i class="bi bi-check-lg me-2"></i>
                    Сохранить изменения
                </button>
                <a href="{{ route('admin.banners', $currentUserId) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg me-2"></i>
                    Отмена
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Индикатор загрузки -->
<div id="loading-overlay" style="display: none;">
    <div class="loading-spinner">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Загрузка...</span>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Swiper CSS и JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- Передача данных баннера в JavaScript -->
<script>
window.bannerData = {
    title: "{{ $banner->title }}",
    description: "{{ $banner->description }}",
    linkUrl: "{{ $banner->link_url }}",
    linkText: "{{ $banner->link_text ?: 'Перейти' }}",
    orderIndex: "{{ $banner->order_index }}",
    hasLink: {{ $banner->link_url ? 'true' : 'false' }}
};

window.currentUserId = "{{ $currentUserId }}";

// Обработка ошибок валидации из Laravel
@if($errors->any())
document.addEventListener('DOMContentLoaded', function() {
    let errorMessages = '';
    @foreach($errors->all() as $error)
        errorMessages += '{{ $error }}\n';
    @endforeach
    alert('Пожалуйста, исправьте ошибки в форме:\n' + errorMessages);
});
@endif
</script>

<!-- Подключение через Vite -->
@vite(['resources/css/admin-banners.css', 'resources/js/admin-banners.js'])
@endsection
