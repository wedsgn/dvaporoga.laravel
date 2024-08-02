@props(['generations', 'car_make', 'car_model'])

@foreach($generations as $years => $models)
<div class="car-generation">
  <div class="car-generation__info">
    <div class="car-generation__years">{{ $years }}</div>
    @php $count = count($models); $i = 1; @endphp
    @foreach($models as $model)
        @if ($count > 1 && $i < $count)
            {{ $model->generation }} /
        @else
            {{ $model->generation }}
        @endif
        @php $i++; @endphp
    @endforeach
  </div>

  <div class="car-generation__models">
    @foreach($models as $model)
      <a href="{{ route('car_generation.show', [$car_make, $car_model, $model->slug]) }}" class="car-generation__model">
        <div class="car-generation__model_image">
          <div class="car-generation__model_image">
              @if ($model->image === 'default')
                <img src="{{ asset('images/mark/no-image.png') }}" alt="Изображения нет" />
              @else
                <img src="{{ asset('storage') . '/' . $model->image }}" alt="Логотип {{ $model->title }}" />
              @endif
          </div>
        </div>

        <h3 class="car-generation__model_title">{{ $model->body }}</h3>
      </a>
    @endforeach
  </div>
</div>
@endforeach
