@props(['car_make', 'car_model'])

<a href="{{ route('car_model.show', [$car_make->slug, $car_model->slug]) }}" class="car-model-card">
    <div class="car-model-card__image">
        @if ($car_model->image === 'default')
            <img src="{{ asset('images/mark/no-image.png') }}" alt="Изображения нет" />
        @else
            <img src="{{ asset('storage') . '/' . $car_model->image }}" alt="Логотип {{ $car_model->title }}" />
        @endif
    </div>

    <div class="car-model-card__info">
        <h3 class="car-model-card__title">{{ $car_model->title }}</h3>
        <div class="car-model-card__count">{{ $car_model->getGenerationsCount() }} поколений</div>
        <div class="car-model-card__years">({{ $car_model->getFirstYear() }}-{{ $car_model->getLastYear() }})</div>
    </div>
</a>
