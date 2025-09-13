@extends('admin.layout')

@section('title', 'Создание услуги - ' . config('app.name'))
@section('description', 'Добавление новой услуги в каталог')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
    <h1 class="h3 mb-0">Добавить услугу</h1>
    <a href="{{ route('admin.services', $currentUserId) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>
        <span class="d-none d-sm-inline">Назад к услугам</span>
        <span class="d-sm-none">Назад</span>
    </a>
</div>

<div class="row">
    <div class="col-lg-8 order-2 order-lg-1">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Информация об услуге</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.services.store', $currentUserId) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Название услуги *</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" required maxlength="100">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Максимум 100 символов. Осталось: <span id="title-counter">100</span></div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Описание *</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4" required maxlength="500">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Максимум 500 символов. Осталось: <span id="description-counter">500</span></div>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Изображение</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Рекомендуемый размер: 400x300px. Поддерживаются изображения в любых форматах. Автоматически конвертируется в WebP с оптимизацией размера.</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">Цена</label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price') }}" min="0" step="0.01">
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
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

                    <div class="mb-3">
                        <label for="order_index" class="form-label">Порядок отображения</label>
                        <input type="number" class="form-control @error('order_index') is-invalid @enderror" 
                               id="order_index" name="order_index" value="{{ old('order_index') }}">
                        @error('order_index')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Оставьте пустым для автоматического порядка</div>
                    </div>

                    <!-- Настройки кнопки действия -->
                    <div class="row">
                        <div class="col-md-6">
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
                        <div class="col-md-6">
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
                                <div class="form-text">Выберите куда будет вести кнопка</div>
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

    <div class="col-lg-4 order-1 order-lg-2 mb-4 mb-lg-0">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Предварительный просмотр</h5>
            </div>
            <div class="card-body">
                <div class="service-preview">
                    <div class="service-image mb-3">
                        <div class="bg-light d-flex align-items-center justify-content-center" 
                             style="height: 150px; border-radius: 8px;">
                            <i class="bi bi-image text-muted fs-1"></i>
                        </div>
                    </div>
                    <h6 class="service-title">Название услуги</h6>
                    <p class="service-description text-muted">Описание услуги будет отображаться здесь...</p>
                    <div class="service-price">
                        <strong>По договоренности</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Советы</h5>
            </div>
            <div class="card-body">
                <ul class="small">
                    <li>Используйте ясное и понятное название</li>
                    <li>Опишите ключевые преимущества услуги</li>
                    <li>Добавьте качественное изображение</li>
                    <li>Указывайте реалистичные цены</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
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

document.addEventListener('DOMContentLoaded', function() {
    setupCharCounter('title', 'title-counter', 100);
    setupCharCounter('description', 'description-counter', 500);
});

// Предварительный просмотр
document.getElementById('title').addEventListener('input', function() {
    document.querySelector('.service-title').textContent = this.value || 'Название услуги';
});

document.getElementById('description').addEventListener('input', function() {
    document.querySelector('.service-description').textContent = this.value || 'Описание услуги будет отображаться здесь...';
});

document.getElementById('price').addEventListener('input', updatePrice);
document.getElementById('price_type').addEventListener('change', updatePrice);

function updatePrice() {
    const price = document.getElementById('price').value;
    const priceType = document.getElementById('price_type').value;
    const priceElement = document.querySelector('.service-price strong');
    
    if (!price) {
        priceElement.textContent = 'По договоренности';
        return;
    }
    
    let formattedPrice = new Intl.NumberFormat('ru-RU').format(price) + ' ₽';
    
    switch (priceType) {
        case 'hourly':
            formattedPrice += '/час';
            break;
        case 'project':
            formattedPrice = 'от ' + formattedPrice;
            break;
    }
    
    priceElement.textContent = formattedPrice;
}

// Предварительный просмотр изображения
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const serviceImage = document.querySelector('.service-image > div');
            serviceImage.innerHTML = `<img src="${e.target.result}" style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px;">`;
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
