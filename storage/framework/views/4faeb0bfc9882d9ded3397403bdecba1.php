<!-- Модальное окно для добавления социальной ссылки -->
<div class="modal fade" id="addSocialLinkModal" tabindex="-1" aria-labelledby="addSocialLinkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="<?php echo e(route('admin.social-links.store', $user->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header bg-gradient-primary text-white">
                    <h5 class="modal-title d-flex align-items-center" id="addSocialLinkModalLabel">
                        <i class="bi bi-plus-circle me-2"></i>
                        Добавить социальную ссылку
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="service_name" class="form-label">
                                    <i class="bi bi-tag me-1"></i>
                                    Название сервиса
                                </label>
                                <input type="text" class="form-control" id="service_name" name="service_name" 
                                       placeholder="Например: Instagram, GitHub, LinkedIn" required maxlength="255">
                                <div class="form-text">Введите название социальной сети или сервиса</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="url" class="form-label">
                                    <i class="bi bi-link-45deg me-1"></i>
                                    URL ссылки
                                </label>
                                <input type="url" class="form-control" id="url" name="url" 
                                       placeholder="https://example.com/yourprofile" required maxlength="255">
                                <div class="form-text">Полная ссылка на ваш профиль</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="icon_class" class="form-label">
                                    <i class="bi bi-palette me-1"></i>
                                    Выберите иконку
                                </label>
                                <select class="form-select" id="icon_class" name="icon_class" required>
                                    <option value="">Выберите иконку</option>
                                    <optgroup label="🌐 Социальные сети">
                                        <option value="bi-instagram">📷 Instagram</option>
                                        <option value="bi-facebook">👥 Facebook</option>
                                        <option value="bi-twitter-x">🐦 Twitter (X)</option>
                                        <option value="bi-linkedin">💼 LinkedIn</option>
                                        <option value="bi-youtube">📺 YouTube</option>
                                        <option value="bi-tiktok">🎵 TikTok</option>
                                        <option value="bi-pinterest">📌 Pinterest</option>
                                        <option value="bi-snapchat">👻 Snapchat</option>
                                        <option value="bi-reddit">🤖 Reddit</option>
                                        <option value="bi-mastodon">🐘 Mastodon</option>
                                    </optgroup>
                                    <optgroup label="💬 Мессенджеры">
                                        <option value="bi-telegram">✈️ Telegram</option>
                                        <option value="bi-whatsapp">💚 WhatsApp</option>
                                        <option value="bi-messenger">💬 Messenger</option>
                                        <option value="bi-skype">🎥 Skype</option>
                                        <option value="bi-slack">💼 Slack</option>
                                        <option value="bi-discord">🎮 Discord</option>
                                    </optgroup>
                                    <optgroup label="👨‍💻 Разработка">
                                        <option value="bi-github">🐙 GitHub</option>
                                        <option value="bi-gitlab">🦊 GitLab</option>
                                        <option value="bi-stack-overflow">📚 Stack Overflow</option>
                                        <option value="bi-code-slash">💻 Портфолио/Код</option>
                                        <option value="bi-cloud">☁️ Облако</option>
                                    </optgroup>
                                    <optgroup label="💼 Бизнес и карьера">
                                        <option value="bi-briefcase">💼 Портфолио</option>
                                        <option value="bi-building">🏢 Компания</option>
                                        <option value="bi-envelope">📧 Email</option>
                                        <option value="bi-telephone">📞 Телефон</option>
                                        <option value="bi-geo-alt">📍 Адрес</option>
                                    </optgroup>
                                    <optgroup label="🎨 Творчество и хобби">
                                        <option value="bi-music-note">🎵 Музыка</option>
                                        <option value="bi-camera">📸 Фотография</option>
                                        <option value="bi-palette">🎨 Дизайн</option>
                                        <option value="bi-book">📚 Блог</option>
                                        <option value="bi-film">🎬 Видео</option>
                                    </optgroup>
                                    <optgroup label="🛒 Другое">
                                        <option value="bi-globe">🌍 Веб-сайт</option>
                                        <option value="bi-rss">📡 RSS</option>
                                        <option value="bi-link-45deg">🔗 Ссылка</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <label class="form-label">Предпросмотр иконки</label>
                                <div class="icon-preview-large mb-3">
                                    <i id="icon-preview" class="bi bi-question-circle"></i>
                                </div>
                                <div class="preview-info">
                                    <small class="text-muted">Выберите иконку слева, чтобы увидеть предпросмотр</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x me-1"></i>
                        Отмена
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>
                        Добавить ссылку
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Модальное окно для редактирования социальной ссылки -->
<div class="modal fade" id="editSocialLinkModal" tabindex="-1" aria-labelledby="editSocialLinkModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editSocialLinkForm" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="editSocialLinkModalLabel">
                        <i class="bi bi-pencil me-2"></i>
                        Редактировать социальную ссылку
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_service_name" class="form-label">Название сервиса</label>
                        <input type="text" class="form-control" id="edit_service_name" name="service_name" 
                               placeholder="Например: Instagram, GitHub, LinkedIn" required maxlength="255">
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_url" class="form-label">URL ссылки</label>
                        <input type="url" class="form-control" id="edit_url" name="url" 
                               placeholder="https://example.com/yourprofile" required maxlength="255">
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_icon_class" class="form-label">Иконка</label>
                        <div class="row g-2">
                            <div class="col-8">
                                <select class="form-select" id="edit_icon_class" name="icon_class" required>
                                    <option value="">Выберите иконку</option>
                                    <optgroup label="Социальные сети">
                                        <option value="bi-instagram">Instagram</option>
                                        <option value="bi-facebook">Facebook</option>
                                        <option value="bi-twitter">Twitter</option>
                                        <option value="bi-linkedin">LinkedIn</option>
                                        <option value="bi-youtube">YouTube</option>
                                        <option value="bi-tiktok">TikTok</option>
                                        <option value="bi-pinterest">Pinterest</option>
                                        <option value="bi-snapchat">Snapchat</option>
                                        <option value="bi-discord">Discord</option>
                                        <option value="bi-twitch">Twitch</option>
                                        <option value="bi-reddit">Reddit</option>
                                        <option value="bi-mastodon">Mastodon</option>
                                    </optgroup>
                                    <optgroup label="Мессенджеры">
                                        <option value="bi-telegram">Telegram</option>
                                        <option value="bi-whatsapp">WhatsApp</option>
                                        <option value="bi-messenger">Messenger</option>
                                        <option value="bi-skype">Skype</option>
                                        <option value="bi-slack">Slack</option>
                                    </optgroup>
                                    <optgroup label="Разработка">
                                        <option value="bi-github">GitHub</option>
                                        <option value="bi-gitlab">GitLab</option>
                                        <option value="bi-stack-overflow">Stack Overflow</option>
                                        <option value="bi-code-slash">Код/Portfolio</option>
                                        <option value="bi-cloud">Облако</option>
                                    </optgroup>
                                    <optgroup label="Бизнес">
                                        <option value="bi-briefcase">Портфолио</option>
                                        <option value="bi-building">Компания</option>
                                        <option value="bi-envelope">Email</option>
                                        <option value="bi-telephone">Телефон</option>
                                        <option value="bi-geo-alt">Адрес</option>
                                    </optgroup>
                                    <optgroup label="Другое">
                                        <option value="bi-globe">Веб-сайт</option>
                                        <option value="bi-rss">Блог/RSS</option>
                                        <option value="bi-music-note">Музыка</option>
                                        <option value="bi-camera">Фото</option>
                                        <option value="bi-link-45deg">Ссылка</option>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="col-4 text-center">
                                <div class="icon-preview" style="font-size: 2rem; color: #0d6efd;">
                                    <i id="edit-icon-preview" class="bi bi-question-circle"></i>
                                </div>
                            </div>
                        </div>
                        <small class="form-text text-muted">Выберите иконку для отображения рядом с ссылкой</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check me-2"></i>
                        Сохранить изменения
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.icon-preview-large {
    width: 100px;
    height: 100px;
    border: 3px dashed #dee2e6;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: #0d6efd;
    margin: 0 auto;
    transition: all 0.3s ease;
}

.icon-preview-large:hover {
    border-color: #0d6efd;
    background-color: rgba(13, 110, 253, 0.05);
}
</style><?php /**PATH C:\OSPanel\domains\link\resources\views\admin\profile\social-modals.blade.php ENDPATH**/ ?>