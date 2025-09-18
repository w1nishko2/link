<form action="<?php echo e(route('admin.profile.update.security', $user->id)); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>
    
    <div class="alert alert-info d-flex align-items-start" role="alert">
        <i class="bi bi-info-circle me-2 mt-1 flex-shrink-0"></i>
        <div>
            <strong class="d-block d-sm-inline">Изменение пароля:</strong>
            <span class="d-block d-sm-inline">Оставьте поля пустыми, если не хотите менять пароль.</span>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12">
            <label for="current_password" class="form-label">
                <i class="bi bi-lock me-1"></i>
                Текущий пароль
            </label>
            <div class="input-group">
                <input type="password" class="form-control <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                       id="current_password" name="current_password" 
                       placeholder="Введите текущий пароль для подтверждения">
                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('current_password', this)">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            <?php $__errorArgs = ['current_password'];
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

        <div class="col-12 col-md-6">
            <label for="password" class="form-label">
                <i class="bi bi-key me-1"></i>
                Новый пароль
            </label>
            <div class="input-group">
                <input type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                       id="password" name="password" 
                       placeholder="Введите новый пароль">
                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password', this)">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            <?php $__errorArgs = ['password'];
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
                <i class="bi bi-info-circle me-1"></i>
                Минимум 8 символов
            </div>
        </div>

        <div class="col-12 col-md-6">
            <label for="password_confirmation" class="form-label">
                <i class="bi bi-check-circle me-1"></i>
                Подтверждение пароля
            </label>
            <div class="input-group">
                <input type="password" class="form-control" 
                       id="password_confirmation" name="password_confirmation" 
                       placeholder="Повторите новый пароль">
                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password_confirmation', this)">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            <div class="form-text">
                <i class="bi bi-arrow-repeat me-1"></i>
                Повторите новый пароль для подтверждения
            </div>
        </div>
    </div>

    <hr class="my-4">

    <div class="mb-4">
        <h6 class="text-muted mb-3">
            <i class="bi bi-person-badge me-2"></i>
            Информация об аккаунте
        </h6>
        <div class="row g-3">
            <div class="col-12 col-sm-6">
                <div class="d-flex align-items-center">
                    <i class="bi bi-calendar-plus me-2 text-primary"></i>
                    <div>
                        <small class="text-muted d-block">Дата регистрации</small>
                        <strong><?php echo e($user->created_at->format('d.m.Y H:i')); ?></strong>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="d-flex align-items-center">
                    <i class="bi bi-clock-history me-2 text-info"></i>
                    <div>
                        <small class="text-muted d-block">Последнее обновление</small>
                        <strong><?php echo e($user->updated_at->format('d.m.Y H:i')); ?></strong>
                    </div>
                </div>
            </div>
         
        </div>
    </div>

    <div class="d-flex flex-column flex-sm-row gap-2">
        <button type="submit" class="btn btn-warning flex-fill">
            <i class="bi bi-shield-check me-2"></i>
            Обновить пароль
        </button>
        <a href="<?php echo e(route('admin.profile', $user->id)); ?>" class="btn btn-outline-secondary flex-fill">
            <i class="bi bi-arrow-left me-2"></i>
            Назад к панели
        </a>
    </div>
</form><?php /**PATH C:\OSPanel\domains\link\resources\views\admin\profile\security.blade.php ENDPATH**/ ?>