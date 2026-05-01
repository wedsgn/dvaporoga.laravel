@extends('layouts.front')

@section('content')
  <main>

    {{ Breadcrumbs::render('car_make.show', $car_make) }}

    <section class="catalog-models-page">
      <div class="container">
        <div class="catalog-models-page__hero">
          <h1 class="catalog-models-page__title">
            <span class="catalog-models-page__title-line">Модели автомобилей</span>
            <span class="catalog-models-page__title-line">{{ $car_make->title }}</span>
          </h1>

          <form action="#" method="get" id="modelSearchForm" class="catalog-models-search">
            <label class="catalog-models-search__field" for="modelSearchInput">
              <input
                type="text"
                name="search"
                id="modelSearchInput"
                class="catalog-models-search__input"
                placeholder="Поиск модели"
                autocomplete="off" />
            </label>

            <button type="submit" class="catalog-models-search__submit" aria-label="Найти модель">
              <span class="catalog-models-search__submit-label">Найти</span>
              <svg
                class="catalog-models-search__submit-icon"
                xmlns="http://www.w3.org/2000/svg"
                width="14"
                height="14"
                viewBox="0 0 14 14"
                fill="none">
                <path
                  d="M6.125 10.5C8.54125 10.5 10.5 8.54125 10.5 6.125C10.5 3.70875 8.54125 1.75 6.125 1.75C3.70875 1.75 1.75 3.70875 1.75 6.125C1.75 8.54125 3.70875 10.5 6.125 10.5Z"
                  stroke="#383838"
                  stroke-width="1"
                  stroke-linecap="round"
                  stroke-linejoin="round" />
                <path
                  d="M9.21777 9.21777L12.2503 12.2503"
                  stroke="#383838"
                  stroke-width="1"
                  stroke-linecap="round"
                  stroke-linejoin="round" />
              </svg>
            </button>
          </form>
        </div>
      </div>
    </section>

    <section class="catalog-models-results">
      <div class="container" id="modelsCatalog">
        <h2 class="catalog-models-results__title">Выберите модель</h2>
        <div class="catalog-models-grid">
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
    const modelSearchInput = document.getElementById('modelSearchInput');
    const modelSearchForm = document.getElementById('modelSearchForm');
    const modelsCatalog = document.getElementById('modelsCatalog');
    const searchRoute = "{{ route('car_model.search', $car_make) }}";

    const loadModelResults = () => {
      const searchValue = encodeURIComponent(modelSearchInput.value);
      fetch(`${searchRoute}?search=${searchValue}`)
        .then(response => response.text())
        .then(data => {
          modelsCatalog.innerHTML = data;
        });
    };

    modelSearchInput.addEventListener('keyup', loadModelResults);

    modelSearchForm.addEventListener('submit', function(event) {
      event.preventDefault();
      loadModelResults();
    });
  </script>
@endsection
