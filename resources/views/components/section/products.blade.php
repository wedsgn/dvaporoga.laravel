<section class="products-section" id='prices'>
    <div class="container">
        <div class="products-section__top">
            <h2 class="h2">
                <span>Фиксированная</span> <br />
                цена на все модели
            </h2>
            <p>
                Благодаря современным европейским станкам наши детали подойдут к вашей
                машине, как родные. Цены на все изготавливаемые нами пороги -
                фиксированная и не зависит от того, какого класса ваш авто.
            </p>
        </div>

        <div class="products-wrap">
            @foreach ($items as $item)
                <x-product-card title="{{ $item->title }}" material="{{ $item->material }}"
                    thickness="{{ $item->metal_thickness }}" side="{{ $item->side }}" />
            @endforeach
        </div>
    </div>
</section>
