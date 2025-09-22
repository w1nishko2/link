<section class="hero <?php echo e(isset($currentUser) && $currentUser && $currentUser->id === $pageUser->id ? 'owner-mode' : ''); ?>"
    aria-label="Главная информация о <?php echo e($pageUser->name); ?>"
    <?php if(isset($currentUser) && $currentUser && $currentUser->id === $pageUser->id): ?>
        id="hero-background-section"
        data-owner="true"
        title="Нажмите на фон, чтобы изменить его"
    <?php endif; ?>>
    
    <?php
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
    ?>
    <style>
        .hero {
            background-image: url('<?php echo e($desktopBg); ?>');
        }
        @media (max-width: 768px) {
            .hero {
                background-image: url('<?php echo e($mobileBg); ?>');
            }
        }
    </style>
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
            <div class="hero-logo"
                <?php if(isset($currentUser) && $currentUser && $currentUser->id === $pageUser->id): ?>
                    onclick="openPhotoEditor('avatar'); event.stopPropagation();" 
                    style="cursor: pointer;" 
                    title="Нажмите, чтобы изменить аватар"
                <?php endif; ?>>
                
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
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <?php endif; ?>
</section>

<?php if(isset($currentUser) && $currentUser && $currentUser->id === $pageUser->id): ?>

    <?php if(isset($currentUser) && $currentUser && $currentUser->id === $pageUser->id): ?>
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
                    
                    // Исключаем клики по модальному окну редактирования текста и его элементам
                    const isEditModal = e.target.closest('.edit-modal');
                    const isEditModalElement = e.target.closest('#edit-modal, #edit-input, #edit-textarea, #edit-save, #edit-cancel, .edit-modal-content, .edit-field-container, .character-counter, .edit-modal-buttons, .btn-cancel, .btn-save');
                    
                    // Дополнительная проверка по ID и классам элементов редактирования
                    const isTextEditingElement = e.target.id === 'edit-input' || 
                                               e.target.id === 'edit-textarea' || 
                                               e.target.id === 'edit-save' || 
                                               e.target.id === 'edit-cancel' ||
                                               e.target.classList.contains('edit-modal-content') ||
                                               e.target.classList.contains('btn-cancel') ||
                                               e.target.classList.contains('btn-save');
                    
                    // Если клик был по содержимому секции, логотипу, интерактивным элементам или модальному окну редактирования - не открываем редактор
                    if (heroContentElement || heroLogoElement || isInteractiveElement || isEditModal || isEditModalElement || isTextEditingElement) {
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
                
                // Временно отключаем клики по hero-секции
                const heroSection = document.getElementById('hero-background-section');
                if (heroSection) {
                    heroSection.style.pointerEvents = 'none';
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
                // Восстанавливаем клики по hero-секции
                const heroSection = document.getElementById('hero-background-section');
                if (heroSection) {
                    heroSection.style.pointerEvents = 'auto';
                }
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
            // Предотвращаем распространение события клика от модального окна к hero-секции
            const editModalContent = document.querySelector('.edit-modal-content');
            if (editModalContent) {
                editModalContent.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
            // Также предотвращаем распространение от элементов ввода
            if (editInput) {
                editInput.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
                editInput.addEventListener('focus', function(e) {
                    e.stopPropagation();
                });
            }
            if (editTextarea) {
                editTextarea.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
                editTextarea.addEventListener('focus', function(e) {
                    e.stopPropagation();
                });
            }
            editSaveBtn.addEventListener('click', function() {
                const newValue = currentEditType === 'name' ? editInput.value.trim() : editTextarea.value.trim();
                
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
                
                if (currentEditType === 'name' && newValue === '') {
                    alert('❌ Имя не может быть пустым');
                    return;
                }
                
                saveTextEdit(currentEditType, newValue);
            });
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
<?php endif; ?>
<?php if(isset($currentUser) && $currentUser && $currentUser->id === $pageUser->id): ?>
    <script>
        window.currentUserId = <?php echo e($currentUser->id); ?>;
        window.pageUsername = '<?php echo e($pageUser->username); ?>';
    </script>
<?php endif; ?><?php /**PATH C:\OSPanel\domains\link\resources\views/sections/hero.blade.php ENDPATH**/ ?>