
<section class="services" aria-label="Услуги">
    <div class="container">
        <?php if(isset($section) && (!empty(trim($section->title)) || !empty(trim($section->subtitle)))): ?>
            <header class="services-header mb-4 ">
                <?php if(!empty(trim($section->title))): ?>
                    <h2><?php echo e($section->title); ?></h2>
                <?php endif; ?>
                <?php if(!empty(trim($section->subtitle))): ?>
                    <p class="text-muted"><?php echo e($section->subtitle); ?></p>
                <?php endif; ?>
            </header>
        <?php endif; ?>
        
        <div class="swiper services-swiper">
            <div class="swiper-wrapper">
               
                <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="swiper-slide">
                        <div class="service-card" data-analytics="service" data-analytics-id="<?php echo e($service->id); ?>"
                            data-analytics-text="<?php echo e($service->title); ?>" data-service-title="<?php echo e($service->title); ?>"
                            data-service-description="<?php echo e($service->description); ?>" data-service-price="<?php echo e($service->formatted_price ?? ''); ?>"
                            data-service-image="<?php echo e($service->image_path ? asset('storage/' . $service->image_path) : '/hero.png'); ?>"
                            data-service-button-text="<?php echo e($service->button_text ?? ''); ?>"
                            data-service-button-link="<?php echo e($service->button_link ?? ''); ?>"
                            style="cursor: pointer;">
                            <div class="service-image">
                                <?php if($service->image_path): ?>
                                    <img src="<?php echo e(asset('storage/' . $service->image_path)); ?>"
                                         alt="<?php echo e($service->title); ?>" 
                                         loading="lazy"
                                         width="300"
                                         height="600"
                                         decoding="async">
                                <?php else: ?>
                                    <img src="/hero.png" 
                                         alt="<?php echo e($service->title); ?>" 
                                         loading="lazy"
                                         width="300"
                                         height="600"
                                         decoding="async">
                                <?php endif; ?>
                            </div>
                            <div class="service-content">
                                <h3><?php echo e($service->title); ?></h3>
                                <p><?php echo e($service->description); ?></p>
                                <div class="service-bottom">
                                    <?php if($service->price): ?>
                                        <div class="service-price"><?php echo e($service->formatted_price); ?></div>
                                    <?php endif; ?>
                                    <?php if($service->button_text && $service->button_link): ?>
                                        <a href="<?php echo e($service->button_link); ?>" 
                                           class="service-button btn btn-primary btn-sm"
                                           target="<?php echo e(str_starts_with($service->button_link, 'http') ? '_blank' : '_self'); ?>"
                                           rel="<?php echo e(str_starts_with($service->button_link, 'http') ? 'noopener noreferrer' : ''); ?>">
                                            <?php echo e($service->button_text); ?>

                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                
                <?php if($currentUser && $currentUser->id === $pageUser->id): ?>
                    <div class="swiper-slide">
                        <a href="<?php echo e(route('admin.services.create', $currentUser->id)); ?>" class="owner-default-block service-add">
                            <div class="owner-default-icon"></div>
                            <div class="owner-default-text">
                                <div class="owner-default-title">Добавить услугу</div>
                                <div class="owner-default-subtitle">Расскажите о своих услугах</div>
                            </div>
                        </a>
                    </div>
                <?php endif; ?>

                
                <?php if($services->count() === 0 && (!$currentUser || $currentUser->id !== $pageUser->id)): ?>
                    <div class="swiper-slide">
                        <div class="service-card text-center">
                            <h3>Услуги не найдены</h3>
                            <p>Здесь будут отображены услуги</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</section><?php /**PATH C:\OSPanel\domains\link\resources\views/sections/services.blade.php ENDPATH**/ ?>