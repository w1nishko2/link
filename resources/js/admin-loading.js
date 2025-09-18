/**
 * Универсальные функции для индикаторов загрузки
 * Используется во всех админских формах
 */

/**
 * Показывает индикатор загрузки
 * @param {string} overlayId - ID элемента overlay (по умолчанию 'loading-overlay' или 'loadingOverlay')
 */
function showLoading(overlayId = null) {
    // Ищем элемент по ID
    const ids = overlayId ? [overlayId] : ['loading-overlay', 'loadingOverlay'];
    
    for (const id of ids) {
        const overlay = document.getElementById(id);
        if (overlay) {
            overlay.style.display = 'flex';
            return;
        }
    }
    
    console.warn('Loading overlay not found with any of these IDs:', ids);
}

/**
 * Скрывает индикатор загрузки
 * @param {string} overlayId - ID элемента overlay (по умолчанию 'loading-overlay' или 'loadingOverlay')
 */
function hideLoading(overlayId = null) {
    // Ищем элемент по ID
    const ids = overlayId ? [overlayId] : ['loading-overlay', 'loadingOverlay'];
    
    for (const id of ids) {
        const overlay = document.getElementById(id);
        if (overlay) {
            overlay.style.display = 'none';
            return;
        }
    }
    
    console.warn('Loading overlay not found with any of these IDs:', ids);
}

// Экспортируем функции для использования в других модулях
window.showLoading = showLoading;
window.hideLoading = hideLoading;