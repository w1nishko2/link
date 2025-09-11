@extends('admin.layout')

@section('title', 'Управление статьями')
@section('description', 'Управление статьями блога: создание, редактирование, публикация')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
    <h1 class="h3 mb-0">Управление статьями</h1>
    <a href="{{ route('admin.articles.create', $currentUserId) }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>
        <span class="d-none d-sm-inline">Добавить статью</span>
        <span class="d-sm-none">Добавить</span>
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($articles->count() > 0)
    <div class="row">
        @foreach($articles as $article)
            <div class="col-sm-6 col-lg-4 mb-4">
                <div class="card h-100">
                    @if($article->image_path)
                        <img src="{{ asset('storage/' . $article->image_path) }}" class="card-img-top" style="height: 180px; object-fit: cover;" alt="{{ $article->title }}">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                            <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                        </div>
                    @endif
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ Str::limit($article->title, 50) }}</h5>
                        <p class="card-text flex-grow-1">{{ Str::limit($article->excerpt, 80) }}</p>
                        <div class="mt-auto">
                            <small class="text-muted">
                                {{ $article->created_at->format('d.m.Y') }}
                            </small>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-transparent">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.articles.edit', [$currentUserId, $article]) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-pencil me-2"></i>
                                Редактировать
                            </a>
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteArticle({{ $article->id }})">
                                <i class="bi bi-trash me-2"></i>
                                Удалить
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Пагинация -->
    @if($articles->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $articles->links() }}
        </div>
    @endif
@else
    <div class="text-center py-5">
        <i class="bi bi-journal-text text-muted" style="font-size: 4rem;"></i>
        <h3 class="mt-3 text-muted">Нет статей</h3>
        <p class="text-muted">Добавьте первую статью, чтобы она появилась здесь.</p>
        <a href="{{ route('admin.articles.create', $currentUserId) }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Добавить статью
        </a>
    </div>
@endif

<!-- Модальное окно подтверждения удаления -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Подтверждение удаления</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Вы уверены, что хотите удалить эту статью? Это действие нельзя отменить.
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
function deleteArticle(articleId) {
    const form = document.getElementById('deleteForm');
    form.action = "{{ route('admin.articles.destroy', [$currentUserId, ':id']) }}".replace(':id', articleId);
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endsection
