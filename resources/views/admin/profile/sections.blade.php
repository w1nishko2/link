<form id="sectionsForm" 
      data-get-url="{{ route('admin.sections.get', $user->id) }}"
      data-update-url="{{ route('admin.sections.update', $user->id) }}"
      data-user-name="{{ $user->name }}">
    @csrf
    @method('PUT')
    
    <div class="alert alert-info mobile-info-alert">
        <i class="bi bi-info-circle me-2"></i>
        <span class="d-none d-md-inline">
            Здесь вы можете настроить отображение разделов на вашей публичной странице. 
            Перетаскивайте секции для изменения порядка, редактируйте заголовки и включайте/выключайте их видимость.
            <strong>Обратите внимание:</strong> главный блок и блок статей имеют фиксированные позиции и не могут быть перемещены.
        </span>
        <span class="d-md-none">
            Настройте разделы своей страницы: перетаскивайте для изменения порядка, редактируйте заголовки и управляйте видимостью.
            <br><strong>Главный блок и статьи</strong> имеют фиксированные позиции.
        </span>
    </div>
    
    <div id="sections-container" class="sections-container">
        <!-- Секции будут загружены через AJAX -->
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Загрузка...</span>
            </div>
            <p class="mt-2">Загрузка настроек разделов...</p>
        </div>
    </div>
    
    <div class="d-flex flex-column flex-sm-row gap-2 mt-4" id="sectionsControls">
        <button type="button" id="saveSectionsBtn" class="btn btn-primary flex-fill mobile-control-btn">
            <i class="bi bi-save me-2"></i>
            <span class="d-none d-sm-inline">Сохранить настройки</span>
            <span class="d-sm-none">Сохранить</span>
        </button>
        <a href="{{ route('user.show', $user->username) }}" target="_blank" class="btn btn-outline-info flex-fill">
            <i class="bi bi-eye me-2"></i>
            Предпросмотр страницы
        </a>
    </div>
</form>