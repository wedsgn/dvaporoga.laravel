<header class="header">
    <div class="container">
        <div class="header__wrap">
            <a href="/" class="header__logo">
                <img src="/images/logo.svg" alt="Логотип два порога" />
            </a>

            <nav class="header__nav">
                <a href="{{ route('home') }}#features" class="header__link">Преимущества</a>
                <a href="{{ route('home') }}#prices" class="header__link">Цены</a>
                <a href="{{ route('catalog') }}" class="header__link">Каталог</a>
                <a href="{{ route('blog') }}" class="header__link">Блог</a>

                <a href="{{ route('home') }}#about" class="header__link">О нас</a>
                {{-- <a href="{{ route('home') }}#reviews" class="header__link">Отзывы</a> --}}
            </nav>

            <div class="header__btns">
                <div class="header__phone">
                    <a href="tel:{{ $main_info->phone }}">{{ $main_info->phone }}</a>
                    <span>Беспланый звонок по РФ</span>
                </div>

                <a href="{{ $main_info->telegram }}" target="_blank" class="social-link">
                    <img src="{{ asset('images/logos/tg.svg') }}" alt="Логотип телеграм" />
                </a>
                <a href="{{ $main_info->whats_app }}" target="_blank" class="social-link">
                    <img src="{{ asset('images/logos/wa.svg') }}" alt="Логотип ватсап" />
                </a>

                <button class="btn header-consultation-btn" data-micromodal-trigger="modal-1">
                    Консультация
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
            <a href="{{ route('home') }}#prices" class="header__link">Цены</a>
            <a href="{{ route('catalog') }}" class="header__link">Каталог</a>
            <a href="{{ route('blog') }}" class="header__link">Блог</a>
            <a href="{{ route('home') }}#about" class="header__link">О нас</a>
            {{-- <a href="{{ route('home') }}#reviews" class="header__link">Отзывы</a> --}}
        </nav>


        <div class="mobile-nav__bottom">
            <div class="mobile-nav__bottom-contacts">
                <div class="header__phone">
                    <a href="tel:{{ $main_info->phone }}">{{ $main_info->phone }}</a>
                    <span>Беспланый звонок по РФ</span>
                </div>
                <div class="mobile-nav__bottom-socials">
                    <a href="{{ $main_info->telegram }}" target="_blank" class="social-link">
                        <img src="{{ asset('images/logos/tg.svg') }}" alt="Логотип телеграм" />
                    </a>
                    <a href="{{ $main_info->whats_app }}" target="_blank" class="social-link">
                        <img src="{{ asset('images/logos/wa.svg') }}" alt="Логотип ватсап" />
                    </a>
                </div>
            </div>

            <button class="btn header-consultation-btn" data-micromodal-trigger="modal-1">
                Консультация
            </button>
        </div>
    </div>
</div>
