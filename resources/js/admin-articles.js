/**
 * JavaScript для администрирования статей
 * Используется на страницах создания и редактирования статей
 */

// Глобальные переменные
let selectedArticleImage = null;
let articleEditor = null;

// Состояние формы статьи
let articleFormState = {
    title: '',
    excerpt: '',
    content: '',
    isPublished: false,
    readTime: 1
};

/**
 * Инициализация страницы статей
 * @param {Object} initialState - Начальное состояние формы
 */
function initArticlePage(initialState = {}) {
    console.log('Инициализация страницы статей...');
    
    // Устанавливаем начальное состояние
    Object.assign(articleFormState, initialState);
    
    // Инициализируем обработчики событий
    initArticleEventListeners();
    
    // Обновляем скрытые поля
    updateArticleHiddenFields();
    
    console.log('Страница статей инициализирована');
}

/**
 * Инициализация обработчиков событий для статей
 */
function initArticleEventListeners() {
    console.log('Инициализация обработчиков событий...');
    
    // Обработчики для редактируемых элементов
    const editableTitle = document.querySelector('.editable-title');
    const editableExcerpt = document.querySelector('.editable-excerpt');
    const editableContent = document.querySelector('.editable-content');

    if (editableTitle) {
        editableTitle.addEventListener('input', handleArticleTitleChange);
        editableTitle.addEventListener('keydown', limitTitleLength);
        editableTitle.addEventListener('paste', handleContentPaste);
    }

    if (editableExcerpt) {
        editableExcerpt.addEventListener('input', handleArticleExcerptChange);
        editableExcerpt.addEventListener('keydown', limitExcerptLength);
        editableExcerpt.addEventListener('paste', handleContentPaste);
    }

    if (editableContent) {
        editableContent.addEventListener('input', handleArticleContentChange);
        editableContent.addEventListener('paste', handleContentPaste);
    }

    // Обработчик для выбора изображения
    const imageInput = document.getElementById('hidden-image');
    if (imageInput) {
        imageInput.addEventListener('change', handleArticleImageSelect);
    }

    console.log('Обработчики событий инициализированы');
}

/**
 * Обработка изменения заголовка
 */
function handleArticleTitleChange(event) {
    const newTitle = event.target.textContent.trim() || '';
    articleFormState.title = newTitle;
    updateArticleHiddenFields();
    updateReadTime();
}

/**
 * Обработка изменения описания
 */
function handleArticleExcerptChange(event) {
    const newExcerpt = event.target.textContent.trim() || '';
    articleFormState.excerpt = newExcerpt;
    updateArticleHiddenFields();
    updateReadTime();
}

/**
 * Обработка изменения содержания
 */
function handleArticleContentChange(event) {
    const newContent = event.target.innerHTML.trim() || '';
    articleFormState.content = newContent;
    updateArticleHiddenFields();
    updateReadTime();
}

/**
 * Ограничение длины заголовка
 */
function limitTitleLength(event) {
    const maxLength = 150;
    if (event.target.textContent.length >= maxLength && 
        event.keyCode !== 8 && event.keyCode !== 46) {
        event.preventDefault();
    }
}

/**
 * Ограничение длины описания
 */
function limitExcerptLength(event) {
    const maxLength = 300;
    if (event.target.textContent.length >= maxLength && 
        event.keyCode !== 8 && event.keyCode !== 46) {
        event.preventDefault();
    }
}

/**
 * Обработка вставки контента
 */
function handleContentPaste(event) {
    event.preventDefault();
    
    // Получаем только текст без форматирования
    const text = (event.clipboardData || window.clipboardData).getData('text');
    
    // Вставляем простой текст
    document.execCommand('insertText', false, text);
}

/**
 * Выделение текста при клике
 * @param {HTMLElement} element - Элемент для выделения
 */
function selectText(element) {
    const range = document.createRange();
    range.selectNodeContents(element);
    const selection = window.getSelection();
    selection.removeAllRanges();
    selection.addRange(range);
}

/**
 * Выбор изображения для статьи
 */
function selectImage() {
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/*';
    input.onchange = function(event) {
        const file = event.target.files[0];
        if (file) {
            handleArticleImageFile(file);
        }
    };
    input.click();
}

/**
 * Обработка файла изображения с оптимизацией
 * @param {File} file - Файл изображения
 */
function handleArticleImageFile(file) {
    // Валидация файла
    if (!file.type.startsWith('image/')) {
        showUploadError('Пожалуйста, выберите файл изображения');
        return;
    }
    
    if (file.size > 10 * 1024 * 1024) { // 10MB
        showUploadError('Размер файла не должен превышать 10MB');
        return;
    }
    
    selectedArticleImage = file;
    
    const container = document.getElementById('article-image-container') || document.querySelector('.article-image');
    if (container) {
        // Показываем индикатор загрузки
        container.innerHTML = `
            <div class="d-flex justify-content-center align-items-center" style="height: 200px;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Создание превью...</span>
                </div>
                <span class="ms-2">Обработка изображения...</span>
            </div>
        `;
        
        // Создаем оптимизированное превью
        if (window.createOptimizedPreview) {
            window.createOptimizedPreview(file, null, function(file, optimizedDataUrl) {
                container.innerHTML = `
                    <img src="${optimizedDataUrl}" alt="Изображение статьи" class="img-fluid rounded">
                    <div class="image-overlay">
                        <i class="bi bi-camera-fill"></i>
                        <span>Изменить изображение</span>
                    </div>
                `;
            });
        } else {
            // Fallback для обычного превью
            const reader = new FileReader();
            reader.onload = function(e) {
                container.innerHTML = `
                    <img src="${e.target.result}" alt="Изображение статьи" class="img-fluid rounded">
                    <div class="image-overlay">
                        <i class="bi bi-camera-fill"></i>
                        <span>Изменить изображение</span>
                    </div>
                `;
            };
            reader.readAsDataURL(file);
        }
    }
    
    // Обновляем скрытое поле
    const hiddenImage = document.getElementById('hidden-image');
    if (hiddenImage) {
        const dt = new DataTransfer();
        dt.items.add(file);
        hiddenImage.files = dt.files;
    }
}

/**
 * Обработка выбора изображения через input
 */
function handleArticleImageSelect(event) {
    const file = event.target.files[0];
    if (file) {
        handleArticleImageFile(file);
    }
}

/**
 * Обновление времени чтения
 */
function updateReadTime() {
    const content = articleFormState.content.replace(/<[^>]*>/g, '');
    const wordCount = content.split(/\s+/).filter(word => word.length > 0).length;
    const readTime = Math.max(1, Math.ceil(wordCount / 200)); // 200 слов в минуту
    
    articleFormState.readTime = readTime;
    
    // Обновляем отображение времени чтения
    const readTimeElement = document.querySelector('.read-time');
    if (readTimeElement) {
        readTimeElement.textContent = `${readTime} мин чтения`;
    }
    
    updateArticleHiddenFields();
}

/**
 * Обновление скрытых полей формы
 */
function updateArticleHiddenFields() {
    const hiddenTitle = document.getElementById('hidden-title');
    const hiddenExcerpt = document.getElementById('hidden-excerpt');
    const hiddenContent = document.getElementById('hidden-content');
    const hiddenReadTime = document.getElementById('hidden-read-time');
    const hiddenIsPublished = document.getElementById('hidden-is-published');

    if (hiddenTitle) hiddenTitle.value = articleFormState.title;
    if (hiddenExcerpt) hiddenExcerpt.value = articleFormState.excerpt;
    if (hiddenContent) hiddenContent.value = articleFormState.content;
    if (hiddenReadTime) hiddenReadTime.value = articleFormState.readTime;
    if (hiddenIsPublished) hiddenIsPublished.value = articleFormState.isPublished ? '1' : '0';
}

/**
 * Валидация формы статьи
 * @param {boolean} isPublishing - Флаг публикации
 * @returns {boolean} - Результат валидации
 */
function validateArticleForm(isPublishing = false) {
    const errors = [];

    // Проверяем заголовок
    if (!articleFormState.title.trim() || articleFormState.title === 'Новая статья') {
        errors.push('Пожалуйста, введите заголовок статьи');
    }

    // Проверяем описание
    if (!articleFormState.excerpt.trim() || 
        articleFormState.excerpt === 'Краткое описание статьи. Нажмите, чтобы редактировать.') {
        errors.push('Пожалуйста, введите краткое описание статьи');
    }

    // Проверяем содержание
    if (!articleFormState.content.trim() || 
        articleFormState.content === '<p>Содержание статьи. Нажмите, чтобы начать писать...</p>') {
        errors.push('Пожалуйста, введите содержание статьи');
    }

    // Показываем ошибки
    if (errors.length > 0) {
        alert(errors.join('\n'));
        return false;
    }

    return true;
}

/**
 * Сохранение статьи с прогресс-баром
 * @param {boolean} publish - Флаг публикации
 */
function saveArticle(publish = false) {
    console.log('Сохранение статьи...', { publish });

    // Валидируем форму
    if (!validateArticleForm(publish)) {
        return;
    }

    // Показываем прогресс-бар
    const progressInterval = window.simulateUploadProgress ? 
        window.simulateUploadProgress(3000) : null;

    // Устанавливаем статус публикации
    articleFormState.isPublished = publish;
    
    // Обновляем скрытые поля
    updateArticleHiddenFields();
    
    // Отправляем форму
    const form = document.getElementById('article-form');
    if (form) {
        // Если есть изображение, показываем дополнительное время обработки
        if (selectedArticleImage && selectedArticleImage.size > 1024 * 1024) { // > 1MB
            setTimeout(() => {
                if (window.completeUploadProgress) {
                    window.completeUploadProgress();
                }
                form.submit();
            }, 1500); // Дополнительное время для больших файлов
        } else {
            setTimeout(() => {
                if (window.completeUploadProgress) {
                    window.completeUploadProgress();
                }
                form.submit();
            }, 800);
        }
    } else {
        if (window.showUploadError) {
            window.showUploadError('Ошибка: форма не найдена');
        } else {
            alert('Ошибка: форма не найдена');
        }
        
        if (progressInterval) {
            clearInterval(progressInterval);
        }
        if (window.hideProgressBar) {
            window.hideProgressBar();
        }
    }
}

/**
 * Показать индикатор загрузки
 */
function showArticleLoading() {
    const overlay = document.getElementById('loading-overlay');
    if (overlay) {
        overlay.style.display = 'flex';
    }
}

/**
 * Скрыть индикатор загрузки
 */
function hideArticleLoading() {
    const overlay = document.getElementById('loading-overlay');
    if (overlay) {
        overlay.style.display = 'none';
    }
}

/**
 * Автосохранение при закрытии страницы
 */
document.addEventListener('beforeunload', function() {
    updateArticleHiddenFields();
});

// Экспорт функций для глобального использования
window.initArticlePage = initArticlePage;
window.selectText = selectText;
window.selectImage = selectImage;
window.saveArticle = saveArticle;
window.showArticleLoading = showArticleLoading;
window.hideArticleLoading = hideArticleLoading;

console.log('Модуль admin-articles.js загружен');