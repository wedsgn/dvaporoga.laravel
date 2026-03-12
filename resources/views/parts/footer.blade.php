<footer class="footer section">
    <div class="container">
        <div class="footer-top">
            <div class="footer-top__wrap">
                <div class="footer-top__left">
                    <a href="{{ route('home') }}" class="footer-logo">
                        <img src="{{ asset('images/footerlogo.svg') }}" alt="2POROGA">
                    </a>
                </div>

                <div class="footer-top__right">
                    <div class="footer-socials">
                        @if (!empty($main_info->whats_app))
                            <a href="{{ $main_info->whats_app }}" target="_blank" class="footer-social">
                                <img src="{{ asset('images/socials/wa.svg') }}" alt="WhatsApp">
                            </a>
                        @endif

                        @if (!empty($main_info->telegram))
                            <a href="{{ $main_info->telegram }}" target="_blank" class="footer-social">
                                <img src="{{ asset('images/socials/tg.svg') }}" alt="Telegram">
                            </a>
                        @endif

                        <a href="https://vk.com/avtoporogiru" target="_blank" class="footer-social">
                            <img src="{{ asset('images/socials/vk.svg') }}" alt="VK">
                        </a>
                    </div>

                    <div class="header__phone --footer">
                        <div class="header__phone_top">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-phone-call-icon lucide-phone-call">
                                <path d="M13 2a9 9 0 0 1 9 9"></path>
                                <path d="M13 6a5 5 0 0 1 5 5"></path>
                                <path
                                    d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384">
                                </path>
                            </svg>
                            <a href="tel:{{ $main_info->phone }}">{{ $main_info->phone }}</a>
                        </div>
                        <span>Бесплатный звонок по РФ</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-main">
            <div class="footer-main__grid">
                <div class="footer-main__about">
                    <div class="footer-main__descr">
                        Ремонтные арки и пороги для всех автомобилей
                    </div>
                </div>

                <div class="footer-main__menu">
                    <div class="footer-main__title">Информация</div>

                    <nav class="footer-main__nav">
                        <a href="{{ route('catalog') }}" class="footer__nav-link">Каталог</a>
                        <a href="{{ route('home') }}#features" class="footer__nav-link">Наши преимущества</a>
                        <a href="{{ route('home') }}#examples" class="footer__nav-link">Наши работы</a>
                        <a href="{{ route('home') }}#about" class="footer__nav-link">О нас</a>
                        <a href="{{ route('home') }}#delivery" class="footer__nav-link">Доставка и оплата</a>
                    </nav>
                </div>

                <div class="footer-main__docs">
                    <div class="footer-main__title">Документы</div>

                    <div class="footer-main__docs-list">
                        <a href="{{ asset('docs/ОФЕРТА.docx') }}" target="_blank" rel="noopener noreferrer" class="footer__nav-link">
                            Договор оферты
                        </a>

                        <a href="{{ asset('docs/ПОЛИТИКА КОНФИДЕНЦИАЛЬНОСТИ.docx') }}" target="_blank" rel="noopener noreferrer" class="footer__nav-link">
                            Политика конфиденциальности
                        </a>

                        <a href="{{ asset('docs/СОГЛАСИЕ НА ОБРАБОТКУ ПЕРСОНАЛЬНЫХ ДАННЫХ.docx') }}" target="_blank" rel="noopener noreferrer" class="footer__nav-link">
                            Согласие на обработку персональных данных
                        </a>

                        <a href="{{ asset('docs/СОГЛАСИЕ НА ПОЛУЧЕНИЕ ИНФОРМАЦИОННЫХ РАССЫЛОК.docx') }}" target="_blank" rel="noopener noreferrer" class="footer__nav-link">
                            Согласие на получение информационной рассылки
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-copy">
        <div class="container">
            <div class="footer-copy__wrap">
                <p>{{ $main_info->company_details }}</p>
            </div>
        </div>
    </div>
</footer>
