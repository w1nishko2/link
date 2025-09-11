<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Article;
use App\Models\Service;
use App\Models\GalleryImage;
use App\Models\Banner;
use App\Services\GptArticleService;
use Illuminate\Support\Facades\Log;

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

    /**
     * Страница генерации статей с помощью GPT
     */
    public function gptGenerator()
    {
        Log::channel('gpt')->info('GPT Controller: Открытие страницы генератора', [
            'user_id' => auth()->id(),
            'user_role' => auth()->user()->role,
        ]);

        $gptService = new GptArticleService();
        
        // Проверяем существование пользователя @weebs
        $weebsUser = User::where('username', 'weebs')->first();
        
        Log::channel('gpt')->info('GPT Controller: Проверка пользователя @weebs', [
            'weebs_exists' => $weebsUser ? true : false,
            'weebs_id' => $weebsUser ? $weebsUser->id : null,
        ]);
        
        return view('admin.super-admin.gpt-generator', compact('weebsUser'));
    }

    /**
     * Генерация статьи через GPT
     */
    public function generateArticle(Request $request)
    {
        Log::channel('gpt')->info('GPT Controller: Запрос на генерацию статьи', [
            'user_id' => auth()->id(),
            'request_data' => $request->all(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $request->validate([
            'topic' => 'required|string|max:255',
            'style' => 'required|string|in:informative,casual,professional,creative',
            'publish' => 'boolean',
            'preview_only' => 'boolean',
        ]);

        try {
            $gptService = new GptArticleService();
            
            // Находим пользователя @weebs
            $weebsUser = User::where('username', 'weebs')->first();
            if (!$weebsUser) {
                Log::channel('gpt')->error('GPT Controller: Пользователь @weebs не найден');
                return response()->json([
                    'success' => false,
                    'message' => 'Пользователь @weebs не найден в системе'
                ], 404);
            }

            Log::channel('gpt')->info('GPT Controller: Начало генерации статьи', [
                'topic' => $request->topic,
                'style' => $request->style,
                'preview_only' => $request->boolean('preview_only', false),
                'publish' => $request->boolean('publish', false),
                'weebs_user_id' => $weebsUser->id,
            ]);

            // Генерируем статью
            $articleData = $gptService->generateArticle(
                $request->topic,
                $request->style,
                $weebsUser
            );

            // Если это только предварительный просмотр
            if ($request->boolean('preview_only', false)) {
                Log::channel('gpt')->info('GPT Controller: Возврат предварительного просмотра', [
                    'title' => $articleData['title'],
                    'excerpt_length' => strlen($articleData['excerpt']),
                    'content_length' => strlen($articleData['content']),
                ]);
                
                return response()->json([
                    'success' => true,
                    'preview' => true,
                    'article_data' => $articleData
                ]);
            }

            // Создаем статью
            $article = $gptService->createArticle(
                $articleData,
                $weebsUser,
                $request->boolean('publish', false)
            );

            Log::channel('gpt')->info('GPT Controller: Статья успешно создана', [
                'article_id' => $article->id,
                'title' => $article->title,
                'slug' => $article->slug,
                'is_published' => $article->is_published,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Статья успешно сгенерирована',
                'article' => [
                    'id' => $article->id,
                    'title' => $article->title,
                    'slug' => $article->slug,
                    'excerpt' => $article->excerpt,
                    'content' => $article->content,
                    'is_published' => $article->is_published,
                    'edit_url' => route('admin.articles.edit', ['user' => $weebsUser->id, 'article' => $article->id]),
                    'view_url' => $article->is_published ? route('articles.show', ['username' => $weebsUser->username, 'slug' => $article->slug]) : null,
                ]
            ]);

        } catch (\Exception $e) {
            Log::channel('gpt')->error('GPT Controller: Ошибка генерации статьи', [
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'request_data' => $request->all(),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Ошибка генерации статьи: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Генерация идей для тем статей
     */
    public function generateTopicIdeas(Request $request)
    {
        Log::channel('gpt')->info('GPT Controller: Запрос генерации идей тем', [
            'user_id' => auth()->id(),
            'category' => $request->category,
        ]);

        $request->validate([
            'category' => 'nullable|string|max:100',
        ]);

        try {
            $gptService = new GptArticleService();
            $ideas = $gptService->generateTopicIdeas($request->category ?? 'общие');

            Log::channel('gpt')->info('GPT Controller: Идеи тем успешно сгенерированы', [
                'category' => $request->category ?? 'общие',
                'ideas_count' => count($ideas),
            ]);

            return response()->json([
                'success' => true,
                'ideas' => $ideas
            ]);

        } catch (\Exception $e) {
            Log::channel('gpt')->error('GPT Controller: Ошибка генерации идей', [
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'category' => $request->category,
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Ошибка генерации идей: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Просмотр логов GPT генератора
     */
    public function gptLogs(Request $request)
    {
        Log::channel('gpt')->info('GPT Controller: Просмотр логов', [
            'user_id' => auth()->id(),
        ]);

        $logPath = storage_path('logs');
        $gptLogFiles = glob($logPath . '/gpt-generator-*.log');
        
        // Сортируем файлы по дате (новые сначала)
        usort($gptLogFiles, function($a, $b) {
            return filemtime($b) - filemtime($a);
        });
        
        $logs = [];
        $latestLogContent = '';
        
        if (!empty($gptLogFiles)) {
            foreach ($gptLogFiles as $file) {
                $logs[] = [
                    'name' => basename($file),
                    'path' => $file,
                    'size' => filesize($file),
                    'modified' => filemtime($file),
                ];
            }
            
            // Если запрошен конкретный файл через AJAX
            if ($request->has('file') && $request->ajax()) {
                $requestedFile = $request->get('file');
                if (file_exists($requestedFile) && in_array($requestedFile, $gptLogFiles)) {
                    $content = file_get_contents($requestedFile);
                    $lines = explode("\n", $content);
                    $logContent = implode("\n", array_slice($lines, -100)); // Последние 100 строк
                    
                    return response()->json([
                        'success' => true,
                        'content' => $logContent
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Файл не найден или недоступен'
                    ]);
                }
            }
            
            // Читаем содержимое последнего файла лога для обычного запроса
            $latestLogFile = $gptLogFiles[0];
            if (file_exists($latestLogFile)) {
                $content = file_get_contents($latestLogFile);
                $lines = explode("\n", $content);
                $latestLogContent = implode("\n", array_slice($lines, -100)); // Последние 100 строк
            }
        }
        
        return view('admin.super-admin.gpt-logs', compact('logs', 'latestLogContent'));
    }

    /**
     * Очистка логов GPT генератора
     */
    public function clearGptLogs()
    {
        Log::channel('gpt')->info('GPT Controller: Очистка логов', [
            'user_id' => auth()->id(),
        ]);

        try {
            $logPath = storage_path('logs');
            $gptLogFiles = glob($logPath . '/gpt-generator-*.log');
            
            $deletedFiles = 0;
            foreach ($gptLogFiles as $file) {
                if (unlink($file)) {
                    $deletedFiles++;
                }
            }
            
            Log::channel('gpt')->info('GPT Controller: Логи очищены', [
                'deleted_files' => $deletedFiles,
                'user_id' => auth()->id(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => "Удалено файлов логов: {$deletedFiles}"
            ]);
            
        } catch (\Exception $e) {
            Log::channel('gpt')->error('GPT Controller: Ошибка очистки логов', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при очистке логов: ' . $e->getMessage()
            ], 500);
        }
    }
}
