

<?php $__env->startSection('title', 'Панель администратора'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Панель администратора</h1>
    <div class="text-muted">
        <i class="bi bi-person-check"></i> Добро пожаловать, <?php echo e(auth()->user()->name); ?>

    </div>
</div>

<!-- Статистические карточки -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Всего пользователей
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['total_users']); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-people text-primary fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Администраторов
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['total_admins']); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-shield-check text-success fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Всего статей
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['total_articles']); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-journal-text text-info fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Опубликованных статей
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['published_articles']); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-check-circle text-warning fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Дополнительная статистика -->
<div class="row mb-4">
    <div class="col-xl-4 col-md-4 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Услуги</h6>
            </div>
            <div class="card-body text-center">
                <div class="h4 mb-0"><?php echo e($stats['total_services']); ?></div>
                <small class="text-muted">Всего услуг</small>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-4 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">Галерея</h6>
            </div>
            <div class="card-body text-center">
                <div class="h4 mb-0"><?php echo e($stats['total_gallery_images']); ?></div>
                <small class="text-muted">Изображений в галерее</small>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-4 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-info">Баннеры</h6>
            </div>
            <div class="card-body text-center">
                <div class="h4 mb-0"><?php echo e($stats['total_banners']); ?></div>
                <small class="text-muted">Всего баннеров</small>
            </div>
        </div>
    </div>
</div>

<!-- Последние пользователи и статьи -->
<div class="row">
    <!-- Последние пользователи -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Последние пользователи</h6>
            </div>
            <div class="card-body">
                <?php if($recent_users->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Имя</th>
                                    <th>Username</th>
                                    <th>Роль</th>
                                    <th>Дата регистрации</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $recent_users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($user->name); ?></td>
                                    <td><?php echo e($user->username); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo e($user->role === 'admin' ? 'danger' : 'primary'); ?>">
                                            <?php echo e($user->role === 'admin' ? 'Админ' : 'Пользователь'); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e($user->created_at->format('d.m.Y')); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center">Нет пользователей</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Последние статьи -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">Последние статьи</h6>
            </div>
            <div class="card-body">
                <?php if($recent_articles->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Название</th>
                                    <th>Автор</th>
                                    <th>Статус</th>
                                    <th>Дата</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $recent_articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo e(route('articles.show', ['username' => $article->user->username, 'slug' => $article->slug])); ?>" 
                                           target="_blank" class="text-decoration-none">
                                            <?php echo e(Str::limit($article->title, 30)); ?>

                                        </a>
                                    </td>
                                    <td><?php echo e($article->user->name); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo e($article->is_published ? 'success' : 'warning'); ?>">
                                            <?php echo e($article->is_published ? 'Опубликована' : 'Черновик'); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e($article->created_at->format('d.m.Y')); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center">Нет статей</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
.text-gray-800 {
    color: #5a5c69 !important;
}
.card {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin.super-admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\link\resources\views\admin\super-admin\index.blade.php ENDPATH**/ ?>