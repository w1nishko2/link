

<?php $__env->startSection('title', 'Аналитика'); ?>

<?php $__env->startSection('styles'); ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.min.css">
<style>
    .analytics-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 24px;
        margin-bottom: 24px;
        transition: transform 0.2s ease-in-out;
    }
    
    .analytics-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .stat-number {
        font-size: 2.5rem;
        font-weight: bold;
        margin: 10px 0;
        background: linear-gradient(45deg, #667eea, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .stat-label {
        color: #6b7280;
        font-size: 0.875rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .growth-indicator {
        display: inline-flex;
        align-items: center;
        font-size: 0.875rem;
        font-weight: 600;
        margin-top: 8px;
        padding: 4px 8px;
        border-radius: 20px;
    }
    
    .growth-positive {
        background-color: #ecfdf5;
        color: #065f46;
    }
    
    .growth-negative {
        background-color: #fef2f2;
        color: #991b1b;
    }
    
    .growth-neutral {
        background-color: #f3f4f6;
        color: #374151;
    }
    
    .chart-container {
        position: relative;
        height: 400px;
        margin-top: 20px;
    }
    
    .chart-tabs {
        display: flex;
        gap: 8px;
        margin-bottom: 20px;
    }
    
    .chart-tab {
        padding: 8px 16px;
        border: 2px solid #e5e7eb;
        background: white;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        font-weight: 500;
    }
    
    .chart-tab.active {
        background: #667eea;
        border-color: #667eea;
        color: white;
    }
    
    .chart-tab:hover:not(.active) {
        border-color: #667eea;
        color: #667eea;
    }
    
    .progress-bar {
        width: 100%;
        height: 8px;
        background: #e5e7eb;
        border-radius: 4px;
        overflow: hidden;
        margin-top: 8px;
    }
    
    .progress-fill {
        height: 100%;
        background: linear-gradient(45deg, #667eea, #764ba2);
        border-radius: 4px;
        transition: width 0.8s ease-in-out;
    }
    
    .content-type-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-top: 20px;
    }
    
    .content-type-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        position: relative;
        overflow: hidden;
    }
    
    .content-type-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }
    
    .fade-enter-active, .fade-leave-active {
        transition: opacity 0.5s;
    }
    
    .fade-enter, .fade-leave-to {
        opacity: 0;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <!-- Заголовок -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">📊 Аналитика</h1>
            <p class="text-muted">Детальная статистика вашего контента</p>
        </div>
        <div>
            <span class="badge bg-primary"><?php echo e(now()->format('d.m.Y')); ?></span>
        </div>
    </div>

    <!-- Основная статистика -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="stat-label">Всего статей</div>
                <div class="stat-number"><?php echo e($stats['total_articles']); ?></div>
                <div class="text-muted small">
                    Опубликовано: <?php echo e($stats['published_articles']); ?>

                </div>
                <?php if(isset($growthStats['articles'])): ?>
                    <div class="growth-indicator <?php echo e($growthStats['articles']['change'] > 0 ? 'growth-positive' : ($growthStats['articles']['change'] < 0 ? 'growth-negative' : 'growth-neutral')); ?>">
                        <?php echo e($growthStats['articles']['change'] > 0 ? '+' : ''); ?><?php echo e($growthStats['articles']['change']); ?>

                        (<?php echo e($growthStats['articles']['percentage'] > 0 ? '+' : ''); ?><?php echo e($growthStats['articles']['percentage']); ?>%)
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="stat-label">Всего услуг</div>
                <div class="stat-number"><?php echo e($stats['total_services']); ?></div>
                <div class="text-muted small">
                    Активных: <?php echo e($stats['active_services']); ?>

                </div>
                <?php if(isset($growthStats['services'])): ?>
                    <div class="growth-indicator <?php echo e($growthStats['services']['change'] > 0 ? 'growth-positive' : ($growthStats['services']['change'] < 0 ? 'growth-negative' : 'growth-neutral')); ?>">
                        <?php echo e($growthStats['services']['change'] > 0 ? '+' : ''); ?><?php echo e($growthStats['services']['change']); ?>

                        (<?php echo e($growthStats['services']['percentage'] > 0 ? '+' : ''); ?><?php echo e($growthStats['services']['percentage']); ?>%)
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="stat-label">Изображения галереи</div>
                <div class="stat-number"><?php echo e($stats['total_gallery_images']); ?></div>
                <div class="text-muted small">
                    Активных: <?php echo e($stats['active_gallery_images']); ?>

                </div>
                <?php if(isset($growthStats['gallery'])): ?>
                    <div class="growth-indicator <?php echo e($growthStats['gallery']['change'] > 0 ? 'growth-positive' : ($growthStats['gallery']['change'] < 0 ? 'growth-negative' : 'growth-neutral')); ?>">
                        <?php echo e($growthStats['gallery']['change'] > 0 ? '+' : ''); ?><?php echo e($growthStats['gallery']['change']); ?>

                        (<?php echo e($growthStats['gallery']['percentage'] > 0 ? '+' : ''); ?><?php echo e($growthStats['gallery']['percentage']); ?>%)
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="stat-label">Баннеры</div>
                <div class="stat-number"><?php echo e($stats['total_banners']); ?></div>
                <div class="text-muted small">
                    Активных: <?php echo e($stats['active_banners']); ?>

                </div>
                <?php if(isset($growthStats['banners'])): ?>
                    <div class="growth-indicator <?php echo e($growthStats['banners']['change'] > 0 ? 'growth-positive' : ($growthStats['banners']['change'] < 0 ? 'growth-negative' : 'growth-neutral')); ?>">
                        <?php echo e($growthStats['banners']['change'] > 0 ? '+' : ''); ?><?php echo e($growthStats['banners']['change']); ?>

                        (<?php echo e($growthStats['banners']['percentage'] > 0 ? '+' : ''); ?><?php echo e($growthStats['banners']['percentage']); ?>%)
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- График динамики -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="analytics-card">
                <h4 class="mb-3">📈 Динамика создания контента</h4>
                
                <div class="chart-tabs">
                    <button class="chart-tab active" data-period="monthly">По месяцам</button>
                    <button class="chart-tab" data-period="daily">По дням (30 дней)</button>
                </div>
                
                <div class="chart-container">
                    <canvas id="dynamicsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Распределение контента и профиль -->
    <div class="row mb-4">
        <!-- Распределение по типам контента -->
        <div class="col-lg-8">
            <div class="analytics-card">
                <h4 class="mb-3">🎯 Распределение контента по типам</h4>
                
                <div class="content-type-grid">
                    <?php $__currentLoopData = $contentTypeStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contentType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="content-type-card" style="border-top: 4px solid <?php echo e($contentType['color']); ?>;">
                            <h5 style="color: <?php echo e($contentType['color']); ?>;"><?php echo e($contentType['type']); ?></h5>
                            <div class="stat-number" style="font-size: 1.8rem;"><?php echo e($contentType['total']); ?></div>
                            <div class="text-muted small">
                                Активных: <?php echo e($contentType['active']); ?>

                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?php echo e($contentType['total'] > 0 ? ($contentType['active'] / $contentType['total']) * 100 : 0); ?>%; background: <?php echo e($contentType['color']); ?>;"></div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                
                <div class="chart-container mt-4" style="height: 300px;">
                    <canvas id="contentTypeChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Заполненность профиля -->
        <div class="col-lg-4">
            <div class="analytics-card">
                <h4 class="mb-3">👤 Заполненность профиля</h4>
                
                <div class="text-center mb-4">
                    <div class="stat-number" style="font-size: 3rem;"><?php echo e($profileCompletion['percentage']); ?>%</div>
                    <div class="text-muted"><?php echo e($profileCompletion['completed']); ?> из <?php echo e($profileCompletion['total']); ?> полей</div>
                </div>
                
                <div class="chart-container" style="height: 250px;">
                    <canvas id="profileChart"></canvas>
                </div>
                
                <div class="mt-4">
                    <h6>Статус полей:</h6>
                    <div class="small">
                        <?php $__currentLoopData = $profileCompletion['fields']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $completed): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="d-flex justify-content-between align-items-center py-1">
                                <span><?php echo e(ucfirst(str_replace('_', ' ', $field))); ?></span>
                                <span class="badge <?php echo e($completed ? 'bg-success' : 'bg-secondary'); ?>">
                                    <?php echo e($completed ? '✓' : '✗'); ?>

                                </span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Данные для графиков
    const monthlyData = <?php echo json_encode($monthlyStats, 15, 512) ?>;
    const dailyData = <?php echo json_encode($dailyStats, 15, 512) ?>;
    const contentTypeData = <?php echo json_encode($contentTypeStats, 15, 512) ?>;
    const profileData = <?php echo json_encode($profileCompletion, 15, 512) ?>;
    
    // График динамики
    const dynamicsCtx = document.getElementById('dynamicsChart').getContext('2d');
    let dynamicsChart = new Chart(dynamicsCtx, {
        type: 'line',
        data: {
            labels: monthlyData.map(item => item.month),
            datasets: [
                {
                    label: 'Статьи',
                    data: monthlyData.map(item => item.articles),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Услуги',
                    data: monthlyData.map(item => item.services),
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Галерея',
                    data: monthlyData.map(item => item.gallery),
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Баннеры',
                    data: monthlyData.map(item => item.banners),
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
    
    // График распределения контента
    const contentTypeCtx = document.getElementById('contentTypeChart').getContext('2d');
    new Chart(contentTypeCtx, {
        type: 'doughnut',
        data: {
            labels: contentTypeData.map(item => item.type),
            datasets: [{
                data: contentTypeData.map(item => item.total),
                backgroundColor: contentTypeData.map(item => item.color),
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            }
        }
    });
    
    // График заполненности профиля
    const profileCtx = document.getElementById('profileChart').getContext('2d');
    new Chart(profileCtx, {
        type: 'doughnut',
        data: {
            labels: ['Заполнено', 'Не заполнено'],
            datasets: [{
                data: [profileData.completed, profileData.total - profileData.completed],
                backgroundColor: ['#10b981', '#e5e7eb'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
    
    // Переключение периодов графика
    document.querySelectorAll('.chart-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            // Убираем активный класс у всех табов
            document.querySelectorAll('.chart-tab').forEach(t => t.classList.remove('active'));
            // Добавляем активный класс к нажатому табу
            this.classList.add('active');
            
            const period = this.dataset.period;
            
            if (period === 'daily') {
                // Обновляем данные для дневного периода
                dynamicsChart.data.labels = dailyData.map(item => item.day);
                dynamicsChart.data.datasets[0].data = dailyData.map(item => item.articles);
                dynamicsChart.data.datasets[1].data = dailyData.map(item => item.services);
                dynamicsChart.data.datasets[2].data = dailyData.map(item => item.gallery);
                dynamicsChart.data.datasets[3].data = dailyData.map(item => item.banners);
            } else {
                // Обновляем данные для месячного периода
                dynamicsChart.data.labels = monthlyData.map(item => item.month);
                dynamicsChart.data.datasets[0].data = monthlyData.map(item => item.articles);
                dynamicsChart.data.datasets[1].data = monthlyData.map(item => item.services);
                dynamicsChart.data.datasets[2].data = monthlyData.map(item => item.gallery);
                dynamicsChart.data.datasets[3].data = monthlyData.map(item => item.banners);
            }
            
            dynamicsChart.update('active');
        });
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\link\resources\views\admin\analytics\index.blade.php ENDPATH**/ ?>