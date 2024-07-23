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
                    <li>Каталог</li>
                </ul>
            </div>
        </section>

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
                    <form action="" method="POST">
                        <input type="text" class="blog-search__input" placeholder="Поиск статьи" />
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
                <div class="catalog-concern__wrap">
                    <x-concern-card :title="'AUDI'" :count="3" image="images/mark/audi.webp" link="/" />
                    <x-concern-card :title="'AUDI'" :count="3" image="images/mark/audi.webp" link="/" />
                    <x-concern-card :title="'AUDI'" :count="3" image="images/mark/audi.webp" link="/" />
                    <x-concern-card :title="'AUDI'" :count="3" image="images/mark/audi.webp" link="/" />
                    <x-concern-card :title="'AUDI'" :count="3" image="images/mark/audi.webp" link="/" />
                    <x-concern-card :title="'AUDI'" :count="3" image="images/mark/audi.webp" link="/" />
                </div>
            </div>
        </section>

        <x-section.products />
        <x-section.installing />
        <x-section.faq />


    </main>
@endsection
