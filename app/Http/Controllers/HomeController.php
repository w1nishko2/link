<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\GalleryImage;
use App\Models\Service;
use App\Models\Article;
use App\Models\Banner;
use App\Models\UserSectionSettings;
use App\Services\ImageProcessingService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Exception;

class HomeController extends Controller
{
    protected $imageService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ImageProcessingService $imageService)
    {
        $this->imageService = $imageService;
        $this->middleware('auth')->only(['index', 'updateProfile', 'updateBackground', 'updateAvatar']);
    }

    /**
     * Redirect to home page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToHome()
    {
        return redirect()->route('home');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        // Перенаправляем на персональную страницу пользователя
        $user = auth()->user();
        return redirect()->route('user.page', ['username' => $user->username]);
    }

    /**
     * Show user's personal page.
     *
     * @param string $username
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function userPage($username)
    {
        try {
            // Находим пользователя по username с оптимизированным eager loading
            $pageUser = User::where('username', $username)
                ->with([
                    'galleryImages' => function ($query) {
                        $query->select('id', 'user_id', 'image_path', 'alt_text', 'title', 'order_index', 'is_active')
                              ->active()
                              ->ordered();
                    },
                    'services' => function ($query) {
                        $query->select('id', 'user_id', 'title', 'description', 'price', 'image_path', 'button_text', 'button_link', 'order_index', 'is_active')
                              ->active()
                              ->ordered();
                    },
                    'articles' => function ($query) {
                        $query->select('id', 'user_id', 'title', 'excerpt', 'slug', 'image_path', 'created_at', 'read_time')
                              ->published()
                              ->latest()
                              ->limit(5);
                    },
                    'banners' => function ($query) {
                        $query->select('id', 'user_id', 'title', 'description', 'image_path', 'link_url', 'link_text', 'order_index', 'is_active')
                              ->active()
                              ->ordered();
                    },
                    'socialLinks' => function ($query) {
                        $query->select('id', 'user_id', 'service_name', 'url', 'icon_class', 'order')
                              ->ordered();
                    },
                    'sectionSettings' => function ($query) {
                        $query->select('id', 'user_id', 'section_key', 'title', 'subtitle', 'is_visible', 'order')
                              ->visible()
                              ->ordered();
                    }
                ])
                ->firstOrFail();
                
            // Получаем текущего пользователя (может быть null для неавторизованных)
            $currentUser = auth()->user();
            
            // Получаем настройки секций пользователя и создаем коллекцию по ключам
            $sectionSettings = $pageUser->sectionSettings->keyBy('section_key');
            
            // Если у пользователя нет настроек секций, создаем настройки по умолчанию
            if ($sectionSettings->isEmpty()) {
                $this->createDefaultSectionSettings($pageUser);
                // Перезагружаем настройки секций после создания
                $pageUser->load(['sectionSettings' => function ($query) {
                    $query->visible()->ordered();
                }]);
                $sectionSettings = $pageUser->sectionSettings->keyBy('section_key');
            }
            
            // Получаем данные
            $galleryImages = $pageUser->galleryImages;
            $services = $pageUser->services;
            $articles = $pageUser->articles;
            $banners = $pageUser->banners;
            $socialLinks = $pageUser->socialLinks;

            // Генерируем блоки галереи из реальных изображений
            $galleryBlocks = $this->generateGalleryBlocks($galleryImages->toArray());

            return view('home', compact(
                'galleryBlocks', 
                'services', 
                'articles', 
                'banners', 
                'pageUser', 
                'currentUser', 
                'socialLinks', 
                'sectionSettings'
            ));
            
        } catch (ModelNotFoundException $e) {
            Log::warning('Пользователь не найден', [
                'username' => $username,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            abort(404, 'Пользователь не найден');
            
        } catch (Exception $e) {
            Log::error('Ошибка загрузки страницы пользователя', [
                'username' => $username,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            abort(500, 'Произошла ошибка при загрузке страницы');
        }
    }

    /**
     * Update user's profile.
     *
     * @param string $username
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function updateProfile($username, Request $request)
    {
        try {
            // Находим пользователя по username
            $pageUser = User::where('username', $username)->firstOrFail();
            
            // Проверяем, что текущий пользователь может редактировать этот профиль
            $currentUser = auth()->user();
            if (!$currentUser || $currentUser->id !== $pageUser->id) {
                Log::warning('Попытка несанкционированного редактирования профиля', [
                    'target_username' => $username,
                    'current_user_id' => $currentUser?->id,
                    'ip' => request()->ip()
                ]);
                abort(403, 'Доступ запрещен');
            }
            
            // Валидация данных с улучшенными правилами
            $rules = [];
            $messages = [];
            
            // Валидация только присутствующих полей
            if ($request->has('name')) {
                $rules['name'] = [
                    'required',
                    'string',
                    'max:50',
                    'regex:/^[a-zA-Zа-яА-Я\s\-\'\.]+$/u'
                ];
                $messages['name.required'] = 'Имя обязательно для заполнения';
                $messages['name.max'] = 'Имя не должно превышать 50 символов';
                $messages['name.regex'] = 'Имя может содержать только буквы, пробелы, дефисы и апострофы';
            }
            
            if ($request->has('bio')) {
                $rules['bio'] = [
                    'nullable',
                    'string',
                    'max:1000',
                    'not_regex:/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi'
                ];
                $messages['bio.max'] = 'Описание не должно превышать 1000 символов';
                $messages['bio.not_regex'] = 'Описание содержит недопустимые элементы';
            }
            
            $validatedData = $request->validate($rules, $messages);
            
            // Обновляем данные пользователя
            $pageUser->update($validatedData);
            
            Log::info('Профиль пользователя обновлен', [
                'user_id' => $pageUser->id,
                'username' => $username,
                'updated_fields' => array_keys($validatedData)
            ]);
            
            // Проверяем, является ли запрос AJAX
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Профиль успешно обновлен!'
                ]);
            }
            
            return redirect()->route('user.page', ['username' => $username])
                            ->with('success', 'Профиль успешно обновлен!');
                            
        } catch (ModelNotFoundException $e) {
            Log::warning('Попытка обновления несуществующего профиля', [
                'username' => $username,
                'ip' => request()->ip()
            ]);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Пользователь не найден'], 404);
            }
            abort(404, 'Пользователь не найден');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false, 
                    'message' => $e->validator->errors()->first()
                ], 422);
            }
            // Validation errors will be automatically handled by Laravel
            throw $e;
            
        } catch (Exception $e) {
            Log::error('Ошибка обновления профиля', [
                'username' => $username,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => request()->ip()
            ]);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Произошла ошибка при обновлении профиля'], 500);
            }
            
            return redirect()->back()
                           ->withInput()
                           ->withErrors(['error' => 'Произошла ошибка при обновлении профиля. Попробуйте позже.']);
        }
    }

    /**
     * Генерирует блоки галереи с рандомным количеством изображений
     */
    private function generateGalleryBlocks($images)
    {
        if (empty($images)) {
            return [];
        }

        $blocks = [];
        $imageIndex = 0;
        $totalImages = count($images);
        
        // Варианты блоков: только 1 или 2 изображения (убираем type-3)
        $blockTypes = [1, 2];
        
        while ($imageIndex < $totalImages) {
            // Выбираем случайный тип блока
            $blockType = $blockTypes[array_rand($blockTypes)];
            
            // Определяем количество изображений для текущего блока
            $imagesInBlock = min($blockType, $totalImages - $imageIndex);
            
            // Создаем блок с изображениями
            $blockImages = array_slice($images, $imageIndex, $imagesInBlock);
            
            // Преобразуем данные модели в нужный формат
            $formattedImages = [];
            foreach ($blockImages as $image) {
                $formattedImages[] = [
                    'src' => '/storage/' . $image['image_path'],
                    'alt' => $image['alt_text'] ?: $image['title'] ?: 'Изображение галереи'
                ];
            }
            
            $block = [
                'type' => $imagesInBlock,
                'images' => $formattedImages
            ];
            
            $blocks[] = $block;
            $imageIndex += $imagesInBlock;
        }
        
        return $blocks;
    }

    /**
     * Создать настройки секций по умолчанию для пользователя
     */
    private function createDefaultSectionSettings($user)
    {
        $defaultSections = [
            ['section_key' => 'hero', 'order' => 1],
            ['section_key' => 'services', 'order' => 2],
            ['section_key' => 'gallery', 'order' => 3],
            ['section_key' => 'banners', 'order' => 4],
            ['section_key' => 'articles', 'order' => 5],
        ];

        foreach ($defaultSections as $section) {
            UserSectionSettings::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'section_key' => $section['section_key']
                ],
                [
                    'title' => null, // Пустой заголовок по умолчанию
                    'subtitle' => null, // Пустой подзаголовок по умолчанию
                    'is_visible' => true,
                    'order' => $section['order'],
                ]
            );
        }
    }

    /**
     * Update user's background image.
     *
     * @param string $username
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateBackground($username, Request $request)
    {
        try {
            $request->validate([
                'background_image' => 'required|file|max:10240|mimes:jpeg,png,jpg,gif,webp',
            ]);

            // Находим пользователя по username
            $pageUser = User::where('username', $username)->firstOrFail();
            
            // Проверяем, что текущий пользователь может редактировать этот профиль
            $currentUser = auth()->user();
            if (!$currentUser || $currentUser->id !== $pageUser->id) {
                return response()->json(['success' => false, 'message' => 'Доступ запрещен'], 403);
            }

            // Валидируем изображение
            $validationErrors = $this->imageService->validateImage($request->file('background_image'), 'background');
            if (!empty($validationErrors)) {
                return response()->json(['success' => false, 'message' => $validationErrors[0]], 422);
            }
            
            // Обрабатываем и сохраняем изображение
            $imagePath = $this->imageService->processAndStore(
                $request->file('background_image'), 
                'background', 
                'backgrounds/' . $pageUser->id,
                $pageUser->background_image
            );
            
            if ($imagePath) {
                $pageUser->update(['background_image' => $imagePath]);
                
                Log::info('Фон пользователя обновлен', [
                    'user_id' => $pageUser->id,
                    'username' => $username,
                    'new_image_path' => $imagePath
                ]);

                return response()->json([
                    'success' => true, 
                    'message' => 'Фон успешно обновлен!',
                    'image_url' => asset('storage/' . $imagePath)
                ]);
            }

            return response()->json(['success' => false, 'message' => 'Ошибка при сохранении изображения'], 500);
                            
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Пользователь не найден'], 404);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->first();
            return response()->json(['success' => false, 'message' => $errors], 422);
            
        } catch (Exception $e) {
            Log::error('Ошибка при обновлении фона', [
                'username' => $username,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['success' => false, 'message' => 'Произошла ошибка при обновлении фона'], 500);
        }
    }

    /**
     * Update user's avatar.
     *
     * @param string $username
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAvatar($username, Request $request)
    {
        try {
            $request->validate([
                'avatar' => 'required|file|max:10240|mimes:jpeg,png,jpg,gif,webp',
            ]);

            // Находим пользователя по username
            $pageUser = User::where('username', $username)->firstOrFail();
            
            // Проверяем, что текущий пользователь может редактировать этот профиль
            $currentUser = auth()->user();
            if (!$currentUser || $currentUser->id !== $pageUser->id) {
                return response()->json(['success' => false, 'message' => 'Доступ запрещен'], 403);
            }

            // Валидируем изображение
            $validationErrors = $this->imageService->validateImage($request->file('avatar'), 'avatar');
            if (!empty($validationErrors)) {
                return response()->json(['success' => false, 'message' => $validationErrors[0]], 422);
            }
            
            // Обрабатываем и сохраняем изображение
            $imagePath = $this->imageService->processAndStore(
                $request->file('avatar'), 
                'avatar', 
                'avatars/' . $pageUser->id,
                $pageUser->avatar
            );
            
            if ($imagePath) {
                $pageUser->update(['avatar' => $imagePath]);
                
                Log::info('Аватар пользователя обновлен', [
                    'user_id' => $pageUser->id,
                    'username' => $username,
                    'new_image_path' => $imagePath
                ]);

                return response()->json([
                    'success' => true, 
                    'message' => 'Аватар успешно обновлен!',
                    'image_url' => asset('storage/' . $imagePath)
                ]);
            }

            return response()->json(['success' => false, 'message' => 'Ошибка при сохранении изображения'], 500);
                            
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Пользователь не найден'], 404);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->first();
            return response()->json(['success' => false, 'message' => $errors], 422);
            
        } catch (Exception $e) {
            Log::error('Ошибка при обновлении аватара', [
                'username' => $username,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['success' => false, 'message' => 'Произошла ошибка при обновлении аватара'], 500);
        }
    }
}
