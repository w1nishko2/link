@extends('layouts.app')

@section('content')
<div class="auth-page">
    <div class="auth-container">
        <div class="card auth-card">
            <div class="card-header">
                <h4>Регистрация</h4>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('register') }}" class="auth-form">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Ваше имя</label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               autofocus>
                        
                        @error('name')
                            <div class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label">Ваш ник (только латинские буквы, цифры и _)</label>
                        <input type="text" 
                               class="form-control @error('username') is-invalid @enderror" 
                               id="username" 
                               name="username" 
                               value="{{ old('username') }}" 
                               required
                               pattern="[a-zA-Z0-9_]+" 
                               title="Только латинские буквы, цифры и подчеркивания">
                        
                        @error('username')
                            <div class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Номер телефона</label>
                        <input type="tel" 
                               class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone') }}" 
                               required 
                               placeholder="+7 (904) 448-22-83"
                               title="Формат: +7 (999) 999-99-99или +79991234567">
                        <div class="form-text">Формат: +7 (904) 448-22-83 или +79041234567</div>
                        
                        @error('phone')
                            <div class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Пароль</label>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               required 
                               minlength="8">
                        <div class="form-text">Минимум 8 символов</div>
                        
                        @error('password')
                            <div class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Повторите пароль</label>
                        <input type="password" 
                               class="form-control" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            Зарегистрироваться
                        </button>
                    </div>

                    <div class="auth-links">
                        <p>Уже есть аккаунт? <a href="{{ route('login') }}">Войти</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
