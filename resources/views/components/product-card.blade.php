@props([
    'title' => 'Ремонтный порог',
    'material' => 'Холодная сталь',
    'thickness' => '1мм',
    'side' => 'Левый+Правый',
])

<div class="product">
    <div class="product-image">
        <img src="/images/product/porog.png" alt="Фото {{ $title }}" />
    </div>

    <h3 class="product-title">{{ $title }}</h3>

    <div class="product-info">
        <ul class="product-list">
            <!-- item -->
            <li>
                <div class="product-info__item">
                    <div class="product-info__item_top">
                        <p class="product-info__item_title">Материал:</p>
                        <div class="product-info__item_value">{{ $material }}</div>
                    </div>
                </div>
            </li>

            <!-- item -->
            <li>
                <div class="product-info__item">
                    <div class="product-info__item_top">
                        <p class="product-info__item_title">Толщина металла:</p>
                        <div class="product-info__item_value">{{ $thickness }}</div>
                    </div>
                </div>
            </li>

            <!-- item -->
            <li>
                <div class="product-info__item">
                    <div class="product-info__item_top">
                        <p class="product-info__item_title">Сторона:</p>
                        <div class="product-info__item_value">{{ $side }}</div>
                    </div>
                </div>
            </li>
        </ul>

        <button class="btn product-btn" data-micromodal-trigger="modal-1">Заказать сейчас</button>
    </div>
</div>
