{{-- Секция Hero --}}
<section class="hero {{ isset($currentUser) && $currentUser && $currentUser->id === $pageUser->id ? 'owner-mode' : '' }}"
    aria-label="Главная информация о {{ $pageUser->name }}"
    @if(isset($currentUser) && $currentUser && $currentUser->id === $pageUser->id)
        id="hero-background-section"
        data-owner="true"
        title="Нажмите на фон, чтобы изменить его"
    @endif>
    
    @php
        $desktopBg = $pageUser->background_image_pc ?: $pageUser->background_image ?: '/hero.png';
        $mobileBg = $pageUser->background_image_mobile ?: $pageUser->background_image ?: '/hero.png';
        
        if (!str_starts_with($desktopBg, '/') && !str_starts_with($desktopBg, 'http')) {
            $desktopBg = asset('storage/' . $desktopBg);
        } else if (str_starts_with($desktopBg, '/') && $desktopBg !== '/hero.png') {
            $desktopBg = asset($desktopBg);
        } else {
            $desktopBg = asset($desktopBg);
        }
        
        if (!str_starts_with($mobileBg, '/') && !str_starts_with($mobileBg, 'http')) {
            $mobileBg = asset('storage/' . $mobileBg);
        } else if (str_starts_with($mobileBg, '/') && $mobileBg !== '/hero.png') {
            $mobileBg = asset($mobileBg);
        } else {
            $mobileBg = asset($mobileBg);
        }
    @endphp
    
    <!-- Адаптивный фон -->
    <style>
        .hero {
            background-image: url('{{ $desktopBg }}');
        }
        
        @media (max-width: 768px) {
            .hero {
                background-image: url('{{ $mobileBg }}');
            }
        }
    </style>
    

    <div class="container">
        <div class="hero-section">
            <div class="hero-info">
                @php
                    $displayTitle = (isset($section) && !empty(trim($section->title))) ? $section->title : $pageUser->name;
                    $displayBio = '';
                    if (isset($section) && !empty(trim($section->subtitle))) {
                        $displayBio = $section->subtitle;
                    } elseif ($pageUser->bio) {
                        $displayBio = $pageUser->bio;
                    } else {
                        $displayBio = 'Добро пожаловать на мою страницу!';
                    }
                    $isOwner = isset($currentUser) && $currentUser && $currentUser->id === $pageUser->id;
                @endphp
                
                @if($isOwner)
                    <h1 class="editable-name" id="editable-name" title="Нажмите, чтобы изменить имя">{{ $displayTitle }}</h1>
                @else
                    <h1>{{ $displayTitle }}</h1>
                @endif
                
                <p>@ {{ $pageUser->username }}</p>
                
                @if($isOwner)
                    <p class="editable-bio" id="editable-bio" title="Нажмите, чтобы изменить описание">{{ $displayBio }}</p>
                @else
                    <p>{{ $displayBio }}</p>
                @endif
                
                <ul class="hero-links">
                    @if ($pageUser->telegram_url)
                        <a href="{{ $pageUser->telegram_url }}" target="_blank" class="social-link telegram"
                            title="Telegram">
                            <i class="bi bi-telegram"></i>
                        </a>
                    @endif

                    @if ($pageUser->whatsapp_url)
                        <a href="{{ $pageUser->whatsapp_url }}" target="_blank" class="social-link whatsapp"
                            title="WhatsApp">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                    @endif

                    @if ($pageUser->vk_url)
                        <a href="{{ $pageUser->vk_url }}" target="_blank" class="social-link vk" title="ВКонтакте">
                            <i class="bi bi-chat-square-text"></i>
                        </a>
                    @endif

                    @if ($pageUser->youtube_url)
                        <a href="{{ $pageUser->youtube_url }}" target="_blank" class="social-link youtube"
                            title="YouTube">
                            <i class="bi bi-youtube"></i>
                        </a>
                    @endif

                    @if ($pageUser->ok_url)
                        <a href="{{ $pageUser->ok_url }}" target="_blank" class="social-link ok" title="Одноклассники">
                            <i class="bi bi-people-fill"></i>
                        </a>
                    @endif

                    {{-- Дополнительные социальные ссылки --}}
                    @if($socialLinks && $socialLinks->count() > 0)
                        @foreach($socialLinks as $link)
                            @php
                                $serviceClass = '';
                                $serviceName = strtolower($link->service_name);
                                if (str_contains($serviceName, 'instagram')) $serviceClass = 'instagram';
                                elseif (str_contains($serviceName, 'github')) $serviceClass = 'github';
                                elseif (str_contains($serviceName, 'linkedin')) $serviceClass = 'linkedin';
                                elseif (str_contains($serviceName, 'facebook')) $serviceClass = 'facebook';
                                elseif (str_contains($serviceName, 'twitter')) $serviceClass = 'twitter';
                                elseif (str_contains($serviceName, 'discord')) $serviceClass = 'discord';
                                elseif (str_contains($serviceName, 'tiktok')) $serviceClass = 'tiktok';
                                elseif (str_contains($serviceName, 'pinterest')) $serviceClass = 'pinterest';
                                elseif (str_contains($serviceName, 'email') || str_contains($serviceName, 'mail')) $serviceClass = 'email';
                                elseif (str_contains($serviceName, 'портфолио') || str_contains($serviceName, 'portfolio')) $serviceClass = 'portfolio';
                                elseif (str_contains($serviceName, 'сайт') || str_contains($serviceName, 'website') || str_contains($serviceName, 'ссылка')) $serviceClass = 'website';
                            @endphp
                            <a href="{{ $link->url }}" target="_blank" class="social-link custom {{ $serviceClass }}" title="{{ $link->service_name }}">
                                <i class="bi {{ $link->icon_class }}"></i>
                            </a>
                        @endforeach
                    @endif
                </ul>
            </div>
            <div class="hero-logo"
                @if(isset($currentUser) && $currentUser && $currentUser->id === $pageUser->id)
                    onclick="openPhotoEditor('avatar'); event.stopPropagation();" 
                    style="cursor: pointer;" 
                    title="Нажмите, чтобы изменить аватар"
                @endif>
                
                <x-optimized-image 
                    :src="$pageUser->avatar ? asset('storage/' . $pageUser->avatar) : null"
                    :alt="'Фотография ' . $pageUser->name"
                    width="150"
                    height="150"
                    loading="eager"
                    fetchpriority="high" />
            </div>
        </div>
    </div>
    
    @if(isset($currentUser) && $currentUser && $currentUser->id === $pageUser->id)
        {{-- Модальное окно для редактирования текста --}}
        <div class="edit-modal" id="edit-modal">
            <div class="edit-modal-content">
                <h3 id="edit-modal-title">Редактировать</h3>
                <div class="edit-field-container">
                    <input type="text" id="edit-input" style="display: none;" maxlength="50" placeholder="Введите имя...">
                    <textarea id="edit-textarea" style="display: none;" maxlength="190" placeholder="Расскажите о себе..."></textarea>
                    <div class="character-counter" id="character-counter">
                        <span id="current-length">0</span> / <span id="max-length">50</span>
                    </div>
                </div>
                <div class="edit-modal-buttons">
                    <button type="button" class="btn-cancel" id="edit-cancel">Отмена</button>
                    <button type="button" class="btn-save" id="edit-save">Сохранить</button>
                </div>
            </div>
        </div>
        
        {{-- CSRF токен для AJAX запросов --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endif
</section>

@if(isset($currentUser) && $currentUser && $currentUser->id === $pageUser->id)

    @if(isset($currentUser) && $currentUser && $currentUser->id === $pageUser->id)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const heroCSRFToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            // Элементы для редактирования текста
            const editableNameElement = document.getElementById('editable-name');
            const editableBioElement = document.getElementById('editable-bio');
            const editModal = document.getElementById('edit-modal');
            const editModalTitle = document.getElementById('edit-modal-title');
            const editInput = document.getElementById('edit-input');
            const editTextarea = document.getElementById('edit-textarea');
            const editSaveBtn = document.getElementById('edit-save');
            const editCancelBtn = document.getElementById('edit-cancel');
            const characterCounter = document.getElementById('character-counter');
            const currentLengthSpan = document.getElementById('current-length');
            const maxLengthSpan = document.getElementById('max-length');
            
            let currentEditType = null;
            let currentEditElement = null;
            
            // Функция для обновления счетчика символов
            function updateCharacterCounter(input, maxLength) {
                const currentLength = input.value.length;
                currentLengthSpan.textContent = currentLength;
                maxLengthSpan.textContent = maxLength;
                
                // Изменяем цвет в зависимости от количества символов
                characterCounter.classList.remove('warning', 'danger');
                if (currentLength > maxLength * 0.9) {
                    characterCounter.classList.add('danger');
                } else if (currentLength > maxLength * 0.8) {
                    characterCounter.classList.add('warning');
                }
            }
            
            // Функция валидации имени
            function validateName(value) {
                if (!value || value.trim().length === 0) {
                    return { isValid: false, message: 'Имя обязательно для заполнения' };
                }
                if (value.length > 50) {
                    return { isValid: false, message: 'Имя не должно превышать 50 символов' };
                }
                const nameRegex = /^[a-zA-Zа-яА-Я\s\-'.]+$/u;
                if (!nameRegex.test(value)) {
                    return { isValid: false, message: 'Имя может содержать только буквы, пробелы, дефисы и апострофы' };
                }
                return { isValid: true, message: '' };
            }
            
            // Функция валидации описания
            function validateBio(value) {
                if (value && value.length > 190) {
                    return { isValid: false, message: 'Описание не должно превышать 190 символов' };
                }
                const scriptRegex = /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi;
                if (scriptRegex.test(value)) {
                    return { isValid: false, message: 'Описание содержит недопустимые элементы' };
                }
                return { isValid: true, message: '' };
            }
            
            // Функция для очистки и форматирования имени
            function formatName(value) {
                return value
                    .replace(/[^a-zA-Zа-яА-Я\s\-'\.]/g, '') // Удаляем недопустимые символы
                    .replace(/\s+/g, ' ') // Заменяем множественные пробелы на один
                    .trim(); // Убираем пробелы в начале и конце
            }
            
            // Обработчик клика по имени
            if (editableNameElement) {
                editableNameElement.addEventListener('click', function(e) {
                    e.stopPropagation();
                    openEditModal('name', 'Редактировать имя', this.textContent.trim());
                });
            }
            
            // Обработчик клика по описанию
            if (editableBioElement) {
                editableBioElement.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const currentText = this.textContent.trim();
                    const bioText = currentText === 'Добро пожаловать на мою страницу!' ? '' : currentText;
                    openEditModal('bio', 'Редактировать описание', bioText);
                });
            }
            
            // Обработчик клика по фону hero-секции (для изменения фона)
            const heroSection = document.getElementById('hero-background-section');
            if (heroSection && heroSection.dataset.owner === 'true') {
                heroSection.addEventListener('click', function(e) {
                    // Проверяем, что клик не был по hero-section или hero-logo
                    const heroContentElement = e.target.closest('.hero-section');
                    const heroLogoElement = e.target.closest('.hero-logo');
                    const isInteractiveElement = e.target.closest('a, button, [contenteditable], .editable-name, .editable-bio, .social-link');
                    
                    // Если клик был по содержимому секции, логотипу или интерактивным элементам - не открываем редактор
                    if (heroContentElement || heroLogoElement || isInteractiveElement) {
                        return;
                    }
                    
                    // Открываем фоторедактор только если клик был по фону
                    if (typeof openPhotoEditor === 'function') {
                        openPhotoEditor('hero');
                    }
                });
                
                // Добавляем курсор pointer только для фона
                heroSection.style.cursor = 'pointer';
            }
            
            // Функция открытия модального окна
            function openEditModal(type, title, currentValue) {
                currentEditType = type;
                currentEditElement = type === 'name' ? editableNameElement : editableBioElement;
                
                if (!editModal) {
                    return;
                }
                
                editModalTitle.textContent = title;
                
                if (type === 'name') {
                    editInput.value = currentValue;
                    editInput.style.display = 'block';
                    editTextarea.style.display = 'none';
                    editInput.classList.remove('invalid');
                    updateCharacterCounter(editInput, 50);
                    
                    // Добавляем обработчики для валидации в реальном времени
                    editInput.oninput = function() {
                        // Применяем форматирование имени
                        const cursorPosition = this.selectionStart;
                        const formattedValue = formatName(this.value);
                        if (this.value !== formattedValue) {
                            this.value = formattedValue;
                            this.setSelectionRange(cursorPosition, cursorPosition);
                        }
                        
                        updateCharacterCounter(this, 50);
                        const validation = validateName(this.value);
                        if (validation.isValid) {
                            this.classList.remove('invalid');
                            editSaveBtn.disabled = false;
                        } else {
                            this.classList.add('invalid');
                            editSaveBtn.disabled = true;
                        }
                    };
                    
                    editInput.focus();
                } else {
                    editTextarea.value = currentValue;
                    editTextarea.style.display = 'block';
                    editInput.style.display = 'none';
                    editTextarea.classList.remove('invalid');
                    updateCharacterCounter(editTextarea, 190);
                    
                    // Добавляем обработчики для валидации в реальном времени
                    editTextarea.oninput = function() {
                        updateCharacterCounter(this, 190);
                        const validation = validateBio(this.value);
                        if (validation.isValid) {
                            this.classList.remove('invalid');
                            editSaveBtn.disabled = false;
                        } else {
                            this.classList.add('invalid');
                            editSaveBtn.disabled = true;
                        }
                    };
                    
                    editTextarea.focus();
                }
                
                // Начальная валидация
                const isNameType = type === 'name';
                const validation = isNameType ? validateName(currentValue) : validateBio(currentValue);
                editSaveBtn.disabled = !validation.isValid;
                
                editModal.classList.add('show');
            }
            
            // Функция закрытия модального окна
            function closeEditModal() {
                editModal.classList.remove('show');
                currentEditType = null;
                currentEditElement = null;
                editInput.value = '';
                editTextarea.value = '';
                editInput.classList.remove('invalid');
                editTextarea.classList.remove('invalid');
                editSaveBtn.disabled = false;
                
                // Очищаем обработчики событий
                editInput.oninput = null;
                editTextarea.oninput = null;
            }
            
            // Обработчик кнопки "Отмена"
            editCancelBtn.addEventListener('click', closeEditModal);
            
            // Обработчик клика по фону модального окна
            editModal.addEventListener('click', function(e) {
                if (e.target === editModal) {
                    closeEditModal();
                }
            });
            
            // Обработчик кнопки "Сохранить"
            editSaveBtn.addEventListener('click', function() {
                const newValue = currentEditType === 'name' ? editInput.value.trim() : editTextarea.value.trim();
                
                // Финальная валидация перед отправкой
                let validation;
                if (currentEditType === 'name') {
                    validation = validateName(newValue);
                } else {
                    validation = validateBio(newValue);
                }
                
                if (!validation.isValid) {
                    alert('❌ ' + validation.message);
                    return;
                }
                
                // Дополнительная проверка для имени
                if (currentEditType === 'name' && newValue === '') {
                    alert('❌ Имя не может быть пустым');
                    return;
                }
                
                saveTextEdit(currentEditType, newValue);
            });
            
            // Обработчик Enter для input и Ctrl+Enter для textarea
            editInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    editSaveBtn.click();
                }
                if (e.key === 'Escape') {
                    closeEditModal();
                }
            });
            
            editTextarea.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && e.ctrlKey) {
                    editSaveBtn.click();
                }
                if (e.key === 'Escape') {
                    closeEditModal();
                }
            });
            
            // Функция сохранения изменений
            function saveTextEdit(type, value) {
                const data = {};
                data[type] = value;
                
                fetch('{{ route("user.update", $pageUser->username) }}', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': heroCSRFToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Обновляем текст на странице
                        if (type === 'name') {
                            currentEditElement.textContent = value;
                        } else {
                            currentEditElement.textContent = value || 'Добро пожаловать на мою страницу!';
                        }
                        
                        closeEditModal();
                        showNotification('Изменения сохранены!', 'success');
                    } else {
                        showNotification(data.message || 'Ошибка при сохранении', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Произошла ошибка при сохранении', 'error');
                });
            }
            
            function showNotification(message, type) {
                // Простое уведомление через alert (можно заменить на более красивое)
                if (type === 'success') {
                    alert('✅ ' + message);
                } else {
                    alert('❌ ' + message);
                }
            }
        });
    </script>
    @endif
@endif

@if(isset($currentUser) && $currentUser && $currentUser->id === $pageUser->id)
   
    <!-- Передаем ID текущего пользователя в JavaScript -->
    <script>
        window.currentUserId = {{ $currentUser->id }};
        window.pageUsername = '{{ $pageUser->username }}';
    </script>
@endif