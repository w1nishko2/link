<?php
require_once 'vendor/autoload.php';

// Загружаем Laravel приложение
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\UserSocialLink;

$user = User::where('username', 'weebs')->first();

if ($user) {
    echo "Тестирование ограничения дополнительных социальных ссылок для пользователя: " . $user->name . "\n";
    echo "====================================================\n";
    
    $currentCount = $user->socialLinks()->count();
    echo "Текущее количество дополнительных социальных ссылок: " . $currentCount . "\n";
    
    // Покажем существующие ссылки
    $socialLinks = $user->socialLinks;
    if ($socialLinks->count() > 0) {
        echo "Существующие ссылки:\n";
        foreach ($socialLinks as $link) {
            echo "  - {$link->service_name}: {$link->url}\n";
        }
    }
    
    echo "\n";
    
    // Протестируем добавление ссылок до лимита
    $limit = 5;
    $needed = $limit - $currentCount;
    
    if ($needed > 0) {
        echo "Можно добавить еще: " . $needed . " ссылок\n";
        
        // Добавим тестовые ссылки
        for ($i = 1; $i <= $needed; $i++) {
            $testService = "TestService{$i}";
            $testUrl = "https://example{$i}.com";
            $testIcon = "bi-link-45deg";
            
            $user->socialLinks()->create([
                'service_name' => $testService,
                'url' => $testUrl,
                'icon_class' => $testIcon,
                'order' => $currentCount + $i,
            ]);
            
            echo "Добавлена тестовая ссылка: {$testService}\n";
        }
        
        echo "\nТеперь у пользователя " . $user->socialLinks()->count() . " дополнительных ссылок\n";
    } else {
        echo "Лимит уже достигнут или превышен!\n";
    }
    
    // Попробуем добавить еще одну ссылку для проверки лимита
    echo "\nПопытка добавить ссылку сверх лимита:\n";
    
    try {
        $user->socialLinks()->create([
            'service_name' => "ExtraService",
            'url' => "https://extra.com",
            'icon_class' => "bi-plus",
            'order' => 999,
        ]);
        echo "ОШИБКА: Ссылка была добавлена, хотя не должна была!\n";
    } catch (Exception $e) {
        echo "Исключение поймано (это хорошо): " . $e->getMessage() . "\n";
    }
    
    $finalCount = $user->socialLinks()->count();
    echo "Финальное количество ссылок: " . $finalCount . "\n";
    
    if ($finalCount > 5) {
        echo "ПРОБЛЕМА: Количество ссылок превышает лимит!\n";
    } else {
        echo "ОК: Лимит соблюден\n";
    }
    
} else {
    echo "Пользователь не найден\n";
}