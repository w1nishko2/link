<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Article;
use App\Models\Service;
use App\Models\GalleryImage;
use App\Models\Banner;

class SuperAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isAdmin()) {
                abort(403, 'Доступ запрещен. Требуются права администратора.');
            }
            return $next($request);
        });
    }

    /**
     * Главная страница администратора
     */
    public function index()
    {
        // Получаем общую статистику системы
        $stats = [
            'total_users' => User::count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_articles' => Article::count(),
            'published_articles' => Article::where('is_published', true)->count(),
            'total_services' => Service::count(),
            'total_gallery_images' => GalleryImage::count(),
            'total_banners' => Banner::count(),
        ];

        // Получаем последних зарегистрированных пользователей
        $recent_users = User::latest()
            ->take(5)
            ->get();

        // Получаем последние статьи
        $recent_articles = Article::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.super-admin.index', compact('stats', 'recent_users', 'recent_articles'));
    }

    /**
     * Управление пользователями
     */
    public function users()
    {
        $users = User::paginate(20);
        return view('admin.super-admin.users', compact('users'));
    }

    /**
     * Управление всеми статьями
     */
    public function articles()
    {
        $articles = Article::with('user')
            ->latest()
            ->paginate(20);
        
        return view('admin.super-admin.articles', compact('articles'));
    }

    /**
     * Системные настройки
     */
    public function settings()
    {
        return view('admin.super-admin.settings');
    }
}
