<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\GalleryImage;
use App\Models\Service;
use App\Models\Article;
use App\Models\Banner;
use App\Models\UserSectionSettings;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->only(['index', 'updateProfile']);
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
        // Находим пользователя по username
        $pageUser = User::where('username', $username)->firstOrFail();
        
        // Получаем текущего пользователя (может быть null для неавторизованных)
        $currentUser = auth()->user();
        
        // Получаем настройки секций пользователя
        $sectionSettings = $pageUser->sectionSettings()->visible()->ordered()->get()->keyBy('section_key');
        
        // Если у пользователя нет настроек секций, создаем настройки по умолчанию
        if ($sectionSettings->isEmpty()) {
            $this->createDefaultSectionSettings($pageUser);
            $sectionSettings = $pageUser->sectionSettings()->visible()->ordered()->get()->keyBy('section_key');
        }
        
        // Получаем реальные данные пользователя
        $galleryImages = $pageUser->galleryImages()->active()->ordered()->get();
        $services = $pageUser->services()->active()->ordered()->get();
        $articles = $pageUser->articles()->published()->latest()->limit(5)->get();
        $banners = $pageUser->banners()->active()->ordered()->get();
        
        // Получаем дополнительные социальные ссылки пользователя
        $socialLinks = $pageUser->socialLinks()->ordered()->get();

        // Генерируем блоки галереи из реальных изображений
        $galleryBlocks = $this->generateGalleryBlocks($galleryImages->toArray());

        return view('home', compact('galleryBlocks', 'services', 'articles', 'banners', 'pageUser', 'currentUser', 'socialLinks', 'sectionSettings'));
    }

    /**
     * Update user's profile.
     *
     * @param string $username
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile($username, Request $request)
    {
        // Находим пользователя по username
        $pageUser = User::where('username', $username)->firstOrFail();
        
        // Проверяем, что текущий пользователь может редактировать этот профиль
        $currentUser = auth()->user();
        if ($currentUser->id !== $pageUser->id) {
            abort(403, 'Unauthorized action.');
        }
        
        // Валидация данных
        $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:1000',
        ]);
        
        // Обновляем данные пользователя
        $pageUser->update([
            'name' => $request->name,
            'bio' => $request->bio,
        ]);
        
        return redirect()->route('user.page', ['username' => $username])
                        ->with('success', 'Профиль успешно обновлен!');
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
            ['section_key' => 'articles', 'order' => 4],
            ['section_key' => 'banners', 'order' => 5],
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
}
