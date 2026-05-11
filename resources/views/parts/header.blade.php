<header class="header">
    <div class="container">
        <div class="header__wrap">
            <div class="header__left">
                <a href="/" class="header__logo">
                    <span class="header__logo-text">ДВАПОРОГА</span>
                </a>

                <div class="header-div"></div>
                <nav class="header__nav">
                    <a href="{{ route('catalog') }}" class="header__link --highlighted">Каталог</a>
                    <a href="{{ route('home') }}#features" class="header__link">Преимуществa</a>


                    {{-- <a href="{{ route('blog') }}" class="header__link">Блог</a> --}}
                    <a href="{{ route('home') }}#delivery" class="header__link">Доставка</a>

                    <a href="{{ route('home') }}#about" class="header__link">О нас</a>
                    <a href="{{ route('home') }}#faq" class="header__link">FAQ</a>
                    <a href="{{ route('partnership') }}" class="header__link{{ request()->routeIs('partnership') ? ' is-active' : '' }}">Сотрудничество</a>

                    {{-- <a href="{{ route('home') }}#reviews" class="header__link">Отзывы</a> --}}
                </nav>
            </div>

            <div class="header__btns">
              @if (!empty($main_info->phone_clients))
                <div class="header__phone header__phone--secondary">
                    <div class="header__phone_top">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-phone-call-icon lucide-phone-call">
                            <path d="M13 2a9 9 0 0 1 9 9" />
                            <path d="M13 6a5 5 0 0 1 5 5" />
                            <path
                                d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384" />
                        </svg>
                        <a>{{ $main_info->phone_clients }}</a>
                    </div>
                    <span>Для постоянных клиентов</span>
                </div>
                @endif
                <div class="header__phone">
                    <div class="header__phone_top">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-phone-call-icon lucide-phone-call">
                            <path d="M13 2a9 9 0 0 1 9 9" />
                            <path d="M13 6a5 5 0 0 1 5 5" />
                            <path
                                d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384" />
                        </svg>
                        <a href="tel:{{ $main_info->phone }}">{{ $main_info->phone }}</a>
                    </div>
                    <span>Бесплатный звонок по РФ</span>
                </div>
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
            <a href="{{ route('catalog') }}" class="header__link --highlighted mobile-nav__catalog-btn">Каталог</a>
            <a href="{{ route('home') }}#features" class="header__link">Преимущества</a>
            {{-- <a href="{{ route('blog') }}" class="header__link">Блог</a> --}}
            <a href="{{ route('home') }}#about" class="header__link">О нас</a>
            <a href="{{ route('home') }}#delivery" class="header__link">Доставка</a>
            <a href="{{ route('home') }}#faq" class="header__link">FAQ</a>
            <a href="{{ route('partnership') }}" class="header__link{{ request()->routeIs('partnership') ? ' is-active' : '' }}">Сотрудничество</a>
        </nav>

        <div class="mobile-nav__bottom">
            <div class="mobile-nav__bottom-socials">
                @if (!empty($main_info->telegram))
                    <a href="{{ $main_info->telegram }}" target="_blank" class="mobile-nav__social-item">
                        <img src="{{ asset('images/logos/tg.svg') }}" alt="Telegram" />
                        <span>Telegram</span>
                    </a>
                @endif

                @if (!empty($main_info->whats_app))
                    <a href="{{ $main_info->whats_app }}" target="_blank" class="mobile-nav__social-item">
                        <img src="{{ asset('images/logos/wa.svg') }}" alt="WhatsApp" />
                        <span>WhatsApp</span>
                    </a>
                @endif

            </div>

            <div class="mobile-nav__contact-card">

                <a href="tel:{{ $main_info->phone }}" class="mobile-nav__contact-row">
                    <span class="mobile-nav__contact-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path
                                d="M22 16.92V21a2 2 0 0 1-2.18 2A19.86 19.86 0 0 1 11.19 19.93a19.5 19.5 0 0 1-6-6A19.86 19.86 0 0 1 2.08 4.18 2 2 0 0 1 4.06 2h4.09a2 2 0 0 1 2 1.72c.12.89.32 1.76.59 2.6a2 2 0 0 1-.45 2.11L9.09 9.91a16 16 0 0 0 6 6l1.48-1.2a2 2 0 0 1 2.11-.45c.84.27 1.71.47 2.6.59A2 2 0 0 1 22 16.92z" />
                        </svg>
                    </span>
                    <span>{{ $main_info->phone }}</span>
                </a>
                @if (!empty($main_info->phone_clients))
                <div class="mobile-nav__contact-row mobile-nav__contact-row--plain">
                    <span class="mobile-nav__contact-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path
                                d="M22 16.92V21a2 2 0 0 1-2.18 2A19.86 19.86 0 0 1 11.19 19.93a19.5 19.5 0 0 1-6-6A19.86 19.86 0 0 1 2.08 4.18 2 2 0 0 1 4.06 2h4.09a2 2 0 0 1 2 1.72c.12.89.32 1.76.59 2.6a2 2 0 0 1-.45 2.11L9.09 9.91a16 16 0 0 0 6 6l1.48-1.2a2 2 0 0 1 2.11-.45c.84.27 1.71.47 2.6.59A2 2 0 0 1 22 16.92z" />
                        </svg>
                    </span>
                    <span class="mobile-nav__contact-text">
                        <span>{{ $main_info->phone_clients }}</span>
                        <small>Для постоянных клиентов</small>
                    </span>
                </div>
                @endif
                <div class="mobile-nav__contact-row">
                    <span class="mobile-nav__contact-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M12 6v6l4 2" />
                        </svg>
                    </span>
                    <span>ПН – ВС: с 9 до 21</span>
                </div>
            </div>
        </div>
    </div>
</div>
