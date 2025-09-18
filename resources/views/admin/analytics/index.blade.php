@extends('admin.layout')

@section('title', 'Аналитика - ' . config('app.name'))

@section('content')
<div class="container-fluid ">
    <div class="d-flex justify-content-between align-items-center mb-4">
       
        <div class="d-flex gap-2" style="width: 100%; margin: 0;">
            <button class="btn  btn-sm" onclick="refreshData()" style="width: 100%; margin: 0;">
                <i class="bi bi-arrow-clockwise me-1"></i>
                Обновить
            </button>
            <div class="d-flex gap-2 btn-group" role="group">
                <input type="radio" class="btn-check" name="period" id="period-daily" value="daily" checked>
                <label class="btn btn-outline-secondary btn-sm" for="period-daily">30д</label>
                
                <input type="radio" class="btn-check" name="period" id="period-monthly" value="monthly">
                <label class="btn btn-outline-secondary btn-sm" for="period-monthly">12м</label>
            </div>
        </div>
    </div>

    <!-- Основная статистика -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card stats-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-primary">
                            <i class="bi bi-journal-text"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <div class="stats-label">Статьи</div>
                            <div class="stats-value">{{ $stats['total_articles'] }}</div>
                            <div class="stats-subtext">
                                <span class="text-success">{{ $stats['published_articles'] }}</span> опубликовано
                            </div>
                        </div>
                        @if(isset($growthStats['articles']))
                        <div class="stats-change {{ $growthStats['articles']['change'] >= 0 ? 'positive' : 'negative' }}">
                            <i class="bi bi-arrow-{{ $growthStats['articles']['change'] >= 0 ? 'up' : 'down' }}"></i>
                            {{ abs($growthStats['articles']['change']) }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card stats-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-success">
                            <i class="bi bi-briefcase"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <div class="stats-label">Услуги</div>
                            <div class="stats-value">{{ $stats['total_services'] }}</div>
                            <div class="stats-subtext">
                                <span class="text-success">{{ $stats['active_services'] }}</span> активных
                            </div>
                        </div>
                        @if(isset($growthStats['services']))
                        <div class="stats-change {{ $growthStats['services']['change'] >= 0 ? 'positive' : 'negative' }}">
                            <i class="bi bi-arrow-{{ $growthStats['services']['change'] >= 0 ? 'up' : 'down' }}"></i>
                            {{ abs($growthStats['services']['change']) }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card stats-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-warning">
                            <i class="bi bi-images"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <div class="stats-label">Галерея</div>
                            <div class="stats-value">{{ $stats['total_gallery_images'] }}</div>
                            <div class="stats-subtext">
                                <span class="text-success">{{ $stats['active_gallery_images'] }}</span> активных
                            </div>
                        </div>
                        @if(isset($growthStats['gallery']))
                        <div class="stats-change {{ $growthStats['gallery']['change'] >= 0 ? 'positive' : 'negative' }}">
                            <i class="bi bi-arrow-{{ $growthStats['gallery']['change'] >= 0 ? 'up' : 'down' }}"></i>
                            {{ abs($growthStats['gallery']['change']) }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card stats-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-danger">
                            <i class="bi bi-flag"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <div class="stats-label">Баннеры</div>
                            <div class="stats-value">{{ $stats['total_banners'] }}</div>
                            <div class="stats-subtext">
                                <span class="text-success">{{ $stats['active_banners'] }}</span> активных
                            </div>
                        </div>
                        @if(isset($growthStats['banners']))
                        <div class="stats-change {{ $growthStats['banners']['change'] >= 0 ? 'positive' : 'negative' }}">
                            <i class="bi bi-arrow-{{ $growthStats['banners']['change'] >= 0 ? 'up' : 'down' }}"></i>
                            {{ abs($growthStats['banners']['change']) }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Графики -->
    <div class="row mb-4">
        <div class="col-lg-8 mb-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-graph-up me-2"></i>
                        Динамика добавления контента
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="contentChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-pie-chart me-2"></i>
                        Распределение контента
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="pieChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Заполненность профиля -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-person-check me-2"></i>
                        Заполненность профиля
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="progress flex-grow-1 me-3" style="height: 20px;">
                            <div class="progress-bar bg-gradient" 
                                 role="progressbar" 
                                 style="width: {{ $profileCompletion['percentage'] }}%"
                                 aria-valuenow="{{ $profileCompletion['percentage'] }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                            </div>
                        </div>
                        <strong class="text-primary">{{ $profileCompletion['percentage'] }}%</strong>
                    </div>
                    <div class="row">
                        @foreach($profileCompletion['fields'] as $field => $completed)
                        <div class="col-6 mb-2">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-{{ $completed ? 'check-circle-fill text-success' : 'circle text-muted' }} me-2"></i>
                                <small class="{{ $completed ? 'text-dark' : 'text-muted' }}">
                                    {{ ucfirst(str_replace('_', ' ', $field)) }}
                                </small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-bar-chart me-2"></i>
                        Активность по типам
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($contentTypeStats as $type)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-medium">{{ $type['type'] }}</span>
                            <span class="text-muted">{{ $type['active'] }}/{{ $type['total'] }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar" 
                                 style="width: {{ $type['total'] > 0 ? ($type['active'] / $type['total']) * 100 : 0 }}%; background-color: {{ $type['color'] }}"
                                 role="progressbar">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Данные для графиков
    const dailyData = @json($dailyStats);
    const monthlyData = @json($monthlyStats);
    const contentTypeData = @json($contentTypeStats);
    
    let currentChart = null;
    let currentPieChart = null;
    
    // Инициализация графиков
    initializeCharts('daily');
    
    // Обработчики для переключения периода
    document.querySelectorAll('input[name="period"]').forEach(input => {
        input.addEventListener('change', function() {
            initializeCharts(this.value);
        });
    });
    
    function initializeCharts(period) {
        const data = period === 'daily' ? dailyData : monthlyData;
        const labels = data.map(item => period === 'daily' ? item.day : item.month);
        
        // Уничтожаем предыдущие графики
        if (currentChart) currentChart.destroy();
        if (currentPieChart) currentPieChart.destroy();
        
        // Основной график
        const ctx = document.getElementById('contentChart').getContext('2d');
        currentChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Статьи',
                        data: data.map(item => item.articles),
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Услуги',
                        data: data.map(item => item.services),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Галерея',
                        data: data.map(item => item.gallery),
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Баннеры',
                        data: data.map(item => item.banners),
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        fill: true,
                        tension: 0.4
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
                        ticks: {
                            precision: 0
                        }
                    }
                },
                elements: {
                    point: {
                        radius: 3,
                        hoverRadius: 6
                    }
                }
            }
        });
        
        // Круговая диаграмма
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        currentPieChart = new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: contentTypeData.map(item => item.type),
                datasets: [{
                    data: contentTypeData.map(item => item.total),
                    backgroundColor: contentTypeData.map(item => item.color),
                    borderWidth: 2,
                    borderColor: '#fff'
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
                },
                cutout: '60%'
            }
        });
    }
    
    // Функция обновления данных
    window.refreshData = function() {
        window.location.reload();
    };
});
</script>

<style>
.stats-card {
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.stats-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
}

.stats-label {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
}

.stats-value {
    font-size: 2rem;
    font-weight: 700;
    color: #111827;
    line-height: 1;
}

.stats-subtext {
    font-size: 0.75rem;
    color: #6b7280;
}

.stats-change {
    font-size: 0.875rem;
    font-weight: 600;
    padding: 4px 8px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    gap: 2px;
}

.stats-change.positive {
    color: #059669;
    background-color: #d1fae5;
}

.stats-change.negative {
    color: #dc2626;
    background-color: #fee2e2;
}

.card {
    border: none;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    border-radius: 12px;
}

.card-header {
    background-color: #f8fafc;
    border-bottom: 1px solid #e5e7eb;
    border-radius: 12px 12px 0 0 !important;
}

.progress {
    border-radius: 10px;
    background-color: #f3f4f6;
}

.progress-bar {
    border-radius: 10px;
}

.bg-gradient {
    background: linear-gradient(90deg, #3b82f6, #1d4ed8);
}

.btn-group .btn-check:checked + .btn {
    background-color: #3b82f6;
    border-color: #3b82f6;
    color: white;
}

@media (max-width: 768px) {
    .stats-value {
        font-size: 1.5rem;
    }
    
    .stats-change {
        font-size: 0.75rem;
    }
}
</style>
@endsection
     