@props(['makes']) {{-- передай сюда $makesForForm = CarMake::orderBy('title','asc')->get(['id','title']) --}}

<section class="choose-section" id="features">
  <div class="container">
    <h2 class="h2">Выберите свой автомобиль</h2>
    <p class="choose-section__descr">Оставьте заявку, мы сообщим о наличии запчастей и их стоимость</p>

    <div class="choose-section__form">
      <form id="choose-car-form" method="post" action="{{ route('lead.store_car') }}" novalidate>
        @csrf
        <input type="hidden" name="form_id" value="index-choose-car">
        <input type="hidden" name="current_url"
          value="{{ url()->current() }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}">

        {{-- UTM --}}
        <input type="hidden" name="utm_source" value="{{ request('utm_source') }}">
        <input type="hidden" name="utm_medium" value="{{ request('utm_medium') }}">
        <input type="hidden" name="utm_campaign" value="{{ request('utm_campaign') }}">
        <input type="hidden" name="utm_term" value="{{ request('utm_term') }}">
        <input type="hidden" name="utm_content" value="{{ request('utm_content') }}">

        <div class="choose-section__form_row">
          {{-- Марка --}}
          <div class="select-item">
            <select name="make_id" id="choose-make" class="js-choice" data-placeholder="Марка" required>
              {{-- <option value="" disabled {{ old('make_id') ? '' : 'selected' }}>Марка</option> --}}
              @foreach ($makes as $make)
                <option value="{{ $make->id }}" @selected(old('make_id') == $make->id)>{{ $make->title }}
                </option>
              @endforeach
            </select>
            <div class="field-error" data-error-for="make_id"></div>
          </div>

          <div class="select-item">
            <select name="model_id" id="choose-model" class="js-choice" data-placeholder="Выберите марку"
              data-models-url="{{ route('ajax.car-models') }}" required>

            </select>
            <div class="field-error" data-error-for="model_id"></div>
          </div>

          <div class="input-item">
            <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="Телефон" required>
            <div class="field-error" data-error-for="phone"></div>
          </div>
        </div>

        <button type="submit" class="btn black lg">Отправить заявку</button>
        <div class="form-policy-item">
          <div class="form-policy">
            <input type="checkbox" id="choose-check" name="policy" value="1" checked required>
            <label for="choose-check">
              Я соглашаюсь с
              <a href="{{ asset('policy.pdf') }}" target="_blank">политикой конфиденциальности</a>
              и даю согласие на обработку персональных данных
            </label>
          </div>
          <div class="field-error" data-error-for="policy"></div>
        </div>
      </form>

    </div>
  </div>
</section>
