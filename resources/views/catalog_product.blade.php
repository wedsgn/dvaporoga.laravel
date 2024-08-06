@extends('layouts.front')

@section('content')
    <main>

        {{ Breadcrumbs::render('car_generation.show', $car_make, $car_model, $car) }}

        <section class="catalog-page-section">
            <div class="container">
                <div class="product-page-top">
                    <h1 class="h1 product-page__title">
                        {{ $car->title }} {{ $car->years }}
                    </h1>
                    <p class="product-page__description">
                        {{ $car->description }}
                    </p>
                </div>
            </div>
        </section>

        <section class="product-parts-section">
            <div class="container">
                <div class="product-parts-section__wrap">
                    <div class="product-parts__list">
                        @foreach ($products as $part)
                            <div class="product-part">
                                <div class="product-part__image">
                                    @if ($part->image === 'default')
                                        <img src="{{ asset('images/mark/no-image.png') }}" alt="Изображения нет" />
                                    @else
                                        <img src="{{ asset('storage') . '/' . $part->image }}"
                                            alt="Фото {{ $part->title }}" />
                                    @endif
                                </div>

                                <div class="product-part__info">
                                    <h3 class="product-part__title">
                                        {{ $part->title }}
                                    </h3>

                                    <div class="product-part__info_wrap">
                                        @if ($part->material)
                                            <div class="product-part__info_item">
                                                <p class="product-part__info_item_title">Материал:</p>
                                                <p class="product-part__info_item_value">{{ $part->material }}</p>
                                            </div>
                                        @endif


                                        <div class="product-part__info_item">
                                            <p class="product-part__info_item_title">Профиль:</p>
                                            <p>Стандартный</p>
                                        </div>

                                        @if ($part->metal_thickness)
                                            <div class="product-part__info_item">
                                                <p class="product-part__info_item_title">Толщина:</p>
                                                <p>{{ $part->metal_thickness }}</p>
                                            </div>
                                        @endif



                                        @if ($part->side)
                                            <div class="product-part__info_item">
                                                <p class="product-part__info_item_title">Сторона:</p>
                                                <p>{{ $part->side }}</p>
                                            </div>
                                        @endif

                                        @if ($part->size)
                                            <div class="product-part__info_item">
                                                <p class="product-part__info_item_title">Размер:</p>
                                                <p>{{ $part->size }} мм</p>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="product-part__bottom">
                                        <div class="product-part__price">
                                            <p class="product-part__price-price">Цена:</p>
                                            <div class="product-part__price_num">{{ $part->price_one_side }} руб</div>

                                        </div>

                                        <button class="btn">Добавить в заказ</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="product-parts__total">
                        <h4 class="product-parts__total_title">Ваш заказ</h4>

                        <form class="total-form">
                            <div class="total-form__car">
                                <div class="total-form__car_title">Автомобиль:</div>
                                <div class="total-form__car_value"> {{ $car->title }} {{ $car->years }}</div>
                            </div>

                            <div class="total-form__parts">
                                <div class="total-form__part">
                                    <p class="total-form__part_title">asdasd</p>
                                </div>
                                <div class="total-form__part">
                                    <p class="total-form__part_title">asdasd</p>
                                </div>
                                <div class="total-form__part">
                                    <p class="total-form__part_title">asdasd</p>
                                </div>
                            </div>

                        </form>
                        <button class="btn product-parts__total_btn">Заказать</button>
                    </div>
                </div>
            </div>
        </section>



    </main>
@endsection
