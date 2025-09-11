@extends('admin.layouts.app')

@section('title', 'Логи GPT генератора')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Логи GPT генератора</h4>
                    <div>
                        <button class="btn btn-primary me-2" onclick="refreshLogs()">
                            <i class="fas fa-sync-alt"></i> Обновить
                        </button>
                        <button class="btn btn-warning me-2" onclick="clearLogs()">
                            <i class="fas fa-trash"></i> Очистить логи
                        </button>
                        <a href="{{ route('super-admin.gpt-generator') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Назад к генератору
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(!empty($logs))
                        <div class="row">
                            <div class="col-md-3">
                                <h5>Файлы логов</h5>
                                <div class="list-group" id="log-files">
                                    @foreach($logs as $index => $log)
                                        <a href="#" 
                                           class="list-group-item list-group-item-action {{ $index === 0 ? 'active' : '' }}" 
                                           onclick="loadLogFile('{{ $log['path'] }}', this)">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1">{{ $log['name'] }}</h6>
                                                <small>{{ number_format($log['size'] / 1024, 2) }} KB</small>
                                            </div>
                                            <small>{{ date('d.m.Y H:i:s', $log['modified']) }}</small>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-9">
                                <h5>Содержимое лога</h5>
                                <div class="form-group">
                                    <label for="log-content" class="sr-only">Содержимое лога</label>
                                    <textarea id="log-content" 
                                              class="form-control" 
                                              rows="25" 
                                              readonly 
                                              style="font-family: monospace; font-size: 12px; background-color: #f8f9fa;">{{ $latestLogContent }}</textarea>
                                </div>
                                <div class="text-muted">
                                    <small>Показаны последние 100 строк лога. Для полного содержимого используйте файловый менеджер.</small>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle fa-2x mb-3"></i>
                            <h5>Логи отсутствуют</h5>
                            <p class="mb-0">Логи GPT генератора пока не созданы. Они появятся после первого использования генератора статей.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно для подтверждения очистки -->
<div class="modal fade" id="clearLogsModal" tabindex="-1" aria-labelledby="clearLogsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="clearLogsModalLabel">Подтверждение очистки</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Вы уверены, что хотите удалить все файлы логов GPT генератора?</p>
                <p class="text-danger"><strong>Это действие необратимо!</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-danger" onclick="confirmClearLogs()">Удалить логи</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Автопрокрутка к концу текста в textarea
    const logContent = document.getElementById('log-content');
    if (logContent) {
        logContent.scrollTop = logContent.scrollHeight;
    }
});

function refreshLogs() {
    window.location.reload();
}

function clearLogs() {
    $('#clearLogsModal').modal('show');
}

function confirmClearLogs() {
    $('#clearLogsModal').modal('hide');
    
    $.ajax({
        url: '{{ route("super-admin.clear-gpt-logs") }}',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function() {
            showLoading('Очистка логов...');
        },
        success: function(response) {
            hideLoading();
            
            if (response.success) {
                showAlert('success', response.message);
                // Перезагружаем страницу через 2 секунды
                setTimeout(function() {
                    window.location.reload();
                }, 2000);
            } else {
                showAlert('danger', response.message || 'Ошибка при очистке логов');
            }
        },
        error: function(xhr) {
            hideLoading();
            let errorMessage = 'Ошибка при очистке логов';
            
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            
            showAlert('danger', errorMessage);
        }
    });
}

function loadLogFile(filePath, element) {
    // Удаляем активный класс у всех элементов
    $('.list-group-item').removeClass('active');
    // Добавляем активный класс к выбранному элементу
    $(element).addClass('active');
    
    $.ajax({
        url: '{{ route("super-admin.gpt-logs") }}',
        method: 'GET',
        data: {
            file: filePath
        },
        beforeSend: function() {
            $('#log-content').val('Загрузка...');
        },
        success: function(response) {
            if (response.content) {
                $('#log-content').val(response.content);
                // Прокрутка к концу
                const logContent = document.getElementById('log-content');
                logContent.scrollTop = logContent.scrollHeight;
            } else {
                $('#log-content').val('Не удалось загрузить содержимое файла');
            }
        },
        error: function() {
            $('#log-content').val('Ошибка при загрузке файла');
        }
    });
}

function showLoading(message = 'Загрузка...') {
    if ($('#loading-overlay').length === 0) {
        $('body').append(`
            <div id="loading-overlay" style="
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 9999;
            ">
                <div style="
                    background: white;
                    padding: 20px;
                    border-radius: 5px;
                    text-align: center;
                ">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <div class="mt-2">${message}</div>
                </div>
            </div>
        `);
    }
}

function hideLoading() {
    $('#loading-overlay').remove();
}

function showAlert(type, message) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 300px;">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    $('body').append(alertHtml);
    
    // Автоматически скрываем через 5 секунд
    setTimeout(function() {
        $('.alert').last().fadeOut(function() {
            $(this).remove();
        });
    }, 5000);
}
</script>
@endsection