<a href="{{ $link }}" class="mark">
  <div class="mark-image">
    @if ($image === 'default')
      <img src="{{ asset('images/mark/' . $slug . '.png') }}" alt="{{ $slug }}" />
    @else
      <img src="{{ asset('storage') . '/' . $image }}" alt="Логотип {{ $title }}" />
    @endif
  </div>
  <div class="mark-info">
    <h3 class="mark-title">{{ $title }}</h3>
    {{-- <p class="mark-count">({{ $count }} моделей)</p> --}}
  </div>
</a>
