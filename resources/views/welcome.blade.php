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

                        <form class="index-hero-form" action="{{ route('request_consultation.store', 'index-hero-form') }}" id="indexHeroForm" method="POST">
                            @csrf
                            <input type="text" placeholder="Имя" class="input" name="name" required />
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            <input type="tel" placeholder="+7 (___) ___ __ __" class="input" name="phone" required />
                            @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            <button class="btn lg" type="submit">Отправить</button>

                            <p class="copyright">
                                Нажимая кнопку “Отправить” вы соглашаетесь с нашей
                                <a href="" download=""> политикой конфиденциальности </a>
                            </p>
                        </form>

                        {{-- <script>
                            const form = document.querySelector('.index-hero-form');


                            form.addEventListener('submit', async function(event) {
                                event.preventDefault();

                                const formData = new FormData(form);
                                try {
                                    const response = await fetch('/your-endpoint', {
                                        method: 'POST',
                                        body: formData
                                    });

                                    if (!response.ok) {
                                        throw new Error('Network response was not ok');
                                    }

                                    const data = await response.json();
                                    console.log(data);
                                    // Очистка формы и удаление сообщения об ошибке
                                    form.reset();
                                    errorInput.classList.remove('error');
                                    errorMessage.textContent = '';
                                } catch (error) {
                                    console.error('There has been a problem with your fetch operation:', error);
                                    // Отображение сообщения об ошибке
                                    errorInput.classList.add('error');
                                    errorMessage.textContent = error.message;
                                }
                            });
                        </script> --}}
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
        <x-section.products :items="$products" />
        <x-section.marks :items="$car_makes" />
        <x-section.installing />
        <x-section.how-we-work />
        <x-section.about-parts />
        <x-section.about-company />
        <x-section.blog :items="$blogs" />
        <x-section.faq />



    </main>
@endsection
