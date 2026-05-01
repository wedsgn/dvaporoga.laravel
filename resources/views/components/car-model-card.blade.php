@props(['car_make', 'car_model'])

<a href="{{ route('car_model.show', [$car_make->slug, $car_model->slug]) }}" class="catalog-model-card">
  <div class="catalog-model-card__media">
    @if (filled($car_model->image_url))
      <img
        src="{{ $car_model->image_url }}"
        alt="Модель {{ $car_model->title }}"
        loading="lazy" />
    @endif
  </div>

  <h3 class="catalog-model-card__title">{{ $car_model->title }}</h3>
</a>
