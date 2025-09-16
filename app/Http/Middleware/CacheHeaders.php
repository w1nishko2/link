<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, int $seconds = 3600): Response
    {
        $response = $next($request);

        // Применяем кеширование только для GET запросов
        if ($request->isMethod('GET') && $response->isSuccessful()) {
            $response->headers->set('Cache-Control', "public, max-age={$seconds}");
            $response->headers->set('Expires', gmdate('D, d M Y H:i:s \G\M\T', time() + $seconds));
            
            // ETag для валидации кеша
            $etag = md5($response->getContent());
            $response->headers->set('ETag', $etag);
            
            // Проверяем If-None-Match заголовок
            if ($request->header('If-None-Match') === $etag) {
                return response('', 304);
            }
        }

        return $response;
    }
}