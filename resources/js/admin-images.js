/**
 * Универсальные функции для работы с изображениями
 * Используется в админских формах для загрузки и обработки изображений
 */

/**
 * Инициирует выбор файла через скрытый input
 * @param {string} inputId - ID скрытого input элемента (по умолчанию 'image-input')
 */
function triggerFileInput(inputId = 'image-input') {
    const input = document.getElementById(inputId);
    if (input) {
        input.click();
    } else {
        console.warn('File input not found with ID:', inputId);
    }
}

/**
 * Обрабатывает выбор изображения с оптимизацией
 * @param {Event} event - Событие изменения input файла
 * @param {string} previewContainerId - ID контейнера для предпросмотра
 * @param {Function} callback - Дополнительная функция обратного вызова
 */
function handleImageSelect(event, previewContainerId = 'image-preview-container', callback = null) {
    const file = event.target.files[0];
    
    if (!file) {
        return;
    }
    
    // Проверка типа файла
    if (!file.type.startsWith('image/')) {
        alert('Пожалуйста, выберите файл изображения');
        event.target.value = '';
        return;
    }
    
    // Проверка размера файла (максимум 10MB)
    const maxSize = 10 * 1024 * 1024;
    if (file.size > maxSize) {
        alert('Размер файла не должен превышать 10MB');
        event.target.value = '';
        return;
    }

    // Показываем индикатор загрузки
    showImageLoadingState(previewContainerId);

    // Создание оптимизированного предпросмотра
    createOptimizedPreview(file, previewContainerId, callback);
}

/**
 * Создает оптимизированный предпросмотр изображения
 */
function createOptimizedPreview(file, previewContainerId, callback = null) {
    const reader = new FileReader();
    
    reader.onload = function(e) {
        // Создаем canvas для оптимизации предпросмотра
        const img = new Image();
        img.onload = function() {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            
            // Вычисляем оптимальные размеры для превью
            const maxWidth = 800;
            const maxHeight = 600;
            let { width, height } = calculateOptimalSize(this.width, this.height, maxWidth, maxHeight);
            
            canvas.width = width;
            canvas.height = height;
            
            // Рисуем оптимизированное изображение
            ctx.drawImage(this, 0, 0, width, height);
            
            // Получаем оптимизированный data URL
            const optimizedDataUrl = canvas.toDataURL('image/jpeg', 0.85);
            
            // Обновляем превью
            updatePreviewContainer(previewContainerId, optimizedDataUrl);
            
            // Вызываем callback
            if (callback && typeof callback === 'function') {
                callback(file, optimizedDataUrl);
            }
        };
        
        img.src = e.target.result;
    };
    
    reader.readAsDataURL(file);
}

/**
 * Вычисляет оптимальный размер изображения
 */
function calculateOptimalSize(width, height, maxWidth, maxHeight) {
    if (width <= maxWidth && height <= maxHeight) {
        return { width, height };
    }
    
    const ratio = Math.min(maxWidth / width, maxHeight / height);
    return {
        width: Math.round(width * ratio),
        height: Math.round(height * ratio)
    };
}

/**
 * Показывает состояние загрузки изображения
 */
function showImageLoadingState(previewContainerId) {
    const previewContainer = document.getElementById(previewContainerId);
    if (previewContainer) {
        // Добавляем спиннер загрузки
        const loadingDiv = document.createElement('div');
        loadingDiv.className = 'image-loading';
        loadingDiv.style.cssText = `
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
        `;
        loadingDiv.innerHTML = `
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Загрузка...</span>
            </div>
        `;
        previewContainer.appendChild(loadingDiv);
    }
}

/**
 * Обновляет контейнер предпросмотра
 */
function updatePreviewContainer(previewContainerId, imageSrc) {
    const previewContainer = document.getElementById(previewContainerId);
    if (!previewContainer) return;
    
    // Убираем индикатор загрузки
    const loadingDiv = previewContainer.querySelector('.image-loading');
    if (loadingDiv) {
        loadingDiv.remove();
    }
    
    // Обновляем изображение в контейнере
    const img = previewContainer.querySelector('img');
    if (img) {
        img.src = imageSrc;
    } else {
        // Создаем новое изображение если его нет
        const newImg = document.createElement('img');
        newImg.src = imageSrc;
        newImg.style.width = '100%';
        newImg.style.height = '100%';
        newImg.style.objectFit = 'cover';
        newImg.style.borderRadius = '12px';
        previewContainer.appendChild(newImg);
    }
    
    // Убираем placeholder если есть
    const placeholder = previewContainer.querySelector('.no-image, .image-no-image');
    if (placeholder) {
        placeholder.style.display = 'none';
    }
}

/**
 * Создает preview изображения из URL
 * @param {string} imageUrl - URL изображения
 * @param {string} previewContainerId - ID контейнера для предпросмотра
 */
function createImagePreview(imageUrl, previewContainerId) {
    const previewContainer = document.getElementById(previewContainerId);
    if (!previewContainer) {
        console.warn('Preview container not found with ID:', previewContainerId);
        return;
    }
    
    const img = previewContainer.querySelector('img');
    if (img) {
        img.src = imageUrl;
    } else {
        const newImg = document.createElement('img');
        newImg.src = imageUrl;
        newImg.style.width = '100%';
        newImg.style.height = '100%';
        newImg.style.objectFit = 'cover';
        newImg.style.borderRadius = '12px';
        previewContainer.appendChild(newImg);
    }
    
    // Убираем placeholder если есть
    const placeholder = previewContainer.querySelector('.no-image, .image-no-image');
    if (placeholder) {
        placeholder.style.display = 'none';
    }
}

/**
 * Удаляет изображение и показывает placeholder
 * @param {string} previewContainerId - ID контейнера для предпросмотра
 * @param {string} inputId - ID скрытого input элемента
 */
function removeImage(previewContainerId, inputId = 'image-input') {
    const previewContainer = document.getElementById(previewContainerId);
    const input = document.getElementById(inputId);
    
    if (previewContainer) {
        const img = previewContainer.querySelector('img');
        if (img) {
            img.remove();
        }
        
        const placeholder = previewContainer.querySelector('.no-image, .image-no-image');
        if (placeholder) {
            placeholder.style.display = 'flex';
        }
    }
    
    if (input) {
        input.value = '';
    }
}

/**
 * Валидация изображения
 * @param {File} file - Файл для валидации
 * @param {Object} options - Опции валидации
 * @returns {boolean} - Результат валидации
 */
function validateImage(file, options = {}) {
    const {
        maxSize = 10 * 1024 * 1024, // 10MB по умолчанию
        allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
        minWidth = 0,
        minHeight = 0,
        maxWidth = Infinity,
        maxHeight = Infinity
    } = options;
    
    // Проверка типа файла
    if (!allowedTypes.includes(file.type)) {
        alert('Допустимые форматы: ' + allowedTypes.join(', '));
        return false;
    }
    
    // Проверка размера файла
    if (file.size > maxSize) {
        alert(`Размер файла не должен превышать ${Math.round(maxSize / 1024 / 1024)}MB`);
        return false;
    }
    
    return true;
}

// Экспортируем функции для глобального использования
window.triggerFileInput = triggerFileInput;
window.handleImageSelect = handleImageSelect;
window.createImagePreview = createImagePreview;
window.removeImage = removeImage;
window.validateImage = validateImage;