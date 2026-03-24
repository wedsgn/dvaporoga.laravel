<section class="partnership-section">
    <div class="container">
        <div class="partnership-section__head">
            <h2 class="partnership-section__title h2">Сотрудничество</h2>
            <p class="partnership-section__subtitle">
                Станьте представителем нашей компании в своем регионе
            </p>
        </div>

        <div class="partnership-section__grid">
            <div class="partnership-form-card">
                <h3 class="partnership-form-card__title">Заполните данные</h3>
                <p class="partnership-form-card__text">
                    Наш менеджер Вам перезвонит в ближайшее время
                </p>

                <form class="partnership-form modal-form" method="POST"
                    action="{{ route('request_consultation.store') }}"
                    data-action="{{ route('request_consultation.store') }}" data-ym-goal="partnership"
                    data-ym-mode="manual">
                    @csrf

                    <div class="input-item">
                        <input class="input" type="text" name="name" placeholder="Ваше имя">
                        <div class="field-error" data-error-for="name"></div>
                    </div>

                    <div class="input-item">
                        <input class="input" type="tel" name="phone" placeholder="+7 (___) ___ __ __">
                        <div class="field-error" data-error-for="phone"></div>
                    </div>

                    <input type="hidden" name="form_id" value="partnership-form">
                    <input type="hidden" name="current_url" value="{{ request()->fullUrl() }}">

                    <input type="hidden" name="utm_source" value="{{ $utm['utm_source'] ?? '' }}">
                    <input type="hidden" name="utm_medium" value="{{ $utm['utm_medium'] ?? '' }}">
                    <input type="hidden" name="utm_campaign" value="{{ $utm['utm_campaign'] ?? '' }}">
                    <input type="hidden" name="utm_term" value="{{ $utm['utm_term'] ?? '' }}">
                    <input type="hidden" name="utm_content" value="{{ $utm['utm_content'] ?? '' }}">
                    <input type="hidden" name="cm_id" value="{{ $utm['cm_id'] ?? '' }}">

                    <div class="form-policy-wrap">
                        <div class="form-policy">
                            <input type="checkbox" id="policy-partnership" name="policy" value="1" required checked>
                            <label for="policy-partnership">
                                Я соглашаюсь с
                                <a href="{{ url('/policy.pdf') }}" target="_blank" rel="noopener noreferrer">
                                    политикой конфиденциальности
                                </a>
                                и даю согласие на обработку персональных данных
                            </label>
                        </div>
                        <div class="field-error field-error--policy" data-error-for="policy"></div>
                    </div>

                    <button type="submit" class="btn partnership-form-card__btn">
                        Стать партнёром
                    </button>
                </form>
            </div>

            <div class="partnership-info-card">
                <h3 class="partnership-info-card__title">Приглашаем стать партнером:</h3>

                <ul class="partnership-info-card__list">
                    <li>СТО и частные кузовные сервисы</li>
                    <li>Оптовые и розничные сети</li>
                    <li>Оптовые поставщики авто запчастей</li>
                </ul>

                <div class="partnership-info-card__note">
                    Для Вас действуют специальные гибкие условия
                    по поставке запчастей и работе с клиентами
                </div>
                <div class="partnership-info-card__phone">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-phone-call-icon lucide-phone-call">
                            <path d="M13 2a9 9 0 0 1 9 9" />
                            <path d="M13 6a5 5 0 0 1 5 5" />
                            <path
                                d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384" />
                        </svg>

                    <span>{{ $main_info->phone_clients }}</span>
                </div>
                <span>Для постоянных клиентов</span>
            </div>
        </div>
    </div>
</section>
