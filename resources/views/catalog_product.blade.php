@extends('layouts.front')

@section('content')
    <main>

        {{ Breadcrumbs::render('car_generation.show', $car_make, $car_model, $car) }}

        <section class="catalog-page-section">
            <div class="container">
                <div class="product-page-top">
                    <h1 class="h1 product-page__title">
                      {{ $car_make->title }} {{ $car_model->title }} {{ $car->title }} {{ $car->generation }}
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


                                    <input type="hidden" class="product-part__id" value="{{ $part->id }}">

                                    <div class="product-part__info_wrap">
                                      @if ($part->steel_types->count() > 0)
                                            <div class="product-part__info_item">
                                                <p class="product-part__info_item_title">Материал:</p>
                                                {{-- <p class="product-part__info_item_value">{{ $part->material }}</p> --}}
                                                <select class="form-select" name="steel_type_id" id="steel_type_id">
                                                    @foreach ($part->steel_types as $steel_type)
                                                        <option value="{{ $steel_type->id }}" {{ $part->steel_type_id == $steel_type->id ? 'selected' : '' }}>
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
                                          <select class="form-select" name="thickness_id" id="thickness_id">
                                            @foreach ($part->thicknesses as $thickness)
                                                <option value="{{ $thickness->id }}" {{ $part->thickness_id == $thickness->id ? 'selected' : '' }}>
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
                                            <select class="form-select" name="type_id" id="type_id">
                                              @foreach ($part->types as $type)
                                                  <option value="{{ $type->id }}" {{ $part->type_id == $type->id ? 'selected' : '' }}>
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
                                                <select class="form-select" name="size_id" id="size_id">
                                                  @foreach ($part->sizes as $size)
                                                      <option value="{{ $size->id }}" {{ $part->size_id == $size->id ? 'selected' : '' }}>
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
                                            <div class="product-part__price_num"><span>Сюда цена выводится</span>
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
                                <div class="total-form__car_value"> {{ $car->title }} {{ $car->years }}</div>
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
                            <input type="hidden" name="car" value="{{ $car->title }} {{ $car->years }}">
                            <input type="hidden" name="form_id" value="Форма каталога">
                            <button class="btn lg" type="submit" id="indexHeroFormSubmit">Отправить</button>

                            <p class="copyright">
                                Нажимая кнопку “Отправить” вы соглашаетесь с нашей
                                <a href="/Политика_в_области_обработки_персональных_данных.pdf" target="_blank"> политикой конфиденциальности </a>
                            </p>
                        </form>

                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        const cartForm = document.getElementById('cart-form');



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
