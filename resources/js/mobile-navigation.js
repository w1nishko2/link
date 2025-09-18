/*
============================================
НЕЗАВИСИМЫЙ JAVASCRIPT ДЛЯ МОБИЛЬНОЙ НАВИГАЦИИ
============================================
Этот файл содержит весь JavaScript код для работы 
мобильной навигации независимо от админки
*/

(function() {
    'use strict';
    
    // Переменные для элементов
    let sidebar = null;
    let overlay = null;
    let isInitialized = false;
    
    /**
     * Инициализация мобильной навигации
     */
    function initMobileNavigation() {
        if (isInitialized) return;
        
        // Получаем элементы
        sidebar = document.getElementById('mobileAdminSidebar');
        overlay = document.querySelector('.mobile-sidebar-overlay');
        
        if (!sidebar || !overlay) {
            console.warn('Mobile navigation elements not found');
            return;
        }
        
        // Проверяем и исправляем скролл при инициализации
        checkAndFixScroll();
        
        // Добавляем обработчики событий
        setupEventListeners();
        
        // Настраиваем улучшения для мобильных устройств
        setupMobileEnhancements();
        
        isInitialized = true;
        console.log('Mobile navigation initialized');
    }
    
    /**
     * Проверка и исправление скролла
     */
    function checkAndFixScroll() {
        // Если ширина экрана больше 768px, убираем блокировку скролла
        if (window.innerWidth >= 768) {
            document.body.style.overflow = '';
            document.body.classList.remove('mobile-sidebar-open');
        }
        // На мобильных устройствах проверяем, не заблокирован ли скролл случайно
        else if (window.innerWidth < 768) {
            // Если сайдбар не показан, убираем блокировку скролла
            if (!sidebar || !sidebar.classList.contains('show')) {
                document.body.style.overflow = '';
                document.body.classList.remove('mobile-sidebar-open');
            }
        }
    }
    
    /**
     * Переключение состояния сайдбара
     */
    function toggleMobileSidebar() {
        if (!sidebar || !overlay) {
            console.warn('Mobile navigation not initialized');
            return;
        }
        
        const isOpen = sidebar.classList.contains('show');
        
        if (isOpen) {
            closeMobileSidebar();
        } else {
            openMobileSidebar();
        }
    }
    
    /**
     * Открытие мобильного сайдбара
     */
    function openMobileSidebar() {
        if (!sidebar || !overlay) return;
        
        sidebar.classList.add('show');
        overlay.classList.add('show');
        document.body.classList.add('mobile-sidebar-open');
        document.body.style.overflow = 'hidden';
        
        // Устанавливаем фокус на кнопку закрытия для accessibility
        const closeBtn = sidebar.querySelector('.mobile-menu-btn');
        if (closeBtn) {
            setTimeout(() => closeBtn.focus(), 100);
        }
    }
    
    /**
     * Закрытие мобильного сайдбара
     */
    function closeMobileSidebar() {
        if (!sidebar || !overlay) return;
        
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
        document.body.classList.remove('mobile-sidebar-open');
        document.body.style.overflow = '';
    }
    
    /**
     * Настройка обработчиков событий
     */
    function setupEventListeners() {
        // Закрытие по изменению размера экрана
        window.addEventListener('resize', handleWindowResize);
        
        // Закрытие по клавише Escape
        document.addEventListener('keydown', handleKeyDown);
        
        // Автозакрытие при клике на ссылки навигации
        setupNavLinkHandlers();
        
        // Предотвращение всплытия событий на сайдбаре
        sidebar.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
    
    /**
     * Обработчик изменения размера окна
     */
    function handleWindowResize() {
        if (window.innerWidth >= 768) {
            closeMobileSidebar();
            // Дополнительная проверка - убираем overflow:hidden с body на больших экранах
            document.body.style.overflow = '';
            document.body.classList.remove('mobile-sidebar-open');
        }
    }
    
    /**
     * Обработчик нажатия клавиш
     */
    function handleKeyDown(e) {
        if (e.key === 'Escape' && sidebar && sidebar.classList.contains('show')) {
            closeMobileSidebar();
        }
    }
    
    /**
     * Настройка обработчиков для ссылок навигации
     */
    function setupNavLinkHandlers() {
        if (!sidebar) return;
        
        const navLinks = sidebar.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                // Закрываем меню с небольшой задержкой на мобильных устройствах
                if (window.innerWidth < 768) {
                    setTimeout(closeMobileSidebar, 150);
                }
            });
        });
    }
    
    /**
     * Улучшения для мобильных устройств
     */
    function setupMobileEnhancements() {
        // Предотвращение зума на iOS при фокусе на input
        if (/iPad|iPhone|iPod/.test(navigator.userAgent)) {
            const viewport = document.querySelector('meta[name=viewport]');
            if (viewport) {
                viewport.setAttribute('content', 'width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no');
            }
        }
        
        // Улучшение производительности для touch устройств
        if ('ontouchstart' in window) {
            document.body.addEventListener('touchstart', function() {}, { passive: true });
        }
    }
    
    /**
     * Проверка состояния сайдбара
     */
    function isSidebarOpen() {
        return sidebar && sidebar.classList.contains('show');
    }
    
    /**
     * Публичный API
     */
    window.MobileNavigation = {
        init: initMobileNavigation,
        toggle: toggleMobileSidebar,
        open: openMobileSidebar,
        close: closeMobileSidebar,
        isOpen: isSidebarOpen
    };
    
    // Глобальные функции для обратной совместимости
    window.toggleMobileSidebar = toggleMobileSidebar;
    window.closeMobileSidebar = closeMobileSidebar;
    window.openMobileSidebar = openMobileSidebar;
    
    // Автоинициализация при загрузке DOM
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMobileNavigation);
    } else {
        initMobileNavigation();
    }
    
    // Инициализация при динамической загрузке
    if (typeof window.addEventListener === 'function') {
        window.addEventListener('load', function() {
            // Повторная инициализация на случай если элементы добавились позже
            setTimeout(initMobileNavigation, 100);
            
            // Дополнительная проверка скролла через 500мс после загрузки
            setTimeout(function() {
                // Принудительно убираем блокировку скролла для всех устройств
                document.body.style.overflow = '';
                document.body.classList.remove('mobile-sidebar-open');
                
                // Дополнительная проверка для владельцев страниц
                if (document.body.classList.contains('owner-mode')) {
                    document.body.style.overflow = '';
                    document.body.style.position = '';
                    document.body.style.width = '';
                }
            }, 500);
        });
    }
    
})();

/*
============================================
ДОПОЛНИТЕЛЬНЫЕ УТИЛИТЫ
============================================
*/

/**
 * Утилита для безопасного выполнения функций
 */
function safeMobileNavCall(fn, ...args) {
    try {
        if (typeof fn === 'function') {
            return fn.apply(this, args);
        }
    } catch (error) {
        console.warn('Mobile navigation error:', error);
    }
}

/**
 * Проверка поддержки touch событий
 */
function isTouchDevice() {
    return 'ontouchstart' in window || navigator.maxTouchPoints > 0 || navigator.msMaxTouchPoints > 0;
}

/**
 * Debounce функция для оптимизации
 */
function debounce(func, wait, immediate) {
    let timeout;
    return function executedFunction() {
        const context = this;
        const args = arguments;
        const later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        const callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
}