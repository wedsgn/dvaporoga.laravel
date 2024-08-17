@extends('layouts.front')

@section('content')
    <main>
        {{-- Hero --}}
        <section class="index-hero-section section">
            <div class="container">
                <h1 class="h1 uppercase">
                    <span>Ремонтные <br /> пороги</span>

                    и <span>арки</span>
                </h1>
                <p class="index-hero__subtitle">Без предоплаты и быстрой доставкой</p>

                <div class="index-hero__inner">
                    <div class="index-hero__wrap">
                        <p class="index-hero-callout">
                            Оставьте заявку, мы свяжемся с вами в течении
                            <span>5 минут</span> и ответим на все вопросы.
                        </p>

                        <form class="index-hero-form" id="indexHeroForm">
                            @csrf
                            <input type="text" placeholder="Имя" class="input" name="name" required />
                            <input type="tel" placeholder="+7 (___) ___ __ __" class="input" name="phone" required />
                            <input type="hidden" name="form_id" value="Форма на главной странице">
                            <button class="btn lg" type="submit" id="indexHeroFormSubmit">Отправить</button>

                            <p class="copyright">
                                Нажимая кнопку “Отправить” вы соглашаетесь с нашей
                                <a href="/Политика_в_области_обработки_персональных_данных.pdf" target="_blank"> политикой
                                    конфиденциальности </a>
                            </p>
                        </form>

                        <script>
                            const form = document.querySelector('#indexHeroForm');
                            const submitButton = document.querySelector('#indexHeroFormSubmit');

                            form.addEventListener('submit', async function(event) {
                                event.preventDefault();
                                const formData = new FormData(form);

                                try {
                                    const response = await fetch("{{ route('request_consultation.store') }}", {
                                        method: 'POST',
                                        body: formData
                                    });

                                    if (response.ok) {
                                        form.reset();
                                        MicroModal.close('modal-1');
                                        MicroModal.show('modal-2');

                                        setTimeout(() => {
                                            MicroModal.close('modal-2');
                                        }, 3000);
                                    } else {
                                        throw new Error('Ошибка отправки');
                                    }
                                } catch (error) {
                                    alert(error.message);
                                } finally {
                                    submitButton.disabled = false;
                                }
                            });
                        </script>

                    </div>

                    <div class="index-hero__scheme">
                        <picture>
                            <source srcset="{{ asset('/images/hero-car.webp') }}" type="image/webp"><img
                                src="/images/hero-car.png" alt="Схема автомобиля" />
                        </picture>

                        {{-- Арка --}}
                        <div class="scheme-dot">
                            <div class="scheme-dot-center"></div>
                        </div>

                        {{-- Порог --}}
                        <div class="scheme-dot">
                            <div class="scheme-dot-center"></div>
                        </div>

                        {{-- Дверь --}}
                        <div class="scheme-dot">
                            <div class="scheme-dot-center"></div>
                        </div>


                        {{-- Арка зад --}}
                        <div class="scheme-dot">
                            <div class="scheme-dot-center"></div>
                        </div>

                        {{-- Багажник --}}
                        <div class="scheme-dot --bagaj">
                            <div class="scheme-dot-center"></div>
                        </div>

                        {{-- Арка
                        <div class="scheme-item-wrap --arka">
                            <div class="scheme-item">

                                <div class="scheme-item__img">
                                    <img src="{{ asset('images/hero/arka.png') }}" alt="">
                                </div>
                                <div class="scheme-item__info">
                                    <div class="scheme-item__title">Передняя арка</div>
                                    <div class="scheme-item__price">от 1950 руб</div>
                                </div>
                            </div>

                        </div>

                        <div class="scheme-item-wrap --porog">
                            <div class="scheme-item">
                                <div class="scheme-item__img">
                                    <img src="" alt="">
                                </div>
                                <div class="scheme-item__info">
                                    <div class="scheme-item__title">Порог</div>
                                    <div class="scheme-item__price">от 1950 руб</div>
                                </div>
                            </div>

                            <div class="scheme-item">
                                <div class="scheme-item__img">
                                    <img src="" alt="">
                                </div>
                                <div class="scheme-item__info">
                                    <div class="scheme-item__title">Усилитель порога</div>
                                    <div class="scheme-item__price">от 1950 руб</div>
                                </div>
                            </div>
                        </div>

                        <div class="scheme-item-wrap --arka-r">
                            <div class="scheme-item">
                                <div class="scheme-item__img">
                                    <img src="" alt="">
                                </div>
                                <div class="scheme-item__info">
                                    <div class="scheme-item__title">Арка задняя</div>
                                    <div class="scheme-item__price">от 1950 руб</div>
                                </div>
                            </div>

                            <div class="scheme-item">
                                <div class="scheme-item__img">
                                    <img src="" alt="">
                                </div>
                                <div class="scheme-item__info">
                                    <div class="scheme-item__title">Арка внутренняя</div>
                                    <div class="scheme-item__price">от 1950 руб</div>
                                </div>
                            </div>
                        </div>


                        <div class="scheme-item-wrap --penka">

                            <div class="scheme-item">
                                <div class="scheme-item__img">
                                    <img src="" alt="">
                                </div>
                                <div class="scheme-item__info">
                                    <div class="scheme-item__title">Пенка двери</div>
                                    <div class="scheme-item__price">от 1950 руб</div>
                                </div>
                            </div>
                        </div>


                        <div class="scheme-item-wrap --bagaj">

                            <div class="scheme-item">
                                <div class="scheme-item__img">
                                    <img src="" alt="">
                                </div>
                                <div class="scheme-item__info">
                                    <div class="scheme-item__title">Пенка Багажника</div>
                                    <div class="scheme-item__price">от 1950 руб</div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </section>

        <x-section.features />
        <x-section.marks :items="$car_makes" />
        <x-section.products :items="$products" />
        <x-section.installing />
        <x-section.how-we-work />
        <x-section.about-parts />
        <x-section.about-company />
        <x-section.blog :items="$blogs" />
        <x-section.faq />



    </main>
@endsection
