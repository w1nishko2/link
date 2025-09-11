<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'phone';
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string', 'regex:/^\+7\s?\(?\d{3}\)?\s?[\d\-\s]{7,10}$/'],
            'password' => ['required', 'string'],
        ], [
            'phone.required' => 'Поле "Номер телефона" обязательно для заполнения.',
            'phone.regex' => 'Номер телефона должен быть в формате +7 (999) 999-99-99 или +79991234567.',
            'password.required' => 'Поле "Пароль" обязательно для заполнения.',
        ]);
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $credentials = $request->only($this->username(), 'password');
        
        // Нормализуем номер телефона: убираем все символы кроме цифр и плюса
        $credentials['phone'] = preg_replace('/[^+\d]/', '', $credentials['phone']);
        
        return $credentials;
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        // Всегда включаем "запомнить меня" для бесконечной сессии
        return $this->guard()->attempt(
            $this->credentials($request), 
            true // remember = true
        );
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // Если используется наш кастомный guard, дополнительная настройка cookie не нужна
        // так как это уже обрабатывается в ExtendedSessionGuard
        
        return null;
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
