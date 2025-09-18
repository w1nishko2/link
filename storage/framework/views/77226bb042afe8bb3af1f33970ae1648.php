
<section class="gallery" id="gallery" aria-label="Галерея работ">
    <div class="container">
        <?php if((isset($section) && (!empty(trim($section->title)) || !empty(trim($section->subtitle)))) || !isset($section)): ?>
        <header class="gallery-header mb-4">
            <?php if(isset($section)): ?>
                <?php if(!empty(trim($section->title))): ?>
                    <h2><?php echo e($section->title); ?></h2>
                <?php endif; ?>
                
                <?php if(!empty(trim($section->subtitle))): ?>
                    <p class="text-muted"><?php echo e($section->subtitle); ?></p>
                <?php endif; ?>
            <?php else: ?>
                <h2><?php echo e($currentUser && $currentUser->id === $pageUser->id ? 'Моя галерея' : 'Галерея работ ' . $pageUser->name); ?></h2>
                <p class="text-muted">Портфолио и примеры работ</p>
            <?php endif; ?>
        </header>
        <?php endif; ?>
     
        <div class="gallery-wrapper">
            <?php if(!empty($galleryBlocks)): ?>
                <!-- Swiper галерея -->
                <div class="gallery-swiper swiper">
                    <div class="swiper-wrapper">
                        
                        <?php if($currentUser && $currentUser->id === $pageUser->id): ?>
                            <div class="swiper-slide">
                                <a href="<?php echo e(route('admin.gallery.create', $currentUser->id)); ?>" class="owner-default-block gallery-add">
                                    <div class="owner-default-icon"></div>
                                    <div class="owner-default-text">
                                        <div class="owner-default-title">Добавить фото</div>
                                        <div class="owner-default-subtitle">Покажите свои работы</div>
                                    </div>
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php $__currentLoopData = $galleryBlocks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $block): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $__currentLoopData = $block['images']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="swiper-slide">
                                    <figure class="gallery-item editable-item" data-bs-toggle="modal" data-bs-target="#galleryModal"
                                        data-image="<?php echo e($image['src']); ?>" data-alt="<?php echo e($image['alt']); ?>">
                                        <img src="<?php echo e($image['src']); ?>" 
                                             alt="<?php echo e($image['alt'] ?: 'Работа из портфолио ' . $pageUser->name); ?>" 
                                             loading="lazy" 
                                             width="300"
                                             height="200"
                                             decoding="async"
                                             itemscope 
                                             itemtype="https://schema.org/ImageObject">
                                        <div class="gallery-item-overlay">
                                            <figcaption class="gallery-item-text"><?php echo e($image['alt'] ?: 'Портфолио'); ?></figcaption>
                                        </div>
                                    </figure>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    
                 
                </div>
            <?php else: ?>
                
                <?php if($currentUser && $currentUser->id === $pageUser->id): ?>
                    <div class=" justify-content-center">
                        <a href="<?php echo e(route('admin.gallery.create', $currentUser->id)); ?>" class="owner-default-block gallery-add" style="max-width: 400px;">
                            <div class="owner-default-icon"></div>
                            <div class="owner-default-text">
                                <div class="owner-default-title">Добавить фото</div>
                                <div class="owner-default-subtitle">Покажите свои работы</div>
                            </div>
                        </a>
                    </div>
                <?php else: ?>
                    <div class="text-center">
                        <p class="text-muted">Галерея пуста</p>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Модальное окно для просмотра изображений -->
    <div class="modal fade" id="galleryModal" tabindex="-1" aria-labelledby="galleryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="galleryModalLabel">Просмотр изображения</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>
</section><?php /**PATH C:\OSPanel\domains\link\resources\views/sections/gallery.blade.php ENDPATH**/ ?>