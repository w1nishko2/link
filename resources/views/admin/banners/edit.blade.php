@extends('admin.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Редактировать баннер</h1>
    <a href="{{ route('admin.banners', $currentUserId) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>
        Назад к баннерам
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Редактирование баннера</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.banners.update', [$currentUserId, $banner]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="title" class="form-label">Заголовок баннера *</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title', $banner->title) }}" required maxlength="100">
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
                                       id="order_index" name="order_index" value="{{ old('order_index', $banner->order_index) }}">
                                @error('order_index')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Описание</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" maxlength="300">{{ old('description', $banner->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Максимум 300 символов. Осталось: <span id="description-counter">300</span></div>
                    </div>

                    @if($banner->image_path)
                        <div class="mb-3">
                            <label class="form-label">Текущее изображение</label>
                            <div>
                                <img src="{{ asset('storage/' . $banner->image_path) }}" class="img-thumbnail" style="max-height: 150px;" alt="Текущее изображение">
                            </div>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="image" class="form-label">{{ $banner->image_path ? 'Новое изображение' : 'Изображение' }}</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            {{ $banner->image_path ? 'Оставьте пустым, чтобы сохранить текущее изображение' : 'Максимальный размер: 10MB' }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="link_url" class="form-label">Ссылка</label>
                        <input type="url" class="form-control @error('link_url') is-invalid @enderror" 
                               id="link_url" name="link_url" value="{{ old('link_url', $banner->link_url) }}" 
                               placeholder="https://example.com" maxlength="255">
                        @error('link_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Максимум 255 символов. Осталось: <span id="link-url-counter">255</span></div>
                    </div>

                    <div class="mb-3">
                        <label for="link_text" class="form-label">Текст ссылки</label>
                        <input type="text" class="form-control @error('link_text') is-invalid @enderror" 
                               id="link_text" name="link_text" value="{{ old('link_text', $banner->link_text) }}" 
                               placeholder="Перейти" maxlength="50">
                        @error('link_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Максимум 50 символов. Осталось: <span id="link-text-counter">50</span></div>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input @error('is_active') is-invalid @enderror" 
                               type="checkbox" value="1" id="is_active" name="is_active" 
                               {{ old('is_active', $banner->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Активный баннер
                        </label>
                        @error('is_active')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>
                            Сохранить изменения
                        </button>
                        <a href="{{ route('admin.banners', $currentUserId) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg me-2"></i>
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
                <h6 class="card-title mb-0">Предварительный просмотр</h6>
            </div>
            <div class="card-body">
                <div id="preview" class="border rounded p-3 text-center">
                    @if($banner->image_path)
                        <img id="preview-image" src="{{ asset('storage/' . $banner->image_path) }}" class="img-fluid mb-2" alt="Предпросмотр">
                    @else
                        <div id="no-image" class="text-muted">
                            <i class="bi bi-image display-4"></i>
                            <p>Изображение не выбрано</p>
                        </div>
                    @endif
                    <h6 id="preview-title">{{ $banner->title }}</h6>
                    <p id="preview-description" class="text-muted small">{{ $banner->description }}</p>
                    @if($banner->link_url)
                        <a id="preview-link" href="{{ $banner->link_url }}" class="btn btn-sm btn-primary" target="_blank">
                            {{ $banner->link_text ?: 'Перейти' }}
                        </a>
                    @endif
                </div>
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
    
    input.addEventListener('input', updateCounter);
    updateCounter();
}

// Предварительный просмотр
function updatePreview() {
    const title = document.getElementById('title').value || 'Заголовок баннера';
    const description = document.getElementById('description').value || 'Описание баннера';
    const linkUrl = document.getElementById('link_url').value;
    const linkText = document.getElementById('link_text').value || 'Перейти';
    
    document.getElementById('preview-title').textContent = title;
    document.getElementById('preview-description').textContent = description;
    
    const linkElement = document.getElementById('preview-link');
    if (linkUrl) {
        if (!linkElement) {
            const link = document.createElement('a');
            link.id = 'preview-link';
            link.className = 'btn btn-sm btn-primary';
            link.target = '_blank';
            document.getElementById('preview').appendChild(link);
        }
        document.getElementById('preview-link').href = linkUrl;
        document.getElementById('preview-link').textContent = linkText;
    } else if (linkElement) {
        linkElement.remove();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    setupCharCounter('title', 'title-counter', 100);
    setupCharCounter('description', 'description-counter', 300);
    setupCharCounter('link_url', 'link-url-counter', 255);
    setupCharCounter('link_text', 'link-text-counter', 50);
    
    // Слушатели для предварительного просмотра
    ['title', 'description', 'link_url', 'link_text'].forEach(id => {
        document.getElementById(id).addEventListener('input', updatePreview);
    });
});

// Предварительный просмотр изображения
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewImage = document.getElementById('preview-image');
            const noImage = document.getElementById('no-image');
            
            if (previewImage) {
                previewImage.src = e.target.result;
            } else {
                if (noImage) noImage.remove();
                const img = document.createElement('img');
                img.id = 'preview-image';
                img.className = 'img-fluid mb-2';
                img.alt = 'Предпросмотр';
                img.src = e.target.result;
                document.getElementById('preview').prepend(img);
            }
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
