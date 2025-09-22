

<?php $__env->startSection('title', 'Управление галереей - ' . config('app.name')); ?>
<?php $__env->startSection('description', 'Управление изображениями галереи: загрузка, редактирование, организация'); ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
     <a href="<?php echo e(route('admin.gallery.create', $currentUserId)); ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>
        <span class="d-none d-sm-inline">Добавить изображение</span>
        <span class="d-sm-none">Добавить</span>
    </a>
</div>

<?php if($images->count() > 0): ?>
    <!-- Блок со слайдером изображений -->
    <div class="row justify-content-center mb-4">
        <div class="col-12">
            <div class="swiper gallery-swiper" id="gallery-preview-swiper" style="padding:0; padding-left: 0 !important">
                <div class="swiper-wrapper">
                    <?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="swiper-slide">
                            <div class="card h-100 gallery-image clickable-card" onclick="editImage(<?php echo e($image->id); ?>)">
                                <div class="image-container position-relative">
                                    <?php if($image->image_path): ?>
                                        <img src="<?php echo e(asset('storage/' . $image->image_path)); ?>" 
                                             class="card-img-top" 
                                             alt="<?php echo e($image->alt_text ?? $image->title); ?>"
                                             style="height: 350px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="card-img-top d-flex align-items-center justify-content-center bg-light text-muted" 
                                             style="height: 350px;">
                                            <i class="bi bi-image display-4"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                          
                                    <div class="edit-overlay">
                                        <i class="bi bi-pencil-square"></i>
                                        <span>Редактировать</span>
                                    </div>
                                </div>
                                
                                <div class="card-body">
                                    <h6 class="card-title"><?php echo e($image->title ?? 'Без названия'); ?></h6>
                                    <?php if($image->alt_text): ?>
                                        <p class="card-text text-muted small"><?php echo e($image->alt_text); ?></p>
                                    <?php endif; ?>
                                    
                                    <!-- Действия с изображением -->
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <small class="text-muted">
                                            <?php echo e($image->created_at->format('d.m.Y')); ?>

                                        </small>
                                        <div class="btn-group" role="group">
                                            <button type="button" 
                                                    class="btn btn-sm 
                                                    onclick="event.stopPropagation(); editImage(<?php echo e($image->id); ?>)"
                                                    title="Редактировать">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    onclick="event.stopPropagation(); deleteImage(<?php echo e($image->id); ?>)"
                                                    title="Удалить">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="text-center py-5">
        <i class="bi bi-images display-1 text-muted"></i>
        <h3 class="mt-3">Галерея пуста</h3>
        <p class="text-muted">Добавьте первое изображение в галерею</p>
        <a href="<?php echo e(route('admin.gallery.create', $currentUserId)); ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>
            Добавить изображение
        </a>
    </div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<style>
/* Стили для кликабельных карточек */
.clickable-card {
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    position: relative;
}

.clickable-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.edit-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: white;
    opacity: 0;
    transition: opacity 0.3s ease;
    border-radius: inherit;
}

.image-container {
    position: relative;
    overflow: hidden;
}

.clickable-card:hover .edit-overlay {
    opacity: 1;
}

.edit-overlay i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.edit-overlay span {
    font-size: 0.9rem;
    font-weight: 500;
}

/* Стили для навигации слайдера */
.swiper-button-next,
.swiper-button-prev {
    color: #007bff;
    background: rgba(255, 255, 255, 0.9);
    width: 40px;
    height: 40px;
    margin-top: -20px;
    border-radius: 50%;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.swiper-button-next:after,
.swiper-button-prev:after {
    font-size: 18px;
    font-weight: 600;
}

/* Убираем пагинацию */
.swiper-pagination {
    display: none;
}

/* Настройки высоты слайдера */
.gallery-swiper {
    width: 100%;
    height: 400px;
    padding: 20px 0;
}

@media (max-width: 768px) {
    .gallery-swiper {
        height: 350px;
    }
}

@media (max-width: 480px) {
    .gallery-swiper {
        height: 350px;
    }
}
</style>

<script>
// Инициализация Swiper
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('gallery-preview-swiper')) {
        new Swiper('#gallery-preview-swiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                576: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                992: {
                    slidesPerView: 3,
                    spaceBetween: 20,
                },
                1200: {
                    slidesPerView: 4,
                    spaceBetween: 25,
                },
                1400: {
                    slidesPerView: 4,
                    spaceBetween: 25,
                },
            },
            autoHeight: false,
            centeredSlides: false,
            loop: false,
            grabCursor: true,
            watchOverflow: true,
            resistance: true,
            resistanceRatio: 0.85,
        });
    }
});

// Функция для редактирования изображения
function editImage(imageId) {
    window.location.href = "<?php echo e(route('admin.gallery.edit', [$currentUserId, ':id'])); ?>".replace(':id', imageId);
}

// Функция для удаления изображения
function deleteImage(imageId) {
    if (confirm('Вы уверены, что хотите удалить это изображение? Это действие нельзя отменить.')) {
        const form = document.getElementById('deleteImageForm');
        const url = "<?php echo e(route('admin.gallery.destroy', [$currentUserId, ':id'])); ?>".replace(':id', imageId);
        form.action = url;
        form.submit();
    }
}
</script>

<!-- Form для удаления изображения -->
<form id="deleteImageForm" method="POST" style="display: none;">
    <?php echo csrf_field(); ?>
    <?php echo method_field('DELETE'); ?>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\link\resources\views/admin/gallery/index.blade.php ENDPATH**/ ?>