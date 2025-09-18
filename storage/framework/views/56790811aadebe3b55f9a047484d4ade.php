

<?php $__env->startSection('title', 'Управление услугами - ' . config('app.name')); ?>
<?php $__env->startSection('description', 'Управление каталогом услуг: создание, редактирование, настройка цен'); ?>

<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<?php echo app('Illuminate\Foundation\Vite')(['resources/css/services-reels.css', 'resources/css/admin-services.css', 'resources/js/admin-services.js']); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
    <h1 class="h4 mb-0">Услуги (<?php echo e($services->count()); ?>)</h1>
    <a href="<?php echo e(route('admin.services.create', $currentUserId)); ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>
        <span class="d-none d-sm-inline">Добавить услугу</span>
        <span class="d-sm-none">Добавить</span>
    </a>
</div>

<?php if($services->count() > 0): ?>
    <!-- Блок со слайдером услуг -->
    <div class="row justify-content-center mb-4">
        <div class="col-12">
         
                <div class="swiper services-swiper" id="services-preview-swiper">
                    <div class="swiper-wrapper">
                        <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="swiper-slide">
                                <div class="service-card clickable-card" onclick="editService(<?php echo e($service->id); ?>)">
                                    <!-- Изображение услуги -->
                                    <div class="service-image">
                                        <img src="<?php echo e($service->image_path ? asset('storage/' . $service->image_path) : '/hero.png'); ?>" 
                                             alt="<?php echo e($service->title); ?>" 
                                             loading="lazy"
                                             width="300"
                                             height="600"
                                             decoding="async">
                                        <div class="edit-overlay">
                                            <i class="bi bi-pencil-fill"></i>
                                            <span>Редактировать</span>
                                        </div>
                                    </div>
                                    
                                    <div class="service-content">
                                        <!-- Название услуги -->
                                        <h3><?php echo e($service->title); ?></h3>
                                        
                                        <!-- Описание услуги -->
                                        <p><?php echo e($service->description); ?></p>
                                        
                                        <div class="service-bottom">
                                            <?php if($service->price): ?>
                                                <!-- Цена -->
                                                <div class="service-price"><?php echo e($service->formatted_price); ?></div>
                                            <?php endif; ?>
                                            
                                            <div class="service-buttons">
                                                <?php if($service->button_text && $service->button_link): ?>
                                                    <!-- Кнопка услуги -->
                                                    <div class="service-button btn btn-primary btn-sm">
                                                        <?php echo e($service->button_text); ?>

                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <!-- Навигация слайдера -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            
        </div>
    </div>
<?php else: ?>
    <div class="text-center py-5">
        <i class="bi bi-briefcase display-1 text-muted"></i>
        <h3 class="mt-3">Нет услуг</h3>
        <p class="text-muted">Добавьте первую услугу</p>
        <a href="<?php echo e(route('admin.services.create', $currentUserId)); ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>
            Добавить услугу
        </a>
    </div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
// Инициализация Swiper для просмотра услуг
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('services-preview-swiper')) {
        new Swiper('#services-preview-swiper', {
            slidesPerView: 2.4,
            spaceBetween: 20,
            loop: <?php echo e($services->count() > 1 ? 'true' : 'false'); ?>,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                320: {
                    slidesPerView: 1.1,
                    spaceBetween: 20,
                },
                480: {
                    slidesPerView: 2.1,
                    spaceBetween: 20,
                },
                700: {
                    slidesPerView: 2.1,
                    spaceBetween: 20,
                },
                768: {
                    slidesPerView: 2.1,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: 2.1,
                    spaceBetween: 20,
                },
                1200: {
                    slidesPerView: 3.2,
                    spaceBetween: 20,
                }
            }
        });
    }
});

// Функция для редактирования услуги
function editService(serviceId) {
    window.location.href = "<?php echo e(route('admin.services.edit', [$currentUserId, ':id'])); ?>".replace(':id', serviceId);
}

// Функция для удаления услуги
function deleteService(serviceId) {
    if (confirm('Вы уверены, что хотите удалить эту услугу?')) {
        const form = document.getElementById('deleteServiceForm');
        form.action = "<?php echo e(route('admin.services.destroy', [$currentUserId, ':id'])); ?>".replace(':id', serviceId);
        form.submit();
    }
}
</script>

<!-- Form для удаления услуги -->
<form id="deleteServiceForm" action="" method="POST" style="display: none;">
    <?php echo csrf_field(); ?>
    <?php echo method_field('DELETE'); ?>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\link\resources\views/admin/services/index.blade.php ENDPATH**/ ?>