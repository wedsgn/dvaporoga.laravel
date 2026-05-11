@extends('layouts.front')

@section('content')
  <main>

    {{ Breadcrumbs::render('catalog') }}

    <section class="catalog-parts-page">
      <div class="container">
        <div class="catalog-parts-page__header">
          <h1 class="catalog-parts-page__title">{!! $page->title !!}</h1>

          <form method="get" id="concernSearchForm" class="catalog-parts-search">
            <label class="catalog-parts-search__field" for="concernSearchInput">
              <input
                type="text"
                name="search"
                class="catalog-parts-search__input"
                id="concernSearchInput"
                placeholder="Введите марку"
                autocomplete="off"
              />
            </label>

            <button type="submit" class="btn catalog-parts-search__submit" aria-label="Подобрать">
              <span class="catalog-parts-search__submit-label">Подобрать</span>
              <svg class="catalog-parts-search__submit-icon" width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <circle cx="6.16667" cy="6.16667" r="5.16667" stroke="currentColor" stroke-width="1.4"/>
                <path d="M10.25 10.25L13 13" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
              </svg>
            </button>
          </form>
        </div>

        <div class="catalog-parts-results" id="concernsCatalog">
          @include('partials.concern-card', ['car_makes' => $car_makes])
        </div>
      </div>
    </section>

  </main>

  <script>
    const search = document.getElementById('concernSearchInput');
    const form = document.getElementById('concernSearchForm');
    const results = document.getElementById('concernsCatalog');

    const renderConcerns = () => {
      const searchInput = document.getElementById('concernSearchInput').value;
      const url = "{{ route('car_make.search') }}?search=" + encodeURIComponent(searchInput);

      fetch(url)
        .then(response => response.text())
        .then(data => {
          results.innerHTML = data;
        });
    };

    search.addEventListener('keyup', renderConcerns);

    form.addEventListener('submit', function(event) {
      event.preventDefault();
      renderConcerns();
    });
  </script>
@endsection
