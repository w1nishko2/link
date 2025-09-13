{{-- Секция Галерея --}}
<section class="gallery" aria-label="Галерея работ">
    <div class="container">
        @if((isset($section) && (!empty(trim($section->title)) || !empty(trim($section->subtitle)))) || !isset($section))
        <header class="gallery-header mb-4">
            @if(isset($section))
                @if(!empty(trim($section->title)))
                    <h2>{{ $section->title }}</h2>
                @endif
                
                @if(!empty(trim($section->subtitle)))
                    <p class="text-muted">{{ $section->subtitle }}</p>
                @endif
            @else
                <h2>{{ $currentUser && $currentUser->id === $pageUser->id ? 'Моя галерея' : 'Галерея работ ' . $pageUser->name }}</h2>
                <p class="text-muted">Портфолио и примеры работ</p>
            @endif
        </header>
        @endif
     
        <div class="gallery-wrapper">
            <div class="gallery-grid" id="galleryGrid" role="region" aria-label="Галерея изображений">
                @forelse($galleryBlocks as $index => $block)
                    <div class="gallery-block type-{{ $block['type'] }}">
                        @foreach ($block['images'] as $image)
                            <figure class="gallery-item" data-bs-toggle="modal" data-bs-target="#galleryModal"
                                data-image="{{ $image['src'] }}" data-alt="{{ $image['alt'] }}">
                                <img src="{{ $image['src'] }}" alt="{{ $image['alt'] ?: 'Работа из портфолио ' . $pageUser->name }}" loading="lazy" itemscope itemtype="https://schema.org/ImageObject">
                                <div class="gallery-item-overlay">
                                    <figcaption class="gallery-item-text">{{ $image['alt'] ?: 'Портфолио' }}</figcaption>
                                </div>
                            </figure>
                        @endforeach
                    </div>
                @empty
                    <div class="text-center">
                        <p class="text-muted">Галерея пуста</p>
                    </div>
                @endforelse
            </div>

            <!-- Навигационные кнопки -->
            <button class="gallery-nav gallery-nav-left" id="galleryPrev" type="button">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </button>
            <button class="gallery-nav gallery-nav-right" id="galleryNext" type="button">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Модальное окно для просмотра изображений -->
    <div class="modal fade" id="galleryModal" tabindex="-1" aria-labelledby="galleryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="galleryModalLabel">Просмотр изображения</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>
</section>