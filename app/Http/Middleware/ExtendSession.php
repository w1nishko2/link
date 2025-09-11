<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ExtendSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Если пользователь авторизован, продлеваем сессию
        if (Auth::check()) {
            // Обновляем время жизни сессии при каждом запросе
            Session::migrate();
            
            // Устанавливаем длительное время жизни для remember me
            if ($request->hasCookie(Auth::getRecallerName())) {
                Auth::viaRemember();
            }
        }
        
        return $next($request);
    }
}
