@props([
    'image' => '',
    'label' => '',
    'title' => '',
    'descr' => '',
    'price' => '',
    'priceOld' => '',
    'link' => '',
    'alt' => '',
])

<div class="car-single-part">
    @if (!empty($label))
        <div class="car-single-part__label">{{ $label }}</div>
    @endif

    <div class="car-single-part__image">
        <img src="{{ $image }}" alt="{{ $alt ?: $title }}">
    </div>

    <h3 class="car-single-part__title">
        {{ $title }}
    </h3>

    <p class="car-single-part__descr">
        {{ $descr }}
    </p>

    <div class="car-single-part__bottom">
        <div class="car-single-part__price-wrap">
            <div class="car-single-part__price">{{ $price }}</div>

            @if (!empty($priceOld))
                <div class="car-single-part__price-old">{{ $priceOld }}</div>
            @endif
        </div>

        <div class="car-single-part__btn">
            <button data-micromodal-trigger="modal-1">Заказать</button>
        </div>
    </div>
</div>
