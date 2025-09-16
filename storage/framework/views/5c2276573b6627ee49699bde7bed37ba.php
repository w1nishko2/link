

<?php $__env->startSection('title', 'Управление галереей - ' . config('app.name')); ?>
<?php $__env->startSection('description', 'Управление изображениями галереи: загрузка, редактирование, организация'); ?>

<?php $__env->startSection('content'); ?>
<style>
.gallery-image {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.gallery-image:hover {
    transform: scale(1.02);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.card-img-top {
    transition: opacity 0.2s ease-in-out;
}

.card-img-top:hover {
    opacity: 0.9;
}

.modal-footer .btn-danger {
    margin-right: auto;
}
</style>

<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
   
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addImageModal">
        <i class="bi bi-plus-circle me-2"></i>
        <span class="d-none d-sm-inline">Добавить изображение</span>
        <span class="d-sm-none">Добавить</span>
    </button>
</div>

<?php if($images->count() > 0): ?>
    <div class="row">
        <?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-12 col-md-4 col-lg-3 mb-4">
                <div class="card h-100 gallery-image" style="margin: 0 !important;">
                    <img src="<?php echo e(asset('storage/' . $image->image_path)); ?>" 
                         class="card-img-top" 
                         alt="<?php echo e($image->alt_text ?: $image->title); ?>"
                         style=" object-fit: cover; width: ; cursor: pointer;"
                         data-bs-toggle="modal" 
                         data-bs-target="#editImageModal<?php echo e($image->id); ?>"
                         title="Нажмите для редактирования">
                  
                </div>
                
                <!-- Modal для редактирования -->
                <div class="modal fade" id="editImageModal<?php echo e($image->id); ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="<?php echo e(route('admin.gallery.update', [$currentUserId, $image])); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                <div class="modal-header">
                                    <h5 class="modal-title">Редактировать изображение</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="title<?php echo e($image->id); ?>" class="form-label">Название</label>
                                        <input type="text" class="form-control" 
                                               id="title<?php echo e($image->id); ?>" 
                                               name="title" 
                                               value="<?php echo e($image->title); ?>" maxlength="100">
                                        <div class="form-text">Максимум 100 символов</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="alt_text<?php echo e($image->id); ?>" class="form-label">Alt текст</label>
                                        <input type="text" class="form-control" 
                                               id="alt_text<?php echo e($image->id); ?>" 
                                               name="alt_text" 
                                               value="<?php echo e($image->alt_text); ?>" maxlength="150">
                                        <div class="form-text">Максимум 150 символов</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="order_index<?php echo e($image->id); ?>" class="form-label">Порядок</label>
                                        <input type="number" class="form-control" 
                                               id="order_index<?php echo e($image->id); ?>" 
                                               name="order_index" 
                                               value="<?php echo e($image->order_index); ?>">
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               id="is_active<?php echo e($image->id); ?>" 
                                               name="is_active" 
                                               value="1" <?php echo e($image->is_active ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="is_active<?php echo e($image->id); ?>">
                                            Активно
                                        </label>
                                    </div>
                                </div>
                                <div class="modal-footer d-flex justify-content-between">
                                    <button type="button" class="btn btn-danger" onclick="deleteImage(<?php echo e($image->id); ?>)">
                                        <i class="bi bi-trash"></i> Удалить
                                    </button>
                                    <div class="d-flex" style="width: 100% ;gap: 10px;">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                                        <button type="submit" class="btn btn-primary">Сохранить</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    
    <div class="d-flex justify-content-center mt-4">
        <?php echo e($images->links('pagination.custom')); ?>

    </div>
<?php else: ?>
    <div class="text-center py-5">
        <i class="bi bi-images display-1 text-muted"></i>
        <h3 class="mt-3">Галерея пуста</h3>
        <p class="text-muted">Добавьте первое изображение в галерею</p>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addImageModal">
            <i class="bi bi-plus-circle me-2"></i>
            Добавить изображение
        </button>
    </div>
<?php endif; ?>

<!-- Modal для добавления изображения -->
<div class="modal fade" id="addImageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo e(route('admin.gallery.store', $currentUserId)); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title">Добавить изображение</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="image" class="form-label">Изображение *</label>
                        <input type="file" class="form-control <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="image" name="image" accept="image/*" required>
                        <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <div class="form-text">Поддерживаются изображения в любых форматах. Автоматически конвертируется в WebP с оптимизацией размера.</div>
                    </div>
                    <div class="mb-3">
                        <label for="title" class="form-label">Название</label>
                        <input type="text" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="title" name="title" value="<?php echo e(old('title')); ?>" maxlength="100">
                        <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <div class="form-text">Максимум 100 символов. Осталось: <span id="title-counter">100</span></div>
                    </div>
                    <div class="mb-3">
                        <label for="alt_text" class="form-label">Alt текст</label>
                        <input type="text" class="form-control <?php $__errorArgs = ['alt_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="alt_text" name="alt_text" value="<?php echo e(old('alt_text')); ?>" maxlength="150">
                        <?php $__errorArgs = ['alt_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <div class="form-text">Максимум 150 символов. Осталось: <span id="alt-text-counter">150</span></div>
                    </div>
                    <div class="mb-3">
                        <label for="order_index" class="form-label">Порядок</label>
                        <input type="number" class="form-control" 
                               id="order_index" name="order_index" value="<?php echo e(old('order_index')); ?>">
                        <div class="form-text">Оставьте пустым для автоматического порядка</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-primary">Добавить</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Form для удаления изображения -->
<form id="deleteImageForm" method="POST" style="display: none;">
    <?php echo csrf_field(); ?>
    <?php echo method_field('DELETE'); ?>
</form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
// Счетчики символов
function setupCharCounter(inputId, counterId, maxLength) {
    const input = document.getElementById(inputId);
    const counter = document.getElementById(counterId);
    
    if (!input || !counter) return;
    
    function updateCounter() {
        const currentLength = input.value.length;
        const remaining = maxLength - currentLength;
        counter.textContent = remaining;
        
        if (remaining < 0) {
            counter.style.color = '#dc3545';
        } else if (remaining < 20) {
            counter.style.color = '#fd7e14';
        } else {
            counter.style.color = '#6c757d';
        }
    }
    
    updateCounter();
    input.addEventListener('input', updateCounter);
    input.addEventListener('keydown', updateCounter);
    input.addEventListener('paste', function() {
        setTimeout(updateCounter, 10);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    setupCharCounter('title', 'title-counter', 100);
    setupCharCounter('alt_text', 'alt-text-counter', 150);
});

function deleteImage(imageId) {
    if (confirm('Вы уверены, что хотите удалить это изображение? Это действие нельзя отменить.')) {
        const form = document.getElementById('deleteImageForm');
        const url = "<?php echo e(route('admin.gallery.destroy', [$currentUserId, ':id'])); ?>".replace(':id', imageId);
        console.log('Отправка формы удаления на URL:', url);
        form.action = url;
        
        // Закрываем модальное окно перед удалением
        const modals = document.querySelectorAll('.modal.show');
        modals.forEach(modal => {
            const modalInstance = bootstrap.Modal.getInstance(modal);
            if (modalInstance) {
                modalInstance.hide();
            }
        });
        
        form.submit();
    }
}

// Показать модал добавления если есть ошибки
<?php if($errors->any()): ?>
    document.addEventListener('DOMContentLoaded', function() {
        new bootstrap.Modal(document.getElementById('addImageModal')).show();
    });
<?php endif; ?>
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\link\resources\views/admin/gallery/index.blade.php ENDPATH**/ ?>