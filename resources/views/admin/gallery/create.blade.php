@extends('admin.layout')

@section('title', 'Создание изображения - ' . config('app.name'))
@section('description', 'Добавление нового изображения в галерею')

<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<style>
.image-buttons {
    display: flex;
    gap: 8px;
    align-items: center;
    flex-wrap: wrap;
}

.add-metadata-button {
    white-space: nowrap;
    font-size: 12px;
    padding: 4px 8px;
}

.image-bottom {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-top: 12px;
}

.image-metadata {
    display: none;
}

.image-buttons {
    display: flex;
    justify-content: space-between;
}

/* Стили для редактируемых элементов */
.editable-title, .editable-alt-text {
    background: transparent;
    border: 1px dashed transparent;
    padding: 4px 8px;
    border-radius: 4px;
    transition: all 0.3s ease;
    cursor: text;
}

.editable-title:focus, .editable-alt-text:focus {
    border-color: #007bff;
    background: rgba(0, 123, 255, 0.1);
    outline: none;
}

.editable-title:hover, .editable-alt-text:hover {
    border-color: #dee2e6;
}

/* Убираем стандартные стили contenteditable */
.editable-title[contenteditable="true"]:empty:before,
.editable-alt-text[contenteditable="true"]:empty:before {
    content: attr(data-placeholder);
    color: #6c757d;
    font-style: italic;
}

/* Плавные переходы для показа/скрытия элементов */
.image-metadata, .add-metadata-button {
    transition: all 0.3s ease;
}

.image-metadata[style*="display: none"] {
    opacity: 0;
    transform: translateY(-10px);
}

.image-metadata[style*="display: block"] {
    opacity: 1;
    transform: translateY(0);
}

/* Адаптация для мобильных */
@media (max-width: 768px) {
    .image-buttons {
        flex-direction: column;
        align-items: stretch;
        gap: 8px;
    }
    
    .add-metadata-button {
        align-self: flex-start;
    }
}

.image-no-image {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 2px dashed #adb5bd;
    border-radius: 12px;
    min-height: 300px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.image-no-image:hover {
    border-color: #007bff;
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
}

.image-no-image p {
    margin: 0;
    color: #6c757d;
    font-size: 0.9rem;
    text-align: center;
}

/* Индикатор загрузки */
#loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.95);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
}

.loading-spinner {
    text-align: center;
    padding: 2rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
}

.loading-text {
    color: #6c757d;
    font-size: 0.9rem;
    margin: 0;
}

.spinner-border {
    width: 3rem;
    height: 3rem;
}
</style>

@section('content')
<!-- Скрытая форма для отправки данных -->
<form id="image-form" action="{{ route('admin.gallery.store', $currentUserId) }}" method="POST" enctype="multipart/form-data" style="display: none;">
    @csrf
    <input type="hidden" name="title" id="hidden-title">
    <input type="hidden" name="alt_text" id="hidden-alt-text">
    <input type="hidden" name="order_index" id="hidden-order-index">
    <input type="file" name="image" id="hidden-image" accept="image/*">
</form>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="swiper edit-swiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class=" h-100" style="min-height: 500px;">
                        <!-- Область изображения -->
                        <div class="image-container" style="height: 300px; position: relative;">
                            <div id="image-preview" class="image-no-image w-100 h-100" onclick="selectImage()">
                                <i class="bi bi-image display-1 text-muted mb-3"></i>
                                <p><strong>Нажмите для выбора изображения</strong></p>
                                <p>Поддерживаются: JPG, PNG, GIF, SVG, WebP</p>
                                <p>Максимальный размер: 10 МБ</p>
                            </div>
                        </div>
                        
                        <!-- Содержимое карточки -->
                        <div class="card-body d-flex flex-column">
                            <!-- Заголовок изображения -->
                            <h6 class="card-title mb-2">
                                <span class="editable-title" 
                                      contenteditable="true" 
                                      data-placeholder="Название изображения..."
                                      onclick="selectText(this)"
                                      onblur="updateHiddenFields()"
                                      onkeyup="updateHiddenFields()">
                                </span>
                            </h6>
                            
                            <!-- Alt-текст -->
                            <div id="metadata-section" class="image-metadata">
                                <p class="card-text text-muted small mb-2">
                                    Alt-текст: 
                                    <span class="editable-alt-text" 
                                          contenteditable="true" 
                                          data-placeholder="Описание для поисковых систем..."
                                          onclick="selectText(this)"
                                          onblur="updateHiddenFields()"
                                          onkeyup="updateHiddenFields()">
                                    </span>
                                </p>
                            </div>
                            
                            <!-- Область кнопок -->
                            <div class="image-bottom mt-auto">
                                <div class="image-buttons">
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-secondary add-metadata-button" 
                                            onclick="addMetadataInCard()">
                                        <i class="bi bi-plus"></i> Добавить описание
                                    </button>
                                    
                                    <div class="d-flex gap-2 " style="width: 100%">
                                        <!-- Дополнительные настройки -->
                                        <button type="button" 
                                                class="btn btn-sm " 
                                                onclick="toggleAdvanced()"
                                                title="Дополнительные настройки">
                                            <i class="bi bi-gear"></i>
                                        </button>
                                        
                                        <!-- Кнопка сохранения -->
                                        <button type="button" 
                                                class="btn btn-sm btn-success" 
                                                onclick="saveImage()"
                                                disabled
                                                id="save-button">
                                            <i class="bi bi-check"></i> Сохранить
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Дополнительные настройки -->
<div id="advanced-settings" style="display: none;" class="mt-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-gear me-2"></i>
                        Дополнительные настройки
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="order-index" class="form-label">Порядок сортировки</label>
                        <input type="number" 
                               class="form-control" 
                               id="order-index" 
                               placeholder="Оставьте пустым для автоматического порядка"
                               onchange="updateHiddenFields()">
                        <div class="form-text">Определяет порядок отображения в галерее</div>
                    </div>
                </div>
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
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
@vite(['resources/js/admin-images.js', 'resources/js/upload-progress.js'])

<script>
// Глобальные переменные
let editSwiper;
let selectedImageFile = null;

// Состояние формы
const formState = {
    title: '',
    alt_text: '',
    order_index: '',
    image: null,
    hasMetadata: false,
    advancedOpen: false
};

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    initializeSwiper();
    bindEvents();
    loadOldValues();
});

// Инициализация Swiper
function initializeSwiper() {
    editSwiper = new Swiper('.edit-swiper', {
        slidesPerView: 1,
        spaceBetween: 0,
        allowTouchMove: false,
        autoHeight: true
    });
}

// Привязка событий
function bindEvents() {
    // Обработчики для редактируемых полей
    document.querySelectorAll('[contenteditable="true"]').forEach(element => {
        element.addEventListener('paste', function(e) {
            e.preventDefault();
            const text = (e.originalEvent || e).clipboardData.getData('text/plain');
            document.execCommand('insertText', false, text);
            updateHiddenFields();
        });
        
        element.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.blur();
            }
        });
        
        element.addEventListener('input', updateHiddenFields);
    });
    
    // Глобальные горячие клавиши
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            saveImage();
        }
        
        if (e.key === 'Escape') {
            // Убираем фокус с активного элемента
            if (document.activeElement) {
                document.activeElement.blur();
            }
        }
    });
}

// Загрузка старых значений (если есть ошибки валидации)
function loadOldValues() {
    @if(old('title'))
        document.querySelector('.editable-title').textContent = '{{ old('title') }}';
        formState.title = '{{ old('title') }}';
    @endif
    
    @if(old('alt_text'))
        document.querySelector('.editable-alt-text').textContent = '{{ old('alt_text') }}';
        formState.alt_text = '{{ old('alt_text') }}';
        addMetadataInCard();
    @endif
    
    @if(old('order_index'))
        document.getElementById('order-index').value = '{{ old('order_index') }}';
        formState.order_index = '{{ old('order_index') }}';
        toggleAdvanced();
    @endif
    
    updateHiddenFields();
}

// Выбор изображения
function selectImage() {
    document.getElementById('hidden-image').click();
}

// Валидация файла изображения
function validateImageFile(file) {
    // Проверка типа файла
    const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/svg+xml', 'image/webp'];
    if (!allowedTypes.includes(file.type)) {
        alert('Неподдерживаемый формат файла. Разрешены: JPG, PNG, GIF, SVG, WebP');
        resetImageInput();
        return false;
    }
    
    // Проверка размера файла (10 МБ)
    if (file.size > 10 * 1024 * 1024) {
        alert('Размер файла не должен превышать 10 МБ');
        resetImageInput();
        return false;
    }
    
    return true;
}

// Сброс поля выбора файла
function resetImageInput() {
    document.getElementById('hidden-image').value = '';
    selectedImageFile = null;
    document.getElementById('save-button').disabled = true;
    
    // Возвращаем исходное состояние превью
    const preview = document.getElementById('image-preview');
    preview.innerHTML = `
        <i class="bi bi-image display-1 text-muted mb-3"></i>
        <p><strong>Нажмите для выбора изображения</strong></p>
        <p>Поддерживаются: JPG, PNG, GIF, SVG, WebP</p>
        <p>Максимальный размер: 10 МБ</p>
    `;
    preview.className = 'image-no-image w-100 h-100';
    preview.onclick = selectImage;
}

// Обработка выбора изображения
document.getElementById('hidden-image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        selectedImageFile = file;
        formState.image = file;
        
        // Валидируем файл
        if (!validateImageFile(file)) {
            if (window.showUploadError) {
                window.showUploadError('Неподдерживаемый тип файла или слишком большой размер');
            }
            return;
        }
        
        // Показываем индикатор загрузки превью
        const preview = document.getElementById('image-preview');
        preview.innerHTML = `
            <div class="d-flex justify-content-center align-items-center h-100">
                <div class="text-center">
                    <div class="spinner-border text-primary mb-2" role="status">
                        <span class="visually-hidden">Загрузка...</span>
                    </div>
                    <div class="small text-muted">Обработка изображения...</div>
                </div>
            </div>
        `;
        
        // Создаем оптимизированное превью
        if (window.createOptimizedPreview) {
            window.createOptimizedPreview(file, null, function(file, optimizedDataUrl) {
                preview.innerHTML = `<img src="${optimizedDataUrl}" class="w-100 h-100" style="object-fit: cover; border-radius: 12px;" alt="Превью">`;
                preview.onclick = selectImage; // Сохраняем возможность выбрать другое изображение
            });
        } else {
            // Fallback для обычного превью
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" class="w-100 h-100" style="object-fit: cover; border-radius: 12px;" alt="Превью">`;
                preview.onclick = selectImage; // Сохраняем возможность выбрать другое изображение
            };
            reader.readAsDataURL(file);
        }
        
        // Активируем кнопку сохранения
        document.getElementById('save-button').disabled = false;
        
        // Если нет названия, предлагаем название из имени файла
        const titleElement = document.querySelector('.editable-title');
        if (!titleElement.textContent.trim()) {
            const fileName = file.name.replace(/\.[^/.]+$/, ""); // Убираем расширение
            titleElement.textContent = fileName;
            updateHiddenFields();
        }
        
        updateHiddenFields();
    }
});

// Выделение текста при клике
function selectText(element) {
    const range = document.createRange();
    range.selectNodeContents(element);
    const sel = window.getSelection();
    sel.removeAllRanges();
    sel.addRange(range);
}

// Добавление метаданных прямо в карточке
function addMetadataInCard() {
    const metadataSection = document.getElementById('metadata-section');
    const addButton = document.querySelector('.add-metadata-button');
    
    if (metadataSection.style.display === 'none' || !metadataSection.style.display) {
        metadataSection.style.display = 'block';
        addButton.innerHTML = '<i class="bi bi-dash"></i> Убрать описание';
        addButton.onclick = function() {
            metadataSection.style.display = 'none';
            addButton.innerHTML = '<i class="bi bi-plus"></i> Добавить описание';
            addButton.onclick = addMetadataInCard;
            formState.hasMetadata = false;
        };
        formState.hasMetadata = true;
    }
}

// Переключение дополнительных настроек
function toggleAdvanced() {
    const advancedSettings = document.getElementById('advanced-settings');
    
    if (advancedSettings.style.display === 'none' || !advancedSettings.style.display) {
        advancedSettings.style.display = 'block';
        formState.advancedOpen = true;
    } else {
        advancedSettings.style.display = 'none';
        formState.advancedOpen = false;
    }
}

// Обновление скрытых полей формы
function updateHiddenFields() {
    document.getElementById('hidden-title').value = document.querySelector('.editable-title').textContent.trim();
    document.getElementById('hidden-alt-text').value = document.querySelector('.editable-alt-text').textContent.trim();
    document.getElementById('hidden-order-index').value = document.getElementById('order-index').value;
}

// Валидация и сохранение
function saveImage() {
    updateHiddenFields();
    
    if (!selectedImageFile) {
        alert('Пожалуйста, выберите изображение');
        return;
    }
    
    // Валидация размера файла (10 МБ)
    if (selectedImageFile.size > 10 * 1024 * 1024) {
        alert('Размер файла не должен превышать 10 МБ');
        return;
    }
    
    // Показываем прогресс-бар
    const progressInterval = window.simulateUploadProgress ? 
        window.simulateUploadProgress(selectedImageFile.size > 1024 * 1024 ? 4000 : 2500) : null;
    
    // Показываем индикатор загрузки
    showLoading();
    
    // Создаем FormData для правильной отправки файла
    const formData = new FormData();
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
    formData.append('_token', csrfToken);
    formData.append('title', document.querySelector('.editable-title').textContent.trim());
    formData.append('alt_text', document.querySelector('.editable-alt-text').textContent.trim());
    formData.append('order_index', document.getElementById('order-index').value);
    formData.append('image', selectedImageFile);
    
    // Отправляем через fetch
    fetch(document.getElementById('image-form').action, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (window.completeUploadProgress) {
            window.completeUploadProgress();
        }
        if (response.redirected) {
            // Если сервер отправил редирект, переходим туда
            window.location.href = response.url;
            return;
        }
        
        if (response.ok) {
            // Успешная отправка, перенаправляем на галерею
            const userId = '{{ $currentUserId }}';
            window.location.href = `{{ route('admin.gallery', ':userId') }}`.replace(':userId', userId);
        } else {
            throw new Error('Ошибка сервера: ' + response.status);
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Ошибка:', error);
        alert('Ошибка при сохранении изображения. Пожалуйста, попробуйте еще раз.');
    });
}

// Показать индикатор загрузки
function showLoading() {
    document.getElementById('loading-overlay').style.display = 'flex';
    document.getElementById('save-button').disabled = true;
}

// Скрыть индикатор загрузки
function hideLoading() {
    document.getElementById('loading-overlay').style.display = 'none';
    document.getElementById('save-button').disabled = false;
}

// Показ ошибок валидации
@if($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            let errorMessages = [];
            @foreach($errors->all() as $error)
                errorMessages.push('{{ $error }}');
            @endforeach
            alert('Ошибки валидации:\n' + errorMessages.join('\n'));
        }, 500);
    });
@endif
</script>

<style>
    .edit-swiper {
        height: auto !important;
    }
    
    .edit-swiper .swiper-slide {
        height: auto !important;
    }
}
</style>
@endsection