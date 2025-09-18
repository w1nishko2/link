<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
// Маршрут для персональных страниц пользователей (публичный) - используем префикс "user"
Route::middleware(['throttle:60,1'])->group(function () {
    Route::get('/user/{username}', [App\Http\Controllers\HomeController::class, 'show'])->name('user.show');
    
    // Маршрут для галереи пользователя
    Route::get('/user/{username}/gallery', [App\Http\Controllers\HomeController::class, 'gallery'])->name('user.gallery');
    Route::get('/user/{username}/gallery/{image}', [App\Http\Controllers\HomeController::class, 'galleryImage'])->name('user.gallery.image');
    
    // Маршруты для услуг
    Route::get('/user/{username}/services', [App\Http\Controllers\HomeController::class, 'services'])->name('user.services');
    Route::get('/user/{username}/services/{service}', [App\Http\Controllers\HomeController::class, 'serviceDetail'])->name('user.service.detail');
    
    // Маршруты для статей
    Route::get('/user/{username}/articles', [App\Http\Controllers\ArticleController::class, 'index'])->name('articles.index');
    Route::get('/user/{username}/article/{slug}', [App\Http\Controllers\ArticleController::class, 'show'])->name('articles.show');
});

// Роуты для фоторедактора (требуют авторизации)
Route::middleware('auth')->prefix('photo-editor')->group(function () {
    Route::post('/save', [App\Http\Controllers\PhotoEditorController::class, 'save'])->name('photo-editor.save');
    Route::get('/current-images', [App\Http\Controllers\PhotoEditorController::class, 'getCurrentImages'])->name('photo-editor.current');
    Route::delete('/delete-image', [App\Http\Controllers\PhotoEditorController::class, 'deleteImage'])->name('photo-editor.delete');
});
Route::get('/', [App\Http\Controllers\HomeController::class, 'redirectToHome'])->name('welcome');

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
});

// Маршруты админки (требуют авторизации) - должны быть ПЕРЕД публичными маршрутами
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    // Перенаправление на аналитику
    Route::get('/', function() {
        return redirect()->route('admin.analytics', ['user' => auth()->id()]);
    });
    
    // Аналитика
    Route::get('/user/{user}/analytics', [App\Http\Controllers\AnalyticsController::class, 'index'])->name('analytics');
    Route::get('/user/{user}/analytics/data', [App\Http\Controllers\AnalyticsController::class, 'getChartData'])->name('analytics.data');
    
    // Управление профилем
    Route::get('/user/{user}/profile', [App\Http\Controllers\AdminController::class, 'profile'])->name('profile');
    Route::get('/user/{user}/profile/{tab?}', [App\Http\Controllers\AdminController::class, 'profileTab'])->name('profile.tab');
    Route::put('/user/{user}/profile/basic', [App\Http\Controllers\AdminController::class, 'updateBasicInfo'])->name('profile.update.basic');
    Route::put('/user/{user}/profile/social', [App\Http\Controllers\AdminController::class, 'updateSocialMedia'])->name('profile.update.social');
    Route::put('/user/{user}/profile/security', [App\Http\Controllers\AdminController::class, 'updateSecurity'])->name('profile.update.security');
    Route::put('/user/{user}/profile', [App\Http\Controllers\AdminController::class, 'updateProfile'])->name('profile.update');
    
    // Обновление изображений профиля
    Route::post('/profile/{user}/update-avatar', [App\Http\Controllers\AdminController::class, 'updateAvatar'])->name('profile.update.avatar');
    Route::post('/profile/{user}/update-background', [App\Http\Controllers\AdminController::class, 'updateBackground'])->name('profile.update.background');
    
    // Управление галереей
    Route::get('/user/{user}/gallery', [App\Http\Controllers\AdminController::class, 'gallery'])->name('gallery');
    Route::get('/user/{user}/gallery/create', [App\Http\Controllers\AdminController::class, 'galleryCreate'])->name('gallery.create');
    Route::post('/user/{user}/gallery', [App\Http\Controllers\AdminController::class, 'galleryStore'])->name('gallery.store');
    Route::get('/user/{user}/gallery/{image}/edit', [App\Http\Controllers\AdminController::class, 'galleryEdit'])->name('gallery.edit');
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
    Route::get('/user/{user}/banners/create', [App\Http\Controllers\AdminController::class, 'bannersCreate'])->name('banners.create');
    Route::post('/user/{user}/banners', [App\Http\Controllers\AdminController::class, 'bannersStore'])->name('banners.store');
    Route::get('/user/{user}/banners/{banner}/edit', [App\Http\Controllers\AdminController::class, 'bannersEdit'])->name('banners.edit');
    Route::put('/user/{user}/banners/{banner}', [App\Http\Controllers\AdminController::class, 'bannersUpdate'])->name('banners.update');
    Route::delete('/user/{user}/banners/{banner}', [App\Http\Controllers\AdminController::class, 'bannersDestroy'])->name('banners.destroy');
    
    // Управление пользовательскими социальными ссылками
    Route::post('/user/{user}/social-links', [App\Http\Controllers\AdminController::class, 'socialLinksStore'])->name('social-links.store');
    Route::put('/user/{user}/social-links/{socialLink}', [App\Http\Controllers\AdminController::class, 'socialLinksUpdate'])->name('social-links.update');
    Route::delete('/user/{user}/social-links/{socialLink}', [App\Http\Controllers\AdminController::class, 'socialLinksDestroy'])->name('social-links.destroy');
    Route::post('/user/{user}/social-links/update-order', [App\Http\Controllers\AdminController::class, 'socialLinksUpdateOrder'])->name('social-links.update-order');
    
    // Управление разделами сайта
    Route::get('/user/{user}/sections', [App\Http\Controllers\AdminController::class, 'getSectionSettings'])->name('sections.get');
    Route::post('/user/{user}/sections', [App\Http\Controllers\AdminController::class, 'updateSectionSettings'])->name('sections.update');
    
    // Роуты для фоторедактора
    Route::prefix('photo-editor')->group(function () {
        Route::post('/save', [App\Http\Controllers\PhotoEditorController::class, 'save'])->name('photo-editor.save');
        Route::get('/current-images', [App\Http\Controllers\PhotoEditorController::class, 'getCurrentImages'])->name('photo-editor.current');
        Route::delete('/delete-image', [App\Http\Controllers\PhotoEditorController::class, 'deleteImage'])->name('photo-editor.delete');
    });
    
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
Route::middleware(['throttle:60,1'])->group(function () {
    Route::get('/user/{username}', [App\Http\Controllers\HomeController::class, 'userPage'])->name('user.page');
    
    // Маршрут для просмотра всех статей всех пользователей с поиском
    Route::get('/articles', [App\Http\Controllers\AllArticlesController::class, 'index'])->name('articles.all');
    
    // API для автодополнения поиска статей
    Route::get('/api/articles/suggestions', [App\Http\Controllers\AllArticlesController::class, 'searchSuggestions'])->name('articles.suggestions');
    
    // Маршрут для просмотра всех статей пользователя
    Route::get('/user/{username}/articles', [App\Http\Controllers\ArticleController::class, 'index'])->name('articles.index');
    
    // Маршрут для просмотра статьи пользователя
    Route::get('/user/{username}/article/{slug}', [App\Http\Controllers\ArticleController::class, 'show'])->name('articles.show');
});

// Маршрут для обновления профиля пользователя (требует авторизации)
Route::put('/user/{username}/update', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('user.update')->middleware('auth');

// Маршруты для обновления изображений на пользовательской странице (требует авторизации)
Route::post('/user/{username}/update-background', [App\Http\Controllers\HomeController::class, 'updateBackground'])->name('user.update.background')->middleware('auth');
Route::post('/user/{username}/update-dual-background', [App\Http\Controllers\HomeController::class, 'updateDualBackground'])->name('user.update.dual.background')->middleware('auth');
Route::post('/user/{username}/update-avatar', [App\Http\Controllers\HomeController::class, 'updateAvatar'])->name('user.update.avatar')->middleware('auth');
if (app()->environment('production')) {
    URL::forceScheme('https');
}

