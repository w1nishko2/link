@extends('layouts.app')

@section('title', 'Доступ запрещён')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="max-w-md w-full space-y-8 text-center">
        <div>
            <h1 class="text-9xl font-bold text-gray-300">403</h1>
            <h2 class="mt-6 text-3xl font-bold text-gray-900">
                Доступ запрещён
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                У вас недостаточно прав для доступа к данной странице.
            </p>
        </div>
        
        <div class="mt-8 space-y-4">
            <a href="{{ route('welcome') }}" 
               class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Вернуться на главную
            </a>
            
            @auth
                <a href="{{ route('admin.profile', ['user' => auth()->id()]) }}" 
                   class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Перейти в личный кабинет
                </a>
            @endauth
        </div>
        
        <div class="mt-6">
            <p class="text-xs text-gray-500">
                Код ошибки: 403 | Обратитесь к администратору для получения доступа
            </p>
        </div>
    </div>
</div>
@endsection