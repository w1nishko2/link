@foreach($articles as $article)
<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
    <article class="card h-100 article-card" itemscope itemtype="https://schema.org/Article">
        <a href="{{ route('articles.show', ['username' => $article->user->username, 'slug' => $article->slug]) }}" 
           class="text-decoration-none">
            <!-- Изображение статьи -->
            <div class="card-img-top position-relative" style="height: 200px; overflow: hidden;">
                @if ($article->image_path)
                    <img src="{{ asset('storage/' . $article->image_path) }}" 
                         alt="{{ $article->title }}"
                         class="w-100 h-100 object-fit-cover"
                         loading="lazy" 
                         itemprop="image">
                @else
                    <img src="/hero.png" 
                         alt="{{ $article->title }}" 
                         class="w-100 h-100 object-fit-cover"
                         loading="lazy" 
                         itemprop="image">
                @endif
                
                <!-- Дата публикации -->
                <div class="position-absolute top-0 end-0 m-3">
                    <time class="badge bg-dark bg-opacity-75" 
                          datetime="{{ $article->created_at->toISOString() }}" 
                          itemprop="datePublished">
                        {{ $article->created_at->format('d.m.Y') }}
                    </time>
                </div>
            </div>

            <!-- Содержимое карточки -->
            <div class="card-body d-flex flex-column">
                <h3 class="card-title h5 fw-bold text-dark mb-3" itemprop="headline">
                    @if(!empty($search))
                        {!! str_ireplace($search, '<mark>' . $search . '</mark>', e($article->title)) !!}
                    @else
                        {{ $article->title }}
                    @endif
                </h3>
                
                @if($article->excerpt)
                    <p class="card-text text-muted flex-grow-1" itemprop="description">
                        @if(!empty($search))
                            {!! str_ireplace($search, '<mark>' . $search . '</mark>', e(Str::limit($article->excerpt, 120))) !!}
                        @else
                            {{ Str::limit($article->excerpt, 120) }}
                        @endif
                    </p>
                @endif

                <!-- Автор и мета-информация -->
                <div class="mt-auto">
                    <!-- Информация об авторе -->
                    <div class="d-flex align-items-center mb-2">
                        <div class="author-avatar me-2">
                            @if($article->user->avatar)
                                <img src="{{ asset('storage/' . $article->user->avatar) }}" 
                                     alt="{{ $article->user->name }}" 
                                     class="rounded-circle"
                                     style="width: 32px; height: 32px; object-fit: cover;">
                            @else
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 32px; height: 32px; font-size: 14px;">
                                    {{ strtoupper(substr($article->user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1" itemprop="author" itemscope itemtype="https://schema.org/Person">
                            <div class="fw-medium small text-dark">
                                <span itemprop="name">
                                    @if(!empty($search))
                                        {!! str_ireplace($search, '<mark>' . $search . '</mark>', e($article->user->name)) !!}
                                    @else
                                        {{ $article->user->name }}
                                    @endif
                                </span>
                            </div>
                            <div class="text-muted small">{{ '@' . $article->user->username }}</div>
                        </div>
                    </div>
                    
                    <!-- Мета-информация -->
                    <div class="d-flex justify-content-between align-items-center text-muted small">
                        <span>
                            <i class="bi bi-calendar3"></i>
                            {{ $article->created_at->format('d.m.Y') }}
                        </span>
                        @if($article->read_time)
                            <span>
                                <i class="bi bi-clock"></i>
                                {{ $article->read_time }} мин
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </a>
    </article>
</div>
@endforeach
