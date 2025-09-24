/**
 * Система прогресс-бара для загрузки изображений
 * Обеспечивает визуальную обратную связь во время загрузки
 */

/**
 * Создает и показывает прогресс-бар
 */
function createProgressBar() {
    // Удаляем существующий прогресс-бар если есть
    const existingProgress = document.getElementById('upload-progress-bar');
    if (existingProgress) {
        existingProgress.remove();
    }

    const progressContainer = document.createElement('div');
    progressContainer.id = 'upload-progress-bar';
    progressContainer.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 9999;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        padding: 20px 0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    `;

    progressContainer.innerHTML = `
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="text-center mb-3">
                        <h6 class="mb-2">Загрузка изображения...</h6>
                        <small class="text-muted">Пожалуйста, подождите</small>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" 
                             role="progressbar" 
                             style="width: 0%;" 
                             id="upload-progress-bar-fill"></div>
                    </div>
                    <div class="text-center mt-2">
                        <small class="text-muted" id="upload-progress-text">0%</small>
                    </div>
                </div>
            </div>
        </div>
    `;

    document.body.appendChild(progressContainer);
    return progressContainer;
}

/**
 * Обновляет прогресс-бар
 */
function updateProgressBar(percentage) {
    const progressFill = document.getElementById('upload-progress-bar-fill');
    const progressText = document.getElementById('upload-progress-text');
    
    if (progressFill && progressText) {
        progressFill.style.width = percentage + '%';
        progressText.textContent = Math.round(percentage) + '%';
    }
}

/**
 * Скрывает прогресс-бар
 */
function hideProgressBar() {
    const progressBar = document.getElementById('upload-progress-bar');
    if (progressBar) {
        progressBar.style.opacity = '0';
        progressBar.style.transition = 'opacity 0.3s ease';
        setTimeout(() => {
            progressBar.remove();
        }, 300);
    }
}

/**
 * Симулирует прогресс загрузки (для лучшего UX)
 */
function simulateUploadProgress(duration = 2000) {
    createProgressBar();
    
    let progress = 0;
    const increment = 100 / (duration / 50); // Обновляем каждые 50мс
    
    const interval = setInterval(() => {
        progress += increment;
        
        if (progress >= 90) {
            // Замедляем в конце для реалистичности
            clearInterval(interval);
            updateProgressBar(90);
            return;
        }
        
        updateProgressBar(progress);
    }, 50);
    
    return interval;
}

/**
 * Завершает прогресс загрузки
 */
function completeUploadProgress() {
    updateProgressBar(100);
    setTimeout(() => {
        hideProgressBar();
    }, 500);
}

/**
 * Показывает ошибку загрузки
 */
function showUploadError(message = 'Ошибка загрузки изображения') {
    hideProgressBar();
    
    // Создаем уведомление об ошибке
    const errorNotification = document.createElement('div');
    errorNotification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #dc3545;
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        z-index: 10000;
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
        font-size: 14px;
        max-width: 300px;
    `;
    
    errorNotification.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(errorNotification);
    
    // Автоматически убираем через 5 секунд
    setTimeout(() => {
        errorNotification.style.opacity = '0';
        errorNotification.style.transition = 'opacity 0.3s ease';
        setTimeout(() => {
            errorNotification.remove();
        }, 300);
    }, 5000);
}

// Экспортируем функции для глобального использования
window.createProgressBar = createProgressBar;
window.updateProgressBar = updateProgressBar;
window.hideProgressBar = hideProgressBar;
window.simulateUploadProgress = simulateUploadProgress;
window.completeUploadProgress = completeUploadProgress;
window.showUploadError = showUploadError;