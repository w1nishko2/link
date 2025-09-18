/**
 * Универсальные функции для переключения элементов
 * Используется в формах админки для показа/скрытия дополнительных полей
 */

/**
 * Переключает видимость цены для услуг
 */
function togglePrice() {
    const priceBlock = document.querySelector('.service-price');
    const toggleButton = document.getElementById('price-toggle');
    
    if (!priceBlock || !toggleButton) {
        console.warn('Price elements not found');
        return;
    }
    
    if (priceBlock.style.display === 'none' || !priceBlock.style.display) {
        priceBlock.style.display = 'block';
        toggleButton.innerHTML = '<i class="bi bi-dash"></i> Убрать цену';
        toggleButton.className = 'btn btn-outline-danger btn-sm';
    } else {
        priceBlock.style.display = 'none';
        toggleButton.innerHTML = '<i class="bi bi-plus"></i> Добавить цену';
        toggleButton.className = 'btn btn-outline-success btn-sm';
    }
}

/**
 * Переключает настройки ссылки для баннеров
 */
function toggleLinkSettings() {
    const checkbox = document.getElementById('has-link-checkbox');
    const linkBlock = document.querySelector('.banner-link');
    
    if (!checkbox || !linkBlock) {
        console.warn('Link elements not found');
        return;
    }
    
    if (checkbox.checked) {
        linkBlock.style.display = 'block';
    } else {
        linkBlock.style.display = 'none';
        // Очищаем поля ссылки
        const linkUrlField = linkBlock.querySelector('[contenteditable]');
        if (linkUrlField) {
            linkUrlField.textContent = '';
        }
    }
}

/**
 * Переключает метаданные для изображений
 */
function toggleMetadata() {
    const metadataBlock = document.querySelector('.image-metadata');
    const toggleButton = document.querySelector('.add-metadata-button');
    
    if (!metadataBlock || !toggleButton) {
        console.warn('Metadata elements not found');
        return;
    }
    
    if (metadataBlock.style.display === 'none' || !metadataBlock.style.display) {
        metadataBlock.style.display = 'block';
        toggleButton.innerHTML = '<i class="bi bi-dash"></i> Убрать описание';
        toggleButton.className = 'btn btn-outline-danger btn-sm add-metadata-button';
    } else {
        metadataBlock.style.display = 'none';
        toggleButton.innerHTML = '<i class="bi bi-plus"></i> Добавить описание';
        toggleButton.className = 'btn btn-outline-success btn-sm add-metadata-button';
    }
}

/**
 * Универсальная функция для переключения любого элемента
 * @param {string} elementSelector - Селектор элемента для переключения
 * @param {string} buttonSelector - Селектор кнопки
 * @param {string} showText - Текст кнопки при показе элемента
 * @param {string} hideText - Текст кнопки при скрытии элемента
 * @param {string} showClass - CSS класс кнопки при показе элемента
 * @param {string} hideClass - CSS класс кнопки при скрытии элемента
 */
function toggleElement(elementSelector, buttonSelector, showText, hideText, showClass, hideClass) {
    const element = document.querySelector(elementSelector);
    const button = document.querySelector(buttonSelector);
    
    if (!element || !button) {
        console.warn('Toggle elements not found:', { elementSelector, buttonSelector });
        return;
    }
    
    if (element.style.display === 'none' || !element.style.display) {
        element.style.display = 'block';
        button.innerHTML = hideText;
        button.className = hideClass;
    } else {
        element.style.display = 'none';
        button.innerHTML = showText;
        button.className = showClass;
    }
}

// Экспортируем функции для глобального использования
window.togglePrice = togglePrice;
window.toggleLinkSettings = toggleLinkSettings;
window.toggleMetadata = toggleMetadata;
window.toggleElement = toggleElement;