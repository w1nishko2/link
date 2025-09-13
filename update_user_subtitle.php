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
    echo "User bio: " . ($user->bio ?? 'No bio') . "\n";
    
    // Находим hero секцию
    $heroSection = UserSectionSettings::where('user_id', $user->id)
                                      ->where('section_key', 'hero')
                                      ->first();
    
    if ($heroSection) {
        echo "Current hero subtitle: " . $heroSection->subtitle . "\n";
        
        // Если у пользователя есть bio, используем его как подзаголовок
        if ($user->bio) {
            // Обрезаем bio если оно слишком длинное
            $subtitle = strlen($user->bio) > 200 ? substr($user->bio, 0, 200) . '...' : $user->bio;
            
            $heroSection->update([
                'subtitle' => $subtitle
            ]);
            
            echo "Updated hero subtitle to user's bio\n";
        } else {
            echo "User has no bio, keeping default subtitle\n";
        }
    } else {
        echo "Hero section not found\n";
    }
    
    echo "Update completed!\n";
} else {
    echo "User not found\n";
}