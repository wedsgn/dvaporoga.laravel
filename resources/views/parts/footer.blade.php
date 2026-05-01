<footer class="footer section">
    <div class="container">
        <div class="footer-main">
            <div class="footer-main__grid">
                <div class="footer-main__brand">
                    <a href="{{ route('home') }}" class="footer-logo">
                        <img src="{{ asset('images/footerlogo.svg') }}" alt="2POROGA">
                    </a>

                    <div class="footer-main__descr">
                        Ремонтные арки и пороги для всех автомобилей
                    </div>

                    <div class="footer-main__company">
                        {{ $main_info->company_details }}
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
                        <a href="{{ route('partnership') }}" class="footer__nav-link">Сотрудничество</a>
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

                <div class="footer-main__contacts">
                    <div class="footer-main__title">Контакты для связи</div>

                    <a href="tel:{{ $main_info->phone }}" class="footer-main__phone">
                        <svg class="footer-main__phone-icon" width="16" height="16" viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M22 16.92V20a2 2 0 0 1-2.18 2 19.86 19.86 0 0 1-8.63-3.07A19.5 19.5 0 0 1 5.09 12.8 19.86 19.86 0 0 1 2 4.18 2 2 0 0 1 4 2h3.09a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                        </svg>
                        <span>{{ $main_info->phone }}</span>
                    </a>

                    <button type="button" class="btn btn--primary footer-main__callback" data-micromodal-trigger="modal-1">
                        Заказать звонок
                    </button>

                    <div class="footer-socials">
                        @if (!empty($main_info->telegram))
                            <a href="{{ $main_info->telegram }}" target="_blank" class="footer-social footer-social--telegram" rel="noopener noreferrer">
                                <img src="{{ asset('images/socials/footer-telegram.svg') }}" alt="Telegram">
                            </a>
                        @endif

                        @if (!empty($main_info->vk))
                            <a href="{{ $main_info->vk }}" target="_blank" class="footer-social footer-social--vk" rel="noopener noreferrer">
                                <img src="{{ asset('images/socials/footer-vk.svg') }}" alt="VK">
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="footer-main__legal">
                <div class="footer-main__legal-company">
                    {{ $main_info->company_details }}
                </div>

                <div class="footer-main__legal-note">
                    Сайт не является офертой
                </div>
            </div>
        </div>
    </div>
</footer>
