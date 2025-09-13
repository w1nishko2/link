<form action="{{ route('admin.profile.update.social', $user->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="mb-3">
        <label for="telegram_url" class="form-label">
            <i class="bi bi-telegram text-info me-1"></i>
            Telegram
        </label>
        <input type="url" class="form-control @error('telegram_url') is-invalid @enderror" 
               id="telegram_url" name="telegram_url" 
               value="{{ old('telegram_url', $user->telegram_url) }}"
               placeholder="https://t.me/username" maxlength="255">
        @error('telegram_url')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text">Максимум 255 символов. Осталось: <span id="telegram-counter">255</span></div>
    </div>

    <div class="mb-3">
        <label for="whatsapp_url" class="form-label">
            <i class="bi bi-whatsapp text-success me-1"></i>
            WhatsApp
        </label>
        <input type="url" class="form-control @error('whatsapp_url') is-invalid @enderror" 
               id="whatsapp_url" name="whatsapp_url" 
               value="{{ old('whatsapp_url', $user->whatsapp_url) }}"
               placeholder="https://wa.me/79XXXXXXXXX" maxlength="255">
        @error('whatsapp_url')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text">Максимум 255 символов. Осталось: <span id="whatsapp-counter">255</span></div>
    </div>

    <div class="mb-3">
        <label for="vk_url" class="form-label">
            <i class="bi bi-link-45deg text-primary me-1"></i>
            ВКонтакте
        </label>
        <input type="url" class="form-control @error('vk_url') is-invalid @enderror" 
               id="vk_url" name="vk_url" 
               value="{{ old('vk_url', $user->vk_url) }}"
               placeholder="https://vk.com/username" maxlength="255">
        @error('vk_url')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text">Максимум 255 символов. Осталось: <span id="vk-counter">255</span></div>
    </div>

    <div class="mb-3">
        <label for="youtube_url" class="form-label">
            <i class="bi bi-youtube text-danger me-1"></i>
            YouTube
        </label>
        <input type="url" class="form-control @error('youtube_url') is-invalid @enderror" 
               id="youtube_url" name="youtube_url" 
               value="{{ old('youtube_url', $user->youtube_url) }}"
               placeholder="https://youtube.com/@username" maxlength="255">
        @error('youtube_url')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text">Максимум 255 символов. Осталось: <span id="youtube-counter">255</span></div>
    </div>

    <div class="mb-4">
        <label for="ok_url" class="form-label">
            <i class="bi bi-link-45deg text-warning me-1"></i>
            Одноклассники
        </label>
        <input type="url" class="form-control @error('ok_url') is-invalid @enderror" 
               id="ok_url" name="ok_url" 
               value="{{ old('ok_url', $user->ok_url) }}"
               placeholder="https://ok.ru/profile/username" maxlength="255">
        @error('ok_url')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text">Максимум 255 символов. Осталось: <span id="ok-counter">255</span></div>
    </div>

    <div class="d-flex flex-column flex-sm-row gap-2">
        <button type="submit" class="btn btn-primary flex-fill">
            <i class="bi bi-check-circle me-2"></i>
            Сохранить социальные сети
        </button>
        <a href="{{ route('admin.dashboard', $user->id) }}" class="btn btn-outline-secondary flex-fill">
            <i class="bi bi-arrow-left me-2"></i>
            Назад к панели
        </a>
    </div>
</form>



<!-- Дополнительные социальные ссылки -->
<div class="form-label mb-4">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3 gap-2">
        <label class="form-label mb-0">
            <i class="bi bi-globe text-primary me-1"></i>
            <span class="d-none d-sm-inline">Доп. социальные ссылки</span>
            <span class="d-sm-none">Дополнительные ссылки</span>
        </label>
        @if($user->socialLinks->count() < 5)
            <button type="button" class="btn btn-success btn-sm" 
                    data-bs-toggle="modal" data-bs-target="#addSocialLinkModal" 
                    id="addSocialLinkBtn"
                    title="Добавить дополнительную социальную ссылку">
                <i class="bi bi-plus-circle me-1"></i>
                <span class="d-none d-sm-inline">Добавить ссылку</span>
                <span class="d-sm-none">Добавить</span>
            </button>
        @else
            <span class="badge bg-warning text-dark">
                <i class="bi bi-exclamation-triangle me-1"></i>
                Лимит достигнут (5/5)
            </span>
        @endif
    </div>
    
    @if($user->socialLinks->count() > 0)
        <div class="row g-3">
            @foreach($user->socialLinks as $link)
                <div class="col-12" data-link-id="{{ $link->id }}">
                    <div class="card border-0 bg-light">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <label class="form-label mb-0 fw-semibold">
                                    <i class="bi {{ $link->icon_class }} me-2 text-primary"></i>
                                    {{ $link->service_name }}
                                </label>
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" 
                                            onclick="editSocialLink({{ $link->id }}, '{{ addslashes($link->service_name) }}', '{{ addslashes($link->url) }}', '{{ $link->icon_class }}')"
                                            title="Редактировать">
                                        <i class="bi bi-pencil"></i>
                                        <span class="d-none d-md-inline ms-1">Изменить</span>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm" 
                                            onclick="deleteSocialLink({{ $link->id }})"
                                            title="Удалить">
                                        <i class="bi bi-trash"></i>
                                        <span class="d-none d-md-inline ms-1">Удалить</span>
                                    </button>
                                </div>
                            </div>
                            <div class="input-group mb-2">
                                <input type="url" class="form-control form-control-sm" 
                                       value="{{ $link->url }}" 
                                       readonly
                                       placeholder="URL ссылки">
                               
                            </div>
                            <div class="form-text small">
                                <a href="{{ $link->url }}" target="_blank" rel="noopener" class="text-decoration-none text-muted">
                                    Перейти по ссылке <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-muted text-center py-4">
            <i class="bi bi-link-45deg fs-1 d-block mb-3 opacity-50"></i>
            <p class="mb-2 fw-semibold">Дополнительные ссылки не добавлены</p>
            <small class="d-block">Добавьте ссылки на свои профили в социальных сетях,</small>
            <small class="d-block">портфолио или другие ресурсы</small>
            <small class="d-block mt-2 text-warning">
                <i class="bi bi-info-circle me-1"></i>
                Максимум 5 дополнительных ссылок
            </small>
        </div>
    @endif
</div>

