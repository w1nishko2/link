
<?php if($banners->count() > 0 || (isset($section) && ($section->title || $section->subtitle)) || ($currentUser && $currentUser->id === $pageUser->id)): ?>
<section class="banners" aria-label="Рекламные блоки">
    <div class="container">
        <?php if(isset($section) && (!empty(trim($section->title)) || !empty(trim($section->subtitle)))): ?>
            <header class="banners-header mb-4 ">
                <?php if(!empty(trim($section->title))): ?>
                    <h2><?php echo e($section->title); ?></h2>
                <?php else: ?>
                    <h2 class="visually-hidden">Реклама и предложения</h2>
                <?php endif; ?>
                <?php if(!empty(trim($section->subtitle))): ?>
                    <p class="text-muted"><?php echo e($section->subtitle); ?></p>
                <?php endif; ?>
            </header>
        <?php else: ?>
            <h2 class="visually-hidden">Реклама и предложения</h2>
        <?php endif; ?>
        
        <div class="swiper banners-swiper">
            <div class="swiper-wrapper">
                <?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="swiper-slide">
                        <div class="banners-banner" data-analytics="banner" data-analytics-id="<?php echo e($banner->id); ?>"
                            data-analytics-text="<?php echo e($banner->title); ?>" data-link-url="<?php echo e($banner->link_url); ?>" data-link-text="<?php echo e($banner->link_text); ?>">
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
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                
                <?php if($currentUser && $currentUser->id === $pageUser->id): ?>
                    <div class="swiper-slide">
                        <a href="<?php echo e(route('admin.banners', $currentUser->id)); ?>" class="owner-default-block banner-add">
                            <div class="owner-default-icon"></div>
                            <div class="owner-default-text">
                                <div class="owner-default-title">Добавить баннер</div>
                                <div class="owner-default-subtitle">Разместите рекламу или объявления</div>
                            </div>
                        </a>
                    </div>
                <?php endif; ?>

                
                <?php if($banners->count() === 0 && (!$currentUser || $currentUser->id !== $pageUser->id)): ?>
                    <div class="swiper-slide">
                        <div class="banners-banner">
                            <div class="banners-banner-block">
                                <h3>Добро пожаловать!</h3>
                                <p>Здесь будут размещены баннеры</p>
                            </div>
                            <div class="banners-banner-block-img">
                                <img src="/hero.png" 
                                     alt="Добро пожаловать"
                                     loading="lazy"
                                     width="300"
                                     height="200"
                                     decoding="async">
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?><?php /**PATH C:\OSPanel\domains\link\resources\views\sections\banners.blade.php ENDPATH**/ ?>