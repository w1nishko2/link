

<?php $__env->startSection('title', 'Статьи ' . $user->name . ' | ' . config('app.name')); ?>
<?php $__env->startSection('description', 'Все статьи и публикации от ' . $user->name . '. Полезные материалы, советы и экспертные мнения от ' . $user->username . '.'); ?>
<?php $__env->startSection('keywords', 'статьи, блог, ' . strtolower($user->name) . ', публикации, материалы, ' . strtolower($user->username) . ', авторские статьи'); ?>
<?php $__env->startSection('author', $user->name); ?>

<?php $__env->startSection('og_type', 'website'); ?>
<?php $__env->startSection('og_title', 'Статьи от ' . $user->name); ?>
<?php $__env->startSection('og_description', 'Все статьи и публикации от ' . $user->name . '. Полезные материалы и экспертные советы.'); ?>
<?php $__env->startSection('og_url', request()->url()); ?>
<?php $__env->startSection('og_image', $user->avatar ? asset('storage/' . $user->avatar) : ($user->background_image ? asset('storage/' . $user->background_image) : asset('/hero.png'))); ?>

<?php $__env->startSection('twitter_title', 'Статьи от ' . $user->name); ?>
<?php $__env->startSection('twitter_description', 'Все статьи и публикации от ' . $user->name . '. Полезные материалы и советы.'); ?>
<?php $__env->startSection('twitter_image', $user->avatar ? asset('storage/' . $user->avatar) : asset('/hero.png')); ?>

<?php $__env->startSection('canonical_url', route('articles.index', $user->username)); ?>

<?php $__env->startPush('head'); ?>
<!-- Structured Data (JSON-LD) -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Blog",
    "name": "Блог <?php echo e($user->name); ?>",
    "description": "Все статьи и публикации от <?php echo e($user->name); ?>",
    "author": {
        "@type": "Person",
        "name": "<?php echo e($user->name); ?>",
        "url": "<?php echo e(route('user.page', $user->username)); ?>"
    },
    "url": "<?php echo e(route('articles.index', $user->username)); ?>",
    <?php if($articles->count() > 0): ?>
    "blogPost": [
        <?php $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        {
            "@type": "BlogPosting",
            "headline": "<?php echo e($article->title); ?>",
            "description": "<?php echo e($article->excerpt); ?>",
            "url": "<?php echo e(route('articles.show', ['username' => $user->username, 'slug' => $article->slug])); ?>",
            "datePublished": "<?php echo e($article->created_at->toISOString()); ?>",
            "author": {
                "@type": "Person",
                "name": "<?php echo e($user->name); ?>"
            }
        }<?php if($index < $articles->count() - 1): ?>,<?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    ]
    <?php endif; ?>
}
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container ">
    <!-- Breadcrumb навигация -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?php echo e(route('user.page', $user->username)); ?>">
                    <i class="bi bi-house"></i> <?php echo e($user->name); ?>

                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Статьи</li>
        </ol>
    </nav>

    <!-- Заголовок страницы -->
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto text-center">
            <h1 class="display-5 fw-bold mb-3">Статьи от <?php echo e($user->name); ?></h1>
            <p class="lead text-muted">
                Полезные материалы, советы и экспертные мнения
            </p>
            <?php if($user->bio): ?>
                <p class="text-muted"><?php echo e($user->bio); ?></p>
            <?php endif; ?>
        </div>
    </div>

    <?php if($articles->count() > 0): ?>
        <!-- Статьи -->
        <div class="row g-4">
            <?php $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-lg-4 col-md-6">
                    <article class="card h-100 article-card" itemscope itemtype="https://schema.org/Article">
                        <a href="<?php echo e(route('articles.show', ['username' => $user->username, 'slug' => $article->slug])); ?>" class="text-decoration-none">
                            <!-- Изображение статьи -->
                            <div class="card-img-top position-relative" style="height: 200px; overflow: hidden;">
                                <?php if($article->image_path): ?>
                                    <img src="<?php echo e(asset('storage/' . $article->image_path)); ?>" 
                                         alt="<?php echo e($article->title); ?>"
                                         class="w-100 h-100 object-fit-cover"
                                         loading="lazy" 
                                         itemprop="image">
                                <?php else: ?>
                                    <img src="/hero.png" 
                                         alt="<?php echo e($article->title); ?>" 
                                         class="w-100 h-100 object-fit-cover"
                                         loading="lazy" 
                                         itemprop="image">
                                <?php endif; ?>
                                
                                <!-- Дата публикации -->
                                <div class="position-absolute top-0 end-0 m-3">
                                    <time class="badge bg-dark bg-opacity-75" 
                                          datetime="<?php echo e($article->created_at->toISOString()); ?>" 
                                          itemprop="datePublished">
                                        <?php echo e($article->created_at->format('d.m.Y')); ?>

                                    </time>
                                </div>
                            </div>

                            <!-- Содержимое карточки -->
                            <div class="card-body d-flex flex-column">
                                <h3 class="card-title h5 fw-bold text-dark mb-3" itemprop="headline">
                                    <?php echo e($article->title); ?>

                                </h3>
                                
                                <?php if($article->excerpt): ?>
                                    <p class="card-text text-muted flex-grow-1" itemprop="description">
                                        <?php echo e(Str::limit($article->excerpt, 120)); ?>

                                    </p>
                                <?php endif; ?>

                                <!-- Мета-информация -->
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center text-muted small">
                                        <span itemprop="author" itemscope itemtype="https://schema.org/Person">
                                            <i class="bi bi-person"></i>
                                            <span itemprop="name"><?php echo e($user->name); ?></span>
                                        </span>
                                        <?php if($article->read_time): ?>
                                            <span>
                                                <i class="bi bi-clock"></i>
                                                <?php echo e($article->read_time); ?> мин
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </article>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Пагинация -->
        <div class="d-flex justify-content-center mt-5">
            <?php echo e($articles->links('pagination.custom')); ?>

        </div>

    <?php else: ?>
        <!-- Пустое состояние -->
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="bi bi-file-text display-1 text-muted"></i>
            </div>
            <h3 class="h4 text-muted mb-3">Статьи не найдены</h3>
            <p class="text-muted mb-4">У <?php echo e($user->name); ?> пока нет опубликованных статей</p>
            <a href="<?php echo e(route('user.page', $user->username)); ?>" class="btn 
                <i class="bi bi-arrow-left"></i> Вернуться на главную
            </a>
        </div>
    <?php endif; ?>

    <!-- Действия -->
    <div class="text-center mt-5">
        <div class="row justify-content-center">
            <div class="col-auto">
                <a href="<?php echo e(route('user.page', $user->username)); ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Назад к профилю
                </a>
            </div>
            <?php if($user->telegram_url || $user->whatsapp_url || $user->vk_url): ?>
                <div class="col-auto">
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-share"></i> Связаться
                        </button>
                        <ul class="dropdown-menu">
                            <?php if($user->telegram_url): ?>
                                <li>
                                    <a class="dropdown-item" href="<?php echo e($user->telegram_url); ?>" target="_blank" rel="noopener">
                                        <i class="bi bi-telegram"></i> Telegram
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if($user->whatsapp_url): ?>
                                <li>
                                    <a class="dropdown-item" href="<?php echo e($user->whatsapp_url); ?>" target="_blank" rel="noopener">
                                        <i class="bi bi-whatsapp"></i> WhatsApp
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if($user->vk_url): ?>
                                <li>
                                    <a class="dropdown-item" href="<?php echo e($user->vk_url); ?>" target="_blank" rel="noopener">
                                        <i class="bi bi-person-vcard"></i> VK
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.article-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    border: 1px solid rgba(0,0,0,0.125);
}

.article-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
}

.object-fit-cover {
    object-fit: cover;
}

.breadcrumb-item a {
    text-decoration: none;
    color: #6c757d;
}

.breadcrumb-item a:hover {
    color: #495057;
}

/* Адаптивность для карточек */
@media (max-width: 768px) {
    .col-md-6 {
        margin-bottom: 1.5rem;
    }
}

/* Анимации для лучшего UX */
.article-card a {
    color: inherit;
    display: block;
    height: 100%;
}

.article-card .card-title {
    transition: color 0.2s ease-in-out;
}

.article-card:hover .card-title {
    color: #0d6efd !important;
}
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\link\resources\views\articles\index.blade.php ENDPATH**/ ?>