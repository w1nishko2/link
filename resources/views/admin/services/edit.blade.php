@extends('admin.layout')

@section('title', 'Редактирование услуги - ' . config('app.name'))
@section('description', 'Редактирование описания и настроек услуги')

<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
@vite(['resources/css/services-reels.css', 'resources/css/admin-services.css', 'resources/js/admin-services.js'])

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
    
    <a href="{{ route('admin.services', $currentUserId) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>
        <span class="d-none d-sm-inline">Назад к услугам</span>
        <span class="d-sm-none">Назад</span>
    </a>
</div>

<!-- Скрытая форма для отправки данных -->
<form id="service-form" action="{{ route('admin.services.update', [$currentUserId, $service]) }}" method="POST" enctype="multipart/form-data" style="display: none;">
    @csrf
    @method('PUT')
    <input type="hidden" name="title" id="hidden-title">
    <input type="hidden" name="description" id="hidden-description">
    <input type="hidden" name="price" id="hidden-price">
    <input type="hidden" name="price_type" id="hidden-price-type" value="fixed">
    <input type="hidden" name="button_text" id="hidden-button-text">
    <input type="hidden" name="button_link" id="hidden-button-link">
    <input type="hidden" name="order_index" id="hidden-order-index">
    <input type="file" name="image" id="hidden-image" accept="image/*">
</form>
<div class="row justify-content-center">
    <div class="col-lg-8 col-xl-4">
        
            <div class="swiper services-swiper" id="edit-services-swiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="service-card editable-card" id="editable-service-card">
                            <!-- Изображение с возможностью загрузки -->
                            <div class="service-image editable-image" onclick="selectImage()">
                                <img id="service-image" 
                                     src="{{ $service->image_path ? asset('storage/' . $service->image_path) : '/hero.png' }}" 
                                     alt="Изображение услуги" 
                                     loading="lazy"
                                     width="300"
                                     height="600"
                                     decoding="async">
                                <div class="image-overlay">
                                    <i class="bi bi-camera-fill"></i>
                                    <span>Изменить изображение</span>
                                </div>
                            </div>
                            
                            <div class="service-content">
                                <!-- Редактируемое название -->
                                <h3 class="editable-title" 
                                    contenteditable="true" 
                                    placeholder="Введите название услуги..."
                                    data-max-length="100"
                                    onclick="selectText(this)">{{ $service->title }}</h3>
                                
                                <!-- Редактируемое описание -->
                                <p class="editable-description" 
                                   contenteditable="true" 
                                   placeholder="Введите описание услуги..."
                                   data-max-length="500"
                                   onclick="selectText(this)">{{ $service->description }}</p>
                                
                                <div class="service-bottom">
                                    <!-- Редактируемая цена -->
                                    <div class="service-price editable-price" 
                                         contenteditable="true" 
                                         placeholder="Цена"
                                         onclick="selectText(this)"
                                         style="display: {{ $service->price ? 'block' : 'none' }};margin:0;">{{ $service->formatted_price ?? '' }}</div>
                                    
                                    <div class="service-buttons" style="flex-wrap: nowrap">
                                        <!-- Кнопка добавления цены -->
                                        <button type="button" class="btn btn-outline-success btn-sm add-price-button" 
                                                onclick="addPriceInCard()" id="add-price-card-btn"
                                                style="display: {{ $service->price ? 'none' : 'inline-block' }};">
                                            <i class="bi bi-tag me-1"></i> Добавить цену
                                        </button>
                                        
                                        <!-- Редактируемая кнопка -->
                                        <div class="service-button btn btn-primary btn-sm editable-button" 
                                             onclick="editButton()">
                                            {{ $service->button_text ?? 'Кнопка' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-footer">
                <div class="row">
                    <div class="col-12">
                        <!-- Основные действия -->
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-primary" onclick="saveService()">
                                <i class="bi bi-check-circle me-2"></i>
                                Обновить услугу
                            </button>
                            <a href="{{ route('admin.services', $currentUserId) }}" class="btn btn-outline-secondary btn-sm">
                                Отмена
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        
        
        <!-- Дополнительные настройки (скрыты по умолчанию) -->
        <div class="card mt-3" id="advanced-settings" style="display: none;">
            <div class="card-header">
                <h6 class="card-title mb-0">Дополнительные настройки</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <!-- Управление ценой -->
                        <label class="form-label">Управление ценой</label>
                        <div class="d-grid">
                            <button type="button" class="btn btn-outline-success btn-sm" onclick="togglePrice()" id="price-toggle">
                                <i class="bi bi-tag me-1"></i> {{ $service->price ? 'Убрать цену' : 'Добавить цену' }}
                            </button>
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Тип цены</label>
                        <select class="form-select form-select-sm" id="price-type-select">
                            <option value="fixed" {{ $service->price_type == 'fixed' ? 'selected' : '' }}>Фиксированная</option>
                            <option value="hourly" {{ $service->price_type == 'hourly' ? 'selected' : '' }}>За час</option>
                            <option value="project" {{ $service->price_type == 'project' ? 'selected' : '' }}>За проект</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Порядок отображения</label>
                        <input type="number" class="form-control form-control-sm" id="order-input" placeholder="Авто" value="{{ $service->order_index }}">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-3">
            <button type="button" class="btn btn-link btn-sm" onclick="toggleAdvanced()">
                <i class="bi bi-gear me-1"></i> Дополнительные настройки
            </button>
        </div>
    </div>
</div>

<!-- Модальные окна -->
<!-- Модальное окно редактирования кнопки -->
<div class="modal fade" id="buttonModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Настройка кнопки</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Текст кнопки</label>
                    <select class="form-select" id="button-text-select">
                        <option value="">Выберите текст</option>
                        <option value="Заказать услугу" {{ $service->button_text == 'Заказать услугу' ? 'selected' : '' }}>Заказать услугу</option>
                        <option value="Связаться с нами" {{ $service->button_text == 'Связаться с нами' ? 'selected' : '' }}>Связаться с нами</option>
                        <option value="Узнать подробнее" {{ $service->button_text == 'Узнать подробнее' ? 'selected' : '' }}>Узнать подробнее</option>
                        <option value="Написать в WhatsApp" {{ $service->button_text == 'Написать в WhatsApp' ? 'selected' : '' }}>Написать в WhatsApp</option>
                        <option value="Написать в Telegram" {{ $service->button_text == 'Написать в Telegram' ? 'selected' : '' }}>Написать в Telegram</option>
                        <option value="Позвонить" {{ $service->button_text == 'Позвонить' ? 'selected' : '' }}>Позвонить</option>
                        <option value="Отправить email" {{ $service->button_text == 'Отправить email' ? 'selected' : '' }}>Отправить email</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Ссылка</label>
                    <select class="form-select" id="button-link-select">
                        <option value="">Выберите ссылку</option>
                        @if($user->phone)
                            <option value="tel:{{ $user->phone }}" {{ $service->button_link == 'tel:' . $user->phone ? 'selected' : '' }}>Телефон: {{ $user->phone }}</option>
                        @endif
                        @if($user->email)
                            <option value="mailto:{{ $user->email }}" {{ $service->button_link == 'mailto:' . $user->email ? 'selected' : '' }}>Email: {{ $user->email }}</option>
                        @endif
                        @if($user->telegram_url)
                            <option value="{{ $user->telegram_url }}" {{ $service->button_link == $user->telegram_url ? 'selected' : '' }}>Telegram</option>
                        @endif
                        @if($user->whatsapp_url)
                            <option value="{{ $user->whatsapp_url }}" {{ $service->button_link == $user->whatsapp_url ? 'selected' : '' }}>WhatsApp</option>
                        @endif
                        @if($user->vk_url)
                            <option value="{{ $user->vk_url }}" {{ $service->button_link == $user->vk_url ? 'selected' : '' }}>VK</option>
                        @endif
                        @if($user->instagram_url)
                            <option value="{{ $user->instagram_url }}" {{ $service->button_link == $user->instagram_url ? 'selected' : '' }}>Instagram</option>
                        @endif
                        @if($user->website_url)
                            <option value="{{ $user->website_url }}" {{ $service->button_link == $user->website_url ? 'selected' : '' }}>Сайт</option>
                        @endif
                        @foreach($user->socialLinks as $socialLink)
                            <option value="{{ $socialLink->url }}" {{ $service->button_link == $socialLink->url ? 'selected' : '' }}>{{ $socialLink->service_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary" onclick="applyButtonSettings()">Применить</button>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно для настройки кнопки -->
<div class="modal fade" id="buttonModal" tabindex="-1" aria-labelledby="buttonModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="buttonModalLabel">Настройка кнопки</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="button-text-select" class="form-label">Текст кнопки</label>
                    <select class="form-select" id="button-text-select">
                        <option value="">Выберите текст кнопки</option>
                        <option value="Подробнее">Подробнее</option>
                        <option value="Заказать">Заказать</option>
                        <option value="Узнать больше">Узнать больше</option>
                        <option value="Связаться">Связаться</option>
                        <option value="Получить консультацию">Получить консультацию</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="button-link-select" class="form-label">Ссылка</label>
                    <select class="form-select" id="button-link-select">
                        <option value="">Выберите действие</option>
                        <option value="#contact">Контакты</option>
                        <option value="#order">Форма заказа</option>
                        <option value="tel:+7">Позвонить</option>
                        <option value="mailto:info@example.com">Написать email</option>
                        <option value="https://wa.me/">WhatsApp</option>
                        <option value="https://t.me/">Telegram</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary" onclick="applyButtonSettings()">Применить</button>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="loading-overlay">
    <div class="loading-content">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Загрузка...</span>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
// Инициализация страницы редактирования услуг с данными из сервера
document.addEventListener('DOMContentLoaded', function() {
    const initialState = {
        title: '{{ $service->title }}',
        description: '{{ $service->description }}',
        price: '{{ $service->price ?? "" }}',
        priceType: '{{ $service->price_type ?? "fixed" }}',
        hasPrice: {{ $service->price ? 'true' : 'false' }},
        buttonText: '{{ $service->button_text }}',
        buttonLink: '{{ $service->button_link }}',
        orderIndex: '{{ $service->order_index ?? 1 }}'
    };
    
    // Инициализируем страницы услуг
    initializeSwiper();
    bindEvents();
    
    // Загружаем старые значения если есть ошибки валидации
    @if(old())
        const oldValues = @json(old());
        Object.keys(oldValues).forEach(key => {
            const element = document.querySelector(`[data-field="${key}"]`);
            if (element && oldValues[key]) {
                element.textContent = oldValues[key];
            }
        });
    @endif
    
    // Показываем ошибки если они есть
    @if($errors->any())
        const errorMessages = @json($errors->all());
        if (errorMessages.length > 0) {
            showNotification('Исправьте ошибки:\n' + errorMessages.join('\n'), 'error');
        }
    @endif
});
</script>
  
@endsection
