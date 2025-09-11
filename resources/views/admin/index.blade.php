@extends('admin.layout')

@section('title', 'Панель управления - ' . config('app.name'))
@section('description', 'Панель управления контентом: статьи, услуги, галерея, баннеры')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    <h1 class="mb-2 mb-md-0">Панель управления</h1>
    <div class="text-muted">
        Добро пожаловать, {{ auth()->user()->name }}!
    </div>
</div>

<!-- Основная статистика -->
<div class="row mb-4">
    <div class="col-sm-6 col-lg-3 mb-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="h4 mb-0">{{ $stats['total_articles'] }}</div>
                        <div class="small">Всего статей</div>
                        <div class="text-white-50 small">Опубликовано: {{ $stats['published_articles'] }}</div>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-journal-text" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.articles', $currentUserId) }}" class="text-white text-decoration-none">
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
                        <div class="h4 mb-0">{{ $stats['total_services'] }}</div>
                        <div class="small">Всего услуг</div>
                        <div class="text-white-50 small">Активных: {{ $stats['active_services'] }}</div>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-briefcase" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.services', $currentUserId) }}" class="text-white text-decoration-none">
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
                        <div class="h4 mb-0">{{ $stats['total_gallery_images'] }}</div>
                        <div class="small">Изображений</div>
                        <div class="text-white-50 small">Активных: {{ $stats['active_gallery_images'] }}</div>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-images" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.gallery', $currentUserId) }}" class="text-white text-decoration-none">
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
                        <div class="h4 mb-0">{{ $stats['total_banners'] }}</div>
                        <div class="small">Баннеров</div>
                        <div class="text-white-50 small">Активных: {{ $stats['active_banners'] }}</div>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-card-image" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.banners', $currentUserId) }}" class="text-white text-decoration-none">
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
                        <span>{{ $contentPerformance['profile_completion'] }}%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" 
                             style="width: {{ $contentPerformance['profile_completion'] }}%" 
                             aria-valuenow="{{ $contentPerformance['profile_completion'] }}" 
                             aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                
                <div class="small text-muted mb-3">
                    Всего контента: {{ $contentPerformance['total_content_items'] }} элементов
                </div>

                <div class="d-grid gap-2">
                    @if($contentPerformance['profile_completion'] < 100)
                        <a href="{{ route('admin.profile', $currentUserId) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-person-gear me-2"></i>
                            Дополнить профиль
                        </a>
                    @endif
                    <a href="{{ route('user.page', $user->username) }}" class="btn btn-outline-success btn-sm" target="_blank">
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
                    <a href="{{ route('admin.articles.create', $currentUserId) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-2"></i>
                        Добавить статью
                    </a>
                    <a href="{{ route('admin.services.create', $currentUserId) }}" class="btn btn-success btn-sm">
                        <i class="bi bi-plus-circle me-2"></i>
                        Добавить услугу
                    </a>
                    <a href="{{ route('admin.gallery', $currentUserId) }}" class="btn btn-info btn-sm">
                        <i class="bi bi-plus-circle me-2"></i>
                        Добавить в галерею
                    </a>
                    <a href="{{ route('admin.banners', $currentUserId) }}" class="btn btn-warning btn-sm">
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
                <a href="{{ route('admin.articles', $currentUserId) }}" class="btn btn-outline-primary btn-sm">Все статьи</a>
            </div>
            <div class="card-body">
                @if($recent['recent_articles']->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recent['recent_articles'] as $article)
                            <div class="list-group-item px-0 d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        <a href="{{ route('articles.show', ['username' => $user->username, 'slug' => $article->slug]) }}" 
                                           class="text-decoration-none" target="_blank">
                                            {{ Str::limit($article->title, 50) }}
                                        </a>
                                    </h6>
                                    <small class="text-muted">{{ $article->created_at->diffForHumans() }}</small>
                                </div>
                                <div class="ms-2">
                                    @if($article->is_published)
                                        <span class="badge bg-success">Опубликована</span>
                                    @else
                                        <span class="badge bg-secondary">Черновик</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted py-3">
                        <i class="bi bi-journal-text mb-2" style="font-size: 2rem;"></i>
                        <p class="mb-0">Нет статей</p>
                        <a href="{{ route('admin.articles.create', $currentUserId) }}" class="btn btn-primary btn-sm mt-2">Создать первую статью</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Последние услуги</h5>
                <a href="{{ route('admin.services', $currentUserId) }}" class="btn btn-outline-success btn-sm">Все услуги</a>
            </div>
            <div class="card-body">
                @if($recent['recent_services']->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recent['recent_services'] as $service)
                            <div class="list-group-item px-0 d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ Str::limit($service->title, 50) }}</h6>
                                    <small class="text-muted">{{ $service->created_at->diffForHumans() }}</small>
                                </div>
                                <div class="ms-2">
                                    @if($service->is_active)
                                        <span class="badge bg-success">Активна</span>
                                    @else
                                        <span class="badge bg-secondary">Неактивна</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted py-3">
                        <i class="bi bi-briefcase mb-2" style="font-size: 2rem;"></i>
                        <p class="mb-0">Нет услуг</p>
                        <a href="{{ route('admin.services.create', $currentUserId) }}" class="btn btn-success btn-sm mt-2">Добавить первую услугу</a>
                    </div>
                @endif
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
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// График активности
const ctx = document.getElementById('activityChart').getContext('2d');
const dateStats = @json($dateStats);

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
@endsection
