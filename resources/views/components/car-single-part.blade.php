@props([
    'id' => null,
    'image' => '',
    'title' => '',
    'description' => '',
    'price' => '',
    'priceOld' => '',
    'link' => '',
    'alt' => '',
    'requestSource' => 'car',
    'requestCar' => '',
])

<div class="car-single-part">
    <div class="car-single-part__image">
        <img src="{{ $image }}" alt="{{ $alt ?: $title }}">
    </div>

    <div class="car-single-part__content">
        <div class="car-single-part__heading">
            <h3 class="car-single-part__title">{{ $title }}</h3>

            <div class="car-single-part__price-wrap">
                <div class="car-single-part__price{{ !empty($priceOld) ? ' car-single-part__price--discounted' : '' }}">
                    {{ $price }}
                </div>

                @if (!empty($priceOld))
                    <div class="car-single-part__price-old">{{ $priceOld }}</div>
                @endif
            </div>
        </div>

        @if (!empty($description))
            <p class="car-single-part__descr">{{ $description }}</p>
        @endif
    </div>

    <div class="car-single-part__btn">
        <button type="button" data-micromodal-trigger="modal-product" data-product-id="{{ $id ?? '' }}"
            data-product-title="{{ $title }}" data-product-price="{{ $price }}"
            data-product-price-old="{{ $priceOld }}" data-request-source="{{ $requestSource }}"
            data-request-car="{{ $requestCar }}">
            Заказать
        </button>
    </div>
</div>
