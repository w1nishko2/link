<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\GalleryImage;
use App\Models\Service;
use App\Models\Article;
use App\Models\Banner;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function __construct()
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
    }

    /**
     * Главная страница аналитики
     */
    public function index($user = null)
    {
        // Если передан ID пользователя, используем его, иначе текущего авторизованного
        if ($user) {
            $targetUser = User::findOrFail($user);
            // Проверяем права доступа - пользователь может видеть только свою аналитику
            if (auth()->user()->id != (int)$user) {
                abort(403, 'Доступ запрещен');
            }
        } else {
            $targetUser = auth()->user();
        }
        
        // Основная статистика
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

        // Статистика по месяцам (последние 12 месяцев)
        $monthlyStats = $this->getMonthlyStats($targetUser);
        
        // Статистика по дням (последние 30 дней)
        $dailyStats = $this->getDailyStats($targetUser);
        
        // Статистика контента по типам
        $contentTypeStats = $this->getContentTypeStats($targetUser);
        
        // Динамика роста контента
        $growthStats = $this->getGrowthStats($targetUser);
        
        // Процент заполненности профиля
        $profileCompletion = $this->calculateProfileCompletion($targetUser);

        return view('admin.analytics.index', compact(
            'stats', 
            'monthlyStats', 
            'dailyStats', 
            'contentTypeStats', 
            'growthStats',
            'profileCompletion',
            'targetUser'
        ))->with('user', $targetUser);
    }

    /**
     * Получить статистику по месяцам
     */
    private function getMonthlyStats($user)
    {
        $monthlyData = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            $monthName = $date->format('M Y');
            
            $monthlyData[] = [
                'month' => $monthName,
                'month_key' => $monthKey,
                'articles' => Article::where('user_id', $user->id)
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'services' => Service::where('user_id', $user->id)
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'gallery' => GalleryImage::where('user_id', $user->id)
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'banners' => Banner::where('user_id', $user->id)
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
            ];
        }
        
        return $monthlyData;
    }

    /**
     * Получить статистику по дням
     */
    private function getDailyStats($user)
    {
        $dailyData = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dateKey = $date->format('Y-m-d');
            $dayName = $date->format('d M');
            
            $dailyData[] = [
                'day' => $dayName,
                'date' => $dateKey,
                'articles' => Article::where('user_id', $user->id)
                    ->whereDate('created_at', $date)
                    ->count(),
                'services' => Service::where('user_id', $user->id)
                    ->whereDate('created_at', $date)
                    ->count(),
                'gallery' => GalleryImage::where('user_id', $user->id)
                    ->whereDate('created_at', $date)
                    ->count(),
                'banners' => Banner::where('user_id', $user->id)
                    ->whereDate('created_at', $date)
                    ->count(),
            ];
        }
        
        return $dailyData;
    }

    /**
     * Получить статистику по типам контента
     */
    private function getContentTypeStats($user)
    {
        return [
            [
                'type' => 'Статьи',
                'total' => Article::where('user_id', $user->id)->count(),
                'active' => Article::where('user_id', $user->id)->where('is_published', true)->count(),
                'color' => '#3b82f6'
            ],
            [
                'type' => 'Услуги',
                'total' => Service::where('user_id', $user->id)->count(),
                'active' => Service::where('user_id', $user->id)->where('is_active', true)->count(),
                'color' => '#10b981'
            ],
            [
                'type' => 'Галерея',
                'total' => GalleryImage::where('user_id', $user->id)->count(),
                'active' => GalleryImage::where('user_id', $user->id)->where('is_active', true)->count(),
                'color' => '#f59e0b'
            ],
            [
                'type' => 'Баннеры',
                'total' => Banner::where('user_id', $user->id)->count(),
                'active' => Banner::where('user_id', $user->id)->where('is_active', true)->count(),
                'color' => '#ef4444'
            ]
        ];
    }

    /**
     * Получить статистику роста
     */
    private function getGrowthStats($user)
    {
        $currentMonth = now();
        $previousMonth = now()->subMonth();
        
        $currentStats = [
            'articles' => Article::where('user_id', $user->id)
                ->whereYear('created_at', $currentMonth->year)
                ->whereMonth('created_at', $currentMonth->month)
                ->count(),
            'services' => Service::where('user_id', $user->id)
                ->whereYear('created_at', $currentMonth->year)
                ->whereMonth('created_at', $currentMonth->month)
                ->count(),
            'gallery' => GalleryImage::where('user_id', $user->id)
                ->whereYear('created_at', $currentMonth->year)
                ->whereMonth('created_at', $currentMonth->month)
                ->count(),
            'banners' => Banner::where('user_id', $user->id)
                ->whereYear('created_at', $currentMonth->year)
                ->whereMonth('created_at', $currentMonth->month)
                ->count(),
        ];
        
        $previousStats = [
            'articles' => Article::where('user_id', $user->id)
                ->whereYear('created_at', $previousMonth->year)
                ->whereMonth('created_at', $previousMonth->month)
                ->count(),
            'services' => Service::where('user_id', $user->id)
                ->whereYear('created_at', $previousMonth->year)
                ->whereMonth('created_at', $previousMonth->month)
                ->count(),
            'gallery' => GalleryImage::where('user_id', $user->id)
                ->whereYear('created_at', $previousMonth->year)
                ->whereMonth('created_at', $previousMonth->month)
                ->count(),
            'banners' => Banner::where('user_id', $user->id)
                ->whereYear('created_at', $previousMonth->year)
                ->whereMonth('created_at', $previousMonth->month)
                ->count(),
        ];
        
        $growth = [];
        foreach ($currentStats as $type => $current) {
            $previous = $previousStats[$type];
            $growth[$type] = [
                'current' => $current,
                'previous' => $previous,
                'change' => $current - $previous,
                'percentage' => $previous > 0 ? round((($current - $previous) / $previous) * 100, 1) : ($current > 0 ? 100 : 0)
            ];
        }
        
        return $growth;
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
            'has_services' => Service::where('user_id', $user->id)->count() > 0,
            'has_articles' => Article::where('user_id', $user->id)->count() > 0,
            'has_gallery' => GalleryImage::where('user_id', $user->id)->count() > 0,
        ];

        $completed = count(array_filter($fields));
        $total = count($fields);

        return [
            'percentage' => round(($completed / $total) * 100),
            'completed' => $completed,
            'total' => $total,
            'fields' => $fields
        ];
    }

    /**
     * API эндпоинт для получения данных графиков
     */
    public function getChartData(Request $request, $user)
    {
        $targetUser = User::findOrFail($user);
        
        // Проверяем права доступа
        if (auth()->user()->id != (int)$user) {
            abort(403, 'Доступ запрещен');
        }
        
        $type = $request->get('type', 'monthly');
        
        if ($type === 'monthly') {
            return response()->json($this->getMonthlyStats($targetUser));
        } elseif ($type === 'daily') {
            return response()->json($this->getDailyStats($targetUser));
        }
        
        return response()->json([]);
    }
}