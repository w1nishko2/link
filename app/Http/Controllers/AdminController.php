<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\GalleryImage;
use App\Models\Service;
use App\Models\Article;
use App\Models\Banner;
use App\Models\UserSocialLink;
use App\Models\UserSectionSettings;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Services\ImageProcessingService;

class AdminController extends Controller
{
    protected $imageService;

    public function __construct(ImageProcessingService $imageService)
    {
        $this->middleware('auth');
        // Добавляем проверку доступа для защищенных методов админки
        $this->middleware(function ($request, $next) {
            // Проверяем, что пользователь может управлять только своими данными
            $routeUser = $request->route('user');
            $userId = $routeUser instanceof \App\Models\User ? $routeUser->id : $routeUser;
            if ($userId && auth()->user()->id != (int)$userId && !auth()->user()->isAdmin()) {
                abort(403, 'Доступ запрещен');
            }
            return $next($request);
        });
        
        $this->imageService = $imageService;
    }

    /**
     * Управление профилем
     */
    public function profile(User $user = null)
    {
        // Если параметр user не передан, используем текущего авторизованного пользователя
        if (!$user) {
            $user = auth()->user();
        }
        
        // Проверяем права доступа
        if (auth()->user()->id != $user->id) {
            abort(403, 'Доступ запрещен');
        }

        // По умолчанию показываем первую вкладку (основная информация)
        $tab = 'basic';

        return view('admin.profile', compact('user', 'tab'));
    }

    public function updateProfile(Request $request, User $user = null)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'bio' => 'nullable|string|max:190',
            'telegram_url' => 'nullable|url|max:255',
            'whatsapp_url' => 'nullable|url|max:255',
            'vk_url' => 'nullable|url|max:255',
            'youtube_url' => 'nullable|url|max:255',
            'ok_url' => 'nullable|url|max:255',
            'background_image' => 'nullable|file|max:10240',
            'avatar' => 'nullable|file|max:10240',
        ]);

        // Если параметр user не передан, используем текущего авторизованного пользователя
        if (!$user) {
            $user = auth()->user();
        }
        
        // Проверяем права доступа
        if (auth()->user()->id != $user->id) {
            abort(403, 'Доступ запрещен');
        }
        
        // Обрабатываем загрузку фонового изображения и аватара
        $data = $request->only([
            'name', 
            'bio', 
            'telegram_url', 
            'whatsapp_url', 
            'vk_url', 
            'youtube_url', 
            'ok_url'
        ]);
        
        if ($request->hasFile('background_image')) {
            // Валидируем изображение
            $validationErrors = $this->imageService->validateImage($request->file('background_image'), 'background');
            if (!empty($validationErrors)) {
                return redirect()->back()->withErrors(['background_image' => $validationErrors[0]]);
            }
            
            // Обрабатываем и сохраняем изображение
            $imagePath = $this->imageService->processAndStore(
                $request->file('background_image'), 
                'background', 
                'backgrounds/' . $user->id,
                $user->background_image
            );
            
            if ($imagePath) {
                $data['background_image'] = $imagePath;
            }
        }

        if ($request->hasFile('avatar')) {
            // Валидируем изображение
            $validationErrors = $this->imageService->validateImage($request->file('avatar'), 'avatar');
            if (!empty($validationErrors)) {
                return redirect()->back()->withErrors(['avatar' => $validationErrors[0]]);
            }
            
            // Обрабатываем и сохраняем изображение
            $imagePath = $this->imageService->processAndStore(
                $request->file('avatar'), 
                'avatar', 
                'avatars/' . $user->id,
                $user->avatar
            );
            
            if ($imagePath) {
                $data['avatar'] = $imagePath;
            }
        }

        $user->update($data);

        return redirect()->route('admin.profile', $user->id)->with('success', 'Профиль успешно обновлен!');
    }

    /**
     * Показ профиля с определенной вкладкой
     */
    public function profileTab(User $user = null, $tab = 'basic')
    {
        // Если параметр user не передан, используем текущего авторизованного пользователя
        if (!$user) {
            $user = auth()->user();
        }
        
        // Проверяем права доступа
        if (auth()->user()->id != $user->id) {
            abort(403, 'Доступ запрещен');
        }

        // Валидируем вкладку
        $validTabs = ['basic', 'social', 'security'];
        if (!in_array($tab, $validTabs)) {
            $tab = 'basic';
        }

        return view('admin.profile', compact('user', 'tab'));
    }

    /**
     * Обновление основной информации
     */
    public function updateBasicInfo(Request $request, User $user = null)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'bio' => 'nullable|string|max:190',
        ]);

        // Если параметр user не передан, используем текущего авторизованного пользователя
        if (!$user) {
            $user = auth()->user();
        }
        
        // Проверяем права доступа
        if (auth()->user()->id != $user->id) {
            abort(403, 'Доступ запрещен');
        }

        $user->update($request->only(['name', 'bio']));

        return redirect()->route('admin.profile.tab', [$user->id, 'basic'])
                        ->with('success', 'Основная информация успешно обновлена!');
    }

    /**
     * Обновление социальных сетей
     */
    public function updateSocialMedia(Request $request, User $user = null)
    {
        $request->validate([
            'telegram_url' => 'nullable|url|max:255',
            'whatsapp_url' => 'nullable|url|max:255',
            'vk_url' => 'nullable|url|max:255',
            'youtube_url' => 'nullable|url|max:255',
            'ok_url' => 'nullable|url|max:255',
        ]);

        // Если параметр user не передан, используем текущего авторизованного пользователя
        if (!$user) {
            $user = auth()->user();
        }
        
        // Проверяем права доступа
        if (auth()->user()->id != $user->id) {
            abort(403, 'Доступ запрещен');
        }

        $user->update($request->only([
            'telegram_url', 
            'whatsapp_url', 
            'vk_url', 
            'youtube_url', 
            'ok_url'
        ]));

        return redirect()->route('admin.profile.tab', [$user->id, 'social'])
                        ->with('success', 'Ссылки на социальные сети успешно обновлены!');
    }

    /**
     * Обновление настроек безопасности
     */
    public function updateSecurity(Request $request, User $user = null)
    {
        $rules = [];
        $data = [];

        // Если пользователь хочет изменить пароль
        if ($request->filled('current_password')) {
            $rules = [
                'current_password' => 'required',
                'password' => 'required|string|min:8|confirmed',
            ];

            $request->validate($rules);

            // Проверяем текущий пароль
            if (!Hash::check($request->current_password, auth()->user()->password)) {
                return redirect()->back()->withErrors(['current_password' => 'Неверный текущий пароль']);
            }

            $data['password'] = Hash::make($request->password);
        }

        // Если параметр user не передан, используем текущего авторизованного пользователя
        if (!$user) {
            $user = auth()->user();
        }
        
        // Проверяем права доступа
        if (auth()->user()->id != $user->id) {
            abort(403, 'Доступ запрещен');
        }

        if (!empty($data)) {
            $user->update($data);
            $message = 'Пароль успешно изменен!';
        } else {
            $message = 'Настройки безопасности обновлены!';
        }

        return redirect()->route('admin.profile.tab', [$user->id, 'security'])
                        ->with('success', $message);
    }

    /**
     * Управление галереей
     */
    public function gallery()
    {
        $user = auth()->user();
        $images = GalleryImage::where('user_id', $user->id)->orderBy('order_index')->get();
        return view('admin.gallery.index', compact('images'))
            ->with('currentUserId', $user->id);
    }

    public function galleryCreate()
    {
        $user = auth()->user();
        return view('admin.gallery.create', compact('user'))
            ->with('currentUserId', $user->id);
    }

    public function galleryStore(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:100',
            'alt_text' => 'nullable|string|max:150',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
            'order_index' => 'nullable|integer|min:0'
        ]);

        $user = auth()->user();
        
        try {
            // Валидируем изображение
            $validationErrors = $this->imageService->validateImage($request->file('image'), 'gallery');
            if (!empty($validationErrors)) {
                return redirect()->back()->withErrors(['image' => $validationErrors[0]])->withInput();
            }
            
            // Обрабатываем и сохраняем изображение
            $imagePath = $this->imageService->processAndStore(
                $request->file('image'), 
                'gallery', 
                'gallery/' . $user->id
            );
            
            if (!$imagePath) {
                return redirect()->back()->withErrors(['image' => 'Ошибка при обработке изображения'])->withInput();
            }

            $orderIndex = $request->order_index ?? (GalleryImage::where('user_id', $user->id)->max('order_index') + 1);

            GalleryImage::create([
                'user_id' => $user->id,
                'title' => $request->title,
                'alt_text' => $request->alt_text ?: $request->title,
                'image_path' => $imagePath,
                'order_index' => $orderIndex,
                'is_active' => true
            ]);

            $redirectUrl = route('user.show', $user->username) . '#gallery';
            return redirect($redirectUrl)->with('success', 'Изображение добавлено в галерею!');
        } catch (\Exception $e) {
            Log::error('Ошибка добавления изображения: ' . $e->getMessage());
            return back()->withErrors(['image' => 'Ошибка при загрузке изображения.'])->withInput();
        }
    }

    public function galleryUpdate(Request $request, $user, GalleryImage $image)
    {
        // Проверяем, что пользователь авторизован
        if (!auth()->check()) {
            abort(401, 'Необходима авторизация');
        }

        // Для админов разрешаем редактировать любые изображения
        // Если нужна строгая проверка владельца, раскомментируйте:
        // if ($image->user_id !== auth()->user()->id) {
        //     abort(403);
        // }

        $request->validate([
            'title' => 'nullable|string|max:100',
            'alt_text' => 'nullable|string|max:150',
            'order_index' => 'nullable|integer',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
        ]);

        try {
            // Если загружается новое изображение
            if ($request->hasFile('image')) {
                // Валидируем изображение
                $validationErrors = $this->imageService->validateImage($request->file('image'), 'gallery');
                if (!empty($validationErrors)) {
                    return redirect()->back()->withErrors(['image' => $validationErrors[0]])->withInput();
                }
                
                // Удаляем старое изображение
                if ($image->image_path) {
                    $this->imageService->deleteImage($image->image_path);
                }
                
                // Обрабатываем и сохраняем новое изображение
                $imagePath = $this->imageService->processAndStore(
                    $request->file('image'), 
                    'gallery', 
                    'gallery/' . $image->user_id
                );
                
                if (!$imagePath) {
                    return redirect()->back()->withErrors(['image' => 'Ошибка при обработке изображения'])->withInput();
                }
                
                $image->image_path = $imagePath;
            }

            // Обновляем остальные поля
            $image->update($request->only(['title', 'alt_text', 'order_index', 'is_active']));

            $redirectUrl = route('user.show', auth()->user()->username) . '#gallery';
            return redirect($redirectUrl)->with('success', 'Изображение обновлено!');
        } catch (\Exception $e) {
            Log::error('Ошибка обновления изображения: ' . $e->getMessage());
            return back()->withErrors(['image' => 'Ошибка при обновлении изображения.'])->withInput();
        }
    }

    public function galleryDestroy($user, GalleryImage $image)
    {
        // Логируем попытку удаления
        Log::info('Попытка удаления изображения', [
            'image_id' => $image->id,
            'image_user_id' => $image->user_id,
            'current_user_id' => auth()->user()->id,
            'is_authenticated' => auth()->check()
        ]);

        // Проверяем, что пользователь авторизован
        if (!auth()->check()) {
            Log::warning('Попытка удаления без авторизации');
            abort(401, 'Необходима авторизация');
        }

        // Для админов разрешаем удалять любые изображения
        // Если нужна строгая проверка владельца, раскомментируйте:
        // if ($image->user_id !== auth()->user()->id) {
        //     abort(403);
        // }

        // Удаляем файл через ImageProcessingService
        $this->imageService->deleteImage($image->image_path);
        
        $image->delete();

        Log::info('Изображение успешно удалено', ['image_id' => $image->id]);

        $redirectUrl = route('user.show', auth()->user()->username) . '#gallery';
        return redirect($redirectUrl)->with('success', 'Изображение удалено!');
    }

    public function galleryEdit($user, GalleryImage $image)
    {
        // Проверяем, что пользователь авторизован
        if (!auth()->check()) {
            abort(401, 'Необходима авторизация');
        }

        // Для админов разрешаем редактировать любые изображения
        // Если нужна строгая проверка владельца, раскомментируйте:
        // if ($image->user_id !== auth()->user()->id) {
        //     abort(403);
        // }

        $user = auth()->user();
        return view('admin.gallery.edit', compact('user', 'image'))
            ->with('currentUserId', $user->id);
    }

    /**
     * Управление услугами
     */
    public function services(User $user = null)
    {
        // Если параметр user не передан, используем текущего авторизованного пользователя
        if (!$user) {
            $user = auth()->user();
        }
        
        // Проверяем права доступа - пользователь может видеть только свои услуги
        if (auth()->user()->id != $user->id) {
            abort(403, 'Доступ запрещен');
        }

        // Временно используем прямые запросы к базе вместо отношений
        $services = Service::where('user_id', $user->id)->orderBy('order_index')->paginate(10);
        return view('admin.services.index', compact('services', 'user'));
    }

    public function servicesCreate(User $user = null)
    {
        // Если параметр user не передан, используем текущего авторизованного пользователя
        if (!$user) {
            $user = auth()->user();
        }
        
        // Проверяем права доступа
        if (auth()->user()->id != $user->id) {
            abort(403, 'Доступ запрещен');
        }

        return view('admin.services.create', compact('user'));
    }

    public function servicesStore(Request $request, User $user = null)
    {
        // Если параметр user не передан, используем текущего авторизованного пользователя
        if (!$user) {
            $user = auth()->user();
        }
        
        // Проверяем права доступа
        if (auth()->user()->id != $user->id) {
            abort(403, 'Доступ запрещен');
        }

        $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:500',
            'image' => 'nullable|file',
            'price' => 'nullable|numeric|min:0',
            'price_type' => 'required|in:fixed,hourly,project',
            'order_index' => 'nullable|integer',
            'button_text' => 'nullable|string|max:50',
            'button_link' => 'nullable|string|max:255',
        ]);

        $data = $request->only(['title', 'description', 'price', 'price_type', 'button_text', 'button_link']);
        $data['user_id'] = $user->id;
        
        // Обрабатываем order_index отдельно
        if ($request->filled('order_index') && is_numeric($request->input('order_index'))) {
            $data['order_index'] = $request->input('order_index');
        } else {
            // Используем прямой запрос к базе вместо отношения
            $data['order_index'] = Service::where('user_id', $user->id)->count();
        }

        if ($request->hasFile('image')) {
            // Валидируем изображение
            $validationErrors = $this->imageService->validateImage($request->file('image'), 'service');
            if (!empty($validationErrors)) {
                return redirect()->back()->withErrors(['image' => $validationErrors[0]]);
            }
            
            // Обрабатываем и сохраняем изображение
            $imagePath = $this->imageService->processAndStore(
                $request->file('image'), 
                'service', 
                'services/' . $user->id
            );
            
            if ($imagePath) {
                $data['image_path'] = $imagePath;
            }
        }

        Service::create($data);

        $redirectUrl = route('user.show', $user->username) . '#services';
        return redirect($redirectUrl)->with('success', 'Услуга добавлена!');
    }

    public function servicesEdit(User $user, Service $service)
    {
        // Проверяем права доступа - пользователь может редактировать только свои услуги
        if (auth()->user()->id != $user->id) {
            abort(403, 'Доступ запрещен');
        }

        // Проверяем, что пользователь авторизован
        if (!auth()->check()) {
            abort(401, 'Необходима авторизация');
        }

        // Для админов разрешаем редактировать любые услуги
        // Если нужна строгая проверка владельца, раскомментируйте:
        // if ($service->user_id !== auth()->user()->id) {
        //     abort(403);
        // }

        return view('admin.services.edit', compact('service', 'user'));
    }

    public function servicesUpdate(Request $request, User $user, Service $service)
    {
        // Проверяем права доступа - пользователь может редактировать только свои услуги
        if (auth()->user()->id != $user->id) {
            abort(403, 'Доступ запрещен');
        }

        // Проверяем, что пользователь авторизован
        if (!auth()->check()) {
            abort(401, 'Необходима авторизация');
        }

        // Для админов разрешаем редактировать любые услуги
        // Если нужна строгая проверка владельца, раскомментируйте:
        // if ($service->user_id !== auth()->user()->id) {
        //     abort(403);
        // }

        $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:500',
            'image' => 'nullable|file|max:10240',
            'price' => 'nullable|numeric|min:0',
            'price_type' => 'required|in:fixed,hourly,project',
            'order_index' => 'nullable|integer',
            'is_active' => 'boolean',
            'button_text' => 'nullable|string|max:50',
            'button_link' => 'nullable|string|max:255',
        ]);

        $data = $request->only(['title', 'description', 'price', 'price_type', 'is_active', 'button_text', 'button_link']);
        
        // Добавляем order_index только если он не null
        if ($request->filled('order_index')) {
            $data['order_index'] = $request->input('order_index');
        }

        if ($request->hasFile('image')) {
            // Валидируем изображение
            $validationErrors = $this->imageService->validateImage($request->file('image'), 'service');
            if (!empty($validationErrors)) {
                return redirect()->back()->withErrors(['image' => $validationErrors[0]]);
            }
            
            // Обрабатываем и сохраняем изображение
            $imagePath = $this->imageService->processAndStore(
                $request->file('image'), 
                'service', 
                'services/' . $user->id,
                $service->image_path
            );
            
            if ($imagePath) {
                $data['image_path'] = $imagePath;
            }
        }

        $service->update($data);

        $redirectUrl = route('user.show', $user->username) . '#services';
        return redirect($redirectUrl)->with('success', 'Услуга обновлена!');
    }

    public function servicesDestroy(User $user, Service $service)
    {
        // Проверяем права доступа - пользователь может удалять только свои услуги
        if (auth()->user()->id != $user->id) {
            abort(403, 'Доступ запрещен');
        }

        // Проверяем, что пользователь авторизован
        if (!auth()->check()) {
            abort(401, 'Необходима авторизация');
        }

        if ($service->image_path) {
            Storage::disk('public')->delete($service->image_path);
        }

        $service->delete();

        $redirectUrl = route('user.show', $user->username) . '#services';
        return redirect($redirectUrl)->with('success', 'Услуга удалена!');
    }

    /**
     * Управление статьями
     */
    public function articles(User $user = null)
    {
        // Если параметр user не передан, используем текущего авторизованного пользователя
        if (!$user) {
            $user = auth()->user();
        }
        
        // Проверяем права доступа - пользователь может видеть только свои статьи
        if (auth()->user()->id != $user->id) {
            abort(403, 'Доступ запрещен');
        }

        $articles = $user->articles()->latest()->paginate(10);
        $currentUserId = $user->id;
        
        return view('admin.articles.index', compact('articles', 'currentUserId'));
    }

    public function articlesCreate(User $user = null)
    {
        // Если параметр user не передан, используем текущего авторизованного пользователя
        if (!$user) {
            $user = auth()->user();
        }
        
        // Проверяем права доступа
        if (auth()->user()->id != $user->id) {
            abort(403, 'Доступ запрещен');
        }

        $currentUserId = $user->id;
        return view('admin.articles.create', compact('user', 'currentUserId'));
    }

    public function articlesStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:150',
            'excerpt' => 'required|string|max:300',
            'content' => 'required|string',
            'image' => 'nullable|file|mimes:jpeg,jpg,png,gif,webp|max:10240',
            'read_time' => 'nullable|integer|min:1',
            'order_index' => 'nullable|integer',
            'is_published' => 'nullable|in:0,1',
        ]);

        /** @var User $user */
        $user = auth()->user();
        $data = $request->only(['title', 'excerpt', 'content', 'read_time', 'order_index', 'is_published']);
        $data['user_id'] = $user->id;
        $data['slug'] = Str::slug($request->title);
        
        // Приводим is_published к boolean
        $data['is_published'] = $request->input('is_published') == '1';
        
        // Устанавливаем order_index
        if ($request->has('order_index') && $request->input('order_index') !== null) {
            $data['order_index'] = $request->input('order_index');
        } else {
            $data['order_index'] = Article::where('user_id', $user->id)->count();
        }

        if ($request->hasFile('image')) {
            // Валидируем изображение
            $validationErrors = $this->imageService->validateImage($request->file('image'), 'article');
            if (!empty($validationErrors)) {
                return redirect()->back()->withErrors(['image' => $validationErrors[0]]);
            }
            
            // Обрабатываем и сохраняем изображение
            $imagePath = $this->imageService->processAndStore(
                $request->file('image'), 
                'article', 
                'articles/' . $user->id
            );
            
            if ($imagePath) {
                $data['image_path'] = $imagePath;
            }
        }

        Article::create($data);

        $redirectUrl = route('user.show', $user->username) . '#articles';
        return redirect($redirectUrl)->with('success', 'Статья добавлена!');
    }

    public function articlesEdit($user, Article $article)
    {
        // Проверяем права доступа
        if ($article->user_id !== auth()->user()->id) {
            abort(403, 'Доступ запрещен');
        }

        $currentUserId = auth()->user()->id;
        $user = auth()->user(); // Добавляем переменную user для использования в blade шаблоне
        return view('admin.articles.edit', compact('article', 'currentUserId', 'user'));
    }

    public function articlesUpdate(Request $request, $user, Article $article)
    {
        // Проверяем права доступа
        if ($article->user_id !== auth()->user()->id) {
            abort(403, 'Доступ запрещен');
        }

        $request->validate([
            'title' => 'required|string|max:150',
            'excerpt' => 'required|string|max:300',
            'content' => 'required|string',
            'image' => 'nullable|file|mimes:jpeg,jpg,png,gif,webp|max:10240',
            'read_time' => 'nullable|integer|min:1',
            'order_index' => 'nullable|integer',
            'is_published' => 'nullable|in:0,1',
        ]);

        $data = $request->only(['title', 'excerpt', 'content', 'read_time', 'order_index', 'is_published']);
        
        // Приводим is_published к boolean
        $data['is_published'] = $request->input('is_published') == '1';
        
        // Обновляем slug если изменился заголовок
        if ($request->title !== $article->title) {
            $data['slug'] = Str::slug($request->title);
        }

        if ($request->hasFile('image')) {
            // Валидируем изображение
            $validationErrors = $this->imageService->validateImage($request->file('image'), 'article');
            if (!empty($validationErrors)) {
                return redirect()->back()->withErrors(['image' => $validationErrors[0]]);
            }
            
            // Обрабатываем и сохраняем изображение
            $imagePath = $this->imageService->processAndStore(
                $request->file('image'), 
                'article', 
                'articles/' . auth()->user()->id,
                $article->image_path
            );
            
            if ($imagePath) {
                $data['image_path'] = $imagePath;
            }
        }

        $article->update($data);

        // Получаем объект пользователя для редиректа
        $userObj = auth()->user();
        $redirectUrl = route('user.show', $userObj->username) . '#articles';
        return redirect($redirectUrl)->with('success', 'Статья обновлена!');
    }

    public function articlesDestroy($user, Article $article)
    {
        // if ($article->user_id !== auth()->user()->id) {
        //     abort(403);
        // }

        if ($article->image_path) {
            $this->imageService->deleteImage($article->image_path);
        }

        $article->delete();

        $redirectUrl = route('user.show', auth()->user()->username) . '#articles';
        return redirect($redirectUrl)->with('success', 'Статья удалена!');
    }

    /**
     * Управление баннерами
     */
    public function banners()
    {
        $user = auth()->user();
        $banners = $user->banners()->ordered()->paginate(10);
        $currentUserId = $user->id;
        return view('admin.banners.index', compact('banners', 'currentUserId'));
    }

    public function bannersCreate(User $user = null)
    {
        // Если параметр user не передан, используем текущего авторизованного пользователя
        if (!$user) {
            $user = auth()->user();
        }
        
        // Проверяем права доступа
        if (auth()->user()->id != $user->id) {
            abort(403, 'Доступ запрещен');
        }

        return view('admin.banners.create', compact('user'));
    }

    public function bannersStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'nullable|string|max:300',
            'image' => 'nullable|file|max:10240',
            'link_url' => 'nullable|url|max:255',
            'link_text' => 'nullable|string|max:50',
            'order_index' => 'nullable|integer',
        ]);

        $user = auth()->user();
        $data = $request->only(['title', 'description', 'link_url', 'link_text', 'order_index']);
        $data['user_id'] = $user->id;
        $data['is_active'] = true; // Все баннеры автоматически активны
        $data['order_index'] = $data['order_index'] ?: $user->banners()->count();

        if ($request->hasFile('image')) {
            // Валидируем изображение
            $validationErrors = $this->imageService->validateImage($request->file('image'), 'banner');
            if (!empty($validationErrors)) {
                return redirect()->back()->withErrors(['image' => $validationErrors[0]]);
            }
            
            // Обрабатываем и сохраняем изображение
            $imagePath = $this->imageService->processAndStore(
                $request->file('image'), 
                'banner', 
                'banners/' . $user->id
            );
            
            if ($imagePath) {
                $data['image_path'] = $imagePath;
            }
        }

        Banner::create($data);

        $redirectUrl = route('user.show', auth()->user()->username) . '#banners';
        return redirect($redirectUrl)->with('success', 'Баннер добавлен!');
    }

    public function bannersEdit($user, Banner $banner)
    {
        // if ($banner->user_id !== auth()->user()->id) {
        //     abort(403);
        // }

        return view('admin.banners.edit', compact('banner'));
    }

    public function bannersUpdate(Request $request, $user, Banner $banner)
    {
        // if ($banner->user_id !== auth()->user()->id) {
        //     abort(403);
        // }

        $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'nullable|string|max:300',
            'image' => 'nullable|file|max:10240',
            'link_url' => 'nullable|url|max:255',
            'link_text' => 'nullable|string|max:50',
            'order_index' => 'nullable|integer',
        ]);

        $data = $request->only(['title', 'description', 'link_url', 'link_text', 'order_index']);
        $data['is_active'] = true; // Принудительно устанавливаем активность

        if ($request->hasFile('image')) {
            // Валидируем изображение
            $validationErrors = $this->imageService->validateImage($request->file('image'), 'banner');
            if (!empty($validationErrors)) {
                return redirect()->back()->withErrors(['image' => $validationErrors[0]]);
            }
            
            // Обрабатываем и сохраняем изображение
            $imagePath = $this->imageService->processAndStore(
                $request->file('image'), 
                'banner', 
                'banners/' . auth()->user()->id,
                $banner->image_path
            );
            
            if ($imagePath) {
                $data['image_path'] = $imagePath;
            }
        }

        $banner->update($data);

        $redirectUrl = route('user.show', auth()->user()->username) . '#banners';
        return redirect($redirectUrl)->with('success', 'Баннер обновлен!');
    }

    public function bannersDestroy($user, Banner $banner)
    {
        // if ($banner->user_id !== auth()->user()->id) {
        //     abort(403);
        // }

        if ($banner->image_path) {
            $this->imageService->deleteImage($banner->image_path);
        }

        $banner->delete();

        $redirectUrl = route('user.show', auth()->user()->username) . '#banners';
        return redirect($redirectUrl)->with('success', 'Баннер удален!');
    }

    /**
     * Управление пользовательскими социальными ссылками
     */
    
    /**
     * Добавить новую пользовательскую социальную ссылку
     */
    public function socialLinksStore(Request $request, User $user = null)
    {
        if (!$user) {
            $user = auth()->user();
        }
        
        // Проверяем права доступа
        if (auth()->user()->id != $user->id) {
            abort(403, 'Доступ запрещен');
        }
        
        // Проверяем лимит дополнительных социальных ссылок (максимум 5)
        $existingSocialLinksCount = $user->socialLinks()->count();
        if ($existingSocialLinksCount >= 5) {
            return redirect()->route('admin.profile.tab', [$user->id, 'social'])
                            ->with('error', 'Достигнут максимальный лимит дополнительных социальных ссылок (5)');
        }

        $request->validate([
            'service_name' => 'required|string|max:255',
            'url' => 'required|url|max:255',
            'icon_class' => 'required|string|max:50',
        ]);

        // Определяем порядок для новой ссылки
        $maxOrder = $user->socialLinks()->max('order') ?? 0;

        $user->socialLinks()->create([
            'service_name' => $request->service_name,
            'url' => $request->url,
            'icon_class' => $request->icon_class,
            'order' => $maxOrder + 1,
        ]);

        return redirect()->route('admin.profile.tab', [$user->id, 'social'])
                        ->with('success', 'Социальная ссылка добавлена!');
    }

    /**
     * Обновить пользовательскую социальную ссылку
     */
    public function socialLinksUpdate(Request $request, User $user, UserSocialLink $socialLink)
    {
        // Проверяем права доступа
        if (auth()->user()->id != $user->id || $socialLink->user_id != $user->id) {
            abort(403, 'Доступ запрещен');
        }

        $request->validate([
            'service_name' => 'required|string|max:255',
            'url' => 'required|url|max:255',
            'icon_class' => 'required|string|max:50',
        ]);

        $socialLink->update([
            'service_name' => $request->service_name,
            'url' => $request->url,
            'icon_class' => $request->icon_class,
        ]);

        return redirect()->route('admin.profile.tab', [$user->id, 'social'])
                        ->with('success', 'Социальная ссылка обновлена!');
    }

    /**
     * Удалить пользовательскую социальную ссылку
     */
    public function socialLinksDestroy(User $user, UserSocialLink $socialLink)
    {
        // Проверяем права доступа
        if (auth()->user()->id != $user->id || $socialLink->user_id != $user->id) {
            abort(403, 'Доступ запрещен');
        }

        $socialLink->delete();

        return redirect()->route('admin.profile.tab', [$user->id, 'social'])
                        ->with('success', 'Социальная ссылка удалена!');
    }

    /**
     * Обновить порядок социальных ссылок
     */
    public function socialLinksUpdateOrder(Request $request, User $user)
    {
        // Проверяем права доступа
        if (auth()->user()->id != $user->id) {
            abort(403, 'Доступ запрещен');
        }

        $request->validate([
            'links' => 'required|array',
            'links.*.id' => 'required|integer|exists:user_social_links,id',
            'links.*.order' => 'required|integer|min:0',
        ]);

        foreach ($request->links as $linkData) {
            $socialLink = $user->socialLinks()->find($linkData['id']);
            if ($socialLink) {
                $socialLink->update(['order' => $linkData['order']]);
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * Получить настройки секций пользователя
     */
    public function getSectionSettings(User $user = null)
    {
        if (!$user) {
            $user = auth()->user();
        }

        // Проверяем права доступа
        if (auth()->user()->id !== $user->id && !auth()->user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Доступ запрещен'], 403);
        }

        // Получаем существующие настройки
        $existingSettings = $user->sectionSettings()->get()->keyBy('section_key');

        // Определяем все доступные секции в фиксированном порядке
        $fixedSections = [
            'hero' => [
                'name' => 'Главный экран',
                'titles' => [
                    'Пусто',
                    $user->name,
                    'Добро пожаловать!',
                    'Привет! Я ' . $user->name,
                    'Обо мне',
                    'Моя страница'
                ],
                'subtitles' => [
                    'Пусто',
                    'Добро пожаловать на мою страницу',
                    'Узнайте больше обо мне',
                    'Профессионал своего дела',
                    'Рад вас видеть',
                    'Делаю мир лучше'
                ],
                'order' => 1,
            ],
            'services' => [
                'name' => 'Услуги',
                'titles' => [
                    'Пусто',
                    'Мои услуги',
                    'Что я предлагаю',
                    'Мои возможности',
                    'Чем могу помочь',
                    'Услуги и Консультация'
                ],
                'subtitles' => [
                    'Пусто',
                    'Что я предлагаю',
                    'Качественные услуги для вас',
                    'Профессиональный подход',
                    'Решения для ваших задач',
                    'Индивидуальный подход'
                ],
                'order' => 2,
            ],
            'gallery' => [
                'name' => 'Портфолио',
                'titles' => [
                    'Пусто',
                    'Портфолио',
                    'Мои работы',
                    'Галерея проектов',
                    'Примеры работ',
                    'Что я делаю'
                ],
                'subtitles' => [
                    'Пусто',
                    'Мои работы и проекты',
                    'Портфолио выполненных работ',
                    'Примеры моих проектов',
                    'Результаты моего труда',
                    'Лучшие работы'
                ],
                'order' => 3,
            ],
            'banners' => [
                'name' => 'Банер',
                'titles' => [
                    'Пусто',
                    'Важная информация',
                    'Актуальные предложения',
                    'Специальные предложения',
                    'Новости и акции',
                    'Обратите внимание'
                ],
                'subtitles' => [
                    'Пусто',
                    'Актуальные предложения',
                    'Не пропустите важное',
                    'Специально для вас',
                    'Лучшие предложения',
                    'Ограниченное предложение'
                ],
                'order' => 4,
            ],
            'articles' => [
                'name' => 'Статьи',
                'titles' => [
                    'Пусто',
                    'Статьи',
                    'Мой блог',
                    'Полезные материалы',
                    'Последние публикации',
                    'Блог'
                ],
                'subtitles' => [
                    'Пусто',
                    'Последние публикации',
                    'Полезные материалы и советы',
                    'Делюсь опытом',
                    'Интересные статьи',
                    'Читайте и применяйте'
                ],
                'order' => 5,
            ],
        ];

        $sections = [];
        foreach ($fixedSections as $key => $sectionInfo) {
            if (isset($existingSettings[$key])) {
                $setting = $existingSettings[$key];
                
                // Если title пустой или null, то выбираем "Пусто", иначе сохраненное значение
                $selectedTitle = (!empty($setting->title)) ? $setting->title : 'Пусто';
                $selectedSubtitle = (!empty($setting->subtitle)) ? $setting->subtitle : 'Пусто';
                
                $sections[] = [
                    'section_key' => $key,
                    'section_name' => $sectionInfo['name'],
                    'title' => $selectedTitle,
                    'subtitle' => $selectedSubtitle,
                    'available_titles' => $sectionInfo['titles'],
                    'available_subtitles' => $sectionInfo['subtitles'],
                    'order' => $sectionInfo['order'],
                ];
            } else {
                // Для новых секций устанавливаем пустые значения по умолчанию
                $sections[] = [
                    'section_key' => $key,
                    'section_name' => $sectionInfo['name'],
                    'title' => 'Пусто', // Отображаем как "Пусто" в админке
                    'subtitle' => 'Пусто', // Отображаем как "Пусто" в админке
                    'available_titles' => $sectionInfo['titles'],
                    'available_subtitles' => $sectionInfo['subtitles'],
                    'order' => $sectionInfo['order'],
                ];
            }
        }

        // Сортируем по фиксированному порядку
        usort($sections, function ($a, $b) {
            return $a['order'] <=> $b['order'];
        });

        return response()->json([
            'success' => true,
            'sections' => $sections
        ]);
    }

    /**
     * Обновить настройки секций пользователя
     */
    public function updateSectionSettings(Request $request, User $user = null)
    {
        if (!$user) {
            $user = auth()->user();
        }

        // Проверяем права доступа
        if (auth()->user()->id !== $user->id && !auth()->user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Доступ запрещен'], 403);
        }

        $request->validate([
            'sections' => 'required|array',
            'sections.*.section_key' => 'required|string|in:hero,services,gallery,articles,banners',
            'sections.*.title' => 'nullable|string|max:100',
            'sections.*.subtitle' => 'nullable|string|max:200',
        ]);

        try {
            // Фиксированный порядок секций
            $fixedOrder = [
                'hero' => 1,
                'services' => 2,
                'gallery' => 3,
                'banners' => 4,
                'articles' => 5,
            ];

            foreach ($request->sections as $sectionData) {
                // Пропускаем обновление настроек для главного экрана
                if ($sectionData['section_key'] === 'hero') {
                    continue;
                }
                
                $order = $fixedOrder[$sectionData['section_key']] ?? 999;
                
                // Преобразуем пустые строки в null для корректного сохранения
                $title = !empty(trim($sectionData['title'])) ? $sectionData['title'] : null;
                $subtitle = !empty(trim($sectionData['subtitle'])) ? $sectionData['subtitle'] : null;
                
                $user->sectionSettings()->updateOrCreate(
                    ['section_key' => $sectionData['section_key']],
                    [
                        'title' => $title,
                        'subtitle' => $subtitle,
                        'is_visible' => true, // Все секции всегда видимы
                        'order' => $order,
                    ]
                );
            }

            return response()->json(['success' => true, 'message' => 'Настройки секций успешно сохранены']);
        } catch (\Exception $e) {
            Log::error('Ошибка сохранения настроек секций: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'sections' => $request->input('sections', []),
                'exception' => $e->getTraceAsString()
            ]);
            return response()->json(['success' => false, 'message' => 'Произошла ошибка при сохранении: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Обновление аватара пользователя с обрезанным изображением
     */
    public function updateAvatar(Request $request, User $user)
    {
        try {
            // Проверяем, что пользователь может редактировать этот профиль
            if (auth()->id() !== $user->id) {
                return response()->json(['success' => false, 'message' => 'Нет прав доступа'], 403);
            }

            $request->validate([
                'cropped_image' => 'required|string',
            ]);

            // Получаем base64 данные изображения
            $imageData = $request->input('cropped_image');
            
            // Убираем префикс data:image/jpeg;base64, если он есть
            if (strpos($imageData, ',') !== false) {
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
            }

            // Декодируем base64
            $decodedImage = base64_decode($imageData);
            
            if ($decodedImage === false) {
                throw new \Exception('Ошибка декодирования изображения');
            }

            // Создаем временный файл
            $tempPath = tempnam(sys_get_temp_dir(), 'avatar_');
            file_put_contents($tempPath, $decodedImage);

            // Генерируем уникальное имя файла
            $fileName = 'avatar_' . $user->id . '_' . time() . '.jpg';
            $storagePath = 'avatars/' . $fileName;

            // Сохраняем в storage
            $fullPath = storage_path('app/public/' . $storagePath);
            
            // Создаем директорию если её нет
            $directory = dirname($fullPath);
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            // Копируем файл
            copy($tempPath, $fullPath);

            // Удаляем временный файл
            unlink($tempPath);

            // Удаляем старый аватар
            if ($user->avatar) {
                $oldAvatarPath = storage_path('app/public/' . $user->avatar);
                if (file_exists($oldAvatarPath)) {
                    unlink($oldAvatarPath);
                }
            }

            // Обновляем путь к аватару в базе данных
            $user->update(['avatar' => $storagePath]);

            Log::info('Аватар успешно обновлен', [
                'user_id' => $user->id,
                'new_avatar' => $storagePath
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Аватар успешно обновлен!',
                'image_url' => asset('storage/' . $storagePath)
            ]);

        } catch (\Exception $e) {
            Log::error('Ошибка обновления аватара: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'exception' => $e->getTraceAsString()
            ]);
            return response()->json(['success' => false, 'message' => 'Ошибка при сохранении аватара: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Обновление фонового изображения пользователя с обрезанным изображением
     */
    public function updateBackground(Request $request, User $user)
    {
        try {
            // Проверяем, что пользователь может редактировать этот профиль
            if (auth()->id() !== $user->id) {
                return response()->json(['success' => false, 'message' => 'Нет прав доступа'], 403);
            }

            $request->validate([
                'cropped_image' => 'required|string',
            ]);

            // Получаем base64 данные изображения
            $imageData = $request->input('cropped_image');
            
            // Убираем префикс data:image/jpeg;base64, если он есть
            if (strpos($imageData, ',') !== false) {
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
            }

            // Декодируем base64
            $decodedImage = base64_decode($imageData);
            
            if ($decodedImage === false) {
                throw new \Exception('Ошибка декодирования изображения');
            }

            // Создаем временный файл
            $tempPath = tempnam(sys_get_temp_dir(), 'background_');
            file_put_contents($tempPath, $decodedImage);

            // Генерируем уникальное имя файла
            $fileName = 'background_' . $user->id . '_' . time() . '.jpg';
            $storagePath = 'backgrounds/' . $fileName;

            // Сохраняем в storage
            $fullPath = storage_path('app/public/' . $storagePath);
            
            // Создаем директорию если её нет
            $directory = dirname($fullPath);
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            // Копируем файл
            copy($tempPath, $fullPath);

            // Удаляем временный файл
            unlink($tempPath);

            // Удаляем старое фоновое изображение
            if ($user->background_image) {
                $oldBackgroundPath = storage_path('app/public/' . $user->background_image);
                if (file_exists($oldBackgroundPath)) {
                    unlink($oldBackgroundPath);
                }
            }

            // Обновляем путь к фоновому изображению в базе данных
            $user->update(['background_image' => $storagePath]);

            Log::info('Фоновое изображение успешно обновлено', [
                'user_id' => $user->id,
                'new_background' => $storagePath
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Фоновое изображение успешно обновлено!',
                'image_url' => asset('storage/' . $storagePath)
            ]);

        } catch (\Exception $e) {
            Log::error('Ошибка обновления фонового изображения: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'exception' => $e->getTraceAsString()
            ]);
            return response()->json(['success' => false, 'message' => 'Ошибка при сохранении фонового изображения: ' . $e->getMessage()], 500);
        }
    }
}
