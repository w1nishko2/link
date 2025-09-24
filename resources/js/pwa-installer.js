// PWA Install Prompt
class PWAInstaller {
    constructor() {
        this.deferredPrompt = null;
        this.installButton = null;
        this.init();
    }

    init() {
        // Слушаем событие beforeinstallprompt
        window.addEventListener('beforeinstallprompt', (e) => {
            console.log('PWA: beforeinstallprompt событие получено');
            
            // Предотвращаем автоматический показ промпта
            e.preventDefault();
            
            // Сохраняем событие для последующего использования
            this.deferredPrompt = e;
            
            // Показываем кнопку установки
            this.showInstallButton();
        });

        // Слушаем событие установки
        window.addEventListener('appinstalled', (e) => {
            console.log('PWA: приложение установлено');
            this.hideInstallButton();
            this.deferredPrompt = null;
            
            // Можно показать уведомление об успешной установке
            this.showInstallSuccess();
        });

        // Создаем кнопку установки
        this.createInstallButton();
    }

    createInstallButton() {
        // Проверяем, не установлено ли уже приложение
        if (window.matchMedia('(display-mode: standalone)').matches || 
            window.navigator.standalone === true) {
            console.log('PWA: приложение уже установлено');
            return;
        }

        // Создаем кнопку
        this.installButton = document.createElement('button');
        this.installButton.innerHTML = `
            <i class="bi bi-download"></i>
            <span>Установить приложение</span>
        `;
        this.installButton.className = 'pwa-install-button';
        this.installButton.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #2A5885;
            color: white;
            border: none;
            border-radius: 25px;
            padding: 12px 20px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(42, 88, 133, 0.3);
            z-index: 1000;
            display: none;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        `;

        // Добавляем hover эффект
        this.installButton.addEventListener('mouseenter', () => {
            this.installButton.style.transform = 'translateY(-2px)';
            this.installButton.style.boxShadow = '0 6px 20px rgba(42, 88, 133, 0.4)';
        });

        this.installButton.addEventListener('mouseleave', () => {
            this.installButton.style.transform = 'translateY(0)';
            this.installButton.style.boxShadow = '0 4px 12px rgba(42, 88, 133, 0.3)';
        });

        // Обработчик клика
        this.installButton.addEventListener('click', () => {
            this.installPWA();
        });

        // Добавляем кнопку в DOM
        document.body.appendChild(this.installButton);
    }

    showInstallButton() {
        if (this.installButton) {
            this.installButton.style.display = 'flex';
            
            // Анимация появления
            setTimeout(() => {
                this.installButton.style.opacity = '1';
                this.installButton.style.transform = 'translateY(0)';
            }, 100);
        }
    }

    hideInstallButton() {
        if (this.installButton) {
            this.installButton.style.display = 'none';
        }
    }

    async installPWA() {
        if (!this.deferredPrompt) {
            console.log('PWA: нет доступного промпта для установки');
            return;
        }

        // Показываем промпт установки
        this.deferredPrompt.prompt();

        // Ждем ответа пользователя
        const { outcome } = await this.deferredPrompt.userChoice;
        
        console.log(`PWA: пользователь ${outcome === 'accepted' ? 'принял' : 'отклонил'} установку`);

        // Очищаем сохраненный промпт
        this.deferredPrompt = null;

        if (outcome === 'accepted') {
            // Скрываем кнопку после принятия
            this.hideInstallButton();
        }
    }

    showInstallSuccess() {
        // Показываем уведомление об успешной установке
        const notification = document.createElement('div');
        notification.innerHTML = `
            <i class="bi bi-check-circle"></i>
            <span>Приложение успешно установлено!</span>
        `;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            z-index: 1001;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
            animation: slideInFromRight 0.3s ease;
        `;

        // Добавляем CSS анимацию
        if (!document.querySelector('#pwa-animations')) {
            const style = document.createElement('style');
            style.id = 'pwa-animations';
            style.textContent = `
                @keyframes slideInFromRight {
                    0% {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                    100% {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
            `;
            document.head.appendChild(style);
        }

        document.body.appendChild(notification);

        // Убираем уведомление через 3 секунды
        setTimeout(() => {
            notification.style.animation = 'slideInFromRight 0.3s ease reverse';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }

    // Проверка поддержки PWA
    static isPWASupported() {
        return 'serviceWorker' in navigator && 'PushManager' in window;
    }

    // Проверка, установлено ли PWA
    static isPWAInstalled() {
        return window.matchMedia('(display-mode: standalone)').matches || 
               window.navigator.standalone === true;
    }
}

// Инициализация PWA установщика
document.addEventListener('DOMContentLoaded', function() {
    if (PWAInstaller.isPWASupported() && !PWAInstaller.isPWAInstalled()) {
        window.pwaInstaller = new PWAInstaller();
    }
});

// Экспорт для использования в других модулях
window.PWAInstaller = PWAInstaller;