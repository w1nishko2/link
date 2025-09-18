

<?php $__env->startSection('title', 'Создание услуги - ' . config('app.name')); ?>
<?php $__env->startSection('description', 'Добавление новой услуги в каталог'); ?>


<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<?php echo app('Illuminate\Foundation\Vite')(['resources/css/services-reels.css']); ?>

<style>
.service-buttons {
    display: flex;
    gap: 8px;
    align-items: center;
    flex-wrap: wrap;
}

.add-price-button {
    white-space: nowrap;
    font-size: 12px;
    padding: 4px 8px;
}

.service-bottom {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.service-price {
    order: 1;
}

.service-buttons {
    order: 2;
}

/* Стили для редактируемых элементов */
.editable-title, .editable-description, .editable-price {
    outline: none;
    border: none;
    background: transparent;
    transition: all 0.2s ease;
    border-radius: 4px;
    padding: 2px 4px;
    margin: -2px -4px;
}

.editable-title:focus, .editable-description:focus, .editable-price:focus {
    background-color: rgba(255, 255, 255, 0.1);
    box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.25);
    outline: none;
}

.editable-title:hover, .editable-description:hover, .editable-price:hover {
    background-color: rgba(255, 255, 255, 0.05);
}

/* Убираем стандартные стили contenteditable */
.editable-title[contenteditable="true"]:empty:before,
.editable-description[contenteditable="true"]:empty:before,
.editable-price[contenteditable="true"]:empty:before {
    content: attr(placeholder);
    color: rgba(255, 255, 255, 0.6);
    font-style: italic;
}

/* Плавные переходы для показа/скрытия элементов */
.service-price, .add-price-button {
    transition: opacity 0.3s ease, transform 0.3s ease;
}

.service-price[style*="display: none"] {
    opacity: 0;
    transform: scale(0.8);
}

.service-price[style*="display: block"] {
    opacity: 1;
    transform: scale(1);
}

/* Адаптация для мобильных */
@media (max-width: 768px) {
    .service-buttons {
        justify-content: center;
    }
    
    .add-price-button {
        font-size: 11px;
        padding: 3px 6px;
    }
    
    .editable-title, .editable-description, .editable-price {
        padding: 4px 6px;
        margin: -4px -6px;
    }
}
</style>




<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
   
    <a href="<?php echo e(route('admin.services', $currentUserId)); ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>
        <span class="d-none d-sm-inline">Назад к услугам</span>
        <span class="d-sm-none">Назад</span>
    </a>
</div>

<!-- Скрытая форма для отправки данных -->
<form id="service-form" action="<?php echo e(route('admin.services.store', $currentUserId)); ?>" method="POST" enctype="multipart/form-data" style="display: none;">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="title" id="hidden-title">
    <input type="hidden" name="description" id="hidden-description">
    <input type="hidden" name="price" id="hidden-price">
    <input type="hidden" name="price_type" id="hidden-price-type" value="fixed">
    <input type="hidden" name="button_text" id="hidden-button-text">
    <input type="hidden" name="button_link" id="hidden-button-link">
    <input type="hidden" name="order_index" id="hidden-order-index">
    <input type="file" name="image" id="hidden-image" accept="image/*">
</form>

<div class="row justify-content-center">
    <div class="col-lg-8 col-xl-4">
       
         
                            <div class="swiper services-swiper" id="edit-services-swiper">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <div class="service-card editable-card" id="editable-service-card">
                                            <!-- Изображение с возможностью загрузки -->
                                            <div class="service-image editable-image" onclick="selectImage()">
                                                <img id="service-image" 
                                                     src="/hero.png" 
                                                     alt="Изображение услуги" 
                                                     loading="lazy"
                                                     width="300"
                                                     height="600"
                                                     decoding="async">
                                                <div class="image-overlay">
                                                    <i class="bi bi-camera-fill"></i>
                                                    <span>Выбрать изображение</span>
                                                </div>
                                            </div>
                                            
                                            <div class="service-content">
                                                <!-- Редактируемое название -->
                                                <h3 class="editable-title" 
                                                    contenteditable="true" 
                                                    placeholder="Введите название услуги..."
                                                    data-max-length="100"
                                                    onclick="selectText(this)">Название услуги</h3>
                                                
                                                <!-- Редактируемое описание -->
                                                <p class="editable-description" 
                                                   contenteditable="true" 
                                                   placeholder="Введите описание услуги..."
                                                   data-max-length="500"
                                                   onclick="selectText(this)">Описание услуги. Нажмите, чтобы редактировать.</p>
                                                
                                                <div class="service-bottom">
                                                    <!-- Редактируемая цена -->
                                                    <div class="service-price editable-price" 
                                                         contenteditable="true" 
                                                         placeholder="Цена"
                                                         onclick="selectText(this)"
                                                         style="display: none;margin:0;"></div>
                                                    
                                                    <div class="service-buttons" style="flex-wrap: nowrap">
                                                        <!-- Кнопка добавления цены -->
                                                        <button type="button" class="btn btn-outline-success btn-sm add-price-button" 
                                                                onclick="addPriceInCard()" id="add-price-card-btn">
                                                            <i class="bi bi-tag me-1"></i> Добавить цену
                                                        </button>
                                                        
                                                        <!-- Редактируемая кнопка -->
                                                        <div class="service-button btn btn-primary btn-sm editable-button" 
                                                             onclick="editButton()">
                                                            Кнопка
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                       
                    
              
           
            <div class="card-footer">
                <div class="row">
                    <div class="col-12">
                        <!-- Основные действия -->
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-primary" onclick="saveService()">
                                <i class="bi bi-check-circle me-2"></i>
                                Сохранить услугу
                            </button>
                            <a href="<?php echo e(route('admin.services', $currentUserId)); ?>" class="btn btn-outline-secondary btn-sm">
                                Отмена
                            </a>
                        </div>
                    </div>
                </div>
            </div>
     
        <!-- Дополнительные настройки (скрыты по умолчанию) -->
        <div class="card mt-3" id="advanced-settings" style="display: none;">
            <div class="card-header">
                <h6 class="card-title mb-0">Дополнительные настройки</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <!-- Управление ценой -->
                        <label class="form-label">Управление ценой</label>
                        <div class="d-grid">
                            <button type="button" class="btn btn-outline-success btn-sm" onclick="togglePrice()" id="price-toggle">
                                <i class="bi bi-tag me-1"></i> Добавить цену
                            </button>
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Тип цены</label>
                        <select class="form-select form-select-sm" id="price-type-select">
                            <option value="fixed">Фиксированная</option>
                            <option value="hourly">За час</option>
                            <option value="project">За проект</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Порядок отображения</label>
                        <input type="number" class="form-control form-control-sm" id="order-input" placeholder="Авто">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-3">
            <button type="button" class="btn btn-link btn-sm" onclick="toggleAdvanced()">
                <i class="bi bi-gear me-1"></i> Дополнительные настройки
            </button>
        </div>
    </div>
</div>

<!-- Модальные окна -->
<!-- Модальное окно редактирования кнопки -->
<div class="modal fade" id="buttonModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Настройка кнопки</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Текст кнопки</label>
                    <select class="form-select" id="button-text-select">
                        <option value="">Выберите текст</option>
                        <option value="Заказать услугу">Заказать услугу</option>
                        <option value="Связаться с нами">Связаться с нами</option>
                        <option value="Узнать подробнее">Узнать подробнее</option>
                        <option value="Написать в WhatsApp">Написать в WhatsApp</option>
                        <option value="Написать в Telegram">Написать в Telegram</option>
                        <option value="Позвонить">Позвонить</option>
                        <option value="Отправить email">Отправить email</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Ссылка</label>
                    <select class="form-select" id="button-link-select">
                        <option value="">Выберите ссылку</option>
                        <?php if($user->phone): ?>
                            <option value="tel:<?php echo e($user->phone); ?>">Телефон: <?php echo e($user->phone); ?></option>
                        <?php endif; ?>
                        <?php if($user->email): ?>
                            <option value="mailto:<?php echo e($user->email); ?>">Email: <?php echo e($user->email); ?></option>
                        <?php endif; ?>
                        <?php if($user->telegram_url): ?>
                            <option value="<?php echo e($user->telegram_url); ?>">Telegram</option>
                        <?php endif; ?>
                        <?php if($user->whatsapp_url): ?>
                            <option value="<?php echo e($user->whatsapp_url); ?>">WhatsApp</option>
                        <?php endif; ?>
                        <?php if($user->vk_url): ?>
                            <option value="<?php echo e($user->vk_url); ?>">VK
                        <?php endif; ?>
                        <?php if($user->instagram_url): ?>
                            <option value="<?php echo e($user->instagram_url); ?>">Instagram</option>
                        <?php endif; ?>
                        <?php if($user->website_url): ?>
                            <option value="<?php echo e($user->website_url); ?>">Сайт</option>
                        <?php endif; ?>
                        <?php $__currentLoopData = $user->socialLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $socialLink): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($socialLink->url); ?>"><?php echo e($socialLink->service_name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary" onclick="applyButtonSettings()">Применить</button>
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
    title: '',
    description: '',
    price: '',
    priceType: 'fixed',
    buttonText: 'Кнопка',
    buttonLink: '',
    orderIndex: '',
    hasPrice: false,
    hasButton: true
};

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    initializeSwiper();
    bindEvents();
    loadOldValues();
});

// Инициализация Swiper
function initializeSwiper() {
    editSwiper = new Swiper('#edit-services-swiper', {
        slidesPerView: 1,
        spaceBetween: 0,
        loop: false,
        allowTouchMove: false,
        breakpoints: {
            320: {
                slidesPerView: 1,
                spaceBetween: 0,
            }
        }
    });
}

// Привязка событий
function bindEvents() {
    // Редактируемые элементы
    const titleElement = document.querySelector('.editable-title');
    const descriptionElement = document.querySelector('.editable-description');
    const priceElement = document.querySelector('.editable-price');
    
    // События для title
    titleElement.addEventListener('input', function() {
        const text = this.textContent.trim();
        if (text.length > 100) {
            this.textContent = text.substring(0, 100);
        }
        formState.title = this.textContent.trim();
        updateHiddenFields();
    });
    
    titleElement.addEventListener('blur', function() {
        if (this.textContent.trim() === '') {
            this.textContent = 'Название услуги';
            formState.title = '';
        }
    });
    
    // События для description
    descriptionElement.addEventListener('input', function() {
        const text = this.textContent.trim();
        if (text.length > 500) {
            this.textContent = text.substring(0, 500);
        }
        formState.description = this.textContent.trim();
        updateHiddenFields();
    });
    
    descriptionElement.addEventListener('blur', function() {
        if (this.textContent.trim() === '' || this.textContent.trim() === 'Описание услуги. Нажмите, чтобы редактировать.') {
            this.textContent = 'Описание услуги. Нажмите, чтобы редактировать.';
            formState.description = '';
        }
    });
    
    // События для price
    priceElement.addEventListener('input', function() {
        let text = this.textContent.replace(/[^\d.,]/g, '');
        this.textContent = text;
        formState.price = text;
        updatePriceDisplay();
        updateHiddenFields();
    });
    
    // События для дополнительных настроек
    document.getElementById('price-type-select').addEventListener('change', function() {
        formState.priceType = this.value;
        updatePriceDisplay();
        updateHiddenFields();
    });
    
    document.getElementById('order-input').addEventListener('input', function() {
        formState.orderIndex = this.value;
        updateHiddenFields();
    });
}

// Загрузка старых значений (если есть ошибки валидации)
function loadOldValues() {
    <?php if(old('title')): ?>
        document.querySelector('.editable-title').textContent = "<?php echo e(old('title')); ?>";
        formState.title = "<?php echo e(old('title')); ?>";
    <?php endif; ?>
    
    <?php if(old('description')): ?>
        document.querySelector('.editable-description').textContent = "<?php echo e(old('description')); ?>";
        formState.description = "<?php echo e(old('description')); ?>";
    <?php endif; ?>
    
    <?php if(old('price')): ?>
        formState.price = "<?php echo e(old('price')); ?>";
        formState.hasPrice = true;
        document.querySelector('.editable-price').textContent = "<?php echo e(old('price')); ?>";
        document.querySelector('.editable-price').style.display = 'block';
        document.getElementById('add-price-card-btn').style.display = 'none';
        updatePriceDisplay();
    <?php endif; ?>
    
    <?php if(old('price_type')): ?>
        formState.priceType = "<?php echo e(old('price_type')); ?>";
        document.getElementById('price-type-select').value = "<?php echo e(old('price_type')); ?>";
    <?php endif; ?>
    
    <?php if(old('button_text') && old('button_link')): ?>
        formState.buttonText = "<?php echo e(old('button_text')); ?>";
        formState.buttonLink = "<?php echo e(old('button_link')); ?>";
        document.querySelector('.editable-button').textContent = "<?php echo e(old('button_text')); ?>";
    <?php endif; ?>
    
    <?php if(old('order_index')): ?>
        formState.orderIndex = "<?php echo e(old('order_index')); ?>";
        document.getElementById('order-input').value = "<?php echo e(old('order_index')); ?>";
    <?php endif; ?>
    
    updateHiddenFields();
}

// Выбор изображения
function selectImage() {
    document.getElementById('hidden-image').click();
}

// Обработка выбора изображения
document.getElementById('hidden-image').addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        selectedImageFile = e.target.files[0];
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('service-image').src = e.target.result;
        }
        reader.readAsDataURL(selectedImageFile);
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

// Добавление цены прямо в карточке
function addPriceInCard() {
    const priceElement = document.querySelector('.service-price');
    const addPriceButton = document.getElementById('add-price-card-btn');
    
    // Показать поле цены
    priceElement.style.display = 'block';
    formState.hasPrice = true;
    
    // Скрыть кнопку добавления цены
    addPriceButton.style.display = 'none';
    
    // Установить начальное значение
    if (!formState.price) {
        priceElement.textContent = '0';
        formState.price = '0';
    }
    
    // Обновить отображение и скрытые поля
    updatePriceDisplay();
    updateHiddenFields();
    
    // Фокус на поле цены для редактирования
    priceElement.focus();
    selectText(priceElement);
}

// Переключение цены
function togglePrice() {
    const priceElement = document.querySelector('.service-price');
    const toggleButton = document.getElementById('price-toggle');
    const addPriceCardButton = document.getElementById('add-price-card-btn');
    
    if (formState.hasPrice) {
        // Скрыть цену
        priceElement.style.display = 'none';
        formState.hasPrice = false;
        formState.price = '';
        toggleButton.innerHTML = '<i class="bi bi-tag me-1"></i> Добавить цену';
        toggleButton.classList.remove('btn-outline-danger');
        toggleButton.classList.add('btn-outline-success');
        
        // Показать кнопку добавления цены в карточке
        addPriceCardButton.style.display = 'inline-block';
    } else {
        // Показать цену
        priceElement.style.display = 'block';
        formState.hasPrice = true;
        if (!formState.price) {
            priceElement.textContent = '0';
            formState.price = '0';
        }
        toggleButton.innerHTML = '<i class="bi bi-tag-fill me-1"></i> Убрать цену';
        toggleButton.classList.remove('btn-outline-success');
        toggleButton.classList.add('btn-outline-danger');
        updatePriceDisplay();
        
        // Скрыть кнопку добавления цены в карточке
        addPriceCardButton.style.display = 'none';
    }
    
    updateHiddenFields();
}

// Редактирование кнопки
function editButton() {
    // Заполняем модальное окно текущими значениями
    document.getElementById('button-text-select').value = formState.buttonText || '';
    document.getElementById('button-link-select').value = formState.buttonLink || '';
    
    // Показываем модальное окно
    const modal = new bootstrap.Modal(document.getElementById('buttonModal'));
    modal.show();
}

// Применение настроек кнопки
function applyButtonSettings() {
    const buttonText = document.getElementById('button-text-select').value;
    const buttonLink = document.getElementById('button-link-select').value;
    
    if (buttonText && buttonLink) {
        formState.buttonText = buttonText;
        formState.buttonLink = buttonLink;
        
        const buttonElement = document.querySelector('.editable-button');
        buttonElement.textContent = buttonText;
        
        updateHiddenFields();
        
        // Закрываем модальное окно
        const modal = bootstrap.Modal.getInstance(document.getElementById('buttonModal'));
        modal.hide();
    } else {
        alert('Пожалуйста, выберите текст и ссылку для кнопки');
    }
}

// Обновление отображения цены
function updatePriceDisplay() {
    if (!formState.hasPrice || !formState.price) return;
    
    const priceElement = document.querySelector('.service-price');
    const numPrice = parseFloat(formState.price);
    
    if (isNaN(numPrice)) return;
    
    const formatted = new Intl.NumberFormat('ru-RU').format(numPrice);
    
    switch(formState.priceType) {
        case 'hourly':
            priceElement.textContent = `${formatted} ₽/час`;
            break;
        case 'project':
            priceElement.textContent = `от ${formatted} ₽`;
            break;
        case 'fixed':
        default:
            priceElement.textContent = `${formatted} ₽`;
            break;
    }
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
    document.getElementById('hidden-price').value = formState.hasPrice ? formState.price : '';
    document.getElementById('hidden-price-type').value = formState.priceType;
    document.getElementById('hidden-button-text').value = formState.buttonText;
    document.getElementById('hidden-button-link').value = formState.buttonLink;
    document.getElementById('hidden-order-index').value = formState.orderIndex;
}

// Валидация и сохранение
function saveService() {
    // Проверяем обязательные поля
    if (!formState.title || formState.title === 'Название услуги') {
        alert('Пожалуйста, введите название услуги');
        document.querySelector('.editable-title').focus();
        return;
    }
    
    if (!formState.description || formState.description === 'Описание услуги. Нажмите, чтобы редактировать.') {
        alert('Пожалуйста, введите описание услуги');
        document.querySelector('.editable-description').focus();
        return;
    }
    
    // Проверяем кнопку (текст и ссылка обязательны)
    if (!formState.buttonText || !formState.buttonLink) {
        alert('Пожалуйста, настройте кнопку (текст и ссылку)');
        editButton();
        return;
    }
    
    // Обновляем скрытые поля и отправляем форму
    updateHiddenFields();
    document.getElementById('service-form').submit();
}

// Показ ошибок валидации
<?php if($errors->any()): ?>
    document.addEventListener('DOMContentLoaded', function() {
        let errorMessages = [];
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            errorMessages.push("<?php echo e($error); ?>");
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        
        if (errorMessages.length > 0) {
            alert('Ошибки:\n' + errorMessages.join('\n'));
        }
    });
<?php endif; ?>
</script>

<style>
    .swiper.services-swiper {
        padding: 20px !important;
    }
    .service-buttons {
    display: flex;
    width: 100%;
    justify-content: space-between;
    gap: 20px;
    align-items: center;
    align-content: center;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\link\resources\views\admin\services\create.blade.php ENDPATH**/ ?>