
<section class="articles" aria-label="Статьи блога">
    <div class="container">
        <?php if((isset($section) && (!empty(trim($section->title)) || !empty(trim($section->subtitle)))) || !isset($section)): ?>
        <header class="articles-header">
            <?php if(isset($section)): ?>
                <?php if(!empty(trim($section->title))): ?>
                    <h2><?php echo e($section->title); ?></h2>
                <?php endif; ?>
                
                <?php if(!empty(trim($section->subtitle))): ?>
                    <p class="text-muted"><?php echo e($section->subtitle); ?></p>
                <?php endif; ?>
            <?php else: ?>
                <h2><?php echo e($currentUser && $currentUser->id === $pageUser->id ? 'Мои статьи' : 'Статьи от ' . $pageUser->name); ?></h2>
                <p class="text-muted">Полезные материалы и советы</p>
            <?php endif; ?>
        </header>
        <?php endif; ?>

        <div class="articles-list">
            
            <?php if($currentUser && $currentUser->id === $pageUser->id): ?>
                <a href="<?php echo e(route('admin.articles.create', $currentUser->id)); ?>" class="owner-default-block article-add">
                    <div class="owner-default-icon"></div>
                    <div class="owner-default-text">
                        <div class="owner-default-title">Создать статью</div>
                        <div class="owner-default-subtitle">Поделитесь своими знаниями</div>
                    </div>
                </a>
            <?php endif; ?>

            <?php $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <article class="article-preview" itemscope itemtype="https://schema.org/Article" data-article-id="<?php echo e($article->id); ?>">
                    <a href="<?php echo e(route('articles.show', ['username' => $pageUser->username, 'slug' => $article->slug])); ?>"
                        class="article-item">
                        <div class="article-image">
                            <?php if($article->image_path): ?>
                                <img src="<?php echo e(asset('storage/' . $article->image_path)); ?>" 
                                     alt="<?php echo e($article->title); ?>"
                                     loading="lazy" 
                                     width="300"
                                     height="200"
                                     decoding="async"
                                     itemprop="image">
                            <?php else: ?>
                                <img src="/hero.png" 
                                     alt="<?php echo e($article->title); ?>" 
                                     loading="lazy" 
                                     width="300"
                                     height="200"
                                     decoding="async"
                                     itemprop="image">
                            <?php endif; ?>
                           
                        </div>
                        <div class="article-content">
                            
                            <h3 class="article-title" itemprop="headline">
                                <?php echo e($article->title); ?>

                            </h3>
                            <p class="" itemprop="description">
                                <?php echo e($article->excerpt); ?>

                            </p>
                            <div class="article-meta">
                                 <time class="" datetime="<?php echo e($article->created_at->toISOString()); ?>" itemprop="datePublished">
                                <span><?php echo e($article->created_at->format('d')); ?></span>
                                <span><?php echo e($article->created_at->format('M')); ?></span>
                            </time>
                                <span class="article-author" itemprop="author" itemscope itemtype="https://schema.org/Person">
                                    <span itemprop="name">Автор: <?php echo e($pageUser->name); ?></span>
                                </span>
                                <span class="article-read-time"><?php echo e($article->read_time); ?> мин чтения</span>
                            </div>
                        </div>
                    </a>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            
            <?php if($articles->count() === 0 && (!$currentUser || $currentUser->id !== $pageUser->id)): ?>
                <div class="text-center py-5">
                    <h4>Статьи не найдены</h4>
                    <p class="text-muted">Здесь будут отображаться статьи</p>
                </div>
            <?php endif; ?>
        </div>

        <?php if($articles->count() > 0): ?>
        <div class="articles-footer text-center mt-5">
            <a href="<?php echo e(route('articles.index', ['username' => $pageUser->username])); ?>" class="btn btn-outline-primary">Все статьи</a>
        </div>
        <?php endif; ?>
    </div>
</section><?php /**PATH C:\OSPanel\domains\link\resources\views\sections\articles.blade.php ENDPATH**/ ?>