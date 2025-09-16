{{-- Компонент заголовка секции --}}
@props(['section' => null, 'defaultTitle' => '', 'defaultSubtitle' => '', 'headingLevel' => 'h2'])

@if((isset($section) && (!empty(trim($section->title ?? '')) || !empty(trim($section->subtitle ?? '')))) || (!isset($section) && ($defaultTitle || $defaultSubtitle)))
    <header class="section-header mb-4">
        @if(isset($section))
            @if(!empty(trim($section->title ?? '')))
                <{{ $headingLevel }}>{{ $section->title }}</{{ $headingLevel }}>
            @elseif($defaultTitle)
                <{{ $headingLevel }}>{{ $defaultTitle }}</{{ $headingLevel }}>
            @endif
            
            @if(!empty(trim($section->subtitle ?? '')))
                <p class="text-muted">{{ $section->subtitle }}</p>
            @elseif($defaultSubtitle)
                <p class="text-muted">{{ $defaultSubtitle }}</p>
            @endif
        @else
            @if($defaultTitle)
                <{{ $headingLevel }}>{{ $defaultTitle }}</{{ $headingLevel }}>
            @endif
            @if($defaultSubtitle)
                <p class="text-muted">{{ $defaultSubtitle }}</p>
            @endif
        @endif
    </header>
@endif