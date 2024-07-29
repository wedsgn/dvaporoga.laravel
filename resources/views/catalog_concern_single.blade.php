@extends('layouts.front')

@section('content')
    <main>
        <section class="breadcrumbs-section">
            <div class="container">
                <ul class="breadcrumbs">
                    <li><a href="/">Главная</a></li>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M14 8.0013L10.6667 4.66797M14 8.0013L10.6667 11.3346M14 8.0013H2" stroke="#1E1E1E"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </li>
                    <li><a href="/">Каталог запчастей</a></li>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"
                            fill="none">
                            <path d="M14 8.0013L10.6667 4.66797M14 8.0013L10.6667 11.3346M14 8.0013H2" stroke="#1E1E1E"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </li>
                    <li><a href="/">Audi</a></li>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"
                            fill="none">
                            <path d="M14 8.0013L10.6667 4.66797M14 8.0013L10.6667 11.3346M14 8.0013H2" stroke="#1E1E1E"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </li>
                    <li>80</li>
                </ul>
            </div>
        </section>

        <section class="catalog-page-section">
            <div class="container">
                <div class="catalog-page-top">
                    <h1 class="h1 catalog-page__title">Audi 80 поколения</h1>
                    <p class="catalog-page__description">
                        В нашем каталоге более 300 моделей автомобилей с 1990г выпуска. Мы
                        изготавливаем ремонтные пороги, кузовные колесные арки, пенки
                        дверей и другие элементы. Выберите нужную вам марку, деталь и
                        оформите заказ. Если вы не нашли свой автомобиль, то
                        <a href="#">оставьте заявку онлайн</a> через форму сайта.
                    </p>
                </div>
            </div>
        </section>

        <section class="section car-generation-section">
            <div class="container">
                <div class="car-generation__wrap">
                    <!-- Generation row -->
                    @foreach ($car_models as $car_model)
                        <x-car-model-card :data="$car_model" />
                    @endforeach

                </div>
            </div>
        </section>

        <section class="catalog-models section">
            <div class="container">
                <h2 class="h2">Другие модели Audi</h2>
                <div class="catalog-models__wrap">
                    <!-- Card -->
                    <a href="#" class="car-model-card">
                        <div class="car-model-card__image">
                            <img src="/images/cars/audi-80.jpg" alt="Название авто на картинке" />
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
                            <img src="/images/cars/audi-80.jpg" alt="Название авто на картинке" />
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
                            <img src="/images/cars/audi-80.jpg" alt="Название авто на картинке" />
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
                            <img src="/images/cars/audi-80.jpg" alt="Название авто на картинке" />
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
                            <img src="/images/cars/audi-80.jpg" alt="Название авто на картинке" />
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
                            <img src="/images/cars/audi-80.jpg" alt="Название авто на картинке" />
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
                            <img src="/images/cars/audi-80.jpg" alt="Название авто на картинке" />
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
                            <img src="/images/cars/audi-80.jpg" alt="Название авто на картинке" />
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
                            <img src="/images/cars/audi-80.jpg" alt="Название авто на картинке" />
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
                            <img src="/images/cars/audi-80.jpg" alt="Название авто на картинке" />
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
                            <img src="/images/cars/audi-80.jpg" alt="Название авто на картинке" />
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


        <x-section.faq />


    </main>
@endsection
