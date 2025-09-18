// Миниредактор фотографий для обрезки изображений
class PhotoEditor {
    constructor() {
        this.modal = null;
        this.image = null;
        this.canvas = null;
        this.ctx = null;
        this.currentFormat = null;
        this.currentStep = 1;
        this.maxSteps = 2;
        this.editType = null; // 'hero' или 'avatar'
        
        // Состояние изображения
        this.imageState = {
            x: 0,
            y: 0,
            scale: 1,
            rotation: 0,
            isDragging: false,
            isResizing: false,
            dragStart: { x: 0, y: 0 },
            resizeHandle: null,
            // Touch состояние для pinch-to-zoom
            touches: [],
            initialDistance: 0,
            initialScale: 1,
            isPinching: false,
            pinchCenter: { x: 0, y: 0 }
        };

        // Форматы для обрезки (увеличенные размеры для лучшего качества)
        this.formats = {
            reel: { width: 540, height: 960, ratio: 9/16, name: 'Рилс (9:16)', outputWidth: 1080, outputHeight: 1920 },
            desktop: { width: 800, height: 450, ratio: 16/9, name: 'Десктоп (16:9)', outputWidth: 1920, outputHeight: 1080 },
            square: { width: 600, height: 600, ratio: 1, name: 'Квадрат (1:1)', outputWidth: 1080, outputHeight: 1080 }
        };

        this.init();
    }

    init() {
        console.log('PhotoEditor: Initialized without creating modal (lazy loading)');
          }

    createModal() {
        const modalHTML = `
            <div class="photo-editor-modal" id="photoEditorModal">
                <div class="photo-editor-content">
                    <div class="photo-editor-header">
                        <h2 class="photo-editor-title" id="editorTitle">Редактор изображений</h2>
                        <button class="photo-editor-close" id="closeEditor">&times;</button>
                    </div>
                    
                    <div class="photo-editor-steps" id="editorSteps" style="display: none;">
                        <div class="step-indicator" id="step1">
                            <span class="step-number">1</span>
                            <span>Рилс (9:16)</span>
                        </div>
                        <div class="step-indicator" id="step2">
                            <span class="step-number">2</span>
                            <span>Десктоп (16:9)</span>
                        </div>
                    </div>
                    
                    <div class="photo-editor-body">
                        <div class="editor-toolbar">
                            <div class="toolbar-section">
                               
                                <div class="file-input-wrapper">
                                    <input type="file" id="imageInput" class="file-input" accept="image/*">
                                    <label for="imageInput" class="file-input-button">
                                        Выбрать файл
                                    </label>
                                </div>
                            </div>
                            
                            <div class="toolbar-section">
                                <div class="action-buttons">
                                    <button class="action-button secondary" id="resetButton">Сбросить</button>
                                    <button class="action-button primary" id="cropButton" disabled>
                                        <span id="cropButtonText">Обрезать</span>
                                    </button>
                                    <button class="action-button primary" id="nextStepButton" style="display: none;">
                                        Следующий этап
                                    </button>
                                    <button class="action-button primary" id="saveButton" style="display: none;">
                                        Сохранить
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="editor-canvas-area">
                            <div class="format-container" id="formatContainer">
                                <canvas id="editorCanvas" style="display: none;"></canvas>
                                <img id="editorImage" class="editor-image" style="display: none;">
                                
                                <div class="resize-handles" id="resizeHandles" style="display: none;">
                                    <div class="resize-handle nw" data-handle="nw"></div>
                                    <div class="resize-handle ne" data-handle="ne"></div>
                                    <div class="resize-handle sw" data-handle="sw"></div>
                                    <div class="resize-handle se" data-handle="se"></div>

                                </div>
                            </div>
                            
                        </div>
                    </div>
                    
                    <div class="editor-loader" id="editorLoader">
                        <div class="loader-spinner"></div>
                        <div>Обработка изображения...</div>
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', modalHTML);
        this.modal = document.getElementById('photoEditorModal');
    }

    bindEvents() {
        // Кнопка закрытия
        document.getElementById('closeEditor').addEventListener('click', () => this.close());
        
        // Клик по фону модального окна
        this.modal.addEventListener('click', (e) => {
            if (e.target === this.modal) this.close();
        });
        
        // Загрузка файла
        document.getElementById('imageInput').addEventListener('change', (e) => this.loadImage(e));
        
        // Кнопки действий
        document.getElementById('resetButton').addEventListener('click', () => this.resetImage());
        document.getElementById('cropButton').addEventListener('click', () => this.cropImage());
        document.getElementById('nextStepButton').addEventListener('click', () => this.nextStep());
        document.getElementById('saveButton').addEventListener('click', () => this.saveImages());
        
        // НЕ привязываем touch события и клавиатурные сокращения здесь - они будут привязаны только при открытии редактора
    }

    bindImageEvents() {
        const image = document.getElementById('editorImage');
        const container = document.getElementById('formatContainer');
        
        if (!image || !container) {
            return;
        }
        
        // Сохраняем ссылки на функции для последующей отвязки
        this.imageEventHandlers = {
            mousedown: (e) => this.startDrag(e),
            mousemove: (e) => this.drag(e),
            mouseup: () => this.endDrag(),
            touchstart: (e) => this.handleTouchStart(e),
            touchmove: (e) => this.handleTouchMove(e),
            touchend: (e) => this.handleTouchEnd(e),
            wheel: (e) => this.handleWheel(e)
        };
        
        // События мыши для ПК
        image.addEventListener('mousedown', this.imageEventHandlers.mousedown);
        document.addEventListener('mousemove', this.imageEventHandlers.mousemove);
        document.addEventListener('mouseup', this.imageEventHandlers.mouseup);
        
        // Touch события только для контейнера изображения - с passive: true для лучшей производительности
        // preventDefault будет вызываться только при необходимости внутри обработчиков
        container.addEventListener('touchstart', this.imageEventHandlers.touchstart, { passive: true });
        container.addEventListener('touchmove', this.imageEventHandlers.touchmove, { passive: true });
        container.addEventListener('touchend', this.imageEventHandlers.touchend, { passive: true });
        
        // Обработка колеса мыши для масштабирования - passive false нужен для preventDefault на wheel
        container.addEventListener('wheel', this.imageEventHandlers.wheel, { passive: false });
        
        // Обработка ресайз-хэндлов
        this.bindResizeHandles();
    }

    unbindImageEvents() {
        const image = document.getElementById('editorImage');
        const container = document.getElementById('formatContainer');
        
        if (!this.imageEventHandlers) {
            return;
        }
        
        if (image) {
            image.removeEventListener('mousedown', this.imageEventHandlers.mousedown);
        }
        
        if (container) {
            container.removeEventListener('touchstart', this.imageEventHandlers.touchstart);
            container.removeEventListener('touchmove', this.imageEventHandlers.touchmove);
            container.removeEventListener('touchend', this.imageEventHandlers.touchend);
            container.removeEventListener('wheel', this.imageEventHandlers.wheel);
        }
        
        document.removeEventListener('mousemove', this.imageEventHandlers.mousemove);
        document.removeEventListener('mouseup', this.imageEventHandlers.mouseup);
        
        // Отвязываем также resize обработчики
        this.unbindResizeHandles();
        
        // Очищаем обработчики
        this.imageEventHandlers = null;
    }

    bindResizeHandles() {
        // Пропускаем привязку resize-handles на touch устройствах
        if (window.matchMedia('(pointer: coarse)').matches || window.innerWidth <= 768) {
            return;
        }
        
        // Сохраняем ссылки на обработчики для resize handles
        this.resizeEventHandlers = {
            mousemove: (e) => this.resize(e),
            mouseup: () => this.endResize(),
            touchend: () => this.endResize()
        };
        
        const handles = document.querySelectorAll('.resize-handle');
        handles.forEach(handle => {
            handle.addEventListener('mousedown', (e) => this.startResize(e));
        });
        
        document.addEventListener('mousemove', this.resizeEventHandlers.mousemove);
        document.addEventListener('mouseup', this.resizeEventHandlers.mouseup);
        document.addEventListener('touchend', this.resizeEventHandlers.touchend);
    }

    unbindResizeHandles() {
        if (!this.resizeEventHandlers) {
            return;
        }
        
        document.removeEventListener('mousemove', this.resizeEventHandlers.mousemove);
        document.removeEventListener('mouseup', this.resizeEventHandlers.mouseup);
        document.removeEventListener('touchend', this.resizeEventHandlers.touchend);
        
        this.resizeEventHandlers = null;
    }

    open(type) {
        // Создаем модальное окно только при первом открытии (lazy loading)
        if (!this.modal) {
            console.log('PhotoEditor: Creating modal on first open');
            this.createModal();
            this.bindEvents();
        }
        
        this.editType = type;
        this.currentStep = 1;
        
        // Настройка заголовка и шагов
        const title = document.getElementById('editorTitle');
        const steps = document.getElementById('editorSteps');
        
        if (type === 'hero') {
            title.textContent = 'Редактор фона (Hero)';
            steps.style.display = 'flex';
            this.maxSteps = 2;
            this.setFormat('reel');
        } else if (type === 'avatar') {
            title.textContent = 'Редактор аватара';
            steps.style.display = 'none';
            this.maxSteps = 1;
            this.setFormat('square');
        }
        
        this.updateStepIndicators();
        this.modal.classList.add('show');
        
        // Привязываем touch события только при открытии редактора
        this.bindImageEvents();
        
        // Добавляем свайп для закрытия только при открытии редактора
        this.addModalSwipeToClose();
        
        // Привязываем клавиатурные сокращения только при открытии
        this.keyboardHandler = (e) => this.handleKeyboard(e);
        document.addEventListener('keydown', this.keyboardHandler);
        
        // НЕ блокируем скролл на мобильных устройствах для лучшего UX
        // Пользователь сможет скроллить страницу и закрывать модальное окно
        // Блокировка touch действий происходит только внутри области редактирования
    }

    close() {
        if (!this.modal) {
            console.log('PhotoEditor: Modal not created yet, nothing to close');
            return;
        }
        
        this.modal.classList.remove('show');
        // Всегда восстанавливаем скролл
        document.body.style.overflow = '';
        
        // ВАЖНО: Отвязываем все touch события при закрытии редактора
        this.unbindImageEvents();
        
        // Отвязываем свайп обработчики
        this.removeModalSwipeToClose();
        
        // Отвязываем клавиатурные события
        if (this.keyboardHandler) {
            document.removeEventListener('keydown', this.keyboardHandler);
            this.keyboardHandler = null;
        }
        
        this.resetEditor();
    }

    setFormat(formatKey) {
        this.currentFormat = formatKey;
        const container = document.getElementById('formatContainer');
        const format = this.formats[formatKey];
        
        // Удаляем предыдущие классы формата
        container.classList.remove('reel', 'desktop', 'square');
        container.classList.add(formatKey);
        
        // Обновляем размеры контейнера
        container.style.width = format.width + 'px';
        container.style.height = format.height + 'px';
        
        // Если есть изображение, обновляем его отображение
        if (this.image) {
            this.updateImageDisplay();
        }
    }

    loadImage(event) {
        const file = event.target.files[0];
        if (!file) return;
        
        // Проверка типа файла
        if (!file.type.startsWith('image/')) {
            alert('Пожалуйста, выберите файл изображения');
            return;
        }
        
        // Проверка размера файла (максимум 10MB)
        if (file.size > 10 * 1024 * 1024) {
            alert('Размер файла не должен превышать 10MB');
            return;
        }
        
        const reader = new FileReader();
        reader.onload = (e) => {
            this.image = new Image();
            this.image.onload = () => {
                this.setupImage();
            };
            this.image.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    setupImage() {
        const imageElement = document.getElementById('editorImage');
        const container = document.getElementById('formatContainer');
        const handles = document.getElementById('resizeHandles');
        
        imageElement.src = this.image.src;
        imageElement.style.display = 'block';
        handles.style.display = 'block';
        container.classList.add('has-image');
        
        // Сброс состояния
        this.resetImageState();
        
        // Автоматическое масштабирование для вписывания в контейнер
        this.autoFitImage();
        
        // Активация кнопки обрезки
        document.getElementById('cropButton').disabled = false;
        
        // Обновление подсказки (элемент может не существовать в HTML)
        const hint = document.querySelector('.editor-hint');
        if (hint) {
            hint.textContent = 'Перетащите и масштабируйте изображение';
        }
    }

    autoFitImage() {
        const format = this.formats[this.currentFormat];
        const scaleX = format.width / this.image.width;
        const scaleY = format.height / this.image.height;
        const scale = Math.max(scaleX, scaleY);
        
        this.imageState.scale = scale;
        this.imageState.x = 0;
        this.imageState.y = 0;
        this.imageState.rotation = 0;
        
        this.updateImageDisplay();
    }

    resetImageState() {
        this.imageState = {
            x: 0,
            y: 0,
            scale: 1,
            rotation: 0,
            isDragging: false,
            isResizing: false,
            dragStart: { x: 0, y: 0 },
            resizeHandle: null,
            // Touch состояние для pinch-to-zoom
            touches: [],
            initialDistance: 0,
            initialScale: 1,
            isPinching: false,
            pinchCenter: { x: 0, y: 0 }
        };
    }

    updateImageDisplay() {
        const imageElement = document.getElementById('editorImage');
        const transform = `translate(${this.imageState.x}px, ${this.imageState.y}px) 
                          scale(${this.imageState.scale}) 
                          rotate(${this.imageState.rotation}deg)`;
        
        imageElement.style.transform = transform;
        imageElement.style.width = this.image.width + 'px';
        imageElement.style.height = this.image.height + 'px';
    }

    startDrag(event) {
        if (this.imageState.isResizing) return;
        
        event.preventDefault();
        this.imageState.isDragging = true;
        
        const clientX = event.touches ? event.touches[0].clientX : event.clientX;
        const clientY = event.touches ? event.touches[0].clientY : event.clientY;
        
        this.imageState.dragStart = {
            x: clientX - this.imageState.x,
            y: clientY - this.imageState.y
        };
        
        document.getElementById('editorImage').classList.add('dragging');
    }

    drag(event) {
        if (!this.imageState.isDragging || this.imageState.isResizing) return;
        
        event.preventDefault();
        
        const clientX = event.touches ? event.touches[0].clientX : event.clientX;
        const clientY = event.touches ? event.touches[0].clientY : event.clientY;
        
        this.imageState.x = clientX - this.imageState.dragStart.x;
        this.imageState.y = clientY - this.imageState.dragStart.y;
        
        this.updateImageDisplay();
    }

    endDrag() {
        this.imageState.isDragging = false;
        document.getElementById('editorImage').classList.remove('dragging');
    }

    startResize(event) {
        event.preventDefault();
        event.stopPropagation();
        
        this.imageState.isResizing = true;
        this.imageState.resizeHandle = event.target.dataset.handle;
        
        const clientX = event.touches ? event.touches[0].clientX : event.clientX;
        const clientY = event.touches ? event.touches[0].clientY : event.clientY;
        
        this.imageState.dragStart = { x: clientX, y: clientY };
        this.imageState.startScale = this.imageState.scale;
    }

    resize(event) {
        if (!this.imageState.isResizing) return;
        
        event.preventDefault();
        
        const clientX = event.touches ? event.touches[0].clientX : event.clientX;
        const clientY = event.touches ? event.touches[0].clientY : event.clientY;
        
        const deltaX = clientX - this.imageState.dragStart.x;
        const deltaY = clientY - this.imageState.dragStart.y;
        const delta = Math.sqrt(deltaX * deltaX + deltaY * deltaY);
        
        // Определяем направление изменения масштаба
        const handle = this.imageState.resizeHandle;
        let scaleFactor = 1;
        
        if (handle === 'se' || handle === 'nw') {
            scaleFactor = (deltaX + deltaY) > 0 ? 1.01 : 0.99;
        } else {
            scaleFactor = (deltaX - deltaY) > 0 ? 1.01 : 0.99;
        }
        
        const newScale = Math.max(0.1, Math.min(3, this.imageState.startScale * Math.pow(scaleFactor, delta / 10)));
        this.imageState.scale = newScale;
        
        this.updateImageDisplay();
    }

    endResize() {
        this.imageState.isResizing = false;
        this.imageState.resizeHandle = null;
    }

    handleWheel(event) {
        if (!this.image) return;
        
        event.preventDefault();
        
        const delta = event.deltaY > 0 ? -0.1 : 0.1;
        const newScale = Math.max(0.1, Math.min(3, this.imageState.scale + delta));
        
        this.imageState.scale = newScale;
        this.updateImageDisplay();
    }

    handleKeyboard(event) {
        if (!this.modal || !this.modal.classList.contains('show') || !this.image) return;
        
        const step = event.shiftKey ? 10 : 1;
        
        switch (event.key) {
            case 'ArrowLeft':
                event.preventDefault();
                this.imageState.x -= step;
                break;
            case 'ArrowRight':
                event.preventDefault();
                this.imageState.x += step;
                break;
            case 'ArrowUp':
                event.preventDefault();
                this.imageState.y -= step;
                break;
            case 'ArrowDown':
                event.preventDefault();
                this.imageState.y += step;
                break;
            case 'Escape':
                this.close();
                return;
            default:
                return;
        }
        
        this.updateImageDisplay();
    }

    // Touch события для мобильных устройств
    handleTouchStart(event) {
        // Проверяем, что событие происходит внутри контейнера формата
        const container = document.getElementById('formatContainer');
        if (!container || (!container.contains(event.target) && event.target !== container)) {
            // Если касание происходит вне области редактора - не обрабатываем его
            return;
        }
        
        // Только если есть изображение, обрабатываем touch события
        if (!this.image) {
            return;
        }
        
        // НЕ вызываем preventDefault для touch событий, чтобы разрешить скролл страницы
        // Пользователь может скроллить страницу даже при открытом редакторе
        
        const touches = Array.from(event.touches);
        this.imageState.touches = touches;

        if (touches.length === 1) {
            // Одно касание - перетаскивание
            const touch = touches[0];
            this.imageState.isDragging = true;
            this.imageState.dragStart = {
                x: touch.clientX - this.imageState.x,
                y: touch.clientY - this.imageState.y
            };
        } else if (touches.length === 2) {
            // Два касания - масштабирование
            this.imageState.isPinching = true;
            this.imageState.isDragging = false;
            
            // Вычисляем начальное расстояние между пальцами
            this.imageState.initialDistance = this.getTouchDistance(touches[0], touches[1]);
            this.imageState.initialScale = this.imageState.scale;
            
            // Вычисляем центр между пальцами
            this.imageState.pinchCenter = this.getTouchCenter(touches[0], touches[1]);
        }
    }

    handleTouchMove(event) {
        // Обрабатываем touch events только если активно взаимодействие с изображением
        if (!this.imageState.isDragging && !this.imageState.isPinching) {
            return;
        }
        
        // Проверяем, что мы работаем с нашим контейнером
        const container = document.getElementById('formatContainer');
        if (!container || !this.image) {
            return;
        }
        
        // НЕ вызываем preventDefault для лучшего UX - пользователь может скроллить страницу
        
        const touches = Array.from(event.touches);
        
        if (touches.length === 1 && this.imageState.isDragging && !this.imageState.isPinching) {
            // Одно касание - перетаскивание
            const touch = touches[0];
            this.imageState.x = touch.clientX - this.imageState.dragStart.x;
            this.imageState.y = touch.clientY - this.imageState.dragStart.y;
            this.updateImageDisplay();
        } else if (touches.length === 2 && this.imageState.isPinching) {
            // Два касания - масштабирование
            const currentDistance = this.getTouchDistance(touches[0], touches[1]);
            const scaleChange = currentDistance / this.imageState.initialDistance;
            
            // Ограничиваем масштаб
            const newScale = Math.max(0.1, Math.min(5, this.imageState.initialScale * scaleChange));
            
            if (newScale !== this.imageState.scale) {
                // Масштабируем относительно центра между пальцами
                const center = this.getTouchCenter(touches[0], touches[1]);
                const container = document.getElementById('formatContainer');
                const containerRect = container.getBoundingClientRect();
                
                // Переводим координаты центра в локальные координаты контейнера
                const localCenterX = center.x - containerRect.left;
                const localCenterY = center.y - containerRect.top;
                
                // Сохраняем старые значения
                const oldScale = this.imageState.scale;
                const scaleDiff = newScale / oldScale;
                
                // Обновляем позицию изображения для масштабирования от центра
                this.imageState.x = localCenterX - (localCenterX - this.imageState.x) * scaleDiff;
                this.imageState.y = localCenterY - (localCenterY - this.imageState.y) * scaleDiff;
                this.imageState.scale = newScale;
                
                this.updateImageDisplay();
            }
        }
    }

    handleTouchEnd(event) {
        // НЕ предотвращаем события для лучшего UX - разрешаем скролл страницы
        
        if (event.touches.length === 0) {
            // Все пальцы убраны
            this.imageState.isDragging = false;
            this.imageState.isPinching = false;
            this.imageState.touches = [];
        } else if (event.touches.length === 1 && this.imageState.isPinching) {
            // Остался один палец после масштабирования
            this.imageState.isPinching = false;
            
            // Переключаемся на перетаскивание
            const touch = event.touches[0];
            this.imageState.isDragging = true;
            this.imageState.dragStart = {
                x: touch.clientX - this.imageState.x,
                y: touch.clientY - this.imageState.y
            };
        }
    }

    // Вспомогательные методы для touch событий
    getTouchDistance(touch1, touch2) {
        const dx = touch2.clientX - touch1.clientX;
        const dy = touch2.clientY - touch1.clientY;
        return Math.sqrt(dx * dx + dy * dy);
    }

    getTouchCenter(touch1, touch2) {
        return {
            x: (touch1.clientX + touch2.clientX) / 2,
            y: (touch1.clientY + touch2.clientY) / 2
        };
    }

    resetImage() {
        if (!this.image) return;
        
        this.autoFitImage();
    }

    cropImage() {
        if (!this.image) return;
        
        this.showLoader();
        
        setTimeout(() => {
            const canvas = this.createCroppedCanvas();
            
            // Улучшенное сжатие: высокое качество для desktop формата
            let quality = 0.9; // Высокое качество по умолчанию
            if (this.currentFormat === 'desktop') {
                quality = 0.92; // Максимальное качество для фона ПК
            } else if (this.currentFormat === 'reel') {
                quality = 0.88; // Хорошее качество для рилсов
            }
            
            const croppedDataUrl = canvas.toDataURL('image/webp', quality);
            
            if (this.editType === 'hero' && this.currentStep < this.maxSteps) {
                // Сохраняем результат первого этапа и переходим ко второму
                this.saveStepResult(this.currentStep, croppedDataUrl);
                this.nextStep();
            } else {
                // Финальное сохранение
                this.saveStepResult(this.currentStep, croppedDataUrl);
                this.showSaveButton();
            }
            
            this.hideLoader();
        }, 500);
    }

    createCroppedCanvas() {
        const format = this.formats[this.currentFormat];
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        
        // Используем выходные размеры для высокого качества
        const outputWidth = format.outputWidth || format.width;
        const outputHeight = format.outputHeight || format.height;
        
        canvas.width = outputWidth;
        canvas.height = outputHeight;
        
        // Включаем сглаживание для лучшего качества
        ctx.imageSmoothingEnabled = true;
        ctx.imageSmoothingQuality = 'high';
        
        // Рассчитываем коэффициент масштабирования для выходного размера
        const scaleFactorX = outputWidth / format.width;
        const scaleFactorY = outputHeight / format.height;
        
        // Сохраняем контекст
        ctx.save();
        
        // Применяем трансформации с учетом масштабирования
        ctx.translate(outputWidth / 2, outputHeight / 2);
        ctx.translate(this.imageState.x * scaleFactorX, this.imageState.y * scaleFactorY);
        ctx.scale(this.imageState.scale * scaleFactorX, this.imageState.scale * scaleFactorY);
        ctx.rotate(this.imageState.rotation * Math.PI / 180);
        
        // Рисуем изображение
        ctx.drawImage(this.image, -this.image.width / 2, -this.image.height / 2);
        
        // Восстанавливаем контекст
        ctx.restore();
        
        return canvas;
    }

    nextStep() {
        if (this.currentStep >= this.maxSteps) return;
        
        this.currentStep++;
        
        if (this.editType === 'hero' && this.currentStep === 2) {
            this.setFormat('desktop');
            this.updateStepIndicators();
            
            // Сброс изображения для нового формата
            if (this.image) {
                this.autoFitImage();
            }
            
            // Обновляем кнопки
            document.getElementById('nextStepButton').style.display = 'none';
            document.getElementById('cropButton').style.display = 'block';
            document.getElementById('cropButtonText').textContent = 'Обрезать';
        }
    }

    updateStepIndicators() {
        if (this.editType !== 'hero') return;
        
        for (let i = 1; i <= this.maxSteps; i++) {
            const stepElement = document.getElementById(`step${i}`);
            if (stepElement) {
                stepElement.classList.toggle('active', i === this.currentStep);
            }
        }
    }

    saveStepResult(step, dataUrl) {
        // Сохраняем результат в локальном хранилище для последующей отправки
        const results = JSON.parse(localStorage.getItem('photoEditorResults') || '{}');
        
        if (this.editType === 'hero') {
            if (!results.hero) results.hero = {};
            results.hero[step === 1 ? 'reel' : 'desktop'] = dataUrl;
        } else if (this.editType === 'avatar') {
            results.avatar = dataUrl;
        }
        
        localStorage.setItem('photoEditorResults', JSON.stringify(results));
    }

    showSaveButton() {
        document.getElementById('cropButton').style.display = 'none';
        document.getElementById('saveButton').style.display = 'block';
    }

    async saveImages() {
        const results = JSON.parse(localStorage.getItem('photoEditorResults') || '{}');
        
        console.log('PhotoEditor: Saving images...', { type: this.editType, results });
        
        if (Object.keys(results).length === 0) {
            alert('Нет данных для сохранения');
            return;
        }
        
        // Проверяем наличие CSRF токена
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            alert('❌ Ошибка: пользователь не авторизован');
            return;
        }
        
        this.showLoader();
        
        try {
            const formData = new FormData();
            formData.append('_token', csrfToken.getAttribute('content'));
            formData.append('type', this.editType);
            
            // Конвертируем base64 в blob и добавляем в FormData
            for (const [key, dataUrl] of Object.entries(results)) {
                if (typeof dataUrl === 'object') {
                    // Для hero изображений - используем правильные имена параметров
                    for (const [format, data] of Object.entries(dataUrl)) {
                        const blob = this.dataURLtoBlob(data);
                        // Маппинг форматов для соответствия с бэкендом
                        let paramName = key;
                        if (format === 'reel') {
                            paramName = 'hero_reel';
                        } else if (format === 'desktop') {
                            paramName = 'hero_desktop';
                        }
                        formData.append(paramName, blob, `${paramName}.webp`);
                    }
                } else {
                    // Для avatar
                    const blob = this.dataURLtoBlob(dataUrl);
                    formData.append(key, blob, `${key}.webp`);
                }
            }
            
            const response = await fetch('/photo-editor/save', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            // Проверяем статус ответа
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                console.error('Received non-JSON response:', text);
                throw new Error('Сервер вернул некорректный ответ');
            }
            
            const result = await response.json();
            
            console.log('PhotoEditor: Server response:', result);
            
            if (result.success) {
                // Обновляем интерфейс
                this.updateUIAfterSave(result.data);
                
                // Очищаем временные данные
                localStorage.removeItem('photoEditorResults');
                
                let successMessage = '✅ Изображения успешно сохранены!';
                if (this.editType === 'hero') {
                    successMessage += '\n🖼️ Фон обновлен для ПК и мобильных устройств.';
                } else if (this.editType === 'avatar') {
                    successMessage += '\n👤 Аватар обновлен.';
                }
                
                alert(successMessage);
                this.close();
                
                // Небольшая задержка для перезагрузки страницы, чтобы изменения точно применились
                setTimeout(() => {
                    if (confirm('Перезагрузить страницу для полного применения изменений?')) {
                        window.location.reload();
                    }
                }, 1000);
            } else {
                throw new Error(result.message || 'Ошибка сохранения');
            }
        } catch (error) {
            console.error('Ошибка сохранения:', error);
            
            let errorMessage = 'Неизвестная ошибка';
            if (error.message.includes('HTTP error!')) {
                errorMessage = 'Ошибка сервера. Проверьте авторизацию и попробуйте снова.';
            } else if (error.message.includes('некорректный ответ')) {
                errorMessage = 'Сервер вернул некорректный ответ. Возможно, проблема с маршрутизацией.';
            } else {
                errorMessage = error.message;
            }
            
            alert('❌ Ошибка при сохранении изображений: ' + errorMessage);
        } finally {
            this.hideLoader();
        }
    }

    dataURLtoBlob(dataURL) {
        const arr = dataURL.split(',');
        const mime = arr[0].match(/:(.*?);/)[1];
        const bstr = atob(arr[1]);
        let n = bstr.length;
        const u8arr = new Uint8Array(n);
        
        while (n--) {
            u8arr[n] = bstr.charCodeAt(n);
        }
        
        return new Blob([u8arr], { type: mime });
    }

    updateUIAfterSave(data) {
        if (this.editType === 'hero') {
            // Обновляем фон hero секции
            const heroSection = document.querySelector('.hero');
            if (heroSection && (data.hero_desktop || data.hero_reel)) {
                console.log('PhotoEditor: Updating hero background...', data);
                
                // Удаляем старые динамические стили
                const oldStyles = document.querySelectorAll('style[data-photo-editor="hero"]');
                oldStyles.forEach(style => style.remove());
                
                // Создаем новые стили
                const style = document.createElement('style');
                style.setAttribute('data-photo-editor', 'hero');
                
                const desktopImage = data.hero_desktop || data.hero_reel;
                const mobileImage = data.hero_reel || data.hero_desktop;
                
                style.textContent = `
                    .hero {
                        background-image: url('${desktopImage}?t=${Date.now()}') !important;
                    }
                    
                    @media (max-width: 768px) {
                        .hero {
                            background-image: url('${mobileImage}?t=${Date.now()}') !important;
                        }
                    }
                `;
                document.head.appendChild(style);
                
                // Принудительно перерисовываем фон с cache-busting
                heroSection.style.backgroundImage = `url('${desktopImage}?t=${Date.now()}')`;
                
                // Обновляем встроенные стили в секции hero, если они есть
                const heroStyles = document.querySelector('.hero + style, .hero style, section.hero + style');
                if (heroStyles) {
                    console.log('PhotoEditor: Updating inline hero styles');
                    const oldText = heroStyles.textContent;
                    heroStyles.textContent = oldText.replace(
                        /background-image:\s*url\([^)]+\)/g,
                        `background-image: url('${desktopImage}?t=${Date.now()}')`
                    );
                }
                
                console.log('PhotoEditor: Hero background updated successfully');
            } else {
                console.warn('PhotoEditor: Hero section not found or no images provided');
            }
        } else if (this.editType === 'avatar') {
            console.log('PhotoEditor: Updating avatar...', data);
            
            // Обновляем аватар - ищем все возможные селекторы
            const avatarSelectors = [
                '.hero-logo img',
                '.hero-logo x-optimized-image img', 
                '.avatar img',
                '.user-avatar img',
                'img[class*="avatar"]',
                'img[class*="hero-logo"]',
                '.hero .hero-logo img'  // Более специфичный селектор
            ];
            
            let avatarUpdated = false;
            for (const selector of avatarSelectors) {
                const avatarImg = document.querySelector(selector);
                console.log(`PhotoEditor: Checking selector "${selector}":`, avatarImg);
                if (avatarImg && data.avatar) {
                    const oldSrc = avatarImg.src;
                    avatarImg.src = data.avatar + '?t=' + Date.now();
                    avatarUpdated = true;
                    console.log('PhotoEditor: Avatar updated!', {
                        selector: selector,
                        oldSrc: oldSrc,
                        newSrc: avatarImg.src
                    });
                    break;
                }
            }
            
            if (!avatarUpdated) {
                console.warn('PhotoEditor: Avatar element not found!');
                console.log('PhotoEditor: Available selectors searched:', avatarSelectors);
                console.log('PhotoEditor: All images on page:', document.querySelectorAll('img'));
                console.log('PhotoEditor: .hero-logo element:', document.querySelector('.hero-logo'));
            }
        }
    }

    showLoader() {
        document.getElementById('editorLoader').classList.add('show');
    }

    hideLoader() {
        document.getElementById('editorLoader').classList.remove('show');
    }

    // Добавляем обработку свайпа для закрытия модального окна
    addModalSwipeToClose() {
        if (!this.modal) {
            console.log('PhotoEditor: Modal not created, cannot add swipe handlers');
            return;
        }
        
        let startY = 0;
        let startTime = 0;
        let isSwipeToClose = false;

        // Сохраняем ссылки на обработчики для последующей отвязки
        this.swipeHandlers = {
            touchstart: (e) => {
                // Свайп для закрытия работает только если касание началось вне области редактора
                const container = document.getElementById('formatContainer');
                if (container && (container.contains(e.target) || e.target === container)) {
                    return;
                }
                
                startY = e.touches[0].clientY;
                startTime = Date.now();
                isSwipeToClose = true;
            },
            touchmove: (e) => {
                if (!isSwipeToClose) return;
                
                const currentY = e.touches[0].clientY;
                const deltaY = currentY - startY;
                
                // Если свайп вверх или слишком малый - отменяем
                if (deltaY < 50) {
                    isSwipeToClose = false;
                }
            },
            touchend: (e) => {
                if (!isSwipeToClose) return;
                
                const endY = e.changedTouches[0].clientY;
                const deltaY = endY - startY;
                const deltaTime = Date.now() - startTime;
                
                // Если свайп вниз больше 100px или быстрый свайп вниз - закрываем
                if (deltaY > 100 || (deltaY > 50 && deltaTime < 300)) {
                    this.close();
                }
                
                isSwipeToClose = false;
            }
        };

        this.modal.addEventListener('touchstart', this.swipeHandlers.touchstart, { passive: true });
        this.modal.addEventListener('touchmove', this.swipeHandlers.touchmove, { passive: true });
        this.modal.addEventListener('touchend', this.swipeHandlers.touchend, { passive: true });
    }

    removeModalSwipeToClose() {
        if (!this.swipeHandlers || !this.modal) {
            return;
        }

        this.modal.removeEventListener('touchstart', this.swipeHandlers.touchstart);
        this.modal.removeEventListener('touchmove', this.swipeHandlers.touchmove);
        this.modal.removeEventListener('touchend', this.swipeHandlers.touchend);
        
        this.swipeHandlers = null;
    }

    resetEditor() {
        // Сброс всех состояний
        this.image = null;
        this.currentFormat = null;
        this.currentStep = 1;
        this.editType = null;
        
        // Очистка UI
        const imageElement = document.getElementById('editorImage');
        const container = document.getElementById('formatContainer');
        const handles = document.getElementById('resizeHandles');
        const fileInput = document.getElementById('imageInput');
        
        if (imageElement) {
            imageElement.style.display = 'none';
            imageElement.src = '';
        }
        
        if (handles) {
            handles.style.display = 'none';
        }
        
        if (container) {
            container.classList.remove('has-image', 'reel', 'desktop', 'square');
        }
        
        if (fileInput) {
            fileInput.value = '';
        }
        
        // Сброс кнопок
        const cropButton = document.getElementById('cropButton');
        const nextStepButton = document.getElementById('nextStepButton');
        const saveButton = document.getElementById('saveButton');
        const cropButtonText = document.getElementById('cropButtonText');
        
        if (cropButton) {
            cropButton.disabled = true;
            cropButton.style.display = 'block';
        }
        
        if (nextStepButton) {
            nextStepButton.style.display = 'none';
        }
        
        if (saveButton) {
            saveButton.style.display = 'none';
        }
        
        if (cropButtonText) {
            cropButtonText.textContent = 'Обрезать';
        }
        
        // Сброс слайдеров
        this.resetImageState();
        
        // Сброс подсказки
        const hint = document.querySelector('.editor-hint');
        if (hint) {
            hint.textContent = '';
        }
    }
}

// Инициализация редактора при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    window.photoEditor = new PhotoEditor();
});

// Функции для открытия редактора (делаем глобальными)
window.openPhotoEditor = function(type) {
    if (window.photoEditor) {
        window.photoEditor.open(type);
    } else {
        // Попробуем через небольшую задержку
        setTimeout(() => {
            if (window.photoEditor) {
                window.photoEditor.open(type);
            }
        }, 100);
    }
}

// Экспорт для использования в других модулях
if (typeof module !== 'undefined' && module.exports) {
    module.exports = PhotoEditor;
}