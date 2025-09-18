<script>
document.addEventListener('DOMContentLoaded', function() {
    // Функция для настройки счетчика символов
    function setupCharCounter(inputId, counterId, maxLength) {
        const input = document.getElementById(inputId);
        const counter = document.getElementById(counterId);
        
        if (!input || !counter) return;
        
        function updateCounter() {
            const currentLength = input.value.length;
            const remaining = maxLength - currentLength;
            counter.textContent = remaining;
            
            if (remaining < 0) {
                counter.style.color = '#dc3545';
            } else if (remaining < 20) {
                counter.style.color = '#fd7e14';
            } else {
                counter.style.color = '#6c757d';
            }
        }
        
        updateCounter();
        input.addEventListener('input', updateCounter);
        input.addEventListener('keydown', updateCounter);
        input.addEventListener('paste', function() {
            setTimeout(updateCounter, 10);
        });
    }

    // Настраиваем счетчики для всех полей
    setupCharCounter('name', 'name-counter', 50);
    setupCharCounter('bio', 'bio-counter', 190);
    setupCharCounter('telegram_url', 'telegram-counter', 255);
    setupCharCounter('whatsapp_url', 'whatsapp-counter', 255);
    setupCharCounter('vk_url', 'vk-counter', 255);
    setupCharCounter('youtube_url', 'youtube-counter', 255);
    setupCharCounter('ok_url', 'ok-counter', 255);

    // Обработка вкладок - сохранение состояния и переход по URL
    const tabElements = document.querySelectorAll('#profileTabs [data-bs-toggle="tab"]');
    
    tabElements.forEach(tabElement => {
        tabElement.addEventListener('shown.bs.tab', function (event) {
            const tabId = event.target.getAttribute('aria-controls');
            // Обновляем URL без перезагрузки страницы
            const url = new URL(window.location);
            const pathParts = url.pathname.split('/');
            
            // Если последняя часть URL - это вкладка, заменяем её
            if (['basic', 'images', 'social', 'security', 'sections'].includes(pathParts[pathParts.length - 1])) {
                pathParts[pathParts.length - 1] = tabId;
            } else {
                // Добавляем вкладку в конец URL
                pathParts.push(tabId);
            }
            
            const newPath = pathParts.join('/');
            window.history.replaceState(null, '', newPath);
        });
    });

    // Валидация пароля
    const currentPasswordInput = document.getElementById('current_password');
    const passwordInput = document.getElementById('password');
    const passwordConfirmationInput = document.getElementById('password_confirmation');
    
    if (currentPasswordInput && passwordInput && passwordConfirmationInput) {
        function validatePasswords() {
            // Если заполнен текущий пароль, то новый пароль обязателен
            if (currentPasswordInput.value && !passwordInput.value) {
                passwordInput.setCustomValidity('Введите новый пароль');
            } else if (passwordInput.value && passwordInput.value !== passwordConfirmationInput.value) {
                passwordConfirmationInput.setCustomValidity('Пароли не совпадают');
            } else {
                passwordInput.setCustomValidity('');
                passwordConfirmationInput.setCustomValidity('');
            }
        }
        
        currentPasswordInput.addEventListener('input', validatePasswords);
        passwordInput.addEventListener('input', validatePasswords);
        passwordConfirmationInput.addEventListener('input', validatePasswords);
    }

    // Предварительный просмотр изображений
    const backgroundInput = document.getElementById('background-input');
    const avatarInput = document.getElementById('avatar-input');
    const backgroundPreviewArea = document.getElementById('background-preview-area');
    const avatarPreviewArea = document.getElementById('avatar-preview-area');
    const avatarOverlay = document.getElementById('avatar-overlay');
    
    // Обработка загрузки фонового изображения
    if (backgroundInput && backgroundPreviewArea) {
        backgroundInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imageUrl = e.target.result;
                    
                    // Обновляем фон с плавным переходом
                    backgroundPreviewArea.style.transition = 'background-image 0.5s ease, filter 0.3s ease';
                    backgroundPreviewArea.style.backgroundImage = `url('${imageUrl}')`;
                    
                    // Эффект загрузки
                    backgroundPreviewArea.style.filter = 'brightness(0.7) blur(2px)';
                    setTimeout(() => {
                        backgroundPreviewArea.style.filter = 'brightness(0.9)';
                    }, 300);
                    
                    // Автоматическая отправка формы после выбора изображения
                    setTimeout(() => {
                        if (confirm('Загрузить выбранное фоновое изображение?')) {
                            document.getElementById('images-form').submit();
                        }
                    }, 500);
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Обработка загрузки аватара
    if (avatarInput && avatarPreviewArea) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imageUrl = e.target.result;
                    
                    // Обновляем аватар с плавным переходом
                    avatarPreviewArea.style.transition = 'background-image 0.5s ease, transform 0.3s ease';
                    avatarPreviewArea.style.backgroundImage = `url('${imageUrl}')`;
                    
                    // Эффект загрузки
                    avatarPreviewArea.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        avatarPreviewArea.style.transform = 'scale(1)';
                    }, 300);
                    
                    // Автоматическая отправка формы после выбора изображения
                    setTimeout(() => {
                        if (confirm('Загрузить выбранный аватар?')) {
                            document.getElementById('images-form').submit();
                        }
                    }, 500);
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Эффекты наведения для аватара
    if (avatarPreviewArea && avatarOverlay) {
        avatarPreviewArea.addEventListener('mouseenter', function() {
            avatarOverlay.style.opacity = '1';
            this.style.transform = 'scale(1.05)';
        });
        
        avatarPreviewArea.addEventListener('mouseleave', function() {
            avatarOverlay.style.opacity = '0';
            this.style.transform = 'scale(1)';
        });
    }
    
    // Эффекты наведения для фона
    if (backgroundPreviewArea) {
        backgroundPreviewArea.addEventListener('mouseenter', function() {
            this.style.filter = 'brightness(1.1)';
        });
        
        backgroundPreviewArea.addEventListener('mouseleave', function() {
            this.style.filter = 'brightness(0.9)';
        });
    }
    
    // Улучшение контейнера с изображениями
    const imageContainer = document.querySelector('.image-preview-container');
    if (imageContainer) {
        imageContainer.addEventListener('mouseenter', function() {
            this.style.borderColor = '#007bff';
            this.style.borderStyle = 'solid';
            this.style.boxShadow = '0 4px 20px rgba(0, 123, 255, 0.15)';
        });
        
        imageContainer.addEventListener('mouseleave', function() {
            this.style.borderColor = '#dee2e6';
            this.style.borderStyle = 'dashed';
            this.style.boxShadow = 'none';
        });
    }

    // Инициализация управления секциями
    initializeSectionsManagement();
});

// Функция для отображения предпросмотра иконки
function updateIconPreview(selectElement, previewElement) {
    const selectedIcon = selectElement.value;
    if (selectedIcon) {
        previewElement.className = 'bi ' + selectedIcon;
    } else {
        previewElement.className = 'bi bi-question-circle';
    }
}

// Обработчики событий для предпросмотра иконок
if (document.getElementById('icon_class')) {
    document.getElementById('icon_class').addEventListener('change', function() {
        updateIconPreview(this, document.getElementById('icon-preview'));
    });
}

if (document.getElementById('edit_icon_class')) {
    document.getElementById('edit_icon_class').addEventListener('change', function() {
        updateIconPreview(this, document.getElementById('edit-icon-preview'));
    });
}

// Функция для открытия модального окна редактирования
function editSocialLink(id, serviceName, url, iconClass) {
    document.getElementById('edit_service_name').value = serviceName;
    document.getElementById('edit_url').value = url;
    document.getElementById('edit_icon_class').value = iconClass;
    
    // Обновляем предпросмотр иконки
    updateIconPreview(
        document.getElementById('edit_icon_class'), 
        document.getElementById('edit-icon-preview')
    );
    
    // Устанавливаем action для формы
    document.getElementById('editSocialLinkForm').action = 
        "<?php echo e(route('admin.social-links.update', [$user->id, ':id'])); ?>".replace(':id', id);
    
    // Показываем модальное окно
    new bootstrap.Modal(document.getElementById('editSocialLinkModal')).show();
}

// Функция для удаления социальной ссылки
function deleteSocialLink(id) {
    if (confirm('Вы уверены, что хотите удалить эту социальную ссылку?')) {
        // Создаем форму для удаления
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "<?php echo e(route('admin.social-links.destroy', [$user->id, ':id'])); ?>".replace(':id', id);
        
        // Добавляем CSRF токен
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfToken);
        
        // Добавляем метод DELETE
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Функция для обновления видимости кнопки добавления социальной ссылки
function updateSocialLinksButton() {
    const socialLinksCount = document.querySelectorAll('[data-link-id]').length;
    const addButton = document.getElementById('addSocialLinkBtn');
    const limitBadge = document.querySelector('.social-limit-badge');
    
    if (socialLinksCount >= 5) {
        if (addButton) {
            addButton.style.display = 'none';
        }
        if (!limitBadge) {
            const badge = document.createElement('span');
            badge.className = 'badge bg-warning text-dark social-limit-badge';
            badge.innerHTML = '<i class="bi bi-exclamation-triangle me-1"></i>Лимит достигнут (5/5)';
            addButton.parentNode.appendChild(badge);
        }
    } else {
        if (addButton) {
            addButton.style.display = 'inline-block';
        }
        if (limitBadge) {
            limitBadge.remove();
        }
    }
}

// Вызываем функцию при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('addSocialLinkBtn')) {
        updateSocialLinksButton();
    }
});

// Функция для показа/скрытия пароля
function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}

// Инициализация управления секциями
function initializeSectionsManagement() {
    console.log('Инициализация управления секциями');
    const sectionsTab = document.getElementById('sections-tab');
    console.log('Вкладка sections найдена:', !!sectionsTab);
    if (!sectionsTab) return;

    sectionsTab.addEventListener('click', function() {
        console.log('Клик по вкладке sections, загружаем секции через 100ms');
        setTimeout(loadSections, 100);
    });

    // Если вкладка sections активна по умолчанию, загружаем секции сразу
    if (sectionsTab.classList.contains('active')) {
        console.log('Вкладка sections активна по умолчанию, загружаем секции');
        setTimeout(loadSections, 100);
    }

    // Обработчики кнопок
    const saveSectionsBtn = document.getElementById('saveSectionsBtn');

    if (saveSectionsBtn) {
        saveSectionsBtn.addEventListener('click', saveSections);
    }
}

// Загрузка секций
function loadSections() {
    console.log('Функция loadSections() запущена');
    const container = document.getElementById('sections-container');
    const form = document.getElementById('sectionsForm');
    
    console.log('Container найден:', !!container);
    console.log('Form найден:', !!form);
    
    if (!container || !form) {
        console.error('Не найден container или form');
        return;
    }

    const getUrl = form.getAttribute('data-get-url');
    console.log('URL для загрузки:', getUrl);
    
    if (!getUrl) {
        showAlert('Ошибка конфигурации: не найден URL для загрузки секций', 'danger');
        return;
    }

    console.log('Начинаем fetch запрос к:', getUrl);

    fetch(getUrl, {
        method: 'GET',
        credentials: 'same-origin',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
        .then(response => {
            console.log('Получен ответ от сервера:', response.status, response.statusText);
            console.log('Response OK:', response.ok);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Данные секций получены:', data);
            if (data.success) {
                renderSections(data.sections);
            } else {
                console.error('Ошибка в данных:', data.message);
                showAlert('Ошибка загрузки секций: ' + (data.message || 'Неизвестная ошибка'), 'danger');
            }
        })
        .catch(error => {
            console.error('Ошибка fetch:', error);
            showAlert('Ошибка загрузки секций: ' + error.message, 'danger');
            // Показываем сообщение об ошибке в контейнере
            const container = document.getElementById('sections-container');
            if (container) {
                container.innerHTML = `
                    <div class="alert alert-danger">
                        <h5>Ошибка загрузки секций</h5>
                        <p>Произошла ошибка при загрузке настроек секций: ${error.message}</p>
                        <button class="btn btn-outline-danger" onclick="loadSections()">Попробовать снова</button>
                    </div>
                `;
            }
        });
}

// Отрисовка секций
function renderSections(sections) {
    console.log('=== НАЧАЛО РЕНДЕРИНГА СЕКЦИЙ ===');
    console.log('Получены секции для рендеринга:', sections);
    console.log('Количество секций:', sections ? sections.length : 0);
    
    const container = document.getElementById('sections-container');
    console.log('Container для рендеринга найден:', !!container);
    
    if (!container) {
        console.error('Container для секций не найден!');
        return;
    }
    
    const html = `
        <div class="sections-list" id="sectionsList">
            ${sections.map(section => {
                return `
                <div class="section-item card mb-3" data-section-key="${section.section_key}">
                    <div class="card-body">
                        <div class="section-header mb-3">
                            <h6 class="mb-1 fw-bold text-primary">${section.section_name}</h6>
                            <small class="text-muted">Порядок: ${section.order}</small>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Заголовок</label>
                                <select class="form-control section-title">
                                    ${section.available_titles.map(title => 
                                        `<option value="${title}" ${title === section.title ? 'selected' : ''}>${title}</option>`
                                    ).join('')}
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Подзаголовок</label>
                                <select class="form-control section-subtitle">
                                    ${section.available_subtitles.map(subtitle => 
                                        `<option value="${subtitle}" ${subtitle === section.subtitle ? 'selected' : ''}>${subtitle}</option>`
                                    ).join('')}
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                `
            }).join('')}
        </div>
    `;
    
    console.log('HTML для рендеринга сгенерирован, длина:', html.length);
    container.innerHTML = html;
    console.log('HTML вставлен в контейнер');
    
    // Обработчики событий
    addSectionEventHandlers();
    
    console.log('=== РЕНДЕРИНГ СЕКЦИЙ ЗАВЕРШЕН ===');
}

// Тестовая функция для проверки AJAX
function testAjax() {
    console.log('=== ТЕСТ AJAX ЗАПРОСА ===');
    
    fetch('/test-ajax', {
        method: 'GET',
        credentials: 'same-origin',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Тест AJAX - статус:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Тест AJAX - данные:', data);
    })
    .catch(error => {
        console.error('Тест AJAX - ошибка:', error);
    });
}

// Тестовая функция для проверки секций
function testSections(userId) {
    console.log('=== ТЕСТ ЗАПРОСА СЕКЦИЙ ===');
    
    fetch(`/test-sections/${userId}`, {
        method: 'GET',
        credentials: 'same-origin',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => {
        console.log('Тест секций - статус:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Тест секций - данные:', data);
    })
    .catch(error => {
        console.error('Тест секций - ошибка:', error);
    });
}

// Добавление обработчиков событий для секций
function addSectionEventHandlers() {
    console.log('Добавляем обработчики событий для секций');
    
    // Обработчики для выпадающих списков заголовков и подзаголовков
    document.querySelectorAll('.section-title, .section-subtitle').forEach(select => {
        select.addEventListener('change', function() {
            console.log('Изменение в селекте:', this.value);
        });
    });
}

// Сохранение настроек секций
function saveSections() {
    const form = document.getElementById('sectionsForm');
    if (!form) {
        console.error('Форма секций не найдена');
        return;
    }

    const updateUrl = form.getAttribute('data-update-url');
    if (!updateUrl) {
        showAlert('Ошибка конфигурации: не найден URL для сохранения секций', 'danger');
        return;
    }

    const sections = [];
    document.querySelectorAll('.section-item').forEach((item, index) => {
        const titleValue = item.querySelector('.section-title').value;
        const subtitleValue = item.querySelector('.section-subtitle').value;
        
        const sectionData = {
            section_key: item.dataset.sectionKey,
            title: titleValue === 'Пусто' ? '' : titleValue,
            subtitle: subtitleValue === 'Пусто' ? '' : subtitleValue
        };
        
        console.log('Секция для сохранения:', sectionData);
        sections.push(sectionData);
    });

    console.log('Все секции для сохранения:', sections);

    const saveBtn = document.getElementById('saveSectionsBtn');
    const originalText = saveBtn.innerHTML;
    saveBtn.innerHTML = '<i class="bi bi-hourglass-split spin me-2"></i>Сохранение...';
    saveBtn.disabled = true;

    console.log('Отправляем запрос на:', updateUrl);
    console.log('Данные запроса:', { sections: sections });

    fetch(updateUrl, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ sections: sections })
    })
    .then(response => {
        console.log('Статус ответа:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Ответ сервера:', data);
        if (data.success) {
            showAlert(data.message || 'Настройки секций успешно сохранены', 'success');
        } else {
            showAlert('Ошибка сохранения: ' + (data.message || 'Неизвестная ошибка'), 'danger');
            console.error('Ошибка от сервера:', data);
        }
    })
    .catch(error => {
        console.error('Ошибка запроса:', error);
        showAlert('Ошибка сохранения настроек', 'danger');
    })
    .finally(() => {
        saveBtn.innerHTML = originalText;
        saveBtn.disabled = false;
    });
}

// Показ уведомлений
function showAlert(message, type = 'info') {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    const container = document.getElementById('sections-container');
    if (container) {
        container.insertAdjacentHTML('beforebegin', alertHtml);
        
        // Автоматически скрыть через 5 секунд
        setTimeout(() => {  
            const alert = container.previousElementSibling;
            if (alert && alert.classList.contains('alert')) {
                alert.remove();
            }
        }, 5000); 
    }
}

// Функции для открытия редактора изображений
function openAvatarEditor() {
    console.log('Opening avatar editor from admin panel');
    if (window.imageEditor && typeof window.imageEditor.openEditor === 'function') {
        window.imageEditor.openEditor('avatar');
    } else {
        // Если редактор еще не загружен, ждем его
        document.addEventListener('imageEditorReady', function() {
            if (window.imageEditor) {
                window.imageEditor.openEditor('avatar');
            }
        }, { once: true });
        
        // Если через 1 секунду редактор так и не загрузился, показываем ошибку
        setTimeout(() => {
            if (!window.imageEditor) {
                console.error('Image editor not loaded');
                alert('Редактор изображений не загружен. Пожалуйста, обновите страницу.');
            }
        }, 1000);
    }
}

function openBackgroundEditor() {
    console.log('Opening background editor from admin panel');
    if (window.imageEditor && typeof window.imageEditor.openEditor === 'function') {
        window.imageEditor.openEditor('background');
    } else {
        // Если редактор еще не загружен, ждем его
        document.addEventListener('imageEditorReady', function() {
            if (window.imageEditor) {
                window.imageEditor.openEditor('background');
            }
        }, { once: true });
        
        // Если через 1 секунду редактор так и не загрузился, показываем ошибку
        setTimeout(() => {
            if (!window.imageEditor) {
                console.error('Image editor not loaded');
                alert('Редактор изображений не загружен. Пожалуйста, обновите страницу.');
            }
        }, 1000);
    }
}
</script><?php /**PATH C:\OSPanel\domains\link\resources\views/admin/profile/scripts.blade.php ENDPATH**/ ?>