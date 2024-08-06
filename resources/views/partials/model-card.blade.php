
        <section id="modelsCatalog" class="catalog-models ">
            <div class="container">
              @if ($car_models->count() > 0)
                <h2 class="h3">Выберите модель</h2>
                <div class="catalog-models__wrap">
                    <!-- Card -->
                    @foreach ($car_models as $car_model)
                    <x-car-model-card :car_make="$car_make" :car_model="$car_model" />
                    @endforeach
                </div>
                @else
                <div class="not-found-section">
                    <p>По вашему запросу ничего не найдено</p>
                </div>
            @endif
            </div>
        </section>
