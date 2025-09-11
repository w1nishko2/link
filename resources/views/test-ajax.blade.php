<!DOCTYPE html>
<html>
<head>
    <title>Тест AJAX пагинации</title>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1>Тест AJAX пагинации</h1>
        <button id="testBtn" class="btn btn-primary">Тест загрузки страницы 2</button>
        <div id="result" class="mt-3"></div>
        
        <script>
        document.getElementById('testBtn').addEventListener('click', function() {
            console.log('Начинаем тест...');
            
            fetch('/articles?page=2', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                return response.text();
            })
            .then(data => {
                console.log('Response data:', data);
                document.getElementById('result').innerHTML = '<pre>' + data + '</pre>';
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('result').innerHTML = '<div class="alert alert-danger">Ошибка: ' + error.message + '</div>';
            });
        });
        </script>
    </div>
</body>
</html>
