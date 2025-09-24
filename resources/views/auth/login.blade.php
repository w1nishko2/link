@extends('layouts.app')

@section('content')
<div class="auth-page">
    <div class="auth-container">
        <div class=" ">
         

            <div class="">
                <form method="POST" action="{{ route('login') }}" id="loginForm" class="auth-form">
                    @csrf

                    <div class="mb-3">
                        <label for="phone" class="form-label">Номер телефона</label>
                        <input type="tel" 
                               class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone') }}" 
                               required 
                               placeholder="+7 (900) 123-45-67"
                               title="Российский номер телефона"
                               maxlength="18"
                               autofocus>
                        <div class="form-text">Введите российский номер телефона</div>
                        
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
                               required>
                        
                        @error('password')
                            <div class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" 
                               class="form-check-input" 
                               id="remember" 
                               name="remember" 
                               {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            Запомнить меня
                        </label>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            Войти
                        </button>
                    </div>

                    <div class="auth-links">
                        <p>Нет аккаунта? <a href="{{ route('register') }}">Зарегистрироваться</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const phoneInput = document.getElementById('phone');
    
    // Улучшенная маска для российских номеров
    function formatRussianPhone(value) {
        // Убираем все нецифровые символы
        let cleaned = value.replace(/\D/g, '');
        
        // Обрабатываем разные варианты ввода
        if (cleaned.startsWith('8') && cleaned.length === 11) {
            // Заменяем 8 на 7 для российских номеров
            cleaned = '7' + cleaned.slice(1);
        } else if (!cleaned.startsWith('7') && cleaned.length === 10) {
            // Добавляем 7 к 10-значным номерам
            cleaned = '7' + cleaned;
        } else if (cleaned.startsWith('9') && cleaned.length === 10) {
            // Если номер начинается с 9 и имеет 10 цифр, добавляем 7
            cleaned = '7' + cleaned;
        }
        
        // Ограничиваем до 11 цифр
        if (cleaned.length > 11) {
            cleaned = cleaned.slice(0, 11);
        }
        
        // Форматируем номер
        if (cleaned.length === 0) return '';
        
        let formatted = '+7';
        if (cleaned.length > 1) {
            formatted += ' (' + cleaned.slice(1, 4);
            if (cleaned.length >= 4) {
                formatted += ')';
                if (cleaned.length > 4) {
                    formatted += ' ' + cleaned.slice(4, 7);
                    if (cleaned.length > 7) {
                        formatted += '-' + cleaned.slice(7, 9);
                        if (cleaned.length > 9) {
                            formatted += '-' + cleaned.slice(9, 11);
                        }
                    }
                }
            }
        }
        
        return formatted;
    }
    
    // Обработка ввода
    phoneInput.addEventListener('input', function(e) {
        const cursorPos = e.target.selectionStart;
        const oldValue = e.target.value;
        const formatted = formatRussianPhone(e.target.value);
        
        if (formatted !== oldValue) {
            e.target.value = formatted;
            
            // Корректируем позицию курсора
            let newCursorPos = cursorPos;
            if (formatted.length > oldValue.length) {
                newCursorPos = cursorPos + (formatted.length - oldValue.length);
            } else if (formatted.length < oldValue.length) {
                newCursorPos = Math.max(3, cursorPos - (oldValue.length - formatted.length));
            }
            
            // Не позволяем курсору быть перед +7
            newCursorPos = Math.max(3, newCursorPos);
            e.target.setSelectionRange(newCursorPos, newCursorPos);
        }
    });
    
    // Обработка клавиш
    phoneInput.addEventListener('keydown', function(e) {
        const cursorPos = e.target.selectionStart;
        
        // Разрешаем служебные клавиши
        if ([8, 9, 27, 13, 46].includes(e.keyCode) || 
            (e.keyCode === 65 && e.ctrlKey) || // Ctrl+A
            (e.keyCode === 67 && e.ctrlKey) || // Ctrl+C
            (e.keyCode === 86 && e.ctrlKey) || // Ctrl+V
            (e.keyCode === 88 && e.ctrlKey) || // Ctrl+X
            (e.keyCode >= 35 && e.keyCode <= 40)) { // Home, End, стрелки
            
            // Не позволяем удалять +7
            if (e.keyCode === 8 && cursorPos <= 3) { // Backspace
                e.preventDefault();
            }
            return;
        }
        
        // Разрешаем только цифры
        if (e.shiftKey || (e.keyCode < 48 || e.keyCode > 57) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
    
    // Обработка вставки
    phoneInput.addEventListener('paste', function(e) {
        e.preventDefault();
        const paste = (e.clipboardData || window.clipboardData).getData('text');
        const formatted = formatRussianPhone(paste);
        e.target.value = formatted;
        e.target.dispatchEvent(new Event('input'));
    });
    
    // Применяем форматирование к существующему значению
    if (phoneInput.value) {
        phoneInput.value = formatRussianPhone(phoneInput.value);
    }
    
    // Устанавливаем начальное значение при фокусе на пустом поле
    phoneInput.addEventListener('focus', function(e) {
        if (!e.target.value) {
            e.target.value = '+7 (';
            e.target.setSelectionRange(4, 4);
        }
    });
    
    // Очищаем поле при потере фокуса, если введен только код страны
    phoneInput.addEventListener('blur', function(e) {
        if (e.target.value === '+7 (' || e.target.value === '+7') {
            e.target.value = '';
        }
    });
});
</script>
@endsection
