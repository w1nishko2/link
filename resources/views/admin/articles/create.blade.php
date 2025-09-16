@extends('admin.layout')

@section('title', 'Создание статьи')
@section('description', 'Создание новой статьи для блога')


@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
   
    <a href="{{ route('admin.articles', $currentUserId) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Назад к статьям
    </a>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.articles.store', $currentUserId) }}" method="POST" enctype="multipart/form-data">
                    @csrf
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
                                    <div class="image-preview-box" id="imagePreviewBox">
                                        <input type="file" class="image-upload-input @error('image') is-invalid @enderror" 
                                               id="image" name="image" accept="image/*">
                                        
                                        <div class="image-preview-content empty" id="imagePreviewContent">
                                            <div class="image-placeholder">
                                                <i class="bi bi-image"></i>
                                            </div>
                                            <div class="upload-text">
                                                <strong>Нажмите для выбора изображения</strong><br>
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
                                               id="title" name="title" value="{{ old('title') }}" required maxlength="150">
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Максимум 150 символов. Осталось: <span id="title-counter">150</span></div>
                                    </div>

                                    <div class="mb-0">
                                        <label for="excerpt" class="form-label">Краткое описание *</label>
                                        <textarea class="form-control @error('excerpt') is-invalid @enderror" 
                                                  id="excerpt" name="excerpt" rows="5" required maxlength="300">{{ old('excerpt') }}</textarea>
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
                           
                                <div id="editor" style="min-height: 300px;"></div>
                                <textarea class="form-control @error('content') is-invalid @enderror d-none" 
                                          id="content" name="content" required>{{ old('content') }}</textarea>
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
                                               id="order_index" name="order_index" value="{{ old('order_index', 0) }}" min="0">
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
                                                   {{ old('is_published', true) ? 'checked' : '' }}>
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
                // Показываем ошибку более элегантно
                imagePreviewBox.style.borderColor = '#ef4444';
                imagePreviewBox.style.background = 'linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%)';
                
                setTimeout(() => {
                    imagePreviewBox.style.borderColor = '';
                    imagePreviewBox.style.background = '';
                    alert('Пожалуйста, выберите файл изображения');
                }, 300);
                
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
    imagePreviewContent.innerHTML = `
        <div class="image-placeholder">
            <i class="bi bi-image"></i>
        </div>
        <div class="upload-text">
            <strong>Нажмите для выбора изображения</strong><br>
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
