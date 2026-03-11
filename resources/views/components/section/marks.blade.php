<section class="marks-section catalog-concern">
    <div class="container" id="concernsCatalog">
        <div class="marks-layout">
            <div class="marks-header">
                <h2 class="h2">
                    или выберите деталь по марке
                </h2>
            </div>

            <a href="{{ route('catalog') }}" class="btn marks-catalog-btn">
                Каталог
            </a>

            <div class="mark__wrap catalog-concern__wrap">
                @foreach ($items as $item)
                    <x-concern-card
                        title="{{ $item->title }}"
                        :slug="$item->slug"
                        image="{{ $item->image }}"
                        :link="route('car_make.show', $item->slug)"
                        :count="$item->car_models->count()"
                    />
                @endforeach
            </div>
        </div>
    </div>
</section>
