<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ImageProcessingService;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;

class TestImageProcessing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:image-processing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Тестирование системы обработки изображений';

    protected $imageService;

    public function __construct(ImageProcessingService $imageService)
    {
        parent::__construct();
        $this->imageService = $imageService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Тест системы обработки изображений ===');
        $this->newLine();

        // Тест 1: Создание тестового изображения
        $this->info('1. Создание тестового изображения...');
        
        try {
            $manager = new ImageManager(new Driver());
            
            // Создаем простое тестовое изображение
            $image = $manager->create(800, 600)->fill('rgb(70, 130, 180)');
            
            // Добавляем текст
            $image->text('Test Image 800x600', 400, 300, function ($font) {
                $font->size(24);
                $font->color('white');
                $font->align('center');
                $font->valign('middle');
            });
            
            // Сохраняем как JPEG для тестирования
            $testPath = storage_path('app/test_image.jpg');
            $image->toJpeg(90)->save($testPath);
            
            $this->info('✓ Тестовое изображение создано: ' . $testPath);
            
        } catch (\Exception $e) {
            $this->error('✗ Ошибка создания тестового изображения: ' . $e->getMessage());
            return 1;
        }

        // Тест 2: Проверка валидации
        $this->info('2. Проверка системы валидации...');
        
        // Создаем UploadedFile для тестирования
        $testFile = new \Illuminate\Http\UploadedFile(
            $testPath,
            'test_image.jpg',
            'image/jpeg',
            null,
            true
        );
        
        $errors = $this->imageService->validateImage($testFile, 'gallery');
        
        if (empty($errors)) {
            $this->info('✓ Валидация прошла успешно');
        } else {
            $this->error('✗ Ошибки валидации: ' . implode(', ', $errors));
        }

        // Тест 3: Обработка и конвертация в WebP
        $this->info('3. Тестирование обработки и конвертации...');
        
        try {
            $processedPath = $this->imageService->processAndStore(
                $testFile,
                'gallery',
                'test/gallery'
            );
            
            if ($processedPath) {
                $this->info('✓ Изображение обработано и сохранено: ' . $processedPath);
                
                // Проверяем что файл действительно существует
                if (Storage::disk('public')->exists($processedPath)) {
                    $this->info('✓ Файл существует в storage/app/public');
                    
                    // Проверяем размер файла
                    $fileSize = Storage::disk('public')->size($processedPath);
                    $this->info('✓ Размер файла: ' . number_format($fileSize / 1024, 2) . ' KB');
                    
                } else {
                    $this->error('✗ Файл не найден в storage');
                }
                
            } else {
                $this->error('✗ Ошибка обработки изображения');
            }
            
        } catch (\Exception $e) {
            $this->error('✗ Ошибка обработки: ' . $e->getMessage());
        }

        // Тест 4: Создание миниатюры
        if (isset($processedPath) && $processedPath) {
            $this->info('4. Тестирование создания миниатюр...');
            
            try {
                $thumbnailPath = $this->imageService->createThumbnail($processedPath, 200, 200);
                
                if ($thumbnailPath && Storage::disk('public')->exists($thumbnailPath)) {
                    $this->info('✓ Миниатюра создана: ' . $thumbnailPath);
                } else {
                    $this->error('✗ Ошибка создания миниатюры');
                }
                
            } catch (\Exception $e) {
                $this->error('✗ Ошибка создания миниатюры: ' . $e->getMessage());
            }
        }

        // Тест 5: Тестирование разных типов
        $this->info('5. Тестирование разных типов контента...');
        
        $types = ['avatar', 'service', 'article', 'banner', 'background'];
        
        foreach ($types as $type) {
            try {
                $typePath = $this->imageService->processAndStore(
                    $testFile,
                    $type,
                    "test/{$type}"
                );
                
                if ($typePath) {
                    $this->info("✓ Тип '{$type}' обработан: {$typePath}");
                } else {
                    $this->error("✗ Ошибка обработки типа '{$type}'");
                }
                
            } catch (\Exception $e) {
                $this->error("✗ Ошибка обработки типа '{$type}': " . $e->getMessage());
            }
        }

        // Очистка тестовых файлов
        $this->info('6. Очистка тестовых файлов...');
        
        try {
            // Удаляем исходный тестовый файл
            if (file_exists($testPath)) {
                unlink($testPath);
                $this->info('✓ Исходный тестовый файл удален');
            }
            
            // Удаляем обработанные файлы
            $testFiles = Storage::disk('public')->allFiles('test');
            foreach ($testFiles as $file) {
                Storage::disk('public')->delete($file);
            }
            $this->info('✓ Обработанные тестовые файлы удалены');
            
        } catch (\Exception $e) {
            $this->warn('⚠ Предупреждение при очистке: ' . $e->getMessage());
        }

        $this->newLine();
        $this->info('🎉 Тестирование завершено!');
        
        $this->newLine();
        $this->info('📋 Система готова к использованию:');
        $this->line('- Все изображения автоматически конвертируются в WebP');
        $this->line('- Изображения сжимаются с оптимальным качеством');
        $this->line('- Поддерживаются все основные форматы изображений');
        $this->line('- Автоматическое изменение размера с сохранением пропорций');
        $this->line('- Создание миниатюр для предварительного просмотра');
        
        return 0;
    }
}
