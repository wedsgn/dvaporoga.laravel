<header class="header">
    <div class="container">
        <div class="header__inner">
            <div class="header__left">
                <a href="/" class="header__logo">
                    <span class="header__logo-text">2POROGA</span>
                </a>

                <div class="header__divider"></div>

                <nav class="header__nav">
                    <a href="{{ route('catalog') }}" class="header__link header__link--accent">Каталог</a>
                    <a href="{{ route('home') }}#features" class="header__link">Преимущества</a>
                    <a href="{{ route('home') }}#delivery" class="header__link">Доставка</a>
                    <a href="{{ route('home') }}#about" class="header__link">О нас</a>
                    <a href="{{ route('home') }}#faq" class="header__link">FAQ</a>
                    <a href="{{ route('partnership') }}"
                        class="header__link{{ request()->routeIs('partnership') ? ' header__link--active' : '' }}">Сотрудничество</a>
                </nav>
            </div>

            <div class="header__actions">
                @if (!empty($main_info->phone_clients))
                    <div class="header__phone header__phone--secondary">
                        <div class="header__phone-top">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                <path
                                    d="M9.55 0.75C11.4596 0.75 13.2909 1.50857 14.6412 2.85883C15.9914 4.20909 16.75 6.04044 16.75 7.95M9.55 3.95C10.6109 3.95 11.6283 4.37143 12.3784 5.12157C13.1286 5.87172 13.55 6.88913 13.55 7.95M10.2156 12.4044C10.3808 12.4803 10.567 12.4976 10.7434 12.4536C10.9197 12.4095 11.0759 12.3067 11.186 12.162L11.47 11.79C11.619 11.5913 11.8123 11.43 12.0345 11.3189C12.2566 11.2078 12.5016 11.15 12.75 11.15H15.15C15.5743 11.15 15.9813 11.3186 16.2814 11.6186C16.5814 11.9187 16.75 12.3257 16.75 12.75V15.15C16.75 15.5743 16.5814 15.9813 16.2814 16.2814C15.9813 16.5814 15.5743 16.75 15.15 16.75C11.3309 16.75 7.66819 15.2329 4.96766 12.5323C2.26714 9.83181 0.75 6.16912 0.75 2.35C0.75 1.92565 0.918571 1.51869 1.21863 1.21863C1.51869 0.918571 1.92565 0.75 2.35 0.75H4.75C5.17435 0.75 5.58131 0.918571 5.88137 1.21863C6.18143 1.51869 6.35 1.92565 6.35 2.35V4.75C6.35 4.99839 6.29217 5.24337 6.18108 5.46554C6.07 5.68771 5.90871 5.88097 5.71 6.03L5.3356 6.3108C5.18873 6.42294 5.08522 6.58247 5.04263 6.76228C5.00005 6.94209 5.02103 7.1311 5.102 7.2972C6.19534 9.51789 7.99354 11.3138 10.2156 12.4044Z"
                                    stroke="#383838" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <a class="header__phone-link">{{ $main_info->phone_clients }}</a>
                        </div>
                        <span class="header__phone-note">Для постоянных клиентов</span>
                    </div>
                @endif

                <div class="header__phone">
                    <div class="header__phone-top">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-phone-call-icon lucide-phone-call">
                            <path d="M13 2a9 9 0 0 1 9 9" />
                            <path d="M13 6a5 5 0 0 1 5 5" />
                            <path
                                d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384" />
                        </svg>
                        <a class="header__phone-link" href="tel:{{ $main_info->phone }}">{{ $main_info->phone }}</a>
                    </div>
                    <span class="header__phone-note">Бесплатный звонок</span>
                </div>

                <div class="header__divider"></div>

                <button class="btn header__callback" data-micromodal-trigger="modal-1">
                    <span class="header__callback-text header__callback-text--desktop">Обратный звонок</span>
                    <span class="header__callback-text header__callback-text--tablet">Бесплатный звонок</span>
                </button>

                <button class="burger">
                    <span class="burger__line"></span>
                    <span class="burger__line"></span>
                    <span class="burger__line"></span>
                </button>
            </div>
        </div>
    </div>
</header>

<div class="mobile-nav">
    <div class="mobile-nav__wrap">
        <nav class="mobile-nav__nav">
            <a href="{{ route('catalog') }}" class="mobile-nav__link mobile-nav__link--accent">Каталог</a>
            <a href="{{ route('home') }}#features" class="mobile-nav__link">Преимущества</a>
            <a href="{{ route('home') }}#about" class="mobile-nav__link">О нас</a>
            <a href="{{ route('home') }}#delivery" class="mobile-nav__link">Доставка</a>
            <a href="{{ route('home') }}#faq" class="mobile-nav__link">FAQ</a>
            <a href="{{ route('partnership') }}"
                class="mobile-nav__link{{ request()->routeIs('partnership') ? ' mobile-nav__link--active' : '' }}">Сотрудничество</a>
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
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
