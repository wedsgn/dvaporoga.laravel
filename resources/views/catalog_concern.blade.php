@extends('layouts.front')

@section('content')
    <main>

        {{ Breadcrumbs::render('catalog') }}

        <section class="catalog-page-section">
            <div class="container">
                <div class="catalog-page-top">
                    <h1 class="h1 catalog-page__title">Каталог запчастей "Два порога"</h1>
                    <div class="catalog-page__description">
                        <p>
                            Каталог кузовных запчастей от компании "Два порога" предлагает широкий ассортимент деталей для
                            99% всех марок и моделей автомобилей.
                        </p>

                        <p>
                            Здесь вы найдете крылья, пороги, пенки дверей, заглушки, усилители порогов и другие кузовные
                            элементы, изготовленные с высокой точностью на современном оборудовании.
                        </p>

                        <p>
                            Мы гарантируем высокое качество и точное соответствие каждой детали. Выбирайте надежные кузовные
                            запчасти для вашего авто с быстрой доставкой по всей стране.
                        </p>

                        <button class="btn catalog-page-top__btn" data-micromodal-trigger="modal-1">Подобрать
                            запчасть</button>
                    </div>
                </div>

                <div class="blog-search">
                    <form method="get" id="concernSearchForm">
                        @csrf
                        <input type="text" name="search" class="blog-search__input" id="concernSearchInput"
                            placeholder="Поиск по марке" />
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
            <div class="container" id="concernsCatalog">
                <div class="catalog-concern__wrap">
                    @foreach ($car_makes as $car_make)
                        <x-concern-card :title="$car_make->title" :slug="$car_make->slug" :count="$car_make->car_models->count()" image="{{ $car_make->image }}"
                            link="{{ route('car_make.show', $car_make->slug) }}" />
                    @endforeach
                </div>
            </div>
        </section>

        <x-section.products :items="$products" />
        {{-- <x-section.installing /> --}}
        <x-section.faq />


    </main>
    <script>
        const search = document.getElementById('concernSearchInput')

        search.addEventListener('keyup', () => {
            const searchInput = document.getElementById('concernSearchInput').value;
            const url = "{{ route('car_make.search') }}?search=" + searchInput;
            fetch(url)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('concernsCatalog').innerHTML = data;
                });
        })
        document.getElementById('concernSearchForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const searchInput = document.getElementById('modelSearchInput').value;
            const url = "{{ route('car_make.search') }}?search=" + searchInput;
            fetch(url)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('concernsCatalog').innerHTML = data;
                });
        });
    </script>
@endsection
