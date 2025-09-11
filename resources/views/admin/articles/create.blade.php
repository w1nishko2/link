@extends('admin.layout')

@section('title', 'Создание статьи')
@section('description', 'Создание новой статьи для блога')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Создание статьи</h1>
    <a href="{{ route('admin.articles', $currentUserId) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Назад к статьям
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.articles.store', $currentUserId) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Заголовок статьи *</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" required maxlength="150">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Максимум 150 символов. Осталось: <span id="title-counter">150</span></div>
                    </div>

                    <div class="mb-3">
                        <label for="excerpt" class="form-label">Краткое описание *</label>
                        <textarea class="form-control @error('excerpt') is-invalid @enderror" 
                                  id="excerpt" name="excerpt" rows="3" required maxlength="300">{{ old('excerpt') }}</textarea>
                        @error('excerpt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Краткое описание статьи для превью. Максимум 300 символов. Осталось: <span id="excerpt-counter">300</span></div>
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Содержание статьи *</label>
                        <div id="editor" style="min-height: 300px;"></div>
                        <textarea class="form-control @error('content') is-invalid @enderror d-none" 
                                  id="content" name="content" required>{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Изображение статьи</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Поддерживаются изображения в любых форматах. Максимальный размер: 10MB. Все изображения автоматически конвертируются в WebP с оптимизацией размера.</div>
                    </div>

                    <div class="mb-3">
                        <label for="order_index" class="form-label">Порядок отображения</label>
                        <input type="number" class="form-control @error('order_index') is-invalid @enderror" 
                               id="order_index" name="order_index" value="{{ old('order_index', 0) }}" min="0">
                        @error('order_index')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Статьи с меньшим значением отображаются первыми</div>
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="is_published" name="is_published" value="1" 
                               {{ old('is_published', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_published">
                            Опубликованная статья
                        </label>
                        <div class="form-text">Неопубликованные статьи не отображаются на странице</div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Создать статью
                        </button>
                        <a href="{{ route('admin.articles', $currentUserId) }}" class="btn btn-outline-secondary">
                            Отмена
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Предварительный просмотр</h6>
            </div>
            <div class="card-body">
                <div id="preview">
                    <h6 class="preview-title text-muted">Заголовок статьи</h6>
                    <p class="preview-excerpt text-muted">Краткое описание...</p>
                    <div class="preview-image bg-light d-flex align-items-center justify-content-center" style="height: 120px; border-radius: 4px;">
                        <i class="bi bi-image text-muted"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Счетчики символов
    function setupCharCounter(inputId, counterId, maxLength) {
        const input = document.getElementById(inputId);
        const counter = document.getElementById(counterId);
        
        function updateCounter() {
            const currentLength = input.value.length;
            const remaining = maxLength - currentLength;
            counter.textContent = remaining;
            
            if (remaining < 0) {
                counter.style.color = '#dc3545';
            } else if (remaining < 20) {
                counter.style.color = '#fd7e14';
            } else {
                counter.style.color = '#6c757d';
            }
        }
        
        updateCounter();
        input.addEventListener('input', updateCounter);
        input.addEventListener('keydown', updateCounter);
        input.addEventListener('paste', function() {
            setTimeout(updateCounter, 10);
        });
    }

    setupCharCounter('title', 'title-counter', 150);
    setupCharCounter('excerpt', 'excerpt-counter', 300);

    // Инициализация CKEditor
    let editor;
    
    ClassicEditor
        .create(document.querySelector('#editor'), {
            toolbar: {
                items: [
                    'heading',
                    '|',
                    'bold',
                    'italic',
                    'underline',
                    'strikethrough',
                    '|',
                    'fontSize',
                    'fontColor',
                    'fontBackgroundColor',
                    '|',
                    'alignment',
                    '|',
                    'numberedList',
                    'bulletedList',
                    '|',
                    'outdent',
                    'indent',
                    '|',
                    'link',
                    'insertTable',
                    'imageInsert',
                    'mediaEmbed',
                    '|',
                    'blockQuote',
                    'codeBlock',
                    'horizontalLine',
                    '|',
                    'undo',
                    'redo'
                ]
            },
            language: 'ru',
            image: {
                toolbar: [
                    'imageTextAlternative',
                    'imageStyle:inline',
                    'imageStyle:block',
                    'imageStyle:side'
                ]
            },
            table: {
                contentToolbar: [
                    'tableColumn',
                    'tableRow',
                    'mergeTableCells'
                ]
            },
            licenseKey: '',
        })
        .then(newEditor => {
            editor = newEditor;
            
            // Устанавливаем начальное содержимое
            const initialContent = document.getElementById('content').value;
            if (initialContent) {
                editor.setData(initialContent);
            }
            
            // Синхронизируем данные с textarea
            editor.model.document.on('change:data', () => {
                document.getElementById('content').value = editor.getData();
            });
        })
        .catch(error => {
            console.error('Ошибка инициализации редактора:', error);
        });

    // Обновляем содержимое перед отправкой формы
    document.querySelector('form').addEventListener('submit', function() {
        if (editor) {
            document.getElementById('content').value = editor.getData();
        }
    });

    const titleInput = document.getElementById('title');
    const excerptInput = document.getElementById('excerpt');
    const imageInput = document.getElementById('image');
    
    const previewTitle = document.querySelector('.preview-title');
    const previewExcerpt = document.querySelector('.preview-excerpt');
    const previewImage = document.querySelector('.preview-image');

    titleInput.addEventListener('input', function() {
        previewTitle.textContent = this.value || 'Заголовок статьи';
    });

    excerptInput.addEventListener('input', function() {
        previewExcerpt.textContent = this.value || 'Краткое описание...';
    });

    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.innerHTML = `<img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px;" alt="Preview">`;
            };
            reader.readAsDataURL(file);
        } else {
            previewImage.innerHTML = '<i class="bi bi-image text-muted"></i>';
        }
    });
});
</script>
@endsection
