<?php
require_once 'vendor/autoload.php';

// Загружаем Laravel приложение
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\UserSectionSettings;

$user = User::where('username', 'weebs')->first();

if ($user) {
    echo "Updating user: " . $user->name . "\n";
    
    // Находим hero секцию
    $heroSection = UserSectionSettings::where('user_id', $user->id)
                                      ->where('section_key', 'hero')
                                      ->first();
    
    if ($heroSection) {
        echo "Current hero title: " . $heroSection->title . "\n";
        
        // Обновляем заголовок на имя пользователя
        $heroSection->update([
            'title' => $user->name
        ]);
        
        echo "Updated hero title to: " . $user->name . "\n";
    } else {
        echo "Hero section not found, creating new one\n";
        
        UserSectionSettings::create([
            'user_id' => $user->id,
            'section_key' => 'hero',
            'title' => $user->name,
            'subtitle' => 'Добро пожаловать на мою страницу',
            'is_visible' => true,
            'order' => 1
        ]);
    }
    
    echo "Update completed!\n";
} else {
    echo "User not found\n";
}