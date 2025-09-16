<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class AllArticlesController extends Controller
{
    /**
     * Показать все статьи всех пользователей с возможностью поиска
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Support\Renderable|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        // Очищаем пустые поисковые запросы
        if (empty(trim($search))) {
            $search = null;
        }
        
        // Убираем отладочные логи для продакшена
        if (app()->environment('local')) {
            Log::info('Запрос к AllArticlesController', [
                'isAjax' => $request->ajax(),
                'page' => $request->get('page', 1),
                'search' => $search,
            ]);
        }
        
        $query = Article::query()
            ->with(['user:id,name,username,avatar']) // Загружаем только нужные поля пользователя
            ->where('is_published', true)
            ->select(['id', 'user_id', 'title', 'excerpt', 'image_path', 'slug', 'read_time', 'created_at']); // Выбираем только нужные поля

        // Если есть поисковый запрос, применяем оптимизированный поиск
        if (!empty($search)) {
            $searchTerm = '%' . $search . '%';
            $query->where(function (Builder $q) use ($searchTerm) {
                // Поиск по заголовку статьи (высокий приоритет)
                $q->where('title', 'LIKE', $searchTerm)
                  // Поиск по краткому описанию
                  ->orWhere('excerpt', 'LIKE', $searchTerm)
                  // Поиск по имени автора (оптимизированный)
                  ->orWhereHas('user', function (Builder $userQuery) use ($searchTerm) {
                      $userQuery->select(['id', 'name', 'username'])
                                ->where('name', 'LIKE', $searchTerm)
                                ->orWhere('username', 'LIKE', $searchTerm);
                  });
            });
        }

        // Сортировка по дате создания (сначала новые)
        $articles = $query->latest()->paginate(8);
        
        // Добавляем параметры к ссылкам пагинации только если они не пустые
        $appendParams = [];
        if (!empty($search)) {
            $appendParams['search'] = $search;
        }
        if (!empty($appendParams)) {
            $articles->appends($appendParams);
        }

        // Проверяем, является ли это AJAX-запросом для бесконечной прокрутки
        if ($request->ajax()) {
            if (app()->environment('local')) {
                Log::info('AJAX запрос получен', [
                    'page' => $articles->currentPage(),
                    'total' => $articles->total(),
                    'hasMore' => $articles->hasMorePages(),
                ]);
            }
            
            return response()->json([
                'html' => view('articles.partials.articles-grid', compact('articles', 'search'))->render(),
                'hasMore' => $articles->hasMorePages(),
                'nextPage' => $articles->currentPage() + 1,
            ]);
        }

        // Получаем статистику для отображения с кешированием
        $totalArticles = cache()->remember('total_published_articles', 1800, function () {
            return Article::where('is_published', true)->count();
        });
        
        $totalAuthors = cache()->remember('total_authors_with_articles', 1800, function () {
            return User::whereHas('articles', function (Builder $q) {
                $q->where('is_published', true);
            })->count();
        });

        return view('articles.all', compact('articles', 'search', 'totalArticles', 'totalAuthors'));
    }

    /**
     * API для автодополнения поиска
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchSuggestions(Request $request)
    {
        $term = $request->get('term');
        
        if (empty($term) || strlen($term) < 2) {
            return response()->json([]);
        }

        // Получаем заголовки статей для автодополнения
        $articleTitles = Article::where('is_published', true)
            ->where('title', 'LIKE', '%' . $term . '%')
            ->limit(5)
            ->pluck('title')
            ->toArray();

        // Получаем имена авторов для автодополнения
        $authorNames = User::whereHas('articles', function (Builder $q) {
                $q->where('is_published', true);
            })
            ->where(function (Builder $q) use ($term) {
                $q->where('name', 'LIKE', '%' . $term . '%')
                  ->orWhere('username', 'LIKE', '%' . $term . '%');
            })
            ->limit(3)
            ->pluck('name')
            ->toArray();

        $suggestions = array_merge($articleTitles, $authorNames);
        
        return response()->json(array_slice(array_unique($suggestions), 0, 8));
    }
}
