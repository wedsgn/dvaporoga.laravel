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
      <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title"
        aria-describedby="modal-1-desc" tabindex="-1">
        <header class="modal__header">
          <h2 class="modal__title" id="modal-1-title">Заполните форму</h2>
          <p class="modal__description" id="modal-1-desc">
            Мы свяжемся с вами в течение 5-ти минут <br> и ответим на все вопросы
          </p>
          <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
        </header>

        <form class="modal-form" data-action="{{ route('request_consultation.store') }}" data-ym-goal="modal-1">
          @csrf
          <input type="text" placeholder="Имя" class="input" name="name" />
          <input type="tel" placeholder="+7 (___) ___ __ __" class="input" name="phone" />
          <input type="hidden" name="form_id" value="modal-1">
          <input type="hidden" name="utm_source" value="{{ request()->input('utm_source') }}">
          <input type="hidden" name="utm_medium" value="{{ request()->input('utm_medium') }}">
          <input type="hidden" name="utm_campaign" value="{{ request()->input('utm_campaign') }}">
          <input type="hidden" name="utm_term" value="{{ request()->input('utm_term') }}">
          <input type="hidden" name="utm_content" value="{{ request()->input('utm_content') }}">
          <button class="btn lg submit-modal" type="submit">Отправить</button>
          <p class="copyright">
            Нажимая кнопку “Отправить” вы соглашаетесь с нашей
            <a href="{{ asset('policy.pdf') }}" target="_blank">политикой
              конфиденциальности</a>
          </p>
        </form>
      </div>
    </div>
  </div>

  <div class="modal micromodal-slide" id="modal-hero" aria-hidden="true">
    <div class="modal__overlay" data-micromodal-close>
      <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-hero-title"
        aria-describedby="modal-hero-desc" tabindex="-hero">
        <header class="modal__header">
          <h2 class="modal__title" id="modal-hero-title">Заполните форму</h2>
          <p class="modal__description" id="modal-hero-desc">
            Мы свяжемся с вами в течение 5-ти минут <br> и ответим на все вопросы
          </p>
          <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
        </header>

        <form class="modal-form" data-action="{{ route('request_consultation.store') }}" data-ym-goal="modal-hero">
          @csrf
          <input type="text" placeholder="Имя" class="input" name="name" />
          <input type="tel" placeholder="+7 (___) ___ __ __" class="input" name="phone" />
          <input type="hidden" name="form_id" value="modal-form-hero">
          <input type="hidden" name="utm_source" value="{{ request()->input('utm_source') }}">
          <input type="hidden" name="utm_medium" value="{{ request()->input('utm_medium') }}">
          <input type="hidden" name="utm_campaign" value="{{ request()->input('utm_campaign') }}">
          <input type="hidden" name="utm_term" value="{{ request()->input('utm_term') }}">
          <input type="hidden" name="utm_content" value="{{ request()->input('utm_content') }}">
          <button class="btn lg submit-modal" type="submit">Отправить</button>
          <p class="copyright">
            Нажимая кнопку “Отправить” вы соглашаетесь с нашей
            <a href="{{ asset('policy.pdf') }}" target="_blank">политикой
              конфиденциальности</a>
          </p>
        </form>
      </div>
    </div>
  </div>


  <div class="modal micromodal-slide" id="modal-about" aria-hidden="true">
    <div class="modal__overlay" data-micromodal-close>
      <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-about-title"
        aria-describedby="modal-about-desc" tabindex="-about">
        <header class="modal__header">
          <h2 class="modal__title" id="modal-about-title">Заполните форму</h2>
          <p class="modal__description" id="modal-about-desc">
            Мы свяжемся с вами в течение 5-ти минут <br> и ответим на все вопросы
          </p>
          <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
        </header>

        <form class="modal-form" data-action="{{ route('request_consultation.store') }}" data-ym-goal="modal-about">
          @csrf
          <input type="text" placeholder="Имя" class="input" name="name" />
          <input type="tel" placeholder="+7 (___) ___ __ __" class="input" name="phone" />
          <input type="hidden" name="form_id" value="modal-form-about">
          <input type="hidden" name="utm_source" value="{{ request()->input('utm_source') }}">
          <input type="hidden" name="utm_medium" value="{{ request()->input('utm_medium') }}">
          <input type="hidden" name="utm_campaign" value="{{ request()->input('utm_campaign') }}">
          <input type="hidden" name="utm_term" value="{{ request()->input('utm_term') }}">
          <input type="hidden" name="utm_content" value="{{ request()->input('utm_content') }}">
          <button class="btn lg submit-modal" type="submit">Отправить</button>
          <p class="copyright">
            Нажимая кнопку “Отправить” вы соглашаетесь с нашей
            <a href="{{ asset('policy.pdf') }}" target="_blank">политикой
              конфиденциальности</a>
          </p>
        </form>
      </div>
    </div>
  </div>


  <div class="modal micromodal-slide" id="modal-delivery" aria-hidden="true">
    <div class="modal__overlay" data-micromodal-close>
      <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-delivery-title"
        aria-describedby="modal-delivery-desc" tabindex="-delivery">
        <header class="modal__header">
          <h2 class="modal__title" id="modal-delivery-title">Заполните форму</h2>
          <p class="modal__description" id="modal-delivery-desc">
            Мы свяжемся с вами в течение 5-ти минут <br> и ответим на все вопросы
          </p>
          <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
        </header>

        <form class="modal-form" data-action="{{ route('request_consultation.store') }}" data-ym-goal="modal-delivery">
          @csrf
          <input type="text" placeholder="Имя" class="input" name="name" />
          <input type="tel" placeholder="+7 (___) ___ __ __" class="input" name="phone" />
          <input type="hidden" name="form_id" value="modal-form-delivery">
          <input type="hidden" name="utm_source" value="{{ request()->input('utm_source') }}">
          <input type="hidden" name="utm_medium" value="{{ request()->input('utm_medium') }}">
          <input type="hidden" name="utm_campaign" value="{{ request()->input('utm_campaign') }}">
          <input type="hidden" name="utm_term" value="{{ request()->input('utm_term') }}">
          <input type="hidden" name="utm_content" value="{{ request()->input('utm_content') }}">
          <button class="btn lg submit-modal" type="submit">Отправить</button>
          <p class="copyright">
            Нажимая кнопку “Отправить” вы соглашаетесь с нашей
            <a href="{{ asset('policy.pdf') }}" target="_blank">политикой
              конфиденциальности</a>
          </p>
        </form>
      </div>
    </div>
  </div>

  <div class="modal micromodal-slide" id="modal-faq" aria-hidden="true">
    <div class="modal__overlay" data-micromodal-close>
      <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-faq-title"
        aria-describedby="modal-faq-desc" tabindex="-1">
        <header class="modal__header">
          <h2 class="modal__title" id="modal-faq-title">Заполните форму</h2>
          <p class="modal__description" id="modal-faq-desc">
            Мы свяжемся с вами в течение 5-ти минут <br> и ответим на все вопросы
          </p>
          <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
        </header>

        <form class="modal-form" data-action="{{ route('request_consultation.store') }}" data-ym-goal="modal-faq">
          @csrf
          <input type="text" placeholder="Имя" class="input" name="name" />
          <input type="tel" placeholder="+7 (___) ___ __ __" class="input" name="phone" />
          <input type="hidden" name="form_id" value="modal-form-faq">
          <input type="hidden" name="utm_source" value="{{ request()->input('utm_source') }}">
          <input type="hidden" name="utm_medium" value="{{ request()->input('utm_medium') }}">
          <input type="hidden" name="utm_campaign" value="{{ request()->input('utm_campaign') }}">
          <input type="hidden" name="utm_term" value="{{ request()->input('utm_term') }}">
          <input type="hidden" name="utm_content" value="{{ request()->input('utm_content') }}">
          <button class="btn lg submit-modal" type="submit">Отправить</button>
          <p class="copyright">
            Нажимая кнопку “Отправить” вы соглашаетесь с нашей
            <a href="{{ asset('policy.pdf') }}" target="_blank">политикой
              конфиденциальности</a>
          </p>
        </form>
      </div>
    </div>
  </div>
  <!-- МОДАЛКА "СПАСИБО" -->
  <div class="modal modal-success micromodal-slide" id="modal-2" aria-hidden="true">
    <div class="modal__overlay" data-micromodal-close>
      <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-2-title"
        aria-describedby="modal-2-desc" tabindex="-1">
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

  <!-- МОДАЛКА "Автоматическая" -->
  <div class="modal modal-success micromodal-slide" id="modal-3" aria-hidden="true">
    <div class="modal__overlay" data-micromodal-close>
      <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-3-title"
        aria-describedby="modal-3-desc" tabindex="-1">
        <header class="modal__header">
          <h2 class="modal__title" id="modal-3-title">Остались вопросы?</h2>
          <p class="modal__description" id="modal-3-desc">
            Оставьте свой номер телефона и мы перезвоним Вам в кратчайшее время, чтобы ответить на все Ваши вопросы!
          </p>
          <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
        </header>


        <form class="modal-form" data-action="{{ route('request_consultation.store') }}" data-ym-goal="modal-3">
          @csrf
          <input type="text" placeholder="Имя" class="input" name="name" />
          <input type="tel" placeholder="+7 (___) ___ __ __" class="input" name="phone" />
          <input type="hidden" name="form_id" value="modal-3">
          <input type="hidden" name="utm_source" value="{{ request()->input('utm_source') }}">
          <input type="hidden" name="utm_medium" value="{{ request()->input('utm_medium') }}">
          <input type="hidden" name="utm_campaign" value="{{ request()->input('utm_campaign') }}">
          <input type="hidden" name="utm_term" value="{{ request()->input('utm_term') }}">
          <input type="hidden" name="utm_content" value="{{ request()->input('utm_content') }}">
          <button class="btn lg submit-modal" type="submit">Отправить</button>
          <p class="copyright">
            Нажимая кнопку “Отправить” вы соглашаетесь с нашей
            <a href="{{ asset('policy.pdf') }}" target="_blank">политикой
              конфиденциальности</a>
          </p>
        </form>
      </div>
    </div>
  </div>
<script src="{{ asset('/js/forms-ajax.js') }}"></script>
{{-- <script defer src="{{ asset('js/products-section.js') }}"></script> --}}
<script src="{{ asset('/js/product_calc.js') }}"></script>
</body>


</html>
