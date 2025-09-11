<!DOCTYPE html>
<html>
<head>
    <title>Тест бесконечной прокрутки</title>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Тест бесконечной прокрутки</h1>
        
        <div id="articlesContainer" class="row g-4">
            <!-- Здесь будут статьи -->
        </div>
        
        <div class="text-center mt-4">
            <button id="loadMoreBtn" class="btn btn-primary">Загрузить еще</button>
        </div>
        
        <div id="loadingIndicator" class="text-center mt-4" style="display: none;">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Загрузка...</span>
            </div>
        </div>
        
        <div id="endMessage" class="text-center mt-4" style="display: none;">
            <p class="text-muted">Все статьи загружены</p>
        </div>
        
        <div id="debugInfo" class="mt-4 p-3 bg-light">
            <h5>Отладочная информация</h5>
            <div id="debugOutput"></div>
        </div>
    </div>
    
    <script>
    let currentPage = 1;
    let isLoading = false;
    
    function updateDebug(message) {
        const debugOutput = document.getElementById('debugOutput');
        const time = new Date().toLocaleTimeString();
        debugOutput.innerHTML += `<div>[${time}] ${message}</div>`;
    }
    
    function testAjax() {
        updateDebug('Начинаем тест AJAX...');
        
        // Проверяем элементы
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        updateDebug('CSRF токен: ' + (csrfToken ? csrfToken.getAttribute('content').substring(0, 10) + '...' : 'НЕТ'));
        
        const url = '/articles?page=2';
        const headers = {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        };
        
        if (csrfToken) {
            headers['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
        }
        
        updateDebug('URL: ' + url);
        updateDebug('Заголовки: ' + JSON.stringify(headers, null, 2));
        
        fetch(url, {
            method: 'GET',
            headers: headers,
            credentials: 'same-origin'
        })
        .then(response => {
            updateDebug('Статус ответа: ' + response.status);
            updateDebug('Content-Type: ' + response.headers.get('content-type'));
            
            if (!response.ok) {
                throw new Error('HTTP error! status: ' + response.status);
            }
            
            return response.json();
        })
        .then(data => {
            updateDebug('JSON ответ получен!');
            updateDebug('Количество статей в HTML: ' + (data.html ? data.html.length : 'HTML отсутствует'));
            updateDebug('hasMore: ' + data.hasMore);
            updateDebug('nextPage: ' + data.nextPage);
            
            if (data.html) {
                document.getElementById('articlesContainer').innerHTML = data.html;
                updateDebug('HTML добавлен в контейнер');
            }
        })
        .catch(error => {
            updateDebug('ОШИБКА: ' + error.message);
            console.error('Ошибка:', error);
        });
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        updateDebug('DOM загружен');
        
        const loadMoreBtn = document.getElementById('loadMoreBtn');
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', testAjax);
            updateDebug('Обработчик кнопки установлен');
        }
    });
    </script>
</body>
</html>
