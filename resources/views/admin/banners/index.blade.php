@extends('admin.layout')

@section('title', 'Управление баннерами')
@section('description', 'Управление рекламными баннерами и промо-блоками')

@section('content')
<style>
.card {
    transition: transform 0.2s ease-in-out;
    border: 1px solid #dee2e6;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.btn-group .btn {
    flex: 1;
    font-size: 0.8rem;
    padding: 0.375rem 0.5rem;
    white-space: nowrap;
}
.card-footer {
    padding: 0.5rem;
    background-color: #f8f9fa;
}
.card-footer .btn-group {
    gap: 0;
    display: flex;
}
.card-footer .btn-group .btn:not(:last-child) {
    border-right: 0;
    margin-right: 0;
}
.card-footer .btn-group .btn:first-child {
    border-top-left-radius: 0.375rem;
    border-bottom-left-radius: 0.375rem;
}
.card-footer .btn-group .btn:last-child {
    border-top-right-radius: 0.375rem;
    border-bottom-right-radius: 0.375rem;
}
.card-footer .btn-group .btn:not(:first-child):not(:last-child) {
    border-radius: 0;
}
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Управление баннерами</h1>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBannerModal">
        <i class="bi bi-plus-lg"></i> Добавить баннер
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($banners->count() > 0)
    <div class="row">
        @foreach($banners as $banner)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    @if($banner->image_path)
                        <img src="{{ asset('storage/' . $banner->image_path) }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $banner->title }}">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                        </div>
                    @endif
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $banner->title }}</h5>
                        @if($banner->description)
                            <p class="card-text">{{ $banner->description }}</p>
                        @endif
                        
                        @if($banner->link_url)
                            <div class="mb-2">
                                <small class="text-muted">Ссылка:</small><br>
                                <a href="{{ $banner->link_url }}" target="_blank" class="text-primary small">
                                    {{ $banner->link_text ?: $banner->link_url }}
                                </a>
                            </div>
                        @endif
                        
                        <div class="mt-auto">
                            <small class="text-muted">
                                Порядок: {{ $banner->order_index ?? 0 }} | 
                                @if($banner->is_active)
                                    <span class="text-success">Активен</span>
                                @else
                                    <span class="text-danger">Неактивен</span>
                                @endif
                            </small>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-transparent">
                        <div class="btn-group w-100" role="group">
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="editBanner({{ $banner->id }})">
                                <i class="bi bi-pencil"></i> Быстрое редактирование
                            </button>
                            <a href="{{ route('admin.banners.edit', [$currentUserId, $banner]) }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-pencil-square"></i> Полное редактирование
                            </a>
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteBanner({{ $banner->id }})">
                                <i class="bi bi-trash"></i> Удалить
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Пагинация -->
    @if($banners->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $banners->links() }}
        </div>
    @endif
@else
    <div class="text-center py-5">
        <i class="bi bi-megaphone text-muted" style="font-size: 4rem;"></i>
        <h3 class="mt-3 text-muted">Нет баннеров</h3>
        <p class="text-muted">Добавьте первый баннер, чтобы он появился здесь.</p>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBannerModal">
            <i class="bi bi-plus-lg"></i> Добавить баннер
        </button>
    </div>
@endif

<!-- Модальное окно создания баннера -->
<div class="modal fade" id="createBannerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Создание баннера</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.banners.store', $currentUserId) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create_title" class="form-label">Заголовок баннера *</label>
                        <input type="text" class="form-control" id="create_title" name="title" required maxlength="100">
                        <div class="form-text">Максимум 100 символов. Осталось: <span id="create-title-counter">100</span></div>
                    </div>

                    <div class="mb-3">
                        <label for="create_description" class="form-label">Описание</label>
                        <textarea class="form-control" id="create_description" name="description" rows="3" maxlength="300"></textarea>
                        <div class="form-text">Максимум 300 символов. Осталось: <span id="create-description-counter">300</span></div>
                    </div>

                    <div class="mb-3">
                        <label for="create_image" class="form-label">Изображение баннера</label>
                        <input type="file" class="form-control" id="create_image" name="image" accept="image/*">
                        <div class="form-text">Поддерживаются изображения в форматах: JPEG, PNG, JPG, GIF, BMP, TIFF, WebP. Максимальный размер: 10MB. Все изображения автоматически конвертируются в WebP с оптимизацией размера.</div>
                    </div>

                    <div class="mb-3">
                        <label for="create_link_url" class="form-label">Ссылка</label>
                        <input type="url" class="form-control" id="create_link_url" name="link_url" placeholder="https://example.com" maxlength="255">
                        <div class="form-text">Максимум 255 символов. Осталось: <span id="create-link-url-counter">255</span></div>
                    </div>

                    <div class="mb-3">
                        <label for="create_link_text" class="form-label">Текст ссылки</label>
                        <input type="text" class="form-control" id="create_link_text" name="link_text" placeholder="Перейти" maxlength="50">
                        <div class="form-text">Максимум 50 символов. Осталось: <span id="create-link-text-counter">50</span></div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <label for="create_order_index" class="form-label">Порядок отображения</label>
                            <input type="number" class="form-control" id="create_order_index" name="order_index" value="0" min="0">
                        </div>
                        <div class="col-6 d-flex align-items-end">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="create_is_active" name="is_active" value="1" checked>
                                <label class="form-check-label" for="create_is_active">
                                    Активный баннер
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-primary">Создать баннер</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Модальное окно редактирования баннера -->
<div class="modal fade" id="editBannerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Редактирование баннера</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editBannerForm" action="" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_title" class="form-label">Заголовок баннера *</label>
                        <input type="text" class="form-control" id="edit_title" name="title" required maxlength="100">
                        <div class="form-text">Максимум 100 символов. Осталось: <span id="edit-title-counter">100</span></div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Описание</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3" maxlength="300"></textarea>
                        <div class="form-text">Максимум 300 символов. Осталось: <span id="edit-description-counter">300</span></div>
                    </div>

                    <div class="mb-3" id="current_image_block" style="display: none;">
                        <label class="form-label">Текущее изображение</label>
                        <div>
                            <img id="current_image" class="img-thumbnail" style="max-height: 100px;" alt="Текущее изображение">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_image" class="form-label">Новое изображение</label>
                        <input type="file" class="form-control" id="edit_image" name="image" accept="image/*">
                        <div class="form-text">Оставьте пустым, чтобы сохранить текущее изображение</div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_link_url" class="form-label">Ссылка</label>
                        <input type="url" class="form-control" id="edit_link_url" name="link_url" placeholder="https://example.com" maxlength="255">
                        <div class="form-text">Максимум 255 символов. Осталось: <span id="edit-link-url-counter">255</span></div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_link_text" class="form-label">Текст ссылки</label>
                        <input type="text" class="form-control" id="edit_link_text" name="link_text" placeholder="Перейти" maxlength="50">
                        <div class="form-text">Максимум 50 символов. Осталось: <span id="edit-link-text-counter">50</span></div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <label for="edit_order_index" class="form-label">Порядок отображения</label>
                            <input type="number" class="form-control" id="edit_order_index" name="order_index" min="0">
                        </div>
                        <div class="col-6 d-flex align-items-end">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="edit_is_active" name="is_active" value="1">
                                <label class="form-check-label" for="edit_is_active">
                                    Активный баннер
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Модальное окно подтверждения удаления -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Подтверждение удаления</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Вы уверены, что хотите удалить этот баннер? Это действие нельзя отменить.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <form id="deleteForm" action="" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Удалить</button>
                </form>
            </div>
        </div>
    </div>
</div>

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
    // Счетчики для формы создания
    setupCharCounter('create_title', 'create-title-counter', 100);
    setupCharCounter('create_description', 'create-description-counter', 300);
    setupCharCounter('create_link_url', 'create-link-url-counter', 255);
    setupCharCounter('create_link_text', 'create-link-text-counter', 50);
    
    // Счетчики для формы редактирования
    setupCharCounter('edit_title', 'edit-title-counter', 100);
    setupCharCounter('edit_description', 'edit-description-counter', 300);
    setupCharCounter('edit_link_url', 'edit-link-url-counter', 255);
    setupCharCounter('edit_link_text', 'edit-link-text-counter', 50);
});

// Данные баннеров для JavaScript
const banners = @json($banners->items());

function editBanner(bannerId) {
    const banner = banners.find(b => b.id === bannerId);
    if (!banner) return;

    // Заполняем форму
    document.getElementById('edit_title').value = banner.title || '';
    document.getElementById('edit_description').value = banner.description || '';
    document.getElementById('edit_link_url').value = banner.link_url || '';
    document.getElementById('edit_link_text').value = banner.link_text || '';
    document.getElementById('edit_order_index').value = banner.order_index || 0;
    document.getElementById('edit_is_active').checked = banner.is_active;

    // Обновляем счетчики после заполнения формы
    setTimeout(() => {
        setupCharCounter('edit_title', 'edit-title-counter', 100);
        setupCharCounter('edit_description', 'edit-description-counter', 300);
        setupCharCounter('edit_link_url', 'edit-link-url-counter', 255);
        setupCharCounter('edit_link_text', 'edit-link-text-counter', 50);
    }, 100);

    // Отображаем текущее изображение
    const currentImageBlock = document.getElementById('current_image_block');
    const currentImage = document.getElementById('current_image');
    if (banner.image_path) {
        currentImage.src = `/storage/${banner.image_path}`;
        currentImageBlock.style.display = 'block';
    } else {
        currentImageBlock.style.display = 'none';
    }

    // Устанавливаем action формы
    document.getElementById('editBannerForm').action = `/admin/user/{{ $currentUserId }}/banners/${bannerId}`;

    // Показываем модальное окно
    const modal = new bootstrap.Modal(document.getElementById('editBannerModal'));
    modal.show();
}

function deleteBanner(bannerId) {
    const form = document.getElementById('deleteForm');
    form.action = `/admin/user/{{ $currentUserId }}/banners/${bannerId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endsection
