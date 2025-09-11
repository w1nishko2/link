@extends('admin.layout')

@section('title', 'Управление галереей - ' . config('app.name'))
@section('description', 'Управление изображениями галереи: загрузка, редактирование, организация')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
    <h1 class="h3 mb-0">Управление галереей</h1>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addImageModal">
        <i class="bi bi-plus-circle me-2"></i>
        <span class="d-none d-sm-inline">Добавить изображение</span>
        <span class="d-sm-none">Добавить</span>
    </button>
</div>

@if($images->count() > 0)
    <div class="row">
        @foreach($images as $image)
            <div class="col-6 col-md-4 col-lg-3 mb-4">
                <div class="card h-100">
                    <img src="{{ asset('storage/' . $image->image_path) }}" 
                         class="card-img-top" 
                         alt="{{ $image->alt_text ?: $image->title }}"
                         style="height: 150px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title">{{ Str::limit($image->title ?: 'Без названия', 20) }}</h6>
                        <p class="card-text small text-muted flex-grow-1">{{ Str::limit($image->alt_text, 40) }}</p>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">
                                {{ $image->order_index }}
                            </small>
                            <div>
                                @if($image->is_active)
                                    <span class="badge bg-success">Активно</span>
                                @else
                                    <span class="badge bg-secondary">Скрыто</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-grid gap-1">
                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editImageModal{{ $image->id }}">
                                <i class="bi bi-pencil me-1"></i>
                                <span class="d-none d-sm-inline">Редактировать</span>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                    onclick="deleteImage({{ $image->id }})">
                                <i class="bi bi-trash me-1"></i>
                                <span class="d-none d-sm-inline">Удалить</span>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Modal для редактирования -->
                <div class="modal fade" id="editImageModal{{ $image->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('admin.gallery.update', [$currentUserId, $image]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Редактировать изображение</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="title{{ $image->id }}" class="form-label">Название</label>
                                        <input type="text" class="form-control" 
                                               id="title{{ $image->id }}" 
                                               name="title" 
                                               value="{{ $image->title }}" maxlength="100">
                                        <div class="form-text">Максимум 100 символов</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="alt_text{{ $image->id }}" class="form-label">Alt текст</label>
                                        <input type="text" class="form-control" 
                                               id="alt_text{{ $image->id }}" 
                                               name="alt_text" 
                                               value="{{ $image->alt_text }}" maxlength="150">
                                        <div class="form-text">Максимум 150 символов</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="order_index{{ $image->id }}" class="form-label">Порядок</label>
                                        <input type="number" class="form-control" 
                                               id="order_index{{ $image->id }}" 
                                               name="order_index" 
                                               value="{{ $image->order_index }}">
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               id="is_active{{ $image->id }}" 
                                               name="is_active" 
                                               value="1" {{ $image->is_active ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active{{ $image->id }}">
                                            Активно
                                        </label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                                    <button type="submit" class="btn btn-primary">Сохранить</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    {{ $images->links() }}
@else
    <div class="text-center py-5">
        <i class="bi bi-images display-1 text-muted"></i>
        <h3 class="mt-3">Галерея пуста</h3>
        <p class="text-muted">Добавьте первое изображение в галерею</p>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addImageModal">
            <i class="bi bi-plus-circle me-2"></i>
            Добавить изображение
        </button>
    </div>
@endif

<!-- Modal для добавления изображения -->
<div class="modal fade" id="addImageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.gallery.store', $currentUserId) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Добавить изображение</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="image" class="form-label">Изображение *</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*" required>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Поддерживаются изображения в любых форматах. Автоматически конвертируется в WebP с оптимизацией размера.</div>
                    </div>
                    <div class="mb-3">
                        <label for="title" class="form-label">Название</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" maxlength="100">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Максимум 100 символов. Осталось: <span id="title-counter">100</span></div>
                    </div>
                    <div class="mb-3">
                        <label for="alt_text" class="form-label">Alt текст</label>
                        <input type="text" class="form-control @error('alt_text') is-invalid @enderror" 
                               id="alt_text" name="alt_text" value="{{ old('alt_text') }}" maxlength="150">
                        @error('alt_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Максимум 150 символов. Осталось: <span id="alt-text-counter">150</span></div>
                    </div>
                    <div class="mb-3">
                        <label for="order_index" class="form-label">Порядок</label>
                        <input type="number" class="form-control" 
                               id="order_index" name="order_index" value="{{ old('order_index') }}">
                        <div class="form-text">Оставьте пустым для автоматического порядка</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-primary">Добавить</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Form для удаления изображения -->
<form id="deleteImageForm" method="POST" style="display: none;">
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
    
    if (!input || !counter) return;
    
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
    setupCharCounter('alt_text', 'alt-text-counter', 150);
});

function deleteImage(imageId) {
    if (confirm('Вы уверены, что хотите удалить это изображение?')) {
        const form = document.getElementById('deleteImageForm');
        const url = "{{ route('admin.gallery.destroy', [$currentUserId, ':id']) }}".replace(':id', imageId);
        console.log('Отправка формы удаления на URL:', url);
        form.action = url;
        form.submit();
    }
}

// Показать модал добавления если есть ошибки
@if($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        new bootstrap.Modal(document.getElementById('addImageModal')).show();
    });
@endif
</script>
@endsection
