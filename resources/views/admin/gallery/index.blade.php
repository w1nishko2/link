@extends('admin.layout')

@section('title', 'Управление галереей - ' . config('app.name'))
@section('description', 'Управление изображениями галереи: загрузка, редактирование, организация')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
     <a href="{{ route('admin.gallery.create', $currentUserId) }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>
        <span class="d-none d-sm-inline">Добавить изображение</span>
        <span class="d-sm-none">Добавить</span>
    </a>
</div>

@if($images->count() > 0)
    <!-- Сетка изображений 2x2 -->
    <div class="gallery-grid">
        @foreach($images as $image)
            <div class="gallery-item">
                <div class="gallery-image-wrapper" data-image-id="{{ $image->id }}">
                    <div class="image-container">
                        @if($image->image_path)
                            <img src="{{ asset('storage/' . $image->image_path) }}" 
                                 class="gallery-image" 
                                 alt="{{ $image->alt_text ?? $image->title }}">
                        @else
                            <div class="gallery-image placeholder-image d-flex align-items-center justify-content-center bg-light text-muted">
                                <i class="bi bi-image display-4"></i>
                            </div>
                        @endif
                        
                        <!-- Скрытая кнопка удаления -->
                        <div class="action-buttons">
                            <button type="button" 
                                    class="btn btn-danger btn-sm action-btn delete-btn" 
                                    onclick="deleteImage({{ $image->id }})"
                                    title="Удалить">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-5">
        <i class="bi bi-images display-1 text-muted"></i>
        <h3 class="mt-3">Галерея пуста</h3>
        <p class="text-muted">Добавьте первое изображение в галерею</p>
        <a href="{{ route('admin.gallery.create', $currentUserId) }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>
            Добавить изображение
        </a>
    </div>
@endif

@endsection

@section('styles')
<style>
/* Основные стили для сетки галереи */
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    margin-bottom: 2rem;
}

/* Стили для элементов галереи */
.gallery-item {
    width: 100%;
    aspect-ratio: 1 / 1; /* Строго квадратное соотношение */
}

.gallery-image-wrapper {
    width: 100%;
    height: 100%;
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    background: #f8f9fa;
}

/* Контейнер изображения строго 1:1 */
.image-container {
    position: relative;
    width: 100%;
    height: 100%;
    overflow: hidden;
}

.gallery-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    display: block;
    user-select: none;
    -webkit-user-select: none;
    transition: transform 0.3s ease;
}

.placeholder-image {
    width: 100%;
    height: 100%;
}

/* Анимация при нажатии */
.gallery-image-wrapper.pressed .gallery-image {
    transform: scale(0.95);
}

/* Кнопка действия - скрыта по умолчанию */
.action-buttons {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    display: flex;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    z-index: 10;
}

/* Показываем кнопки при долгом нажатии */
.gallery-image-wrapper.show-actions .action-buttons {
    opacity: 1;
    visibility: visible;
}

/* Затемнение фона при показе кнопок */
.gallery-image-wrapper.show-actions::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.6);
    z-index: 5;
    transition: all 0.3s ease;
}

/* Стили кнопок */
.action-btn {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    border: none;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    transition: all 0.2s ease;
    cursor: pointer;
}

.action-btn:hover {
    transform: scale(1.1);
}

.action-btn:active {
    transform: scale(0.95);
}

.delete-btn {
    background: #dc3545;
    color: white;
}

.delete-btn:hover {
    background: #c82333;
}

/* Адаптивность для планшетов */
@media (max-width: 991.98px) {
    .gallery-grid {
        gap: 12px;
    }
    
    .action-btn {
        width: 40px;
        height: 40px;
        font-size: 16px;
    }
}

/* Адаптивность для мобильных устройств */
@media (max-width: 575.98px) {
    .gallery-grid {
        gap: 10px;
    }
    
    .action-btn {
        width: 35px;
        height: 35px;
        font-size: 14px;
    }
}

/* Адаптивность для очень маленьких экранов */
@media (max-width: 375px) {
    .gallery-grid {
        gap: 8px;
    }
}

/* Анимация появления кнопок */
@keyframes fadeInScale {
    from {
        opacity: 0;
        transform: translate(-50%, -50%) scale(0.8);
    }
    to {
        opacity: 1;
        transform: translate(-50%, -50%) scale(1);
    }
}

.gallery-image-wrapper.show-actions .action-buttons {
    animation: fadeInScale 0.3s ease forwards;
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Переменные для отслеживания долгого нажатия
    let touchTimeout;
    let currentActiveWrapper = null;
    const LONG_PRESS_DELAY = 500; // 500ms для долгого нажатия

    // Получаем все обертки изображений
    const imageWrappers = document.querySelectorAll('.gallery-image-wrapper');

    imageWrappers.forEach(wrapper => {
        let isLongPress = false;
        let touchStartTime = 0;

        // Touch события для мобильных устройств
        wrapper.addEventListener('touchstart', function(e) {
            e.preventDefault();
            touchStartTime = Date.now();
            isLongPress = false;
            
            // Добавляем класс для анимации нажатия
            wrapper.classList.add('pressed');
            
            // Устанавливаем таймер для долгого нажатия
            touchTimeout = setTimeout(() => {
                isLongPress = true;
                showActionButtons(wrapper);
                
                // Добавляем вибрацию если поддерживается
                if (navigator.vibrate) {
                    navigator.vibrate(50);
                }
            }, LONG_PRESS_DELAY);
        });

        wrapper.addEventListener('touchend', function(e) {
            e.preventDefault();
            const touchDuration = Date.now() - touchStartTime;
            
            // Убираем класс анимации нажатия
            wrapper.classList.remove('pressed');
            
            // Отменяем таймер если он еще активен
            clearTimeout(touchTimeout);
            
            // Если это не долгое нажатие - переходим к редактированию
            if (!isLongPress && touchDuration < LONG_PRESS_DELAY) {
                hideActionButtons();
                const imageId = wrapper.dataset.imageId;
                editImage(imageId);
            }
        });

        wrapper.addEventListener('touchmove', function(e) {
            // Отменяем долгое нажатие при движении пальца
            clearTimeout(touchTimeout);
            wrapper.classList.remove('pressed');
        });

        // Mouse события для десктопа (долгое нажатие мышью)
        wrapper.addEventListener('mousedown', function(e) {
            e.preventDefault();
            touchStartTime = Date.now();
            isLongPress = false;
            
            wrapper.classList.add('pressed');
            
            touchTimeout = setTimeout(() => {
                isLongPress = true;
                showActionButtons(wrapper);
            }, LONG_PRESS_DELAY);
        });

        wrapper.addEventListener('mouseup', function(e) {
            e.preventDefault();
            const pressDuration = Date.now() - touchStartTime;
            
            wrapper.classList.remove('pressed');
            clearTimeout(touchTimeout);
            
            // Если это не долгое нажатие - переходим к редактированию
            if (!isLongPress && pressDuration < LONG_PRESS_DELAY) {
                hideActionButtons();
                const imageId = wrapper.dataset.imageId;
                editImage(imageId);
            }
        });

        wrapper.addEventListener('mouseleave', function(e) {
            clearTimeout(touchTimeout);
            wrapper.classList.remove('pressed');
        });
    });

    // Функция показа кнопок действий
    function showActionButtons(wrapper) {
        // Скрываем кнопки у других изображений
        hideActionButtons();
        
        // Показываем кнопки для текущего изображения
        wrapper.classList.add('show-actions');
        currentActiveWrapper = wrapper;
    }

    // Функция скрытия кнопок действий
    function hideActionButtons() {
        if (currentActiveWrapper) {
            currentActiveWrapper.classList.remove('show-actions');
            currentActiveWrapper = null;
        }
        
        // Скрываем все кнопки на всякий случай
        imageWrappers.forEach(wrapper => {
            wrapper.classList.remove('show-actions');
        });
    }

    // Скрываем кнопки при клике вне изображений
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.gallery-image-wrapper')) {
            hideActionButtons();
        }
    });

    // Скрываем кнопки при прокрутке
    window.addEventListener('scroll', function() {
        hideActionButtons();
    });
});

// Функция для редактирования изображения
function editImage(imageId) {
    window.location.href = "{{ route('admin.gallery.edit', [$currentUserId, ':id']) }}".replace(':id', imageId);
}

// Функция для удаления изображения
function deleteImage(imageId) {
    if (confirm('Вы уверены, что хотите удалить это изображение? Это действие нельзя отменить.')) {
        const form = document.getElementById('deleteImageForm');
        const url = "{{ route('admin.gallery.destroy', [$currentUserId, ':id']) }}".replace(':id', imageId);
        form.action = url;
        form.submit();
    }
}
</script>

<!-- Form для удаления изображения -->
<form id="deleteImageForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection
