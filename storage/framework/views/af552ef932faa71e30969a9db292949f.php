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

    <div class="d-flex flex-column flex-sm-row gap-2">
        <button type="submit" class="btn btn-primary flex-fill">
            <i class="bi bi-check-circle me-2"></i>
            Сохранить основную информацию
        </button>
        <a href="<?php echo e(route('admin.dashboard', $user->id)); ?>" class="btn btn-outline-secondary flex-fill">
            <i class="bi bi-arrow-left me-2"></i>
            Назад к панели
        </a>
    </div>
</form><?php /**PATH C:\OSPanel\domains\link\resources\views/admin/profile/basic.blade.php ENDPATH**/ ?>