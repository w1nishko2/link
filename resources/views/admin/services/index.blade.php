@extends('admin.layout')

@section('title', 'Управление услугами - ' . config('app.name'))
@section('description', 'Управление каталогом услуг: создание, редактирование, настройка цен')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
 
    <a href="{{ route('admin.services.create', $currentUserId) }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>
        <span class="d-none d-sm-inline">Добавить услугу</span>
        <span class="d-sm-none">Добавить</span>
    </a>
</div>

@if($services->count() > 0)
    <div class="row">
        @foreach($services as $service)
            <div class="col-sm-6 col-lg-4 mb-4">
                <div class="card h-100">
                    @if($service->image_path)
                        <img src="{{ asset('storage/' . $service->image_path) }}" 
                             class="card-img-top" 
                             alt="{{ $service->title }}"
                             style="height: 200px; object-fit: cover;">
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $service->title }}</h5>
                        <p class="card-text flex-grow-1">{{ Str::limit($service->description, 100) }}</p>
                        @if($service->price)
                            <p class="card-text">
                                <strong>{{ $service->formatted_price }}</strong>
                            </p>
                        @endif
                       
                    </div>
                    
                </div>
            </div>
        @endforeach
    </div>
    
    <div class="d-flex justify-content-center mt-4">
        {{ $services->links('pagination.custom') }}
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

<!-- Form для удаления услуги -->
<form id="deleteServiceForm" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@section('scripts')
<script>
function deleteService(serviceId) {
    if (confirm('Вы уверены, что хотите удалить эту услугу?')) {
        const form = document.getElementById('deleteServiceForm');
        form.action = "{{ route('admin.services.destroy', [$currentUserId, ':id']) }}".replace(':id', serviceId);
        form.submit();
    }
}
</script>
@endsection
