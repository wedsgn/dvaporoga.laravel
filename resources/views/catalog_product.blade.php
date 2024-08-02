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
                                            alt="Логотип {{ $part->title }}" />
                                    @endif
                                </div>

                                <div class="product-part__info">
                                    <h3 class="product-part__title">
                                        {{ $part->title }}
                                    </h3>

                                    <div class="product-part__info_wrap">
                                        <div class="product-part__info_item">
                                            <p class="product-part__info_item_title">Профиль:</p>
                                            <p>Стандартный</p>
                                        </div>

                                        @if ($part->metal_thickness)
                                            <div class="product-part__info_item">
                                                <p class="product-part__info_item_title">Толщина:</p>
                                                <p>{{ $part->metal_thickness }} мм</p>
                                            </div>
                                        @endif

                                        @if ($part->material)
                                            <div class="product-part__info_item">
                                                <p class="product-part__info_item_title">Материал:</p>
                                                <p>{{ $part->material }}</p>
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
                                            <p>Цена</p>
                                            <div class="product-part__price_num">Цена одной стороны
                                                {{ $part->price_one_side }} руб
                                            </div>
                                            <div class="product-part__price_num --old">Цена за комплект
                                                {{ $part->price_set }} руб</div>
                                        </div>

                                        <button class="btn">Добавить в заказ</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="product-parts__total">
                        <h4 class="product-parts__total_title">Ваш заказ</h4>

                        <button class="btn product-parts__total_btn">Заказать</button>
                    </div>
                </div>
            </div>
        </section>

        {{-- <section class="catalog-models section">
          <div class="container">
            <h2 class="h2">Другие модели Audi</h2>
            <div class="catalog-models__wrap">
              <!-- Card -->
              <a href="#" class="car-model-card">
                <div class="car-model-card__image">
                  <img
                    src="/images/cars/audi-80.jpg"
                    alt="Название авто на картинке"
                  />
                </div>

                <div class="car-model-card__info">
                  <h3 class="car-model-card__title">80</h3>
                  <div class="car-model-card__count">5 поколений</div>
                  <div class="car-model-card__years">(1994-2022)</div>
                </div>
              </a>

              <!-- Card -->
              <a href="#" class="car-model-card">
                <div class="car-model-card__image">
                  <img
                    src="/images/cars/audi-80.jpg"
                    alt="Название авто на картинке"
                  />
                </div>

                <div class="car-model-card__info">
                  <h3 class="car-model-card__title">80</h3>
                  <div class="car-model-card__count">5 поколений</div>
                  <div class="car-model-card__years">(1994-2022)</div>
                </div>
              </a>

              <!-- Card -->
              <a href="#" class="car-model-card">
                <div class="car-model-card__image">
                  <img
                    src="/images/cars/audi-80.jpg"
                    alt="Название авто на картинке"
                  />
                </div>

                <div class="car-model-card__info">
                  <h3 class="car-model-card__title">80</h3>
                  <div class="car-model-card__count">5 поколений</div>
                  <div class="car-model-card__years">(1994-2022)</div>
                </div>
              </a>

              <!-- Card -->
              <a href="#" class="car-model-card">
                <div class="car-model-card__image">
                  <img
                    src="/images/cars/audi-80.jpg"
                    alt="Название авто на картинке"
                  />
                </div>

                <div class="car-model-card__info">
                  <h3 class="car-model-card__title">80</h3>
                  <div class="car-model-card__count">5 поколений</div>
                  <div class="car-model-card__years">(1994-2022)</div>
                </div>
              </a>

              <!-- Card -->
              <a href="#" class="car-model-card">
                <div class="car-model-card__image">
                  <img
                    src="/images/cars/audi-80.jpg"
                    alt="Название авто на картинке"
                  />
                </div>

                <div class="car-model-card__info">
                  <h3 class="car-model-card__title">80</h3>
                  <div class="car-model-card__count">5 поколений</div>
                  <div class="car-model-card__years">(1994-2022)</div>
                </div>
              </a>

              <!-- Card -->
              <a href="#" class="car-model-card">
                <div class="car-model-card__image">
                  <img
                    src="/images/cars/audi-80.jpg"
                    alt="Название авто на картинке"
                  />
                </div>

                <div class="car-model-card__info">
                  <h3 class="car-model-card__title">80</h3>
                  <div class="car-model-card__count">5 поколений</div>
                  <div class="car-model-card__years">(1994-2022)</div>
                </div>
              </a>

              <!-- Card -->
              <a href="#" class="car-model-card">
                <div class="car-model-card__image">
                  <img
                    src="/images/cars/audi-80.jpg"
                    alt="Название авто на картинке"
                  />
                </div>

                <div class="car-model-card__info">
                  <h3 class="car-model-card__title">80</h3>
                  <div class="car-model-card__count">5 поколений</div>
                  <div class="car-model-card__years">(1994-2022)</div>
                </div>
              </a>

              <!-- Card -->
              <a href="#" class="car-model-card">
                <div class="car-model-card__image">
                  <img
                    src="/images/cars/audi-80.jpg"
                    alt="Название авто на картинке"
                  />
                </div>

                <div class="car-model-card__info">
                  <h3 class="car-model-card__title">80</h3>
                  <div class="car-model-card__count">5 поколений</div>
                  <div class="car-model-card__years">(1994-2022)</div>
                </div>
              </a>

              <!-- Card -->
              <a href="#" class="car-model-card">
                <div class="car-model-card__image">
                  <img
                    src="/images/cars/audi-80.jpg"
                    alt="Название авто на картинке"
                  />
                </div>

                <div class="car-model-card__info">
                  <h3 class="car-model-card__title">80</h3>
                  <div class="car-model-card__count">5 поколений</div>
                  <div class="car-model-card__years">(1994-2022)</div>
                </div>
              </a>

              <!-- Card -->
              <a href="#" class="car-model-card">
                <div class="car-model-card__image">
                  <img
                    src="/images/cars/audi-80.jpg"
                    alt="Название авто на картинке"
                  />
                </div>

                <div class="car-model-card__info">
                  <h3 class="car-model-card__title">80</h3>
                  <div class="car-model-card__count">5 поколений</div>
                  <div class="car-model-card__years">(1994-2022)</div>
                </div>
              </a>

              <!-- Card -->
              <a href="#" class="car-model-card">
                <div class="car-model-card__image">
                  <img
                    src="/images/cars/audi-80.jpg"
                    alt="Название авто на картинке"
                  />
                </div>

                <div class="car-model-card__info">
                  <h3 class="car-model-card__title">80</h3>
                  <div class="car-model-card__count">5 поколений</div>
                  <div class="car-model-card__years">(1994-2022)</div>
                </div>
              </a>
            </div>
          </div>
        </section>



        <section class="faq-section section">
    <div class="container">
      <h2 class="h2">
        Часто <br />
        задаваемые <br />
        вопросы
      </h2>
      <div class="faq-wrap">
        <!--  -->
        <div class="faq-left">
          <p class="faq-description">
            Если не нашли ответ на нужный вопрос
            <a href="#">оставьте заявку</a> или свяжитесь с нами. Мы с
            удовольствием расскажем все подробнее и проконсультируем вас.
          </p>

          <button class="btn">Получить консультацию</button>
        </div>

        <!-- Right -->
        <div class="faq-right">
          <div class="faq-accordition">
            <!-- Item -->
            <div class="faq-item accordion active">
              <div class="faq-item__head accordion__intro">
                <p>У вас есть пороги на все модели авто?</p>
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="#1E1E1E"
                >
                  <path d="M5 9.07129L12.0711 16.1424L19.1421 9.07129" />
                </svg>
              </div>

              <div class="accordion__content">
                <div class="faq-item__content">
                  <p>
                    Наше производство оснащено современным оборудованием и
                    технологиями, что позволяет нам создавать ремонтные пороги,
                    соответствующие самым высоким стандартам качества. Мы
                    используем только качественные материалы и комплектующие, что
                    гарантирует долговечность и надёжность нашей продукции.
                  </p>
                </div>
              </div>
            </div>

            <!-- Item -->
            <div class="faq-item accordion">
              <div class="faq-item__head accordion__intro">
                <p>Что делать, если на мой автомобиль нет порогов в базе?</p>
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="#1E1E1E"
                >
                  <path d="M5 9.07129L12.0711 16.1424L19.1421 9.07129" />
                </svg>
              </div>

              <div class="accordion__content">
                <div class="faq-item__content">
                  <p>
                    Наше производство оснащено современным оборудованием и
                    технологиями, что позволяет нам создавать ремонтные пороги,
                    соответствующие самым высоким стандартам качества. Мы
                    используем только качественные материалы и комплектующие, что
                    гарантирует долговечность и надёжность нашей продукции.
                  </p>
                </div>
              </div>
            </div>

            <!-- Item -->
            <div class="faq-item accordion">
              <div class="faq-item__head accordion__intro">
                <p>Подойдут ли пороги к моему авто?</p>
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="#1E1E1E"
                >
                  <path d="M5 9.07129L12.0711 16.1424L19.1421 9.07129" />
                </svg>
              </div>

              <div class="accordion__content">
                <div class="faq-item__content">
                  <p>
                    Наше производство оснащено современным оборудованием и
                    технологиями, что позволяет нам создавать ремонтные пороги,
                    соответствующие самым высоким стандартам качества. Мы
                    используем только качественные материалы и комплектующие, что
                    гарантирует долговечность и надёжность нашей продукции.
                  </p>
                </div>
              </div>
            </div>

            <!-- Item -->
            <div class="faq-item accordion">
              <div class="faq-item__head accordion__intro">
                <p>Сколько будут стоить пороги на моё авто"?</p>
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="#1E1E1E"
                >
                  <path d="M5 9.07129L12.0711 16.1424L19.1421 9.07129" />
                </svg>
              </div>

              <div class="accordion__content">
                <div class="faq-item__content">
                  <p>
                    Наше производство оснащено современным оборудованием и
                    технологиями, что позволяет нам создавать ремонтные пороги,
                    соответствующие самым высоким стандартам качества. Мы
                    используем только качественные материалы и комплектующие, что
                    гарантирует долговечность и надёжность нашей продукции.
                  </p>
                </div>
              </div>
            </div>

            <!-- Item -->
            <div class="faq-item accordion">
              <div class="faq-item__head accordion__intro">
                <p>Подойдут ли пороги к моему авто?</p>
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="#1E1E1E"
                >
                  <path d="M5 9.07129L12.0711 16.1424L19.1421 9.07129" />
                </svg>
              </div>

              <div class="accordion__content">
                <div class="faq-item__content">
                  <p>
                    Наше производство оснащено современным оборудованием и
                    технологиями, что позволяет нам создавать ремонтные пороги,
                    соответствующие самым высоким стандартам качества. Мы
                    используем только качественные материалы и комплектующие, что
                    гарантирует долговечность и надёжность нашей продукции.
                  </p>
                </div>
              </div>
            </div>

            <!-- Item -->
            <div class="faq-item accordion">
              <div class="faq-item__head accordion__intro">
                <p>В течение какого времени я могу получить пороги?</p>
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="#1E1E1E"
                >
                  <path d="M5 9.07129L12.0711 16.1424L19.1421 9.07129" />
                </svg>
              </div>

              <div class="accordion__content">
                <div class="faq-item__content">
                  <p>
                    Наше производство оснащено современным оборудованием и
                    технологиями, что позволяет нам создавать ремонтные пороги,
                    соответствующие самым высоким стандартам качества. Мы
                    используем только качественные материалы и комплектующие, что
                    гарантирует долговечность и надёжность нашей продукции.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section> --}}


    </main>
@endsection
