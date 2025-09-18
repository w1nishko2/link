@extends('admin.layout')

@section('title', 'Управление баннерами')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="{{ route('admin.banners.create', $currentUserId) }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Добавить баннер
    </a>
</div>
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if($banners->count() > 0)
    <div class="swiper banners-swiper" id="banners-preview-swiper">
        <div class="swiper-wrapper">
                @foreach($banners as $banner)
                    <div class="swiper-slide">
                        <div class="banner-card clickable-card" onclick="editBanner({{ $banner->id }})">
                            <div class="banners-banner">
                                <div class="banners-banner-block">
                                    <h3>{{ $banner->title }}</h3>
                                    @if($banner->description)
                                        <p>{{ $banner->description }}</p>
                                    @endif
                                </div>  
                                
                                <div class="banners-banner-block-img">
                                    @if($banner->image_path)
                                        <img src="{{ asset('storage/' . $banner->image_path) }}" 
                                             alt="{{ $banner->title }}"
                                             loading="lazy"
                                             width="300"
                                             height="200"
                                             decoding="async">
                                    @else
                                        <div class="banner-no-image">
                                            <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="edit-overlay">
                                    <i class="bi bi-pencil-fill"></i>
                                    <span>Редактировать</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
@else
    <div class="text-center py-5">
        <i class="bi bi-megaphone display-1 text-muted"></i>
        <h3 class="mt-3">Нет баннеров</h3>
        <p class="text-muted">Добавьте первый баннер</p>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBannerModal">
            <i class="bi bi-plus-circle me-2"></i>
            Добавить баннер
        </button>
    </div>
@endif

<!-- Модальное окно создания баннера -->
<!-- Модальное окно подтверждения удаления -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Подтверждение удаления</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Вы уверены, что хотите удалить этот баннер? Это действие нельзя отменить.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <form id="deleteForm" action="" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Удалить</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
window.currentUserId = "{{ $currentUserId }}";
</script>
@endsection

@section('scripts')
<!-- Swiper CSS и JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- Подключение через Vite -->
@vite(['resources/css/admin-banners.css', 'resources/js/admin-banners.js'])
@endsection
