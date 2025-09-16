

<?php $__env->startSection('title', 'Панель управления - ' . config('app.name')); ?>
<?php $__env->startSection('description', 'Панель управления контентом: статьи, услуги, галерея, баннеры'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    <h1 class="mb-2 mb-md-0">Панель управления</h1>
    <div class="text-muted">
        Добро пожаловать, <?php echo e(auth()->user()->name); ?>!
    </div>
</div>

<!-- Основная статистика -->
<div class="row mb-4">
    <div class="col-sm-6 col-lg-3 mb-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="h4 mb-0"><?php echo e($stats['total_articles']); ?></div>
                        <div class="small">Всего статей</div>
                        <div class="text-white-50 small">Опубликовано: <?php echo e($stats['published_articles']); ?></div>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-journal-text" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="<?php echo e(route('admin.articles', $currentUserId)); ?>" class="text-white text-decoration-none">
                    <small>Управление статьями <i class="bi bi-arrow-right"></i></small>
                </a>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="h4 mb-0"><?php echo e($stats['total_services']); ?></div>
                        <div class="small">Всего услуг</div>
                        <div class="text-white-50 small">Активных: <?php echo e($stats['active_services']); ?></div>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-briefcase" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="<?php echo e(route('admin.services', $currentUserId)); ?>" class="text-white text-decoration-none">
                    <small>Управление услугами <i class="bi bi-arrow-right"></i></small>
                </a>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3 mb-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="h4 mb-0"><?php echo e($stats['total_gallery_images']); ?></div>
                        <div class="small">Изображений</div>
                        <div class="text-white-50 small">Активных: <?php echo e($stats['active_gallery_images']); ?></div>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-images" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="<?php echo e(route('admin.gallery', $currentUserId)); ?>" class="text-white text-decoration-none">
                    <small>Управление галереей <i class="bi bi-arrow-right"></i></small>
                </a>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3 mb-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="h4 mb-0"><?php echo e($stats['total_banners']); ?></div>
                        <div class="small">Баннеров</div>
                        <div class="text-white-50 small">Активных: <?php echo e($stats['active_banners']); ?></div>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-card-image" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="<?php echo e(route('admin.banners', $currentUserId)); ?>" class="text-white text-decoration-none">
                    <small>Управление баннерами <i class="bi bi-arrow-right"></i></small>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Заполненность профиля и быстрые действия -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Заполненность профиля</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Профиль заполнен</span>
                        <span><?php echo e($contentPerformance['profile_completion']); ?>%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" 
                             style="width: <?php echo e($contentPerformance['profile_completion']); ?>%" 
                             aria-valuenow="<?php echo e($contentPerformance['profile_completion']); ?>" 
                             aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                
                <div class="small text-muted mb-3">
                    Всего контента: <?php echo e($contentPerformance['total_content_items']); ?> элементов
                </div>

                <div class="d-grid gap-2">
                    <?php if($contentPerformance['profile_completion'] < 100): ?>
                        <a href="<?php echo e(route('admin.profile', $currentUserId)); ?>" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-person-gear me-2"></i>
                            Дополнить профиль
                        </a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('user.page', $user->username)); ?>" class="btn btn-outline-success btn-sm" target="_blank">
                        <i class="bi bi-eye me-2"></i>
                        Посмотреть свою страницу
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Быстрые действия</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?php echo e(route('admin.articles.create', $currentUserId)); ?>" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-2"></i>
                        Добавить статью
                    </a>
                    <a href="<?php echo e(route('admin.services.create', $currentUserId)); ?>" class="btn btn-success btn-sm">
                        <i class="bi bi-plus-circle me-2"></i>
                        Добавить услугу
                    </a>
                    <a href="<?php echo e(route('admin.gallery', $currentUserId)); ?>" class="btn btn-info btn-sm">
                        <i class="bi bi-plus-circle me-2"></i>
                        Добавить в галерею
                    </a>
                    <a href="<?php echo e(route('admin.banners', $currentUserId)); ?>" class="btn btn-warning btn-sm">
                        <i class="bi bi-plus-circle me-2"></i>
                        Добавить баннер
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Последние добавленные элементы -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Последние статьи</h5>
                <a href="<?php echo e(route('admin.articles', $currentUserId)); ?>" class="btn btn-outline-primary btn-sm">Все статьи</a>
            </div>
            <div class="card-body">
                <?php if($recent['recent_articles']->count() > 0): ?>
                    <div class="list-group list-group-flush">
                        <?php $__currentLoopData = $recent['recent_articles']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="list-group-item px-0 d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        <a href="<?php echo e(route('articles.show', ['username' => $user->username, 'slug' => $article->slug])); ?>" 
                                           class="text-decoration-none" target="_blank">
                                            <?php echo e(Str::limit($article->title, 50)); ?>

                                        </a>
                                    </h6>
                                    <small class="text-muted"><?php echo e($article->created_at->diffForHumans()); ?></small>
                                </div>
                                <div class="ms-2">
                                    <?php if($article->is_published): ?>
                                        <span class="badge bg-success">Опубликована</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Черновик</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-3">
                        <i class="bi bi-journal-text mb-2" style="font-size: 2rem;"></i>
                        <p class="mb-0">Нет статей</p>
                        <a href="<?php echo e(route('admin.articles.create', $currentUserId)); ?>" class="btn btn-primary btn-sm mt-2">Создать первую статью</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Последние услуги</h5>
                <a href="<?php echo e(route('admin.services', $currentUserId)); ?>" class="btn btn-outline-success btn-sm">Все услуги</a>
            </div>
            <div class="card-body">
                <?php if($recent['recent_services']->count() > 0): ?>
                    <div class="list-group list-group-flush">
                        <?php $__currentLoopData = $recent['recent_services']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="list-group-item px-0 d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1"><?php echo e(Str::limit($service->title, 50)); ?></h6>
                                    <small class="text-muted"><?php echo e($service->created_at->diffForHumans()); ?></small>
                                </div>
                                <div class="ms-2">
                                    <?php if($service->is_active): ?>
                                        <span class="badge bg-success">Активна</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Неактивна</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-3">
                        <i class="bi bi-briefcase mb-2" style="font-size: 2rem;"></i>
                        <p class="mb-0">Нет услуг</p>
                        <a href="<?php echo e(route('admin.services.create', $currentUserId)); ?>" class="btn btn-success btn-sm mt-2">Добавить первую услугу</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Активность за последние 30 дней (График) -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Активность за последние 30 дней</h5>
            </div>
            <div class="card-body">
                <canvas id="activityChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// График активности
const ctx = document.getElementById('activityChart').getContext('2d');
const dateStats = <?php echo json_encode($dateStats, 15, 512) ?>;

const labels = dateStats.map(item => {
    const date = new Date(item.date);
    return date.toLocaleDateString('ru-RU', { day: '2-digit', month: '2-digit' });
});

const articlesData = dateStats.map(item => item.articles);
const servicesData = dateStats.map(item => item.services);
const galleryData = dateStats.map(item => item.gallery);

new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'Статьи',
                data: articlesData,
                borderColor: '#0d6efd',
                backgroundColor: '#0d6efd',
                tension: 0.4
            },
            {
                label: 'Услуги',
                data: servicesData,
                borderColor: '#198754',
                backgroundColor: '#198754',
                tension: 0.4
            },
            {
                label: 'Галерея',
                data: galleryData,
                borderColor: '#0dcaf0',
                backgroundColor: '#0dcaf0',
                tension: 0.4
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        },
        plugins: {
            legend: {
                position: 'top',
            }
        }
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\link\resources\views/admin/index.blade.php ENDPATH**/ ?>