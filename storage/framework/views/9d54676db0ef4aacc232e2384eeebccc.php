

<?php $__env->startSection('title', 'Системные настройки'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Системные настройки</h1>
    <div class="text-muted">
        <i class="bi bi-gear"></i> Конфигурация системы
    </div>
</div>

<!-- Общие настройки -->
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Общие настройки</h6>
            </div>
            <div class="card-body">
                <form>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="site_name" class="form-label">Название сайта</label>
                            <input type="text" class="form-control" id="site_name" value="<?php echo e(config('app.name')); ?>" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="app_env" class="form-label">Окружение</label>
                            <input type="text" class="form-control" id="app_env" value="<?php echo e(config('app.env')); ?>" disabled>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="app_url" class="form-label">URL сайта</label>
                            <input type="text" class="form-control" id="app_url" value="<?php echo e(config('app.url')); ?>" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="app_timezone" class="form-label">Часовой пояс</label>
                            <input type="text" class="form-control" id="app_timezone" value="<?php echo e(config('app.timezone')); ?>" disabled>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="max_upload_size" class="form-label">Максимальный размер загружаемых файлов</label>
                        <input type="text" class="form-control" id="max_upload_size" value="<?php echo e(ini_get('upload_max_filesize')); ?>" disabled>
                    </div>
                    
                    <button type="button" class="btn btn-primary" disabled>
                        <i class="bi bi-save"></i> Сохранить настройки
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">Информация о системе</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Версия PHP:</strong><br>
                    <span class="text-muted"><?php echo e(PHP_VERSION); ?></span>
                </div>
                
                <div class="mb-3">
                    <strong>Версия Laravel:</strong><br>
                    <span class="text-muted"><?php echo e(app()->version()); ?></span>
                </div>
                
                <div class="mb-3">
                    <strong>База данных:</strong><br>
                    <span class="text-muted"><?php echo e(config('database.default')); ?></span>
                </div>
                
                <div class="mb-3">
                    <strong>Кэш:</strong><br>
                    <span class="text-muted"><?php echo e(config('cache.default')); ?></span>
                </div>
                
                <div class="mb-3">
                    <strong>Сессии:</strong><br>
                    <span class="text-muted"><?php echo e(config('session.driver')); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Настройки безопасности -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-warning">Безопасность</h6>
            </div>
            <div class="card-body">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="force_https" disabled>
                    <label class="form-check-label" for="force_https">
                        Принудительное использование HTTPS
                    </label>
                </div>
                
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="registration_enabled" disabled>
                    <label class="form-check-label" for="registration_enabled">
                        Разрешить регистрацию новых пользователей
                    </label>
                </div>
                
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="maintenance_mode" disabled>
                    <label class="form-check-label" for="maintenance_mode">
                        Режим обслуживания
                    </label>
                </div>
                
                <button type="button" class="btn btn-warning" disabled>
                    <i class="bi bi-shield-check"></i> Применить настройки безопасности
                </button>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-info">Системные действия</h6>
            </div>
            <div class="card-body">
                <div class="d-flex gap-2">
                    <button type="button" class="btn " disabled>
                        <i class="bi bi-arrow-clockwise"></i> Очистить кэш
                    </button>
                    
                    <button type="button" class="btn " disabled>
                        <i class="bi bi-gear"></i> Перезапустить конфигурацию
                    </button>
                    
                    <button type="button" class="btn " disabled>
                        <i class="bi bi-download"></i> Создать резервную копию
                    </button>
                    
                    <button type="button" class="btn " disabled>
                        <i class="bi bi-exclamation-triangle"></i> Режим обслуживания
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Информационная карточка -->
<div class="alert alert-info">
    <h5 class="alert-heading">
        <i class="bi bi-info-circle"></i> Информация
    </h5>
    <p class="mb-0">
        Эта страница находится в разработке. 
        В будущем здесь будут доступны полные системные настройки, 
        включая управление конфигурацией, мониторинг системы и административные инструменты.
    </p>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.super-admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\link\resources\views\admin\super-admin\settings.blade.php ENDPATH**/ ?>