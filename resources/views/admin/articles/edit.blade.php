@extends('admin.layout')

@section('title', 'Редактирование статьи')
@section('description', 'Редактирование содержания и настроек статьи')

@push('head')
<style>
.article-info-flex {
    display: flex;
    gap: 25px;
    align-items: flex-start;
}

.image-zone {
    flex: 0 0 320px;
    min-height: 200px;
}

.image-zone .form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.image-zone .form-label::before {
    content: "📸";
    font-size: 16px;
}

.text-fields-zone {
    flex: 1;
    min-width: 0;
}

.image-preview-box {
    border: 3px dashed #cbd5e0;
    border-radius: 16px;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    padding: 24px;
    text-align: center;
    min-height: 220px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    cursor: pointer;
    overflow: hidden;
}

.image-preview-box::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, transparent 30%, rgba(59, 130, 246, 0.05) 50%, transparent 70%);
    transform: translateX(-100%);
    transition: transform 0.6s ease;
}

.image-preview-box:hover::before {
    transform: translateX(100%);
}

.image-preview-box:hover {
    border-color: #3b82f6;
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(59, 130, 246, 0.15);
}

.image-preview-box.has-image {
    border-color: #10b981;
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    padding: 12px;
    border-style: solid;
}

.image-preview-box.has-image:hover {
    border-color: #059669;
    background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
    box-shadow: 0 10px 25px rgba(16, 185, 129, 0.15);
}

.image-upload-input {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
    z-index: 2;
}

.image-preview-content {
    pointer-events: none;
    z-index: 1;
}

.image-preview-content.empty {
    color: #64748b;
}

.preview-image {
    max-width: 100%;
    max-height: 190px;
    object-fit: cover;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    transition: transform 0.3s ease;
}

.image-preview-box.has-image .preview-image:hover {
    transform: scale(1.02);
}

.image-placeholder {
    font-size: 64px;
    color: #94a3b8;
    margin-bottom: 16px;
    transition: all 0.3s ease;
}

.image-preview-box:hover .image-placeholder {
    color: #3b82f6;
    transform: scale(1.1);
}

.remove-image {
    position: absolute;
    top: 12px;
    right: 12px;
    z-index: 3;
    opacity: 0;
    transition: all 0.3s ease;
    border-radius: 50%;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(239, 68, 68, 0.9);
    border: none;
    backdrop-filter: blur(8px);
}

.image-preview-box.has-image:hover .remove-image {
    opacity: 1;
    transform: scale(1.1);
}

.remove-image:hover {
    background: rgba(220, 38, 38, 1);
    transform: scale(1.2) !important;
}

.upload-text {
    font-size: 15px;
    color: #64748b;
    margin-top: 12px;
    line-height: 1.5;
    transition: color 0.3s ease;
}

.image-preview-box:hover .upload-text {
    color: #3b82f6;
}

.upload-text strong {
    color: #1e293b;
    font-weight: 600;
    display: block;
    margin-bottom: 4px;
    font-size: 16px;
}

.image-preview-box:hover .upload-text strong {
    color: #1d4ed8;
}

.upload-text small {
    font-size: 13px;
    color: #94a3b8;
    display: block;
    margin-top: 4px;
}

.current-image {
    position: relative;
    display: inline-block;
    margin-bottom: 15px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    transition: transform 0.3s ease;
}

.current-image:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.18);
}

.current-image img {
    max-width: 100%;
    max-height: 190px;
    object-fit: cover;
    display: block;
}

.current-image .badge {
    position: absolute;
    top: 8px;
    left: 8px;
    background: rgba(16, 185, 129, 0.9);
    backdrop-filter: blur(8px);
    border: none;
    font-size: 12px;
    padding: 4px 8px;
}

.card-header h6 {
    color: #495057;
    font-weight: 600;
}

.card-header h6 i {
    margin-right: 8px;
    color: #6c757d;
}

.form-check-input:checked {
    background-color: #198754;
    border-color: #198754;
}

.form-check-label i {
    margin-right: 5px;
}

/* Анимация загрузки */
.image-preview-box.uploading {
    border-color: #f59e0b;
    background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
}

.image-preview-box.uploading::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #f59e0b, #d97706);
    animation: loading 2s infinite;
}

@keyframes loading {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

/* Мобильная адаптация */
@media (max-width: 768px) {
    .article-info-flex {
        flex-direction: column;
        gap: 20px;
    }
    
    .image-zone {
        flex: none;
        width: 100%;
    }
    
    .image-preview-box {
        min-height: 180px;
        padding: 20px;
    }
    
    .image-placeholder {
        font-size: 48px;
    }
}
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Редактирование статьи</h1>
    <a href="{{ route('admin.articles', $currentUserId) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Назад к статьям
    </a>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.articles.update', [$currentUserId, $article]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Блок основной информации -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="bi bi-pencil-square"></i> Основная информация</h6>
                        </div>
                        <div class="card-body">
                            <div class="article-info-flex">
                                <!-- Зона изображения -->
                                <div class="image-zone">
                                    <label for="image" class="form-label">Изображение статьи</label>
                                    
                                    @if($article->image_path)
                                        <div class="current-image mb-3">
                                            <span class="badge bg-success">Текущее изображение</span>
                                            <img src="{{ asset('storage/' . $article->image_path) }}" alt="Текущее изображение">
                                        </div>
                                    @endif
                                    
                                    <div class="image-preview-box" id="imagePreviewBox">
                                        <input type="file" class="image-upload-input @error('image') is-invalid @enderror" 
                                               id="image" name="image" accept="image/*">
                                        
                                        <div class="image-preview-content empty" id="imagePreviewContent">
                                            <div class="image-placeholder">
                                                <i class="bi bi-image"></i>
                                            </div>
                                            <div class="upload-text">
                                                <strong>
                                                    @if($article->image_path)
                                                        Нажмите для замены изображения
                                                    @else
                                                        Нажмите для выбора изображения
                                                    @endif
                                                </strong><br>
                                                <small>Поддерживаются все форматы изображений<br>
                                                Максимальный размер: 10MB</small>
                                            </div>
                                        </div>
                                        
                                        <button type="button" class="btn btn-sm btn-danger remove-image" 
                                                id="removeImageBtn" onclick="removeImage()" style="display: none;">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    @error('image')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Зона текстовых полей -->
                                <div class="text-fields-zone">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Заголовок статьи *</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                               id="title" name="title" value="{{ old('title', $article->title) }}" required maxlength="150">
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Максимум 150 символов. Осталось: <span id="title-counter">150</span></div>
                                    </div>

                                    <div class="mb-0">
                                        <label for="excerpt" class="form-label">Краткое описание *</label>
                                        <textarea class="form-control @error('excerpt') is-invalid @enderror" 
                                                  id="excerpt" name="excerpt" rows="5" required maxlength="300">{{ old('excerpt', $article->excerpt) }}</textarea>
                                        @error('excerpt')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Краткое описание статьи для превью. Максимум 300 символов. Осталось: <span id="excerpt-counter">300</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Блок содержания -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="bi bi-text-paragraph"></i> Содержание статьи</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-0">
                                <label for="content" class="form-label">Содержание статьи *</label>
                                <div id="editor" style="min-height: 300px;"></div>
                                <textarea class="form-control @error('content') is-invalid @enderror d-none" 
                                          id="content" name="content" required>{{ old('content', $article->content) }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Блок настроек -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="bi bi-gear"></i> Настройки публикации</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="order_index" class="form-label">Порядок отображения</label>
                                        <input type="number" class="form-control @error('order_index') is-invalid @enderror" 
                                               id="order_index" name="order_index" value="{{ old('order_index', $article->order_index) }}" min="0">
                                        @error('order_index')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Статьи с меньшим значением отображаются первыми</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Статус публикации</label>
                                        <div class="form-check form-switch">
                                            <input type="checkbox" class="form-check-input" id="is_published" name="is_published" value="1" 
                                                   {{ old('is_published', $article->is_published) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_published">
                                                <i class="bi bi-eye"></i> Опубликованная статья
                                            </label>
                                        </div>
                                        <div class="form-text">Неопубликованные статьи не отображаются на странице</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Сохранить изменения
                        </button>
                        <a href="{{ route('admin.articles', $currentUserId) }}" class="btn btn-outline-secondary">
                            Отмена
                        </a>
                    </div>
                </form>
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

    // Функциональность предпросмотра изображения
    const imageInput = document.getElementById('image');
    const imagePreviewBox = document.getElementById('imagePreviewBox');
    const imagePreviewContent = document.getElementById('imagePreviewContent');
    const removeImageBtn = document.getElementById('removeImageBtn');

    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            if (file.type.startsWith('image/')) {
                // Показываем состояние загрузки
                imagePreviewContent.innerHTML = `
                    <div class="loading-container">
                        <div class="loading-spinner"></div>
                        <div class="loading-text">Загрузка изображения...</div>
                    </div>
                `;
                imagePreviewContent.classList.remove('empty');
                imagePreviewBox.classList.add('loading');
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Небольшая задержка для демонстрации анимации загрузки
                    setTimeout(() => {
                        // Показываем изображение
                        imagePreviewContent.innerHTML = `<img src="${e.target.result}" alt="Предпросмотр" class="preview-image">`;
                        imagePreviewBox.classList.remove('loading');
                        imagePreviewBox.classList.add('has-image');
                        removeImageBtn.style.display = 'block';
                    }, 500);
                };
                reader.readAsDataURL(file);
            } else {
                alert('Пожалуйста, выберите файл изображения');
                imageInput.value = '';
            }
        }
    });
});

// Функция для удаления изображения
function removeImage() {
    const imageInput = document.getElementById('image');
    const imagePreviewBox = document.getElementById('imagePreviewBox');
    const imagePreviewContent = document.getElementById('imagePreviewContent');
    const removeImageBtn = document.getElementById('removeImageBtn');
    
    // Сбрасываем input
    imageInput.value = '';
    
    // Возвращаем исходное состояние
    const hasCurrentImage = document.querySelector('.current-image');
    const buttonText = hasCurrentImage ? 'Нажмите для замены изображения' : 'Нажмите для выбора изображения';
    
    imagePreviewContent.innerHTML = `
        <div class="image-placeholder">
            <i class="bi bi-image"></i>
        </div>
        <div class="upload-text">
            <strong>${buttonText}</strong><br>
            <small>Поддерживаются все форматы изображений<br>
            Максимальный размер: 10MB</small>
        </div>
    `;
    imagePreviewContent.classList.add('empty');
    imagePreviewBox.classList.remove('has-image');
    removeImageBtn.style.display = 'none';
}
</script>
@endsection
