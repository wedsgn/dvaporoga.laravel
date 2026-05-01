@if ($car_makes->count() > 0)
  <div class="catalog-makes-grid">
    @foreach ($car_makes as $car_make)
      @php
        $fallbackImage = asset('images/mark/' . $car_make->slug . '.png');
        $imageUrl = empty($car_make->image) || $car_make->image === 'default'
            ? $fallbackImage
            : asset('storage/' . ltrim($car_make->image, '/'));
        $emptyImage = asset('images/no-image.jpg');
      @endphp

      <a href="{{ route('car_make.show', $car_make->slug) }}" class="catalog-make-card catalog-make-card--{{ $car_make->slug }}">
        <div class="catalog-make-card__media">
          <img
            src="{{ $imageUrl }}"
            alt="Логотип {{ $car_make->title }}"
            data-fallback="{{ $fallbackImage }}"
            data-empty="{{ $emptyImage }}"
            onerror="if (this.dataset.fallback && this.src !== this.dataset.fallback) { this.src = this.dataset.fallback; } else if (this.dataset.empty && this.src !== this.dataset.empty) { this.src = this.dataset.empty; } else { this.onerror = null; }"
          />
        </div>

        <h3 class="catalog-make-card__title">{{ $car_make->title }}</h3>
      </a>
    @endforeach
  </div>
@else
  <div class="catalog-makes-empty">
    По вашему запросу ничего не найдено
  </div>
@endif
