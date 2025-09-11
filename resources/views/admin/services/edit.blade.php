@extends('admin.layout')

@section('title', 'Редактирование услуги - ' . config('app.name'))
@section('description', 'Редактирование описания и настроек услуги')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Редактировать услугу</h1>
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
                                <label for="order_index" class="form-label">Порядок отображения</label>
                                <input type="number" class="form-control @error('order_index') is-invalid @enderror" 
                                       id="order_index" name="order_index" value="{{ old('order_index', $service->order_index) }}">
                                @error('order_index')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Описание услуги *</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4" required maxlength="500">{{ old('description', $service->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Максимум 500 символов. Осталось: <span id="description-counter">500</span></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">Цена (₽)</label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price', $service->price) }}" min="0" step="0.01">
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
                                    <option value="fixed" {{ old('price_type', $service->price_type) == 'fixed' ? 'selected' : '' }}>Фиксированная цена</option>
                                    <option value="hourly" {{ old('price_type', $service->price_type) == 'hourly' ? 'selected' : '' }}>За час</option>
                                    <option value="project" {{ old('price_type', $service->price_type) == 'project' ? 'selected' : '' }}>За проект</option>
                                </select>
                                @error('price_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Изображение услуги</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if($service->image_path)
                            <div class="mt-2">
                                <small class="text-muted">Текущее изображение:</small><br>
                                <img src="{{ asset('storage/' . $service->image_path) }}" 
                                     alt="{{ $service->title }}" class="img-thumbnail mt-1" style="max-height: 100px;">
                            </div>
                        @endif
                        <div class="form-text">Поддерживаются изображения в любых форматах. Автоматически конвертируется в WebP с оптимизацией размера. Оставьте пустым, чтобы сохранить текущее изображение.</div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                   {{ old('is_active', $service->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Активная услуга (отображается на сайте)
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>
                            Обновить услугу
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
                <div class="d-grid gap-2">
                    <a href="{{ route('user.page', auth()->user()->username) }}" class="btn btn-outline-primary btn-sm" target="_blank">
                        <i class="bi bi-eye me-2"></i>
                        Посмотреть на сайте
                    </a>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteService({{ $service->id }})">
                        <i class="bi bi-trash me-2"></i>
                        Удалить услугу
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
