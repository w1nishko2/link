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
        // Находим пользователя по username
        $user = User::where('username', $username)->firstOrFail();
        
        // Получаем все опубликованные статьи пользователя с пагинацией
        $articles = $user->articles()
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
        // Находим пользователя по username
        $user = User::where('username', $username)->firstOrFail();
        
        // Находим статью по slug у данного пользователя
        $article = $user->articles()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();
        
        // Получаем другие статьи этого автора (исключая текущую)
        $relatedArticles = $user->articles()
            ->published()
            ->where('id', '!=', $article->id)
            ->latest()
            ->limit(5)
            ->get();
        
        return view('articles.show', compact('article', 'relatedArticles'));
    }
}
