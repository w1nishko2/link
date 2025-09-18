@extends('layouts.app')

@section('title', 'Ошибка сервера')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="max-w-md w-full space-y-8 text-center">
        <div>
            <h1 class="text-9xl font-bold text-gray-300">500</h1>
            <h2 class="mt-6 text-3xl font-bold text-gray-900">
                Ошибка сервера
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Произошла внутренняя ошибка сервера. Мы уже работаем над её устранением.
            </p>
        </div>
        
        <div class="mt-8 space-y-4">
            <a href="{{ route('welcome') }}" 
               class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Вернуться на главную
            </a>
            
            <button onclick="window.location.reload()" 
                    class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Обновить страницу
            </button>
        </div>
        
        <div class="mt-6">
            <p class="text-xs text-gray-500">
                Код ошибки: 500 | Если проблема повторяется, обратитесь к администратору
            </p>
        </div>
    </div>
</div>
@endsection