<section class="articles" id="articles" aria-label="Статьи блога">
    <div class="container">
        @if((isset($section) && (!empty(trim($section->title)) || !empty(trim($section->subtitle)))) || !isset($section))
        <header class="banners-header mb-4 ">
             @if(isset($section))
                @if(!empty(trim($section->title)))
                    <h2>{{ $section->title }}</h2>
                @endif
                
                @if(!empty(trim($section->subtitle)))
                    <p class="text-muted">{{ $section->subtitle }}</p>
                @endif
            @else
                <h2>{{ $currentUser && $currentUser->id === $pageUser->id ? 'Мои статьи' : 'Статьи от ' . $pageUser->name }}</h2>
                <p class="text-muted">Полезные материалы и советы</p>
            @endif
        </header>
        @endif

        <div class="articles-list">
            @if ($currentUser && $currentUser->id === $pageUser->id)
                <a href="{{ route('admin.articles.create', $currentUser->id) }}" class="owner-default-block article-add">
                    <div class="owner-default-icon"></div>
                    <div class="owner-default-text">
                        <div class="owner-default-title">Создать статью</div>
                        <div class="owner-default-subtitle">Поделитесь своими знаниями</div>
                    </div>
                </a>
            @endif

            @foreach($articles as $article)
                <article class="article-preview" itemscope itemtype="https://schema.org/Article" data-article-id="{{ $article->id }}">
                    <a href="{{ route('articles.show', ['username' => $pageUser->username, 'slug' => $article->slug]) }}"
                        class="article-item">
                        <div class="article-image">
                            @if ($article->image_path)
                                <img src="{{ asset('storage/' . $article->image_path) }}" 
                                     alt="{{ $article->title }}"
                                     loading="lazy" 
                                     width="300"
                                     height="200"
                                     decoding="async"
                                     itemprop="image">
                            @else
                                <img src="/hero.png" 
                                     alt="{{ $article->title }}" 
                                     loading="lazy" 
                                     width="300"
                                     height="200"
                                     decoding="async"
                                     itemprop="image">
                            @endif
                           
                        </div>
                        <div class="article-content">
                            
                            <h3 class="article-title" itemprop="headline">
                                {{ $article->title }}
                            </h3>
                            <p class="" itemprop="description">
                                {{ $article->excerpt }}
                            </p>
                            <div class="article-meta">
                                 <time class="" datetime="{{ $article->created_at->toISOString() }}" itemprop="datePublished">
                                <span>{{ $article->created_at->format('d') }}</span>
                                <span>{{ $article->created_at->format('M') }}</span>
                            </time>
                                <span class="article-author" itemprop="author" itemscope itemtype="https://schema.org/Person">
                                    <span itemprop="name">Автор: {{ $pageUser->name }}</span>
                                </span>
                                <span class="article-read-time">{{ $article->read_time }} мин чтения</span>
                            </div>
                        </div>
                    </a>
                </article>
            @endforeach

            {{-- Показываем информативную пустышку только если нет статей и нет дефолтного блока --}}
            @if($articles->count() === 0 && (!$currentUser || $currentUser->id !== $pageUser->id))
                <article class="article-preview placeholder-content">
                    <div class="article-item">
                        <div class="article-image">
                            <img src="/hero.png" 
                                 alt="Скоро здесь появятся статьи" 
                                 loading="lazy" 
                                 width="300"
                                 height="200"
                                 decoding="async">
                        </div>
                        <div class="article-content">
                            <h3 class="article-title">Статьи скоро появятся</h3>
                            <p class="">{{ $pageUser->name }} работает над наполнением этого раздела. Заходите позже, чтобы прочитать интересные материалы!</p>
                            <div class="article-meta">
                                <time class="">
                                    <span>{{ now()->format('d') }}</span>
                                    <span>{{ now()->format('M') }}</span>
                                </time>
                                <span class="article-author">
                                    <span>Автор: {{ $pageUser->name }}</span>
                                </span>
                                <span class="article-read-time">Ожидаем...</span>
                            </div>
                        </div>
                    </div>
                </article>
            @endif
        </div>
        @if($articles->count() > 0)
        <div class="articles-footer text-center mt-5">
            <a href="{{ route('articles.index', ['username' => $pageUser->username]) }}" class="btn btn-outline-primary">Все статьи</a>
        </div>
        @endif
    </div>
</section>