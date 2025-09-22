

<?php $__env->startSection('title', $pageUser->name . ' - ' . $pageUser->username . ' | Персональная страница'); ?>
<?php $__env->startSection('description', $pageUser->bio ? Str::limit(strip_tags($pageUser->bio), 160) : 'Персональная страница ' . $pageUser->name . '. Услуги, статьи, портфолио и контакты.'); ?>
<?php $__env->startSection('keywords', 'персональная страница, ' . strtolower($pageUser->name) . ', услуги, портфолио, контакты, ' . strtolower($pageUser->username)); ?>
<?php $__env->startSection('author', $pageUser->name); ?>

<?php $__env->startSection('og_type', 'profile'); ?>
<?php $__env->startSection('og_title', $pageUser->name . ' - Персональная страница'); ?>
<?php $__env->startSection('og_description', $pageUser->bio ? Str::limit(strip_tags($pageUser->bio), 200) : 'Персональная страница ' . $pageUser->name . '. Узнайте больше о моих услугах и проектах.'); ?>
<?php $__env->startSection('og_url', request()->url()); ?>
<?php $__env->startSection('og_image', $pageUser->avatar ? asset('storage/' . $pageUser->avatar) : ($pageUser->background_image ? asset('storage/' . $pageUser->background_image) : asset('/hero.png'))); ?>

<?php $__env->startSection('twitter_title', $pageUser->name . ' - ' . $pageUser->username); ?>
<?php $__env->startSection('twitter_description', $pageUser->bio ? Str::limit(strip_tags($pageUser->bio), 160) : 'Персональная страница с услугами и портфолио'); ?>
<?php $__env->startSection('twitter_image', $pageUser->avatar ? asset('storage/' . $pageUser->avatar) : asset('/hero.png')); ?>

<?php $__env->startSection('canonical_url', route('user.show', $pageUser->username)); ?>

<?php $__env->startPush('head'); ?>
<!-- Structured Data (JSON-LD) -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Person",
    "name": "<?php echo e($pageUser->name); ?>",
    "alternateName": "<?php echo e($pageUser->username); ?>",
    "description": "<?php echo e($pageUser->bio ? strip_tags($pageUser->bio) : 'Персональная страница специалиста'); ?>",
    "url": "<?php echo e(route('user.show', $pageUser->username)); ?>",
    <?php if($pageUser->avatar): ?>
    "image": "<?php echo e(asset('storage/' . $pageUser->avatar)); ?>",
    <?php endif; ?>
    "sameAs": [
        <?php if($pageUser->telegram_url): ?>"<?php echo e($pageUser->telegram_url); ?>"<?php endif; ?>
        <?php if($pageUser->whatsapp_url && $pageUser->telegram_url): ?>, <?php endif; ?>
        <?php if($pageUser->whatsapp_url): ?>"<?php echo e($pageUser->whatsapp_url); ?>"<?php endif; ?>
        <?php if($pageUser->vk_url && ($pageUser->telegram_url || $pageUser->whatsapp_url)): ?>, <?php endif; ?>
        <?php if($pageUser->vk_url): ?>"<?php echo e($pageUser->vk_url); ?>"<?php endif; ?>
        <?php if($pageUser->youtube_url && ($pageUser->telegram_url || $pageUser->whatsapp_url || $pageUser->vk_url)): ?>, <?php endif; ?>
        <?php if($pageUser->youtube_url): ?>"<?php echo e($pageUser->youtube_url); ?>"<?php endif; ?>
        <?php if($pageUser->ok_url && ($pageUser->telegram_url || $pageUser->whatsapp_url || $pageUser->vk_url || $pageUser->youtube_url)): ?>, <?php endif; ?>
        <?php if($pageUser->ok_url): ?>"<?php echo e($pageUser->ok_url); ?>"<?php endif; ?>
    ]
}
</script>

<?php if($services->count() > 0): ?>
<!-- Services Structured Data -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Service",
    "serviceType": "Professional Services",
    "provider": {
        "@type": "Person",
        "name": "<?php echo e($pageUser->name); ?>",
        "url": "<?php echo e(route('user.show', $pageUser->username)); ?>"
    },
    "areaServed": "Online",
    "availableChannel": {
        "@type": "ServiceChannel",
        "serviceUrl": "<?php echo e(route('user.show', $pageUser->username)); ?>"
    }
}
</script>
<?php endif; ?>

<?php if(count($galleryBlocks) > 0): ?>
<!-- Gallery/Portfolio Structured Data -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ImageGallery",
    "name": "Портфолио <?php echo e($pageUser->name); ?>",
    "description": "Галерея работ и проектов",
    "author": {
        "@type": "Person",
        "name": "<?php echo e($pageUser->name); ?>",
        "url": "<?php echo e(route('user.show', $pageUser->username)); ?>"
    },
    "url": "<?php echo e(route('user.show', $pageUser->username)); ?>#gallery"
}
</script>
<?php endif; ?>

<?php if($articles->count() > 0): ?>
<!-- Blog/Articles Structured Data -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Blog",
    "name": "Блог <?php echo e($pageUser->name); ?>",
    "description": "Статьи и полезные материалы",
    "author": {
        "@type": "Person",
        "name": "<?php echo e($pageUser->name); ?>",
        "url": "<?php echo e(route('user.show', $pageUser->username)); ?>"
    },
    "url": "<?php echo e(route('user.show', $pageUser->username)); ?>#articles",
    "blogPost": [
        <?php $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        {
            "@type": "BlogPosting",
            "headline": "<?php echo e($article->title); ?>",
            "description": "<?php echo e($article->excerpt); ?>",
            "url": "<?php echo e(route('articles.show', ['username' => $pageUser->username, 'slug' => $article->slug])); ?>",
            "datePublished": "<?php echo e($article->created_at->toISOString()); ?>",
            "author": {
                "@type": "Person",
                "name": "<?php echo e($pageUser->name); ?>"
            }
        }<?php if($index < $articles->count() - 1): ?>,<?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    ]
}
</script>
<?php endif; ?>  
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
   

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <main role="main">
        
        <?php
            $orderedSections = $sectionSettings->sortBy('order');
        ?>
        
        <?php $__currentLoopData = $orderedSections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($section->is_visible): ?>
                <?php if($section->section_key === 'hero'): ?>
                    <?php echo $__env->make('sections.hero', ['section' => $section], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php elseif($section->section_key === 'services'): ?>
                    <?php echo $__env->make('sections.services', ['section' => $section], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php elseif($section->section_key === 'gallery'): ?>
                    <?php echo $__env->make('sections.gallery', ['section' => $section], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php elseif($section->section_key === 'articles'): ?>
                    <?php echo $__env->make('sections.articles', ['section' => $section], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php elseif($section->section_key === 'banners'): ?>
                    <?php echo $__env->make('sections.banners', ['section' => $section], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endif; ?>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <?php if($orderedSections->isEmpty()): ?>
            <?php echo $__env->make('sections.hero', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->make('sections.services', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->make('sections.gallery', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->make('sections.banners', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->make('sections.articles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>

        <!-- Фиксированная кнопка социальных сетей -->
        <div class="social-floating-button">
            <button class="social-main-btn" id="socialMainBtn">
                <i class="bi bi-share-fill"></i>
            </button>

            <div class="social-links" id="socialLinks">
                <?php if($pageUser->telegram_url): ?>
                    <a href="<?php echo e($pageUser->telegram_url); ?>" target="_blank" class="social-link telegram" title="Telegram">
                        <i class="bi bi-telegram"></i>
                    </a>
                <?php endif; ?>

                <?php if($pageUser->whatsapp_url): ?>
                    <a href="<?php echo e($pageUser->whatsapp_url); ?>" target="_blank" class="social-link whatsapp" title="WhatsApp">
                        <i class="bi bi-whatsapp"></i>
                    </a>
                <?php endif; ?>

                <?php if($pageUser->vk_url): ?>
                    <a href="<?php echo e($pageUser->vk_url); ?>" target="_blank" class="social-link vk" title="ВКонтакте">
                        <i class="bi bi-chat-square-text"></i>
                    </a>
                <?php endif; ?>

                <?php if($pageUser->youtube_url): ?>
                    <a href="<?php echo e($pageUser->youtube_url); ?>" target="_blank" class="social-link youtube" title="YouTube">
                        <i class="bi bi-youtube"></i>
                    </a>
                <?php endif; ?>

                <?php if($pageUser->ok_url): ?>
                    <a href="<?php echo e($pageUser->ok_url); ?>" target="_blank" class="social-link ok" title="Одноклассники">
                        <i class="bi bi-people-fill"></i>
                    </a>
                <?php endif; ?>

                
                <?php if($socialLinks && $socialLinks->count() > 0): ?>
                    <?php $__currentLoopData = $socialLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $serviceClass = '';
                            $serviceName = strtolower($link->service_name);
                            if (str_contains($serviceName, 'instagram')) $serviceClass = 'instagram';
                            elseif (str_contains($serviceName, 'github')) $serviceClass = 'github';
                            elseif (str_contains($serviceName, 'linkedin')) $serviceClass = 'linkedin';
                            elseif (str_contains($serviceName, 'facebook')) $serviceClass = 'facebook';
                            elseif (str_contains($serviceName, 'twitter')) $serviceClass = 'twitter';
                            elseif (str_contains($serviceName, 'discord')) $serviceClass = 'discord';
                            elseif (str_contains($serviceName, 'tiktok')) $serviceClass = 'tiktok';
                            elseif (str_contains($serviceName, 'pinterest')) $serviceClass = 'pinterest';
                            elseif (str_contains($serviceName, 'email') || str_contains($serviceName, 'mail')) $serviceClass = 'email';
                            elseif (str_contains($serviceName, 'портфолио') || str_contains($serviceName, 'portfolio')) $serviceClass = 'portfolio';
                            elseif (str_contains($serviceName, 'сайт') || str_contains($serviceName, 'website') || str_contains($serviceName, 'ссылка')) $serviceClass = 'website';
                        ?>
                        <a href="<?php echo e($link->url); ?>" target="_blank" class="social-link custom <?php echo e($serviceClass); ?>" title="<?php echo e($link->service_name); ?>">
                            <i class="bi <?php echo e($link->icon_class); ?>"></i>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </div>
        </div>
    </main>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\link\resources\views/home.blade.php ENDPATH**/ ?>