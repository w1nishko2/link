<?php
require_once 'vendor/autoload.php';

// Загружаем Laravel приложение
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$user = User::where('username', 'weebs')->first();

if ($user) {
    echo "User found: " . $user->name . "\n";
    echo "Bio: " . ($user->bio ?? 'No bio') . "\n";
    echo "\nSection settings:\n";
    
    $sections = $user->sectionSettings;
    if ($sections->count() > 0) {
        foreach ($sections as $section) {
            echo $section->section_key . ': title="' . $section->title . '" | subtitle="' . $section->subtitle . '"\n';
        }
    } else {
        echo "No section settings found\n";
    }
} else {
    echo "User not found\n";
}