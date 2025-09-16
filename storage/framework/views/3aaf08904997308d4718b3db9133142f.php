

<?php $__env->startSection('title', 'Управление баннерами'); ?>
<?php $__env->startSection('description', 'Управление рекламными баннерами и промо-блоками'); ?>

<?php $__env->startSection('content'); ?>
<style>
.card {
    transition: transform 0.2s ease-in-out;
    border: 1px solid #dee2e6;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.btn-group .btn {
    flex: 1;
    font-size: 0.875rem;
    padding: 0.5rem 0.75rem;
}
.card-footer {
    padding: 0.75rem;
    background-color: #f8f9fa;
}
.card-footer .btn-group {
    gap: 0.5rem;
    display: flex;
}

/* Стили для баннеров в админке */
.banner-admin-container {
    width: 100%;
}

.banner-admin-container .banners-banner {
    height: 280px; /* Немного меньше чем на главной */
    margin-bottom: 0;
    cursor: default; /* Убираем курсор pointer в админке */
}

.banner-admin-container .banners-banner-block {
    padding: 15px 10px;
}

.banner-admin-container .banners-banner-block h3 {
    font-size: 16px;
    margin-bottom: 8px;
    color: #333;
    font-weight: 600;
}

.banner-admin-container .banners-banner-block p {
    font-size: 12px;
    color: #666;
    line-height: 1.4;
    margin-bottom: 10px;
}

.banner-admin-container .banner-link-info {
    margin-bottom: 10px;
}

.banner-admin-container .banner-status-info {
    margin-top: auto;
}

.banner-admin-container .banner-no-image {
    width: 100%;
    height: 100%;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 15px;
    border: 2px dashed #dee2e6;
}

.banner-admin-controls {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 8px;
    padding: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}


</style>

<div class="d-flex justify-content-between align-items-center mb-4">
  
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBannerModal">
        <i class="bi bi-plus-lg"></i> Добавить баннер
    </button>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if($banners->count() > 0): ?>
    <div class="row">
        <?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="banner-admin-container">
                    <div class="banners-banner">
                        <div class="banners-banner-block">
                            <h3><?php echo e($banner->title); ?></h3>
                            <?php if($banner->description): ?>
                                <p><?php echo e($banner->description); ?></p>
                            <?php endif; ?>
                        </div>  
                        
                        <div class="banners-banner-block-img">
                            <?php if($banner->image_path): ?>
                                <img src="<?php echo e(asset('storage/' . $banner->image_path)); ?>" 
                                     alt="<?php echo e($banner->title); ?>"
                                     loading="lazy"
                                     width="300"
                                     height="200"
                                     decoding="async">
                            <?php else: ?>
                                <div class="banner-no-image">
                                    <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="banner-admin-controls mt-2">
                        <div class="btn-group w-100" role="group">
                            <a href="<?php echo e(route('admin.banners.edit', [$currentUserId, $banner])); ?>" class="btn  btn-sm">
                                <i class="bi bi-pencil"></i> Редактировать
                            </a>
                            <button type="button" class="btn  btn-sm" onclick="deleteBanner(<?php echo e($banner->id); ?>)">
                                <i class="bi bi-trash"></i> Удалить
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <!-- Пагинация -->
    <?php if($banners->hasPages()): ?>
        <div class="d-flex justify-content-center mt-4">
            <?php echo e($banners->links('pagination.custom')); ?>

        </div>
    <?php endif; ?>
<?php else: ?>
    <div class="text-center py-5">
        <i class="bi bi-megaphone text-muted" style="font-size: 4rem;"></i>
        <h3 class="mt-3 text-muted">Нет баннеров</h3>
        <p class="text-muted">Добавьте первый баннер, чтобы он появился здесь.</p>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBannerModal">
            <i class="bi bi-plus-lg"></i> Добавить баннер
        </button>
    </div>
<?php endif; ?>

<!-- Модальное окно создания баннера -->
<div class="modal fade" id="createBannerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Создание баннера</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('admin.banners.store', $currentUserId)); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create_title" class="form-label">Заголовок баннера *</label>
                        <input type="text" class="form-control" id="create_title" name="title" required maxlength="100">
                        <div class="form-text">Максимум 100 символов. Осталось: <span id="create-title-counter">100</span></div>
                    </div>

                    <div class="mb-3">
                        <label for="create_description" class="form-label">Описание</label>
                        <textarea class="form-control" id="create_description" name="description" rows="3" maxlength="300"></textarea>
                        <div class="form-text">Максимум 300 символов. Осталось: <span id="create-description-counter">300</span></div>
                    </div>

                    <div class="mb-3">
                        <label for="create_image" class="form-label">Изображение баннера</label>
                        <input type="file" class="form-control" id="create_image" name="image" accept="image/*">
                        <div class="form-text">Поддерживаются изображения в форматах: JPEG, PNG, JPG, GIF, BMP, TIFF, WebP. Максимальный размер: 10MB. Все изображения автоматически конвертируются в WebP с оптимизацией размера.</div>
                    </div>

                    <div class="mb-3">
                        <label for="create_link_url" class="form-label">Ссылка</label>
                        <input type="url" class="form-control" id="create_link_url" name="link_url" placeholder="https://example.com" maxlength="255">
                        <div class="form-text">Максимум 255 символов. Осталось: <span id="create-link-url-counter">255</span></div>
                    </div>

                    <div class="mb-3">
                        <label for="create_link_text" class="form-label">Текст ссылки</label>
                        <input type="text" class="form-control" id="create_link_text" name="link_text" placeholder="Перейти" maxlength="50">
                        <div class="form-text">Максимум 50 символов. Осталось: <span id="create-link-text-counter">50</span></div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <label for="create_order_index" class="form-label">Порядок отображения</label>
                            <input type="number" class="form-control" id="create_order_index" name="order_index" value="0" min="0">
                        </div>
                        <div class="col-6 d-flex align-items-end">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="create_is_active" name="is_active" value="1" checked>
                                <label class="form-check-label" for="create_is_active">
                                    Активный баннер
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-primary">Создать баннер</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Модальное окно подтверждения удаления -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Подтверждение удаления</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Вы уверены, что хотите удалить этот баннер? Это действие нельзя отменить.
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
    // Счетчики для формы создания
    setupCharCounter('create_title', 'create-title-counter', 100);
    setupCharCounter('create_description', 'create-description-counter', 300);
    setupCharCounter('create_link_url', 'create-link-url-counter', 255);
    setupCharCounter('create_link_text', 'create-link-text-counter', 50);
});

function deleteBanner(bannerId) {
    const form = document.getElementById('deleteForm');
    form.action = `/admin/user/<?php echo e($currentUserId); ?>/banners/${bannerId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\link\resources\views/admin/banners/index.blade.php ENDPATH**/ ?>