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

    <div class="d-flex flex-column flex-sm-row gap-2">
        <button type="submit" class="btn btn-primary flex-fill">
            <i class="bi bi-check-circle me-2"></i>
            Сохранить основную информацию
        </button>
        <a href="{{ route('admin.dashboard', $user->id) }}" class="btn btn-outline-secondary flex-fill">
            <i class="bi bi-arrow-left me-2"></i>
            Назад к панели
        </a>
    </div>
</form>