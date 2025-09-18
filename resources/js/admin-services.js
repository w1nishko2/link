/**
 * JavaScript для работы с услугами
 * Используется на страницах создания и редактирования услуг
 */

// Глобальные переменные
let selectedImageFile = null;
let swiperInstance = null;

// Состояние формы (будет инициализировано из blade шаблона)
let formState = {};

/**
 * Инициализация страницы услуг
 * @param {Object} initialState - Начальное состояние формы
 */
function initServicePage(initialState = {}) {
    formState = {
        title: '',
        description: '',
        price: '',
        priceType: 'fixed',
        hasPrice: false,
        buttonText: 'Заказать',
        ...initialState
    };
    
    // Инициализируем компоненты
    initSwiper();
    initEventListeners();
    
    // Показываем/скрываем блок цены в зависимости от состояния
    if (formState.hasPrice && formState.price) {
        document.querySelector('.service-price').style.display = 'block';
        updatePriceToggleButton(true);
    }
}

/**
 * Инициализация Swiper слайдера
 */
function initSwiper() {
    if (document.querySelector('.services-swiper')) {
        swiperInstance = new Swiper('.services-swiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: false,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                    spaceBetween: 30,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                }
            }
        });
    }
}

/**
 * Инициализация обработчиков событий
 */
function initEventListeners() {
    // Обработчики для редактируемых элементов
    const editableElements = document.querySelectorAll('[contenteditable="true"]');
    editableElements.forEach(element => {
        element.addEventListener('input', handleContentEdit);
        element.addEventListener('blur', handleContentBlur);
    });
    
    // Обработчик для загрузки изображений
    const imageInput = document.getElementById('image-input');
    if (imageInput) {
        imageInput.addEventListener('change', (e) => {
            handleImageSelect(e, 'image-preview', (file, dataUrl) => {
                selectedImageFile = file;
                updateFormState();
            });
        });
    }
    
    // Обработчик отправки формы
    const form = document.getElementById('service-form');
    if (form) {
        form.addEventListener('submit', handleFormSubmit);
    }
}

/**
 * Обработка изменений в редактируемых элементах
 */
function handleContentEdit(event) {
    const element = event.target;
    const fieldName = element.getAttribute('data-field');
    
    if (fieldName && formState.hasOwnProperty(fieldName)) {
        formState[fieldName] = element.textContent.trim();
    }
}

/**
 * Обработка потери фокуса редактируемых элементов
 */
function handleContentBlur(event) {
    const element = event.target;
    const fieldName = element.getAttribute('data-field');
    
    // Валидация и форматирование для конкретных полей
    if (fieldName === 'price') {
        formatPriceField(element);
    }
}

/**
 * Форматирование поля цены
 */
function formatPriceField(element) {
    let value = element.textContent.trim();
    
    // Убираем все кроме цифр, точки и запятой
    value = value.replace(/[^\d.,]/g, '');
    
    // Заменяем запятую на точку
    value = value.replace(',', '.');
    
    // Ограничиваем до 2 знаков после запятой
    const parts = value.split('.');
    if (parts.length > 2) {
        value = parts[0] + '.' + parts[1];
    }
    if (parts[1] && parts[1].length > 2) {
        value = parts[0] + '.' + parts[1].substring(0, 2);
    }
    
    element.textContent = value;
    formState.price = value;
}

/**
 * Обновление кнопки переключения цены
 */
function updatePriceToggleButton(hasPrice) {
    const toggleButton = document.getElementById('price-toggle');
    if (!toggleButton) return;
    
    if (hasPrice) {
        toggleButton.innerHTML = '<i class="bi bi-dash"></i> Убрать цену';
        toggleButton.className = 'btn btn-outline-danger btn-sm';
    } else {
        toggleButton.innerHTML = '<i class="bi bi-plus"></i> Добавить цену';
        toggleButton.className = 'btn btn-outline-success btn-sm';
    }
}

/**
 * Обновление состояния формы
 */
function updateFormState() {
    // Собираем данные из всех редактируемых элементов
    const editableElements = document.querySelectorAll('[contenteditable="true"]');
    editableElements.forEach(element => {
        const fieldName = element.getAttribute('data-field');
        if (fieldName && formState.hasOwnProperty(fieldName)) {
            formState[fieldName] = element.textContent.trim();
        }
    });
    
    // Проверяем состояние цены
    const priceBlock = document.querySelector('.service-price');
    formState.hasPrice = priceBlock && priceBlock.style.display !== 'none';
}

/**
 * Обработка отправки формы
 */
async function handleFormSubmit(event) {
    event.preventDefault();
    
    // Валидация
    if (!validateServiceForm()) {
        return;
    }
    
    // Обновляем состояние формы
    updateFormState();
    
    // Создаем FormData
    const formData = new FormData();
    
    // Добавляем данные из состояния формы
    Object.keys(formState).forEach(key => {
        if (formState[key] !== null && formState[key] !== undefined) {
            formData.append(key, formState[key]);
        }
    });
    
    // Добавляем изображение если выбрано
    if (selectedImageFile) {
        formData.append('image', selectedImageFile);
    }
    
    // Добавляем CSRF токен
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (csrfToken) {
        formData.append('_token', csrfToken);
    }
    
    // Отправляем форму
    await submitForm(event.target, {
        method: 'POST',
        loadingOverlayId: 'loadingOverlay',
        validateBeforeSubmit: validateServiceForm,
        onSuccess: (result) => {
            if (result.redirect_url) {
                window.location.href = result.redirect_url;
            }
        }
    });
}

/**
 * Валидация формы услуги
 */
function validateServiceForm() {
    updateFormState();
    
    // Проверяем обязательные поля
    if (!formState.title || formState.title.trim() === '') {
        showNotification('Пожалуйста, введите название услуги', 'error');
        const titleElement = document.querySelector('[data-field="title"]');
        if (titleElement) {
            titleElement.focus();
            titleElement.classList.add('is-invalid');
            setTimeout(() => titleElement.classList.remove('is-invalid'), 3000);
        }
        return false;
    }
    
    if (!formState.description || formState.description.trim() === '') {
        showNotification('Пожалуйста, введите описание услуги', 'error');
        const descElement = document.querySelector('[data-field="description"]');
        if (descElement) {
            descElement.focus();
            descElement.classList.add('is-invalid');
            setTimeout(() => descElement.classList.remove('is-invalid'), 3000);
        }
        return false;
    }
    
    // Проверяем цену если она указана
    if (formState.hasPrice && (!formState.price || formState.price.trim() === '')) {
        showNotification('Пожалуйста, введите цену услуги', 'error');
        const priceElement = document.querySelector('[data-field="price"]');
        if (priceElement) {
            priceElement.focus();
            priceElement.classList.add('is-invalid');
            setTimeout(() => priceElement.classList.remove('is-invalid'), 3000);
        }
        return false;
    }
    
    return true;
}

/**
 * Функции для страницы редактирования услуг
 */

/**
 * Инициализация Swiper для страницы редактирования
 */
function initializeSwiper() {
    if (typeof Swiper !== 'undefined' && document.querySelector('.services-swiper')) {
        swiperInstance = new Swiper('.services-swiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: false,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                768: {
                    slidesPerView: 1,
                    spaceBetween: 30,
                },
                1024: {
                    slidesPerView: 1,
                    spaceBetween: 30,
                }
            }
        });
    }
}

/**
 * Привязка событий для редактируемых элементов
 */
function bindEvents() {
    // Привязываем события для редактируемых элементов
    document.querySelectorAll('[contenteditable="true"]').forEach(element => {
        element.addEventListener('input', function() {
            const fieldName = this.getAttribute('data-field');
            const value = this.textContent.trim();
            
            if (fieldName) {
                formState[fieldName] = value;
                updateFormData(fieldName, value);
            }
        });
        
        element.addEventListener('blur', function() {
            const fieldName = this.getAttribute('data-field');
            if (fieldName === 'price') {
                formatPriceField(this);
            }
        });
    });
    
    // События для кнопок добавления/удаления цены
    document.querySelectorAll('.add-price-button').forEach(button => {
        button.addEventListener('click', function() {
            const serviceId = this.getAttribute('data-service-id');
            const priceBlock = document.querySelector(`.service-price[data-service-id="${serviceId}"]`);
            
            if (priceBlock.style.display === 'none' || !priceBlock.style.display) {
                priceBlock.style.display = 'block';
                this.innerHTML = '<i class="bi bi-dash"></i> Убрать';
                this.classList.remove('btn-outline-success');
                this.classList.add('btn-outline-danger');
            } else {
                priceBlock.style.display = 'none';
                this.innerHTML = '<i class="bi bi-plus"></i> Цена';
                this.classList.remove('btn-outline-danger');
                this.classList.add('btn-outline-success');
            }
        });
    });
    
    // События для редактируемых изображений
    document.querySelectorAll('.editable-image').forEach(imageElement => {
        imageElement.addEventListener('click', function() {
            const serviceId = this.getAttribute('data-service-id');
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/*';
            
            input.addEventListener('change', function(e) {
                if (e.target.files && e.target.files[0]) {
                    const file = e.target.files[0];
                    handleImageChange(serviceId, file);
                }
            });
            
            input.click();
        });
    });
    
    // События для редактируемых кнопок
    document.querySelectorAll('.editable-button').forEach(button => {
        button.addEventListener('click', function() {
            const span = this.querySelector('span');
            if (span) {
                span.contentEditable = true;
                span.focus();
                
                // Выделяем весь текст
                const range = document.createRange();
                range.selectNodeContents(span);
                const selection = window.getSelection();
                selection.removeAllRanges();
                selection.addRange(range);
                
                span.addEventListener('blur', function() {
                    this.contentEditable = false;
                    const serviceId = button.getAttribute('data-service-id');
                    const buttonText = this.textContent.trim();
                    updateFormData('buttonText', buttonText, serviceId);
                }, { once: true });
                
                span.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        this.blur();
                    }
                });
            }
        });
    });
}

/**
 * Обработка изменения изображения
 */
function handleImageChange(serviceId, file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        const imageElement = document.querySelector(`.service-image[data-service-id="${serviceId}"] img`);
        if (imageElement) {
            imageElement.src = e.target.result;
        }
        
        // Сохраняем файл для отправки
        updateFormData('image', file, serviceId);
    };
    reader.readAsDataURL(file);
}

/**
 * Обновление данных формы
 */
function updateFormData(field, value, serviceId = null) {
    if (!formState.services) {
        formState.services = {};
    }
    
    if (serviceId) {
        if (!formState.services[serviceId]) {
            formState.services[serviceId] = {};
        }
        formState.services[serviceId][field] = value;
    } else {
        formState[field] = value;
    }
}

/**
 * Сохранение всех изменений
 */
async function saveAllChanges() {
    const loadingOverlay = document.getElementById('loadingOverlay');
    if (loadingOverlay) {
        loadingOverlay.style.display = 'flex';
    }
    
    try {
        const formData = new FormData();
        
        // Добавляем CSRF токен
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (csrfToken) {
            formData.append('_token', csrfToken);
        }
        
        // Добавляем данные услуг
        if (formState.services) {
            formData.append('services', JSON.stringify(formState.services));
        }
        
        // Отправляем данные
        const response = await fetch(window.location.href, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('Изменения успешно сохранены', 'success');
            
            // Обновляем страницу через небольшую задержку
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showNotification(result.message || 'Произошла ошибка при сохранении', 'error');
        }
    } catch (error) {
        console.error('Ошибка при сохранении:', error);
        showNotification('Произошла ошибка при сохранении изменений', 'error');
    } finally {
        if (loadingOverlay) {
            loadingOverlay.style.display = 'none';
        }
    }
}

/**
 * Переключение цены для услуги
 */
function togglePrice(serviceId) {
    const priceBlock = document.querySelector(`.service-price[data-service-id="${serviceId}"]`);
    const button = document.querySelector(`.add-price-button[data-service-id="${serviceId}"]`);
    
    if (!priceBlock || !button) return;
    
    const isVisible = priceBlock.style.display !== 'none';
    
    if (isVisible) {
        priceBlock.style.display = 'none';
        button.innerHTML = '<i class="bi bi-plus"></i> Цена';
        button.classList.remove('btn-outline-danger');
        button.classList.add('btn-outline-success');
        updateFormData('hasPrice', false, serviceId);
    } else {
        priceBlock.style.display = 'block';
        button.innerHTML = '<i class="bi bi-dash"></i> Убрать';
        button.classList.remove('btn-outline-success');
        button.classList.add('btn-outline-danger');
        updateFormData('hasPrice', true, serviceId);
    }
}

// Экспортируем функции для глобального использования
window.initServicePage = initServicePage;
window.validateServiceForm = validateServiceForm;
window.initializeSwiper = initializeSwiper;
window.bindEvents = bindEvents;
window.saveAllChanges = saveAllChanges;
window.togglePrice = togglePrice;

// Автоинициализация при загрузке DOM
document.addEventListener('DOMContentLoaded', function() {
    // Проверяем, находимся ли мы на странице услуг
    if (document.getElementById('service-form')) {
        // Инициализируем с пустым состоянием, 
        // реальные данные будут переданы из blade шаблона
        initServicePage();
    }
    
    // Инициализация для страницы редактирования
    if (document.querySelector('.services-swiper')) {
        initializeSwiper();
        bindEvents();
    }
});