@extends('admin.layout')

@section('title', 'Редактирование услуги - ' . config('app.name'))
@section('description', 'Редактирование описания и настроек услуги')

@push('head')
<style>
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
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    
    <a href="{{ route('admin.services', $currentUserId) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>
        Назад к услугам
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Редактирование услуги</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.services.update', [$currentUserId, $service]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Первый ряд: Название и Порядок -->
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="title" class="form-label">Название услуги *</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title', $service->title) }}" required maxlength="100">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Максимум 100 символов. Осталось: <span id="title-counter">100</span></div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="order_index" class="form-label">Порядок</label>
                                <input type="number" class="form-control @error('order_index') is-invalid @enderror" 
                                       id="order_index" name="order_index" value="{{ old('order_index', $service->order_index) }}" placeholder="Авто">
                                @error('order_index')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Порядок показа</div>
                            </div>
                        </div>
                    </div>

                    <!-- Описание - полная ширина -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Описание услуги *</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" required maxlength="500">{{ old('description', $service->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Максимум 500 символов. Осталось: <span id="description-counter">500</span></div>
                    </div>

                    <!-- Второй ряд: Цена и Тип цены -->
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">Цена (₽)</label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price', $service->price) }}" min="0" step="0.01" placeholder="0">
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
                                    <option value="fixed" {{ old('price_type', $service->price_type) == 'fixed' ? 'selected' : '' }}>Фиксированная</option>
                                    <option value="hourly" {{ old('price_type', $service->price_type) == 'hourly' ? 'selected' : '' }}>За час</option>
                                    <option value="project" {{ old('price_type', $service->price_type) == 'project' ? 'selected' : '' }}>За проект</option>
                                </select>
                                @error('price_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Третий ряд: Изображение и Статус -->
                    <div class="row">
                        <div class="col-8">
                            <div class="mb-3">
                                <label for="image" class="form-label">Изображение услуги</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                       id="image" name="image" accept="image/*">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($service->image_path)
                                    <div class="mt-2">
                                        <small class="text-muted">Текущее:</small>
                                        <img src="{{ asset('storage/' . $service->image_path) }}" 
                                             alt="{{ $service->title }}" class="img-thumbnail mt-1" style="max-height: 60px;">
                                    </div>
                                @endif
                                <div class="form-text">WebP оптимизация</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label class="form-label">Статус</label>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                           {{ old('is_active', $service->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Активна
                                    </label>
                                </div>
                                <div class="form-text">Показывать на сайте</div>
                            </div>
                        </div>
                    </div>

                    <!-- Четвертый ряд: Настройки кнопки действия -->
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="button_text" class="form-label">Текст кнопки</label>
                                <select class="form-select @error('button_text') is-invalid @enderror" 
                                        id="button_text" name="button_text">
                                    <option value="">Без кнопки</option>
                                    <option value="Заказать услугу" {{ old('button_text', $service->button_text) == 'Заказать услугу' ? 'selected' : '' }}>Заказать услугу</option>
                                    <option value="Связаться с нами" {{ old('button_text', $service->button_text) == 'Связаться с нами' ? 'selected' : '' }}>Связаться с нами</option>
                                    <option value="Узнать подробнее" {{ old('button_text', $service->button_text) == 'Узнать подробнее' ? 'selected' : '' }}>Узнать подробнее</option>
                                    <option value="Написать в WhatsApp" {{ old('button_text', $service->button_text) == 'Написать в WhatsApp' ? 'selected' : '' }}>Написать в WhatsApp</option>
                                    <option value="Написать в Telegram" {{ old('button_text', $service->button_text) == 'Написать в Telegram' ? 'selected' : '' }}>Написать в Telegram</option>
                                    <option value="Позвонить" {{ old('button_text', $service->button_text) == 'Позвонить' ? 'selected' : '' }}>Позвонить</option>
                                    <option value="Отправить email" {{ old('button_text', $service->button_text) == 'Отправить email' ? 'selected' : '' }}>Отправить email</option>
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
                                        <option value="tel:{{ $user->phone }}" {{ old('button_link', $service->button_link) == 'tel:' . $user->phone ? 'selected' : '' }}>
                                            Телефон: {{ $user->phone }}
                                        </option>
                                    @endif
                                    @if($user->email)
                                        <option value="mailto:{{ $user->email }}" {{ old('button_link', $service->button_link) == 'mailto:' . $user->email ? 'selected' : '' }}>
                                            Email: {{ $user->email }}
                                        </option>
                                    @endif
                                    @if($user->telegram_url)
                                        <option value="{{ $user->telegram_url }}" {{ old('button_link', $service->button_link) == $user->telegram_url ? 'selected' : '' }}>
                                            Telegram
                                        </option>
                                    @endif
                                    @if($user->whatsapp_url)
                                        <option value="{{ $user->whatsapp_url }}" {{ old('button_link', $service->button_link) == $user->whatsapp_url ? 'selected' : '' }}>
                                            WhatsApp
                                        </option>
                                    @endif
                                    @if($user->vk_url)
                                        <option value="{{ $user->vk_url }}" {{ old('button_link', $service->button_link) == $user->vk_url ? 'selected' : '' }}>
                                            VK
                                        </option>
                                    @endif
                                    @if($user->instagram_url)
                                        <option value="{{ $user->instagram_url }}" {{ old('button_link', $service->button_link) == $user->instagram_url ? 'selected' : '' }}>
                                            Instagram
                                        </option>
                                    @endif
                                    @if($user->website_url)
                                        <option value="{{ $user->website_url }}" {{ old('button_link', $service->button_link) == $user->website_url ? 'selected' : '' }}>
                                            Сайт
                                        </option>
                                    @endif
                                    @foreach($user->socialLinks as $socialLink)
                                        <option value="{{ $socialLink->url }}" {{ old('button_link', $service->button_link) == $socialLink->url ? 'selected' : '' }}>
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

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>
                            Обновить 
                        </button>
                        <a href="{{ route('admin.services', $currentUserId) }}" class="btn btn-outline-secondary">Отмена</a>
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
            <div class="card-body">
                <div class="service-preview">
                    <div class="service-image mb-3" style="height: 200px; background: #f8f9fa; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        @if($service->image_path)
                            <img src="{{ asset('storage/' . $service->image_path) }}" 
                                 alt="{{ $service->title }}" class="img-fluid rounded">
                        @else
                            <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                        @endif
                    </div>
                    <h5 class="service-title">{{ $service->title }}</h5>
                    <p class="service-description text-muted">{{ $service->description }}</p>
                    <div class="service-price">
                        <strong>{{ $service->formatted_price }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Действия</h5>
            </div>
            <div class="card-body">
                <div class="d-flex gap-2">
                    <a href="{{ route('user.page', auth()->user()->username) }}" class="btn  btn-sm" target="_blank">
                        <i class="bi bi-eye me-2"></i>
                        Посмотреть 
                    </a>
                    <button type="button" class="btn  btn-sm" onclick="deleteService({{ $service->id }})">
                        <i class="bi bi-trash me-2"></i>
                        Удалить 
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form для удаления -->
<form id="deleteServiceForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
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
            const previewDiv = document.querySelector('.service-image');
            previewDiv.innerHTML = '<img src="' + e.target.result + '" alt="Preview" class="img-fluid rounded">';
        };
        reader.readAsDataURL(file);
    }
});

function deleteService(serviceId) {
    if (confirm('Вы уверены, что хотите удалить эту услугу?')) {
        const form = document.getElementById('deleteServiceForm');
        form.action = "{{ route('admin.services.destroy', [$currentUserId, ':id']) }}".replace(':id', serviceId);
        form.submit();
    }
}
</script>
@endsection
