

<?php $__env->startSection('title', 'Управление статьями'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Управление всеми статьями</h1>
    <div class="text-muted">
        <i class="bi bi-journal-text"></i> Всего статей: <?php echo e($articles->total()); ?>

    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Все статьи в системе</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Автор</th>
                        <th>Статус</th>
                        <th>Просмотры</th>
                        <th>Дата создания</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($article->id); ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <?php if($article->image_path): ?>
                                    <img src="<?php echo e(asset('storage/' . $article->image_path)); ?>" 
                                         alt="<?php echo e($article->title); ?>" 
                                         class="rounded me-2"
                                         style="width: 40px; height: 40px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center"
                                         style="width: 40px; height: 40px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <div class="fw-bold"><?php echo e(Str::limit($article->title, 40)); ?></div>
                                    <?php if($article->excerpt): ?>
                                        <small class="text-muted"><?php echo e(Str::limit($article->excerpt, 50)); ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <?php if($article->user->avatar): ?>
                                    <img src="<?php echo e(asset('storage/' . $article->user->avatar)); ?>" 
                                         alt="<?php echo e($article->user->name); ?>" 
                                         class="rounded-circle me-2"
                                         style="width: 24px; height: 24px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center"
                                         style="width: 24px; height: 24px; font-size: 0.7rem;">
                                        <i class="bi bi-person text-white"></i>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <div class="small"><?php echo e($article->user->name); ?></div>
                                    <small class="text-muted"><?php echo e($article->user->username); ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-<?php echo e($article->is_published ? 'success' : 'warning'); ?>">
                                <?php echo e($article->is_published ? 'Опубликована' : 'Черновик'); ?>

                            </span>
                        </td>
                        <td><?php echo e($article->read_time ?? 0); ?> мин</td>
                        <td><?php echo e($article->created_at->format('d.m.Y H:i')); ?></td>
                        <td>
                            <div class="btn-group" role="group">
                                <?php if($article->is_published): ?>
                                <a href="<?php echo e(route('articles.show', ['username' => $article->user->username, 'slug' => $article->slug])); ?>" 
                                   target="_blank" 
                                   class="btn btn-sm ">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <?php endif; ?>
                                <button type="button" class="btn btn-sm btn-outline-secondary" disabled>
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" disabled>
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">Статьи не найдены</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Пагинация -->
        <?php if($articles->hasPages()): ?>
        <div class="d-flex justify-content-center mt-4">
            <?php echo e($articles->links('pagination.custom')); ?>

        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Информационная карточка -->
<div class="alert alert-info">
    <h5 class="alert-heading">
        <i class="bi bi-info-circle"></i> Информация
    </h5>
    <p class="mb-0">
        Эта страница находится в разработке. 
        В будущем здесь будет полнофункциональное управление статьями, 
        включая модерацию контента, массовые операции и статистику по статьям.
    </p>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.super-admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\link\resources\views\admin\super-admin\articles.blade.php ENDPATH**/ ?>