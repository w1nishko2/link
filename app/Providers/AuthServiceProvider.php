<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Регистрируем кастомный guard для расширенных сессий
        Auth::extend('extended_session', function ($app, $name, array $config) {
            $guard = new \App\Auth\ExtendedSessionGuard(
                $name,
                Auth::createUserProvider($config['provider']),
                $app['session.store'],
                $app['request']
            );
            
            // Когда guard создан, нам нужно установить cookie jar
            $guard->setCookieJar($app['cookie']);
            
            return $guard;
        });
    }
}
