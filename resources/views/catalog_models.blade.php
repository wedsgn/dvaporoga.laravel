@extends('layouts.front')

@section('content')
  <main>

    {{ Breadcrumbs::render('car_make.show', $car_make) }}

    <section class="catalog-page-section">
      <div class="container">
        <div class="catalog-page-top --model">
          <div class="catalog-page-top__left">
            <h1 class="h1 catalog-page__title">Модели автомобилей {{ $car_make->title }}</h1>

            {{-- <p class="model-count">{{ $car_models->count() }} Моделей</p> --}}
          </div>
          {{-- <div class="catalog-page__description">
            {!! $car_make->description !!}
          </div> --}}
        </div>
        <div class="blog-search">
          <form action="#" method="get" id="modelSearchForm">
            @csrf
            <input type="text" name="search" class="blog-search__input" id="modelSearchInput"
              placeholder="Поиск модели" />
            <button type="submit" class="blog-search__btn">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path
                  d="M8.75 15C12.2018 15 15 12.2018 15 8.75C15 5.29822 12.2018 2.5 8.75 2.5C5.29822 2.5 2.5 5.29822 2.5 8.75C2.5 12.2018 5.29822 15 8.75 15Z"
                  stroke="#1E1E1E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M13.1696 13.168L17.5 17.4984" stroke="#1E1E1E" stroke-width="1.5" stroke-linecap="round"
                  stroke-linejoin="round" />
              </svg>
            </button>
          </form>
        </div>
      </div>
    </section>



    <section class="catalog-models">
      <div class="container" id="modelsCatalog">
        <h2 class="h3">Выберите модель</h2>
        <div class="catalog-models__wrap">
          <!-- Card -->
          @foreach ($car_models as $car_model)
            <x-car-model-card :car_make="$car_make" :car_model="$car_model" />
          @endforeach
        </div>
      </div>
    </section>
    {{-- 
    <x-section.about-parts />
    <x-section.how-we-work />
    <x-section.about-company />
    <x-section.faq /> --}}

  </main>
  <script>
    const search = document.getElementById('modelSearchInput')

    search.addEventListener('keyup', () => {
      const searchInput = document.getElementById('modelSearchInput').value;
      const url = "{{ route('car_model.search', $car_make) }}?search=" + searchInput;
      fetch(url)
        .then(response => response.text())
        .then(data => {
          document.getElementById('modelsCatalog').innerHTML = data;
        });
    })
    document.getElementById('modelSearchForm').addEventListener('submit', function(event) {
      event.preventDefault();
      const searchInput = document.getElementById('modelSearchInput').value;
      const url = "{{ route('car_model.search', $car_make) }}?search=" + searchInput;
      fetch(url)
        .then(response => response.text())
        .then(data => {
          document.getElementById('modelsCatalog').innerHTML = data;
        });
    });
  </script>
@endsection
