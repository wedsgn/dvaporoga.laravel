@props(['title', 'image' => 'no image default', 'link' => 'link', 'count'])

<a href="{{ $link }}" class="mark">
    <div class="mark-image">
        <img src="{{ $image }}" alt="Audi" />
    </div>
    <div class="mark-info">
        <h3 class="mark-title">{{ $title }}</h3>
        <p class="mark-count">({{ $count }} моделей)</p>
    </div>
</a>
