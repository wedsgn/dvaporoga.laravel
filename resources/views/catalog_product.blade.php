@extends('layouts.front')

@section('content')
    <main>

        {{ Breadcrumbs::render('car_generation.show', $car_make, $car_model, $car) }}

        <section class="catalog-page-section">
            <div class="container">
                <div class="product-page-top">
                    <h1 class="h1 product-page__title">
                        Каталог кузовных запчастей для автомобилей <br>{{ $car->title }}
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
                            <div class="product-part" data-prices="{{ json_encode($part->prices) }}"
                                data-item="{{ json_encode($part) }}">
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


                                    <input type="hidden" class="product-part__id" value="{{ $part->id }}">

                                    <div class="product-part__info_wrap">
                                        @if ($part->steel_types->count() > 0)
                                            <div class="product-part__info_item">
                                                <p class="product-part__info_item_title">Материал:</p>
                                                {{-- <p class="product-part__info_item_value">{{ $part->material }}</p> --}}
                                                <select class="form-select steel-select" name="steel_type_id"
                                                    id="steel_type_id">
                                                    @foreach ($part->steel_types as $steel_type)
                                                        <option value="{{ $steel_type->id }}"
                                                            {{ $part->steel_type_id == $steel_type->id ? 'selected' : '' }}>
                                                            {{ $steel_type->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif

                                        @if ($part->thicknesses->count() > 0)
                                            <div class="product-part__info_item">
                                                <p class="product-part__info_item_title">Толщина:</p>
                                                {{-- <p class="product-part__info_item_value">{{ $part->metal_thickness }}</p> --}}
                                                <select class="form-select thickness_select" name="thickness_id"
                                                    id="thickness_id">
                                                    @foreach ($part->thicknesses as $thickness)
                                                        <option value="{{ $thickness->id }}"
                                                            {{ $part->thickness_id == $thickness->id ? 'selected' : '' }}>
                                                            {{ $thickness->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                        @if ($part->types->count() > 0)
                                            <div class="product-part__info_item">
                                                <p class="product-part__info_item_title">Тип:</p>
                                                {{-- <p class="product-part__info_item_value">Стандартный</p> --}}
                                                <select class="form-select type-selector" name="type_id" id="type_id">
                                                    @foreach ($part->types as $type)
                                                        <option value="{{ $type->id }}"
                                                            {{ $part->type_id == $type->id ? 'selected' : '' }}>
                                                            {{ $type->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif

                                        @if ($part->sizes->count() > 0)
                                            <div class="product-part__info_item">
                                                <p class="product-part__info_item_title">Размер:</p>
                                                {{-- <p class="product-part__info_item_value">{{ $part->size }} </p> --}}
                                                <select class="form-select size-selector" name="size_id" id="size_id">
                                                    @foreach ($part->sizes as $size)
                                                        <option value="{{ $size->id }}"
                                                            {{ $part->size_id == $size->id ? 'selected' : '' }}>
                                                            {{ $size->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="product-part__bottom">
                                        <div class="product-part__price">
                                            <p class="product-part__price-price">Цена:</p>
                                            <div class="product-part__price_num product-price"><span></span>
                                                руб</div>
                                        </div>

                                        <button class="btn">Добавить в заказ</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="product-parts__total">
                        <div class="product-parts__total_head">
                            <h4 class="product-parts__total_title">Ваш заказ</h4>

                            <button class="product-parts__total_clear">Очистить</button>
                        </div>

                        <div class="total-form">
                            <div class="total-form__car">
                                <div class="total-form__car_title">Автомобиль:</div>
                                <div class="total-form__car_value">{{ $car_make->title }} {{ $car_model->title }}
                                    {{ $car->years }}</div>
                            </div>

                            <div class="total-form__parts">
                                <div class="total-form__empty">
                                    Добавьте запчасти в заказ
                                </div>
                            </div>

                            <div class="total-form__total">
                                <div class="total-form__total_title">Итого:</div>
                                <div class="total-form__total_value"><span>0</span> руб.</div>
                            </div>
                        </div>

                        <form class="cart-form" id="cart-form">
                            @csrf
                            <input type="text" placeholder="Имя" class="input" name="name" required />
                            <input type="tel" placeholder="+7 (___) ___ __ __" class="input" name="phone" required />
                            <input type="hidden" class="product-form__price" name="total_price" value="">
                            <input type="hidden" class="product-form__array" name="data" value="">
                            <input type="hidden" name="car"
                                value="{{ $car_make->title }} {{ $car_model->title }} {{ $car->years }}">
                            <input type="hidden" name="form_id" value="Форма каталога">
                            <button class="btn lg" type="submit" id="indexHeroFormSubmit">Отправить</button>

                            <p class="copyright">
                                Нажимая кнопку “Отправить” вы соглашаетесь с нашей
                                <a href="/Политика_в_области_обработки_персональных_данных.pdf" target="_blank"> политикой
                                    конфиденциальности </a>
                            </p>
                        </form>

                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        const cartForm = document.getElementById('cart-form');
        const products = document.querySelectorAll('.product-part');

        const cartItemsPlace = document.querySelector(".total-form__parts");
        const totalPriceInput = document.querySelector(".product-form__price");
        const arrayFormInput = document.querySelector(".product-form__array");
        const clearCart = document.querySelector(".product-parts__total_clear");
        const totalPriceDiv = document.querySelector(".total-form__total_value span");

        let selectedItems = [];
        let totalPrice = 0;
        let checkedProducts = []

        clearCart?.addEventListener("click", () => {
            selectedItems = [];
            totalPrice = 0;
            checkSum();
            pushArray();
            cartItemsPlace.insertAdjacentHTML(
                "afterbegin",
                `<div class="total-form__empty">Добавьте запчасти в заказ</div>`
            );
        });


        const checkSum = () => {
            totalPriceDiv.innerHTML = totalPrice;
            totalPriceInput.value = totalPrice;
            arrayFormInput.value = JSON.stringify(selectedItems);
        };

        const pushArray = () => {
            cartItemsPlace.innerHTML = "";
            selectedItems.forEach((item) => {
                cartItemsPlace.insertAdjacentHTML(
                    "afterbegin",
                    `<div class="total-form__part">
            <p class="total-form__part_title">${item.title}</p>
            <p class="total-form__part_price">${item.price} р.</p>
            <p class="total-form__part_price" style="display: none">${item}</p>
          </div>`
                );
            });
        };


        products.forEach((item, idx) => {
            const addBtn = item.querySelector(".btn");
            addBtn.addEventListener("click", () => {
                const title = item.querySelector(".product-part__title").innerHTML;
                const itemId = item.querySelector(".product-part__id").value;
                const price = item.querySelector(
                    ".product-part__price_num span"
                ).innerHTML;

                const itemIndex = selectedItems.findIndex(
                    (item) => item.id === itemId
                );

                if (itemIndex === -1) {
                    selectedItems.push({
                        id: itemId,
                        data: item.getAttribute("data-item"),
                        title: title,
                        price: price
                    });
                    totalPrice += +price;
                    addBtn.innerHTML = "Убрать из заказа";
                } else {
                    totalPrice -= +selectedItems[itemIndex].price;
                    selectedItems.splice(itemIndex, 1);
                    addBtn.innerHTML = "Добавить в заказ";
                }
                checkSum();
                pushArray();
            });
        });

        if (products) {
            products.forEach(product => {
                const data = product.getAttribute("data-item");
                const res = JSON.parse(data);

                const steelSelector = product.querySelector(".steel-select");
                const thicknessSelector = product.querySelector(".thickness_select");
                const typeSelector = product.querySelector(".type-selector");
                const sizeSelector = product.querySelector(".size-selector");
                const priceDeiv = product.querySelector(".product-price span");


                let options = {
                    size_id: sizeSelector.value,
                    steel_type_id: steelSelector.value,
                    thickness_id: thicknessSelector.value,
                    type_id: typeSelector.value,
                };

                const getPrice = () => {
                    const price = res.prices.find((item) => {
                        return (
                            item.size_id == options.size_id &&
                            item.steel_type_id == options.steel_type_id &&
                            item.thickness_id == options.thickness_id &&
                            item.type_id == options.type_id
                        );
                    });




                    if (price) {
                        priceDeiv.innerHTML = price.one_side;

                    }
                };



                steelSelector.addEventListener("change", function(e) {
                    options.steel_type_id = +e.target.value;
                    getPrice();
                });

                thicknessSelector.addEventListener("change", function(e) {
                    options.thickness_id = +e.target.value;
                    getPrice("thickness");
                });

                typeSelector.addEventListener("change", function(e) {
                    options.type_id = +e.target.value;
                    getPrice();
                });
                sizeSelector.addEventListener("change", function(e) {
                    options.size_id = +e.target.value;
                    getPrice();
                });

                priceDeiv.innerHTML = "";
                getPrice();
            });
        }

        cartForm.addEventListener('submit', async function(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);

            const response = await fetch("{{ route('request_product.store') }}", {
                method: 'POST',
                body: formData,
            })

            if (response.ok) {
                form.reset();
                MicroModal.show('modal-2');
                setTimeout(() => {
                    MicroModal.close('modal-2');
                }, 3000);
            } else {
                throw new Error('Ошибка отправки');
            }

        });
    </script>
@endsection
