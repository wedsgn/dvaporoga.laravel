<section class="marks-section section">
    <div class="container">
        <h2 class="h2">Выберите автозапчасти по марке</h2>

        <div class="mark__wrap">
            @foreach ($items as $item)
                <x-concern-card title="{{ $item->title }}" image="{{ $item->image }}" :link="route('car_make.show', $item->slug)"
                    :count="$item->car_models->count()" />
            @endforeach
        </div>
        <a href="{{ route('catalog') }}" class="btn mark-section-btn">Все марки</a>
    </div>
</section>
