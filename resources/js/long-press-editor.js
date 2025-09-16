/**
 * Long Press Editor - Функциональность долгого нажатия для редактирования элементов
 * Позволяет владельцу страницы долгим нажатием переходить к редактированию
 * элементов (статей, услуг, баннеров, фотографий)
 */

class LongPressEditor {
    constructor() {
        this.longPressTimeout = null;
        this.longPressDuration = 2000; // 2 секунды для долгого нажатия
        this.isLongPress = false;
        this.startX = 0;
        this.startY = 0;
        this.maxMovement = 10; // максимальное движение в пикселях
        this.currentElement = null;
        
        this.init();
    }

    init() {
        // Проверяем, является ли текущий пользователь владельцем страницы
        if (!this.isPageOwner()) {
            return;
        }

        this.bindEvents();
        this.addStyles();
    }

    /**
     * Проверяет, является ли текущий пользователь владельцем страницы
     */
    isPageOwner() {
        // Получаем данные из мета-тегов или глобальных переменных
        const currentUserId = window.currentUserId || document.querySelector('meta[name="current-user-id"]')?.getAttribute('content');
        const pageUserId = window.pageUserId || document.querySelector('meta[name="page-user-id"]')?.getAttribute('content');
        
        return currentUserId && pageUserId && currentUserId === pageUserId;
    }

    /**
     * Привязывает события к редактируемым элементам
     */
    bindEvents() {
        // Селекторы для редактируемых элементов
        const selectors = [
            '.article-preview', // статьи
            '.service-card', // услуги  
            '.banners-banner', // баннеры
            '.gallery-item.editable-item' // галерея
        ];

        selectors.forEach(selector => {
            const elements = document.querySelectorAll(selector);
            elements.forEach(element => {
                this.addLongPressEvents(element);
            });
        });
    }

    /**
     * Добавляет события долгого нажатия к элементу
     */
    addLongPressEvents(element) {
        // Touch события для мобильных устройств
        element.addEventListener('touchstart', (e) => this.handleStart(e, element), { passive: true });
        element.addEventListener('touchend', (e) => this.handleEnd(e, element), { passive: true });
        element.addEventListener('touchmove', (e) => this.handleMove(e, element), { passive: true });
        element.addEventListener('touchcancel', (e) => this.handleCancel(e, element), { passive: true });

        // Mouse события для десктопа
        element.addEventListener('mousedown', (e) => this.handleStart(e, element), { passive: true });
        element.addEventListener('mouseup', (e) => this.handleEnd(e, element), { passive: true });
        element.addEventListener('mousemove', (e) => this.handleMove(e, element), { passive: true });
        element.addEventListener('mouseleave', (e) => this.handleCancel(e, element), { passive: true });

        // Предотвращаем контекстное меню на долгое нажатие
        element.addEventListener('contextmenu', (e) => {
            if (this.isLongPress) {
                e.preventDefault();
            }
        });

        // Предотвращаем клики после долгого нажатия
        element.addEventListener('click', (e) => {
            if (this.isLongPress) {
                e.preventDefault();
                e.stopPropagation();
                this.isLongPress = false; // Сбрасываем флаг
                return false;
            }
        });
    }

    /**
     * Обработка начала нажатия
     */
    handleStart(event, element) {
        this.currentElement = element;
        this.isLongPress = false;
        
        // Получаем координаты
        const point = event.touches ? event.touches[0] : event;
        this.startX = point.clientX;
        this.startY = point.clientY;

        // Добавляем визуальную подсветку
        element.classList.add('long-press-active');

        // Устанавливаем таймер для долгого нажатия
        this.longPressTimeout = setTimeout(() => {
            this.isLongPress = true;
            this.triggerLongPress(element);
        }, this.longPressDuration);
    }

    /**
     * Обработка движения во время нажатия
     */
    handleMove(event, element) {
        if (!this.longPressTimeout) return;

        const point = event.touches ? event.touches[0] : event;
        const deltaX = Math.abs(point.clientX - this.startX);
        const deltaY = Math.abs(point.clientY - this.startY);

        // Если движение слишком большое, отменяем долгое нажатие
        if (deltaX > this.maxMovement || deltaY > this.maxMovement) {
            this.handleCancel(event, element);
        }
    }

    /**
     * Обработка окончания нажатия
     */
    handleEnd(event, element) {
        // Если это был долгий клик, предотвращаем переход по ссылке
        const wasLongPress = this.isLongPress;
        
        this.cleanup(element);
        
        // Возвращаем false для предотвращения обычного поведения, если это был долгий клик
        if (wasLongPress) {
            return false;
        }
    }

    /**
     * Обработка отмены нажатия
     */
    handleCancel(event, element) {
        this.cleanup(element);
    }

    /**
     * Очистка состояния
     */
    cleanup(element) {
        if (this.longPressTimeout) {
            clearTimeout(this.longPressTimeout);
            this.longPressTimeout = null;
        }
        
        if (element) {
            element.classList.remove('long-press-active');
        }
        
        this.currentElement = null;
    }

    /**
     * Срабатывание долгого нажатия
     */
    triggerLongPress(element) {
        // Добавляем визуальную обратную связь
        element.classList.add('long-press-triggered');
        
        // Вибрация на поддерживаемых устройствах
        if (navigator.vibrate) {
            navigator.vibrate(50);
        }

        // Определяем тип элемента и переходим к соответствующей странице редактирования
        const editUrl = this.getEditUrl(element);
        
        if (editUrl) {
            // Небольшая задержка для визуального эффекта
            setTimeout(() => {
                window.location.href = editUrl;
            }, 100);
        }
    }

    /**
     * Получает URL для редактирования в зависимости от типа элемента
     */
    getEditUrl(element) {
        const currentUserId = document.querySelector('meta[name="current-user-id"]')?.getAttribute('content');
        
        if (!currentUserId) {
            console.warn('Current user ID not found');
            return null;
        }

        // Статьи
        if (element.classList.contains('article-preview')) {
            const articleId = element.getAttribute('data-article-id');
            if (articleId) {
                return `/admin/user/${currentUserId}/articles/${articleId}/edit`;
            }
        }

        // Услуги
        if (element.classList.contains('service-card')) {
            const serviceId = element.getAttribute('data-analytics-id');
            if (serviceId) {
                return `/admin/user/${currentUserId}/services/${serviceId}/edit`;
            }
        }

        // Баннеры
        if (element.classList.contains('banners-banner')) {
            const bannerId = element.getAttribute('data-analytics-id');
            if (bannerId) {
                return `/admin/user/${currentUserId}/banners/${bannerId}/edit`;
            }
        }

        // Галерея
        if (element.classList.contains('gallery-item') && element.classList.contains('editable-item')) {
            // Для галереи переходим на общую страницу управления
            return `/admin/user/${currentUserId}/gallery`;
        }

        return null;
    }

    /**
     * Добавляет CSS стили для визуальной обратной связи
     */
    addStyles() {
        const styles = `
            .long-press-active {
                transition: transform 0.1s ease-in-out, opacity 0.1s ease-in-out;
                transform: scale(0.98);
                opacity: 0.8;
            }
            
            .long-press-triggered {
                transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
                transform: scale(1.02);
                box-shadow: 0 8px 25px rgba(0, 123, 255, 0.3);
                border: 2px solid rgba(0, 123, 255, 0.5);
                border-radius: 8px;
                position: relative;
                z-index: 10;
            }
            
            .long-press-triggered::before {
                content: "✏️ Редактировать";
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: rgba(0, 123, 255, 0.95);
                color: white;
                padding: 8px 16px;
                border-radius: 20px;
                font-size: 14px;
                font-weight: 500;
                white-space: nowrap;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
                z-index: 11;
                pointer-events: none;
                backdrop-filter: blur(4px);
            }

            /* Специальные стили для разных типов элементов */
            .article-preview.long-press-triggered {
                border-radius: 12px;
            }

            .service-card.long-press-triggered {
                border-radius: 16px;
            }

            .banners-banner.long-press-triggered {
                border-radius: 12px;
            }

            .gallery-item.long-press-triggered {
                border-radius: 8px;
            }

            /* Добавляем прогресс-бар для показа процесса долгого нажатия */
            .long-press-active::after {
                content: "";
                position: absolute;
                bottom: 0;
                left: 0;
                height: 3px;
                background: linear-gradient(90deg, #007bff, #0056b3);
                border-radius: 0 0 8px 8px;
                animation: longPressProgress 2s linear;
                z-index: 5;
            }

            @keyframes longPressProgress {
                from {
                    width: 0%;
                }
                to {
                    width: 100%;
                }
            }
        `;

        const styleSheet = document.createElement('style');
        styleSheet.textContent = styles;
        document.head.appendChild(styleSheet);
    }
}

// Инициализация при загрузке DOM
document.addEventListener('DOMContentLoaded', () => {
    // Небольшая задержка для уверенности что все элементы загружены
    setTimeout(() => {
        new LongPressEditor();
    }, 100);
});

// Экспорт для возможного использования в других модулях
window.LongPressEditor = LongPressEditor;