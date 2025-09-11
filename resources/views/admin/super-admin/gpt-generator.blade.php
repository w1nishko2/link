@extends('admin.super-admin.layout')

@section('title', 'GPT Генератор статей')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">GPT Генератор статей</h1>
    <div>
        <a href="{{ route('super-admin.gpt-logs') }}" class="btn btn-outline-secondary me-2">
            <i class="bi bi-file-text"></i> Просмотр логов
        </a>
        <div class="text-muted d-inline-block">
            <i class="bi bi-robot"></i> Автоматическая генерация контента
        </div>
    </div>
</div>

<!-- Статус пользователя @weebs -->
<div class="row mb-4">
    <div class="col-12">
        @if($weebsUser)
            <div class="alert alert-success">
                <h5 class="alert-heading">
                    <i class="bi bi-check-circle"></i> Пользователь найден
                </h5>
                <p class="mb-0">
                    Статьи будут созданы от имени пользователя: 
                    <strong>{{ $weebsUser->name }}</strong> 
                    (@{{ $weebsUser->username }})
                </p>
            </div>
        @else
            <div class="alert alert-danger">
                <h5 class="alert-heading">
                    <i class="bi bi-exclamation-triangle"></i> Пользователь не найден
                </h5>
                <p class="mb-0">
                    Пользователь @weebs не найден в системе. 
                    Создайте пользователя с username "weebs" для работы генератора.
                </p>
            </div>
        @endif
    </div>
</div>

@if($weebsUser)
<!-- Форма генерации статьи -->
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-pencil-square"></i> Генерация новой статьи
                </h6>
            </div>
            <div class="card-body">
                <form id="generateArticleForm">
                    @csrf
                    <div class="mb-3">
                        <label for="topic" class="form-label">Тема статьи</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="topic" name="topic" 
                                   placeholder="Например: Основы веб-разработки" required>
                            <button type="button" class="btn btn-outline-secondary" id="generateIdeasBtn">
                                <i class="bi bi-lightbulb"></i> Идеи
                            </button>
                        </div>
                        <div class="form-text">Введите тему, на которую хотите создать статью</div>
                    </div>

                    <div class="mb-3">
                        <label for="style" class="form-label">Стиль написания</label>
                        <select class="form-select" id="style" name="style" required>
                            <option value="informative">Информативный</option>
                            <option value="casual">Неформальный</option>
                            <option value="professional">Профессиональный</option>
                            <option value="creative">Творческий</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="publish" name="publish" value="1">
                            <label class="form-check-label" for="publish">
                                Опубликовать статью сразу после генерации
                            </label>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary" id="previewBtn">
                            <i class="bi bi-eye"></i> Предварительный просмотр
                        </button>
                        <button type="submit" class="btn btn-primary" id="generateBtn">
                            <i class="bi bi-magic"></i> Сгенерировать статью
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Генератор идей для тем -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-info">
                    <i class="bi bi-lightbulb-fill"></i> Генератор идей
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="category" class="form-label">Категория</label>
                    <select class="form-select" id="category">
                        <option value="общие">Общие темы</option>
                        <option value="технологии">Технологии</option>
                        <option value="бизнес">Бизнес</option>
                        <option value="образование">Образование</option>
                        <option value="здоровье">Здоровье</option>
                        <option value="путешествия">Путешествия</option>
                        <option value="кулинария">Кулинария</option>
                        <option value="спорт">Спорт</option>
                    </select>
                </div>
                <button type="button" class="btn btn-info w-100" id="getIdeasBtn">
                    <i class="bi bi-arrow-clockwise"></i> Получить идеи
                </button>
                
                <div id="topicIdeas" class="mt-3" style="display: none;">
                    <h6>Предлагаемые темы:</h6>
                    <div id="ideasList" class="list-group list-group-flush">
                        <!-- Здесь будут идеи тем -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Статистика генерации -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="bi bi-graph-up"></i> Статистика
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <small class="text-muted">Статей пользователя @weebs:</small>
                    <div class="fw-bold">{{ $weebsUser->articles()->count() }}</div>
                </div>
                <div class="mb-2">
                    <small class="text-muted">Опубликованных:</small>
                    <div class="fw-bold">{{ $weebsUser->articles()->where('is_published', true)->count() }}</div>
                </div>
                <div>
                    <small class="text-muted">Черновиков:</small>
                    <div class="fw-bold">{{ $weebsUser->articles()->where('is_published', false)->count() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Результат генерации -->
<div id="generationResult" class="mt-4" style="display: none;">
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-success">
                <i class="bi bi-check-circle"></i> Результат генерации
            </h6>
        </div>
        <div class="card-body" id="resultContent">
            <!-- Здесь будет результат -->
        </div>
    </div>
</div>

<!-- Модальное окно предварительного просмотра -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">
                    <i class="bi bi-eye"></i> Предварительный просмотр статьи
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="previewContent">
                <!-- Здесь будет содержимое предварительного просмотра -->
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-between w-100">
                    <div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="publishAfterPreview" name="publish" value="1">
                            <label class="form-check-label" for="publishAfterPreview">
                                Опубликовать сразу после сохранения
                            </label>
                        </div>
                    </div>
                    <div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="button" class="btn btn-primary" id="saveFromPreview">
                            <i class="bi bi-save"></i> Сохранить статью
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
.topic-idea-item {
    cursor: pointer;
    transition: background-color 0.2s;
}

.topic-idea-item:hover {
    background-color: #f8f9fa;
}

.loading-spinner {
    display: inline-block;
    width: 1rem;
    height: 1rem;
    border: 2px solid #f3f3f3;
    border-top: 2px solid #007bff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.article-preview h2 {
    margin-bottom: 1rem;
}

.article-preview .article-content {
    line-height: 1.6;
    font-size: 1.1rem;
}

.article-preview .article-content h2,
.article-preview .article-content h3 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    color: #333;
}

.article-preview .article-content p {
    margin-bottom: 1rem;
}

.article-preview .article-content ul,
.article-preview .article-content ol {
    margin-bottom: 1rem;
    padding-left: 2rem;
}

.article-preview .article-content li {
    margin-bottom: 0.5rem;
}

.modal-xl {
    max-width: 1200px;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    let currentArticleData = null;

    // Логирование загрузки страницы
    console.log('GPT Generator: Страница загружена');
    
    // Отправляем лог о загрузке страницы
    $.post('{{ route("super-admin.generate-article") }}', {
        _token: '{{ csrf_token() }}',
        action: 'page_loaded'
    }).fail(function() {
        console.log('GPT Generator: Не удалось отправить лог загрузки страницы');
    });

    // Генерация статьи
    $('#generateArticleForm').on('submit', function(e) {
        e.preventDefault();
        console.log('GPT Generator: Отправка формы генерации статьи');
        generateArticle(false);
    });

    // Предварительный просмотр
    $('#previewBtn').on('click', function(e) {
        e.preventDefault();
        console.log('GPT Generator: Запрос предварительного просмотра');
        generateArticle(true);
    });

    // Сохранение из предварительного просмотра
    $('#saveFromPreview').on('click', function() {
        console.log('GPT Generator: Сохранение из предварительного просмотра');
        if (currentArticleData) {
            saveArticle(currentArticleData, $('#publishAfterPreview').is(':checked'));
        }
    });

    function generateArticle(previewOnly = false) {
        const btn = previewOnly ? $('#previewBtn') : $('#generateBtn');
        const originalText = btn.html();
        
        // Проверяем заполненность формы
        const topic = $('#topic').val().trim();
        const style = $('#style').val();
        
        console.log('GPT Generator: Параметры генерации', {
            topic: topic,
            style: style,
            previewOnly: previewOnly
        });
        
        if (!topic) {
            console.log('GPT Generator: Ошибка - тема не указана');
            alert('Пожалуйста, введите тему статьи');
            return;
        }

        // Показываем загрузку
        btn.prop('disabled', true).html('<span class="loading-spinner"></span> ' + (previewOnly ? 'Генерируем...' : 'Генерируем...'));
        $('#generationResult').hide();
        
        console.log('GPT Generator: Отправка AJAX запроса');
        
        $.ajax({
            url: '{{ route("super-admin.generate-article") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                topic: topic,
                style: style,
                preview_only: previewOnly,
                publish: previewOnly ? false : $('#publish').is(':checked')
            },
            success: function(response) {
                console.log('GPT Generator: Успешный ответ от сервера', response);
                
                if (response.success) {
                    if (response.preview) {
                        console.log('GPT Generator: Показ предварительного просмотра');
                        showPreview(response.article_data);
                    } else {
                        console.log('GPT Generator: Статья создана', response.article);
                        showResult(response.article, response.message);
                    }
                } else {
                    console.log('GPT Generator: Ошибка в ответе сервера', response.message);
                    showError(response.message);
                }
            },
            error: function(xhr) {
                console.error('GPT Generator: AJAX ошибка', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText: xhr.responseText
                });
                
                const message = xhr.responseJSON?.message || 'Произошла ошибка при генерации статьи';
                showError(message);
            },
            complete: function() {
                console.log('GPT Generator: AJAX запрос завершен');
                btn.prop('disabled', false).html(originalText);
            }
        });
    }

    function saveArticle(articleData, publish = false) {
        const btn = $('#saveFromPreview');
        const originalText = btn.html();
        
        console.log('GPT Generator: Сохранение статьи', {
            publish: publish,
            title: articleData.title
        });
        
        btn.prop('disabled', true).html('<span class="loading-spinner"></span> Сохраняем...');
        
        $.ajax({
            url: '{{ route("super-admin.generate-article") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                topic: $('#topic').val(),
                style: $('#style').val(),
                publish: publish
            },
            success: function(response) {
                console.log('GPT Generator: Статья сохранена', response);
                
                if (response.success) {
                    $('#previewModal').modal('hide');
                    showResult(response.article, response.message);
                } else {
                    showError(response.message);
                }
            },
            error: function(xhr) {
                console.error('GPT Generator: Ошибка сохранения', xhr);
                const message = xhr.responseJSON?.message || 'Произошла ошибка при сохранении статьи';
                showError(message);
            },
            complete: function() {
                btn.prop('disabled', false).html(originalText);
            }
        });
    }

    function showPreview(articleData) {
        console.log('GPT Generator: Отображение предварительного просмотра');
        currentArticleData = articleData;
        
        const previewHtml = `
            <div class="article-preview">
                <div class="mb-4">
                    <h2 class="text-primary">${articleData.title}</h2>
                    <p class="text-muted">${articleData.excerpt}</p>
                </div>
                <div class="article-content">
                    ${articleData.content}
                </div>
            </div>
        `;
        
        $('#previewContent').html(previewHtml);
        $('#publishAfterPreview').prop('checked', $('#publish').is(':checked'));
        $('#previewModal').modal('show');
    }

    // Получение идей для тем
    $('#getIdeasBtn, #generateIdeasBtn').on('click', function() {
        const btn = $(this);
        const originalText = btn.html();
        const category = $('#category').val();
        
        console.log('GPT Generator: Запрос идей тем', {
            category: category
        });
        
        btn.prop('disabled', true).html('<span class="loading-spinner"></span>');
        
        $.ajax({
            url: '{{ route("super-admin.generate-topic-ideas") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                category: category
            },
            success: function(response) {
                console.log('GPT Generator: Идеи получены', response);
                
                if (response.success && response.ideas.length > 0) {
                    showTopicIdeas(response.ideas);
                } else {
                    console.log('GPT Generator: Не удалось получить идеи');
                    alert('Не удалось получить идеи тем');
                }
            },
            error: function(xhr) {
                console.error('GPT Generator: Ошибка получения идей', xhr);
                alert('Ошибка при получении идей');
            },
            complete: function() {
                btn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Выбор темы из списка идей
    $(document).on('click', '.topic-idea-item', function() {
        const topic = $(this).text().trim();
        console.log('GPT Generator: Выбрана тема из списка', topic);
        
        $('#topic').val(topic);
        $('.topic-idea-item').removeClass('bg-light');
        $(this).addClass('bg-light');
    });

    function showTopicIdeas(ideas) {
        console.log('GPT Generator: Отображение идей тем', ideas);
        
        const list = $('#ideasList');
        list.empty();
        
        ideas.forEach(function(idea) {
            if (idea.trim()) {
                const item = $('<div class="list-group-item list-group-item-action topic-idea-item">')
                    .text(idea.trim());
                list.append(item);
            }
        });
        
        $('#topicIdeas').show();
    }

    function showResult(article, message) {
        console.log('GPT Generator: Отображение результата', article);
        
        const resultHtml = `
            <div class="alert alert-success">
                <h5><i class="bi bi-check-circle"></i> ${message}</h5>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <h5>Информация о статье:</h5>
                    <ul class="list-unstyled">
                        <li><strong>Заголовок:</strong> ${article.title}</li>
                        <li><strong>Slug:</strong> ${article.slug}</li>
                        <li><strong>Краткое описание:</strong> ${article.excerpt}</li>
                        <li><strong>Статус:</strong> ${article.is_published ? 'Опубликована' : 'Черновик'}</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6>Действия:</h6>
                    <div class="d-grid gap-2">
                        <a href="${article.edit_url}" class="btn btn-primary btn-sm">
                            <i class="bi bi-pencil"></i> Редактировать
                        </a>
                        ${article.view_url ? `<a href="${article.view_url}" class="btn btn-success btn-sm" target="_blank">
                            <i class="bi bi-eye"></i> Просмотреть
                        </a>` : ''}
                    </div>
                </div>
            </div>
        `;
        
        $('#resultContent').html(resultHtml);
        $('#generationResult').show();
        
        // Прокручиваем к результату
        $('html, body').animate({
            scrollTop: $('#generationResult').offset().top - 100
        }, 500);
    }

    function showError(message) {
        console.error('GPT Generator: Отображение ошибки', message);
        
        const errorHtml = `
            <div class="alert alert-danger">
                <h5><i class="bi bi-exclamation-triangle"></i> Ошибка</h5>
                <p class="mb-0">${message}</p>
            </div>
        `;
        
        $('#resultContent').html(errorHtml);
        $('#generationResult').show();
    }
});
</script>
@endpush