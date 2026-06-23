@props(['generations', 'car_make', 'car_model'])

@foreach ($generations as $generation => $models)
  @php
    $normalizeCardText = function ($value) {
        $value = trim((string) $value);

        if ($value === '') {
            return '';
        }

        $value = preg_replace('/\s{2,}/u', ' ', $value) ?? $value;
        $value = preg_replace('/\s+([.,;:])/u', '$1', $value) ?? $value;

        return trim($value, " \t\n\r\0\x0B-–—,.;:");
    };

    $generationLabel = $normalizeCardText($generation);
    $yearsLabel = $models->pluck('years')->filter()->unique()->implode(', ');
    $generationTitle = implode(' / ', array_filter([$generationLabel, $yearsLabel]));

    $getModelCardTitle = function ($model) use ($normalizeCardText) {
        $bodyTitle = $normalizeCardText($model->body ?? '');

        if ($bodyTitle !== '') {
            return $bodyTitle;
        }

        return $normalizeCardText($model->title ?? '');
    };
  @endphp

  <div class="car-generation">
    @if ($generationTitle !== '')
      <div class="car-generation__years h2-small">{{ $generationTitle }}</div>
    @endif

    <div class="car-generation__models">
      @foreach ($models as $model)
        <a href="{{ route('car_generation.show', [$car_make, $car_model, $model->slug]) }}" class="car-generation__model">
          <div class="car-generation__model_image">
            @if ($model->image_url)
              <img src="{{ $model->image_url }}" alt="Логотип {{ $model->title }}" />
            @else
              <img src="{{ asset('images/mark/no-image.png') }}" alt="Изображения нет" />
            @endif
          </div>

          <h3 class="car-generation__model_title">{{ $getModelCardTitle($model) }}</h3>
        </a>
      @endforeach
    </div>
  </div>
@endforeach
