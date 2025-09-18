

<?php $__env->startSection('title', 'Управление пользователями'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Управление пользователями</h1>
    <div class="text-muted">
        <i class="bi bi-people"></i> Всего пользователей: <?php echo e($users->total()); ?>

    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Список пользователей</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Username</th>
                        <th>Телефон</th>
                        <th>Роль</th>
                        <th>Дата регистрации</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($user->id); ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <?php if($user->avatar): ?>
                                    <img src="<?php echo e(asset('storage/' . $user->avatar)); ?>" 
                                         alt="<?php echo e($user->name); ?>" 
                                         class="rounded-circle me-2"
                                         style="width: 32px; height: 32px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center"
                                         style="width: 32px; height: 32px;">
                                        <i class="bi bi-person text-white"></i>
                                    </div>
                                <?php endif; ?>
                                <?php echo e($user->name); ?>

                            </div>
                        </td>
                        <td><?php echo e($user->username); ?></td>
                        <td><?php echo e($user->phone ?? 'Не указан'); ?></td>
                        <td>
                            <span class="badge bg-<?php echo e($user->role === 'admin' ? 'danger' : 'primary'); ?>">
                                <?php echo e($user->role === 'admin' ? 'Администратор' : 'Пользователь'); ?>

                            </span>
                        </td>
                        <td><?php echo e($user->created_at->format('d.m.Y H:i')); ?></td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="<?php echo e(route('user.page', $user->username)); ?>" 
                                   target="_blank" 
                                   class="btn btn-sm 
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-secondary" disabled>
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" disabled>
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">Пользователи не найдены</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Пагинация -->
        <?php if($users->hasPages()): ?>
        <div class="d-flex justify-content-center mt-4">
            <?php echo e($users->links('pagination.custom')); ?>

        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Информационная карточка -->
<div class="alert alert-info">
    <h5 class="alert-heading">
        <i class="bi bi-info-circle"></i> Информация
    </h5>
    <p class="mb-0">
        Эта страница находится в разработке. 
        В будущем здесь будет полнофункциональное управление пользователями, 
        включая возможность изменения ролей, блокировки пользователей и другие административные функции.
    </p>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.super-admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\link\resources\views\admin\super-admin\users.blade.php ENDPATH**/ ?>