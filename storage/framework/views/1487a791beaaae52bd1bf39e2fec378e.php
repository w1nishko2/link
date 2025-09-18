

<?php $__env->startSection('title', 'Редактирование статьи - ' . config('app.name')); ?>
<?php $__env->startSection('description', 'Редактирование содержания и настроек статьи'); ?>

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
    color: #6c757d;
}

/* Фиксированная панель действий */
.action-panel {
    position: relative;
   
    transform: translateY(-50%);
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    padding: 20px;
    z-index: 1000;
}

/* Стили для автора */
.author-section {
    display: flex;
    align-items: center;
    gap: 12px;
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
    color: #6c757d;
    font-size: 0.9rem;
}

.read-time {
    color: #6c757d;
}

/* Стили для мета-информации */
.article-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 20px 0;
}

.article-actions {
    display: flex;
    gap: 10px;
}

/* Адаптация для мобильных */
@media (max-width: 768px) {
   
}

/* Стили для удаления изображения */
.remove-image-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(220, 53, 69, 0.9);
    color: white;
    border: none;
    border-radius: 50%;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    opacity: 0;
    transition: all 0.2s ease;
}

.article-image:hover .remove-image-btn {
    opacity: 1;
}

.remove-image-btn:hover {
    background: rgba(220, 53, 69, 1);
    transform: scale(1.1);
}
</style>

<?php $__env->startSection('content'); ?>
    <main class="" role="main">
        <!-- Скрытая форма для отправки данных -->
        <form id="article-form" action="<?php echo e(route('admin.articles.update', [$currentUserId, $article])); ?>" method="POST" enctype="multipart/form-data" style="display: none;">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <input type="hidden" name="title" id="hidden-title">
            <input type="hidden" name="excerpt" id="hidden-excerpt">
            <input type="hidden" name="content" id="hidden-content">
            <input type="hidden" name="read_time" id="hidden-read-time" value="<?php echo e($article->read_time); ?>">
            <input type="hidden" name="is_published" id="hidden-is-published" value="<?php echo e($article->is_published ? '1' : '0'); ?>">
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
                                onclick="selectText(this)"><?php echo e($article->title); ?></h1>

                            <div class="article-meta">
                                <div class="author-section">
                                    <div class="author-avatar">
                                        <?php if($article->user->avatar): ?>
                                            <img src="<?php echo e(asset('storage/' . $article->user->avatar)); ?>" alt="Аватар <?php echo e($article->user->name); ?>" class="rounded-circle">
                                        <?php else: ?>
                                            <i class="bi bi-person-circle"></i>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="article-actions">
                                    <button type="button" class="btn btn-sm" onclick="selectImage()">
                                        <i class="bi bi-image me-1"></i><?php echo e($article->image_path ? 'Изменить изображение' : 'Добавить изображение'); ?>

                                    </button>
                                    <?php if($article->image_path): ?>
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeImage()">
                                            <i class="bi bi-trash me-1"></i>Удалить изображение
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </header>

                        <!-- Изображение статьи -->
                        <div class="article-image mb-4" onclick="selectImage()" id="article-image-container">
                            <?php if($article->image_path): ?>
                                <img src="<?php echo e(asset('storage/' . $article->image_path)); ?>" alt="<?php echo e($article->title); ?>" class="img-fluid rounded">
                                <div class="image-overlay">
                                    <i class="bi bi-camera-fill"></i>
                                    <span>Изменить изображение</span>
                                </div>
                                <button type="button" class="remove-image-btn" onclick="removeImage(event)">
                                    <i class="bi bi-x"></i>
                                </button>
                            <?php else: ?>
                                <div class="no-image">
                                    <i class="bi bi-image" style="font-size: 3rem;"></i>
                                    <p class="mt-2 mb-0">Нажмите, чтобы добавить изображение</p>
                                </div>
                                <div class="image-overlay">
                                    <i class="bi bi-camera-fill"></i>
                                    <span>Добавить изображение</span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Краткое описание -->
                        <div class="mb-4">
                            <p class="lead editable-excerpt" 
                               contenteditable="true" 
                               placeholder="Введите краткое описание статьи..."
                               data-max-length="300"
                               onclick="selectText(this)"><?php echo e($article->excerpt); ?></p>
                        </div>

                        <!-- Содержание статьи -->
                        <div class="article-content editable-content" 
                             contenteditable="true" 
                             placeholder="Введите содержание статьи..."
                             onclick="selectText(this)">
                            <?php echo $article->content; ?>

                        </div>

                        <!-- Мета-информация в конце -->
                        <footer class="article-footer mt-5">
                            <div class="col-md-6">
                                <div class="article-tags">
                                    <small class="text-muted">
                                        Статус: <span class="badge <?php echo e($article->is_published ? 'bg-success' : 'bg-warning'); ?>"><?php echo e($article->is_published ? 'Опубликовано' : 'Черновик'); ?></span>
                                    </small>
                                    <small class="text-muted">
                                        Автор: <?php echo e($article->user->name); ?>

                                    </small>
                                    <small class="text-muted read-time">
                                        <?php echo e($article->read_time); ?> мин чтения
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
            <button type="button" class="btn btn-success" onclick="saveArticle(true)">
                <i class="bi bi-cloud-upload me-2"></i>
                <?php echo e($article->is_published ? 'Обновить' : 'Опубликовать'); ?>

            </button>
            <button type="button" class="btn btn-primary" onclick="saveArticle(false)">
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
        let currentImagePath = '<?php echo e($article->image_path); ?>';

        // Состояние формы
        const formState = {
            title: '<?php echo e(addslashes($article->title)); ?>',
            excerpt: '<?php echo e(addslashes($article->excerpt)); ?>',
            content: `<?php echo addslashes($article->content); ?>`,
            isPublished: <?php echo e($article->is_published ? 'true' : 'false'); ?>,
            readTime: <?php echo e($article->read_time); ?>

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
                formState.title = this.textContent.trim() || '';
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
                        <button type="button" class="remove-image-btn" onclick="removeImage(event)">
                            <i class="bi bi-x"></i>
                        </button>
                    `;
                    
                    // Обновляем кнопки действий
                    updateImageButtons(true);
                };
                reader.readAsDataURL(file);
                
                // Обновляем скрытое поле
                const hiddenImage = document.getElementById('hidden-image');
                const dt = new DataTransfer();
                dt.items.add(file);
                hiddenImage.files = dt.files;
            }
        }

        // Удаление изображения
        function removeImage(event) {
            if (event) {
                event.stopPropagation();
            }
            
            const container = document.getElementById('article-image-container');
            container.innerHTML = `
                <div class="no-image">
                    <i class="bi bi-image" style="font-size: 3rem;"></i>
                    <p class="mt-2 mb-0">Нажмите, чтобы добавить изображение</p>
                </div>
                <div class="image-overlay">
                    <i class="bi bi-camera-fill"></i>
                    <span>Добавить изображение</span>
                </div>
            `;
            
            // Сбрасываем выбранный файл
            selectedImageFile = null;
            currentImagePath = '';
            
            // Очищаем скрытое поле
            const hiddenImage = document.getElementById('hidden-image');
            hiddenImage.value = '';
            
            // Обновляем кнопки действий
            updateImageButtons(false);
        }

        // Обновление кнопок действий с изображениями
        function updateImageButtons(hasImage) {
            const actionsContainer = document.querySelector('.article-actions');
            if (hasImage) {
                actionsContainer.innerHTML = `
                    <button type="button" class="btn btn-sm" onclick="selectImage()">
                        <i class="bi bi-image me-1"></i>Изменить изображение
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeImage()">
                        <i class="bi bi-trash me-1"></i>Удалить изображение
                    </button>
                `;
            } else {
                actionsContainer.innerHTML = `
                    <button type="button" class="btn btn-sm" onclick="selectImage()">
                        <i class="bi bi-image me-1"></i>Добавить изображение
                    </button>
                `;
            }
        }

        // Обновление времени чтения
        function updateReadTime() {
            const content = formState.content.replace(/<[^>]*>/g, '');
            const wordCount = content.split(/\s+/).filter(word => word.length > 0).length;
            const readTime = Math.max(1, Math.ceil(wordCount / 200)); // 200 слов в минуту
            
            formState.readTime = readTime;
            
            // Обновляем отображение времени чтения
            const readTimeElement = document.querySelector('.read-time');
            if (readTimeElement) {
                readTimeElement.textContent = `${readTime} мин чтения`;
            }
            
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
            if (!formState.title.trim()) {
                alert('Пожалуйста, введите заголовок статьи');
                return;
            }

            if (!formState.excerpt.trim()) {
                alert('Пожалуйста, введите краткое описание статьи');
                return;
            }

            if (!formState.content.trim()) {
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
<?php echo $__env->make('admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\link\resources\views\admin\articles\edit.blade.php ENDPATH**/ ?>