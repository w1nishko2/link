

<?php $__env->startSection('title', 'Редактирование услуги - ' . config('app.name')); ?>
<?php $__env->startSection('description', 'Редактирование описания и настроек услуги'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    
    <a href="<?php echo e(route('admin.services', $currentUserId)); ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>
        Назад к услугам
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Редактирование услуги</h5>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('admin.services.update', [$currentUserId, $service])); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="title" class="form-label">Название услуги *</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="title" name="title" value="<?php echo e(old('title', $service->title)); ?>" required maxlength="100">
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
                                       id="order_index" name="order_index" value="<?php echo e(old('order_index', $service->order_index)); ?>">
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
                        <label for="description" class="form-label">Описание услуги *</label>
                        <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                  id="description" name="description" rows="4" required maxlength="500"><?php echo e(old('description', $service->description)); ?></textarea>
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
                        <div class="form-text">Максимум 500 символов. Осталось: <span id="description-counter">500</span></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">Цена (₽)</label>
                                <input type="number" class="form-control <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="price" name="price" value="<?php echo e(old('price', $service->price)); ?>" min="0" step="0.01">
                                <?php $__errorArgs = ['price'];
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
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price_type" class="form-label">Тип цены *</label>
                                <select class="form-select <?php $__errorArgs = ['price_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="price_type" name="price_type" required>
                                    <option value="fixed" <?php echo e(old('price_type', $service->price_type) == 'fixed' ? 'selected' : ''); ?>>Фиксированная цена</option>
                                    <option value="hourly" <?php echo e(old('price_type', $service->price_type) == 'hourly' ? 'selected' : ''); ?>>За час</option>
                                    <option value="project" <?php echo e(old('price_type', $service->price_type) == 'project' ? 'selected' : ''); ?>>За проект</option>
                                </select>
                                <?php $__errorArgs = ['price_type'];
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
                        <label for="image" class="form-label">Изображение услуги</label>
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
                        <?php if($service->image_path): ?>
                            <div class="mt-2">
                                <small class="text-muted">Текущее изображение:</small><br>
                                <img src="<?php echo e(asset('storage/' . $service->image_path)); ?>" 
                                     alt="<?php echo e($service->title); ?>" class="img-thumbnail mt-1" style="max-height: 100px;">
                            </div>
                        <?php endif; ?>
                        <div class="form-text">Поддерживаются изображения в любых форматах. Автоматически конвертируется в WebP с оптимизацией размера. Оставьте пустым, чтобы сохранить текущее изображение.</div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                   <?php echo e(old('is_active', $service->is_active) ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="is_active">
                                Активная услуга (отображается на сайте)
                            </label>
                        </div>
                    </div>

                    <!-- Настройки кнопки действия -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="button_text" class="form-label">Текст кнопки</label>
                                <select class="form-select <?php $__errorArgs = ['button_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="button_text" name="button_text">
                                    <option value="">Без кнопки</option>
                                    <option value="Заказать услугу" <?php echo e(old('button_text', $service->button_text) == 'Заказать услугу' ? 'selected' : ''); ?>>Заказать услугу</option>
                                    <option value="Связаться с нами" <?php echo e(old('button_text', $service->button_text) == 'Связаться с нами' ? 'selected' : ''); ?>>Связаться с нами</option>
                                    <option value="Узнать подробнее" <?php echo e(old('button_text', $service->button_text) == 'Узнать подробнее' ? 'selected' : ''); ?>>Узнать подробнее</option>
                                    <option value="Написать в WhatsApp" <?php echo e(old('button_text', $service->button_text) == 'Написать в WhatsApp' ? 'selected' : ''); ?>>Написать в WhatsApp</option>
                                    <option value="Написать в Telegram" <?php echo e(old('button_text', $service->button_text) == 'Написать в Telegram' ? 'selected' : ''); ?>>Написать в Telegram</option>
                                    <option value="Позвонить" <?php echo e(old('button_text', $service->button_text) == 'Позвонить' ? 'selected' : ''); ?>>Позвонить</option>
                                    <option value="Отправить email" <?php echo e(old('button_text', $service->button_text) == 'Отправить email' ? 'selected' : ''); ?>>Отправить email</option>
                                </select>
                                <?php $__errorArgs = ['button_text'];
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
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="button_link" class="form-label">Ссылка для кнопки</label>
                                <select class="form-select <?php $__errorArgs = ['button_link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="button_link" name="button_link">
                                    <option value="">Выберите ссылку</option>
                                    <?php if($user->phone): ?>
                                        <option value="tel:<?php echo e($user->phone); ?>" <?php echo e(old('button_link', $service->button_link) == 'tel:' . $user->phone ? 'selected' : ''); ?>>
                                            Телефон: <?php echo e($user->phone); ?>

                                        </option>
                                    <?php endif; ?>
                                    <?php if($user->email): ?>
                                        <option value="mailto:<?php echo e($user->email); ?>" <?php echo e(old('button_link', $service->button_link) == 'mailto:' . $user->email ? 'selected' : ''); ?>>
                                            Email: <?php echo e($user->email); ?>

                                        </option>
                                    <?php endif; ?>
                                    <?php if($user->telegram_url): ?>
                                        <option value="<?php echo e($user->telegram_url); ?>" <?php echo e(old('button_link', $service->button_link) == $user->telegram_url ? 'selected' : ''); ?>>
                                            Telegram
                                        </option>
                                    <?php endif; ?>
                                    <?php if($user->whatsapp_url): ?>
                                        <option value="<?php echo e($user->whatsapp_url); ?>" <?php echo e(old('button_link', $service->button_link) == $user->whatsapp_url ? 'selected' : ''); ?>>
                                            WhatsApp
                                        </option>
                                    <?php endif; ?>
                                    <?php if($user->vk_url): ?>
                                        <option value="<?php echo e($user->vk_url); ?>" <?php echo e(old('button_link', $service->button_link) == $user->vk_url ? 'selected' : ''); ?>>
                                            VK
                                        </option>
                                    <?php endif; ?>
                                    <?php if($user->instagram_url): ?>
                                        <option value="<?php echo e($user->instagram_url); ?>" <?php echo e(old('button_link', $service->button_link) == $user->instagram_url ? 'selected' : ''); ?>>
                                            Instagram
                                        </option>
                                    <?php endif; ?>
                                    <?php if($user->website_url): ?>
                                        <option value="<?php echo e($user->website_url); ?>" <?php echo e(old('button_link', $service->button_link) == $user->website_url ? 'selected' : ''); ?>>
                                            Сайт
                                        </option>
                                    <?php endif; ?>
                                    <?php $__currentLoopData = $user->socialLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $socialLink): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($socialLink->url); ?>" <?php echo e(old('button_link', $service->button_link) == $socialLink->url ? 'selected' : ''); ?>>
                                            <?php echo e($socialLink->service_name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['button_link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div class="form-text">Выберите куда будет вести кнопка</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>
                            Обновить 
                        </button>
                        <a href="<?php echo e(route('admin.services', $currentUserId)); ?>" class="btn btn-outline-secondary">Отмена</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Предварительный просмотр</h5>
            </div>
            <div class="card-body">
                <div class="service-preview">
                    <div class="service-image mb-3" style="height: 200px; background: #f8f9fa; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <?php if($service->image_path): ?>
                            <img src="<?php echo e(asset('storage/' . $service->image_path)); ?>" 
                                 alt="<?php echo e($service->title); ?>" class="img-fluid rounded">
                        <?php else: ?>
                            <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                        <?php endif; ?>
                    </div>
                    <h5 class="service-title"><?php echo e($service->title); ?></h5>
                    <p class="service-description text-muted"><?php echo e($service->description); ?></p>
                    <div class="service-price">
                        <strong><?php echo e($service->formatted_price); ?></strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Действия</h5>
            </div>
            <div class="card-body">
                <div class="d-flex gap-2">
                    <a href="<?php echo e(route('user.page', auth()->user()->username)); ?>" class="btn  btn-sm" target="_blank">
                        <i class="bi bi-eye me-2"></i>
                        Посмотреть 
                    </a>
                    <button type="button" class="btn  btn-sm" onclick="deleteService(<?php echo e($service->id); ?>)">
                        <i class="bi bi-trash me-2"></i>
                        Удалить 
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form для удаления -->
<form id="deleteServiceForm" method="POST" style="display: none;">
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
    setupCharCounter('description', 'description-counter', 500);
});

// Предварительный просмотр
document.getElementById('title').addEventListener('input', function() {
    document.querySelector('.service-title').textContent = this.value || 'Название услуги';
});

document.getElementById('description').addEventListener('input', function() {
    document.querySelector('.service-description').textContent = this.value || 'Описание услуги будет отображаться здесь...';
});

document.getElementById('price').addEventListener('input', updatePrice);
document.getElementById('price_type').addEventListener('change', updatePrice);

function updatePrice() {
    const price = document.getElementById('price').value;
    const priceType = document.getElementById('price_type').value;
    const priceElement = document.querySelector('.service-price strong');
    
    if (!price) {
        priceElement.textContent = 'По договоренности';
        return;
    }
    
    let formattedPrice = new Intl.NumberFormat('ru-RU').format(price) + ' ₽';
    
    switch (priceType) {
        case 'hourly':
            formattedPrice += '/час';
            break;
        case 'project':
            formattedPrice = 'от ' + formattedPrice;
            break;
    }
    
    priceElement.textContent = formattedPrice;
}

// Предварительный просмотр изображения
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewDiv = document.querySelector('.service-image');
            previewDiv.innerHTML = '<img src="' + e.target.result + '" alt="Preview" class="img-fluid rounded">';
        };
        reader.readAsDataURL(file);
    }
});

function deleteService(serviceId) {
    if (confirm('Вы уверены, что хотите удалить эту услугу?')) {
        const form = document.getElementById('deleteServiceForm');
        form.action = "<?php echo e(route('admin.services.destroy', [$currentUserId, ':id'])); ?>".replace(':id', serviceId);
        form.submit();
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\link\resources\views/admin/services/edit.blade.php ENDPATH**/ ?>