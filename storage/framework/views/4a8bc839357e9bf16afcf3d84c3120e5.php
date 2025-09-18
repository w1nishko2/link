

<?php $__env->startSection('title', 'Редактирование баннера - ' . config('app.name')); ?>
<?php $__env->startSection('description', 'Редактирование баннера в каталоге'); ?>

<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<style>
.banner-buttons {
    display: flex;
    gap: 8px;
    align-items: center;
    flex-wrap: wrap;
}

.add-link-button {
    white-space: nowrap;
    font-size: 12px;
    padding: 4px 8px;
}

.banner-bottom {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.banner-link {
    order: 1;
}

.banner-buttons {
    order: 2;
}

/* Стили для редактируемых элементов */
.editable-title, .editable-description, .editable-link-text {
    outline: none;
    border: none;
    background: transparent;
    transition: all 0.2s ease;
    border-radius: 4px;
    padding: 2px 4px;
    margin: -2px -4px;
}

.editable-title:focus, .editable-description:focus, .editable-link-text:focus {
    background-color: rgba(255, 255, 255, 0.1);
    box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.25);
    outline: none;
}

.editable-title:hover, .editable-description:hover, .editable-link-text:hover {
    background-color: rgba(255, 255, 255, 0.05);
}

/* Убираем стандартные стили contenteditable */
.editable-title[contenteditable="true"]:empty:before,
.editable-description[contenteditable="true"]:empty:before,
.editable-link-text[contenteditable="true"]:empty:before {
    content: attr(placeholder);
    color: rgba(255, 255, 255, 0.6);
    font-style: italic;
}

/* Плавные переходы для показа/скрытия элементов */
.banner-link, .add-link-button {
    transition: opacity 0.3s ease, transform 0.3s ease;
}

.banner-link[style*="display: none"] {
    opacity: 0;
    transform: scale(0.8);
}

.banner-link[style*="display: block"] {
    opacity: 1;
    transform: scale(1);
}

/* Адаптация для мобильных */
@media (max-width: 768px) {
    .banner-buttons {
        justify-content: center;
    }
    
    .add-link-button {
        font-size: 11px;
        padding: 3px 6px;
    }
    
    .editable-title, .editable-description, .editable-link-text {
        padding: 4px 6px;
        margin: -4px -6px;
    }
}

.banner-no-image {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: #e9ecef;
    border-radius: 15px;
    height: 100%;
    width: 100%;
    transition: all 0.3s ease;
    cursor: pointer;
}

.banner-no-image:hover {
    background: #dee2e6;
}

.banner-no-image p {
    font-size: 12px;
    text-align: center;
    margin: 10px 0 0 0;
}
</style>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
   
    <a href="<?php echo e(route('admin.banners', $currentUserId)); ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>
        <span class="d-none d-sm-inline">Назад к баннерам</span>
        <span class="d-sm-none">Назад</span>
    </a>
</div>

<!-- Скрытая форма для отправки данных -->
<form id="banner-form" action="<?php echo e(route('admin.banners.update', [$currentUserId, $banner])); ?>" method="POST" enctype="multipart/form-data" style="display: none;">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>
    <input type="hidden" name="title" id="hidden-title">
    <input type="hidden" name="description" id="hidden-description">
    <input type="hidden" name="link_url" id="hidden-link-url">
    <input type="hidden" name="link_text" id="hidden-link-text">
    <input type="hidden" name="order_index" id="hidden-order-index">
    <input type="file" name="image" id="hidden-image" accept="image/*">
</form>

<div class="row justify-content-center">
    <div class="col-lg-8 col-xl-6">
        <div class="d-flex flex-column">
            <div class="swiper banners-swiper" id="edit-banners-swiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="banners-banner">
                            <div class="banners-banner-block" style="justify-content: space-between">
                                <div class="banners-banner-block-title">
                                    <h3 class="editable-title" contenteditable="true" placeholder="Название баннера" onclick="selectText(this); event.stopPropagation();"><?php echo e($banner->title); ?></h3>
                                    <p class="editable-description" contenteditable="true" placeholder="Описание баннера. Нажмите, чтобы редактировать." onclick="selectText(this); event.stopPropagation();"><?php echo e($banner->description); ?></p>
                                </div>
                              
                                <div class="banner-bottom">
                                    <div class="banner-link" id="banner-link-display" style="<?php echo e($banner->link_url ? 'display: block;' : 'display: none;'); ?>">
                                        <a href="<?php echo e($banner->link_url ?: '#'); ?>" class="btn btn-primary btn-sm" id="banner-link-button"><?php echo e($banner->link_text ?: 'Перейти'); ?></a>
                                    </div>
                                    
                                    <div class="banner-buttons">
                                        <button type="button" class="btn btn-sm add-link-button" id="add-link-card-btn" onclick="addLinkInCard(); event.stopPropagation();" style="<?php echo e($banner->link_url ? 'display: none;' : 'display: inline-block;'); ?>">
                                            <i class="bi bi-plus"></i> Добавить ссылку
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="banners-banner-block-img" onclick="selectImage()">
                                <?php if($banner->image_path): ?>
                                    <img id="banner-preview-image" src="<?php echo e(asset('storage/' . $banner->image_path)); ?>" alt="Предпросмотр изображения" style="display: block; width: 100%; height: 100%; object-fit: cover; border-radius: 15px;">
                                    <div class="banner-no-image" id="banner-no-image" style="display: none;">
                                        <i class="bi bi-image" style="font-size: 3rem; color: #ccc;"></i>
                                        <p>Нажмите, чтобы выбрать изображение</p>
                                    </div>
                                <?php else: ?>
                                    <div class="banner-no-image" id="banner-no-image">
                                        <i class="bi bi-image" style="font-size: 3rem; color: #ccc;"></i>
                                        <p>Нажмите, чтобы выбрать изображение</p>
                                    </div>
                                    <img id="banner-preview-image" src="" alt="Предпросмотр изображения" style="display: none; width: 100%; height: 100%; object-fit: cover; border-radius: 15px;">
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Дополнительные настройки (скрыты по умолчанию) -->
            <div class="mt-3">
                <button type="button" class="btn btn-outline-info btn-sm" onclick="toggleAdvanced()">
                    <i class="bi bi-sliders"></i> Дополнительные настройки
                </button>
            </div>
            
            <div id="advanced-settings" style="display: none;" class="mt-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="order-input" class="form-label">Порядок отображения</label>
                                    <input type="number" class="form-control" id="order-input" placeholder="1" min="1" value="<?php echo e($banner->order_index); ?>">
                                    <div class="form-text">Чем меньше число, тем выше баннер в списке</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    
                                        <input class="form-check-input" style="position: relative"type="checkbox" value="1" id="has-link-checkbox" onchange="toggleLinkSettings()" <?php echo e($banner->link_url ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="has-link-checkbox">
                                            Добавить кнопку-ссылку
                                        </label>
                                    
                                </div>
                            </div>
                        </div>
                        
                        <div id="link-settings" style="<?php echo e($banner->link_url ? 'display: block;' : 'display: none;'); ?>">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="link-url-input" class="form-label">URL ссылки</label>
                                        <input type="url" class="form-control" id="link-url-input" placeholder="https://example.com" value="<?php echo e($banner->link_url); ?>">
                                        <div class="form-text">Введите полный URL включая https://</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="link-text-select" class="form-label">Текст кнопки</label>
                                        <select class="form-select" id="link-text-select" onchange="updateLinkText()">
                                            <option value="Перейти" <?php echo e(($banner->link_text ?? 'Перейти') == 'Перейти' ? 'selected' : ''); ?>>Перейти</option>
                                            <option value="Подробнее" <?php echo e(($banner->link_text ?? '') == 'Подробнее' ? 'selected' : ''); ?>>Подробнее</option>
                                            <option value="Читать далее" <?php echo e(($banner->link_text ?? '') == 'Читать далее' ? 'selected' : ''); ?>>Читать далее</option>
                                            <option value="Узнать больше" <?php echo e(($banner->link_text ?? '') == 'Узнать больше' ? 'selected' : ''); ?>>Узнать больше</option>
                                            <option value="Заказать" <?php echo e(($banner->link_text ?? '') == 'Заказать' ? 'selected' : ''); ?>>Заказать</option>
                                            <option value="Купить" <?php echo e(($banner->link_text ?? '') == 'Купить' ? 'selected' : ''); ?>>Купить</option>
                                            <option value="Оформить" <?php echo e(($banner->link_text ?? '') == 'Оформить' ? 'selected' : ''); ?>>Оформить</option>
                                            <option value="Записаться" <?php echo e(($banner->link_text ?? '') == 'Записаться' ? 'selected' : ''); ?>>Записаться</option>
                                            <option value="Связаться" <?php echo e(($banner->link_text ?? '') == 'Связаться' ? 'selected' : ''); ?>>Связаться</option>
                                            <option value="Смотреть" <?php echo e(($banner->link_text ?? '') == 'Смотреть' ? 'selected' : ''); ?>>Смотреть</option>
                                            <option value="Открыть" <?php echo e(($banner->link_text ?? '') == 'Открыть' ? 'selected' : ''); ?>>Открыть</option>
                                            <option value="Скачать" <?php echo e(($banner->link_text ?? '') == 'Скачать' ? 'selected' : ''); ?>>Скачать</option>
                                        </select>
                                        <div class="form-text">Выберите подходящий текст для кнопки</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Кнопка сохранения -->
            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-primary" onclick="saveBanner()">
                    <i class="bi bi-check-lg me-2"></i>
                    Сохранить изменения
                </button>
                <a href="<?php echo e(route('admin.banners', $currentUserId)); ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg me-2"></i>
                    Отмена
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
// Глобальные переменные
let editSwiper;
let selectedImageFile = null;

// Состояние формы
const formState = {
    title: "<?php echo e($banner->title); ?>",
    description: "<?php echo e($banner->description); ?>",
    linkUrl: "<?php echo e($banner->link_url); ?>",
    linkText: "<?php echo e($banner->link_text ?: 'Перейти'); ?>",
    orderIndex: "<?php echo e($banner->order_index); ?>",
    hasLink: <?php echo e($banner->link_url ? 'true' : 'false'); ?>

};

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    initializeSwiper();
    bindEvents();
    loadExistingData();
});

// Инициализация Swiper
function initializeSwiper() {
    editSwiper = new Swiper('#edit-banners-swiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        centeredSlides: true,
        autoHeight: false,
        height: 350,
        direction: 'horizontal',
        allowTouchMove: false
    });
}

// Привязка событий
function bindEvents() {
    // Редактируемые элементы
    const titleElement = document.querySelector('.editable-title');
    const descriptionElement = document.querySelector('.editable-description');
    
    // События для title
    titleElement.addEventListener('input', function() {
        let text = this.textContent.trim();
        if (text.length > 100) {
            text = text.substring(0, 100);
            this.textContent = text;
        }
        formState.title = text;
        updateHiddenFields();
    });
    
    titleElement.addEventListener('blur', function() {
        if (!this.textContent.trim()) {
            this.textContent = 'Название баннера';
            formState.title = '';
        }
    });
    
    // События для description
    descriptionElement.addEventListener('input', function() {
        let text = this.textContent.trim();
        if (text.length > 300) {
            text = text.substring(0, 300);
            this.textContent = text;
        }
        formState.description = text;
        updateHiddenFields();
    });
    
    descriptionElement.addEventListener('blur', function() {
        if (!this.textContent.trim()) {
            this.textContent = 'Описание баннера. Нажмите, чтобы редактировать.';
            formState.description = '';
        }
    });
    
    // События для дополнительных настроек
    document.getElementById('order-input').addEventListener('input', function() {
        formState.orderIndex = this.value;
        updateHiddenFields();
    });
    
    // События для настроек ссылки
    document.getElementById('link-url-input').addEventListener('input', function() {
        formState.linkUrl = this.value;
        updateHiddenFields();
    });
}

// Загрузка существующих данных баннера
function loadExistingData() {
    // Загружаем данные в состояние формы
    updateHiddenFields();
    
    // Обрабатываем отображение чекбокса ссылки и связанных настроек
    if (formState.hasLink) {
        document.getElementById('has-link-checkbox').checked = true;
        document.getElementById('link-settings').style.display = 'block';
        document.getElementById('banner-link-display').style.display = 'block';
        document.getElementById('add-link-card-btn').style.display = 'none';
    }
}

// Выбор изображения
function selectImage() {
    document.getElementById('hidden-image').click();
}

// Обработка выбора изображения
document.getElementById('hidden-image').addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        const file = e.target.files[0];
        selectedImageFile = file;
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const noImage = document.getElementById('banner-no-image');
            const previewImage = document.getElementById('banner-preview-image');
            
            noImage.style.display = 'none';
            previewImage.src = e.target.result;
            previewImage.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});

// Выделение текста при клике
function selectText(element) {
    const range = document.createRange();
    range.selectNodeContents(element);
    const selection = window.getSelection();
    selection.removeAllRanges();
    selection.addRange(range);
}

// Добавление ссылки прямо в карточке
function addLinkInCard() {
    // Включаем чекбокс в дополнительных настройках
    document.getElementById('has-link-checkbox').checked = true;
    
    // Показываем дополнительные настройки
    const advancedSettings = document.getElementById('advanced-settings');
    if (advancedSettings.style.display === 'none') {
        advancedSettings.style.display = 'block';
    }
    
    // Показываем настройки ссылки
    toggleLinkSettings();
    
    // Устанавливаем фокус на URL поле
    setTimeout(() => {
        document.getElementById('link-url-input').focus();
    }, 100);
}

// Переключение настроек ссылки
function toggleLinkSettings() {
    const checkbox = document.getElementById('has-link-checkbox');
    const linkSettings = document.getElementById('link-settings');
    const linkElement = document.getElementById('banner-link-display');
    const addLinkButton = document.getElementById('add-link-card-btn');
    
    if (checkbox.checked) {
        linkSettings.style.display = 'block';
        linkElement.style.display = 'block';
        addLinkButton.style.display = 'none';
        formState.hasLink = true;
        
        // Устанавливаем начальные значения
        if (!formState.linkText) {
            formState.linkText = 'Перейти';
            updateLinkDisplay();
        }
    } else {
        linkSettings.style.display = 'none';
        linkElement.style.display = 'none';
        addLinkButton.style.display = 'inline-block';
        formState.hasLink = false;
        formState.linkUrl = '';
        formState.linkText = '';
    }
    
    updateHiddenFields();
}

// Обновление текста ссылки
function updateLinkText() {
    const selectElement = document.getElementById('link-text-select');
    const linkButton = document.getElementById('banner-link-button');
    
    formState.linkText = selectElement.value;
    linkButton.textContent = selectElement.value;
    
    updateHiddenFields();
}

// Обновление отображения ссылки
function updateLinkDisplay() {
    const linkButton = document.getElementById('banner-link-button');
    linkButton.textContent = formState.linkText || 'Перейти';
}

// Переключение дополнительных настроек
function toggleAdvanced() {
    const advancedSettings = document.getElementById('advanced-settings');
    if (advancedSettings.style.display === 'none') {
        advancedSettings.style.display = 'block';
    } else {
        advancedSettings.style.display = 'none';
    }
}

// Обновление скрытых полей формы
function updateHiddenFields() {
    document.getElementById('hidden-title').value = formState.title;
    document.getElementById('hidden-description').value = formState.description;
    document.getElementById('hidden-link-url').value = formState.linkUrl;
    document.getElementById('hidden-link-text').value = formState.linkText;
    document.getElementById('hidden-order-index').value = formState.orderIndex;
}

// Валидация и сохранение
function saveBanner() {
    // Проверяем обязательные поля
    if (!formState.title || formState.title === 'Название баннера') {
        alert('Пожалуйста, введите название баннера');
        document.querySelector('.editable-title').focus();
        return;
    }
    
    if (!formState.description || formState.description === 'Описание баннера. Нажмите, чтобы редактировать.') {
        alert('Пожалуйста, введите описание баннера');
        document.querySelector('.editable-description').focus();
        return;
    }
    
    // Обновляем скрытые поля и отправляем форму
    updateHiddenFields();
    document.getElementById('banner-form').submit();
}

// Показ ошибок валидации
<?php if($errors->any()): ?>
    document.addEventListener('DOMContentLoaded', function() {
        let errorMessages = '';
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            errorMessages += '<?php echo e($error); ?>\n';
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        alert('Пожалуйста, исправьте ошибки в форме:\n' + errorMessages);
    });
<?php endif; ?>
</script>

<style>
    .swiper.banners-swiper {
        height: 350px;
        width: 100%;
    }
    .banner-buttons {
    display: flex;
    width: 100%;
    justify-content: space-between;
    gap: 20px;
    align-items: center;
    align-content: center;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\link\resources\views\admin\banners\edit.blade.php ENDPATH**/ ?>