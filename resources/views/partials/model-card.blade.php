@if ($car_models->count() > 0)
  <h2 class="catalog-models-results__title">Выберите модель</h2>
  <div class="catalog-models-grid">
    @foreach ($car_models as $car_model)
      <x-car-model-card :car_make="$car_make" :car_model="$car_model" />
    @endforeach
  </div>
@else
  <p class="catalog-models-results__empty">По вашему запросу ничего не найдено</p>
@endif
