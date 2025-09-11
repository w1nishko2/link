<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\GalleryImage;
use App\Models\Service;
use App\Models\Article;
use App\Models\Banner;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Services\ImageProcessingService;

class AdminController extends Controller
{
    protected $imageService;

    public function __construct(ImageProcessingService $imageService)
    {
        $this->middleware('auth');
        $this->imageService = $imageService;
    }

    /**
     * Главная страница админки
        $this->imageService = $imageService;



     * Показать главную страницу админки с подробной статистикой
     */
    public function index($user = null)
    {
        // Если передан ID пользователя, используем его, иначе текущего авторизованного
        if ($user) {
            $targetUser = User::findOrFail($user);
            // Проверяем права доступа - пользователь может видеть только свою админку
            // Сравниваем числовой ID пользователя (из URL) с ID текущего авторизованного пользователя
            if (auth()->user()->id != (int)$user) {
                abort(403, 'Доступ запрещен');
            }
        } else {
            $targetUser = auth()->user();
        }
        
        // Основная статистика (временно используем прямые запросы к базе)
        $stats = [
            'total_gallery_images' => GalleryImage::where('user_id', $targetUser->id)->count(),
            'active_gallery_images' => GalleryImage::where('user_id', $targetUser->id)->where('is_active', true)->count(),
            'total_services' => Service::where('user_id', $targetUser->id)->count(),
            'active_services' => Service::where('user_id', $targetUser->id)->where('is_active', true)->count(),
            'total_articles' => Article::where('user_id', $targetUser->id)->count(),
            'published_articles' => Article::where('user_id', $targetUser->id)->where('is_published', true)->count(),
            'total_banners' => Banner::where('user_id', $targetUser->id)->count(),
            'active_banners' => Banner::where('user_id', $targetUser->id)->where('is_active', true)->count(),
        ];

        // Последние добавленные элементы
        $recent = [
            'recent_articles' => $targetUser->articles()->latest()->limit(5)->get(),
            'recent_services' => $targetUser->services()->latest()->limit(5)->get(),
            'recent_gallery' => $targetUser->galleryImages()->latest()->limit(5)->get(),
            'recent_banners' => $targetUser->banners()->latest()->limit(5)->get(),
        ];

        // Статистика по датам (последние 30 дней)
        $dateStats = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dateStats[] = [
                'date' => $date,
                'articles' => $targetUser->articles()->whereDate('created_at', $date)->count(),
                'services' => $targetUser->services()->whereDate('created_at', $date)->count(),
                'gallery' => $targetUser->galleryImages()->whereDate('created_at', $date)->count(),
            ];
        }

        // Популярность контента (можно расширить в будущем для отслеживания просмотров)
        $contentPerformance = [
            'most_recent_article' => $targetUser->articles()->published()->latest()->first(),
            'total_content_items' => $stats['total_articles'] + $stats['total_services'] + $stats['total_gallery_images'],
            'profile_completion' => $this->calculateProfileCompletion($targetUser),
        ];

        return view('admin.index', compact('stats', 'recent', 'dateStats', 'contentPerformance'))->with('user', $targetUser);
    }

    /**
     * Вычисляет процент заполненности профиля
     */
    private function calculateProfileCompletion($user)
    {
        $fields = [
            'name' => !empty($user->name),
            'bio' => !empty($user->bio),
            'avatar' => !empty($user->avatar),
            'background_image' => !empty($user->background_image),
            'telegram_url' => !empty($user->telegram_url),
            'whatsapp_url' => !empty($user->whatsapp_url),
            'vk_url' => !empty($user->vk_url),
            'youtube_url' => !empty($user->youtube_url),
            'has_services' => $user->services()->count() > 0,
            'has_articles' => $user->articles()->count() > 0,
            'has_gallery' => $user->galleryImages()->count() > 0,
        ];

        $completed = count(array_filter($fields));
        $total = count($fields);

        return round(($completed / $total) * 100);
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

        return view('admin.profile', compact('user'));
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
     * Управление галереей
     */
    public function gallery()
    {
        $user = auth()->user();
        $images = $user->galleryImages()->ordered()->paginate(12);
        return view('admin.gallery.index', compact('images'));
    }

    public function galleryStore(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:100',
            'alt_text' => 'nullable|string|max:150',
            'image' => 'required|file',
        ]);

        $user = auth()->user();
        
        // Валидируем изображение
        $validationErrors = $this->imageService->validateImage($request->file('image'), 'gallery');
        if (!empty($validationErrors)) {
            return redirect()->back()->withErrors(['image' => $validationErrors[0]]);
        }
        
        // Обрабатываем и сохраняем изображение
        $imagePath = $this->imageService->processAndStore(
            $request->file('image'), 
            'gallery', 
            'gallery/' . $user->id
        );
        
        if (!$imagePath) {
            return redirect()->back()->withErrors(['image' => 'Ошибка при обработке изображения']);
        }

        $user->galleryImages()->create([
            'title' => $request->title,
            'alt_text' => $request->alt_text ?: $request->title,
            'image_path' => $imagePath,
            'order_index' => $request->order_index ?: $user->galleryImages()->count(),
        ]);

        return redirect()->route('admin.gallery', $user->id)->with('success', 'Изображение добавлено в галерею!');
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
        ]);

        $image->update($request->only(['title', 'alt_text', 'order_index', 'is_active']));

        return redirect()->route('admin.gallery', auth()->user()->id)->with('success', 'Изображение обновлено!');
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

        return redirect()->route('admin.gallery', auth()->user()->id)->with('success', 'Изображение удалено!');
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
        ]);

        $data = $request->only(['title', 'description', 'price', 'price_type', 'order_index']);
        $data['user_id'] = $user->id;
        // Используем прямой запрос к базе вместо отношения
        $data['order_index'] = $data['order_index'] ?: Service::where('user_id', $user->id)->count();

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

        return redirect()->route('admin.services', $user->id)->with('success', 'Услуга добавлена!');
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
        ]);

        $data = $request->only(['title', 'description', 'price', 'price_type', 'order_index', 'is_active']);

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

        return redirect()->route('admin.services', $user->id)->with('success', 'Услуга обновлена!');
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

        return redirect()->route('admin.services', $user->id)->with('success', 'Услуга удалена!');
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

        return view('admin.articles.create', compact('user'));
    }

    public function articlesStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:150',
            'excerpt' => 'required|string|max:300',
            'content' => 'required|string',
            'image' => 'nullable|file|max:10240',
            'read_time' => 'nullable|integer|min:1',
            'order_index' => 'nullable|integer',
            'is_published' => 'boolean',
        ]);

        $user = auth()->user();
        $data = $request->only(['title', 'excerpt', 'content', 'read_time', 'order_index', 'is_published']);
        $data['user_id'] = $user->id;
        $data['slug'] = Str::slug($request->title);
        $data['order_index'] = $data['order_index'] ?: $user->articles()->count();

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

        return redirect()->route('admin.articles', auth()->user()->id)->with('success', 'Статья добавлена!');
    }

    public function articlesEdit($user, Article $article)
    {
        // if ($article->user_id !== auth()->user()->id) {
        //     abort(403);
        // }

        return view('admin.articles.edit', compact('article'));
    }

    public function articlesUpdate(Request $request, $user, Article $article)
    {
        // if ($article->user_id !== auth()->user()->id) {
        //     abort(403);
        // }

        $request->validate([
            'title' => 'required|string|max:150',
            'excerpt' => 'required|string|max:300',
            'content' => 'required|string',
            'image' => 'nullable|file|max:10240',
            'read_time' => 'nullable|integer|min:1',
            'order_index' => 'nullable|integer',
            'is_published' => 'boolean',
        ]);

        $data = $request->only(['title', 'excerpt', 'content', 'read_time', 'order_index', 'is_published']);

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

        return redirect()->route('admin.articles', auth()->user()->id)->with('success', 'Статья обновлена!');
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

        return redirect()->route('admin.articles', auth()->user()->id)->with('success', 'Статья удалена!');
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
        $data['is_active'] = $request->has('is_active') ? true : false;
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

        return redirect()->route('admin.banners', auth()->user()->id)->with('success', 'Баннер добавлен!');
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
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['title', 'description', 'link_url', 'link_text', 'order_index', 'is_active']);

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

        return redirect()->route('admin.banners', auth()->user()->id)->with('success', 'Баннер обновлен!');
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

        return redirect()->route('admin.banners', auth()->user()->id)->with('success', 'Баннер удален!');
    }
}
