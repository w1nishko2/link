

<?php $__env->startSection('title', 'Создание услуги - ' . config('app.name')); ?>
<?php $__env->startSection('description', 'Добавление новой услуги в каталог'); ?>


<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<?php echo app('Illuminate\Foundation\Vite')(['resources/css/services-reels.css']); ?>
<style>
/* Адаптация превью для админки */
.service-preview-container {
    min-height: 500px;
}

.service-preview-container .services-header h6 {
    font-size: 0.9rem;
    color: #6c757d;
}

/* Отключаем navigation для превью */
.service-preview-container .swiper-button-next,
.service-preview-container .swiper-button-prev {
    display: none;
}

.swiper.services-swiper {
    width: 100%;
    height: 600px;
}

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


<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
  
    <a href="<?php echo e(route('admin.services', $currentUserId)); ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>
        <span class="d-none d-sm-inline">Назад к услугам</span>
        <span class="d-sm-none">Назад</span>
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Информация об услуге</h5>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('admin.services.store', $currentUserId)); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    
                    <!-- Название услуги - полная ширина -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Название услуги *</label>
                        <input type="text" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="title" name="title" value="<?php echo e(old('title')); ?>" required maxlength="100">
                        <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <div class="form-text">Максимум 100 символов. Осталось: <span id="title-counter">100</span></div>
                    </div>

                    <!-- Описание - полная ширина -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Описание *</label>
                        <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                  id="description" name="description" rows="3" required maxlength="500"><?php echo e(old('description')); ?></textarea>
                        <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <div class="form-text">Максимум 500 символов. Осталось: <span id="description-counter">500</span></div>
                    </div>

                    <!-- Первый ряд: Изображение и Порядок отображения -->
                    <div class="row">
                        <div class="col-8">
                            <div class="mb-3">
                                <label for="image" class="form-label">Изображение</label>
                                <input type="file" class="form-control <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="image" name="image" accept="image/*">
                                <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div class="form-text">400x300px, WebP оптимизация</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="order_index" class="form-label">Порядок</label>
                                <input type="number" class="form-control <?php $__errorArgs = ['order_index'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="order_index" name="order_index" value="<?php echo e(old('order_index')); ?>" placeholder="Авто">
                                <?php $__errorArgs = ['order_index'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div class="form-text">Порядок показа</div>
                            </div>
                        </div>
                    </div>

                    <!-- Второй ряд: Цена и Тип цены -->
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">Цена</label>
                                <input type="number" class="form-control <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="price" name="price" value="<?php echo e(old('price')); ?>" min="0" step="0.01" placeholder="0">
                                <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="price_type" class="form-label">Тип цены *</label>
                                <select class="form-select <?php $__errorArgs = ['price_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="price_type" name="price_type" required>
                                    <option value="fixed" <?php echo e(old('price_type') == 'fixed' ? 'selected' : ''); ?>>Фиксированная</option>
                                    <option value="hourly" <?php echo e(old('price_type') == 'hourly' ? 'selected' : ''); ?>>За час</option>
                                    <option value="project" <?php echo e(old('price_type') == 'project' ? 'selected' : ''); ?>>За проект</option>
                                </select>
                                <?php $__errorArgs = ['price_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Третий ряд: Настройки кнопки действия -->
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="button_text" class="form-label">Текст кнопки</label>
                                <select class="form-select <?php $__errorArgs = ['button_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="button_text" name="button_text">
                                    <option value="">Без кнопки</option>
                                    <option value="Заказать услугу" <?php echo e(old('button_text') == 'Заказать услугу' ? 'selected' : ''); ?>>Заказать услугу</option>
                                    <option value="Связаться с нами" <?php echo e(old('button_text') == 'Связаться с нами' ? 'selected' : ''); ?>>Связаться с нами</option>
                                    <option value="Узнать подробнее" <?php echo e(old('button_text') == 'Узнать подробнее' ? 'selected' : ''); ?>>Узнать подробнее</option>
                                    <option value="Написать в WhatsApp" <?php echo e(old('button_text') == 'Написать в WhatsApp' ? 'selected' : ''); ?>>Написать в WhatsApp</option>
                                    <option value="Написать в Telegram" <?php echo e(old('button_text') == 'Написать в Telegram' ? 'selected' : ''); ?>>Написать в Telegram</option>
                                    <option value="Позвонить" <?php echo e(old('button_text') == 'Позвонить' ? 'selected' : ''); ?>>Позвонить</option>
                                    <option value="Отправить email" <?php echo e(old('button_text') == 'Отправить email' ? 'selected' : ''); ?>>Отправить email</option>
                                </select>
                                <?php $__errorArgs = ['button_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="button_link" class="form-label">Ссылка для кнопки</label>
                                <select class="form-select <?php $__errorArgs = ['button_link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="button_link" name="button_link">
                                    <option value="">Выберите ссылку</option>
                                    <?php if($user->phone): ?>
                                        <option value="tel:<?php echo e($user->phone); ?>" <?php echo e(old('button_link') == 'tel:' . $user->phone ? 'selected' : ''); ?>>
                                            Телефон: <?php echo e($user->phone); ?>

                                        </option>
                                    <?php endif; ?>
                                    <?php if($user->email): ?>
                                        <option value="mailto:<?php echo e($user->email); ?>" <?php echo e(old('button_link') == 'mailto:' . $user->email ? 'selected' : ''); ?>>
                                            Email: <?php echo e($user->email); ?>

                                        </option>
                                    <?php endif; ?>
                                    <?php if($user->telegram_url): ?>
                                        <option value="<?php echo e($user->telegram_url); ?>" <?php echo e(old('button_link') == $user->telegram_url ? 'selected' : ''); ?>>
                                            Telegram
                                        </option>
                                    <?php endif; ?>
                                    <?php if($user->whatsapp_url): ?>
                                        <option value="<?php echo e($user->whatsapp_url); ?>" <?php echo e(old('button_link') == $user->whatsapp_url ? 'selected' : ''); ?>>
                                            WhatsApp
                                        </option>
                                    <?php endif; ?>
                                    <?php if($user->vk_url): ?>
                                        <option value="<?php echo e($user->vk_url); ?>" <?php echo e(old('button_link') == $user->vk_url ? 'selected' : ''); ?>>
                                            VK
                                        </option>
                                    <?php endif; ?>
                                    <?php if($user->instagram_url): ?>
                                        <option value="<?php echo e($user->instagram_url); ?>" <?php echo e(old('button_link') == $user->instagram_url ? 'selected' : ''); ?>>
                                            Instagram
                                        </option>
                                    <?php endif; ?>
                                    <?php if($user->website_url): ?>
                                        <option value="<?php echo e($user->website_url); ?>" <?php echo e(old('button_link') == $user->website_url ? 'selected' : ''); ?>>
                                            Сайт
                                        </option>
                                    <?php endif; ?>
                                    <?php $__currentLoopData = $user->socialLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $socialLink): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($socialLink->url); ?>" <?php echo e(old('button_link') == $socialLink->url ? 'selected' : ''); ?>>
                                            <?php echo e($socialLink->service_name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['button_link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div class="form-text">Куда ведёт кнопка</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="bi bi-check-circle me-2"></i>
                            Сохранить услугу
                        </button>
                        <a href="<?php echo e(route('admin.services', $currentUserId)); ?>" class="btn btn-outline-secondary flex-fill">
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
                <h5 class="card-title mb-0">Предварительный просмотр</h5>
            </div>
            <div class="card-body p-0">
                <!-- Предварительный просмотр услуги -->
                <div id="service-preview" class="service-preview-container">
                    <section class="services" aria-label="Предварительный просмотр услуги">
                        <div class="container-fluid p-3">
                            <header class="services-header mb-4 text-center">
                                <h6 class="text-muted mb-3">Так будет выглядеть ваша услуга:</h6>
                            </header>
                            
                            <div class="swiper services-swiper" id="preview-services-swiper">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <div class="service-card" id="preview-service-card">
                                            <div class="service-image" id="preview-service-image">
                                                <img id="preview-image" 
                                                     src="/hero.png" 
                                                     alt="Предварительный просмотр" 
                                                     loading="lazy"
                                                     width="300"
                                                     height="600"
                                                     decoding="async">
                                            </div>
                                            <div class="service-content">
                                                <h3 id="preview-title">Название услуги</h3>
                                                <p id="preview-description">Описание услуги будет отображаться здесь</p>
                                                <div class="service-bottom">
                                                    <div class="service-price" id="preview-price" style="display: none;"></div>
                                                    <a href="#" class="service-button btn btn-primary btn-sm" id="preview-button" style="display: none;">
                                                        Кнопка
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

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

// Функция форматирования цены
function formatPrice(price, priceType) {
    if (!price || price === '' || price === '0') {
        return '';
    }
    
    const numPrice = parseFloat(price);
    if (isNaN(numPrice)) return '';
    
    const formatted = new Intl.NumberFormat('ru-RU').format(numPrice);
    
    switch(priceType) {
        case 'hourly':
            return `${formatted} ₽/час`;
        case 'project':
            return `от ${formatted} ₽`;
        case 'fixed':
        default:
            return `${formatted} ₽`;
    }
}

// Функция обновления предварительного просмотра
function updatePreview() {
    const title = document.getElementById('title').value || 'Название услуги';
    const description = document.getElementById('description').value || 'Описание услуги будет отображаться здесь';
    const price = document.getElementById('price').value;
    const priceType = document.getElementById('price_type').value;
    const buttonText = document.getElementById('button_text').value;
    const buttonLink = document.getElementById('button_link').value;
    const imageInput = document.getElementById('image');
    
    // Обновляем текст
    document.getElementById('preview-title').textContent = title;
    document.getElementById('preview-description').textContent = description;
    
    // Обновляем цену
    const priceElement = document.getElementById('preview-price');
    const formattedPrice = formatPrice(price, priceType);
    
    if (formattedPrice) {
        priceElement.textContent = formattedPrice;
        priceElement.style.display = 'block';
    } else {
        priceElement.style.display = 'none';
    }
    
    // Обновляем кнопку
    const buttonElement = document.getElementById('preview-button');
    if (buttonText && buttonLink) {
        buttonElement.textContent = buttonText;
        buttonElement.href = buttonLink;
        buttonElement.style.display = 'inline-block';
        // Устанавливаем target для внешних ссылок
        if (buttonLink.startsWith('http')) {
            buttonElement.target = '_blank';
            buttonElement.rel = 'noopener noreferrer';
        } else {
            buttonElement.target = '_self';
            buttonElement.rel = '';
        }
    } else {
        buttonElement.style.display = 'none';
    }
    
    // Обновляем изображение при выборе файла
    if (imageInput.files && imageInput.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-image').src = e.target.result;
        }
        reader.readAsDataURL(imageInput.files[0]);
    }
}

let previewSwiper;

document.addEventListener('DOMContentLoaded', function() {
    setupCharCounter('title', 'title-counter', 100);
    setupCharCounter('description', 'description-counter', 500);
    
    // Инициализация Swiper для предварительного просмотра
    previewSwiper = new Swiper('#preview-services-swiper', {
        slidesPerView: 1,
        spaceBetween: 0,
        loop: false,
        allowTouchMove: false, // Отключаем свайпы в превью
        breakpoints: {
            320: {
                slidesPerView: 1,
                spaceBetween: 0,
            }
        }
    });
    
    // Добавляем обработчики событий для обновления предварительного просмотра
    document.getElementById('title').addEventListener('input', updatePreview);
    document.getElementById('description').addEventListener('input', updatePreview);
    document.getElementById('price').addEventListener('input', updatePreview);
    document.getElementById('price_type').addEventListener('change', updatePreview);
    document.getElementById('button_text').addEventListener('change', updatePreview);
    document.getElementById('button_link').addEventListener('change', updatePreview);
    document.getElementById('image').addEventListener('change', updatePreview);
    
    // Инициализируем предварительный просмотр
    updatePreview();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\link\resources\views/admin/services/create.blade.php ENDPATH**/ ?>