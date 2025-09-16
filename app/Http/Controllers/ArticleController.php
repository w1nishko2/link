<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Article;

class ArticleController extends Controller
{
    /**
     * Показать все статьи пользователя
     *
     * @param string $username
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($username)
    {
        // Находим пользователя по username с кешированием
        $user = cache()->remember("user:{$username}", 300, function () use ($username) {
            return User::where('username', $username)->firstOrFail();
        });
        
        // Получаем все опубликованные статьи пользователя с оптимизированной пагинацией
        $articles = $user->articles()
            ->select(['id', 'user_id', 'title', 'excerpt', 'image_path', 'slug', 'read_time', 'created_at'])
            ->published()
            ->latest()
            ->paginate(12);
        
        return view('articles.index', compact('user', 'articles'));
    }

    /**
     * Показать статью пользователя
     *
     * @param string $username
     * @param string $slug
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show($username, $slug)
    {
        // Находим пользователя по username с кешированием
        $user = cache()->remember("user:{$username}", 300, function () use ($username) {
            return User::where('username', $username)->firstOrFail();
        });
        
        // Находим статью по slug у данного пользователя с оптимизированным запросом
        $article = $user->articles()
            ->with(['user:id,name,username,avatar'])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();
        
        // Получаем другие статьи этого автора (исключая текущую) с кешированием
        $relatedArticles = cache()->remember("related_articles:{$article->id}", 600, function () use ($user, $article) {
            return $user->articles()
                ->select(['id', 'user_id', 'title', 'excerpt', 'image_path', 'slug', 'read_time', 'created_at'])
                ->published()
                ->where('id', '!=', $article->id)
                ->latest()
                ->limit(5)
                ->get();
        });
        
        return view('articles.show', compact('article', 'relatedArticles'));
    }
}
