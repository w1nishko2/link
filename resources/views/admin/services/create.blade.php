@extends('admin.layout')

@section('title', 'Создание услуги - ' . config('app.name'))
@section('description', 'Добавление новой услуги в каталог')


<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
@vite(['resources/css/services-reels.css'])
<style>
/* Адаптация превью для админки */
.service-preview-container {
    min-height: 500px;
}

.service-preview-container .services-header h6 {
    font-size: 0.9rem;
    color: #6c757d;
}

/* Отключаем navigation для превью */
.service-preview-container .swiper-button-next,
.service-preview-container .swiper-button-prev {
    display: none;
}

.swiper.services-swiper {
    width: 100%;
    height: 600px;
}

/* Компактная форма */
@media (max-width: 768px) {
    .form-text {
        font-size: 0.7rem;
        margin-top: 0.2rem;
    }
    
    .mb-3 {
        margin-bottom: 0.75rem !important;
    }
    
    .card-body {
        padding: 0.75rem;
    }
}

/* Оптимизация пространства для мелких полей */
.form-label {
    margin-bottom: 0.2rem;
    font-weight: 500;
    font-size: 0.9rem;
}

/* Упрощенный текст подсказок */
.form-text {
    margin-top: 0.2rem;
    font-size: 0.75rem;
    color: #6c757d;
}

/* Компактные кнопки */
.btn-group .btn,
.d-flex .btn {
    padding: 0.5rem 1rem;
}

@media (max-width: 456px) {
    .card-header h5 {
        font-size: 0.95rem;
    }
    
    .col-lg-8 {
        margin-bottom: 0.75rem;
    }
    
    .form-label {
        font-size: 0.85rem;
        margin-bottom: 0.15rem;
    }
    
    .form-text {
        font-size: 0.7rem;
        margin-top: 0.15rem;
    }
    
    .mb-3 {
        margin-bottom: 0.6rem !important;
    }
    
    .card-body {
        padding: 0.5rem;
    }
    
    .form-control,
    .form-select {
        font-size: 0.9rem;
        padding: 0.4rem 0.75rem;
    }
}
</style>


@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
  
    <a href="{{ route('admin.services', $currentUserId) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>
        <span class="d-none d-sm-inline">Назад к услугам</span>
        <span class="d-sm-none">Назад</span>
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Информация об услуге</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.services.store', $currentUserId) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Название услуги - полная ширина -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Название услуги *</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" required maxlength="100">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Максимум 100 символов. Осталось: <span id="title-counter">100</span></div>
                    </div>

                    <!-- Описание - полная ширина -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Описание *</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" required maxlength="500">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Максимум 500 символов. Осталось: <span id="description-counter">500</span></div>
                    </div>

                    <!-- Первый ряд: Изображение и Порядок отображения -->
                    <div class="row">
                        <div class="col-8">
                            <div class="mb-3">
                                <label for="image" class="form-label">Изображение</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                       id="image" name="image" accept="image/*">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">400x300px, WebP оптимизация</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="order_index" class="form-label">Порядок</label>
                                <input type="number" class="form-control @error('order_index') is-invalid @enderror" 
                                       id="order_index" name="order_index" value="{{ old('order_index') }}" placeholder="Авто">
                                @error('order_index')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Порядок показа</div>
                            </div>
                        </div>
                    </div>

                    <!-- Второй ряд: Цена и Тип цены -->
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">Цена</label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price') }}" min="0" step="0.01" placeholder="0">
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="price_type" class="form-label">Тип цены *</label>
                                <select class="form-select @error('price_type') is-invalid @enderror" 
                                        id="price_type" name="price_type" required>
                                    <option value="fixed" {{ old('price_type') == 'fixed' ? 'selected' : '' }}>Фиксированная</option>
                                    <option value="hourly" {{ old('price_type') == 'hourly' ? 'selected' : '' }}>За час</option>
                                    <option value="project" {{ old('price_type') == 'project' ? 'selected' : '' }}>За проект</option>
                                </select>
                                @error('price_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Третий ряд: Настройки кнопки действия -->
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="button_text" class="form-label">Текст кнопки</label>
                                <select class="form-select @error('button_text') is-invalid @enderror" 
                                        id="button_text" name="button_text">
                                    <option value="">Без кнопки</option>
                                    <option value="Заказать услугу" {{ old('button_text') == 'Заказать услугу' ? 'selected' : '' }}>Заказать услугу</option>
                                    <option value="Связаться с нами" {{ old('button_text') == 'Связаться с нами' ? 'selected' : '' }}>Связаться с нами</option>
                                    <option value="Узнать подробнее" {{ old('button_text') == 'Узнать подробнее' ? 'selected' : '' }}>Узнать подробнее</option>
                                    <option value="Написать в WhatsApp" {{ old('button_text') == 'Написать в WhatsApp' ? 'selected' : '' }}>Написать в WhatsApp</option>
                                    <option value="Написать в Telegram" {{ old('button_text') == 'Написать в Telegram' ? 'selected' : '' }}>Написать в Telegram</option>
                                    <option value="Позвонить" {{ old('button_text') == 'Позвонить' ? 'selected' : '' }}>Позвонить</option>
                                    <option value="Отправить email" {{ old('button_text') == 'Отправить email' ? 'selected' : '' }}>Отправить email</option>
                                </select>
                                @error('button_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="button_link" class="form-label">Ссылка для кнопки</label>
                                <select class="form-select @error('button_link') is-invalid @enderror" 
                                        id="button_link" name="button_link">
                                    <option value="">Выберите ссылку</option>
                                    @if($user->phone)
                                        <option value="tel:{{ $user->phone }}" {{ old('button_link') == 'tel:' . $user->phone ? 'selected' : '' }}>
                                            Телефон: {{ $user->phone }}
                                        </option>
                                    @endif
                                    @if($user->email)
                                        <option value="mailto:{{ $user->email }}" {{ old('button_link') == 'mailto:' . $user->email ? 'selected' : '' }}>
                                            Email: {{ $user->email }}
                                        </option>
                                    @endif
                                    @if($user->telegram_url)
                                        <option value="{{ $user->telegram_url }}" {{ old('button_link') == $user->telegram_url ? 'selected' : '' }}>
                                            Telegram
                                        </option>
                                    @endif
                                    @if($user->whatsapp_url)
                                        <option value="{{ $user->whatsapp_url }}" {{ old('button_link') == $user->whatsapp_url ? 'selected' : '' }}>
                                            WhatsApp
                                        </option>
                                    @endif
                                    @if($user->vk_url)
                                        <option value="{{ $user->vk_url }}" {{ old('button_link') == $user->vk_url ? 'selected' : '' }}>
                                            VK
                                        </option>
                                    @endif
                                    @if($user->instagram_url)
                                        <option value="{{ $user->instagram_url }}" {{ old('button_link') == $user->instagram_url ? 'selected' : '' }}>
                                            Instagram
                                        </option>
                                    @endif
                                    @if($user->website_url)
                                        <option value="{{ $user->website_url }}" {{ old('button_link') == $user->website_url ? 'selected' : '' }}>
                                            Сайт
                                        </option>
                                    @endif
                                    @foreach($user->socialLinks as $socialLink)
                                        <option value="{{ $socialLink->url }}" {{ old('button_link') == $socialLink->url ? 'selected' : '' }}>
                                            {{ $socialLink->service_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('button_link')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Куда ведёт кнопка</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="bi bi-check-circle me-2"></i>
                            Сохранить услугу
                        </button>
                        <a href="{{ route('admin.services', $currentUserId) }}" class="btn btn-outline-secondary flex-fill">
                            Отмена
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Предварительный просмотр</h5>
            </div>
            <div class="card-body p-0">
                <!-- Предварительный просмотр услуги -->
                <div id="service-preview" class="service-preview-container">
                    <section class="services" aria-label="Предварительный просмотр услуги">
                        <div class="container-fluid p-3">
                            <header class="services-header mb-4 text-center">
                                <h6 class="text-muted mb-3">Так будет выглядеть ваша услуга:</h6>
                            </header>
                            
                            <div class="swiper services-swiper" id="preview-services-swiper">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <div class="service-card" id="preview-service-card">
                                            <div class="service-image" id="preview-service-image">
                                                <img id="preview-image" 
                                                     src="/hero.png" 
                                                     alt="Предварительный просмотр" 
                                                     loading="lazy"
                                                     width="300"
                                                     height="600"
                                                     decoding="async">
                                            </div>
                                            <div class="service-content">
                                                <h3 id="preview-title">Название услуги</h3>
                                                <p id="preview-description">Описание услуги будет отображаться здесь</p>
                                                <div class="service-bottom">
                                                    <div class="service-price" id="preview-price" style="display: none;"></div>
                                                    <a href="#" class="service-button btn btn-primary btn-sm" id="preview-button" style="display: none;">
                                                        Кнопка
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
// Счетчики символов
function setupCharCounter(inputId, counterId, maxLength) {
    const input = document.getElementById(inputId);
    const counter = document.getElementById(counterId);
    
    function updateCounter() {
        const currentLength = input.value.length;
        const remaining = maxLength - currentLength;
        counter.textContent = remaining;
        
        if (remaining < 0) {
            counter.style.color = '#dc3545';
        } else if (remaining < 20) {
            counter.style.color = '#fd7e14';
        } else {
            counter.style.color = '#6c757d';
        }
    }
    
    updateCounter();
    input.addEventListener('input', updateCounter);
    input.addEventListener('keydown', updateCounter);
    input.addEventListener('paste', function() {
        setTimeout(updateCounter, 10);
    });
}

// Функция форматирования цены
function formatPrice(price, priceType) {
    if (!price || price === '' || price === '0') {
        return '';
    }
    
    const numPrice = parseFloat(price);
    if (isNaN(numPrice)) return '';
    
    const formatted = new Intl.NumberFormat('ru-RU').format(numPrice);
    
    switch(priceType) {
        case 'hourly':
            return `${formatted} ₽/час`;
        case 'project':
            return `от ${formatted} ₽`;
        case 'fixed':
        default:
            return `${formatted} ₽`;
    }
}

// Функция обновления предварительного просмотра
function updatePreview() {
    const title = document.getElementById('title').value || 'Название услуги';
    const description = document.getElementById('description').value || 'Описание услуги будет отображаться здесь';
    const price = document.getElementById('price').value;
    const priceType = document.getElementById('price_type').value;
    const buttonText = document.getElementById('button_text').value;
    const buttonLink = document.getElementById('button_link').value;
    const imageInput = document.getElementById('image');
    
    // Обновляем текст
    document.getElementById('preview-title').textContent = title;
    document.getElementById('preview-description').textContent = description;
    
    // Обновляем цену
    const priceElement = document.getElementById('preview-price');
    const formattedPrice = formatPrice(price, priceType);
    
    if (formattedPrice) {
        priceElement.textContent = formattedPrice;
        priceElement.style.display = 'block';
    } else {
        priceElement.style.display = 'none';
    }
    
    // Обновляем кнопку
    const buttonElement = document.getElementById('preview-button');
    if (buttonText && buttonLink) {
        buttonElement.textContent = buttonText;
        buttonElement.href = buttonLink;
        buttonElement.style.display = 'inline-block';
        // Устанавливаем target для внешних ссылок
        if (buttonLink.startsWith('http')) {
            buttonElement.target = '_blank';
            buttonElement.rel = 'noopener noreferrer';
        } else {
            buttonElement.target = '_self';
            buttonElement.rel = '';
        }
    } else {
        buttonElement.style.display = 'none';
    }
    
    // Обновляем изображение при выборе файла
    if (imageInput.files && imageInput.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-image').src = e.target.result;
        }
        reader.readAsDataURL(imageInput.files[0]);
    }
}

let previewSwiper;

document.addEventListener('DOMContentLoaded', function() {
    setupCharCounter('title', 'title-counter', 100);
    setupCharCounter('description', 'description-counter', 500);
    
    // Инициализация Swiper для предварительного просмотра
    previewSwiper = new Swiper('#preview-services-swiper', {
        slidesPerView: 1,
        spaceBetween: 0,
        loop: false,
        allowTouchMove: false, // Отключаем свайпы в превью
        breakpoints: {
            320: {
                slidesPerView: 1,
                spaceBetween: 0,
            }
        }
    });
    
    // Добавляем обработчики событий для обновления предварительного просмотра
    document.getElementById('title').addEventListener('input', updatePreview);
    document.getElementById('description').addEventListener('input', updatePreview);
    document.getElementById('price').addEventListener('input', updatePreview);
    document.getElementById('price_type').addEventListener('change', updatePreview);
    document.getElementById('button_text').addEventListener('change', updatePreview);
    document.getElementById('button_link').addEventListener('change', updatePreview);
    document.getElementById('image').addEventListener('change', updatePreview);
    
    // Инициализируем предварительный просмотр
    updatePreview();
});
</script>
@endsection
