/**
 * Универсальные функции для работы с формами
 * Используется в админских формах для отправки данных
 */

/**
 * Отправляет форму с обработкой ошибок и loading состояния
 * @param {HTMLFormElement|string} form - Форма или селектор формы
 * @param {Object} options - Опции отправки
 */
async function submitForm(form, options = {}) {
    const {
        showLoading = true,
        loadingOverlayId = null,
        validateBeforeSubmit = null,
        onSuccess = null,
        onError = null,
        method = 'POST'
    } = options;
    
    // Получаем форму
    const formElement = typeof form === 'string' ? document.querySelector(form) : form;
    if (!formElement) {
        console.error('Form not found');
        return;
    }
    
    // Валидация перед отправкой
    if (validateBeforeSubmit && typeof validateBeforeSubmit === 'function') {
        const isValid = validateBeforeSubmit();
        if (!isValid) {
            return;
        }
    }
    
    // Показываем loading
    if (showLoading && typeof window.showLoading === 'function') {
        window.showLoading(loadingOverlayId);
    }
    
    try {
        // Собираем данные формы
        const formData = new FormData(formElement);
        
        // Добавляем данные из редактируемых элементов
        const editableElements = formElement.querySelectorAll('[contenteditable="true"]');
        editableElements.forEach(element => {
            const name = element.getAttribute('data-name');
            if (name) {
                formData.append(name, element.textContent.trim());
            }
        });
        
        // Отправляем запрос
        const response = await fetch(formElement.action, {
            method: method,
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        });
        
        if (response.ok) {
            const result = await response.json();
            
            if (onSuccess && typeof onSuccess === 'function') {
                onSuccess(result);
            } else {
                // Показываем сообщение об успехе
                showNotification('Данные успешно сохранены', 'success');
                
                // Перенаправляем если указан redirect_url
                if (result.redirect_url) {
                    setTimeout(() => {
                        window.location.href = result.redirect_url;
                    }, 1000);
                }
            }
        } else {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Ошибка сервера');
        }
        
    } catch (error) {
        console.error('Form submission error:', error);
        
        if (onError && typeof onError === 'function') {
            onError(error);
        } else {
            showNotification(error.message || 'Произошла ошибка при сохранении', 'error');
        }
    } finally {
        // Скрываем loading
        if (showLoading && typeof window.hideLoading === 'function') {
            window.hideLoading(loadingOverlayId);
        }
    }
}

/**
 * Показывает уведомление пользователю
 * @param {string} message - Текст сообщения
 * @param {string} type - Тип сообщения (success, error, warning, info)
 * @param {number} duration - Длительность показа в миллисекундах
 */
function showNotification(message, type = 'info', duration = 5000) {
    // Создаем элемент уведомления
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = `
        top: 20px;
        right: 20px;
        z-index: 10000;
        min-width: 300px;
        max-width: 500px;
    `;
    
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Добавляем в body
    document.body.appendChild(notification);
    
    // Автоматически удаляем через указанное время
    setTimeout(() => {
        if (notification.parentNode) {
            notification.classList.remove('show');
            setTimeout(() => {
                notification.remove();
            }, 150);
        }
    }, duration);
}

/**
 * Валидирует обязательные поля
 * @param {HTMLFormElement|string} form - Форма или селектор формы
 * @returns {boolean} - Результат валидации
 */
function validateRequiredFields(form) {
    const formElement = typeof form === 'string' ? document.querySelector(form) : form;
    if (!formElement) {
        return false;
    }
    
    const requiredFields = formElement.querySelectorAll('[required], [data-required="true"]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        let value = '';
        
        if (field.hasAttribute('contenteditable')) {
            value = field.textContent.trim();
        } else {
            value = field.value ? field.value.trim() : '';
        }
        
        if (!value) {
            field.classList.add('is-invalid');
            isValid = false;
            
            // Убираем класс ошибки при изменении поля
            const removeInvalid = () => {
                field.classList.remove('is-invalid');
                field.removeEventListener('input', removeInvalid);
                field.removeEventListener('blur', removeInvalid);
            };
            
            field.addEventListener('input', removeInvalid);
            field.addEventListener('blur', removeInvalid);
        } else {
            field.classList.remove('is-invalid');
        }
    });
    
    if (!isValid) {
        showNotification('Пожалуйста, заполните все обязательные поля', 'error');
    }
    
    return isValid;
}

/**
 * Автосохранение формы
 * @param {HTMLFormElement|string} form - Форма или селектор формы
 * @param {number} interval - Интервал автосохранения в миллисекундах
 */
function enableAutosave(form, interval = 30000) {
    const formElement = typeof form === 'string' ? document.querySelector(form) : form;
    if (!formElement) {
        return;
    }
    
    let autosaveTimeout;
    let hasChanges = false;
    
    // Отслеживаем изменения
    const markChanged = () => {
        hasChanges = true;
        clearTimeout(autosaveTimeout);
        autosaveTimeout = setTimeout(() => {
            if (hasChanges) {
                submitForm(formElement, {
                    showLoading: false,
                    onSuccess: () => {
                        hasChanges = false;
                        showNotification('Автосохранение выполнено', 'info', 2000);
                    }
                });
            }
        }, interval);
    };
    
    // Добавляем обработчики событий
    formElement.addEventListener('input', markChanged);
    formElement.addEventListener('change', markChanged);
    
    // Для contenteditable элементов
    const editableElements = formElement.querySelectorAll('[contenteditable="true"]');
    editableElements.forEach(element => {
        element.addEventListener('input', markChanged);
        element.addEventListener('blur', markChanged);
    });
}

// Экспортируем функции для глобального использования
window.submitForm = submitForm;
window.showNotification = showNotification;
window.validateRequiredFields = validateRequiredFields;
window.enableAutosave = enableAutosave;