<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PhotoEditorController extends Controller
{
    /**
     * Сохранение отредактированных изображений
     */
    public function save(Request $request)
    {
        try {
            // Проверяем аутентификацию
            if (!auth()->check()) {
                Log::warning('PhotoEditor: Unauthorized access attempt');
                return response()->json([
                    'success' => false,
                    'message' => 'Необходима авторизация'
                ], 401);
            }

            $user = auth()->user();
            $type = $request->input('type');

            Log::info('PhotoEditor: Save request', [
                'user_id' => $user->id,
                'type' => $type,
                'files' => $request->files->keys()
            ]);

            // Валидация типа редактирования
            if (!in_array($type, ['hero', 'avatar'])) {
                Log::warning('PhotoEditor: Invalid type', ['type' => $type]);
                return response()->json([
                    'success' => false,
                    'message' => 'Недопустимый тип редактирования'
                ], 400);
            }

            $savedImages = [];

            if ($type === 'hero') {
                // Обработка hero изображений (рилс и десктоп)
                $this->validateHeroImages($request);
                
                if ($request->hasFile('hero_reel')) {
                    $reelPath = $this->saveImage($request->file('hero_reel'), $user->id, 'hero_reel');
                    $user->background_image_mobile = $reelPath;
                    $savedImages['hero_reel'] = asset('storage/' . $reelPath);
                }

                if ($request->hasFile('hero_desktop')) {
                    $desktopPath = $this->saveImage($request->file('hero_desktop'), $user->id, 'hero_desktop');
                    $user->background_image_pc = $desktopPath;
                    $savedImages['hero_desktop'] = asset('storage/' . $desktopPath);
                }

            } elseif ($type === 'avatar') {
                // Обработка аватарки
                $this->validateAvatarImage($request);
                
                if ($request->hasFile('avatar')) {
                    $avatarPath = $this->saveImage($request->file('avatar'), $user->id, 'avatar');
                    $user->avatar = $avatarPath;
                    $savedImages['avatar'] = asset('storage/' . $avatarPath);
                }
            }

            // Сохраняем изменения пользователя
            $user->save();

            Log::info('PhotoEditor: Images saved successfully', [
                'user_id' => $user->id,
                'type' => $type,
                'saved_images' => $savedImages
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Изображения успешно сохранены',
                'data' => $savedImages
            ]);

        } catch (\Exception $e) {
            Log::error('Ошибка в PhotoEditor::save', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Произошла ошибка при сохранении изображений'
            ], 500);
        }
    }

    /**
     * Валидация hero изображений
     */
    private function validateHeroImages(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hero_reel' => 'sometimes|file|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'hero_desktop' => 'sometimes|file|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ], [
            'hero_reel.image' => 'Файл рилс-изображения должен быть изображением',
            'hero_reel.mimes' => 'Рилс-изображение должно быть в формате: jpeg, png, jpg, gif, webp',
            'hero_reel.max' => 'Размер рилс-изображения не должен превышать 10MB',
            'hero_desktop.image' => 'Файл десктоп-изображения должен быть изображением',
            'hero_desktop.mimes' => 'Десктоп-изображение должно быть в формате: jpeg, png, jpg, gif, webp',
            'hero_desktop.max' => 'Размер десктоп-изображения не должен превышать 10MB',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
    }

    /**
     * Валидация аватарки
     */
    private function validateAvatarImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|file|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ], [
            'avatar.required' => 'Необходимо выбрать изображение для аватара',
            'avatar.image' => 'Файл аватара должен быть изображением',
            'avatar.mimes' => 'Аватар должен быть в формате: jpeg, png, jpg, gif, webp',
            'avatar.max' => 'Размер аватара не должен превышать 5MB',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
    }

    /**
     * Сохранение изображения с оптимизацией
     */
    private function saveImage($file, $userId, $type)
    {
        // Удаляем старое изображение, если оно существует
        $this->deleteOldImage($userId, $type);

        // Генерируем уникальное имя файла
        $filename = $type . '_' . $userId . '_' . time() . '.webp';
        $path = 'users/' . $userId . '/' . $filename;

        // Создаем директорию, если она не существует
        $directory = 'users/' . $userId;
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        // Обрабатываем изображение с помощью Intervention Image
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file);

        // Применяем оптимизацию в зависимости от типа
        switch ($type) {
            case 'hero_reel':
                // Рилс формат (9:16) - максимум 600x1067
                $image->cover(600, 1067);
                break;
                
            case 'hero_desktop':
                // Десктоп формат (16:9) - максимум 1920x1080
                $image->cover(1920, 1080);
                break;
                
            case 'avatar':
                // Аватар (1:1) - максимум 300x300
                $image->cover(300, 300);
                break;
        }

        // Конвертируем в WebP с качеством 85%
        $webpImage = $image->toWebp(85);

        // Сохраняем файл
        Storage::disk('public')->put($path, $webpImage);

        return $path;
    }

    /**
     * Удаление старого изображения
     */
    private function deleteOldImage($userId, $type)
    {
        $user = User::find($userId);
        if (!$user) return;

        $oldPath = null;

        switch ($type) {
            case 'hero_reel':
                $oldPath = $user->background_image_mobile;
                break;
            case 'hero_desktop':
                $oldPath = $user->background_image_pc;
                break;
            case 'avatar':
                $oldPath = $user->avatar;
                break;
        }

        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }
    }

    /**
     * Получение текущих изображений пользователя
     */
    public function getCurrentImages(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Необходима авторизация'
            ], 401);
        }

        $user = auth()->user();

        return response()->json([
            'success' => true,
            'data' => [
                'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : null,
                'hero_desktop' => $user->background_image_pc ? asset('storage/' . $user->background_image_pc) : null,
                'hero_mobile' => $user->background_image_mobile ? asset('storage/' . $user->background_image_mobile) : null,
            ]
        ]);
    }

    /**
     * Удаление изображения
     */
    public function deleteImage(Request $request)
    {
        try {
            if (!auth()->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Необходима авторизация'
                ], 401);
            }

            $user = auth()->user();
            $type = $request->input('type');

            if (!in_array($type, ['hero_desktop', 'hero_mobile', 'avatar'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Недопустимый тип изображения'
                ], 400);
            }

            // Удаляем файл и обновляем БД
            $this->deleteOldImage($user->id, $type);

            switch ($type) {
                case 'hero_desktop':
                    $user->background_image_pc = null;
                    break;
                case 'hero_mobile':
                    $user->background_image_mobile = null;
                    break;
                case 'avatar':
                    $user->avatar = null;
                    break;
            }

            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Изображение успешно удалено'
            ]);

        } catch (\Exception $e) {
            Log::error('Ошибка в PhotoEditor::deleteImage', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'type' => $request->input('type')
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Произошла ошибка при удалении изображения'
            ], 500);
        }
    }
}