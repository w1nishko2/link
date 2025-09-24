<?php $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
    <article class="card h-100 article-card" itemscope itemtype="https://schema.org/Article">
        <a href="<?php echo e(route('articles.show', ['username' => $article->user->username, 'slug' => $article->slug])); ?>" 
           class="text-decoration-none">
            <!-- Изображение статьи -->
            <div class="card-img-top position-relative" style="height: 200px; overflow: hidden;">
                <?php if($article->image_path): ?>
                    <img src="<?php echo e(asset('storage/' . $article->image_path)); ?>" 
                         alt="<?php echo e($article->title); ?>"
                         class="w-100 h-100 object-fit-cover"
                         loading="lazy" 
                         decoding="async"
                         width="300"
                         height="200"
                         itemprop="image">
                <?php else: ?>
                    <img src="/hero.png" 
                         alt="<?php echo e($article->title); ?>" 
                         class="w-100 h-100 object-fit-cover"
                         loading="lazy" 
                         decoding="async"
                         width="300"
                         height="200"
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
                    <?php if(!empty($search)): ?>
                        <?php echo \App\Helpers\SearchHelper::highlightSearch($article->title, $search); ?>

                    <?php else: ?>
                        <?php echo e($article->title); ?>

                    <?php endif; ?>
                </h3>
                
                <?php if($article->excerpt): ?>
                    <p class="card-text text-muted flex-grow-1" itemprop="description">
                        <?php if(!empty($search)): ?>
                            <?php echo \App\Helpers\SearchHelper::highlightSearch(Str::limit($article->excerpt, 120), $search); ?>

                        <?php else: ?>
                            <?php echo e(Str::limit($article->excerpt, 120)); ?>

                        <?php endif; ?>
                    </p>
                <?php endif; ?>

                <!-- Автор и мета-информация -->
                <div class="mt-auto">
                    <!-- Информация об авторе -->
                    <div class="d-flex align-items-center mb-2">
                        <div class="author-avatar me-2">
                            <?php if($article->user->avatar): ?>
                                <img src="<?php echo e(asset('storage/' . $article->user->avatar)); ?>" 
                                     alt="<?php echo e($article->user->name); ?>" 
                                     class="rounded-circle"
                                     style="width: 32px; height: 32px; object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 32px; height: 32px; font-size: 14px;">
                                    <?php echo e(strtoupper(substr($article->user->name, 0, 1))); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="flex-grow-1" itemprop="author" itemscope itemtype="https://schema.org/Person">
                            <div class="fw-medium small text-dark">
                                <span itemprop="name">
                                    <?php if(!empty($search)): ?>
                                        <?php echo \App\Helpers\SearchHelper::highlightSearch($article->user->name, $search); ?>

                                    <?php else: ?>
                                        <?php echo e($article->user->name); ?>

                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="text-muted small"><?php echo e('@' . $article->user->username); ?></div>
                        </div>
                    </div>
                    
                    <!-- Мета-информация -->
                    <div class="d-flex justify-content-between align-items-center text-muted small">
                        <span>
                            <i class="bi bi-calendar3"></i>
                            <?php echo e($article->created_at->format('d.m.Y')); ?>

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
<?php /**PATH C:\OSPanel\domains\link\resources\views/articles/partials/articles-grid.blade.php ENDPATH**/ ?>