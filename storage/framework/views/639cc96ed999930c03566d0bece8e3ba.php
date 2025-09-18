<form action="<?php echo e(route('admin.profile.update.basic', $user->id)); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>
    
    <div class="mb-3">
        <label for="name" class="form-label">Имя</label>
        <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
               id="name" name="name" value="<?php echo e(old('name', $user->name)); ?>" required maxlength="50">
        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <div class="form-text">Максимум 50 символов. Осталось: <span id="name-counter">50</span></div>
    </div>

    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" 
               value="<?php echo e($user->username); ?>" readonly>
        <div class="form-text">Username нельзя изменить</div>
    </div>

    <div class="mb-3">
        <label for="phone" class="form-label">Телефон</label>
        <input type="text" class="form-control" id="phone" 
               value="<?php echo e($user->phone); ?>" readonly>
        <div class="form-text">Номер телефона нельзя изменить</div>
    </div>

    <div class="mb-4">
        <label for="bio" class="form-label">О себе</label>
        <textarea class="form-control <?php $__errorArgs = ['bio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                  id="bio" name="bio" rows="4" maxlength="190"
                  placeholder="Расскажите о себе..."><?php echo e(old('bio', $user->bio)); ?></textarea>
        <?php $__errorArgs = ['bio'];
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
            Максимум 190 символов. Осталось: <span id="bio-counter">190</span>
        </div>
    </div>

    <!-- Секция изображений -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h6 class="text-muted mb-3">Аватар</h6>
            <div class="text-center">
                <?php if($user->avatar): ?>
                    <img src="<?php echo e(asset('storage/' . $user->avatar)); ?>" 
                         alt="Аватар <?php echo e($user->name); ?>" 
                         class="rounded-circle mb-3" 
                         style="width: 100px; height: 100px; object-fit: cover;">
                <?php else: ?>
                    <div class="bg-light rounded-circle mb-3 d-flex align-items-center justify-content-center" 
                         style="width: 100px; height: 100px; color: #6c757d;">
                        <i class="bi bi-person-circle" style="font-size: 48px;"></i>
                    </div>
                <?php endif; ?>
                <div>
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="openAvatarEditor()">
                        <i class="bi bi-pencil me-1"></i>
                        Редактировать аватар
                    </button>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <h6 class="text-muted mb-3">Фоновое изображение</h6>
            <div class="text-center">
                <?php if($user->background_image): ?>
                    <img src="<?php echo e(asset('storage/' . $user->background_image)); ?>" 
                         alt="Фон <?php echo e($user->name); ?>" 
                         class="img-thumbnail mb-3" 
                         style="width: 100px; height: 60px; object-fit: cover;">
                <?php else: ?>
                    <div class="bg-light img-thumbnail mb-3 d-flex align-items-center justify-content-center" 
                         style="width: 100px; height: 60px; color: #6c757d;">
                        <i class="bi bi-image" style="font-size: 24px;"></i>
                    </div>
                <?php endif; ?>
                <div>
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="openBackgroundEditor()">
                        <i class="bi bi-pencil me-1"></i>
                        Редактировать фон
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex flex-column flex-sm-row gap-2">
        <button type="submit" class="btn btn-primary flex-fill">
            <i class="bi bi-check-circle me-2"></i>
            Сохранить основную информацию
        </button>
        <a href="<?php echo e(route('admin.profile', $user->id)); ?>" class="btn btn-outline-secondary flex-fill">
            <i class="bi bi-arrow-left me-2"></i>
            Назад к панели
        </a>
    </div>
</form><?php /**PATH C:\OSPanel\domains\link\resources\views\admin\profile\basic.blade.php ENDPATH**/ ?>