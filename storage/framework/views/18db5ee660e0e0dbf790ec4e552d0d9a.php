

<?php $__env->startSection('title', $article->title . ' - ' . $article->user->name . ' | ' . config('app.name')); ?>
<?php $__env->startSection('description', $article->excerpt ? Str::limit(strip_tags($article->excerpt), 155) : Str::limit(strip_tags($article->content), 155)); ?>
<?php $__env->startSection('keywords', 'статья, ' . strtolower($article->user->name) . ', блог, ' . strtolower(str_replace([' ', ','], [', ', ' '], $article->title)) . ', ' . strtolower($article->user->username)); ?>
<?php $__env->startSection('author', $article->user->name); ?>

<?php $__env->startSection('og_type', 'article'); ?>
<?php $__env->startSection('og_title', $article->title); ?>
<?php $__env->startSection('og_description', $article->excerpt ? Str::limit(strip_tags($article->excerpt), 200) : Str::limit(strip_tags($article->content), 200)); ?>
<?php $__env->startSection('og_url', route('articles.show', ['username' => $article->user->username, 'slug' => $article->slug])); ?>
<?php $__env->startSection('og_image', $article->image_path ? asset('storage/' . $article->image_path) : ($article->user->avatar ? asset('storage/' . $article->user->avatar) : asset('/hero.png'))); ?>

<?php $__env->startSection('twitter_title', $article->title); ?>
<?php $__env->startSection('twitter_description', $article->excerpt ? Str::limit(strip_tags($article->excerpt), 160) : Str::limit(strip_tags($article->content), 160)); ?>
<?php $__env->startSection('twitter_image', $article->image_path ? asset('storage/' . $article->image_path) : asset('/hero.png')); ?>

<?php $__env->startSection('canonical_url', route('articles.show', ['username' => $article->user->username, 'slug' => $article->slug])); ?>

<?php $__env->startPush('head'); ?>
<!-- Article Structured Data (JSON-LD) -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Article",
    "headline": "<?php echo e($article->title); ?>",
    "description": "<?php echo e($article->excerpt ? strip_tags($article->excerpt) : Str::limit(strip_tags($article->content), 200)); ?>",
    "datePublished": "<?php echo e($article->created_at->toISOString()); ?>",
    "dateModified": "<?php echo e($article->updated_at->toISOString()); ?>",
    "author": {
        "@type": "Person",
        "name": "<?php echo e($article->user->name); ?>",
        "url": "<?php echo e(route('user.show', $article->user->username)); ?>"
    },
    "publisher": {
        "@type": "Organization",
        "name": "<?php echo e(config('app.name')); ?>",
        "url": "<?php echo e(url('/')); ?>"
    },
    <?php if($article->image_path): ?>
    "image": {
        "@type": "ImageObject",
        "url": "<?php echo e(asset('storage/' . $article->image_path)); ?>",
        "width": "1200",
        "height": "630"
    },
    <?php endif; ?>
    "url": "<?php echo e(route('articles.show', ['username' => $article->user->username, 'slug' => $article->slug])); ?>",
    "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "<?php echo e(route('articles.show', ['username' => $article->user->username, 'slug' => $article->slug])); ?>"
    },
    "wordCount": "<?php echo e(str_word_count(strip_tags($article->content))); ?>",
    "timeRequired": "PT<?php echo e($article->read_time); ?>M",
    "inLanguage": "ru-RU",
    "isAccessibleForFree": true,
    "articleSection": "Blog",
    "copyrightHolder": {
        "@type": "Person",
        "name": "<?php echo e($article->user->name); ?>"
    },
    "copyrightYear": "<?php echo e($article->created_at->format('Y')); ?>"
}
</script>

<!-- Breadcrumb Structured Data -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {
            "@type": "ListItem",
            "position": 1,
            "name": "<?php echo e($article->user->name); ?>",
            "item": "<?php echo e(route('user.show', $article->user->username)); ?>"
        },
        {
            "@type": "ListItem",
            "position": 2,
            "name": "<?php echo e($article->title); ?>",
            "item": "<?php echo e(route('articles.show', ['username' => $article->user->username, 'slug' => $article->slug])); ?>"
        }
    ]
}
</script>

<?php if($relatedArticles->count() > 0): ?>
<!-- Related Articles Structured Data -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ItemList",
    "name": "Похожие статьи",
    "itemListElement": [
        <?php $__currentLoopData = $relatedArticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        {
            "@type": "Article",
            "position": <?php echo e($index + 1); ?>,
            "headline": "<?php echo e($related->title); ?>",
            "description": "<?php echo e($related->excerpt); ?>",
            "url": "<?php echo e(route('articles.show', ['username' => $related->user->username, 'slug' => $related->slug])); ?>",
            "datePublished": "<?php echo e($related->created_at->toISOString()); ?>",
            "author": {
                "@type": "Person",
                "name": "<?php echo e($related->user->name); ?>"
            }
        }<?php if($index < $relatedArticles->count() - 1): ?>,<?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    ]
}
</script>
<?php endif; ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <main class="article-page" role="main">
        <!-- Основной контент -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                 
                    
                    <article class="article-wrapper" itemscope itemtype="https://schema.org/Article">
                        <!-- Заголовок и мета-информация -->
                        <header class="article-header">
                            <h1 class="article-title" itemprop="headline"><?php echo e($article->title); ?></h1>

                            <div class="article-meta">
                                <div class="author-section">
                                    <div class="author-avatar">
                                        <?php if($article->user->avatar): ?>
                                            <img src="<?php echo e(asset('storage/' . $article->user->avatar)); ?>" alt="Аватар <?php echo e($article->user->name); ?>" class="rounded-circle" width="40" height="40">
                                        <?php else: ?>
                                            <i class="bi bi-person-circle"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="author-info">
                                        <span class="author-name" itemprop="author" itemscope itemtype="https://schema.org/Person">
                                            <span itemprop="name"><?php echo e($article->user->name); ?></span>
                                        </span>
                                        <div class="article-date">
                                            <time datetime="<?php echo e($article->created_at->toISOString()); ?>" itemprop="datePublished">
                                                <?php echo e($article->created_at->format('d.m.Y')); ?>

                                            </time>
                                            <?php if($article->read_time): ?>
                                                <span class="read-time"><?php echo e($article->read_time); ?> мин чтения</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="article-actions">
                                    <button type="button" class="btn  btn-sm" onclick="copyArticleUrl()">
                                        <i class="bi bi-share me-1"></i>Поделиться
                                    </button>
                                </div>
                            </div>
                        </header>

                        <!-- Изображение статьи -->
                        <?php if($article->image_path): ?>
                            <div class="article-image mb-4">
                                <img src="<?php echo e(asset('storage/' . $article->image_path)); ?>" alt="<?php echo e($article->title); ?>" class="img-fluid rounded" itemprop="image">
                            </div>
                        <?php endif; ?>

                        <!-- Краткое описание -->
                        <?php if($article->excerpt): ?>
                            <div class="">
                                <p class="lead" itemprop="description"><?php echo e($article->excerpt); ?></p>
                            </div>
                        <?php endif; ?>

                        <!-- Содержание статьи -->
                        <div class="article-content" itemprop="articleBody">
                            <?php echo $article->content; ?>

                        </div>

                        <!-- Мета-информация в конце -->
                        <footer class="article-footer mt-5">
                            <div class="row">
                                <div class="col-md-12 d-flex justify-content-between align-items-center ">
                                    <div class="article-tags">
                                        <small class="text-muted">
                                            Опубликовано: <time datetime="<?php echo e($article->created_at->toISOString()); ?>"><?php echo e($article->created_at->format('d.m.Y H:i')); ?></time>
                                        </small>
                                        <button type="button" class="btn btn-outline-success btn-sm" onclick="copyArticleUrl()">
                                        <i class="bi bi-link-45deg me-1"></i>Копировать ссылку
                                    </button>
                                        <?php if($article->updated_at != $article->created_at): ?>
                                            <br>
                                            <small class="text-muted">
                                                Обновлено: <time datetime="<?php echo e($article->updated_at->toISOString()); ?>" itemprop="dateModified"><?php echo e($article->updated_at->format('d.m.Y H:i')); ?></time>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                              
                            </div>
                        </footer>
                    </article>
                </div>
            </div>

          

            <!-- Похожие статьи -->
            <?php if($relatedArticles->count() > 0): ?>
                <section class="row " aria-label="Похожие статьи">
                    <div class="col-lg-10 mx-auto">
                        <h3>Другие статьи автора</h3>
                        <div class="row">
                            <?php $__currentLoopData = $relatedArticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <article class="card h-100" itemscope itemtype="https://schema.org/Article">
                                        <?php if($related->image_path): ?>
                                            <img src="<?php echo e(asset('storage/' . $related->image_path)); ?>" class="card-img-top" style="height: 150px; object-fit: cover;" alt="<?php echo e($related->title); ?>" itemprop="image">
                                        <?php endif; ?>
                                        <div class="card-body">
                                            <h6 class="card-title" itemprop="headline">
                                                <a href="<?php echo e(route('articles.show', ['username' => $related->user->username, 'slug' => $related->slug])); ?>" class="text-decoration-none" itemprop="url">
                                                    <?php echo e(Str::limit($related->title, 50)); ?>

                                                </a>
                                            </h6>
                                            <?php if($related->excerpt): ?>
                                                <p class="card-text small" itemprop="description"><?php echo e(Str::limit($related->excerpt, 100)); ?></p>
                                            <?php endif; ?>
                                            <small class="text-muted">
                                                <time datetime="<?php echo e($related->created_at->toISOString()); ?>" itemprop="datePublished"><?php echo e($related->created_at->format('d.m.Y')); ?></time>
                                            </small>
                                            <meta itemprop="author" content="<?php echo e($related->user->name); ?>">
                                        </div>
                                    </article>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </section>
            <?php endif; ?>
        </div>
    </main>

    <script>
        function copyArticleUrl() {
            const url = window.location.href;
            
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(url).then(() => {
                    showCopyMessage('✅ Ссылка скопирована в буфер обмена!');
                }).catch(() => {
                    fallbackCopyTextToClipboard(url);
                });
            } else {
                fallbackCopyTextToClipboard(url);
            }
        }

        function fallbackCopyTextToClipboard(text) {
            const textArea = document.createElement("textarea");
            textArea.value = text;
            textArea.style.position = "fixed";
            textArea.style.left = "-999999px";
            textArea.style.top = "-999999px";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            
            try {
                document.execCommand('copy');
                showCopyMessage('✅ Ссылка скопирована!');
            } catch (err) {
                showCopyMessage('❌ Не удалось скопировать ссылку');
            }
            
            document.body.removeChild(textArea);
        }

        function showCopyMessage(message) {
            // Создаем уведомление
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show position-fixed';
            alert.style.top = '20px';
            alert.style.right = '20px';
            alert.style.zIndex = '9999';
            alert.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            document.body.appendChild(alert);

            // Автоматически скрываем через 3 секунды
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            }, 3000);
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\link\resources\views/articles/show.blade.php ENDPATH**/ ?>