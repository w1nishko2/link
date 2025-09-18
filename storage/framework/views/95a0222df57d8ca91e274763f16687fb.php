

<?php $__env->startSection('title', 'Управление баннерами'); ?>       
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="<?php echo e(route('admin.banners.create', $currentUserId)); ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Добавить баннер
    </a>
</div>
<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?php if($banners->count() > 0): ?>
    <div class="swiper banners-swiper" id="banners-preview-swiper">
        <div class="swiper-wrapper">
                <?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="swiper-slide">
                        <div class="banner-card clickable-card" onclick="editBanner(<?php echo e($banner->id); ?>)">
                            <div class="banners-banner">
                                <div class="banners-banner-block">
                                    <h3><?php echo e($banner->title); ?></h3>
                                    <?php if($banner->description): ?>
                                        <p><?php echo e($banner->description); ?></p>
                                    <?php endif; ?>
                                </div>  
                                
                                <div class="banners-banner-block-img">
                                    <?php if($banner->image_path): ?>
                                        <img src="<?php echo e(asset('storage/' . $banner->image_path)); ?>" 
                                             alt="<?php echo e($banner->title); ?>"
                                             loading="lazy"
                                             width="300"
                                             height="200"
                                             decoding="async">
                                    <?php else: ?>
                                        <div class="banner-no-image">
                                            <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="edit-overlay">
                                    <i class="bi bi-pencil-fill"></i>
                                    <span>Редактировать</span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
<?php else: ?>
    <div class="text-center py-5">
        <i class="bi bi-megaphone display-1 text-muted"></i>
        <h3 class="mt-3">Нет баннеров</h3>
        <p class="text-muted">Добавьте первый баннер</p>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBannerModal">
            <i class="bi bi-plus-circle me-2"></i>
            Добавить баннер
        </button>
    </div>
<?php endif; ?>

<!-- Модальное окно создания баннера -->
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
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger">Удалить</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Инициализация слайдера и других функций
});
function deleteBanner(bannerId) {
    const form = document.getElementById('deleteForm');
    form.action = `/admin/user/<?php echo e($currentUserId); ?>/banners/${bannerId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
function editBanner(bannerId) {
    window.location.href = `/admin/user/<?php echo e($currentUserId); ?>/banners/${bannerId}/edit`;
}
</script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Инициализация Swiper для баннеров в админке
    const bannersSwiper = new Swiper('#banners-preview-swiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            576: {
                slidesPerView: 1,
                spaceBetween: 20,
            },
            768: {
                slidesPerView: 1,
                spaceBetween: 20,
            },
            992: {
                slidesPerView: 2,
                spaceBetween: 20,
            },
            1200: {
                slidesPerView: 2,
                spaceBetween: 25,
            },
            1400: {
                slidesPerView: 3,
                spaceBetween: 25,
            },
        },
        // Настройки для админки
        autoHeight: false,
        centeredSlides: false,
        loop: false,
        grabCursor: true,
        watchOverflow: true,
        resistance: true,
        resistanceRatio: 0.85,
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\link\resources\views\admin\banners\index.blade.php ENDPATH**/ ?>