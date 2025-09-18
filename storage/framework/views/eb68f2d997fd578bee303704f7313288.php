<form action="<?php echo e(route('admin.profile.update.social', $user->id)); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>
    
    <div class="mb-3">
        <label for="telegram_url" class="form-label">
            <i class="bi bi-telegram text-info me-1"></i>
            Telegram
        </label>
        <input type="url" class="form-control <?php $__errorArgs = ['telegram_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
               id="telegram_url" name="telegram_url" 
               value="<?php echo e(old('telegram_url', $user->telegram_url)); ?>"
               placeholder="https://t.me/username" maxlength="255">
        <?php $__errorArgs = ['telegram_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <div class="form-text">Максимум 255 символов. Осталось: <span id="telegram-counter">255</span></div>
    </div>

    <div class="mb-3">
        <label for="whatsapp_url" class="form-label">
            <i class="bi bi-whatsapp text-success me-1"></i>
            WhatsApp
        </label>
        <input type="url" class="form-control <?php $__errorArgs = ['whatsapp_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
               id="whatsapp_url" name="whatsapp_url" 
               value="<?php echo e(old('whatsapp_url', $user->whatsapp_url)); ?>"
               placeholder="https://wa.me/79XXXXXXXXX" maxlength="255">
        <?php $__errorArgs = ['whatsapp_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <div class="form-text">Максимум 255 символов. Осталось: <span id="whatsapp-counter">255</span></div>
    </div>

    <div class="mb-3">
        <label for="vk_url" class="form-label">
            <i class="bi bi-link-45deg text-primary me-1"></i>
            ВКонтакте
        </label>
        <input type="url" class="form-control <?php $__errorArgs = ['vk_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
               id="vk_url" name="vk_url" 
               value="<?php echo e(old('vk_url', $user->vk_url)); ?>"
               placeholder="https://vk.com/username" maxlength="255">
        <?php $__errorArgs = ['vk_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <div class="form-text">Максимум 255 символов. Осталось: <span id="vk-counter">255</span></div>
    </div>

    <div class="mb-3">
        <label for="youtube_url" class="form-label">
            <i class="bi bi-youtube text-danger me-1"></i>
            YouTube
        </label>
        <input type="url" class="form-control <?php $__errorArgs = ['youtube_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
               id="youtube_url" name="youtube_url" 
               value="<?php echo e(old('youtube_url', $user->youtube_url)); ?>"
               placeholder="https://youtube.com/@username" maxlength="255">
        <?php $__errorArgs = ['youtube_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <div class="form-text">Максимум 255 символов. Осталось: <span id="youtube-counter">255</span></div>
    </div>

    <div class="mb-4">
        <label for="ok_url" class="form-label">
            <i class="bi bi-link-45deg text-warning me-1"></i>
            Одноклассники
        </label>
        <input type="url" class="form-control <?php $__errorArgs = ['ok_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
               id="ok_url" name="ok_url" 
               value="<?php echo e(old('ok_url', $user->ok_url)); ?>"
               placeholder="https://ok.ru/profile/username" maxlength="255">
        <?php $__errorArgs = ['ok_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <div class="form-text">Максимум 255 символов. Осталось: <span id="ok-counter">255</span></div>
    </div>

    <div class="d-flex flex-column flex-sm-row gap-2">
        <button type="submit" class="btn btn-primary flex-fill">
            <i class="bi bi-check-circle me-2"></i>
            Сохранить социальные сети
        </button>
        <a href="<?php echo e(route('admin.profile', $user->id)); ?>" class="btn btn-outline-secondary flex-fill">
            <i class="bi bi-arrow-left me-2"></i>
            Назад к панели
        </a>
    </div>
</form>



<!-- Дополнительные социальные ссылки -->
<div class="form-label mb-4">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3 gap-2">
        <label class="form-label mb-0">
            <i class="bi bi-globe text-primary me-1"></i>
            <span class="d-none d-sm-inline">Доп. социальные ссылки</span>
            <span class="d-sm-none">Дополнительные ссылки</span>
        </label>
        <?php if($user->socialLinks->count() < 5): ?>
            <button type="button" class="btn  " 
                    data-bs-toggle="modal" data-bs-target="#addSocialLinkModal" 
                    id="addSocialLinkBtn"
                    title="Добавить дополнительную социальную ссылку">
                <i class="bi bi-plus-circle me-1"></i>
                <span class="d-none d-sm-inline">Добавить ссылку</span>
                <span class="d-sm-none">Добавить</span>
            </button>
        <?php else: ?>
            <span class="badge bg-warning text-dark">
                <i class="bi bi-exclamation-triangle me-1"></i>
                Лимит достигнут (5/5)
            </span>
        <?php endif; ?>
    </div>
    
    <?php if($user->socialLinks->count() > 0): ?>
        <div class="row g-3">
            <?php $__currentLoopData = $user->socialLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-12" data-link-id="<?php echo e($link->id); ?>">
                    <div class="card border-0 bg-light">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <label class="form-label mb-0 fw-semibold">
                                    <i class="bi <?php echo e($link->icon_class); ?> me-2 text-primary"></i>
                                    <?php echo e($link->service_name); ?>

                                </label>
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" 
                                            onclick="editSocialLink(<?php echo e($link->id); ?>, '<?php echo e(addslashes($link->service_name)); ?>', '<?php echo e(addslashes($link->url)); ?>', '<?php echo e($link->icon_class); ?>')"
                                            title="Редактировать">
                                       
                                        <span class="d-none d-md-inline ms-1">Изменить</span>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm" 
                                            onclick="deleteSocialLink(<?php echo e($link->id); ?>)"
                                            title="Удалить">
                                        <i class="bi bi-trash"></i>
                                        <span class="d-none d-md-inline ms-1">Удалить</span>
                                    </button>
                                </div>
                            </div>
                            <div class="input-group mb-2">
                                <input type="url" class="form-control form-control-sm" 
                                       value="<?php echo e($link->url); ?>" 
                                       readonly
                                       placeholder="URL ссылки">
                               
                            </div>
                            <div class="form-text small">
                                <a href="<?php echo e($link->url); ?>" target="_blank" rel="noopener" class="text-decoration-none text-muted">
                                    Перейти по ссылке <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php else: ?>
        <div class="text-muted text-center py-4">
            <i class="bi bi-link-45deg fs-1 d-block mb-3 opacity-50"></i>
            <p class="mb-2 fw-semibold">Дополнительные ссылки не добавлены</p>
            <small class="d-block">Добавьте ссылки на свои профили в социальных сетях,</small>
            <small class="d-block">портфолио или другие ресурсы</small>
            <small class="d-block mt-2 text-warning">
                <i class="bi bi-info-circle me-1"></i>
                Максимум 5 дополнительных ссылок
            </small>
        </div>
    <?php endif; ?>
</div>

<?php /**PATH C:\OSPanel\domains\link\resources\views\admin\profile\social.blade.php ENDPATH**/ ?>