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
        
        // Отладочный вывод
        Log::info('Запрос к AllArticlesController', [
            'isAjax' => $request->ajax(),
            'wantsJson' => $request->wantsJson(),
            'expectsJson' => $request->expectsJson(),
            'page' => $request->get('page', 1),
            'search' => $search,
            'method' => $request->method(),
            'userAgent' => $request->userAgent(),
            'headers' => [
                'X-Requested-With' => $request->header('X-Requested-With'),
                'Accept' => $request->header('Accept'),
                'Content-Type' => $request->header('Content-Type'),
                'X-CSRF-TOKEN' => $request->header('X-CSRF-TOKEN') ? 'присутствует' : 'отсутствует'
            ]
        ]);
        
        $query = Article::query()
            ->with('user')
            ->where('is_published', true);

        // Если есть поисковый запрос, применяем поиск
        if (!empty($search)) {
            $query->where(function (Builder $q) use ($search) {
                // Поиск по заголовку статьи (70% релевантности)
                $q->where('title', 'LIKE', '%' . $search . '%')
                  // Поиск по краткому описанию
                  ->orWhere('excerpt', 'LIKE', '%' . $search . '%')
                  // Поиск по содержимому статьи
                  ->orWhere('content', 'LIKE', '%' . $search . '%')
                  // Поиск по имени автора
                  ->orWhereHas('user', function (Builder $userQuery) use ($search) {
                      $userQuery->where('name', 'LIKE', '%' . $search . '%')
                                ->orWhere('username', 'LIKE', '%' . $search . '%')
                                ->orWhere('bio', 'LIKE', '%' . $search . '%');
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
            Log::info('AJAX запрос получен', [
                'page' => $articles->currentPage(),
                'total' => $articles->total(),
                'perPage' => $articles->perPage(),
                'hasMore' => $articles->hasMorePages(),
                'search' => $search
            ]);
            
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
        $totalArticles = Article::where('is_published', true)->count();
        $totalAuthors = User::whereHas('articles', function (Builder $q) {
            $q->where('is_published', true);
        })->count();

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
