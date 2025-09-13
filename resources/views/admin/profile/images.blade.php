<form action="{{ route('admin.profile.update.images', $user->id) }}" method="POST" enctype="multipart/form-data" id="images-form">
    @csrf
    @method('PUT')
    
    <!-- Скрытые поля для загрузки -->
    <input type="file" id="avatar-input" name="avatar" accept="image/*" style="display: none;">
    <input type="file" id="background-input" name="background_image" accept="image/*" style="display: none;">
    
    <div class="mb-4">
        <h6 class="text-muted mb-3">
            <i class="bi bi-images me-2"></i>
            Управление изображениями профиля
        </h6>
        
        <!-- Основной блок с изображениями -->
        <div class="image-preview-container position-relative" style="height: 300px; border-radius: 16px; overflow: hidden; border: 2px dashed #dee2e6; transition: all 0.3s ease;">
            
            <!-- Фоновое изображение (занимает весь блок) -->
            <div class="background-area position-absolute w-100 h-100" 
                 style="cursor: pointer; background-image: url('{{ $user->background_image ? asset('storage/' . $user->background_image) : '/hero.png' }}'); 
                        background-size: cover; background-position: center; 
                        transition: all 0.3s ease; filter: brightness(0.9);"
                 onclick="document.getElementById('background-input').click()"
                 id="background-preview-area">
                
                <!-- Overlay для лучшей видимости -->
                <div class="position-absolute w-100 h-100" style="background: linear-gradient(135deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.3) 100%);"></div>
                
                <!-- Индикатор для фона -->
                <div class="position-absolute" style="top: 15px; left: 15px; z-index: 2;">
                    <div class="badge bg-primary bg-opacity-75 px-3 py-2">
                        <i class="bi bi-image me-1"></i>
                        <span class="d-none d-sm-inline">Фон</span>
                    </div>
                </div>
                
                <!-- Кнопка замены фона для мобильных -->
                <div class="position-absolute d-md-none" style="bottom: 15px; left: 15px; z-index: 2;">
                    <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('background-input').click()">
                        <i class="bi bi-camera"></i> Изменить фон
                    </button>
                </div>
                
                <!-- Подсказка по размеру -->
                <div class="position-absolute d-none d-md-block" style="bottom: 15px; left: 15px; z-index: 2;">
                    <small class="badge bg-dark bg-opacity-75 px-2 py-1">
                        Рекомендуемый размер: 1920×1080px
                    </small>
                </div>
            </div>
            
            <!-- Аватар -->
            <div class="avatar-area position-absolute" 
                 style="bottom: 20px; right: 20px; width: 120px; height: 120px; 
                        cursor: pointer; border-radius: 50%; border: 4px solid white; 
                        box-shadow: 0 4px 20px rgba(0,0,0,0.2); transition: all 0.3s ease; z-index: 3;
                        background-image: url('{{ $user->avatar ? asset('storage/' . $user->avatar) : '/hero.png' }}'); 
                        background-size: cover; background-position: center;"
                 onclick="document.getElementById('avatar-input').click()"
                 id="avatar-preview-area">
                
                <!-- Overlay для аватара -->
                <div class="position-absolute w-100 h-100 rounded-circle d-flex align-items-center justify-content-center" 
                     style="background: rgba(0,0,0,0.4); opacity: 0; transition: opacity 0.3s ease;"
                     id="avatar-overlay">
                    <i class="bi bi-camera text-white" style="font-size: 1.5rem;"></i>
                </div>
                
                <!-- Индикатор аватара -->
                <div class="position-absolute" style="top: -10px; right: -10px;">
                    <div class="badge bg-success rounded-pill" style="width: 20px; height: 20px; padding: 0; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-person" style="font-size: 10px;"></i>
                    </div>
                </div>
            </div>
            
            <!-- Подсказка для аватара (скрыта на мобильных) -->
            <div class="position-absolute d-none d-md-block" style="bottom: 150px; right: 20px; z-index: 2;">
                <small class="badge bg-success bg-opacity-75 px-2 py-1">
                    Аватар: 200×200px
                </small>
            </div>
            
            <!-- Кнопка для смены аватара на мобильных -->
            <div class="position-absolute d-md-none" style="bottom: 50px; right: 20px; z-index: 2;">
                <button type="button" class="btn btn-success btn-sm rounded-pill" onclick="document.getElementById('avatar-input').click()" style="width: 35px; height: 35px; padding: 0; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-camera" style="font-size: 0.8rem;"></i>
                </button>
            </div>
        </div>
        
        <!-- Ошибки валидации -->
        @if($errors->has('avatar') || $errors->has('background_image'))
            <div class="mt-3">
                @error('avatar')
                    <div class="alert alert-danger d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Ошибка аватара:</strong> {{ $message }}
                    </div>
                @enderror
                @error('background_image')
                    <div class="alert alert-danger d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Ошибка фона:</strong> {{ $message }}
                    </div>
                @enderror
            </div>
        @endif
        
        <!-- Дополнительная информация -->
        <div class="mt-3">
            <div class="alert alert-info d-flex align-items-start">
                <i class="bi bi-info-circle me-2 mt-1"></i>
                <div>
                    <strong>Советы по изображениям:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Максимальный размер файла: 10MB</li>
                        <li>Поддерживаемые форматы: JPG, PNG, GIF, WebP</li>
                        <li>Изображения автоматически оптимизируются</li>
                        <li>Кликните на область изображения для его замены</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex flex-column flex-sm-row gap-2">
        <button type="submit" class="btn btn-primary flex-fill">
            <i class="bi bi-check-circle me-2"></i>
            Сохранить изменения
        </button>
        <a href="{{ route('admin.dashboard', $user->id) }}" class="btn btn-outline-secondary flex-fill">
            <i class="bi bi-arrow-left me-2"></i>
            Назад к панели
        </a>
    </div>
</form>