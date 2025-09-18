/**
 * JavaScript для работы со статьями
 * Используется на страницах создания и редактирования статей
 */

// Глобальные переменные
let selectedArticleFile = null;
let articleSwiper = null;

// Состояние формы статьи
let articleFormState = {};

/**
 * Инициализация страницы статей
 * @param {Object} initialState - Начальное состояние формы
 */
function initArticlePage(initialState = {}) {
    articleFormState = {
        title: '',
        excerpt: '',
        content: '',
        metaTitle: '',
        metaDescription: '',
        metaKeywords: '',
        hasMetadata: false,
        isPublished: true,
        publishedAt: '',
        ...initialState
    };
    
    // Инициализируем компоненты
    initArticleSwiper();
    initArticleEventListeners();
    
    // Показываем/скрываем блок метаданных в зависимости от состояния
    if (articleFormState.hasMetadata) {
        document.querySelector('.article-metadata').style.display = 'block';
        updateMetadataToggleButton(true);
    }
}

/**
 * Инициализация Swiper для статей
 */
function initArticleSwiper() {
    if (document.querySelector('.articles-swiper')) {
        articleSwiper = new Swiper('.articles-swiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: false,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                    spaceBetween: 30,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 40,
                }
            }
        });
    }
}

/**
 * Инициализация обработчиков событий для статей
 */
function initArticleEventListeners() {
    // Обработчики для редактируемых элементов
    const editableElements = document.querySelectorAll('[contenteditable="true"]');
    editableElements.forEach(element => {
        element.addEventListener('input', handleArticleContentEdit);
        element.addEventListener('blur', handleArticleContentBlur);
        element.addEventListener('paste', handleArticleContentPaste);
        element.addEventListener('keydown', handleArticleContentKeydown);
    });
    
    // Обработчик для загрузки изображений статьи
    const imageInput = document.getElementById('article-image-input');
    if (imageInput) {
        imageInput.addEventListener('change', (e) => {
            handleImageSelect(e, 'article-image-preview', (file, dataUrl) => {
                selectedArticleFile = file;
                updateArticleFormState();
            });
        });
    }
    
    // Обработчик отправки формы
    const form = document.getElementById('article-form');
    if (form) {
        form.addEventListener('submit', handleArticleFormSubmit);
    }
    
    // Обработчик для чекбокса публикации
    const publishedCheckbox = document.getElementById('is-published');
    if (publishedCheckbox) {
        publishedCheckbox.addEventListener('change', function() {
            articleFormState.isPublished = this.checked;
            togglePublishDateField(this.checked);
        });
    }
    
    // Обработчик для даты публикации
    const publishDateInput = document.getElementById('published-at');
    if (publishDateInput) {
        publishDateInput.addEventListener('change', function() {
            articleFormState.publishedAt = this.value;
        });
    }
}

/**
 * Обработка изменений в редактируемых элементах статьи
 */
function handleArticleContentEdit(event) {
    const element = event.target;
    const fieldName = element.getAttribute('data-field');
    
    if (fieldName && articleFormState.hasOwnProperty(fieldName)) {
        articleFormState[fieldName] = element.textContent.trim();
        
        // Автоматическое создание мета-заголовка из заголовка
        if (fieldName === 'title' && !articleFormState.metaTitle) {
            const metaTitleElement = document.querySelector('[data-field="metaTitle"]');
            if (metaTitleElement && metaTitleElement.textContent.trim() === '') {
                const truncatedTitle = truncateText(element.textContent.trim(), 60);
                metaTitleElement.textContent = truncatedTitle;
                articleFormState.metaTitle = truncatedTitle;
            }
        }
        
        // Автоматическое создание мета-описания из краткого описания
        if (fieldName === 'excerpt' && !articleFormState.metaDescription) {
            const metaDescElement = document.querySelector('[data-field="metaDescription"]');
            if (metaDescElement && metaDescElement.textContent.trim() === '') {
                const truncatedExcerpt = truncateText(element.textContent.trim(), 160);
                metaDescElement.textContent = truncatedExcerpt;
                articleFormState.metaDescription = truncatedExcerpt;
            }
        }
        
        // Проверка длины контента для полей с ограничениями
        if (fieldName === 'metaTitle') {
            checkFieldLength(element, 60, 'Мета-заголовок слишком длинный');
        }
        if (fieldName === 'metaDescription') {
            checkFieldLength(element, 160, 'Мета-описание слишком длинное');
        }
    }
}

/**
 * Обработка потери фокуса редактируемых элементов статьи
 */
function handleArticleContentBlur(event) {
    const element = event.target;
    const fieldName = element.getAttribute('data-field');
    
    // Валидация и форматирование для конкретных полей
    if (fieldName === 'title') {
        if (element.textContent.trim().length < 5) {
            element.classList.add('is-invalid');
            showNotification('Заголовок статьи должен содержать минимум 5 символов', 'warning');
            setTimeout(() => element.classList.remove('is-invalid'), 3000);
        }
    }
    
    if (fieldName === 'content') {
        if (element.textContent.trim().length < 50) {
            element.classList.add('is-invalid');
            showNotification('Содержание статьи должно содержать минимум 50 символов', 'warning');
            setTimeout(() => element.classList.remove('is-invalid'), 3000);
        }
    }
}

/**
 * Обработка вставки контента в редактируемые элементы
 */
function handleArticleContentPaste(event) {
    const element = event.target;
    const fieldName = element.getAttribute('data-field');
    
    // Для поля content разрешаем форматированный текст
    if (fieldName === 'content') {
        // Позволяем сохранить базовое форматирование
        setTimeout(() => {
            // Очищаем ненужные атрибуты, но сохраняем структуру
            cleanPastedContent(element);
            articleFormState[fieldName] = element.innerHTML;
        }, 0);
    } else {
        // Для остальных полей только чистый текст
        event.preventDefault();
        const paste = (event.clipboardData || window.clipboardData).getData('text');
        const selection = window.getSelection();
        
        if (!selection.rangeCount) return;
        
        selection.deleteFromDocument();
        selection.getRangeAt(0).insertNode(document.createTextNode(paste));
        selection.collapseToEnd();
        
        articleFormState[fieldName] = element.textContent.trim();
    }
}

/**
 * Обработка нажатий клавиш в редактируемых элементах
 */
function handleArticleContentKeydown(event) {
    const element = event.target;
    const fieldName = element.getAttribute('data-field');
    
    // Для контента разрешаем некоторые горячие клавиши
    if (fieldName === 'content') {
        // Ctrl+B для жирного текста
        if (event.ctrlKey && event.key === 'b') {
            event.preventDefault();
            document.execCommand('bold', false, null);
        }
        // Ctrl+I для курсива
        if (event.ctrlKey && event.key === 'i') {
            event.preventDefault();
            document.execCommand('italic', false, null);
        }
        // Ctrl+U для подчеркивания
        if (event.ctrlKey && event.key === 'u') {
            event.preventDefault();
            document.execCommand('underline', false, null);
        }
    }
}

/**
 * Очистка вставленного контента
 */
function cleanPastedContent(element) {
    // Удаляем нежелательные теги и атрибуты
    const allowedTags = ['b', 'strong', 'i', 'em', 'u', 'br', 'p', 'h1', 'h2', 'h3', 'h4', 'ul', 'ol', 'li'];
    const children = element.querySelectorAll('*');
    
    children.forEach(child => {
        if (!allowedTags.includes(child.tagName.toLowerCase())) {
            child.outerHTML = child.innerHTML;
        } else {
            // Удаляем все атрибуты
            Array.from(child.attributes).forEach(attr => {
                child.removeAttribute(attr.name);
            });
        }
    });
}

/**
 * Проверка длины поля
 */
function checkFieldLength(element, maxLength, message) {
    const length = element.textContent.trim().length;
    if (length > maxLength) {
        element.classList.add('text-warning');
        const remaining = maxLength - length;
        showNotification(`${message} (${remaining} символов превышено)`, 'warning');
    } else {
        element.classList.remove('text-warning');
    }
}

/**
 * Обрезание текста до указанной длины
 */
function truncateText(text, maxLength) {
    if (text.length <= maxLength) return text;
    return text.substring(0, maxLength).trim() + '...';
}

/**
 * Переключение поля даты публикации
 */
function togglePublishDateField(isPublished) {
    const dateField = document.getElementById('publish-date-field');
    if (dateField) {
        dateField.style.display = isPublished ? 'block' : 'none';
    }
}

/**
 * Обновление кнопки переключения метаданных
 */
function updateMetadataToggleButton(hasMetadata) {
    const toggleButton = document.getElementById('metadata-toggle');
    if (!toggleButton) return;
    
    if (hasMetadata) {
        toggleButton.innerHTML = '<i class="bi bi-dash"></i> Скрыть SEO';
        toggleButton.className = 'btn btn-outline-danger btn-sm';
    } else {
        toggleButton.innerHTML = '<i class="bi bi-plus"></i> Показать SEO';
        toggleButton.className = 'btn btn-outline-success btn-sm';
    }
}

/**
 * Обработка отправки формы статьи
 */
async function handleArticleFormSubmit(event) {
    event.preventDefault();
    
    // Валидация
    if (!validateArticleForm()) {
        return;
    }
    
    // Обновляем состояние формы
    updateArticleFormState();
    
    // Создаем FormData
    const formData = new FormData();
    
    // Добавляем данные из состояния формы
    Object.keys(articleFormState).forEach(key => {
        if (articleFormState[key] !== null && articleFormState[key] !== undefined) {
            formData.append(key, articleFormState[key]);
        }
    });
    
    // Добавляем изображение если выбрано
    if (selectedArticleFile) {
        formData.append('image', selectedArticleFile);
    }
    
    // Добавляем CSRF токен
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (csrfToken) {
        formData.append('_token', csrfToken);
    }
    
    // Отправляем форму
    await submitForm(event.target, {
        method: 'POST',
        loadingOverlayId: 'loadingOverlay',
        validateBeforeSubmit: validateArticleForm,
        onSuccess: (result) => {
            if (result.redirect_url) {
                window.location.href = result.redirect_url;
            }
        }
    });
}

/**
 * Обновление состояния формы статьи
 */
function updateArticleFormState() {
    // Собираем данные из всех редактируемых элементов
    const editableElements = document.querySelectorAll('[contenteditable="true"]');
    editableElements.forEach(element => {
        const fieldName = element.getAttribute('data-field');
        if (fieldName && articleFormState.hasOwnProperty(fieldName)) {
            if (fieldName === 'content') {
                // Для контента сохраняем HTML
                articleFormState[fieldName] = element.innerHTML;
            } else {
                // Для остальных полей только текст
                articleFormState[fieldName] = element.textContent.trim();
            }
        }
    });
    
    // Проверяем состояние метаданных
    const metadataBlock = document.querySelector('.article-metadata');
    articleFormState.hasMetadata = metadataBlock && metadataBlock.style.display !== 'none';
    
    // Обновляем состояние чекбокса публикации
    const publishedCheckbox = document.getElementById('is-published');
    if (publishedCheckbox) {
        articleFormState.isPublished = publishedCheckbox.checked;
    }
    
    // Обновляем дату публикации
    const publishDateInput = document.getElementById('published-at');
    if (publishDateInput) {
        articleFormState.publishedAt = publishDateInput.value;
    }
}

/**
 * Валидация формы статьи
 */
function validateArticleForm() {
    updateArticleFormState();
    
    // Проверяем обязательные поля
    if (!articleFormState.title || articleFormState.title.trim() === '') {
        showNotification('Пожалуйста, введите заголовок статьи', 'error');
        const titleElement = document.querySelector('[data-field="title"]');
        if (titleElement) {
            titleElement.focus();
            titleElement.classList.add('is-invalid');
            setTimeout(() => titleElement.classList.remove('is-invalid'), 3000);
        }
        return false;
    }
    
    if (articleFormState.title.trim().length < 5) {
        showNotification('Заголовок статьи должен содержать минимум 5 символов', 'error');
        return false;
    }
    
    if (!articleFormState.excerpt || articleFormState.excerpt.trim() === '') {
        showNotification('Пожалуйста, введите краткое описание статьи', 'error');
        const excerptElement = document.querySelector('[data-field="excerpt"]');
        if (excerptElement) {
            excerptElement.focus();
            excerptElement.classList.add('is-invalid');
            setTimeout(() => excerptElement.classList.remove('is-invalid'), 3000);
        }
        return false;
    }
    
    if (!articleFormState.content || articleFormState.content.trim() === '') {
        showNotification('Пожалуйста, введите содержание статьи', 'error');
        const contentElement = document.querySelector('[data-field="content"]');
        if (contentElement) {
            contentElement.focus();
            contentElement.classList.add('is-invalid');
            setTimeout(() => contentElement.classList.remove('is-invalid'), 3000);
        }
        return false;
    }
    
    if (articleFormState.content.trim().length < 50) {
        showNotification('Содержание статьи должно содержать минимум 50 символов', 'error');
        return false;
    }
    
    // Проверяем метаданные если они включены
    if (articleFormState.hasMetadata) {
        if (articleFormState.metaTitle && articleFormState.metaTitle.length > 60) {
            showNotification('Мета-заголовок не должен превышать 60 символов', 'error');
            return false;
        }
        
        if (articleFormState.metaDescription && articleFormState.metaDescription.length > 160) {
            showNotification('Мета-описание не должно превышать 160 символов', 'error');
            return false;
        }
    }
    
    return true;
}

// Экспортируем функции для глобального использования
window.initArticlePage = initArticlePage;
window.validateArticleForm = validateArticleForm;
window.updateMetadataToggleButton = updateMetadataToggleButton;

// Автоинициализация при загрузке DOM
document.addEventListener('DOMContentLoaded', function() {
    // Проверяем, находимся ли мы на странице статей
    if (document.getElementById('article-form')) {
        initArticlePage();
    }
});