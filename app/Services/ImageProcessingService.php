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
        'avatar' => 90,      // Очень высокое качество для аватаров (маленькие файлы)
        'gallery' => 85,     // Высокое качество для галереи
        'service' => 82,     // Хорошее качество для услуг
        'article' => 82,     // Хорошее качество для статей  
        'banner' => 85,      // Высокое качество для баннеров
        'background' => 70,  // Снижено для быстрой обработки фонов
    ];

    // Адаптивные настройки качества в зависимости от размера файла
    protected $adaptiveQualitySettings = [
        'small' => ['max_size' => 1024*1024, 'quality_bonus' => 10],     // <1MB - повышаем качество
        'medium' => ['max_size' => 3*1024*1024, 'quality_bonus' => 0],   // 1-3MB - стандартное качество  
        'large' => ['max_size' => 8*1024*1024, 'quality_bonus' => -10],  // 3-8MB - понижаем качество
        'huge' => ['max_size' => PHP_INT_MAX, 'quality_bonus' => -20],   // >8MB - сильно понижаем
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
        // Инициализируем ImageManager один раз в конструкторе
        $this->imageManager = new ImageManager(new Driver());
    }

    /**
     * Определяет оптимальное качество сжатия на основе размера файла
     */
    protected function getOptimalQuality(int $fileSize, string $type): int
    {
        $baseQuality = $this->qualitySettings[$type] ?? 75;
        
        foreach ($this->adaptiveQualitySettings as $category => $settings) {
            if ($fileSize <= $settings['max_size']) {
                $quality = $baseQuality + $settings['quality_bonus'];
                // Ограничиваем качество в разумных пределах
                return max(40, min(95, $quality));
            }
        }
        
        return $baseQuality;
    }

    /**
     * Быстрое предварительное сжатие для больших файлов
     * Оптимизировано для HEIC и других больших форматов
     */
    protected function quickCompress(UploadedFile $file): string
    {
        $fileSize = $file->getSize();
        $isHeic = $this->isHeicFormat($file);
        
        // Для HEIC файлов или файлов больше 2MB применяем быстрое сжатие
        if ($isHeic || $fileSize > 2 * 1024 * 1024) {
            $tempPath = tempnam(sys_get_temp_dir(), 'quick_compress_');
            
            try {
                $image = $this->getImageManager()->read($file->getPathname());
                
                // Для HEIC файлов применяем более агрессивное сжатие
                if ($isHeic) {
                    $maxDimension = 1200; // Меньше для HEIC файлов
                    $quality = 55; // Более низкое качество
                } else {
                    $maxDimension = 1500; // Стандартное для других форматов
                    $quality = 60;
                }
                
                // Агрессивное уменьшение размера для ускорения
                if ($image->width() > $maxDimension || $image->height() > $maxDimension) {
                    $image = $image->scale($maxDimension, $maxDimension); // Быстрый scale для HEIC
                }
                
                // Сохраняем с оптимизированным качеством во временный файл
                $compressed = $image->toJpeg($quality);
                file_put_contents($tempPath, $compressed);
                
                Log::info("HEIC/большой файл быстро сжат: {$fileSize} байт -> " . filesize($tempPath) . " байт");
                
                return $tempPath;
                
            } catch (\Exception $e) {
                Log::error('Ошибка быстрого сжатия: ' . $e->getMessage());
                // Возвращаем оригинальный путь при ошибке
                return $file->getPathname();
            }
        }
        
        return $file->getPathname();
    }
    
    protected function getImageManager()
    {
        return $this->imageManager;
    }

    /**
     * Основной метод для обработки изображения
     * Оптимизирован для быстрого сжатия больших файлов
     *
     * @param UploadedFile $file
     * @param string $type - тип изображения (avatar, gallery, service, article, banner, background)
     * @param string $folder - папка для сохранения
     * @param string|null $oldImagePath - путь к старому изображению для удаления
     * @return string - путь к сохраненному файлу
     */
    public function processAndStore(UploadedFile $file, string $type, string $folder, ?string $oldImagePath = null): string
    {
        $startTime = microtime(true);
        
        // Удаляем старое изображение если есть
        if ($oldImagePath) {
            Storage::disk('public')->delete($oldImagePath);
        }

        // Определяем адаптивное качество на основе размера файла
        $fileSize = $file->getSize();
        $quality = $this->getOptimalQuality($fileSize, $type);
        $maxSize = $this->maxSizes[$type] ?? ['width' => 1200, 'height' => 800];

        // Создаем уникальное имя файла
        $filename = time() . '_' . uniqid() . '.webp';
        $fullPath = $folder . '/' . $filename;

        try {
            // Быстрое пре-сжатие для больших файлов
            $imagePath = $this->quickCompress($file);
            $isTemporaryFile = $imagePath !== $file->getPathname();
            
            // Обрабатываем изображение с оптимизированными настройками
            $image = $this->getImageManager()->read($imagePath);

            // Изменяем размер если изображение больше максимального
            $originalWidth = $image->width();
            $originalHeight = $image->height();

            // Умное масштабирование - выбираем алгоритм в зависимости от размера
            if ($originalWidth > $maxSize['width'] || $originalHeight > $maxSize['height']) {
                $image = $this->smartResize($image, $maxSize, $fileSize);
            }

            // Для аватаров делаем квадратными с оптимизацией
            if ($type === 'avatar') {
                $size = min($image->width(), $image->height());
                // Кропаем от центра для лучшего качества
                $image = $image->crop($size, $size);
            }

            // Конвертируем в WebP с оптимизированным качеством
            $encodedImage = $image->toWebp($quality);
            
            // Сохраняем в storage
            Storage::disk('public')->put($fullPath, $encodedImage);

            // Очищаем временный файл если создавали
            if ($isTemporaryFile && file_exists($imagePath)) {
                unlink($imagePath);
            }

            $processingTime = round((microtime(true) - $startTime) * 1000, 2);
            Log::info("Изображение обработано за {$processingTime}мс, размер файла: " . round($fileSize/1024/1024, 2) . "MB, качество: {$quality}");

            return $fullPath;
            
        } catch (\Exception $e) {
            Log::error('Ошибка обработки изображения: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Умное масштабирование в зависимости от размера изображения
     */
    protected function smartResize($image, array $maxSize, int $fileSize)
    {
        // Для очень больших файлов используем более быстрый алгоритм
        if ($fileSize > 5 * 1024 * 1024) { // > 5MB
            // Быстрое масштабирование с меньшим качеством
            return $image->scale(
                width: $maxSize['width'], 
                height: $maxSize['height']
            );
        } else {
            // Качественное масштабирование для файлов поменьше
            return $image->scaleDown(
                width: $maxSize['width'],
                height: $maxSize['height']
            );
        }
    }

    /**
     * Проверяет, является ли файл изображением
     * Поддерживает HEIC формат с iPhone
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
            'image/svg+xml',
            'image/heic',        // iPhone HEIC формат
            'image/heif',        // HEIF формат
            'application/octet-stream' // Иногда HEIC передается как octet-stream
        ];

        $mimeType = $file->getMimeType();
        $extension = strtolower($file->getClientOriginalExtension());
        
        // Проверяем MIME-type
        if (in_array($mimeType, $allowedMimes)) {
            return true;
        }
        
        // Дополнительная проверка по расширению для HEIC файлов
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'tiff', 'svg', 'heic', 'heif'];
        if (in_array($extension, $allowedExtensions)) {
            return true;
        }
        
        return false;
    }

    /**
     * Проверяет, является ли файл HEIC/HEIF форматом
     */
    protected function isHeicFormat(UploadedFile $file): bool
    {
        $mimeType = $file->getMimeType();
        $extension = strtolower($file->getClientOriginalExtension());
        
        return in_array($mimeType, ['image/heic', 'image/heif']) || 
               in_array($extension, ['heic', 'heif']) ||
               ($mimeType === 'application/octet-stream' && in_array($extension, ['heic', 'heif']));
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
     * Оптимизированная для разных типов и форматов
     */
    public function validateImage(UploadedFile $file, string $type): array
    {
        $errors = [];
        $fileSize = $file->getSize();
        $isHeic = $this->isHeicFormat($file);
        
        // Увеличиваем лимит для HEIC файлов (они обычно больше)
        $maxSize = $isHeic ? 20 * 1024 * 1024 : 15 * 1024 * 1024; // 20MB для HEIC, 15MB для других
        
        // Проверяем размер файла
        if ($fileSize > $maxSize) {
            $maxSizeMB = round($maxSize / 1024 / 1024);
            $errors[] = "Размер файла не должен превышать {$maxSizeMB}MB" . ($isHeic ? ' (для HEIC файлов)' : '');
        }
        
        // Проверяем тип файла
        if (!$this->isValidImage($file)) {
            $errors[] = 'Неподдерживаемый формат изображения. Поддерживаются: JPG, PNG, WebP, HEIC и другие';
        }
        
        // Проверяем размеры изображения
        try {
            $info = $this->getImageInfo($file);
            
            // Минимальные размеры (более строгие для аватаров)
            $minSize = $type === 'avatar' ? 100 : 50;
            if ($info['width'] < $minSize || $info['height'] < $minSize) {
                $errors[] = "Изображение слишком маленькое (минимум {$minSize}x{$minSize}px для {$type})";
            }
            
            // Максимальные размеры (более щедрые для HEIC)
            $maxDimension = $isHeic ? 8000 : 6000;
            if ($info['width'] > $maxDimension || $info['height'] > $maxDimension) {
                $errors[] = "Изображение слишком большое (максимум {$maxDimension}x{$maxDimension}px)";
            }
            
            // Предупреждение о больших файлах
            if ($fileSize > 5 * 1024 * 1024) {
                $sizeMB = round($fileSize / 1024 / 1024, 1);
                Log::info("Загружается большой файл: {$sizeMB}MB, HEIC: " . ($isHeic ? 'да' : 'нет'));
            }
            
        } catch (\Exception $e) {
            Log::error('Ошибка валидации изображения: ' . $e->getMessage());
            $errors[] = 'Не удалось обработать изображение. Попробуйте другой файл.';
        }
        
        return $errors;
    }

    /**
     * Быстрая обработка изображения для превью (без сохранения)
     * Оптимизирована для мгновенного отображения
     */
    public function createPreview(UploadedFile $file, string $type): ?string
    {
        try {
            $startTime = microtime(true);
            
            // Быстрые настройки для превью
            $previewSize = ['width' => 400, 'height' => 300]; // Маленький размер для скорости
            $quality = 70; // Низкое качество для скорости
            
            // Быстрое пре-сжатие если файл большой
            $imagePath = $this->quickCompress($file);
            $isTemporaryFile = $imagePath !== $file->getPathname();
            
            $image = $this->getImageManager()->read($imagePath);
            
            // Агрессивное уменьшение для превью
            if ($image->width() > $previewSize['width'] || $image->height() > $previewSize['height']) {
                $image = $image->scale( // Используем быстрый scale вместо scaleDown
                    width: $previewSize['width'], 
                    height: $previewSize['height']
                );
            }
            
            // Для аватаров делаем квадратными
            if ($type === 'avatar') {
                $size = min($image->width(), $image->height());
                $image = $image->crop($size, $size);
            }
            
            // Конвертируем в JPEG для максимальной скорости
            $encodedImage = $image->toJpeg($quality);
            
            // Очищаем временный файл
            if ($isTemporaryFile && file_exists($imagePath)) {
                unlink($imagePath);
            }
            
            $processingTime = round((microtime(true) - $startTime) * 1000, 2);
            Log::info("Превью создано за {$processingTime}мс");
            
            return 'data:image/jpeg;base64,' . base64_encode($encodedImage);
            
        } catch (\Exception $e) {
            Log::error('Ошибка создания превью: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Прогрессивная обработка: быстрое превью + полная обработка
     * Возвращает временный путь для немедленного отображения
     */
    public function processProgressively(UploadedFile $file, string $type, string $folder): array
    {
        try {
            // 1. Создаем быстрое превью для немедленного отображения
            $preview = $this->createPreview($file, $type);
            
            // 2. Создаем временный файл с быстрым сжатием
            $tempPath = $this->createTempProcessed($file, $type);
            
            return [
                'preview' => $preview,        // base64 для немедленного показа
                'temp_path' => $tempPath,     // временный путь для промежуточного показа
                'status' => 'processing'     // статус обработки
            ];
            
        } catch (\Exception $e) {
            Log::error('Ошибка прогрессивной обработки: ' . $e->getMessage());
            return [
                'preview' => null,
                'temp_path' => null,
                'status' => 'error',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Создает временный обработанный файл для промежуточного показа
     */
    protected function createTempProcessed(UploadedFile $file, string $type): string
    {
        $startTime = microtime(true);
        $fileSize = $file->getSize();
        $quality = max(50, $this->getOptimalQuality($fileSize, $type) - 20); // Снижаем качество для скорости
        $maxSize = $this->maxSizes[$type] ?? ['width' => 1200, 'height' => 800];
        
        // Создаем временное имя файла
        $tempFileName = 'temp_' . time() . '_' . uniqid() . '.webp';
        $tempPath = 'temp/' . $tempFileName;
        
        // Быстрое сжатие
        $imagePath = $this->quickCompress($file);
        $isTemporaryFile = $imagePath !== $file->getPathname();
        
        $image = $this->getImageManager()->read($imagePath);
        
        // Быстрое масштабирование
        if ($image->width() > $maxSize['width'] || $image->height() > $maxSize['height']) {
            $image = $image->scale($maxSize['width'], $maxSize['height']);
        }
        
        // Для аватаров
        if ($type === 'avatar') {
            $size = min($image->width(), $image->height());
            $image = $image->crop($size, $size);
        }
        
        // Сохраняем временный файл
        $encodedImage = $image->toWebp($quality);
        Storage::disk('public')->put($tempPath, $encodedImage);
        
        // Очищаем временный файл
        if ($isTemporaryFile && file_exists($imagePath)) {
            unlink($imagePath);
        }
        
        $processingTime = round((microtime(true) - $startTime) * 1000, 2);
        Log::info("Временный файл создан за {$processingTime}мс");
        
        return $tempPath;
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

    /**
     * Оптимизированная обработка для фоновых изображений
     * Специально для быстрой смены фона
     */
    public function processBackgroundImage(UploadedFile $file, string $folder, ?string $oldImagePath = null): array
    {
        $startTime = microtime(true);
        
        try {
            // Создаем быстрое превью для немедленного показа
            $preview = $this->createPreview($file, 'background');
            
            // Обрабатываем с агрессивными настройками для скорости
            $fileSize = $file->getSize();
            $isHeic = $this->isHeicFormat($file);
            
            // Для фонов используем более низкое качество для скорости
            $quality = $isHeic ? 50 : ($fileSize > 5 * 1024 * 1024 ? 55 : 65);
            
            // Быстрое пре-сжатие
            $imagePath = $this->quickCompress($file);
            $isTemporaryFile = $imagePath !== $file->getPathname();
            
            // Создаем уникальное имя файла
            $filename = time() . '_' . uniqid() . '.webp';
            $fullPath = $folder . '/' . $filename;
            
            $image = $this->getImageManager()->read($imagePath);
            
            // Агрессивное масштабирование для фонов
            $maxSize = ['width' => 1920, 'height' => 1080];
            if ($image->width() > $maxSize['width'] || $image->height() > $maxSize['height']) {
                $image = $image->scale($maxSize['width'], $maxSize['height']);
            }
            
            // Быстрое сохранение
            $encodedImage = $image->toWebp($quality);
            Storage::disk('public')->put($fullPath, $encodedImage);
            
            // Удаляем старый файл
            if ($oldImagePath) {
                Storage::disk('public')->delete($oldImagePath);
            }
            
            // Очищаем временный файл
            if ($isTemporaryFile && file_exists($imagePath)) {
                unlink($imagePath);
            }
            
            $processingTime = round((microtime(true) - $startTime) * 1000, 2);
            
            return [
                'success' => true,
                'path' => $fullPath,
                'preview' => $preview,
                'processing_time' => $processingTime,
                'original_size' => round($fileSize / 1024 / 1024, 2),
                'final_quality' => $quality,
                'is_heic' => $isHeic
            ];
            
        } catch (\Exception $e) {
            Log::error('Ошибка обработки фонового изображения: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'processing_time' => round((microtime(true) - $startTime) * 1000, 2)
            ];
        }
    }
}
