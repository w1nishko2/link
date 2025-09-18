

<?php $__env->startSection('title', 'Создание услуги - ' . config('app.name')); ?>
<?php $__env->startSection('description', 'Добавление новой услуги в каталог'); ?>

<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<?php echo app('Illuminate\Foundation\Vite')(['resources/css/services-reels.css', 'resources/css/admin-services.css', 'resources/js/admin-services.js']); ?>

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
    <div class="col-lg-8 col-xl-6">
       
         
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

<!-- Loading Overlay -->
<div id="loadingOverlay" class="loading-overlay">
    <div class="loading-content">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Загрузка...</span>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
// Инициализация страницы создания услуги
document.addEventListener('DOMContentLoaded', function() {
    // Инициализируем с пустым состоянием для новой услуги
    initServicePage();
    
    // Загружаем старые значения если есть ошибки валидации
    <?php if(old()): ?>
        const oldValues = <?php echo json_encode(old(), 15, 512) ?>;
        Object.keys(oldValues).forEach(key => {
            const element = document.querySelector(`[data-field="${key}"]`);
            if (element && oldValues[key]) {
                element.textContent = oldValues[key];
            }
        });
    <?php endif; ?>
    
    // Показываем ошибки если они есть
    <?php if($errors->any()): ?>
        const errorMessages = <?php echo json_encode($errors->all(), 15, 512) ?>;
        if (errorMessages.length > 0) {
            showNotification('Исправьте ошибки:\n' + errorMessages.join('\n'), 'error');
        }
    <?php endif; ?>
});
</script>

<?php $__env->stopSection(); ?>
   



<?php echo $__env->make('admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\link\resources\views/admin/services/create.blade.php ENDPATH**/ ?>