      <div class="catalog-concern__wrap">
          @if ($car_makes->count() > 0)
              @foreach ($car_makes as $car_make)
                  <x-concern-card :title="$car_make->title" :slug="$car_make->slug" :count="$car_make->car_models->count()" image="{{ $car_make->image }}"
                      link="{{ route('car_make.show', $car_make->slug) }}" />
              @endforeach
          @else
              <div class="not-found-section">
                  <p>По вашему запросу ничего не найдено</p>
              </div>
          @endif
      </div>
