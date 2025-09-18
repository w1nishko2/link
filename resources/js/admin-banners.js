/**
 * JavaScript для работы с баннерами
 * Используется на страницах создания, редактирования и просмотра баннеров
 */

// Глобальные переменные
let selectedBannerFile = null;
let bannerSwiper = null;
let editSwiper = null;

// Состояние формы баннера
let bannerFormState = {};

// Состояние формы редактирования (для edit.blade.php)
let editFormState = {};

/**
 * Инициализация страницы баннеров
 * @param {Object} initialState - Начальное состояние формы
 */
function initBannerPage(initialState = {}) {
    bannerFormState = {
        title: '',
        subtitle: '',
        link: '',
        hasLink: false,
        buttonText: '',
        isActive: true,
        position: 1,
        ...initialState
    };
    
    // Инициализируем компоненты
    initBannerSwiper();
    initBannerEventListeners();
    
    // Показываем/скрываем блок ссылки в зависимости от состояния
    if (bannerFormState.hasLink) {
        document.querySelector('.banner-link-settings').style.display = 'block';
        updateLinkToggleButton(true);
    }
}

/**
 * Инициализация Swiper для баннеров
 */
function initBannerSwiper() {
    if (document.querySelector('.banners-swiper')) {
        bannerSwiper = new Swiper('.banners-swiper', {
            slidesPerView: 1,
            spaceBetween: 0,
            loop: true,
            autoplay: {
                delay: 6000,
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
            on: {
                slideChange: function () {
                    // Дополнительная логика при смене слайда
                }
            }
        });
    }
}

/**
 * Инициализация обработчиков событий для баннеров
 */
function initBannerEventListeners() {
    // Обработчики для редактируемых элементов
    const editableElements = document.querySelectorAll('[contenteditable="true"]');
    editableElements.forEach(element => {
        element.addEventListener('input', handleBannerContentEdit);
        element.addEventListener('blur', handleBannerContentBlur);
        element.addEventListener('paste', handleBannerContentPaste);
    });
    
    // Обработчик для загрузки изображений баннера
    const imageInput = document.getElementById('banner-image-input');
    if (imageInput) {
        imageInput.addEventListener('change', (e) => {
            handleImageSelect(e, 'banner-image-preview', (file, dataUrl) => {
                selectedBannerFile = file;
                updateBannerFormState();
            });
        });
    }
    
    // Обработчик отправки формы
    const form = document.getElementById('banner-form');
    if (form) {
        form.addEventListener('submit', handleBannerFormSubmit);
    }
    
    // Обработчик для чекбокса активности
    const activeCheckbox = document.getElementById('is-active');
    if (activeCheckbox) {
        activeCheckbox.addEventListener('change', function() {
            bannerFormState.isActive = this.checked;
        });
    }
    
    // Обработчик для поля позиции
    const positionInput = document.getElementById('position');
    if (positionInput) {
        positionInput.addEventListener('input', function() {
            bannerFormState.position = parseInt(this.value) || 1;
        });
    }
}

/**
 * Обработка изменений в редактируемых элементах баннера
 */
function handleBannerContentEdit(event) {
    const element = event.target;
    const fieldName = element.getAttribute('data-field');
    
    if (fieldName && bannerFormState.hasOwnProperty(fieldName)) {
        bannerFormState[fieldName] = element.textContent.trim();
        
        // Специальная логика для полей ссылки
        if (fieldName === 'link') {
            validateBannerLink(element);
        }
    }
}

/**
 * Обработка потери фокуса редактируемых элементов баннера
 */
function handleBannerContentBlur(event) {
    const element = event.target;
    const fieldName = element.getAttribute('data-field');
    
    // Валидация и форматирование для конкретных полей
    if (fieldName === 'link') {
        formatBannerLink(element);
    }
}

/**
 * Обработка вставки контента в редактируемые элементы
 */
function handleBannerContentPaste(event) {
    // Предотвращаем вставку форматированного текста
    event.preventDefault();
    
    const paste = (event.clipboardData || window.clipboardData).getData('text');
    const selection = window.getSelection();
    
    if (!selection.rangeCount) return;
    
    selection.deleteFromDocument();
    selection.getRangeAt(0).insertNode(document.createTextNode(paste));
    selection.collapseToEnd();
    
    // Обновляем состояние формы
    const element = event.target;
    const fieldName = element.getAttribute('data-field');
    if (fieldName && bannerFormState.hasOwnProperty(fieldName)) {
        bannerFormState[fieldName] = element.textContent.trim();
    }
}

/**
 * Валидация ссылки баннера
 */
function validateBannerLink(element) {
    const link = element.textContent.trim();
    
    if (link && !isValidUrl(link)) {
        element.classList.add('is-invalid');
        showNotification('Пожалуйста, введите корректную ссылку (например: https://example.com)', 'warning');
    } else {
        element.classList.remove('is-invalid');
    }
}

/**
 * Форматирование ссылки баннера
 */
function formatBannerLink(element) {
    let link = element.textContent.trim();
    
    if (link && !link.startsWith('http://') && !link.startsWith('https://')) {
        // Автоматически добавляем https:// если протокол не указан
        link = 'https://' + link;
        element.textContent = link;
        bannerFormState.link = link;
    }
}

/**
 * Проверка валидности URL
 */
function isValidUrl(string) {
    try {
        new URL(string);
        return true;
    } catch (_) {
        return false;
    }
}

/**
 * Обновление кнопки переключения ссылки
 */
function updateLinkToggleButton(hasLink) {
    const toggleButton = document.getElementById('link-toggle');
    if (!toggleButton) return;
    
    if (hasLink) {
        toggleButton.innerHTML = '<i class="bi bi-dash"></i> Убрать ссылку';
        toggleButton.className = 'btn btn-outline-danger btn-sm';
    } else {
        toggleButton.innerHTML = '<i class="bi bi-plus"></i> Добавить ссылку';
        toggleButton.className = 'btn btn-outline-success btn-sm';
    }
}

/**
 * Обработка отправки формы баннера
 */
async function handleBannerFormSubmit(event) {
    event.preventDefault();
    
    // Валидация
    if (!validateBannerForm()) {
        return;
    }
    
    // Обновляем состояние формы
    updateBannerFormState();
    
    // Создаем FormData
    const formData = new FormData();
    
    // Добавляем данные из состояния формы
    Object.keys(bannerFormState).forEach(key => {
        if (bannerFormState[key] !== null && bannerFormState[key] !== undefined) {
            formData.append(key, bannerFormState[key]);
        }
    });
    
    // Добавляем изображение если выбрано
    if (selectedBannerFile) {
        formData.append('image', selectedBannerFile);
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
        validateBeforeSubmit: validateBannerForm,
        onSuccess: (result) => {
            if (result.redirect_url) {
                window.location.href = result.redirect_url;
            }
        }
    });
}

/**
 * Обновление состояния формы баннера
 */
function updateBannerFormState() {
    // Собираем данные из всех редактируемых элементов
    const editableElements = document.querySelectorAll('[contenteditable="true"]');
    editableElements.forEach(element => {
        const fieldName = element.getAttribute('data-field');
        if (fieldName && bannerFormState.hasOwnProperty(fieldName)) {
            bannerFormState[fieldName] = element.textContent.trim();
        }
    });
    
    // Проверяем состояние ссылки
    const linkBlock = document.querySelector('.banner-link-settings');
    bannerFormState.hasLink = linkBlock && linkBlock.style.display !== 'none';
    
    // Обновляем состояние чекбокса активности
    const activeCheckbox = document.getElementById('is-active');
    if (activeCheckbox) {
        bannerFormState.isActive = activeCheckbox.checked;
    }
    
    // Обновляем позицию
    const positionInput = document.getElementById('position');
    if (positionInput) {
        bannerFormState.position = parseInt(positionInput.value) || 1;
    }
}

/**
 * Валидация формы баннера
 */
function validateBannerForm() {
    updateBannerFormState();
    
    // Проверяем обязательные поля
    if (!bannerFormState.title || bannerFormState.title.trim() === '') {
        showNotification('Пожалуйста, введите заголовок баннера', 'error');
        const titleElement = document.querySelector('[data-field="title"]');
        if (titleElement) {
            titleElement.focus();
            titleElement.classList.add('is-invalid');
            setTimeout(() => titleElement.classList.remove('is-invalid'), 3000);
        }
        return false;
    }
    
    // Проверяем ссылку если она указана
    if (bannerFormState.hasLink && bannerFormState.link) {
        if (!isValidUrl(bannerFormState.link)) {
            showNotification('Пожалуйста, введите корректную ссылку', 'error');
            const linkElement = document.querySelector('[data-field="link"]');
            if (linkElement) {
                linkElement.focus();
                linkElement.classList.add('is-invalid');
                setTimeout(() => linkElement.classList.remove('is-invalid'), 3000);
            }
            return false;
        }
    }
    
    // Проверяем позицию
    if (bannerFormState.position < 1 || bannerFormState.position > 100) {
        showNotification('Позиция должна быть от 1 до 100', 'error');
        const positionInput = document.getElementById('position');
        if (positionInput) {
            positionInput.focus();
            positionInput.classList.add('is-invalid');
            setTimeout(() => positionInput.classList.remove('is-invalid'), 3000);
        }
        return false;
    }
    
    return true;
}

/* ========================================
   Функции для страницы редактирования (edit.blade.php)
   ======================================== */

/**
 * Инициализация редактора баннеров
 * @param {Object} initialData - Начальные данные баннера
 */
function initBannerEditor(initialData = {}) {
    editFormState = {
        title: initialData.title || '',
        description: initialData.description || '',
        linkUrl: initialData.linkUrl || '',
        linkText: initialData.linkText || 'Перейти',
        orderIndex: initialData.orderIndex || 1,
        hasLink: initialData.hasLink || false
    };
    
    initializeEditSwiper();
    bindEditEvents();
    loadExistingData();
}

/**
 * Инициализация Swiper для редактирования
 */
function initializeEditSwiper() {
    const editSwiperElement = document.querySelector('#edit-banners-swiper');
    if (editSwiperElement) {
        editSwiper = new Swiper(editSwiperElement, {
            slidesPerView: 1,
            spaceBetween: 20,
            centeredSlides: true,
            autoHeight: false,
            height: 350,
            direction: 'horizontal',
            allowTouchMove: false
        });
    }
}

/**
 * Привязка событий для редактирования
 */
function bindEditEvents() {
    // Редактируемые элементы
    const titleElement = document.querySelector('.editable-title');
    const descriptionElement = document.querySelector('.editable-description');
    
    if (titleElement) {
        // События для title
        titleElement.addEventListener('input', function() {
            let text = this.textContent.trim();
            if (text.length > 100) {
                text = text.substring(0, 100);
                this.textContent = text;
            }
            editFormState.title = text;
            updateHiddenFields();
        });
        
        titleElement.addEventListener('blur', function() {
            if (!this.textContent.trim()) {
                this.textContent = 'Название баннера';
                editFormState.title = '';
            }
        });
    }
    
    if (descriptionElement) {
        // События для description
        descriptionElement.addEventListener('input', function() {
            let text = this.textContent.trim();
            if (text.length > 300) {
                text = text.substring(0, 300);
                this.textContent = text;
            }
            editFormState.description = text;
            updateHiddenFields();
        });
        
        descriptionElement.addEventListener('blur', function() {
            if (!this.textContent.trim()) {
                this.textContent = 'Описание баннера. Нажмите, чтобы редактировать.';
                editFormState.description = '';
            }
        });
    }
    
    // События для дополнительных настроек
    const orderInput = document.getElementById('order-input');
    if (orderInput) {
        orderInput.addEventListener('input', function() {
            editFormState.orderIndex = this.value;
            updateHiddenFields();
        });
    }
    
    // События для настроек ссылки
    const linkUrlInput = document.getElementById('link-url-input');
    if (linkUrlInput) {
        linkUrlInput.addEventListener('input', function() {
            editFormState.linkUrl = this.value;
            updateHiddenFields();
        });
    }
    
    // Обработчик для скрытого input изображения
    const hiddenImageInput = document.getElementById('hidden-image');
    if (hiddenImageInput) {
        hiddenImageInput.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const file = e.target.files[0];
                selectedBannerFile = file;
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const noImage = document.getElementById('banner-no-image');
                    const previewImage = document.getElementById('banner-preview-image');
                    
                    if (noImage && previewImage) {
                        noImage.style.display = 'none';
                        previewImage.src = e.target.result;
                        previewImage.style.display = 'block';
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
}

/**
 * Загрузка существующих данных баннера
 */
function loadExistingData() {
    // Загружаем данные в состояние формы
    updateHiddenFields();
    
    // Обрабатываем отображение чекбокса ссылки и связанных настроек
    if (editFormState.hasLink) {
        const hasLinkCheckbox = document.getElementById('has-link-checkbox');
        const linkSettings = document.getElementById('link-settings');
        const linkDisplay = document.getElementById('banner-link-display');
        const addLinkBtn = document.getElementById('add-link-card-btn');
        
        if (hasLinkCheckbox) hasLinkCheckbox.checked = true;
        if (linkSettings) linkSettings.style.display = 'block';
        if (linkDisplay) linkDisplay.style.display = 'block';
        if (addLinkBtn) addLinkBtn.style.display = 'none';
    }
}

/**
 * Выбор изображения
 */
function selectImage() {
    const hiddenImage = document.getElementById('hidden-image');
    if (hiddenImage) {
        hiddenImage.click();
    }
}

/**
 * Выделение текста при клике
 */
function selectText(element) {
    const range = document.createRange();
    range.selectNodeContents(element);
    const selection = window.getSelection();
    selection.removeAllRanges();
    selection.addRange(range);
}

/**
 * Добавление ссылки прямо в карточке
 */
function addLinkInCard() {
    // Включаем чекбокс в дополнительных настройках
    const hasLinkCheckbox = document.getElementById('has-link-checkbox');
    if (hasLinkCheckbox) {
        hasLinkCheckbox.checked = true;
    }
    
    // Показываем дополнительные настройки
    const advancedSettings = document.getElementById('advanced-settings');
    if (advancedSettings && advancedSettings.style.display === 'none') {
        advancedSettings.style.display = 'block';
    }
    
    // Показываем настройки ссылки
    toggleLinkSettings();
    
    // Устанавливаем фокус на URL поле
    setTimeout(() => {
        const linkUrlInput = document.getElementById('link-url-input');
        if (linkUrlInput) {
            linkUrlInput.focus();
        }
    }, 100);
}

/**
 * Переключение настроек ссылки
 */
function toggleLinkSettings() {
    const checkbox = document.getElementById('has-link-checkbox');
    const linkSettings = document.getElementById('link-settings');
    const linkElement = document.getElementById('banner-link-display');
    const addLinkButton = document.getElementById('add-link-card-btn');
    
    if (checkbox && checkbox.checked) {
        if (linkSettings) linkSettings.style.display = 'block';
        if (linkElement) linkElement.style.display = 'block';
        if (addLinkButton) addLinkButton.style.display = 'none';
        editFormState.hasLink = true;
        
        // Устанавливаем начальные значения
        if (!editFormState.linkText) {
            editFormState.linkText = 'Перейти';
            updateLinkDisplay();
        }
    } else {
        if (linkSettings) linkSettings.style.display = 'none';
        if (linkElement) linkElement.style.display = 'none';
        if (addLinkButton) addLinkButton.style.display = 'inline-block';
        editFormState.hasLink = false;
        editFormState.linkUrl = '';
        editFormState.linkText = '';
    }
    
    updateHiddenFields();
}

/**
 * Обновление текста ссылки
 */
function updateLinkText() {
    const selectElement = document.getElementById('link-text-select');
    const linkButton = document.getElementById('banner-link-button');
    
    if (selectElement && linkButton) {
        editFormState.linkText = selectElement.value;
        linkButton.textContent = selectElement.value;
        
        updateHiddenFields();
    }
}

/**
 * Обновление отображения ссылки
 */
function updateLinkDisplay() {
    const linkButton = document.getElementById('banner-link-button');
    if (linkButton) {
        linkButton.textContent = editFormState.linkText || 'Перейти';
    }
}

/**
 * Переключение дополнительных настроек
 */
function toggleAdvanced() {
    const advancedSettings = document.getElementById('advanced-settings');
    if (advancedSettings) {
        if (advancedSettings.style.display === 'none') {
            advancedSettings.style.display = 'block';
        } else {
            advancedSettings.style.display = 'none';
        }
    }
}

/**
 * Обновление скрытых полей формы
 */
function updateHiddenFields() {
    const hiddenTitle = document.getElementById('hidden-title');
    const hiddenDescription = document.getElementById('hidden-description');
    const hiddenLinkUrl = document.getElementById('hidden-link-url');
    const hiddenLinkText = document.getElementById('hidden-link-text');
    const hiddenOrderIndex = document.getElementById('hidden-order-index');
    
    if (hiddenTitle) hiddenTitle.value = editFormState.title;
    if (hiddenDescription) hiddenDescription.value = editFormState.description;
    if (hiddenLinkUrl) hiddenLinkUrl.value = editFormState.linkUrl;
    if (hiddenLinkText) hiddenLinkText.value = editFormState.linkText;
    if (hiddenOrderIndex) hiddenOrderIndex.value = editFormState.orderIndex;
}

/**
 * Валидация и сохранение
 */
function saveBanner() {
    // Проверяем обязательные поля
    if (!editFormState.title || editFormState.title === 'Название баннера') {
        alert('Пожалуйста, введите название баннера');
        const titleElement = document.querySelector('.editable-title');
        if (titleElement) titleElement.focus();
        return;
    }
    
    if (!editFormState.description || editFormState.description === 'Описание баннера. Нажмите, чтобы редактировать.') {
        alert('Пожалуйста, введите описание баннера');
        const descriptionElement = document.querySelector('.editable-description');
        if (descriptionElement) descriptionElement.focus();
        return;
    }
    
    // Показываем индикатор загрузки
    showLoading();
    
    // Обновляем скрытые поля и отправляем форму
    updateHiddenFields();
    const form = document.getElementById('banner-form');
    if (form) {
        form.submit();
    }
}

/* ========================================
   Функции для страницы списка баннеров (index.blade.php)
   ======================================== */

/**
 * Инициализация Swiper для списка баннеров в админке
 */
function initBannersIndexSwiper() {
    const bannersPreviewSwiper = document.querySelector('#banners-preview-swiper');
    if (bannersPreviewSwiper) {
        const swiper = new Swiper(bannersPreviewSwiper, {
            slidesPerView: 1,
            spaceBetween: 20,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                576: {
                    slidesPerView: 1,
                    spaceBetween: 20,
                },
                768: {
                    slidesPerView: 1,
                    spaceBetween: 20,
                },
                992: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                1200: {
                    slidesPerView: 2,
                    spaceBetween: 25,
                },
                1400: {
                    slidesPerView: 3,
                    spaceBetween: 25,
                },
            },
            // Настройки для админки
            autoHeight: false,
            centeredSlides: false,
            loop: false,
            grabCursor: true,
            watchOverflow: true,
            resistance: true,
            resistanceRatio: 0.85,
        });
    }
}

/**
 * Удаление баннера
 */
function deleteBanner(bannerId) {
    const form = document.getElementById('deleteForm');
    if (form) {
        // Получаем currentUserId из URL или глобальной переменной
        const currentUserId = window.currentUserId || document.querySelector('[data-user-id]')?.dataset.userId;
        form.action = `/admin/user/${currentUserId}/banners/${bannerId}`;
        
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }
}

/**
 * Редактирование баннера
 */
function editBanner(bannerId) {
    // Получаем currentUserId из URL или глобальной переменной
    const currentUserId = window.currentUserId || document.querySelector('[data-user-id]')?.dataset.userId;
    window.location.href = `/admin/user/${currentUserId}/banners/${bannerId}/edit`;
}

/* ========================================
   Универсальные функции
   ======================================== */

/**
 * Показать индикатор загрузки
 */
function showLoading() {
    const loadingOverlay = document.getElementById('loading-overlay');
    if (loadingOverlay) {
        loadingOverlay.style.display = 'flex';
    }
}

/**
 * Скрыть индикатор загрузки
 */
function hideLoading() {
    const loadingOverlay = document.getElementById('loading-overlay');
    if (loadingOverlay) {
        loadingOverlay.style.display = 'none';
    }
}

// Экспортируем функции для глобального использования
window.initBannerPage = initBannerPage;
window.initBannerEditor = initBannerEditor;
window.initBannersIndexSwiper = initBannersIndexSwiper;
window.validateBannerForm = validateBannerForm;
window.updateLinkToggleButton = updateLinkToggleButton;
window.selectImage = selectImage;
window.selectText = selectText;
window.addLinkInCard = addLinkInCard;
window.toggleLinkSettings = toggleLinkSettings;
window.updateLinkText = updateLinkText;
window.toggleAdvanced = toggleAdvanced;
window.saveBanner = saveBanner;
window.deleteBanner = deleteBanner;
window.editBanner = editBanner;
window.showLoading = showLoading;
window.hideLoading = hideLoading;

// Автоинициализация при загрузке DOM
document.addEventListener('DOMContentLoaded', function() {
    // Проверяем, на какой странице баннеров мы находимся
    if (document.getElementById('banner-form') && !document.getElementById('edit-banners-swiper')) {
        // Страница создания баннера
        initBannerPage();
    } else if (document.getElementById('edit-banners-swiper')) {
        // Страница редактирования баннера
        // Данные баннера будут переданы из Blade шаблона
        const bannerData = window.bannerData || {};
        initBannerEditor(bannerData);
    } else if (document.getElementById('banners-preview-swiper')) {
        // Страница списка баннеров
        initBannersIndexSwiper();
    }
});