

<?php $__env->startSection('title', 'Редактирование изображения - ' . config('app.name')); ?>
<?php $__env->startSection('description', 'Редактирование метаданных и замена изображения'); ?>

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

.image-current {
    background: #f8f9fa;
    border: 2px solid #dee2e6;
    border-radius: 12px;
    position: relative;
    cursor: pointer;
    transition: all 0.3s ease;
    overflow: hidden;
}

.image-current:hover {
    border-color: #007bff;
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.15);
}

.image-current .change-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.6);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: white;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.image-current:hover .change-overlay {
    opacity: 1;
}

.image-current .change-overlay i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.remove-image-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 10;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.image-current:hover .remove-image-btn {
    opacity: 1;
}
</style>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
    <h1 class="h4 mb-0">Редактирование изображения</h1>
    <a href="<?php echo e(route('admin.gallery', $currentUserId)); ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Назад к галерее
    </a>
</div>

<!-- Скрытая форма для отправки данных -->
<form id="image-form" action="<?php echo e(route('admin.gallery.update', [$currentUserId, $image])); ?>" method="POST" enctype="multipart/form-data" style="display: none;">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>
    <input type="hidden" name="title" id="hidden-title">
    <input type="hidden" name="alt_text" id="hidden-alt-text">
    <input type="hidden" name="order_index" id="hidden-order-index">
    <input type="hidden" name="is_active" id="hidden-is-active" value="1">
    <input type="file" name="image" id="hidden-image" accept="image/*">
</form>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="swiper edit-swiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="card h-100" style="min-height: 500px;">
                        <!-- Область изображения -->
                        <div class="image-container" style="height: 300px; position: relative;">
                            <div id="image-preview" class="image-current w-100 h-100" onclick="selectImage()">
                                <?php if($image->image_path): ?>
                                    <img src="<?php echo e(asset('storage/' . $image->image_path)); ?>" 
                                         class="w-100 h-100" 
                                         style="object-fit: cover; border-radius: 12px;" 
                                         alt="<?php echo e($image->alt_text ?? $image->title); ?>">
                                    
                                    <!-- Overlay для замены изображения -->
                                    <div class="change-overlay">
                                        <i class="bi bi-camera"></i>
                                        <span>Нажмите для замены</span>
                                    </div>
                                    
                                    <!-- Кнопка удаления изображения -->
                                    <button type="button" 
                                            class="btn btn-sm btn-danger remove-image-btn" 
                                            onclick="event.stopPropagation(); removeImage()"
                                            title="Удалить изображение">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                <?php else: ?>
                                    <div class="d-flex align-items-center justify-content-center h-100">
                                        <div class="text-center">
                                            <i class="bi bi-image display-1 text-muted mb-3"></i>
                                            <p><strong>Нажмите для выбора изображения</strong></p>
                                            <p>Поддерживаются: JPG, PNG, GIF, SVG, WebP</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
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
                                      onkeyup="updateHiddenFields()"><?php echo e($image->title); ?></span>
                            </h6>
                            
                            <!-- Alt-текст -->
                            <div id="metadata-section" class="image-metadata" style="<?php echo e($image->alt_text ? 'display: block;' : 'display: none;'); ?>">
                                <p class="card-text text-muted small mb-2">
                                    Alt-текст: 
                                    <span class="editable-alt-text" 
                                          contenteditable="true" 
                                          data-placeholder="Описание для поисковых систем..."
                                          onclick="selectText(this)"
                                          onblur="updateHiddenFields()"
                                          onkeyup="updateHiddenFields()"><?php echo e($image->alt_text); ?></span>
                                </p>
                            </div>
                            
                            <!-- Область кнопок -->
                            <div class="image-bottom mt-auto">
                                <div class="image-buttons">
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-secondary add-metadata-button" 
                                            onclick="addMetadataInCard()">
                                        <?php if($image->alt_text): ?>
                                            <i class="bi bi-dash"></i> Убрать описание
                                        <?php else: ?>
                                            <i class="bi bi-plus"></i> Добавить описание
                                        <?php endif; ?>
                                    </button>
                                    
                                    <div class="d-flex gap-2">
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
                               value="<?php echo e($image->order_index); ?>"
                               onchange="updateHiddenFields()">
                        <div class="form-text">Определяет порядок отображения в галерее</div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" 
                               type="checkbox" 
                               id="is-active" 
                               <?php echo e($image->is_active ? 'checked' : ''); ?>

                               onchange="updateHiddenFields()">
                        <label class="form-check-label" for="is-active">
                            <i class="bi bi-eye me-1"></i>
                            Активно (показывать в галерее)
                        </label>
                    </div>
                </div>
            </div>
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
                Вы уверены, что хотите удалить это изображение? Это действие нельзя отменить.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                    <i class="bi bi-trash"></i> Удалить
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Форма для удаления -->
<form id="deleteForm" action="<?php echo e(route('admin.gallery.destroy', [$currentUserId, $image])); ?>" method="POST" style="display: none;">
    <?php echo csrf_field(); ?>
    <?php echo method_field('DELETE'); ?>
</form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
// Глобальные переменные
let editSwiper;
let selectedImageFile = null;
let hasChanges = false;

// Состояние формы
const formState = {
    title: '<?php echo e($image->title); ?>',
    alt_text: '<?php echo e($image->alt_text); ?>',
    order_index: '<?php echo e($image->order_index); ?>',
    is_active: <?php echo e($image->is_active ? 'true' : 'false'); ?>,
    image: null,
    hasMetadata: <?php echo e($image->alt_text ? 'true' : 'false'); ?>,
    advancedOpen: false
};

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    initializeSwiper();
    bindEvents();
    loadOldValues();
    updateHiddenFields();
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
            hasChanges = true;
        });
        
        element.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.blur();
            }
        });
        
        element.addEventListener('input', function() {
            updateHiddenFields();
            hasChanges = true;
        });
    });
    
    // Глобальные горячие клавиши
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            saveImage();
        }
        
        if (e.key === 'Escape') {
            if (document.activeElement) {
                document.activeElement.blur();
            }
        }
    });

    // Предупреждение о несохраненных изменениях
    window.addEventListener('beforeunload', function(e) {
        if (hasChanges) {
            e.preventDefault();
            e.returnValue = '';
        }
    });
}

// Загрузка старых значений (если есть ошибки валидации)
function loadOldValues() {
    <?php if(old('title')): ?>
        document.querySelector('.editable-title').textContent = '<?php echo e(old('title')); ?>';
        formState.title = '<?php echo e(old('title')); ?>';
    <?php endif; ?>
    
    <?php if(old('alt_text')): ?>
        document.querySelector('.editable-alt-text').textContent = '<?php echo e(old('alt_text')); ?>';
        formState.alt_text = '<?php echo e(old('alt_text')); ?>';
        addMetadataInCard();
    <?php endif; ?>
    
    <?php if(old('order_index')): ?>
        document.getElementById('order-index').value = '<?php echo e(old('order_index')); ?>';
        formState.order_index = '<?php echo e(old('order_index')); ?>';
        toggleAdvanced();
    <?php endif; ?>
    
    updateHiddenFields();
}

// Выбор изображения
function selectImage() {
    document.getElementById('hidden-image').click();
}

// Обработка выбора изображения
document.getElementById('hidden-image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        selectedImageFile = file;
        formState.image = file;
        hasChanges = true;
        
        // Показываем превью
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('image-preview');
            preview.innerHTML = `
                <img src="${e.target.result}" 
                     class="w-100 h-100" 
                     style="object-fit: cover; border-radius: 12px;" 
                     alt="Новое изображение">
                <div class="change-overlay">
                    <i class="bi bi-camera"></i>
                    <span>Нажмите для замены</span>
                </div>
            `;
            preview.onclick = selectImage;
        };
        reader.readAsDataURL(file);
        
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

// Добавление/удаление метаданных
function addMetadataInCard() {
    const metadataSection = document.getElementById('metadata-section');
    const addButton = document.querySelector('.add-metadata-button');
    
    if (metadataSection.style.display === 'none' || !metadataSection.style.display) {
        metadataSection.style.display = 'block';
        addButton.innerHTML = '<i class="bi bi-dash"></i> Убрать описание';
        addButton.onclick = function() {
            metadataSection.style.display = 'none';
            document.querySelector('.editable-alt-text').textContent = '';
            addButton.innerHTML = '<i class="bi bi-plus"></i> Добавить описание';
            addButton.onclick = addMetadataInCard;
            formState.hasMetadata = false;
            updateHiddenFields();
            hasChanges = true;
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
    document.getElementById('hidden-is-active').value = document.getElementById('is-active').checked ? '1' : '0';
}

// Валидация и сохранение
function saveImage() {
    updateHiddenFields();
    
    // Если выбрано новое изображение, валидируем размер
    if (selectedImageFile && selectedImageFile.size > 10 * 1024 * 1024) {
        alert('Размер файла не должен превышать 10 МБ');
        return;
    }
    
    // Отправляем форму
    hasChanges = false; // Сбрасываем флаг изменений
    document.getElementById('image-form').submit();
}

// Удаление изображения
function removeImage() {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

function confirmDelete() {
    hasChanges = false; // Сбрасываем флаг изменений
    document.getElementById('deleteForm').submit();
}

// Показ ошибок валидации
<?php if($errors->any()): ?>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            let errorMessages = [];
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                errorMessages.push('<?php echo e($error); ?>');
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            alert('Ошибки валидации:\n' + errorMessages.join('\n'));
        }, 500);
    });
<?php endif; ?>
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\link\resources\views\admin\gallery\edit.blade.php ENDPATH**/ ?>