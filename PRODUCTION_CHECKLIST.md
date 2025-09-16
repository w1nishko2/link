# 🚀 Production Deployment Checklist

## ✅ Безопасность

- [x] **Время жизни сессий**: Изменено с 365 дней на 120 минут
- [x] **Шифрование сессий**: Включено (`SESSION_ENCRYPT=true`)
- [x] **Remember Me токены**: Изменены с 365 дней на 30 дней
- [x] **TrustHosts middleware**: Активирован для защиты от Host Header атак
- [x] **Проверка ролей**: Добавлена защита админ панели
- [x] **HTTPS принуждение**: Настроено для продакшена
- [x] **Exception Handler**: Улучшен для скрытия чувствительной информации

## ✅ Производительность

- [x] **Redis для кеша**: Настроен вместо файлового кеша
- [x] **Redis для очередей**: Настроен вместо синхронных очередей
- [x] **Логирование**: Настроено на уровень ERROR для продакшена

## ✅ Мониторинг и Backup

- [x] **Автоматический backup БД**: Команда создана и добавлена в cron (ежедневно в 2:00)
- [x] **Очистка старых backup'ов**: Автоматическое удаление файлов старше 7 дней
- [x] **Улучшенное логирование ошибок**: Детальная информация для отладки

## 📋 Необходимые действия перед деплоем

### 1. Настройка сервера
```bash
# Установить Redis
sudo apt-get install redis-server

# Запустить Redis
sudo systemctl start redis
sudo systemctl enable redis

# Настроить права на папки
sudo chown -R www-data:www-data storage/
sudo chown -R www-data:www-data bootstrap/cache/
sudo chmod -R 755 storage/
sudo chmod -R 755 bootstrap/cache/
```

### 2. Настройка .env файла
```bash
# Скопировать и настроить
cp .env.example .env

# Обязательно изменить:
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
LOG_LEVEL=error
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_LIFETIME=120
SESSION_ENCRYPT=true

# Сгенерировать ключ приложения
php artisan key:generate
```

### 3. Настройка cron для задач
```bash
# Добавить в crontab
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### 4. Команды для деплоя
```bash
# Очистка кешей
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Кеширование для продакшена
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Миграции
php artisan migrate --force

# Запуск queue worker
php artisan queue:work redis --daemon
```

### 5. Настройка веб-сервера (Nginx пример)
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name yourdomain.com;
    
    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/private.key;
    
    root /path/to/your/project/public;
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

## 🔍 Тестирование после деплоя

- [ ] Проверить редирект с HTTP на HTTPS
- [ ] Проверить работу аутентификации
- [ ] Проверить работу админ панели
- [ ] Проверить создание контента
- [ ] Проверить работу очередей: `php artisan queue:work`
- [ ] Проверить создание backup: `php artisan db:backup`
- [ ] Проверить логи: `tail -f storage/logs/laravel-*.log`

## 🚨 В случае проблем

### Откат изменений безопасности (ТОЛЬКО В КРАЙНЕМ СЛУЧАЕ)
```bash
# Временно увеличить время сессий (НЕ РЕКОМЕНДУЕТСЯ)
SESSION_LIFETIME=1440  # 24 часа вместо 120 минут

# Отключить шифрование сессий (НЕ РЕКОМЕНДУЕТСЯ)
SESSION_ENCRYPT=false
```

### Проверка статуса сервисов
```bash
# Redis
redis-cli ping

# PHP-FPM
sudo systemctl status php8.1-fpm

# Nginx
sudo systemctl status nginx

# Очереди Laravel
ps aux | grep "queue:work"
```

## 📊 Мониторинг

Добавить в систему мониторинга:
- Использование Redis
- Размер backup файлов
- Количество ошибок в логах
- Время ответа сервера
- Использование дискового пространства

---

**⚠️ Важно**: Все критические проблемы безопасности исправлены. Приложение готово к продакшену при условии выполнения указанных выше шагов.