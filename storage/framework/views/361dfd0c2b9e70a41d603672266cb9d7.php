
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['banners', 'currentUserId']) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['banners', 'currentUserId']); ?>
<?php foreach (array_filter((['banners', 'currentUserId']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<style>
/* Стили для превью баннеров в админке */
.banners-preview-container {
    border: 2px solid #e9ecef;
    border-radius: 15px;
    padding: 20px;
    background: #f8f9fa;
    margin-bottom: 20px;
}

.banners-preview-container .banners-swiper {
    width: 100%;
    max-width: 100%;
    height: 200px;
    position: relative;
}

.banners-preview-container .banners-banner {
    display: flex;
    padding: 10px;
    height: 180px;
    background: #f1f1f1;
    gap: 15px;
    border-radius: 15px;
    position: relative;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 100%;
    user-select: none;
}

.banners-preview-container .banners-banner:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.banners-preview-container .banners-banner-block {
    display: flex;
    flex-direction: column;
    flex: 1;
    position: relative;
    overflow: hidden;
    padding: 10px;
}

.banners-preview-container .banners-banner-block h3 {
    margin-bottom: 8px;
    font-size: 16px;
    color: #333;
    font-weight: 600;
}

.banners-preview-container .banners-banner-block p {
    color: #666;
    line-height: 1.4;
    font-size: 12px;
    margin: 0;
}

.banners-preview-container .banners-banner-block-img {
    width: 100px;
    height: 100%;
    flex-shrink: 0;
}

.banners-preview-container .banners-banner-block-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 10px;
    transition: transform 0.3s ease;
}

.banners-preview-container .banners-banner:hover .banners-banner-block-img img {
    transform: scale(1.05);
}

/* Кнопки управления */
.banner-admin-controls {
    position: absolute;
    top: 5px;
    right: 5px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.banners-banner:hover .banner-admin-controls {
    opacity: 1;
}

.banner-admin-controls .btn {
    padding: 4px 8px;
    font-size: 12px;
    margin-left: 2px;
}

/* Индикатор статуса */
.banner-status-indicator {
    position: absolute;
    bottom: 5px;
    left: 5px;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 10px;
    font-weight: 500;
}

.banner-status-active {
    background: #d4edda;
    color: #155724;
}

.banner-status-inactive {
    background: #f8d7da;
    color: #721c24;
}

/* Превью заголовок */
.banners-preview-header {
    display: flex;
    justify-content-between;
    align-items: center;
    margin-bottom: 15px;
}

.banners-preview-header h6 {
    margin: 0;
    color: #495057;
    font-weight: 600;
}

/* Навигация Swiper */
.banners-preview-container .swiper-button-next,
.banners-preview-container .swiper-button-prev {
    background: rgba(255, 255, 255, 0.9);
    width: 30px;
    height: 30px;
    border-radius: 50%;
    border: 1px solid #dee2e6;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    color: #495057;
    transition: all 0.3s ease;
}

.banners-preview-container .swiper-button-next:after,
.banners-preview-container .swiper-button-prev:after {
    font-size: 12px;
    font-weight: 600;
}

.banners-preview-container .swiper-button-next:hover,
.banners-preview-container .swiper-button-prev:hover {
    background: #0d6efd;
    color: white;
    border-color: #0d6efd;
}

.banners-preview-container .swiper-button-next {
    right: -15px;
}

.banners-preview-container .swiper-button-prev {
    left: -15px;
}

/* Адаптивность */
@media (max-width: 768px) {
    .banners-preview-container .banners-banner {
        height: 120px;
        gap: 10px;
        padding: 8px;
    }
    
    .banners-preview-container .banners-banner-block-img {
        width: 80px;
    }
    
    .banners-preview-container .banners-banner-block h3 {
        font-size: 14px;
    }
    
    .banners-preview-container .banners-banner-block p {
        font-size: 11px;
    }
    
    .banners-preview-container .swiper-button-next,
    .banners-preview-container .swiper-button-prev {
        display: none;
    }
}
</style>

<div class="banners-preview-container">
    <div class="banners-preview-header">
        <h6><i class="bi bi-eye me-2"></i>Как выглядят на странице</h6>
        <small class="text-muted"><?php echo e($banners->count()); ?> <?php echo e($banners->count() == 1 ? 'баннер' : ($banners->count() > 4 ? 'баннеров' : 'баннера')); ?></small>
    </div>
    
    <?php if($banners->count() > 0): ?>
        <div class="swiper banners-swiper">
            <div class="swiper-wrapper">
                <?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="swiper-slide">
                        <div class="banners-banner" 
                             <?php if($banner->link_url): ?> 
                                 onclick="window.open('<?php echo e($banner->link_url); ?>', '_blank')" 
                                 style="cursor: pointer"
                             <?php endif; ?>>
                            
                            <!-- Кнопки управления -->
                            <div class="banner-admin-controls">
                                <a href="<?php echo e(route('admin.banners.edit', [$currentUserId, $banner])); ?>" 
                                   class="btn btn-primary btn-sm" 
                                   onclick="event.stopPropagation()" 
                                   title="Редактировать">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-danger btn-sm" 
                                        onclick="event.stopPropagation(); deleteBanner(<?php echo e($banner->id); ?>)"
                                        title="Удалить">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                            
                            <!-- Индикатор статуса -->
                            <div class="banner-status-indicator <?php echo e($banner->is_active ? 'banner-status-active' : 'banner-status-inactive'); ?>">
                                <?php echo e($banner->is_active ? 'Активен' : 'Неактивен'); ?>

                            </div>
                            
                            <div class="banners-banner-block">
                                <h3><?php echo e($banner->title); ?></h3>
                                <?php if($banner->description): ?>
                                    <p><?php echo e(Str::limit($banner->description, 80)); ?></p>
                                <?php endif; ?>
                                <?php if($banner->link_url): ?>
                                    <div class="mt-auto">
                                        <small class="text-primary">
                                            <i class="bi bi-link-45deg"></i>
                                            <?php echo e($banner->link_text ?: 'Перейти'); ?>

                                        </small>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="banners-banner-block-img">
                                <?php if($banner->image_path): ?>
                                    <img src="<?php echo e(asset('storage/' . $banner->image_path)); ?>"
                                         alt="<?php echo e($banner->title); ?>"
                                         loading="lazy"
                                         decoding="async">
                                <?php else: ?>
                                    <div class="d-flex align-items-center justify-content-center h-100 bg-light" style="border-radius: 10px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            
            <!-- Навигация для множественных баннеров -->
            <?php if($banners->count() > 1): ?>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-4">
            <i class="bi bi-megaphone text-muted" style="font-size: 2rem;"></i>
            <p class="text-muted mb-0">Баннеры будут отображаться здесь</p>
        </div>
    <?php endif; ?>
</div>

<?php if($banners->count() > 1): ?>
    <?php $__env->startPush('scripts'); ?>
    <script>
    // Инициализация Swiper для превью баннеров
    document.addEventListener('DOMContentLoaded', function() {
        new Swiper('.banners-preview-container .banners-swiper', {
            slidesPerView: 'auto',
            spaceBetween: 15,
            navigation: {
                nextEl: '.banners-preview-container .swiper-button-next',
                prevEl: '.banners-preview-container .swiper-button-prev',
            },
            breakpoints: {
                640: {
                    slidesPerView: 1,
                    spaceBetween: 10,
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 15,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 20,
                }
            }
        });
    });
    </script>
    <?php $__env->stopPush(); ?>
<?php endif; ?><?php /**PATH C:\OSPanel\domains\link\resources\views/components/admin-banners-preview.blade.php ENDPATH**/ ?>