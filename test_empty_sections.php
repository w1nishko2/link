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
    
    $sectionSettings = $user->sectionSettings()->visible()->ordered()->get()->keyBy('section_key');
    
    echo "Количество видимых секций: " . $sectionSettings->count() . "\n\n";
    
    if ($sectionSettings->count() > 0) {
        foreach ($sectionSettings as $key => $section) {
            echo "Секция: {$key}\n";
            echo "  Заголовок: '" . ($section->title ?? 'NULL') . "'\n";
            echo "  Подзаголовок: '" . ($section->subtitle ?? 'NULL') . "'\n";
            echo "  Заголовок пустой: " . (empty(trim($section->title)) ? 'ДА' : 'НЕТ') . "\n";
            echo "  Подзаголовок пустой: " . (empty(trim($section->subtitle)) ? 'ДА' : 'НЕТ') . "\n";
            echo "  Порядок: {$section->order}\n";
            echo "  Видимость: " . ($section->is_visible ? 'ДА' : 'НЕТ') . "\n";
            echo "  ---\n";
        }
    } else {
        echo "Настройки секций не найдены\n";
    }
} else {
    echo "Пользователь не найден\n";
}