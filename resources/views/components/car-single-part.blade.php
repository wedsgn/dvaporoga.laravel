@props([
    'id' => null,
    'image' => '',
    'discount_percentage' => '',
    'title' => '',
    'description' => '',
    'price' => '',
    'priceOld' => '',
    'link' => '',
    'alt' => '',
])

<div class="car-single-part">
    @if (!empty($discount_percentage))
        <div class="car-single-part__label">{{ $discount_percentage }}</div>
    @endif

    <div class="car-single-part__image">
        <img src="{{ $image }}" alt="{{ $alt ?: $title }}">
    </div>

    <h3 class="car-single-part__title">
        {{ $title }}
    </h3>

    <p class="car-single-part__descr">
        {{ $description }}
    </p>

    <div class="car-single-part__bottom">
        <div class="car-single-part__price-wrap">
            <div class="car-single-part__price">{{ $price }}</div>

            @if (!empty($priceOld))
                <div class="car-single-part__price-old">{{ $priceOld }}</div>
            @endif
        </div>

        <div class="car-single-part__btn">
            <button type="button" data-micromodal-trigger="modal-product" data-product-id="{{ $id ?? '' }}"
                data-product-title="{{ $title }}" data-product-price="{{ $price }}"
                data-product-price-old="{{ $priceOld }}">
                Заказать
            </button>
        </div>
    </div>
</div>
