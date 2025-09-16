

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="<?php echo e(route('admin.banners', $currentUserId)); ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>
        Назад к баннерам
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Редактирование баннера</h5>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('admin.banners.update', [$currentUserId, $banner])); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="title" class="form-label">Заголовок баннера *</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="title" name="title" value="<?php echo e(old('title', $banner->title)); ?>" required maxlength="100">
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
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="order_index" class="form-label">Порядок отображения</label>
                                <input type="number" class="form-control <?php $__errorArgs = ['order_index'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="order_index" name="order_index" value="<?php echo e(old('order_index', $banner->order_index)); ?>">
                                <?php $__errorArgs = ['order_index'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Описание</label>
                        <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                  id="description" name="description" rows="3" maxlength="300"><?php echo e(old('description', $banner->description)); ?></textarea>
                        <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <div class="form-text">Максимум 300 символов. Осталось: <span id="description-counter">300</span></div>
                    </div>

                    <?php if($banner->image_path): ?>
                        <div class="mb-3">
                            <label class="form-label">Текущее изображение</label>
                            <div>
                                <img src="<?php echo e(asset('storage/' . $banner->image_path)); ?>" class="img-thumbnail" style="max-height: 150px;" alt="Текущее изображение">
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="image" class="form-label"><?php echo e($banner->image_path ? 'Новое изображение' : 'Изображение'); ?></label>
                        <input type="file" class="form-control <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="image" name="image" accept="image/*">
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
                        <div class="form-text">
                            <?php echo e($banner->image_path ? 'Оставьте пустым, чтобы сохранить текущее изображение' : 'Максимальный размер: 10MB'); ?>

                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="link_url" class="form-label">Ссылка</label>
                        <input type="url" class="form-control <?php $__errorArgs = ['link_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="link_url" name="link_url" value="<?php echo e(old('link_url', $banner->link_url)); ?>" 
                               placeholder="https://example.com" maxlength="255">
                        <?php $__errorArgs = ['link_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <div class="form-text">Максимум 255 символов. Осталось: <span id="link-url-counter">255</span></div>
                    </div>

                    <div class="mb-3">
                        <label for="link_text" class="form-label">Текст ссылки</label>
                        <input type="text" class="form-control <?php $__errorArgs = ['link_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="link_text" name="link_text" value="<?php echo e(old('link_text', $banner->link_text)); ?>" 
                               placeholder="Перейти" maxlength="50">
                        <?php $__errorArgs = ['link_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <div class="form-text">Максимум 50 символов. Осталось: <span id="link-text-counter">50</span></div>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input <?php $__errorArgs = ['is_active'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               type="checkbox" value="1" id="is_active" name="is_active" 
                               <?php echo e(old('is_active', $banner->is_active) ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="is_active">
                            Активный баннер
                        </label>
                        <?php $__errorArgs = ['is_active'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>
                            Сохранить изменения
                        </button>
                        <a href="<?php echo e(route('admin.banners', $currentUserId)); ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg me-2"></i>
                            Отмена
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Предварительный просмотр</h6>
            </div>
            <div class="card-body">
                <div id="preview" class="border rounded p-3 text-center">
                    <?php if($banner->image_path): ?>
                        <img id="preview-image" src="<?php echo e(asset('storage/' . $banner->image_path)); ?>" class="img-fluid mb-2" alt="Предпросмотр">
                    <?php else: ?>
                        <div id="no-image" class="text-muted">
                            <i class="bi bi-image display-4"></i>
                            <p>Изображение не выбрано</p>
                        </div>
                    <?php endif; ?>
                    <h6 id="preview-title"><?php echo e($banner->title); ?></h6>
                    <p id="preview-description" class="text-muted small"><?php echo e($banner->description); ?></p>
                    <?php if($banner->link_url): ?>
                        <a id="preview-link" href="<?php echo e($banner->link_url); ?>" class="btn btn-sm btn-primary" target="_blank">
                            <?php echo e($banner->link_text ?: 'Перейти'); ?>

                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
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
    
    input.addEventListener('input', updateCounter);
    updateCounter();
}

// Предварительный просмотр
function updatePreview() {
    const title = document.getElementById('title').value || 'Заголовок баннера';
    const description = document.getElementById('description').value || 'Описание баннера';
    const linkUrl = document.getElementById('link_url').value;
    const linkText = document.getElementById('link_text').value || 'Перейти';
    
    document.getElementById('preview-title').textContent = title;
    document.getElementById('preview-description').textContent = description;
    
    const linkElement = document.getElementById('preview-link');
    if (linkUrl) {
        if (!linkElement) {
            const link = document.createElement('a');
            link.id = 'preview-link';
            link.className = 'btn btn-sm btn-primary';
            link.target = '_blank';
            document.getElementById('preview').appendChild(link);
        }
        document.getElementById('preview-link').href = linkUrl;
        document.getElementById('preview-link').textContent = linkText;
    } else if (linkElement) {
        linkElement.remove();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    setupCharCounter('title', 'title-counter', 100);
    setupCharCounter('description', 'description-counter', 300);
    setupCharCounter('link_url', 'link-url-counter', 255);
    setupCharCounter('link_text', 'link-text-counter', 50);
    
    // Слушатели для предварительного просмотра
    ['title', 'description', 'link_url', 'link_text'].forEach(id => {
        document.getElementById(id).addEventListener('input', updatePreview);
    });
});

// Предварительный просмотр изображения
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewImage = document.getElementById('preview-image');
            const noImage = document.getElementById('no-image');
            
            if (previewImage) {
                previewImage.src = e.target.result;
            } else {
                if (noImage) noImage.remove();
                const img = document.createElement('img');
                img.id = 'preview-image';
                img.className = 'img-fluid mb-2';
                img.alt = 'Предпросмотр';
                img.src = e.target.result;
                document.getElementById('preview').prepend(img);
            }
        };
        reader.readAsDataURL(file);
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\link\resources\views/admin/banners/edit.blade.php ENDPATH**/ ?>