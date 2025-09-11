<!DOCTYPE html>
<html lang="ru">


@include('parts.head')

<body>

    @include('parts.header')

    @yield('content')

    @include('parts.footer')


<!-- МОДАЛКА ФОРМЫ -->
<div class="modal micromodal-slide" id="modal-1" aria-hidden="true">
  <div class="modal__overlay" data-micromodal-close>
    <div class="modal__container" role="dialog" aria-modal="true"
         aria-labelledby="modal-1-title" aria-describedby="modal-1-desc" tabindex="-1">
      <header class="modal__header">
        <h2 class="modal__title" id="modal-1-title">Заполните форму</h2>
        <p class="modal__description" id="modal-1-desc">
          Мы свяжемся с вами в течение 5-ти минут <br> и ответим на все вопросы
        </p>
        <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
      </header>

      <form class="modal-form" data-action="{{ route('request_consultation.store') }}">
        @csrf
        <input type="text" placeholder="Имя" class="input" name="name" />
        <input type="tel" placeholder="+7 (___) ___ __ __" class="input" name="phone"/>
        <input type="hidden" name="form_id" value="modal-form-header">
        <button class="btn lg submit-modal" type="submit">Отправить</button>
        <p class="copyright">
          Нажимая кнопку “Отправить” вы соглашаетесь с нашей
          <a href="/Политика_в_области_обработки_персональных_данных.pdf" target="_blank">политикой конфиденциальности</a>
        </p>
      </form>
    </div>
  </div>
</div>
<div class="modal micromodal-slide" id="modal-faq" aria-hidden="true">
  <div class="modal__overlay" data-micromodal-close>
    <div class="modal__container" role="dialog" aria-modal="true"
         aria-labelledby="modal-faq-title" aria-describedby="modal-faq-desc" tabindex="-1">
      <header class="modal__header">
        <h2 class="modal__title" id="modal-faq-title">Заполните форму</h2>
        <p class="modal__description" id="modal-faq-desc">
          Мы свяжемся с вами в течение 5-ти минут <br> и ответим на все вопросы
        </p>
        <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
      </header>

      <form class="modal-form" data-action="{{ route('request_consultation.store') }}">
        @csrf
        <input type="text" placeholder="Имя" class="input" name="name" />
        <input type="tel" placeholder="+7 (___) ___ __ __" class="input" name="phone"/>
        <input type="hidden" name="form_id" value="modal-form-faq">
        <button class="btn lg submit-modal" type="submit">Отправить</button>
        <p class="copyright">
          Нажимая кнопку “Отправить” вы соглашаетесь с нашей
          <a href="/Политика_в_области_обработки_персональных_данных.pdf" target="_blank">политикой конфиденциальности</a>
        </p>
      </form>
    </div>
  </div>
</div>
<!-- МОДАЛКА "СПАСИБО" -->
<div class="modal modal-success micromodal-slide" id="modal-2" aria-hidden="true">
  <div class="modal__overlay" data-micromodal-close>
    <div class="modal__container" role="dialog" aria-modal="true"
         aria-labelledby="modal-2-title" aria-describedby="modal-2-desc" tabindex="-1">
      <header class="modal__header">
        <h2 class="modal__title" id="modal-2-title">Заявка успешно отправлена</h2>
        <p class="modal__description" id="modal-2-desc">
          Мы свяжемся с вами в течение 7 минут <br> и ответим на все вопросы
        </p>
        <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
      </header>
    </div>
  </div>
</div>


</body>
<script src="{{ asset('/js/forms-ajax.js') }}"></script>
<script defer src="{{ asset('js/products-section.js') }}"></script>
<script src="{{ asset('/js/product_calc.js') }}"></script>
</html>
