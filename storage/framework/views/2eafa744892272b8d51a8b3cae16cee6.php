

<?php $__env->startSection('title', 'Создание статьи - ' . config('app.name')); ?>
<?php $__env->startSection('description', 'Создание новой статьи для блога'); ?>


<style>
/* Стили для редактируемых элементов */
.editable-title, .editable-excerpt, .editable-content {
    cursor: text;
    border: 2px dashed transparent;
    border-radius: 4px;
    padding: 8px;
    margin: -8px;
    transition: all 0.2s ease;
    min-height: 1.5rem;
}

.editable-title:hover, .editable-excerpt:hover, .editable-content:hover {
    border-color: #007bff;
    background-color: rgba(0, 123, 255, 0.05);
}

.editable-title:focus, .editable-excerpt:focus, .editable-content:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    background-color: rgba(0, 123, 255, 0.05);
}

/* Убираем стандартные стили contenteditable */
.editable-title[contenteditable="true"]:empty:before,
.editable-excerpt[contenteditable="true"]:empty:before,
.editable-content[contenteditable="true"]:empty:before {
    content: attr(placeholder);
    color: #6c757d;
    font-style: italic;
}

/* Стили для изображения */
.article-image {
    position: relative;
    cursor: pointer;
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.2s ease;
}

.article-image:hover {
    border-color: #007bff;
}

.article-image img {
    width: 100%;
    height: auto;
    display: block;
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.2s ease;
}

.article-image:hover .image-overlay {
    opacity: 1;
}

.image-overlay i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.no-image {
    min-height: 200px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa;
    color: #6c757d;
}

/* Фиксированная панель действий */
.action-panel {
    position: relative;
 
    z-index: 1000;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    padding: 15px;
}

/* Стили для автора */
.author-section {
    display: flex;
    align-items: center;
    gap: 10px;
}

.author-avatar img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
}

.author-name {
    font-weight: 600;
}

.article-date {
    font-size: 0.875rem;
    color: #6c757d;
}

.read-time {
    margin-left: 10px;
}

/* Стили для мета-информации */
.article-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.article-actions {
    display: flex;
    gap: 10px;
}

/* Адаптация для мобильных */
@media (max-width: 768px) {
   
    
    .article-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
}
</style>


<?php $__env->startSection('content'); ?>
    <main class="" role="main">
        <!-- Скрытая форма для отправки данных -->
        <form id="article-form" action="<?php echo e(route('admin.articles.store', $currentUserId)); ?>" method="POST" enctype="multipart/form-data" style="display: none;">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="title" id="hidden-title">
            <input type="hidden" name="excerpt" id="hidden-excerpt">
            <input type="hidden" name="content" id="hidden-content">
            <input type="hidden" name="read_time" id="hidden-read-time" value="1">
            <input type="hidden" name="is_published" id="hidden-is-published" value="0">
            <input type="file" name="image" id="hidden-image" accept="image/*">
        </form>

        <!-- Основной контент -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                  
                    
                    <article class="article-wrapper" itemscope itemtype="https://schema.org/Article">
                        <!-- Заголовок и мета-информация -->
                        <header class="article-header">
                            <h1 class="article-title editable-title" 
                                contenteditable="true" 
                                placeholder="Введите заголовок статьи..."
                                data-max-length="150"
                                onclick="selectText(this)">Новая статья</h1>

                            <div class="article-meta">
                                <div class="author-section">
                                    <div class="author-avatar">
                                        <?php if($user->avatar): ?>
                                            <img src="<?php echo e(asset('storage/' . $user->avatar)); ?>" alt="Аватар <?php echo e($user->name); ?>" class="rounded-circle">
                                        <?php else: ?>
                                            <i class="bi bi-person-circle"></i>
                                        <?php endif; ?>
                                    </div>
                                   
                                </div>
                                <div class="article-actions">
                                    <button type="button" class="btn  btn-sm" onclick="selectImage()">
                                        <i class="bi bi-image me-1"></i>Добавить изображение
                                    </button>
                                </div>
                            </div>
                        </header>

                        <!-- Изображение статьи -->
                        <div class="article-image mb-4" onclick="selectImage()" id="article-image-container">
                            <div class="no-image">
                                <i class="bi bi-image" style="font-size: 3rem;"></i>
                                <p class="mt-2 mb-0">Нажмите, чтобы добавить изображение</p>
                            </div>
                            <div class="image-overlay">
                                <i class="bi bi-camera-fill"></i>
                                <span>Изменить изображение</span>
                            </div>
                        </div>

                        <!-- Краткое описание -->
                        <div class="mb-4">
                            <p class="lead editable-excerpt" 
                               contenteditable="true" 
                               placeholder="Введите краткое описание статьи..."
                               data-max-length="300"
                               onclick="selectText(this)">Краткое описание статьи. Нажмите, чтобы редактировать.</p>
                        </div>

                        <!-- Содержание статьи -->
                        <div class="article-content editable-content" 
                             contenteditable="true" 
                             placeholder="Введите содержание статьи..."
                             onclick="selectText(this)">
                            <p>Содержание статьи. Нажмите, чтобы начать писать...</p>
                        </div>

                        <!-- Мета-информация в конце -->
                        <footer class="article-footer mt-5">
                          
                                <div class="col-md-6">
                                    <div class="article-tags">
                                        <small class="text-muted">
                                            Статус: <span class="badge bg-warning">Черновик</span>
                                        </small>
                                         <small class="text-muted">
                                        Автор: <?php echo e($user->name); ?>

                                    </small>
                                    </div>
                                </div>
                               
                            
                        </footer>
                    </article>
                </div>
            </div>
        </div>
    </main>

    <!-- Фиксированная панель действий -->
    <div class="action-panel">
        <div class="d-grid gap-2">
            <button type="button" class="btn " onclick="saveArticle(true)">
                <i class="bi bi-cloud-upload me-2"></i>
                Опубликовать
            </button>
            <button type="button" class="btn " onclick="saveArticle(false)">
                <i class="bi bi-save me-2"></i>
                Сохранить как черновик
            </button>
            <a href="<?php echo e(route('admin.articles', $currentUserId)); ?>" class="btn btn-outline-secondary btn-sm">
                Отмена
            </a>
        </div>
    </div>

    <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
    <script>
        // Глобальные переменные
        let selectedImageFile = null;

        // Состояние формы
        const formState = {
            title: 'Новая статья',
            excerpt: 'Краткое описание статьи. Нажмите, чтобы редактировать.',
            content: '<p>Содержание статьи. Нажмите, чтобы начать писать...</p>',
            isPublished: false,
            readTime: 1
        };

        // Инициализация при загрузке страницы
        document.addEventListener('DOMContentLoaded', function() {
            bindEvents();
            updateHiddenFields();
        });

        // Привязка событий
        function bindEvents() {
            // Обработка редактируемых элементов
            const editableTitle = document.querySelector('.editable-title');
            const editableExcerpt = document.querySelector('.editable-excerpt');
            const editableContent = document.querySelector('.editable-content');

            // События для заголовка
            editableTitle.addEventListener('input', function() {
                formState.title = this.textContent.trim() || 'Новая статья';
                updateHiddenFields();
                updateReadTime();
            });

            // События для описания
            editableExcerpt.addEventListener('input', function() {
                formState.excerpt = this.textContent.trim() || '';
                updateHiddenFields();
                updateReadTime();
            });

            // События для содержания
            editableContent.addEventListener('input', function() {
                formState.content = this.innerHTML.trim() || '';
                updateHiddenFields();
                updateReadTime();
            });

            // Ограничения по длине
            editableTitle.addEventListener('keydown', function(e) {
                if (this.textContent.length >= 150 && e.keyCode !== 8 && e.keyCode !== 46) {
                    e.preventDefault();
                }
            });

            editableExcerpt.addEventListener('keydown', function(e) {
                if (this.textContent.length >= 300 && e.keyCode !== 8 && e.keyCode !== 46) {
                    e.preventDefault();
                }
            });
        }

        // Выбор текста при клике
        function selectText(element) {
            const range = document.createRange();
            range.selectNodeContents(element);
            const selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(range);
        }

        // Выбор изображения
        function selectImage() {
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/*';
            input.onchange = handleImageSelect;
            input.click();
        }

        // Обработка выбора изображения
        function handleImageSelect(event) {
            const file = event.target.files[0];
            if (file) {
                selectedImageFile = file;
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const container = document.getElementById('article-image-container');
                    container.innerHTML = `
                        <img src="${e.target.result}" alt="Изображение статьи" class="img-fluid rounded">
                        <div class="image-overlay">
                            <i class="bi bi-camera-fill"></i>
                            <span>Изменить изображение</span>
                        </div>
                    `;
                };
                reader.readAsDataURL(file);
                
                // Обновляем скрытое поле
                const hiddenImage = document.getElementById('hidden-image');
                const dt = new DataTransfer();
                dt.items.add(file);
                hiddenImage.files = dt.files;
            }
        }

        // Обновление времени чтения
        function updateReadTime() {
            const content = formState.content.replace(/<[^>]*>/g, '');
            const wordCount = content.split(/\s+/).filter(word => word.length > 0).length;
            const readTime = Math.max(1, Math.ceil(wordCount / 200)); // 200 слов в минуту
            
            formState.readTime = readTime;
            
            // Обновляем отображение времени чтения
            document.querySelector('.read-time').textContent = `${readTime} мин чтения`;
            
            updateHiddenFields();
        }

        // Обновление скрытых полей формы
        function updateHiddenFields() {
            document.getElementById('hidden-title').value = formState.title;
            document.getElementById('hidden-excerpt').value = formState.excerpt;
            document.getElementById('hidden-content').value = formState.content;
            document.getElementById('hidden-read-time').value = formState.readTime;
            document.getElementById('hidden-is-published').value = formState.isPublished ? '1' : '0';
        }

        // Сохранение статьи
        function saveArticle(publish = false) {
            // Проверяем обязательные поля
            if (!formState.title.trim() || formState.title === 'Новая статья') {
                alert('Пожалуйста, введите заголовок статьи');
                return;
            }

            if (!formState.excerpt.trim() || formState.excerpt === 'Краткое описание статьи. Нажмите, чтобы редактировать.') {
                alert('Пожалуйста, введите краткое описание статьи');
                return;
            }

            if (!formState.content.trim() || formState.content === '<p>Содержание статьи. Нажмите, чтобы начать писать...</p>') {
                alert('Пожалуйста, введите содержание статьи');
                return;
            }

            // Устанавливаем статус публикации
            formState.isPublished = publish;
            
            // Обновляем скрытые поля
            updateHiddenFields();
            
            // Отправляем форму
            document.getElementById('article-form').submit();
        }

        // Обновление статуса при сохранении
        document.addEventListener('beforeunload', function() {
            updateHiddenFields();
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\link\resources\views\admin\articles\create.blade.php ENDPATH**/ ?>