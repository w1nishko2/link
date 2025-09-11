<?php

use Illuminate\Support\Facades\Route;

// Отладочный маршрут для проверки аутентификации
Route::get('/debug/auth', function () {
    return [
        'auth_check' => auth()->check(),
        'auth_id' => auth()->id(),
        'auth_user' => auth()->user(),
        'session_id' => session()->getId(),
        'session_data' => session()->all(),
    ];
});
