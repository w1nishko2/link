{{-- Секция Галерея --}}
<section class="gallery" id="gallery" aria-label="Галерея работ">
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
            @if(!empty($galleryBlocks))
                <!-- Swiper галерея -->
                <div class="gallery-swiper swiper">
                    <div class="swiper-wrapper">
                        {{-- Дефолтный блок для добавления изображения (только для владельца) --}}
                        @if ($currentUser && $currentUser->id === $pageUser->id)
                            <div class="swiper-slide">
                                <a href="{{ route('admin.gallery.create', $currentUser->id) }}" class="owner-default-block gallery-add">
                                    <div class="owner-default-icon"></div>
                                    <div class="owner-default-text">
                                        <div class="owner-default-title">Добавить фото</div>
                                        <div class="owner-default-subtitle">Покажите свои работы</div>
                                    </div>
                                </a>
                            </div>
                        @endif

                        @foreach($galleryBlocks as $index => $block)
                            @foreach ($block['images'] as $image)
                                <div class="swiper-slide">
                                    <figure class="gallery-item editable-item" data-bs-toggle="modal" data-bs-target="#galleryModal"
                                        data-image="{{ $image['src'] }}" data-alt="{{ $image['alt'] }}">
                                        <img src="{{ $image['src'] }}" 
                                             alt="{{ $image['alt'] ?: 'Работа из портфолио ' . $pageUser->name }}" 
                                             loading="lazy" 
                                             width="300"
                                             height="200"
                                             decoding="async"
                                             itemscope 
                                             itemtype="https://schema.org/ImageObject">
                                        <div class="gallery-item-overlay">
                                            <figcaption class="gallery-item-text">{{ $image['alt'] ?: 'Портфолио' }}</figcaption>
                                        </div>
                                    </figure>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                    
                 
                </div>
            @else
                {{-- Показываем дефолтный блок или сообщение в зависимости от владельца --}}
                @if ($currentUser && $currentUser->id === $pageUser->id)
                    <div class=" justify-content-center">
                        <a href="{{ route('admin.gallery.create', $currentUser->id) }}" class="owner-default-block gallery-add" style="max-width: 400px;">
                            <div class="owner-default-icon"></div>
                            <div class="owner-default-text">
                                <div class="owner-default-title">Добавить фото</div>
                                <div class="owner-default-subtitle">Покажите свои работы</div>
                            </div>
                        </a>
                    </div>
                @else
                    <div class="text-center">
                        <p class="text-muted">Галерея пуста</p>
                    </div>
                @endif
            @endif
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