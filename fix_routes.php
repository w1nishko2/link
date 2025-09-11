<?php

// Скрипт для исправления всех ссылок на админ-маршруты
// Добавляет auth()->id() в качестве параметра пользователя

$viewsDir = __DIR__ . '/resources/views/admin/';
$homeView = __DIR__ . '/resources/views/home.blade.php';

function fixRoutes($content) {
    // Исправляем основные маршруты
    $content = preg_replace(
        '/route\([\'"]admin\.(gallery|services|articles|banners)[\'"]\)/',
        "route('admin.$1', auth()->id())",
        $content
    );
    
    // Исправляем маршруты create
    $content = preg_replace(
        '/route\([\'"]admin\.(gallery|services|articles|banners)\.create[\'"]\)/',
        "route('admin.$1.create', auth()->id())",
        $content
    );
    
    return $content;
}

// Обработка всех файлов в папке admin
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($viewsDir),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($files as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        $newContent = fixRoutes($content);
        
        if ($content !== $newContent) {
            file_put_contents($file->getPathname(), $newContent);
            echo "Fixed: " . $file->getPathname() . "\n";
        }
    }
}

echo "Routes fixed!\n";
