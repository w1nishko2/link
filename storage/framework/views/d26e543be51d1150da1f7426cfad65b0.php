
<section class="hero <?php echo e(isset($currentUser) && $currentUser && $currentUser->id === $pageUser->id ? 'owner-mode' : ''); ?>"
    style="background-image: url('<?php echo e($pageUser->background_image ? asset('storage/' . $pageUser->background_image) : '/hero.png'); ?>');"
    aria-label="Главная информация о <?php echo e($pageUser->name); ?>"
    <?php if(isset($currentUser) && $currentUser && $currentUser->id === $pageUser->id): ?> id="hero-background-editable" <?php endif; ?>>
    
    <?php if(isset($currentUser) && $currentUser && $currentUser->id === $pageUser->id): ?>
        <!-- Кнопки редактирования для владельца -->
        <div class="hero-edit-controls">
            <button type="button" class="hero-edit-btn" onclick="openBackgroundEditor()" title="Изменить фон">
                <i class="bi bi-image"></i>
            </button>
            <button type="button" class="hero-edit-btn" onclick="openAvatarEditor()" title="Изменить аватар">
                <i class="bi bi-person-circle"></i>
            </button>
        </div>
        
        <div class="edit-overlay" id="background-edit-overlay">
            <div class="edit-hint">
                <i class="bi bi-camera"></i>
                <span>Нажмите, чтобы изменить фон</span>
            </div>
        </div>
    <?php endif; ?>
    <div class="container">
        <div class="hero-section">
            <div class="hero-info">
                <?php
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
                ?>
                
                <?php if($isOwner): ?>
                    <h1 class="editable-name" id="editable-name" title="Нажмите, чтобы изменить имя"><?php echo e($displayTitle); ?></h1>
                <?php else: ?>
                    <h1><?php echo e($displayTitle); ?></h1>
                <?php endif; ?>
                
                <p>@ <?php echo e($pageUser->username); ?></p>
                
                <?php if($isOwner): ?>
                    <p class="editable-bio" id="editable-bio" title="Нажмите, чтобы изменить описание"><?php echo e($displayBio); ?></p>
                <?php else: ?>
                    <p><?php echo e($displayBio); ?></p>
                <?php endif; ?>
                
                <ul class="hero-links">
                    <?php if($pageUser->telegram_url): ?>
                        <a href="<?php echo e($pageUser->telegram_url); ?>" target="_blank" class="social-link telegram"
                            title="Telegram">
                            <i class="bi bi-telegram"></i>
                        </a>
                    <?php endif; ?>

                    <?php if($pageUser->whatsapp_url): ?>
                        <a href="<?php echo e($pageUser->whatsapp_url); ?>" target="_blank" class="social-link whatsapp"
                            title="WhatsApp">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                    <?php endif; ?>

                    <?php if($pageUser->vk_url): ?>
                        <a href="<?php echo e($pageUser->vk_url); ?>" target="_blank" class="social-link vk" title="ВКонтакте">
                            <i class="bi bi-chat-square-text"></i>
                        </a>
                    <?php endif; ?>

                    <?php if($pageUser->youtube_url): ?>
                        <a href="<?php echo e($pageUser->youtube_url); ?>" target="_blank" class="social-link youtube"
                            title="YouTube">
                            <i class="bi bi-youtube"></i>
                        </a>
                    <?php endif; ?>

                    <?php if($pageUser->ok_url): ?>
                        <a href="<?php echo e($pageUser->ok_url); ?>" target="_blank" class="social-link ok" title="Одноклассники">
                            <i class="bi bi-people-fill"></i>
                        </a>
                    <?php endif; ?>

                    
                    <?php if($socialLinks && $socialLinks->count() > 0): ?>
                        <?php $__currentLoopData = $socialLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
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
                            ?>
                            <a href="<?php echo e($link->url); ?>" target="_blank" class="social-link custom <?php echo e($serviceClass); ?>" title="<?php echo e($link->service_name); ?>">
                                <i class="bi <?php echo e($link->icon_class); ?>"></i>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="hero-logo <?php echo e(isset($currentUser) && $currentUser && $currentUser->id === $pageUser->id ? 'editable-avatar' : ''); ?>"
                 <?php if(isset($currentUser) && $currentUser && $currentUser->id === $pageUser->id): ?> id="avatar-editable" <?php endif; ?>>
                <?php if(isset($currentUser) && $currentUser && $currentUser->id === $pageUser->id): ?>
                    <div class="avatar-edit-overlay" id="avatar-edit-overlay">
                        <div class="edit-hint">
                            <i class="bi bi-camera"></i>
                            <span>Изменить фото</span>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal69176bc01f29785a9e16a2a1923b2050 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal69176bc01f29785a9e16a2a1923b2050 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.optimized-image','data' => ['src' => $pageUser->avatar ? asset('storage/' . $pageUser->avatar) : null,'alt' => 'Фотография ' . $pageUser->name,'width' => '150','height' => '150','loading' => 'eager','fetchpriority' => 'high']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('optimized-image'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['src' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($pageUser->avatar ? asset('storage/' . $pageUser->avatar) : null),'alt' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('Фотография ' . $pageUser->name),'width' => '150','height' => '150','loading' => 'eager','fetchpriority' => 'high']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal69176bc01f29785a9e16a2a1923b2050)): ?>
<?php $attributes = $__attributesOriginal69176bc01f29785a9e16a2a1923b2050; ?>
<?php unset($__attributesOriginal69176bc01f29785a9e16a2a1923b2050); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal69176bc01f29785a9e16a2a1923b2050)): ?>
<?php $component = $__componentOriginal69176bc01f29785a9e16a2a1923b2050; ?>
<?php unset($__componentOriginal69176bc01f29785a9e16a2a1923b2050); ?>
<?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php if(isset($currentUser) && $currentUser && $currentUser->id === $pageUser->id): ?>
        
        <input type="file" id="background-input" accept="image/*" style="display: none;">
        <input type="file" id="avatar-input" accept="image/*" style="display: none;">
        
        
        <div class="edit-modal" id="edit-modal">
            <div class="edit-modal-content">
                <h3 id="edit-modal-title">Редактировать</h3>
                <div class="edit-field-container">
                    <input type="text" id="edit-input" style="display: none;" maxlength="50" placeholder="Введите имя...">
                    <textarea id="edit-textarea" style="display: none;" maxlength="1000" placeholder="Расскажите о себе..."></textarea>
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
        
        
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <?php endif; ?>
</section>

<?php if(isset($currentUser) && $currentUser && $currentUser->id === $pageUser->id): ?>
    <style>
     
    </style>

    <?php if(isset($currentUser) && $currentUser && $currentUser->id === $pageUser->id): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const backgroundEditableElement = document.getElementById('hero-background-editable');
            const avatarEditableElement = document.getElementById('avatar-editable');
            const backgroundInput = document.getElementById('background-input');
            const avatarInput = document.getElementById('avatar-input');
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
            
            console.log('Editable elements found:', {
                name: !!editableNameElement,
                bio: !!editableBioElement,
                modal: !!editModal
            });
            
            console.log('Name element:', editableNameElement);
            console.log('Bio element:', editableBioElement);
            console.log('Modal element:', editModal);
            
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
                if (value && value.length > 1000) {
                    return { isValid: false, message: 'Описание не должно превышать 1000 символов' };
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
                console.log('Adding click listener to name element');
                editableNameElement.addEventListener('click', function(e) {
                    console.log('Name element clicked!');
                    e.stopPropagation();
                    openEditModal('name', 'Редактировать имя', this.textContent.trim());
                });
            } else {
                console.log('Name element not found!');
            }
            
            // Обработчик клика по описанию
            if (editableBioElement) {
                console.log('Adding click listener to bio element');
                editableBioElement.addEventListener('click', function(e) {
                    console.log('Bio element clicked!');
                    e.stopPropagation();
                    const currentText = this.textContent.trim();
                    const bioText = currentText === 'Добро пожаловать на мою страницу!' ? '' : currentText;
                    openEditModal('bio', 'Редактировать описание', bioText);
                });
            } else {
                console.log('Bio element not found!');
            }
            
            // Функция открытия модального окна
            function openEditModal(type, title, currentValue) {
                console.log('Opening edit modal:', type, title, currentValue);
                currentEditType = type;
                currentEditElement = type === 'name' ? editableNameElement : editableBioElement;
                
                if (!editModal) {
                    console.error('Edit modal not found!');
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
                    updateCharacterCounter(editTextarea, 1000);
                    
                    // Добавляем обработчики для валидации в реальном времени
                    editTextarea.oninput = function() {
                        updateCharacterCounter(this, 1000);
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
                
                fetch('<?php echo e(route("user.update", $pageUser->username)); ?>', {
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
            
            // Сначала регистрируем обработчик клика по аватару с захватом события
            if (avatarEditableElement) {
                avatarEditableElement.addEventListener('click', function(e) {
                    console.log('Avatar click event', e.target);
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    console.log('Opening avatar editor');
                    // Ждем загрузки imageEditor и затем вызываем редактор
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
                }, true); // true означает захват события в фазе захвата
            }
            
            // Обработчик клика по фону
            if (backgroundEditableElement) {
                backgroundEditableElement.addEventListener('click', function(e) {
                    console.log('Background click event', e.target);
                    console.log('Event path:', e.composedPath());
                    
                    // Проверяем, что клик НЕ по аватару
                    const isAvatarClick = e.target.closest('.hero-logo') || 
                                         e.target.closest('#avatar-editable') ||
                                         e.target.closest('.avatar-edit-overlay') ||
                                         e.target.classList.contains('editable-avatar') ||
                                         e.target.tagName === 'IMG';
                    
                    // Проверяем, что клик НЕ по содержимому hero-section (но разрешаем клик по overlay)
                    const isContentClick = (e.target.closest('.container') ||
                                          e.target.closest('.hero-section') ||
                                          e.target.closest('.hero-info') ||
                                          e.target.closest('.hero-links') ||
                                          e.target.tagName === 'H1' ||
                                          e.target.tagName === 'P' ||
                                          e.target.tagName === 'A' ||
                                          e.target.tagName === 'UL' ||
                                          e.target.tagName === 'LI') && 
                                         !e.target.closest('.edit-overlay') &&
                                         !e.target.classList.contains('edit-overlay');
                    
                    // Клик должен быть именно по фону или по overlay области
                    const isBackgroundClick = e.target === backgroundEditableElement ||
                                            e.target.classList.contains('edit-overlay') ||
                                            e.target.closest('.edit-overlay') ||
                                            e.target.classList.contains('edit-hint') ||
                                            e.target.closest('.edit-hint');
                    
                    console.log('Is avatar click:', isAvatarClick);
                    console.log('Is content click:', isContentClick);
                    console.log('Is background click:', isBackgroundClick);
                    console.log('Target element:', e.target);
                    console.log('Target classes:', e.target.className);
                    
                    if (!isAvatarClick && !isContentClick && isBackgroundClick) {
                        console.log('Opening background editor');
                        // Ждем загрузки imageEditor и затем вызываем редактор
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
                    } else {
                        console.log('Click ignored - either avatar click, content click, or not background area');
                    }
                });
            }
            
            function uploadImage(file, type) {
                // Проверяем размер файла (10MB максимум)
                if (file.size > 10 * 1024 * 1024) {
                    alert('Файл слишком большой. Максимальный размер: 10MB');
                    return;
                }
                
                // Проверяем тип файла
                if (!file.type.match(/^image\/(jpeg|png|jpg|gif|webp)$/)) {
                    alert('Поддерживаются только изображения: JPEG, PNG, JPG, GIF, WebP');
                    return;
                }
                
                const formData = new FormData();
                formData.append(type === 'background' ? 'background_image' : 'avatar', file);
                
                const url = type === 'background' 
                    ? '<?php echo e(route("user.update.background", $pageUser->username)); ?>'
                    : '<?php echo e(route("user.update.avatar", $pageUser->username)); ?>';
                
                // Показываем индикатор загрузки
                showLoading(type);
                
                fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': heroCSRFToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading(type);
                    
                    if (data.success) {
                        // Обновляем изображение на странице
                        if (type === 'background') {
                            backgroundEditableElement.style.backgroundImage = `url('${data.image_url}')`;
                        } else {
                            const avatarImg = avatarEditableElement.querySelector('img');
                            if (avatarImg) {
                                avatarImg.src = data.image_url;
                            }
                        }
                        
                        // Показываем уведомление об успехе
                        showNotification(data.message, 'success');
                    } else {
                        showNotification(data.message || 'Произошла ошибка при загрузке изображения', 'error');
                    }
                })
                .catch(error => {
                    hideLoading(type);
                    console.error('Error:', error);
                    showNotification('Произошла ошибка при загрузке изображения', 'error');
                });
            }
            
            function showLoading(type) {
                const element = type === 'background' ? backgroundEditableElement : avatarEditableElement;
                const overlay = element.querySelector('.edit-overlay, .avatar-edit-overlay');
                if (overlay) {
                    overlay.innerHTML = '<div class="edit-hint"><i class="bi bi-arrow-repeat spin"></i><span>Загрузка...</span></div>';
                    overlay.style.opacity = '1';
                    overlay.style.visibility = 'visible';
                }
            }
            
            function hideLoading(type) {
                const element = type === 'background' ? backgroundEditableElement : avatarEditableElement;
                const overlay = element.querySelector('.edit-overlay, .avatar-edit-overlay');
                if (overlay) {
                    if (type === 'background') {
                        overlay.innerHTML = '<div class="edit-hint"><i class="bi bi-camera"></i><span>Нажмите, чтобы изменить фон</span></div>';
                    } else {
                        overlay.innerHTML = '<div class="edit-hint"><i class="bi bi-camera"></i><span>Изменить фото</span></div>';
                    }
                    overlay.style.opacity = '0';
                    overlay.style.visibility = 'hidden';
                }
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
    <?php endif; ?>
    
    <style>
        .spin {
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
<?php endif; ?>

<?php if(isset($currentUser) && $currentUser && $currentUser->id === $pageUser->id): ?>
    <!-- Компонент редактора изображений -->
    <?php echo $__env->make('components.image-editor', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <!-- Передаем ID текущего пользователя в JavaScript -->
    <script>
        window.currentUserId = <?php echo e($currentUser->id); ?>;
        window.pageUsername = '<?php echo e($pageUser->username); ?>';
    </script>
<?php endif; ?><?php /**PATH C:\OSPanel\domains\link\resources\views/sections/hero.blade.php ENDPATH**/ ?>