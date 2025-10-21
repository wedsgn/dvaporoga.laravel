@props(['makes'])

<section class="choose-section" id="features">
  <div class="container">

    <h2 class="h2">Выберите свой автомобиль</h2>
    <p class="choose-section__descr">Оставьте заявку, мы сообщим о наличии запчастей и их стоимость </p>


    <div class="choose-section__form">
      <form action="">
        <div class="choose-section__form_row">
          <select name="asd" id="" class="js-choice" data-placeholder="Марка" required>


            @foreach ($makes as $item)
              <option value="{{ $item['title'] }}">{{ $item['title'] }}</option>
            @endforeach
          </select>

          <select name="asd" id="" class="js-choice" data-placeholder="Модель" required>
            @foreach ($makes as $item)
              <option value="{{ $item['title'] }}">{{ $item['title'] }}</option>
            @endforeach
          </select>

          <input type="tel" placeholder="Телефон" required>
        </div>

        <button type="submit" class="btn black lg">
          Отправить заявку
        </button>

        <div class="form-policy-item">
          <div class="form-policy">
            <input type="checkbox" id="choose-check" checked required>
            <label for="choose-check">Я соглашаюсь с <a href="{{ asset('policy.pdf') }}" target="_blank">политикой
                конфиденциальности</a> и даю согласие
              на обработку
              персонаяльных данных
            </label>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>
