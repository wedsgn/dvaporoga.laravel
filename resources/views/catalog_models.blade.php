@extends('layouts.front')

@section('content')
    <main>
        {{ Breadcrumbs::render('model', $car_make) }}

        <section class="catalog-page-section">
            <div class="container">
                <div class="catalog-page-top --model">
                    <div class="catalog-page-top__left">
                        <h1 class="h1 catalog-page__title">Каталог запчастей {{ $car_make->title }}</h1>

                        <p class="model-count">{{ $car_models->count() }} Моделей</p>
                    </div>
                    <p class="catalog-page__description">
                        {{ $car_make->description }}
                    </p>
                </div>
                <div class="blog-search">
                    <form action="" method="POST">
                        <input type="text" class="blog-search__input" placeholder="Поиск модели" />
                        <button type="submit" class="blog-search__btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                fill="none">
                                <path
                                    d="M8.75 15C12.2018 15 15 12.2018 15 8.75C15 5.29822 12.2018 2.5 8.75 2.5C5.29822 2.5 2.5 5.29822 2.5 8.75C2.5 12.2018 5.29822 15 8.75 15Z"
                                    stroke="#1E1E1E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M13.1696 13.168L17.5 17.4984" stroke="#1E1E1E" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </section>



        <section class="catalog-models ">
            <div class="container">
                <h2 class="h3">Выберите модель</h2>
                <div class="catalog-models__wrap">
                    <!-- Card -->
                    <a href="{{ route('car_gen', ['concern' => 'sd', 'model' => 'asd']) }}" class="car-model-card">
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
