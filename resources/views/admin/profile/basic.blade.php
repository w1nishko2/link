<form action="{{ route('admin.profile.update.basic', $user->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="mb-3">
        <label for="name" class="form-label">Имя</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" 
               id="name" name="name" value="{{ old('name', $user->name) }}" required maxlength="50">
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text">Максимум 50 символов. Осталось: <span id="name-counter">50</span></div>
    </div>

    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" 
               value="{{ $user->username }}" readonly>
        <div class="form-text">Username нельзя изменить</div>
    </div>

    <div class="mb-3">
        <label for="phone" class="form-label">Телефон</label>
        <input type="text" class="form-control" id="phone" 
               value="{{ $user->phone }}" readonly>
        <div class="form-text">Номер телефона нельзя изменить</div>
    </div>

    <div class="mb-4">
        <label for="bio" class="form-label">О себе</label>
        <textarea class="form-control @error('bio') is-invalid @enderror" 
                  id="bio" name="bio" rows="4" maxlength="190"
                  placeholder="Расскажите о себе...">{{ old('bio', $user->bio) }}</textarea>
        @error('bio')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text">
            Максимум 190 символов. Осталось: <span id="bio-counter">190</span>
        </div>
    </div>

    <!-- Секция изображений -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h6 class="text-muted mb-3">Аватар</h6>
            <div class="text-center">
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" 
                         alt="Аватар {{ $user->name }}" 
                         class="rounded-circle mb-3" 
                         style="width: 100px; height: 100px; object-fit: cover;">
                @else
                    <div class="bg-light rounded-circle mb-3 d-flex align-items-center justify-content-center" 
                         style="width: 100px; height: 100px; color: #6c757d;">
                        <i class="bi bi-person-circle" style="font-size: 48px;"></i>
                    </div>
                @endif
                <div>
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="openAvatarEditor()">
                        <i class="bi bi-pencil me-1"></i>
                        Редактировать аватар
                    </button>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <h6 class="text-muted mb-3">Фоновое изображение</h6>
            <div class="text-center">
                @if($user->background_image)
                    <img src="{{ asset('storage/' . $user->background_image) }}" 
                         alt="Фон {{ $user->name }}" 
                         class="img-thumbnail mb-3" 
                         style="width: 100px; height: 60px; object-fit: cover;">
                @else
                    <div class="bg-light img-thumbnail mb-3 d-flex align-items-center justify-content-center" 
                         style="width: 100px; height: 60px; color: #6c757d;">
                        <i class="bi bi-image" style="font-size: 24px;"></i>
                    </div>
                @endif
                <div>
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="openBackgroundEditor()">
                        <i class="bi bi-pencil me-1"></i>
                        Редактировать фон
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex flex-column flex-sm-row gap-2">
        <button type="submit" class="btn btn-primary flex-fill">
            <i class="bi bi-check-circle me-2"></i>
            Сохранить основную информацию
        </button>
        <a href="{{ route('admin.profile', $user->id) }}" class="btn btn-outline-secondary flex-fill">
            <i class="bi bi-arrow-left me-2"></i>
            Назад к панели
        </a>
    </div>
</form>