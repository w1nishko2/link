/**
 * JavaScript для работы с галереей
 * Используется на страницах создания и редактирования галереи
 */

// Глобальные переменные
let selectedGalleryFiles = [];
let gallerySwiper = null;

// Состояние формы
let galleryFormState = {};

/**
 * Инициализация страницы галереи
 * @param {Object} initialState - Начальное состояние формы
 */
function initGalleryPage(initialState = {}) {
    galleryFormState = {
        title: '',
        description: '',
        images: [],
        ...initialState
    };
    
    // Инициализируем компоненты
    initGallerySwiper();
    initGalleryEventListeners();
}

/**
 * Инициализация Swiper для галереи
 */
function initGallerySwiper() {
    if (document.querySelector('.gallery-swiper')) {
        gallerySwiper = new Swiper('.gallery-swiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
                dynamicBullets: true,
            },
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                    effect: 'slide',
                    spaceBetween: 30,
                },
                1024: {
                    slidesPerView: 3,
                    effect: 'slide',
                    spaceBetween: 40,
                }
            }
        });
    }
}

/**
 * Инициализация обработчиков событий для галереи
 */
function initGalleryEventListeners() {
    // Обработчики для редактируемых элементов
    const editableElements = document.querySelectorAll('[contenteditable="true"]');
    editableElements.forEach(element => {
        element.addEventListener('input', handleGalleryContentEdit);
        element.addEventListener('blur', handleGalleryContentBlur);
    });
    
    // Обработчик для множественной загрузки изображений
    const imagesInput = document.getElementById('images-input');
    if (imagesInput) {
        imagesInput.addEventListener('change', handleMultipleImagesSelect);
    }
    
    // Обработчик отправки формы
    const form = document.getElementById('gallery-form');
    if (form) {
        form.addEventListener('submit', handleGalleryFormSubmit);
    }
    
    // Обработчики для кнопок удаления изображений
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-image-btn')) {
            e.preventDefault();
            const index = parseInt(e.target.closest('.remove-image-btn').getAttribute('data-index'));
            removeGalleryImage(index);
        }
    });
}

/**
 * Обработка изменений в редактируемых элементах галереи
 */
function handleGalleryContentEdit(event) {
    const element = event.target;
    const fieldName = element.getAttribute('data-field');
    
    if (fieldName && galleryFormState.hasOwnProperty(fieldName)) {
        galleryFormState[fieldName] = element.textContent.trim();
    }
}

/**
 * Обработка потери фокуса редактируемых элементов галереи
 */
function handleGalleryContentBlur(event) {
    const element = event.target;
    const fieldName = element.getAttribute('data-field');
    
    // Дополнительная валидация для конкретных полей
    if (fieldName === 'title') {
        if (element.textContent.trim().length < 3) {
            element.classList.add('is-invalid');
            showNotification('Название галереи должно содержать минимум 3 символа', 'warning');
            setTimeout(() => element.classList.remove('is-invalid'), 3000);
        }
    }
}

/**
 * Обработка выбора множественных изображений
 */
function handleMultipleImagesSelect(event) {
    const files = Array.from(event.target.files);
    
    if (files.length === 0) return;
    
    // Валидация файлов
    const validFiles = [];
    const maxSize = 10 * 1024 * 1024; // 10MB
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
    
    files.forEach(file => {
        if (!allowedTypes.includes(file.type)) {
            showNotification(`Файл ${file.name} имеет неподдерживаемый формат`, 'error');
            return;
        }
        
        if (file.size > maxSize) {
            showNotification(`Файл ${file.name} слишком большой (максимум 10MB)`, 'error');
            return;
        }
        
        validFiles.push(file);
    });
    
    if (validFiles.length === 0) return;
    
    // Проверяем общее количество изображений
    const currentCount = selectedGalleryFiles.length;
    const maxImages = 20;
    
    if (currentCount + validFiles.length > maxImages) {
        showNotification(`Можно загрузить максимум ${maxImages} изображений. Выбрано только первые ${maxImages - currentCount} файлов.`, 'warning');
        validFiles.splice(maxImages - currentCount);
    }
    
    // Добавляем файлы
    validFiles.forEach(file => {
        selectedGalleryFiles.push(file);
    });
    
    // Обновляем превью
    updateGalleryPreview();
    
    showNotification(`Добавлено ${validFiles.length} изображений`, 'success');
}

/**
 * Обновление превью галереи
 */
function updateGalleryPreview() {
    const previewContainer = document.getElementById('gallery-preview');
    if (!previewContainer) return;
    
    // Очищаем контейнер
    previewContainer.innerHTML = '';
    
    if (selectedGalleryFiles.length === 0) {
        previewContainer.innerHTML = '<div class="text-muted text-center py-4">Изображения не выбраны</div>';
        return;
    }
    
    // Создаем превью для каждого изображения
    selectedGalleryFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const imageHtml = `
                <div class="gallery-image-item position-relative mb-3" data-index="${index}">
                    <img src="${e.target.result}" alt="Превью ${index + 1}" class="img-thumbnail">
                    <button type="button" class="btn btn-danger btn-sm remove-image-btn position-absolute" 
                            data-index="${index}" title="Удалить изображение">
                        <i class="bi bi-x"></i>
                    </button>
                    <div class="image-info">
                        <small class="text-muted">${file.name} (${formatFileSize(file.size)})</small>
                    </div>
                </div>
            `;
            
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = imageHtml;
            previewContainer.appendChild(tempDiv.firstElementChild);
        };
        reader.readAsDataURL(file);
    });
}

/**
 * Удаление изображения из галереи
 */
function removeGalleryImage(index) {
    if (index >= 0 && index < selectedGalleryFiles.length) {
        const fileName = selectedGalleryFiles[index].name;
        selectedGalleryFiles.splice(index, 1);
        updateGalleryPreview();
        showNotification(`Изображение ${fileName} удалено`, 'info');
    }
}

/**
 * Форматирование размера файла
 */
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
}

/**
 * Обработка отправки формы галереи
 */
async function handleGalleryFormSubmit(event) {
    event.preventDefault();
    
    // Валидация
    if (!validateGalleryForm()) {
        return;
    }
    
    // Обновляем состояние формы
    updateGalleryFormState();
    
    // Создаем FormData
    const formData = new FormData();
    
    // Добавляем данные из состояния формы
    Object.keys(galleryFormState).forEach(key => {
        if (key !== 'images' && galleryFormState[key] !== null && galleryFormState[key] !== undefined) {
            formData.append(key, galleryFormState[key]);
        }
    });
    
    // Добавляем изображения
    selectedGalleryFiles.forEach((file, index) => {
        formData.append(`images[${index}]`, file);
    });
    
    // Добавляем CSRF токен
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (csrfToken) {
        formData.append('_token', csrfToken);
    }
    
    // Отправляем форму
    await submitForm(event.target, {
        method: 'POST',
        loadingOverlayId: 'loadingOverlay',
        validateBeforeSubmit: validateGalleryForm,
        onSuccess: (result) => {
            if (result.redirect_url) {
                window.location.href = result.redirect_url;
            }
        }
    });
}

/**
 * Обновление состояния формы галереи
 */
function updateGalleryFormState() {
    // Собираем данные из всех редактируемых элементов
    const editableElements = document.querySelectorAll('[contenteditable="true"]');
    editableElements.forEach(element => {
        const fieldName = element.getAttribute('data-field');
        if (fieldName && galleryFormState.hasOwnProperty(fieldName)) {
            galleryFormState[fieldName] = element.textContent.trim();
        }
    });
    
    // Обновляем массив изображений
    galleryFormState.images = selectedGalleryFiles;
}

/**
 * Валидация формы галереи
 */
function validateGalleryForm() {
    updateGalleryFormState();
    
    // Проверяем обязательные поля
    if (!galleryFormState.title || galleryFormState.title.trim() === '') {
        showNotification('Пожалуйста, введите название галереи', 'error');
        const titleElement = document.querySelector('[data-field="title"]');
        if (titleElement) {
            titleElement.focus();
            titleElement.classList.add('is-invalid');
            setTimeout(() => titleElement.classList.remove('is-invalid'), 3000);
        }
        return false;
    }
    
    if (galleryFormState.title.trim().length < 3) {
        showNotification('Название галереи должно содержать минимум 3 символа', 'error');
        return false;
    }
    
    if (!galleryFormState.description || galleryFormState.description.trim() === '') {
        showNotification('Пожалуйста, введите описание галереи', 'error');
        const descElement = document.querySelector('[data-field="description"]');
        if (descElement) {
            descElement.focus();
            descElement.classList.add('is-invalid');
            setTimeout(() => descElement.classList.remove('is-invalid'), 3000);
        }
        return false;
    }
    
    // Проверяем наличие изображений (для создания новой галереи)
    const isEditMode = document.querySelector('[data-edit-mode="true"]');
    if (!isEditMode && selectedGalleryFiles.length === 0) {
        showNotification('Пожалуйста, выберите хотя бы одно изображение для галереи', 'error');
        return false;
    }
    
    return true;
}

// Экспортируем функции для глобального использования
window.initGalleryPage = initGalleryPage;
window.validateGalleryForm = validateGalleryForm;
window.removeGalleryImage = removeGalleryImage;

// Автоинициализация при загрузке DOM
document.addEventListener('DOMContentLoaded', function() {
    // Проверяем, находимся ли мы на странице галереи
    if (document.getElementById('gallery-form')) {
        initGalleryPage();
    }
});