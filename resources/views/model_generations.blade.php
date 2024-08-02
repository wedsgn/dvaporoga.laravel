@extends('layouts.front')

@section('content')
    <main>

        {{ Breadcrumbs::render('catalog') }}

        <section class="catalog-page-section">
            <div class="container">
                <div class="catalog-page-top">
                    <h1 class="h1 catalog-page__title">Каталог запчастей</h1>
                    <p class="catalog-page__description">
                        В нашем каталоге более 300 моделей автомобилей с 1990г выпуска. Мы
                        изготавливаем ремонтные пороги, кузовные колесные арки, пенки
                        дверей и другие элементы. Выберите нужную вам марку, деталь и
                        оформите заказ. Если вы не нашли свой автомобиль, то
                        <a href="#">оставьте заявку онлайн</a> через форму сайта.
                    </p>
                </div>

                <div class="blog-search">
                    <form action="{{ route('catalog.search') }}" method="get">
                        @csrf
                        <input type="text" name="search" class="blog-search__input" placeholder="Поиск по марке" />
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

        <section class="catalog-concern">
            <div class="container">
                {{-- @if ($car_makes->count() > 0)
                    <div class="catalog-concern__wrap">
                        @foreach ($car_makes as $car_make)
                            <x-concern-card :title="$car_make->title" :count="$car_make->car_models->count()" image="{{ $car_make->image }}"
                                link="{{ route('car_make.show', $car_make->slug) }}" />
                        @endforeach
                    </div>
                @else
                    <div class="not-found-section">
                        <p>По вашему запросу ничего не найдено</p>
                    </div>
                @endif --}}
            </div>
            {{-- <div class="pagination-wrap">
                {{ $car_makes->links('pagination::default') }}
            </div> --}}
            </div>
        </section>

        {{-- <x-section.products :items="$products" /> --}}
        <x-section.installing />
        <x-section.faq />


    </main>
@endsection
