<section class="products-section" id='prices'>
    <div class="container">
        <div class="products-section__top">
            <h2 class="h2">
                <span>Фиксированная</span> <br />
                цена на все модели
            </h2>
            <p>
                Благодаря современному европейскому оборудованию и точности изготовления мы производим запчасти с
                геометрией, полностью повторяющей оригинал.
                <br>
                <br>
                Фиксированные цены на все кузовные запчасти, которые не зависят от марки или типа вашего
                авто.
            </p>
        </div>

        <div class="products-wrap">
            @foreach ($items as $item)
                <x-product-card :part="$item" />
            @endforeach
        </div>
    </div>
</section>
