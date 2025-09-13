<?php
require_once 'vendor/autoload.php';

// Загружаем Laravel приложение
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$user = User::where('username', 'weebs')->first();

if ($user) {
    echo "Проверяем настройки секций для пользователя: " . $user->name . "\n";
    echo "====================================================\n";
    
    // Получаем настройки секций точно так же, как это делает HomeController
    $sectionSettings = $user->sectionSettings()->visible()->ordered()->get()->keyBy('section_key');
    
    echo "Количество видимых секций: " . $sectionSettings->count() . "\n\n";
    
    if ($sectionSettings->count() > 0) {
        echo "Детали секций:\n";
        foreach ($sectionSettings as $key => $section) {
            echo "Секция: {$key}\n";
            echo "  - ID: {$section->id}\n";
            echo "  - Заголовок: '{$section->title}'\n";
            echo "  - Подзаголовок: '{$section->subtitle}'\n";
            echo "  - Видимость: " . ($section->is_visible ? 'true' : 'false') . "\n";
            echo "  - Порядок: {$section->order}\n";
            echo "  - Обновлено: {$section->updated_at}\n";
            echo "\n";
        }
        
        echo "====================================================\n";
        echo "Данные в том формате, в котором они передаются в view:\n";
        $orderedSections = $sectionSettings->sortBy('order');
        foreach($orderedSections as $section) {
            if($section->is_visible) {
                echo "Секция {$section->section_key}: title='{$section->title}', subtitle='{$section->subtitle}'\n";
            }
        }
    } else {
        echo "Настройки секций не найдены или все скрыты\n";
        
        // Проверим все настройки секций (включая скрытые)
        $allSettings = $user->sectionSettings()->get();
        echo "Всего настроек секций: " . $allSettings->count() . "\n";
        
        foreach ($allSettings as $setting) {
            echo "Секция {$setting->section_key}: visible=" . ($setting->is_visible ? 'true' : 'false') . ", order={$setting->order}\n";
        }
    }
} else {
    echo "Пользователь не найден\n";
}