<?php $__env->startSection('content'); ?>
<div class="auth-page">
    <div class="auth-container">
        <div class=" ">
         

            <div class="">
                <form method="POST" action="<?php echo e(route('login')); ?>" id="loginForm" class="auth-form">
                    <?php echo csrf_field(); ?>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Номер телефона</label>
                        <input type="tel" 
                               class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="phone" 
                               name="phone" 
                               value="<?php echo e(old('phone')); ?>" 
                               required 
                               placeholder="+7 (___) ___-__-__"
                               title="Формат: +7 (999) 999-99-99"
                               autofocus>
                        <div class="form-text">Формат: +7 (904) 448-22-83</div>
                        
                        <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback">
                                <strong><?php echo e($message); ?></strong>
                            </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Пароль</label>
                        <input type="password" 
                               class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="password" 
                               name="password" 
                               required>
                        
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback">
                                <strong><?php echo e($message); ?></strong>
                            </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" 
                               class="form-check-input" 
                               id="remember" 
                               name="remember" 
                               <?php echo e(old('remember') ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="remember">
                            Запомнить меня
                        </label>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            Войти
                        </button>
                    </div>

                    <div class="auth-links">
                        <p>Нет аккаунта? <a href="<?php echo e(route('register')); ?>">Зарегистрироваться</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const phoneInput = document.getElementById('phone');
    
    function phoneMask(input) {
        let value = input.value.replace(/\D/g, '');
        
        // Если начинается с 8, заменяем на 7
        if (value.startsWith('8')) {
            value = '7' + value.slice(1);
        }
        
        // Если не начинается с 7, добавляем 7
        if (!value.startsWith('7') && value.length > 0) {
            value = '7' + value;
        }
        
        // Форматируем номер
        let formattedValue = '';
        if (value.length > 0) {
            formattedValue = '+7';
            if (value.length > 1) {
                formattedValue += ' (' + value.slice(1, 4);
                if (value.length > 4) {
                    formattedValue += ') ' + value.slice(4, 7);
                    if (value.length > 7) {
                        formattedValue += '-' + value.slice(7, 9);
                        if (value.length > 9) {
                            formattedValue += '-' + value.slice(9, 11);
                        }
                    }
                }
            }
        }
        
        return formattedValue;
    }
    
    phoneInput.addEventListener('input', function(e) {
        const cursorPosition = e.target.selectionStart;
        const oldValue = e.target.value;
        const newValue = phoneMask(e.target);
        
        e.target.value = newValue;
        
        // Корректируем позицию курсора
        if (newValue.length < oldValue.length) {
            e.target.setSelectionRange(cursorPosition - 1, cursorPosition - 1);
        } else if (newValue.length > oldValue.length) {
            e.target.setSelectionRange(cursorPosition + 1, cursorPosition + 1);
        } else {
            e.target.setSelectionRange(cursorPosition, cursorPosition);
        }
    });
    
    phoneInput.addEventListener('keydown', function(e) {
        // Разрешаем: backspace, delete, tab, escape, enter
        if ([46, 8, 9, 27, 13].indexOf(e.keyCode) !== -1 ||
            // Разрешаем: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
            (e.keyCode === 65 && e.ctrlKey === true) ||
            (e.keyCode === 67 && e.ctrlKey === true) ||
            (e.keyCode === 86 && e.ctrlKey === true) ||
            (e.keyCode === 88 && e.ctrlKey === true) ||
            // Разрешаем: стрелки
            (e.keyCode >= 35 && e.keyCode <= 39)) {
            return;
        }
        
        // Запрещаем все, кроме цифр
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
    
    // Применяем маску к существующему значению при загрузке страницы
    if (phoneInput.value) {
        phoneInput.value = phoneMask(phoneInput);
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\link\resources\views/auth/login.blade.php ENDPATH**/ ?>