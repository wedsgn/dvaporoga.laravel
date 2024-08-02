@props(['title', 'image' => 'no image default', 'link' => 'link', 'count'])

<a href="{{ $link }}" class="mark">
    <div class="mark-image">
        @if ($image === 'default')
            <img src="{{ asset('images/mark/no-image.png') }}" alt="Изображения нет" />
        @else
            <img src="{{ asset('storage') . '/' . $image }}" alt="Логотип {{ $title }}" />
        @endif
    </div>
    <div class="mark-info">
        <h3 class="mark-title">{{ $title }}</h3>
        <p class="mark-count">({{ $count }} моделей)</p>
    </div>
</a>
