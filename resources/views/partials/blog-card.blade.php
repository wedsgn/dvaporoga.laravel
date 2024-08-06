<section class="blog-cards-catalog" id="blogCatalog">
    <div class="container">
        @if ($blogs->count() > 0)
            <div class="blog-cards__wrap">
                @foreach ($blogs as $blog)
                    <x-blog-card :item="$blog" />
                @endforeach
            </div>
        @else
            <div class="not-found-section">
                <p>По вашему запросу ничего не найдено</p>
            </div>
        @endif
    </div>
</section>
