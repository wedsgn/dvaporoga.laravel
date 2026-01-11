@extends('layouts.front')

@section('content')
  <main>
{{-- FEATURES = tags --}}
                        @if (($car->tags ?? collect())->isNotEmpty())
                            <div class="car-single-hero__features">
                                @foreach ($car->tags as $tag)
                                    <div class="car-single-hero__feature">
                                        {{ $tag->title }}
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- PROMO = offers --}}
                        @php
                            $offers = ($car->offers ?? collect())
                                ->filter(fn($o) => (bool) ($o->is_active ?? true))
                                ->values();
                        @endphp

                        @if ($offers->isNotEmpty())
                            <div class="car-single-hero__promo-wrap">
                                @foreach ($offers as $offer)
                                    <div class="car-single-hero__promo">
                                        <div class="car-single-hero__promo-item">

                                            <div class="car-single-hero__promo-item-title">
                                                {{ $offer->title }}
                                            </div>

                                            <div class="car-single-hero__promo-item-price-wrap">
                                                @if (!is_null($offer->price_from))
                                                    <div class="car-single-hero__promo-item-price">
                                                        от {{ number_format((int) $offer->price_from, 0, '.', ' ') }}
                                                        {{ $offer->currency ?? '₽' }}
                                                    </div>
                                                @endif

                                                @if (!is_null($offer->price_old))
                                                    <div class="car-single-hero__promo-item-price-old">
                                                        {{ number_format((int) $offer->price_old, 0, '.', ' ') }}
                                                        {{ $offer->currency ?? '₽' }}
                                                    </div>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                    </div>



    {{-- {{ Breadcrumbs::render('blog') }} --}}

    <section class="car-single__hero-section">
      <div class="container">

        <div class="car-single-hero__wrap">
          {{-- left --}}
          <div class="car-single-hero__info">
            <h1 class="car-single__title">КУЗОВНЫЕ ЭЛЕМЕНТЫ
              <br>
              ДЛЯ <span>FORD FOCUS 2</span>
            </h1>

            <div class="car-single-hero__features">
              <div class="car-single-hero__feature">
                Без предоплаты. Оплата при получении!
              </div>
              <div class="car-single-hero__feature">
                ХКС и Оцинковка
              </div>
              <div class="car-single-hero__feature">
                От 0,8 до 1,5 мм
              </div>
              <div class="car-single-hero__feature">
                Доставка по РФ
              </div>
            </div>

            <div class="car-single-hero__promo-wrap">
              <div class="car-single-hero__promo">

                <div class="car-single-hero__promo-item">
                  <div class="car-single-hero__promo-item-title">
                    ПОРОГИ
                  </div>


                  <div class="car-single-hero__promo-item-price-wrap">
                    <div class="car-single-hero__promo-item-price">от 1 690 ₽</div>
                    <div class="car-single-hero__promo-item-price-old">2 050 ₽</div>
                  </div>
                </div>
              </div>

              <div class="car-single-hero__promo">

                <div class="car-single-hero__promo-item">
                  <div class="car-single-hero__promo-item-title">
                    АРКИ
                  </div>


                  <div class="car-single-hero__promo-item-price-wrap">
                    <div class="car-single-hero__promo-item-price">от 1 950 ₽</div>
                    <div class="car-single-hero__promo-item-price-old">2 250 ₽</div>
                  </div>
                </div>
              </div>
            </div>
          </div>


          {{-- right --}}

          <div class="car-single-hero__image">
            <img src="" alt=" ">
          </div>
        </div>
      </div>
    </section>

    {{-- Форма --}}

    <section class="car-single-form-section">
      <div class="container">
        <div class="car-single-form-section__top">
          <div class="car-single-form-section__label">
            <img src="{{ asset('images/icons/fire.svg') }}" alt="">
            <p>Акция</p>
          </div>
          <h2 class="car-single-form-section__title">Оставьте заявку</h2>
        </div>

        <p class="car-single-form-section__descr">И мы перезвоним вам в течении минуты и ответим на все вопросы</p>

        <form action="" class="car-single-form">
          @csrf
          <div class="choose-section__form_row">
            <div class="input-item">
              <input class="input black" type="text" placeholder="Имя">
            </div>

            <div class="input-item">
              <input class="input black" type="tel" placeholder="+7 (___) ___ __ __">
            </div>

            <button type="submit" class="btn btn-black car-single-form-btn">Отправить</button>
          </div>

          <div class="form-policy">
            <input type="checkbox" id="choose-check" name="policy" value="1" checked="" required="">
            <label for="choose-check">
              Я соглашаюсь с
              <a href="http://localhost:8000/policy.pdf" target="_blank">политикой конфиденциальности</a>
              и даю согласие на обработку персональных данных
            </label>
          </div>
        </form>
      </div>
    </section>

    {{-- Запчасти --}}
    @php
      $parts = [
          [
              'image' => '/images/parts/thresholds.jpg',
              'label' => '-10 %',
              'title' => 'Ремонтные пороги',
              'descr' => 'Пороги для кузовного ремонта. Подходят под сварку и антикор.',
              'price' => '1 850 ₽',
              'priceOld' => '2 350 ₽',
              'link' => '/parts/thresholds',
              'alt' => 'Ремонтные пороги',
          ],
          [
              'image' => '/images/parts/arches.jpg',
              'label' => '-15 %',
              'title' => 'Колёсные арки',
              'descr' => 'Арки под восстановление геометрии и защиты от коррозии.',
              'price' => '2 290 ₽',
              'priceOld' => '2 690 ₽',
              'link' => '/parts/arches',
              'alt' => 'Колёсные арки',
          ],
          [
              'image' => '/images/parts/sills.jpg',
              'label' => '-5 %',
              'title' => 'Усилители порогов',
              'descr' => 'Усиление кузова и жёсткости. Сталь, точная геометрия.',
              'price' => '1 490 ₽',
              'priceOld' => '1 590 ₽',
              'link' => '/parts/sills-reinforcement',
              'alt' => 'Усилители порогов',
          ],
          [
              'image' => '/images/parts/floor.jpg',
              'label' => '-12 %',
              'title' => 'Ремкомплект пола',
              'descr' => 'Локальные вставки пола: водительская/пассажирская зона.',
              'price' => '3 190 ₽',
              'priceOld' => '3 590 ₽',
              'link' => '/parts/floor-repair',
              'alt' => 'Ремкомплект пола',
          ],
          [
              'image' => '/images/parts/jack-points.jpg',
              'label' => '-8 %',
              'title' => 'Домкратные усилители',
              'descr' => 'Усилители под штатные домкратные точки. Устойчивость и безопасность.',
              'price' => '890 ₽',
              'priceOld' => '990 ₽',
              'link' => '/parts/jack-points',
              'alt' => 'Домкратные усилители',
          ],
          [
              'image' => '/images/parts/wing.jpg',
              'label' => '-20 %',
              'title' => 'Ремвставки крыльев',
              'descr' => 'Нижние части крыльев под вырез/вварку. Быстрый ремонт без замены целиком.',
              'price' => '1 250 ₽',
              'priceOld' => '1 560 ₽',
              'link' => '/parts/wing-repair',
              'alt' => 'Ремвставки крыльев',
          ],
          [
              'image' => '/images/parts/door-bottom.jpg',
              'label' => '-10 %',
              'title' => 'Низ дверей',
              'descr' => 'Ремонтные элементы низа двери. Стыковка по заводским линиям.',
              'price' => '1 790 ₽',
              'priceOld' => '1 990 ₽',
              'link' => '/parts/door-bottom',
              'alt' => 'Низ дверей',
          ],
          [
              'image' => '/images/parts/trunk-floor.jpg',
              'label' => '-7 %',
              'title' => 'Пол багажника',
              'descr' => 'Вставки пола багажника и ниши. Устранение коррозии и отверстий.',
              'price' => '2 950 ₽',
              'priceOld' => '3 180 ₽',
              'link' => '/parts/trunk-floor',
              'alt' => 'Пол багажника',
          ],
          [
              'image' => '/images/parts/spar.jpg',
              'label' => '-18 %',
              'title' => 'Лонжероны',
              'descr' => 'Ремкомплекты лонжеронов для восстановления силовой части кузова.',
              'price' => '4 490 ₽',
              'priceOld' => '5 490 ₽',
              'link' => '/parts/spars',
              'alt' => 'Лонжероны',
          ],
          [
              'image' => '/images/parts/radiator-panel.jpg',
              'label' => '-6 %',
              'title' => 'Панель передка',
              'descr' => 'Элементы телевизора/передней панели под восстановление креплений.',
              'price' => '3 750 ₽',
              'priceOld' => '3 990 ₽',
              'link' => '/parts/front-panel',
              'alt' => 'Панель передка',
          ],
          [
              'image' => '/images/parts/crossmember.jpg',
              'label' => '',
              'title' => 'Поперечины',
              'descr' => 'Поперечины пола и усилители. Жёсткость и правильная посадка.',
              'price' => '1 680 ₽',
              'priceOld' => '1 850 ₽',
              'link' => '/parts/crossmembers',
              'alt' => 'Поперечины',
          ],
          [
              'image' => '/images/parts/rocker-covers.jpg',
              'label' => '-11 %',
              'title' => 'Накладки порогов',
              'descr' => 'Накладки для защиты и эстетики. Под покраску.',
              'price' => '2 150 ₽',
              'priceOld' => '',
              'link' => '/parts/rocker-covers',
              'alt' => 'Накладки порогов',
          ],
      ];
    @endphp

    <section class="car-single-parts-section">
      <div class="container">
        <h2 class="h2">Запчасти на Ford Focus 2</h2>

        <div class="car-single-parts">
          @foreach ($parts as $part)
            <x-car-single-part :image="$part['image']" :label="$part['label']" :title="$part['title']" :descr="$part['descr']" :price="$part['price']"
              :priceOld="$part['priceOld']" :link="$part['link']" :alt="$part['alt']" />
          @endforeach
        </div>
      </div>
    </section>
    <x-section.about-parts />

    <x-section.how-we-work />
    <x-section.gallery />


  </main>
@endsection
