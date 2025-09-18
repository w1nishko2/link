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
 * Обрабатывает выбор изображения
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
    
    // Создание предпросмотра
    const reader = new FileReader();
    reader.onload = function(e) {
        const previewContainer = document.getElementById(previewContainerId);
        if (previewContainer) {
            // Обновляем изображение в контейнере
            const img = previewContainer.querySelector('img');
            if (img) {
                img.src = e.target.result;
            } else {
                // Создаем новое изображение если его нет
                const newImg = document.createElement('img');
                newImg.src = e.target.result;
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
        
        // Вызываем callback если передан
        if (callback && typeof callback === 'function') {
            callback(file, e.target.result);
        }
    };
    
    reader.readAsDataURL(file);
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