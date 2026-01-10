@props(['generations', 'car_make', 'car_model'])
@foreach($generations as $generation => $models)

<div class="car-generation">
  <div class="car-generation__info">
    <div class="car-generation__years">
      @if ($models->first()->body)
       {{ $models->first()->body }} /
      @endif
        {{ $generation }}
    </div>
    @php $count = count($models); $i = 1; @endphp
    @php $prev_years = null; @endphp
    @foreach($models as $model)
        @if ($prev_years !== $model->years)
            {{ $model->years }}
            @php $prev_years = $model->years; @endphp
        @endif
    @endforeach
  </div>

  <div class="car-generation__models">
    @foreach($models as $model)
      <a href="{{ route('car_generation.show', [$car_make, $car_model, $model->slug]) }}" class="car-generation__model">
        <div class="car-generation__model_image">
          <div class="car-generation__model_image">
@if ($model->image_url)
  <img src="{{ $model->image_url }}" alt="Логотип {{ $model->title }}" />
@else
  <img src="{{ asset('images/mark/no-image.png') }}" alt="Изображения нет" />
@endif
          </div>
        </div>

        <h3 class="car-generation__model_title">{{ $model->title }}</h3>
      </a>
    @endforeach
  </div>
</div>
@endforeach
