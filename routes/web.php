<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('home');
});

// Тестовый маршрут для проверки AJAX
Route::get('/test-ajax', function() {
    return response()->json(['message' => 'AJAX работает', 'time' => now()]);
});

// Тестовая страница для отладки пагинации
Route::get('/test-pagination', function() {
    return view('test-ajax');
});

// Тестовая страница для отладки бесконечной прокрутки
Route::get('/test-infinite-scroll', function() {
    return view('test-infinite-scroll');
});

// Отладочный маршрут для проверки аутентификации
Route::get('/debug/auth', function () {
    return [
        'auth_check' => auth()->check(),
        'auth_id' => auth()->id(),
        'auth_user' => auth()->user(),
        'session_id' => session()->getId(),
    ];
});

Auth::routes(['reset' => false, 'verify' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Маршрут для поддержания сессии активной
Route::post('/session-ping', function() {
    return response()->json(['status' => 'ok']);
})->middleware('auth');

// Маршруты супер-админа (требуют роль администратора)
Route::middleware(['auth'])->prefix('super-admin')->name('super-admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\SuperAdminController::class, 'index'])->name('index');
    Route::get('/users', [App\Http\Controllers\SuperAdminController::class, 'users'])->name('users');
    Route::get('/articles', [App\Http\Controllers\SuperAdminController::class, 'articles'])->name('articles');
    Route::get('/settings', [App\Http\Controllers\SuperAdminController::class, 'settings'])->name('settings');
    
    // GPT генератор статей
    Route::get('/gpt-generator', [App\Http\Controllers\SuperAdminController::class, 'gptGenerator'])->name('gpt-generator');
    Route::post('/generate-article', [App\Http\Controllers\SuperAdminController::class, 'generateArticle'])->name('generate-article');
    Route::post('/generate-topic-ideas', [App\Http\Controllers\SuperAdminController::class, 'generateTopicIdeas'])->name('generate-topic-ideas');
    
    // Просмотр и управление логами GPT
    Route::get('/gpt-logs', [App\Http\Controllers\SuperAdminController::class, 'gptLogs'])->name('gpt-logs');
    Route::post('/clear-gpt-logs', [App\Http\Controllers\SuperAdminController::class, 'clearGptLogs'])->name('clear-gpt-logs');
});

// Маршруты админки (требуют авторизации) - должны быть ПЕРЕД публичными маршрутами
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    // Главная страница админки - теперь с ID пользователя
    Route::get('/', function() {
        return redirect()->route('admin.dashboard', ['user' => auth()->id()]);
    });
    Route::get('/user/{user}', [App\Http\Controllers\AdminController::class, 'index'])->name('dashboard');
    
    // Управление профилем
    Route::get('/user/{user}/profile', [App\Http\Controllers\AdminController::class, 'profile'])->name('profile');
    Route::put('/user/{user}/profile', [App\Http\Controllers\AdminController::class, 'updateProfile'])->name('profile.update');
    
    // Управление галереей
    Route::get('/user/{user}/gallery', [App\Http\Controllers\AdminController::class, 'gallery'])->name('gallery');
    Route::post('/user/{user}/gallery', [App\Http\Controllers\AdminController::class, 'galleryStore'])->name('gallery.store');
    Route::put('/user/{user}/gallery/{image}', [App\Http\Controllers\AdminController::class, 'galleryUpdate'])->name('gallery.update');
    Route::delete('/user/{user}/gallery/{image}', [App\Http\Controllers\AdminController::class, 'galleryDestroy'])->name('gallery.destroy');
    
    // Управление услугами
    Route::get('/user/{user}/services', [App\Http\Controllers\AdminController::class, 'services'])->name('services');
    Route::get('/user/{user}/services/create', [App\Http\Controllers\AdminController::class, 'servicesCreate'])->name('services.create');
    Route::post('/user/{user}/services', [App\Http\Controllers\AdminController::class, 'servicesStore'])->name('services.store');
    Route::get('/user/{user}/services/{service}/edit', [App\Http\Controllers\AdminController::class, 'servicesEdit'])->name('services.edit');
    Route::put('/user/{user}/services/{service}', [App\Http\Controllers\AdminController::class, 'servicesUpdate'])->name('services.update');
    Route::delete('/user/{user}/services/{service}', [App\Http\Controllers\AdminController::class, 'servicesDestroy'])->name('services.destroy');
    
    // Управление статьями
    Route::get('/user/{user}/articles', [App\Http\Controllers\AdminController::class, 'articles'])->name('articles');
    Route::get('/user/{user}/articles/create', [App\Http\Controllers\AdminController::class, 'articlesCreate'])->name('articles.create');
    Route::post('/user/{user}/articles', [App\Http\Controllers\AdminController::class, 'articlesStore'])->name('articles.store');
    Route::get('/user/{user}/articles/{article}/edit', [App\Http\Controllers\AdminController::class, 'articlesEdit'])->name('articles.edit');
    Route::put('/user/{user}/articles/{article}', [App\Http\Controllers\AdminController::class, 'articlesUpdate'])->name('articles.update');
    Route::delete('/user/{user}/articles/{article}', [App\Http\Controllers\AdminController::class, 'articlesDestroy'])->name('articles.destroy');
    
    // Управление баннерами
    Route::get('/user/{user}/banners', [App\Http\Controllers\AdminController::class, 'banners'])->name('banners');
    Route::post('/user/{user}/banners', [App\Http\Controllers\AdminController::class, 'bannersStore'])->name('banners.store');
    Route::get('/user/{user}/banners/{banner}/edit', [App\Http\Controllers\AdminController::class, 'bannersEdit'])->name('banners.edit');
    Route::put('/user/{user}/banners/{banner}', [App\Http\Controllers\AdminController::class, 'bannersUpdate'])->name('banners.update');
    Route::delete('/user/{user}/banners/{banner}', [App\Http\Controllers\AdminController::class, 'bannersDestroy'])->name('banners.destroy');
    
    // Обратная совместимость со старыми маршрутами
    Route::get('/gallery', function() {
        return redirect()->route('admin.gallery', ['user' => auth()->id()]);
    });
    Route::get('/services', function() {
        return redirect()->route('admin.services', ['user' => auth()->id()]);
    });
    // Убираем редирект для /articles, чтобы не конфликтовал с публичным маршрутом
    Route::get('/banners', function() {
        return redirect()->route('admin.banners', ['user' => auth()->id()]);
    });
});

// Маршрут для персональных страниц пользователей (публичный) - используем префикс "user"
Route::get('/user/{username}', [App\Http\Controllers\HomeController::class, 'userPage'])->name('user.page');

// Маршрут для просмотра всех статей всех пользователей с поиском
Route::get('/articles', [App\Http\Controllers\AllArticlesController::class, 'index'])->name('articles.all');

// Тестовый маршрут для простой версии
Route::get('/articles-simple', function(Illuminate\Http\Request $request) {
    $search = $request->get('search');
    
    // Очищаем пустые поисковые запросы
    if (empty(trim($search))) {
        $search = null;
    }
    
    $query = App\Models\Article::query()
        ->with('user')
        ->where('is_published', true);

    // Если есть поисковый запрос, применяем поиск
    if (!empty($search)) {
        $query->where(function (Illuminate\Database\Eloquent\Builder $q) use ($search) {
            $q->where('title', 'LIKE', '%' . $search . '%')
              ->orWhere('excerpt', 'LIKE', '%' . $search . '%')
              ->orWhere('content', 'LIKE', '%' . $search . '%')
              ->orWhereHas('user', function (Illuminate\Database\Eloquent\Builder $userQuery) use ($search) {
                  $userQuery->where('name', 'LIKE', '%' . $search . '%')
                            ->orWhere('username', 'LIKE', '%' . $search . '%')
                            ->orWhere('bio', 'LIKE', '%' . $search . '%');
              });
        });
    }

    $articles = $query->latest()->paginate(8);
    
    if ($search) {
        $articles->appends(['search' => $search]);
    }

    // Логируем запрос
    \Illuminate\Support\Facades\Log::info('Запрос к articles-simple', [
        'isAjax' => $request->ajax(),
        'wantsJson' => $request->wantsJson(),
        'page' => $request->get('page', 1),
        'search' => $search,
        'headers' => [
            'X-Requested-With' => $request->header('X-Requested-With'),
            'Accept' => $request->header('Accept'),
        ]
    ]);

    // Проверяем, является ли это AJAX-запросом
    if ($request->ajax()) {
        return response()->json([
            'html' => view('articles.partials.articles-grid', compact('articles', 'search'))->render(),
            'hasMore' => $articles->hasMorePages(),
            'nextPage' => $articles->currentPage() + 1,
            'debug' => [
                'currentPage' => $articles->currentPage(),
                'total' => $articles->total(),
                'perPage' => $articles->perPage()
            ]
        ]);
    }

    // Получаем статистику для отображения
    $totalArticles = App\Models\Article::where('is_published', true)->count();
    $totalAuthors = App\Models\User::whereHas('articles', function (Illuminate\Database\Eloquent\Builder $q) {
        $q->where('is_published', true);
    })->count();

    return view('articles.all-simple', compact('articles', 'search', 'totalArticles', 'totalAuthors'));
});

// API для автодополнения поиска статей
Route::get('/api/articles/suggestions', [App\Http\Controllers\AllArticlesController::class, 'searchSuggestions'])->name('articles.suggestions');

// Маршрут для просмотра всех статей пользователя
Route::get('/user/{username}/articles', [App\Http\Controllers\ArticleController::class, 'index'])->name('articles.index');

// Маршрут для просмотра статьи пользователя
Route::get('/user/{username}/article/{slug}', [App\Http\Controllers\ArticleController::class, 'show'])->name('articles.show');

// Маршрут для обновления профиля пользователя (требует авторизации)
Route::put('/user/{username}/update', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('user.update')->middleware('auth');
