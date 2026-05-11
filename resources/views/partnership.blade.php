@extends('layouts.front')

@section('content')
    @php
        $benefits = [
            'Специальные цены на детали',
            'Персональный менеджер',
            'Работает по всей РФ',
            'Приоритет в отправке',
        ];

        $audiences = [
            [
                'title' => 'СТО и частные кузовные сервисы',
                'image' => asset('images/partnership/icon-service.png'),
            ],
            [
                'title' => 'Оптовые и розничные сети',
                'image' => asset('images/partnership/icon-retail.png'),
            ],
            [
                'title' => 'Онлайн продавец запчастей',
                'image' => asset('images/partnership/icon-online.png'),
            ],
            [
                'title' => 'Дропшиппинг',
                'image' => asset('images/partnership/icon-dropship.png'),
            ],
        ];

        $partnershipPhone = $page->phone ?: $main_info->phone_clients ?: $main_info->phone;
        $partnershipPhoneHref = preg_replace('/[^0-9+]/', '', (string) $partnershipPhone);
        $brandName = 'ДВАПОРОГА';
        $partnershipTitle = str_ireplace('2POROGA', $brandName, $page->title);
    @endphp

    <main class="partnership-page">
        {{ Breadcrumbs::render('partnership') }}

        <section class="partnership-page__hero">
            <div class="container">
                <div class="partnership-page__hero-head">
                    <h1 class="partnership-page__title">{{ $partnershipTitle }}</h1>
                    <div class="partnership-page__lead">{!! $page->description !!}</div>
                </div>

                <div class="partnership-page__benefits">
                    @foreach ($benefits as $benefit)
                        <div class="partnership-page__benefit-item">{{ $benefit }}</div>
                    @endforeach

                    <a href="tel:{{ $partnershipPhoneHref }}" class="partnership-page__benefit-phone">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M13 2a9 9 0 0 1 9 9" />
                            <path d="M13 6a5 5 0 0 1 5 5" />
                            <path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384" />
                        </svg>
                        <span>
                            Номер телефона для связи с нами
                            <b>{{ $partnershipPhone }}</b>
                        </span>
                    </a>
                </div>
            </div>
        </section>

        <section class="partnership-page__audience section">
            <div class="container">
                <div class="partnership-page__section-head">
                    <h2 class="partnership-page__section-title">Приглашаем к сотрудничеству</h2>
                </div>

                <div class="partnership-page__audience-grid">
                    @foreach ($audiences as $audience)
                        <article class="partnership-page__audience-card">
                            <div class="partnership-page__audience-icon">
                                <img src="{{ $audience['image'] }}" alt="{{ $audience['title'] }}" loading="lazy">
                            </div>
                            <h3 class="partnership-page__audience-title">{{ $audience['title'] }}</h3>
                        </article>
                    @endforeach
                </div>

                <div class="partnership-page__actions">
                    <button type="button" class="partnership-page__btn" data-micromodal-trigger="modal-partnership">
                        Сотрудничать
                    </button>
                </div>
            </div>
        </section>

        <section class="partnership-page__about section">
            <div class="container">
                <div class="partnership-page__about-layout">
                    <div class="partnership-page__about-image">
                        <img src="{{ asset('images/partnership/team-photo.png') }}" alt="Команда {{ $brandName }}" loading="lazy">
                    </div>

                    <div class="partnership-page__about-content">
                        <h2 class="partnership-page__about-title">{{ $brandName }} - это</h2>

                        <ul class="partnership-page__about-list">
                            <li><b>Собственное производство.</b> Детали в наличии или изготовим за <b>1 день</b> с момента обращения</li>
                            <li>База замеров деталей на более <b>3000</b> автомобилей</li>
                            <li><b>Оплата при получении.</b> Проверяете потом оплачиваете</li>
                            <li>Используем металл ХКС и цинк <b>от 0,8 до 1.5 мм</b></li>
                            <li>Удобный <b>обмен</b> и легкий <b>возврат</b> по заказам</li>
                        </ul>

                        <button type="button" class="partnership-page__btn partnership-page__btn--about" data-micromodal-trigger="modal-partnership">
                            Написать нам
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
