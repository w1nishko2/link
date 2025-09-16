{{-- Компонент оптимизированного изображения --}}
@props([
    'src' => '',
    'alt' => '',
    'width' => '300',
    'height' => '200',
    'loading' => 'lazy',
    'decoding' => 'async',
    'fetchpriority' => null,
    'class' => '',
    'fallback' => '/hero.png'
])

<img src="{{ $src ?: $fallback }}" 
     alt="{{ $alt }}" 
     loading="{{ $loading }}"
     width="{{ $width }}"
     height="{{ $height }}"
     decoding="{{ $decoding }}"
     @if($fetchpriority) fetchpriority="{{ $fetchpriority }}" @endif
     @if($class) class="{{ $class }}" @endif
     {{ $attributes }}>