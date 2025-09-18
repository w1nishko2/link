@extends('admin.layout')

@section('title', 'Управление услугами - ' . config('app.name'))
@section('description', 'Управление каталогом услуг: создание, редактирование, настройка цен')

<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
@vite(['resources/css/services-reels.css', 'resources/css/admin-services.css', 'resources/js/admin-services.js'])

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
    <h1 class="h4 mb-0">Услуги ({{ $services->count() }})</h1>
    <a href="{{ route('admin.services.create', $currentUserId) }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>
        <span class="d-none d-sm-inline">Добавить услугу</span>
        <span class="d-sm-none">Добавить</span>
    </a>
</div>

@if($services->count() > 0)
    <!-- Блок со слайдером услуг -->
    <div class="row justify-content-center mb-4">
        <div class="col-12">
         
                <div class="swiper services-swiper" id="services-preview-swiper">
                    <div class="swiper-wrapper">
                        @foreach($services as $service)
                            <div class="swiper-slide">
                                <div class="service-card clickable-card" onclick="editService({{ $service->id }})">
                                    <!-- Изображение услуги -->
                                    <div class="service-image">
                                        <img src="{{ $service->image_path ? asset('storage/' . $service->image_path) : '/hero.png' }}" 
                                             alt="{{ $service->title }}" 
                                             loading="lazy"
                                             width="300"
                                             height="600"
                                             decoding="async">
                                        <div class="edit-overlay">
                                            <i class="bi bi-pencil-fill"></i>
                                            <span>Редактировать</span>
                                        </div>
                                    </div>
                                    
                                    <div class="service-content">
                                        <!-- Название услуги -->
                                        <h3>{{ $service->title }}</h3>
                                        
                                        <!-- Описание услуги -->
                                        <p>{{ $service->description }}</p>
                                        
                                        <div class="service-bottom">
                                            @if($service->price)
                                                <!-- Цена -->
                                                <div class="service-price">{{ $service->formatted_price }}</div>
                                            @endif
                                            
                                            <div class="service-buttons">
                                                @if($service->button_text && $service->button_link)
                                                    <!-- Кнопка услуги -->
                                                    <div class="service-button btn btn-primary btn-sm">
                                                        {{ $service->button_text }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!-- Навигация слайдера -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            
        </div>
    </div>
@else
    <div class="text-center py-5">
        <i class="bi bi-briefcase display-1 text-muted"></i>
        <h3 class="mt-3">Нет услуг</h3>
        <p class="text-muted">Добавьте первую услугу</p>
        <a href="{{ route('admin.services.create', $currentUserId) }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>
            Добавить услугу
        </a>
    </div>
@endif

@endsection

@section('scripts')
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
// Инициализация Swiper для просмотра услуг
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('services-preview-swiper')) {
        new Swiper('#services-preview-swiper', {
            slidesPerView: 2.4,
            spaceBetween: 20,
            loop: {{ $services->count() > 1 ? 'true' : 'false' }},
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                320: {
                    slidesPerView: 1.1,
                    spaceBetween: 20,
                },
                480: {
                    slidesPerView: 2.1,
                    spaceBetween: 20,
                },
                700: {
                    slidesPerView: 2.1,
                    spaceBetween: 20,
                },
                768: {
                    slidesPerView: 2.1,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: 2.1,
                    spaceBetween: 20,
                },
                1200: {
                    slidesPerView: 3.2,
                    spaceBetween: 20,
                }
            }
        });
    }
});

// Функция для редактирования услуги
function editService(serviceId) {
    window.location.href = "{{ route('admin.services.edit', [$currentUserId, ':id']) }}".replace(':id', serviceId);
}

// Функция для удаления услуги
function deleteService(serviceId) {
    if (confirm('Вы уверены, что хотите удалить эту услугу?')) {
        const form = document.getElementById('deleteServiceForm');
        form.action = "{{ route('admin.services.destroy', [$currentUserId, ':id']) }}".replace(':id', serviceId);
        form.submit();
    }
}
</script>

<!-- Form для удаления услуги -->
<form id="deleteServiceForm" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection
