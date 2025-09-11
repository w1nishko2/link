<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        // Нормализуем номер телефона для проверки уникальности
        $normalizedPhone = preg_replace('/[^+\d]/', '', $data['phone']);
        
        return Validator::make($data + ['normalized_phone' => $normalizedPhone], [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9_]+$/', 'unique:users'],
            'phone' => ['required', 'string', 'regex:/^\+7\s?\(?\d{3}\)?\s?[\d\-\s]{7,10}$/'],
            'normalized_phone' => ['unique:users,phone'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Поле "Имя" обязательно для заполнения.',
            'username.required' => 'Поле "Ник" обязательно для заполнения.',
            'username.regex' => 'Ник может содержать только латинские буквы, цифры и подчеркивания.',
            'username.unique' => 'Этот ник уже занят.',
            'phone.required' => 'Поле "Номер телефона" обязательно для заполнения.',
            'phone.regex' => 'Номер телефона должен быть в формате +7 (999) 999-99-99 или +79991234567.',
            'normalized_phone.unique' => 'Пользователь с таким номером телефона уже существует.',
            'password.required' => 'Поле "Пароль" обязательно для заполнения.',
            'password.min' => 'Пароль должен содержать минимум 8 символов.',
            'password.confirmed' => 'Пароли не совпадают.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // Нормализуем номер телефона: убираем все символы кроме цифр и плюса
        $normalizedPhone = preg_replace('/[^+\d]/', '', $data['phone']);
        
        return User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'phone' => $normalizedPhone,
            'password' => Hash::make($data['password']),
        ]);
    }
}
