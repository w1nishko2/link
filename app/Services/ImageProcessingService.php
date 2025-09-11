<?php

namespace App\Services;

use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;
use Exception;

class ImageProcessingService
{
    protected $imageManager;
    
    // Настройки качества для разных типов изображений
    protected $qualitySettings = [
        'avatar' => 85,      // Высокое качество для аватаров
        'gallery' => 80,     // Хорошее качество для галереи
        'service' => 75,     // Среднее качество для услуг
        'article' => 75,     // Среднее качество для статей
        'banner' => 80,      // Хорошее качество для баннеров
        'background' => 70,  // Низкое качество для фонов (большие файлы)
    ];

    // Максимальные размеры для разных типов
    protected $maxSizes = [
        'avatar' => ['width' => 400, 'height' => 400],
        'gallery' => ['width' => 1200, 'height' => 800],
        'service' => ['width' => 800, 'height' => 600],
        'article' => ['width' => 1200, 'height' => 800],
        'banner' => ['width' => 1200, 'height' => 600],
        'background' => ['width' => 1920, 'height' => 1080],
    ];

    public function __construct()
    {
        // Отложенная инициализация ImageManager
    }
    
    protected function getImageManager()
    {
        if (!$this->imageManager) {
            $this->imageManager = new ImageManager(new Driver());
        }
        return $this->imageManager;
    }

    /**
     * Основной метод для обработки изображения
     *
     * @param UploadedFile $file
     * @param string $type - тип изображения (avatar, gallery, service, article, banner, background)
     * @param string $folder - папка для сохранения
     * @param string|null $oldImagePath - путь к старому изображению для удаления
     * @return string - путь к сохраненному файлу
     */
    public function processAndStore(UploadedFile $file, string $type, string $folder, ?string $oldImagePath = null): string
    {
        // Удаляем старое изображение если есть
        if ($oldImagePath) {
            Storage::disk('public')->delete($oldImagePath);
        }

        // Получаем настройки для данного типа
        $quality = $this->qualitySettings[$type] ?? 75;
        $maxSize = $this->maxSizes[$type] ?? ['width' => 1200, 'height' => 800];

        // Создаем уникальное имя файла
        $filename = time() . '_' . uniqid() . '.webp';
        $fullPath = $folder . '/' . $filename;

        // Обрабатываем изображение
        $image = $this->getImageManager()->read($file->getPathname());

        // Изменяем размер если изображение больше максимального
        $originalWidth = $image->width();
        $originalHeight = $image->height();

        if ($originalWidth > $maxSize['width'] || $originalHeight > $maxSize['height']) {
            $image = $image->scaleDown(
                width: $maxSize['width'],
                height: $maxSize['height']
            );
        }

        // Для аватаров делаем квадратными
        if ($type === 'avatar') {
            $size = min($image->width(), $image->height());
            $image = $image->crop($size, $size);
        }

        // Конвертируем в WebP и сохраняем
        $encodedImage = $image->toWebp($quality);
        
        // Сохраняем в storage
        Storage::disk('public')->put($fullPath, $encodedImage);

        return $fullPath;
    }

    /**
     * Проверяет, является ли файл изображением
     */
    public function isValidImage(UploadedFile $file): bool
    {
        $allowedMimes = [
            'image/jpeg',
            'image/jpg', 
            'image/png',
            'image/gif',
            'image/webp',
            'image/bmp',
            'image/tiff',
            'image/svg+xml'
        ];

        return in_array($file->getMimeType(), $allowedMimes);
    }

    /**
     * Получает информацию об изображении
     */
    public function getImageInfo(UploadedFile $file): array
    {
        $image = $this->getImageManager()->read($file->getPathname());
        
        return [
            'width' => $image->width(),
            'height' => $image->height(),
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'extension' => $file->getClientOriginalExtension(),
        ];
    }

    /**
     * Создает thumbnail (миниатюру) изображения
     */
    public function createThumbnail(string $imagePath, int $width = 150, int $height = 150): string
    {
        $fullPath = storage_path('app/public/' . $imagePath);
        $image = $this->getImageManager()->read($fullPath);
        
        $thumbnail = $image->scaleDown($width, $height);
        
        $pathInfo = pathinfo($imagePath);
        $thumbnailPath = $pathInfo['dirname'] . '/thumb_' . $pathInfo['filename'] . '.webp';
        
        $encodedThumbnail = $thumbnail->toWebp(75);
        Storage::disk('public')->put($thumbnailPath, $encodedThumbnail);
        
        return $thumbnailPath;
    }

    /**
     * Валидация файла изображения
     */
    public function validateImage(UploadedFile $file, string $type): array
    {
        $errors = [];
        
        // Проверяем размер файла (макс. 10MB)
        if ($file->getSize() > 10 * 1024 * 1024) {
            $errors[] = 'Размер файла не должен превышать 10MB';
        }
        
        // Проверяем тип файла
        if (!$this->isValidImage($file)) {
            $errors[] = 'Неподдерживаемый формат изображения';
        }
        
        // Проверяем размеры изображения
        try {
            $info = $this->getImageInfo($file);
            
            // Минимальные размеры
            if ($info['width'] < 50 || $info['height'] < 50) {
                $errors[] = 'Изображение слишком маленькое (минимум 50x50px)';
            }
            
            // Максимальные размеры
            if ($info['width'] > 5000 || $info['height'] > 5000) {
                $errors[] = 'Изображение слишком большое (максимум 5000x5000px)';
            }
            
        } catch (\Exception $e) {
            $errors[] = 'Не удалось обработать изображение';
        }
        
        return $errors;
    }

    /**
     * Удаляет файл изображения
     */
    public function deleteImage(?string $imagePath): bool
    {
        if (!$imagePath) {
            return true;
        }
        
        try {
            if (Storage::disk('public')->exists($imagePath)) {
                return Storage::disk('public')->delete($imagePath);
            }
            return true;
        } catch (\Exception $e) {
            Log::error('Ошибка удаления изображения: ' . $e->getMessage());
            return false;
        }
    }
}
