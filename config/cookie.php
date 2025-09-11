<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Cookie Settings
    |--------------------------------------------------------------------------
    |
    | Here you may configure the default settings for cookies that will be
    | used by your application. These settings affect all cookies created.
    |
    */

    'lifetime' => env('COOKIE_LIFETIME', 525600), // 365 дней в минутах
    'path' => env('COOKIE_PATH', '/'),
    'domain' => env('COOKIE_DOMAIN', null),
    'secure' => env('COOKIE_SECURE', false),
    'http_only' => env('COOKIE_HTTP_ONLY', true),
    'same_site' => env('COOKIE_SAME_SITE', 'lax'),

    /*
    |--------------------------------------------------------------------------
    | Remember Me Cookie Settings
    |--------------------------------------------------------------------------
    |
    | These settings control the "remember me" functionality for authentication.
    |
    */

    'remember_lifetime' => env('REMEMBER_LIFETIME', 525600), // 365 дней в минутах

];
