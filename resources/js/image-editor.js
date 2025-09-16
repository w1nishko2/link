/**
 * Редактор изображений с функцией кропа
 * Поддерживает масштабирование, перемещение и обрезку изображений
 * Адаптирован для мобильных устройств с touch-событиями
 */

class ImageEditor {
    constructor() {
        this.canvas = null;
        this.ctx = null;
        this.image = null;
        this.scale = 1;
        this.offsetX = 0;
        this.offsetY = 0;
        this.isDragging = false;
        this.isResizing = false;
        this.dragStart = { x: 0, y: 0 };
        this.cropType = 'square';
        this.cropArea = { x: 0, y: 0, width: 200, height: 200 };
        this.originalImageData = null;
        this.currentImageType = null;
        
        this.init();
    }

    init() {
        this.canvas = document.getElementById('imageCanvas');
        this.ctx = this.canvas.getContext('2d');
        
        this.bindEvents();
        this.setupCanvas();
    }

    bindEvents() {
        // Загрузка файла
        document.getElementById('imageInput').addEventListener('change', (e) => {
            this.handleFileSelect(e);
        });

        // Сохранение
        document.getElementById('saveImageBtn').addEventListener('click', () => {
            this.saveImage();
        });

        // События canvas для перемещения изображения
        this.bindCanvasEvents();
        
        // События для перемещения области кропа
        this.bindCropEvents();
    }

    bindCanvasEvents() {
        // Mouse events
        this.canvas.addEventListener('mousedown', (e) => {
            this.startDrag(e.clientX, e.clientY);
        });

        this.canvas.addEventListener('mousemove', (e) => {
            if (this.isDragging) {
                this.drag(e.clientX, e.clientY);
            }
        });

        this.canvas.addEventListener('mouseup', () => {
            this.endDrag();
        });

        // Touch events для мобильных устройств
        this.canvas.addEventListener('touchstart', (e) => {
            e.preventDefault();
            const touch = e.touches[0];
            this.startDrag(touch.clientX, touch.clientY);
        });

        this.canvas.addEventListener('touchmove', (e) => {
            e.preventDefault();
            if (this.isDragging) {
                const touch = e.touches[0];
                this.drag(touch.clientX, touch.clientY);
            }
        });

        this.canvas.addEventListener('touchend', (e) => {
            e.preventDefault();
            this.endDrag();
        });

        // Предотвращение контекстного меню
        this.canvas.addEventListener('contextmenu', (e) => {
            e.preventDefault();
        });
    }

    bindCropEvents() {
        const cropCenter = document.getElementById('cropCenter');
        const cropArea = document.getElementById('cropArea');
        
        // Перемещение области кропа
        cropCenter.addEventListener('mousedown', (e) => {
            this.startCropDrag(e);
        });

        cropCenter.addEventListener('touchstart', (e) => {
            e.preventDefault();
            this.startCropDrag(e);
        });

        // Обработчики для handles (углы области кропа)
        document.querySelectorAll('.crop-handle').forEach(handle => {
            handle.addEventListener('mousedown', (e) => {
                this.startCropResize(e, handle);
            });

            handle.addEventListener('touchstart', (e) => {
                e.preventDefault();
                this.startCropResize(e, handle);
            });
        });
    }

    setupCanvas() {
        // Устанавливаем размер canvas на весь доступный экран
        const windowWidth = window.innerWidth;
        const windowHeight = window.innerHeight;
        
        // Учитываем header и footer модального окна
        const headerHeight = 60; // примерная высота header
        const footerHeight = 60; // примерная высота footer
        
        this.canvas.width = windowWidth - 40; // небольшой отступ
        this.canvas.height = windowHeight - headerHeight - footerHeight - 40;
        
        // Настройка области кропа будет выполнена при загрузке изображения
    }

    handleFileSelect(event) {
        const file = event.target.files[0];
        if (!file) return;

        // Проверка типа файла
        if (!file.type.startsWith('image/')) {
            alert('Пожалуйста, выберите файл изображения.');
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            this.loadImage(e.target.result);
        };
        reader.readAsDataURL(file);
    }

    loadImage(src) {
        this.image = new Image();
        this.image.onload = () => {
            this.originalImageData = src;
            this.resetView();
            this.showEditor();
        };
        this.image.src = src;
    }

    showEditor() {
        document.getElementById('fileUploadArea').style.display = 'none';
        document.getElementById('imageEditorWorkspace').style.display = 'block';
        document.getElementById('saveImageBtn').style.display = 'inline-block';
    }

    hideEditor() {
        document.getElementById('fileUploadArea').style.display = 'flex';
        document.getElementById('imageEditorWorkspace').style.display = 'none';
        document.getElementById('saveImageBtn').style.display = 'none';
    }

    resetView() {
        if (!this.image) return;

        const canvasWidth = this.canvas.width;
        const canvasHeight = this.canvas.height;
        const imageWidth = this.image.width;
        const imageHeight = this.image.height;

        // Вычисляем масштаб для помещения изображения в canvas
        const scaleX = canvasWidth / imageWidth;
        const scaleY = canvasHeight / imageHeight;
        this.scale = Math.min(scaleX, scaleY) * 0.9; // увеличиваем до 90% для лучшего использования экрана

        // Центрируем изображение
        this.offsetX = (canvasWidth - imageWidth * this.scale) / 2;
        this.offsetY = (canvasHeight - imageHeight * this.scale) / 2;

        this.updateCropArea();
        this.draw();
    }

    setCropType(type) {
        this.cropType = type;
        this.updateCropArea();
    }

    updateCropArea() {
        const overlay = document.getElementById('cropOverlay');
        const cropAreaEl = document.getElementById('cropArea');
        
        const canvasRect = this.canvas.getBoundingClientRect();
        const centerX = canvasRect.width / 2;
        const centerY = canvasRect.height / 2;

        let width, height;
        
        if (this.cropType === 'square') {
            // Квадрат для аватара
            width = height = Math.min(canvasRect.width, canvasRect.height) * 0.6;
        } else {
            // Прямоугольник 16:9 для фона
            width = canvasRect.width * 0.8;
            height = width * (9 / 16);
            
            // Убеждаемся, что помещается по высоте
            if (height > canvasRect.height * 0.8) {
                height = canvasRect.height * 0.8;
                width = height * (16 / 9);
            }
        }

        this.cropArea = {
            x: centerX - width / 2,
            y: centerY - height / 2,
            width: width,
            height: height
        };

        // Обновляем позицию DOM элемента
        cropAreaEl.style.left = this.cropArea.x + 'px';
        cropAreaEl.style.top = this.cropArea.y + 'px';
        cropAreaEl.style.width = this.cropArea.width + 'px';
        cropAreaEl.style.height = this.cropArea.height + 'px';
    }

    startDrag(x, y) {
        this.isDragging = true;
        const rect = this.canvas.getBoundingClientRect();
        this.dragStart = {
            x: x - rect.left - this.offsetX,
            y: y - rect.top - this.offsetY
        };
    }

    drag(x, y) {
        if (!this.isDragging) return;
        
        const rect = this.canvas.getBoundingClientRect();
        this.offsetX = x - rect.left - this.dragStart.x;
        this.offsetY = y - rect.top - this.dragStart.y;
        
        this.draw();
    }

    endDrag() {
        this.isDragging = false;
    }

    startCropDrag(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const rect = document.getElementById('cropOverlay').getBoundingClientRect();
        const clientX = e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
        const clientY = e.type.includes('touch') ? e.touches[0].clientY : e.clientY;
        
        this.cropDragStart = {
            x: clientX - this.cropArea.x,
            y: clientY - this.cropArea.y
        };

        const moveHandler = (e) => {
            const clientX = e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
            const clientY = e.type.includes('touch') ? e.touches[0].clientY : e.clientY;
            
            this.cropArea.x = clientX - this.cropDragStart.x;
            this.cropArea.y = clientY - this.cropDragStart.y;
            
            this.updateCropAreaPosition();
        };

        const endHandler = () => {
            document.removeEventListener('mousemove', moveHandler);
            document.removeEventListener('mouseup', endHandler);
            document.removeEventListener('touchmove', moveHandler);
            document.removeEventListener('touchend', endHandler);
        };

        document.addEventListener('mousemove', moveHandler);
        document.addEventListener('mouseup', endHandler);
        document.addEventListener('touchmove', moveHandler, { passive: false });
        document.addEventListener('touchend', endHandler);
    }

    startCropResize(e, handle) {
        e.preventDefault();
        e.stopPropagation();
        
        const handleClass = handle.className;
        const clientX = e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
        const clientY = e.type.includes('touch') ? e.touches[0].clientY : e.clientY;
        
        const startCropArea = { ...this.cropArea };
        const startX = clientX;
        const startY = clientY;

        const moveHandler = (e) => {
            const clientX = e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
            const clientY = e.type.includes('touch') ? e.touches[0].clientY : e.clientY;
            
            const deltaX = clientX - startX;
            const deltaY = clientY - startY;
            
            let newX = startCropArea.x;
            let newY = startCropArea.y;
            let newWidth = startCropArea.width;
            let newHeight = startCropArea.height;

            // Определяем направление изменения размера на основе класса handle
            if (handleClass.includes('nw')) {
                // Северо-запад
                newX = startCropArea.x + deltaX;
                newY = startCropArea.y + deltaY;
                newWidth = startCropArea.width - deltaX;
                newHeight = startCropArea.height - deltaY;
            } else if (handleClass.includes('ne')) {
                // Северо-восток
                newY = startCropArea.y + deltaY;
                newWidth = startCropArea.width + deltaX;
                newHeight = startCropArea.height - deltaY;
            } else if (handleClass.includes('sw')) {
                // Юго-запад
                newX = startCropArea.x + deltaX;
                newWidth = startCropArea.width - deltaX;
                newHeight = startCropArea.height + deltaY;
            } else if (handleClass.includes('se')) {
                // Юго-восток
                newWidth = startCropArea.width + deltaX;
                newHeight = startCropArea.height + deltaY;
            }

            // Ограничиваем минимальный размер
            const minSize = 50;
            if (newWidth >= minSize && newHeight >= minSize) {
                // Для квадратного кропа поддерживаем соотношение сторон
                if (this.cropType === 'square') {
                    const size = Math.min(newWidth, newHeight);
                    this.cropArea.width = size;
                    this.cropArea.height = size;
                } else {
                    this.cropArea.width = newWidth;
                    this.cropArea.height = newHeight;
                }
                
                this.cropArea.x = newX;
                this.cropArea.y = newY;
                
                this.updateCropAreaPosition();
            }
        };

        const endHandler = () => {
            document.removeEventListener('mousemove', moveHandler);
            document.removeEventListener('mouseup', endHandler);
            document.removeEventListener('touchmove', moveHandler);
            document.removeEventListener('touchend', endHandler);
        };

        document.addEventListener('mousemove', moveHandler);
        document.addEventListener('mouseup', endHandler);
        document.addEventListener('touchmove', moveHandler, { passive: false });
        document.addEventListener('touchend', endHandler);
    }

    updateCropAreaPosition() {
        const cropAreaEl = document.getElementById('cropArea');
        cropAreaEl.style.left = this.cropArea.x + 'px';
        cropAreaEl.style.top = this.cropArea.y + 'px';
        cropAreaEl.style.width = this.cropArea.width + 'px';
        cropAreaEl.style.height = this.cropArea.height + 'px';
    }

    draw() {
        if (!this.image) return;

        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
        
        // Рисуем изображение
        this.ctx.drawImage(
            this.image,
            this.offsetX,
            this.offsetY,
            this.image.width * this.scale,
            this.image.height * this.scale
        );
    }

    saveImage() {
        if (!this.image) return;

        // Получаем координаты области кропа относительно canvas
        const canvasRect = this.canvas.getBoundingClientRect();
        const scaleFactorX = this.canvas.width / canvasRect.width;
        const scaleFactorY = this.canvas.height / canvasRect.height;

        const cropX = (this.cropArea.x) * scaleFactorX;
        const cropY = (this.cropArea.y) * scaleFactorY;
        const cropWidth = this.cropArea.width * scaleFactorX;
        const cropHeight = this.cropArea.height * scaleFactorY;

        // Создаем временный canvas для обрезки
        const tempCanvas = document.createElement('canvas');
        const tempCtx = tempCanvas.getContext('2d');

        // Размер выходного изображения
        let outputWidth, outputHeight;
        if (this.cropType === 'square') {
            outputWidth = outputHeight = 400; // Размер аватара
        } else {
            outputWidth = 1200; // Размер фона
            outputHeight = 675; // 16:9
        }

        tempCanvas.width = outputWidth;
        tempCanvas.height = outputHeight;

        // Вычисляем масштаб и смещения для получения обрезанной области
        const imageDisplayWidth = this.image.width * this.scale;
        const imageDisplayHeight = this.image.height * this.scale;

        // Координаты обрезки относительно оригинального изображения
        const sourceX = Math.max(0, (cropX - this.offsetX) / this.scale);
        const sourceY = Math.max(0, (cropY - this.offsetY) / this.scale);
        const sourceWidth = Math.min(this.image.width - sourceX, cropWidth / this.scale);
        const sourceHeight = Math.min(this.image.height - sourceY, cropHeight / this.scale);

        // Рисуем обрезанное изображение
        tempCtx.drawImage(
            this.image,
            sourceX, sourceY, sourceWidth, sourceHeight,
            0, 0, outputWidth, outputHeight
        );

        // Получаем данные изображения
        const croppedDataUrl = tempCanvas.toDataURL('image/jpeg', 0.9);
        
        // Отправляем на сервер
        this.uploadCroppedImage(croppedDataUrl);
    }

    uploadCroppedImage(dataUrl) {
        const form = document.getElementById('croppedImageForm');
        const imageData = document.getElementById('croppedImageData');
        const imageType = document.getElementById('imageType');
        
        // Преобразуем data URL в Blob
        const byteString = atob(dataUrl.split(',')[1]);
        const mimeString = dataUrl.split(',')[0].split(':')[1].split(';')[0];
        const ab = new ArrayBuffer(byteString.length);
        const ia = new Uint8Array(ab);
        for (let i = 0; i < byteString.length; i++) {
            ia[i] = byteString.charCodeAt(i);
        }
        const blob = new Blob([ab], { type: mimeString });
        
        // Создаем FormData
        const formData = new FormData();
        
        // Добавляем файл с правильным именем поля
        if (this.currentImageType === 'avatar') {
            formData.append('avatar', blob, 'avatar.jpg');
        } else {
            formData.append('background_image', blob, 'background.jpg');
        }
        
        // Устанавливаем action формы
        const username = window.location.pathname.split('/')[2] || window.pageUsername;
        let url;
        if (this.currentImageType === 'avatar') {
            url = `/user/${username}/update-avatar`;
        } else {
            url = `/user/${username}/update-background`;
        }
        
        // Показываем индикатор загрузки
        const saveBtn = document.getElementById('saveImageBtn');
        const originalText = saveBtn.innerHTML;
        saveBtn.innerHTML = '<i class="bi bi-arrow-repeat spin me-2"></i>Сохранение...';
        saveBtn.disabled = true;

        // Отправляем данные
        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Закрываем модальное окно
                const modal = bootstrap.Modal.getInstance(document.getElementById('imageEditorModal'));
                modal.hide();
                
                // Обновляем изображение на странице
                this.updatePageImage(data.image_url);
                
                // Показываем сообщение об успехе
                this.showSuccessMessage('Изображение успешно обновлено!');
            } else {
                throw new Error(data.message || 'Ошибка при сохранении изображения');
            }
        })
        .catch(error => {
            console.error('Ошибка:', error);
            this.showErrorMessage('Ошибка при сохранении изображения: ' + error.message);
        })
        .finally(() => {
            saveBtn.innerHTML = originalText;
            saveBtn.disabled = false;
        });
    }

    updatePageImage(imageUrl) {
        if (this.currentImageType === 'avatar') {
            // Обновляем аватар - ищем изображение внутри hero-logo
            const avatarImages = document.querySelectorAll('.hero-logo img, #avatar-editable img');
            avatarImages.forEach(img => {
                img.src = imageUrl + '?t=' + Date.now();
            });
        } else {
            // Обновляем фон
            const heroSection = document.querySelector('.hero, #hero-background-editable');
            if (heroSection) {
                heroSection.style.backgroundImage = `url('${imageUrl}?t=${Date.now()}')`;
            }
        }
    }

    showSuccessMessage(message) {
        this.showMessage(message, 'success');
    }

    showErrorMessage(message) {
        this.showMessage(message, 'danger');
    }

    showMessage(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(alertDiv);
        
        // Автоматически удаляем через 5 секунд
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }

    openEditor(type) {
        this.currentImageType = type;
        
        // Сбрасываем состояние
        this.hideEditor();
        document.getElementById('imageInput').value = '';
        
        // Автоматически устанавливаем тип кропа
        this.cropType = type === 'avatar' ? 'square' : 'rectangle';
        
        // Обновляем заголовок
        const title = document.getElementById('imageEditorModalLabel');
        title.textContent = type === 'avatar' ? 'Редактирование аватара' : 'Редактирование фона';
        
        // Пересчитываем размер canvas для полноэкранного режима
        this.setupCanvas();
        
        // Показываем модальное окно
        const modal = new bootstrap.Modal(document.getElementById('imageEditorModal'));
        modal.show();
    }
}

// Инициализация редактора при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    window.imageEditor = new ImageEditor();
    
    // Отправляем событие о готовности редактора
    const event = new CustomEvent('imageEditorReady');
    document.dispatchEvent(event);
    
    console.log('Image editor initialized and ready');
});

// Функции для открытия редактора
function openAvatarEditor() {
    if (window.imageEditor && typeof window.imageEditor.openEditor === 'function') {
        window.imageEditor.openEditor('avatar');
    } else {
        console.error('Image editor not ready');
    }
}

function openBackgroundEditor() {
    if (window.imageEditor && typeof window.imageEditor.openEditor === 'function') {
        window.imageEditor.openEditor('background');
    } else {
        console.error('Image editor not ready');
    }
}

// CSS класс для анимации загрузки
const style = document.createElement('style');
style.textContent = `
    .spin {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);