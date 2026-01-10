@props(['concern_title', 'models'])

<section class="catalog-models section">
  <div class="container">
    <h2 class="h2">Другие модели {{ $concern_title }}</h2>

    <div class="catalog-models__wrap">
      @foreach ($models as $car_model)
        @php
          $img = trim((string) ($car_model->image ?? ''));

          // дефолт
          $noImage = asset('images/mark/no-image.png');

          // 1) пусто/дефолт
          if ($img === '' || $img === 'default') {
            $imgUrl = $noImage;

          // 2) абсолютная ссылка
          } elseif (preg_match('~^https?://~i', $img)) {
            $imgUrl = $img;

          // 3) если уже /storage/...
          } elseif (str_starts_with($img, '/storage/')) {
            $imgUrl = $img;

          // 4) storage public disk: uploads/... / cars/... / etc
          } else {
            // убираем ведущий /
            $img = ltrim($img, '/');
            $imgUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($img);
          }
        @endphp

        <a href="{{ route('car_model.show', [$car_model->car_make->slug, $car_model->slug]) }}"
           class="car-model-card">
          <div class="car-model-card__image">
            <img src="{{ $imgUrl }}"
                 alt="Логотип {{ $car_model->title }}"
                 loading="lazy"
                 onerror="this.onerror=null;this.src='{{ $noImage }}';" />
          </div>

          <div class="car-model-card__info">
            <h3 class="car-model-card__title">{{ $car_model->title }}</h3>
            <div class="car-model-card__count">{{ $car_model->getGenerationsCount() }} поколений</div>
            <div class="car-model-card__years">
              ({{ $car_model->getFirstYear() }}-{{ $car_model->getLastYear() }})
            </div>
          </div>
        </a>
      @endforeach
    </div>
  </div>
</section>
