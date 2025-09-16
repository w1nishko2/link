

<?php $__env->startSection('title', 'Управление статьями'); ?>
<?php $__env->startSection('description', 'Управление статьями блога: создание, редактирование, публикация'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
   
    <a href="<?php echo e(route('admin.articles.create', $currentUserId)); ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>
        <span class="d-none d-sm-inline">Добавить статью</span>
        <span class="d-sm-none">Добавить</span>
    </a>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if($articles->count() > 0): ?>
    <div class="row">
        <?php $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-sm-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <?php if($article->image_path): ?>
                        <img src="<?php echo e(asset('storage/' . $article->image_path)); ?>" class="card-img-top" style="height: 180px; object-fit: cover;" alt="<?php echo e($article->title); ?>">
                    <?php else: ?>
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                            <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo e(Str::limit($article->title, 50)); ?></h5>
                        <p class="card-text flex-grow-1"><?php echo e(Str::limit($article->excerpt, 80)); ?></p>
                        <div class="mt-auto">
                            <small class="text-muted">
                                <?php echo e($article->created_at->format('d.m.Y')); ?>

                            </small>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-transparent">
                        <div class="d-flex gap-2">
                            <a href="<?php echo e(route('admin.articles.edit', [$currentUserId, $article])); ?>" class="btn  btn-sm">
                                <i class="bi bi-pencil me-2"></i>
                                Редактировать
                            </a>
                            <button type="button" class="btn  btn-sm" onclick="deleteArticle(<?php echo e($article->id); ?>)">
                                <i class="bi bi-trash me-2"></i>
                                Удалить
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <!-- Пагинация -->
    <?php if($articles->hasPages()): ?>
        <div class="d-flex justify-content-center mt-4">
            <?php echo e($articles->links('pagination.custom')); ?>

        </div>
    <?php endif; ?>
<?php else: ?>
    <div class="text-center py-5">
        <i class="bi bi-journal-text text-muted" style="font-size: 4rem;"></i>
        <h3 class="mt-3 text-muted">Нет статей</h3>
        <p class="text-muted">Добавьте первую статью, чтобы она появилась здесь.</p>
        <a href="<?php echo e(route('admin.articles.create', $currentUserId)); ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Добавить статью
        </a>
    </div>
<?php endif; ?>

<!-- Модальное окно подтверждения удаления -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Подтверждение удаления</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Вы уверены, что хотите удалить эту статью? Это действие нельзя отменить.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <form id="deleteForm" action="" method="POST" style="display: inline;">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger">Удалить</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteArticle(articleId) {
    const form = document.getElementById('deleteForm');
    form.action = "<?php echo e(route('admin.articles.destroy', [$currentUserId, ':id'])); ?>".replace(':id', articleId);
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\link\resources\views/admin/articles/index.blade.php ENDPATH**/ ?>