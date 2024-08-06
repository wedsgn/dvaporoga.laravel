@props([
    'part' => $part,
])

<div class="product">
    <div class="product-image">
        @if ($part->image === 'default')
            <img src="{{ asset('images/mark/no-image.png') }}" alt="Изображения нет" />
        @else
            <img src="{{ asset('storage') . '/' . $part->image }}" alt="Логотип {{ $part->title }}" />
        @endif
    </div>

    <h3 class="product-title">{{ $part->title }}</h3>

    <div class="product-info">
        <ul class="product-list">
            <!-- item -->
            @if ($part->material)
                <li>
                    <div class="product-info__item">
                        <div class="product-info__item_top">
                            <p class="product-info__item_title">Материал:</p>
                            <div class="product-info__item_value">{{ $part->material }}</div>
                        </div>
                    </div>
                </li>
            @endif

            <!-- item -->
            @if ($part->metal_thickness)
                <li>
                    <div class="product-info__item">
                        <div class="product-info__item_top">
                            <p class="product-info__item_title">Толщина металла:</p>
                            <div class="product-info__item_value">{{ $part->metal_thickness }}</div>
                        </div>
                    </div>
                </li>
            @endif

            <!-- item -->
            @if ($part->side)
                <li>
                    <div class="product-info__item">
                        <div class="product-info__item_top">
                            <p class="product-info__item_title">Сторона:</p>
                            <div class="product-info__item_value">{{ $side }}</div>
                        </div>
                    </div>
                </li>
            @endif

        </ul>

        <button class="btn product-btn" data-micromodal-trigger="modal-1" data-product-id="{{ $part->id }}">Заказать сейчас</button>
    </div>
</div>
