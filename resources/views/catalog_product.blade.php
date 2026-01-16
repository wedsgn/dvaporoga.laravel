@extends('layouts.front')

@section('content')
  <main>

    {{-- HERO --}}
    <section class="car-single__hero-section">
      <div class="container">
        <div class="car-single-hero__wrap">

          {{-- left --}}
          <div class="car-single-hero__info">

            <h1 class="car-single__title">
              КУЗОВНЫЕ ЭЛЕМЕНТЫ

              ДЛЯ <br><span>{{ mb_strtoupper($car->title) }}</span>
            </h1>
            <div class="car-single-hero__features">
              <div class="car-single-hero__feature">
                Оплата при получении
              </div>
              <div class="car-single-hero__feature">
                ХКС и Оцинковка
              </div>
              <div class="car-single-hero__feature">
                От 0,8 до 1,5 мм
              </div>
              <div class="car-single-hero__feature">
                Доставка по РФ
              </div>
              <div class="car-single-hero__feature">
                Повторение оригинала
              </div>
            </div>

            <div class="car-single-hero__promo-wrap">
              <div class="car-single-hero__promo">

                <div class="car-single-hero__promo-item">
                  <div class="car-single-hero__promo-item-title">
                    ПОРОГИ
                  </div>


                  <div class="car-single-hero__promo-item-price-wrap">
                    <div class="car-single-hero__promo-item-price">1 690 ₽</div>
                    <div class="car-single-hero__promo-item-price-old">2 050 ₽</div>
                  </div>
                </div>
              </div>

              <div class="car-single-hero__promo">

                <div class="car-single-hero__promo-item">
                  <div class="car-single-hero__promo-item-title">
                    АРКИ
                  </div>


                  <div class="car-single-hero__promo-item-price-wrap">
                    <div class="car-single-hero__promo-item-price">1 950 ₽</div>
                    <div class="car-single-hero__promo-item-price-old">2 250 ₽</div>
                  </div>
                </div>
              </div>
            </div>
          </div>


          {{-- right --}}
          <div class="car-single-hero__image">
            @php
              $img = null;

              // если у тебя хранится "default" как строка — учитываем
              if (!empty($car->image) && $car->image !== 'default') {
                  $img = asset('storage/' . $car->image);
              } elseif (!empty($car->image_mob) && $car->image_mob !== 'default') {
                  $img = asset('storage/' . $car->image_mob);
              } else {
                  // запасной вариант
                  $img = asset('images/cars/merc.png');
              }
            @endphp

            <img src="{{ $img }}" alt="{{ $car->title }}">
          </div>

        </div>
      </div>
    </section>

    <section class="car-single-form-section">
      <div class="container">
        <div class="car-single-form-section__top">
          {{-- @php
                        $hasOffers = ($car->offers ?? collect())
                            ->filter(fn($o) => (bool) ($o->is_active ?? true))
                            ->isNotEmpty();
                    @endphp

                    @if ($hasOffers) --}}
          <div class="car-single-form-section__label">
            <img src="{{ asset('images/icons/fire.svg') }}" alt="">
            <p>Акция</p>
          </div>
          {{-- @endif --}}
          <h2 class="car-single-form-section__title">Оставьте заявку</h2>
        </div>

        <p class="car-single-form-section__descr">Мы подберем деталь под ваш автомобиль и ответим на все вопросы </p>

        <form id="car-request-form" action="{{ route('requests.car') }}" method="POST" class="car-single-form">
          @csrf

          <input type="hidden" name="form_id" value="car-page-form">
          <input type="hidden" name="car_id" value="{{ $car->id }}">
          <input type="hidden" name="current_url" value="{{ request()->fullUrl() }}">

          {{-- если у тебя UTM тянутся по фронту — можно также пробросить --}}
          <input type="hidden" name="utm_source" value="{{ request('utm_source') }}">
          <input type="hidden" name="utm_medium" value="{{ request('utm_medium') }}">
          <input type="hidden" name="utm_campaign" value="{{ request('utm_campaign') }}">
          <input type="hidden" name="utm_term" value="{{ request('utm_term') }}">
          <input type="hidden" name="utm_content" value="{{ request('utm_content') }}">

          <div class="choose-section__form_row">
            <div class="input-item">
              <input class="input black" type="text" name="name" placeholder="Имя" required>
            </div>

            <div class="input-item">
              <input class="input black" type="tel" name="phone" placeholder="+7 (___) ___ __ __" required>
            </div>

            <button type="submit" class="btn btn-black car-single-form-btn">Отправить</button>
          </div>

          <div class="form-policy">
            <input type="checkbox" id="choose-check" name="policy" value="1" checked required>
            <label for="choose-check">
              Я соглашаюсь с
              <a href="{{ url('/policy.pdf') }}" target="_blank">политикой конфиденциальности</a>
              и даю согласие на обработку персональных данных
            </label>
          </div>
        </form>
      </div>
    </section>

    <section class="car-single-parts-section">
      <div class="container">
        <h2 class="h2">Запчасти на {{ $car->title }}</h2>

        <div class="car-single-parts">
          @foreach ($products as $p)
            @php
              $adminPath = !empty($p->image) && $p->image !== 'default' ? ltrim($p->image, '/') : null;
              $pivotPath =
                  !empty($p->pivot?->image) && $p->pivot->image !== 'default' ? ltrim($p->pivot->image, '/') : null;

              $fallbackPath = null;
              foreach (['webp', 'jpg', 'jpeg', 'png'] as $ext) {
                  $pp = "products_default/{$p->slug}.{$ext}";
                  if (\Illuminate\Support\Facades\Storage::disk('public')->exists($pp)) {
                      $fallbackPath = $pp;
                      break;
                  }
              }

              $finalPath = $adminPath ?: ($pivotPath ?: $fallbackPath);

              $imageUrl = $finalPath ? asset('storage/' . $finalPath) : asset('images/no-image.jpg');
            @endphp

            <x-car-single-part :id="$p->id" :image="$imageUrl" :discount_percentage="$p->discount_percentage ? '-' . $p->discount_percentage . ' %' : ''" :title="$p->title" :description="$p->description ?? ''"
              :price="$p->price ? number_format((int) $p->price, 0, '.', ' ') . ' ₽' : ''" :priceOld="$p->price_old ? number_format((int) $p->price_old, 0, '.', ' ') . ' ₽' : ''" :link="$p->link ?? ''" :alt="$p->alt ?: $p->title" />
          @endforeach
        </div>
      </div>
    </section>

    <x-section.about-parts />
    <x-section.how-we-work />
    <x-section.about-company />
    <x-section.faq />
    {{-- <x-section.gallery /> --}}

  </main>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const form = document.getElementById('car-request-form');
      if (!form) return;

      form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const btn = form.querySelector('button[type="submit"]');
        btn.disabled = true;

        try {
          const response = await fetch(form.action, {
            method: 'POST',
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
            },
            body: new FormData(form),
          });

          if (!response.ok) {
            throw new Error('Request failed');
          }

          // успех → открываем модалку
          MicroModal.show('modal-2');

          // опционально: очистить форму
          form.reset();

        } catch (err) {
          alert('Ошибка отправки формы. Попробуйте позже.');
          console.error(err);
        } finally {
          btn.disabled = false;
        }
      });
    });
  </script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // 1) Подстановка товара в модалку
      document.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-micromodal-trigger="modal-product"]');
        if (!btn) return;

        const form = document.getElementById('modal-product-form');
        if (!form) return;

        const pid = btn.dataset.productId || '';
        const title = btn.dataset.productTitle || '';
        const price = (btn.dataset.productPrice || '').replace(/\s+/g, '').trim(); // если "1 690"
        const priceNum = price ? parseInt(price, 10) : '';

        // data -> как в store_request_product: [{"id":123}]
        const dataInput = document.getElementById('modal-product-data');
        if (dataInput) dataInput.value = JSON.stringify(pid ? [{
          id: Number(pid)
        }] : []);

        // total_price (опционально)
        const totalInput = document.getElementById('modal-product-total');
        if (totalInput) totalInput.value = priceNum || '';

        // можно подсветить заголовок модалки (не обязательно)
        const h = document.getElementById('modal-product-title');
        if (h && title) h.textContent = `Заказ: ${title}`;

        // MicroModal сам откроется по data-micromodal-trigger, но если хочешь гарант:
        // MicroModal.show('modal-product');
      });

      // 2) AJAX submit для модалки товара (и показать modal-2 на успех)
      const modalForm = document.getElementById('modal-product-form');
      if (modalForm) {
        modalForm.addEventListener('submit', async (e) => {
          e.preventDefault();

          const action = modalForm.dataset.action;
          const btn = modalForm.querySelector('button[type="submit"]');
          btn.disabled = true;

          try {
            const resp = await fetch(action, {
              method: 'POST',
              headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': modalForm.querySelector('input[name="_token"]')
                  .value,
              },
              body: new FormData(modalForm),
            });

            if (!resp.ok) throw new Error('Request failed');

            // закрываем модалку товара и открываем успех
            MicroModal.close('modal-product');
            MicroModal.show('modal-2');

            modalForm.reset();

            // вернём базовый заголовок
            const h = document.getElementById('modal-product-title');
            if (h) h.textContent = 'Заполните форму';

            // почистим data
            const dataInput = document.getElementById('modal-product-data');
            if (dataInput) dataInput.value = '[]';

          } catch (err) {
            alert('Ошибка отправки формы. Попробуйте позже.');
            console.error(err);
          } finally {
            btn.disabled = false;
          }
        });
      }
    });
  </script>
@endsection
