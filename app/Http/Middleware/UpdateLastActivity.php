<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;

class UpdateLastActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            // Обновляем время последней активности только раз в 5 минут
            $lastUpdate = session('last_activity_update', 0);
            $now = time();
            
            if ($now - $lastUpdate > 300) { // 5 минут = 300 секунд
                $userId = Auth::id();
                User::where('id', $userId)->update(['updated_at' => Carbon::now()]);
                session(['last_activity_update' => $now]);
            }
        }
        
        return $next($request);
    }
}
