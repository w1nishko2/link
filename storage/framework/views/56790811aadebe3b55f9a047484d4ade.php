

<?php $__env->startSection('title', 'Управление услугами - ' . config('app.name')); ?>
<?php $__env->startSection('description', 'Управление каталогом услуг: создание, редактирование, настройка цен'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
 
    <a href="<?php echo e(route('admin.services.create', $currentUserId)); ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>
        <span class="d-none d-sm-inline">Добавить услугу</span>
        <span class="d-sm-none">Добавить</span>
    </a>
</div>

<?php if($services->count() > 0): ?>
    <div class="row">
        <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-sm-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <?php if($service->image_path): ?>
                        <img src="<?php echo e(asset('storage/' . $service->image_path)); ?>" 
                             class="card-img-top" 
                             alt="<?php echo e($service->title); ?>"
                             style="height: 200px; object-fit: cover;">
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo e($service->title); ?></h5>
                        <p class="card-text flex-grow-1"><?php echo e(Str::limit($service->description, 100)); ?></p>
                        <?php if($service->price): ?>
                            <p class="card-text">
                                <strong><?php echo e($service->formatted_price); ?></strong>
                            </p>
                        <?php endif; ?>
                       
                    </div>
                    
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    
    <div class="d-flex justify-content-center mt-4">
        <?php echo e($services->links('pagination.custom')); ?>

    </div>
<?php else: ?>
    <div class="text-center py-5">
        <i class="bi bi-briefcase display-1 text-muted"></i>
        <h3 class="mt-3">Нет услуг</h3>
        <p class="text-muted">Добавьте первую услугу</p>
        <a href="<?php echo e(route('admin.services.create', $currentUserId)); ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>
            Добавить услугу
        </a>
    </div>
<?php endif; ?>

<!-- Form для удаления услуги -->
<form id="deleteServiceForm" action="" method="POST" style="display: none;">
    <?php echo csrf_field(); ?>
    <?php echo method_field('DELETE'); ?>
</form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
function deleteService(serviceId) {
    if (confirm('Вы уверены, что хотите удалить эту услугу?')) {
        const form = document.getElementById('deleteServiceForm');
        form.action = "<?php echo e(route('admin.services.destroy', [$currentUserId, ':id'])); ?>".replace(':id', serviceId);
        form.submit();
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\link\resources\views/admin/services/index.blade.php ENDPATH**/ ?>