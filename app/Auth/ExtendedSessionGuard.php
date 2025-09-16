<?php

namespace App\Auth;

use Illuminate\Auth\SessionGuard;

class ExtendedSessionGuard extends SessionGuard
{
    /**
     * Get the number of minutes the remember me cookie should be valid for.
     *
     * @return int
     */
    protected function getRememberDuration()
    {
        return 43200; // 30 дней в минутах (рекомендуемое значение для продакшена)
    }

    /**
     * Determine if the user was authenticated via "remember me" cookie.
     *
     * @return bool
     */
    public function viaRemember()
    {
        $remember = parent::viaRemember();
        
        // Если пользователь зашел через remember me, обновляем время жизни сессии
        if ($remember) {
            $this->session->migrate(true);
            $this->session->put('auth.password_confirmed_at', time());
        }
        
        return $remember;
    }

    /**
     * Log a user into the application.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  bool  $remember
     * @return void
     */
    public function login($user, $remember = false)
    {
        $this->updateSession($user->getAuthIdentifier());

        // If the user should be permanently "remembered" by the application we will
        // queue a permanent cookie that contains the encrypted copy of the user
        // identifier. We will then decrypt this on each request to retrieve the user.
        if ($remember) {
            $this->ensureRememberTokenIsSet($user);
            $this->queueRecallerCookie($user);
        }

        // If we have an event dispatcher instance set we will fire an authentication
        // event so that any listeners will hook into the authentication events and
        // run actions based on the login and logout events fired from the guard.
        $this->fireLoginEvent($user, $remember);

        $this->setUser($user);
    }

    /**
     * Create a new "remember me" token for the user if one doesn't already exist.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    protected function ensureRememberTokenIsSet($user)
    {
        if (empty($user->getRememberToken())) {
            $this->cycleRememberToken($user);
        }
    }

    /**
     * Queue the recaller cookie in the cookie jar.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    protected function queueRecallerCookie($user)
    {
        $this->getCookieJar()->queue($this->createRecaller(
            $user->getAuthIdentifier().'|'.$user->getRememberToken().'|'.$user->getAuthPassword()
        ));
    }

    /**
     * Create a "remember me" cookie for a given ID.
     *
     * @param  string  $value
     * @return \Symfony\Component\HttpFoundation\Cookie
     */
    protected function createRecaller($value)
    {
        return $this->getCookieJar()->make(
            $this->getRecallerName(), 
            $value, 
            $this->getRememberDuration()
        );
    }
}
