<header class="header">
  <div class="container">
    <div class="header__wrap">
      <div class="header__left">
        <a href="/" class="header__logo">
          <img src="/images/2p.svg" alt="Логотип два порога" />
        </a>

        <div class="header-div"></div>
        <nav class="header__nav">
          <a href="{{ route('home') }}#features" class="header__link">Преимуществa</a>
          {{--
          <a href="{{ route('catalog') }}" class="header__link">Каталог</a> --}}
          {{-- <a href="{{ route('blog') }}" class="header__link">Блог</a> --}}
          <a href="{{ route('home') }}#delivery" class="header__link">Доставка</a>

          <a href="{{ route('home') }}#about" class="header__link">О нас</a>
          <a href="{{ route('home') }}#faq" class="header__link">FAQ</a>

          {{-- <a href="{{ route('home') }}#reviews" class="header__link">Отзывы</a> --}}
        </nav>
      </div>

      <div class="header__btns">
        <div class="header__phone">
          <div class="header__phone_top">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="lucide lucide-phone-call-icon lucide-phone-call">
              <path d="M13 2a9 9 0 0 1 9 9" />
              <path d="M13 6a5 5 0 0 1 5 5" />
              <path
                d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384" />
            </svg>
            <a href="tel:{{ $main_info->phone }}">{{ $main_info->phone }}</a>
          </div>
          <span>Бесплатный звонок по РФ</span>
        </div>
        {{-- @if (!empty($main_info->telegram))
          <a href="{{ $main_info->telegram }}" target="_blank" class="social-link">
            <img src="{{ asset('images/logos/tg.svg') }}" alt="Логотип телеграм" />
          </a>
        @endif

        @if (!empty($main_info->whats_app))
          <a href="{{ $main_info->whats_app }}" target="_blank" class="social-link">
            <img src="{{ asset('images/logos/wa.svg') }}" alt="Логотип ватсап" />
          </a>
        @endif --}}
        <div class="header-div"></div>
        <button class="btn header-consultation-btn" data-micromodal-trigger="modal-1">
          Обратный звонок
        </button>



        <button class="burger">
          <span></span>
          <span></span>
          <span></span>
        </button>
      </div>
    </div>
  </div>
</header>

<div class="mobile-nav">
  <div class="mobile-nav__wrap">
    <nav class="mobile-nav__nav">
      <a href="{{ route('home') }}#features" class="header__link">Преимущества</a>
      {{-- <a href="{{ route('catalog') }}" class="header__link">Каталог</a> --}}
      <a href="{{ route('blog') }}" class="header__link">Блог</a>
      <a href="{{ route('home') }}#about" class="header__link">О нас</a>
      <a href="{{ route('home') }}#delivery" class="header__link">Доставка</a>
      <a href="{{ route('home') }}#faq" class="header__link">FAQ</a>
      {{-- <a href="{{ route('home') }}#reviews" class="header__link">Отзывы</a> --}}
    </nav>


    <div class="mobile-nav__bottom">
      <div class="mobile-nav__bottom-contacts">
        <div class="header__phone">
          <a href="tel:{{ $main_info->phone }}">{{ $main_info->phone }}</a>
          <span>Бесплатный звонок по РФ</span>
        </div>
        <div class="mobile-nav__bottom-socials">
          @if (!empty($main_info->telegram))
            <a href="{{ $main_info->telegram }}" target="_blank" class="social-link">
              <img src="{{ asset('images/logos/tg.svg') }}" alt="Логотип телеграм" />
            </a>
          @endif

          @if (!empty($main_info->whats_app))
            <a href="{{ $main_info->whats_app }}" target="_blank" class="social-link">
              <img src="{{ asset('images/logos/wa.svg') }}" alt="Логотип ватсап" />
            </a>
          @endif
        </div>
      </div>

      <button class="btn header-consultation-btn" data-micromodal-trigger="modal-1">
        Обратный звонок
      </button>
    </div>
  </div>
</div>
