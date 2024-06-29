@extends('layouts.front')

@section('content')
    <main>
        {{-- Hero --}}
        <section class="index-hero-section section">
            <div class="container">
                <h1 class="h1 uppercase">
                    <span>Ремонтные пороги</span>
                    <br />
                    и <span>арки</span> без предоплат
                </h1>
                <p class="index-hero__subtitle">И бесплатной доставкой</p>

                <div class="index-hero__inner">
                    <div class="index-hero__wrap">
                        <p class="index-hero-callout">
                            Оставьте заявку, мы свяжемся с вами в течении
                            <span>7 минут</span> и ответим на все вопросы.
                        </p>

                        <form class="index-hero-form" action="">
                            <input type="text" placeholder="Имя" class="input" required />
                            <input type="tel" placeholder="+7 (___) ___ __ __" class="input" required />
                            <button class="btn lg" type="submit">Отправить</button>

                            <p class="copyright">
                                Нажимая кнопку “Отправить” вы соглашаетесь с нашей
                                <a href="" download=""> политикой конфиденциальности </a>
                            </p>
                        </form>
                    </div>

                    <div class="index-hero__scheme">
                        <picture>
                            <source srcset="/images/hero-car.webp" type="image/webp"><img src="/images/hero-car.png"
                                alt="Схема автомобиля" />
                        </picture>
                    </div>
                </div>
            </div>
        </section>

        <x-section.features />
        <x-section.products />
        <x-section.marks />
        <x-section.installing />
        <x-section.how-we-work />
        <!-- Тут калькулятор -->
        <x-section.about-company />
        <x-section.blog />
        <x-section.faq />

        <div class="modal micromodal-slide" id="modal-1" aria-hidden="true">
            <div class="modal__overlay" tabindex="-1" data-micromodal-close>
                <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
                    <header class="modal__header">
                        <h2 class="modal__title" id="modal-1-title">Micromodal</h2>
                        <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
                    </header>
                    <main class="modal__content" id="modal-1-content">
                        <p>
                            Try hitting the <code>tab</code> key and notice how the focus stays
                            within the modal itself. Also, <code>esc</code> to close modal.
                        </p>
                    </main>
                    <footer class="modal__footer">
                        <button class="modal__btn modal__btn-primary">Continue</button>
                        <button class="modal__btn" data-micromodal-close aria-label="Close this dialog window">
                            Close
                        </button>
                    </footer>
                </div>
            </div>
        </div>

    </main>
@endsection
