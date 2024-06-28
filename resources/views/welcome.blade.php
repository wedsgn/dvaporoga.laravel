@extends('layouts.front')

@section('content')
    <main>
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

        <section class="features-section section">
            <div class="container">
                <div class="features-items__wrap">
                    <!-- item -->
                    <div class="feature-item">
                        <div class="feature-item__icon">
                            <img src="/images/icons/truck.svg" alt="Иконка" />
                        </div>
                        <p>
                            Доставляем бесплатно <br />
                            по всей России
                        </p>
                    </div>

                    <!-- item -->
                    <div class="feature-item">
                        <div class="feature-item__icon">
                            <img src="/images/icons/wallet.svg" alt="Иконка" />
                        </div>
                        <p>
                            Оплата производится <br />
                            при получении детали
                        </p>
                    </div>

                    <!-- item -->
                    <div class="feature-item">
                        <div class="feature-item__icon">
                            <img src="/images/icons/shield.svg" alt="Иконка" />
                        </div>
                        <p>Гарантия на наши детали <br />90 дней с момента получения</p>
                    </div>

                    <!-- item -->
                    <div class="feature-item">
                        <div class="feature-item__icon">
                            <img src="/images/icons/arrows.svg" alt="Иконка" />
                        </div>
                        <p>
                            Бесплатно заменим деталь, <br />
                            при любых ситуациях
                        </p>
                    </div>

                    <!-- item -->
                    <div class="feature-item">
                        <div class="feature-item__icon">
                            <img src="/images/icons/box.svg" alt="Иконка" />
                        </div>
                        <p>
                            Надежно и бережно <br />
                            упаковываем каждую деталь
                        </p>
                    </div>

                    <!-- item -->
                    <div class="feature-item">
                        <div class="feature-item__icon">
                            <img src="/images/icons/car.svg" alt="Иконка" />
                        </div>
                        <p>
                            Создаем детали для более <br />
                            чем 3 500 моделей авто
                        </p>
                    </div>

                    <!-- item -->
                    <div class="feature-item">
                        <div class="feature-item__icon">
                            <img src="/images/icons/house.svg" alt="Иконка" />
                        </div>
                        <p>
                            Производим на <br />
                            современных станках
                        </p>
                    </div>

                    <!-- item -->
                    <div class="feature-item">
                        <div class="feature-item__icon">
                            <img src="/images/icons/nums.svg" alt="Иконка" />
                        </div>
                        <p>
                            Полное повторение <br />
                            оригальной запчасти
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="products-section">
            <div class="container">
                <div class="products-section__top">
                    <h2 class="h2">
                        <span>Фиксированная</span> <br />
                        цена на все модели
                    </h2>
                    <p>
                        Благодаря современным европейским станкам наши детали подойдут к вашей
                        машине, как родные. Цены на все изготавливаемые нами пороги -
                        фиксированная и не зависит от того, какого класса ваш авто.
                    </p>
                </div>

                <div class="products-wrap">
                    <!-- product -->
                    <div class="product">
                        <div class="product-image">
                            <img src="/images/product/porog.png" alt="Сюда вставить название товара" />
                        </div>

                        <h3 class="product-title">Ремонтный порог</h3>

                        <div class="product-info">
                            <ul class="product-list">
                                <!-- item -->
                                <li>
                                    <div class="product-info__item">
                                        <div class="product-info__item_top">
                                            <p class="product-info__item_title">Материал:</p>
                                            <div class="product-info__item_value">Холодная сталь</div>
                                        </div>
                                    </div>
                                </li>

                                <!-- item -->
                                <li>
                                    <div class="product-info__item">
                                        <div class="product-info__item_top">
                                            <p class="product-info__item_title">Толщина металла:</p>
                                            <div class="product-info__item_value">1мм</div>
                                        </div>
                                    </div>
                                </li>

                                <!-- item -->
                                <li>
                                    <div class="product-info__item">
                                        <div class="product-info__item_top">
                                            <p class="product-info__item_title">Сторона:</p>
                                            <div class="product-info__item_value">Левый+Правый</div>
                                        </div>
                                    </div>
                                </li>
                            </ul>

                            <button class="btn product-btn">Заказать сейчас</button>
                        </div>
                    </div>

                    <!-- product -->
                    <div class="product">
                        <div class="product-image">
                            <img src="/images/product/porog.png" alt="Сюда вставить название товара" />
                        </div>

                        <h3 class="product-title">Ремонтный порог</h3>

                        <div class="product-info">
                            <ul class="product-list">
                                <!-- item -->
                                <li>
                                    <div class="product-info__item">
                                        <div class="product-info__item_top">
                                            <p class="product-info__item_title">Материал:</p>
                                            <div class="product-info__item_value">Холодная сталь</div>
                                        </div>
                                    </div>
                                </li>

                                <!-- item -->
                                <li>
                                    <div class="product-info__item">
                                        <div class="product-info__item_top">
                                            <p class="product-info__item_title">Толщина металла:</p>
                                            <div class="product-info__item_value">1мм</div>
                                        </div>
                                    </div>
                                </li>

                                <!-- item -->
                                <li>
                                    <div class="product-info__item">
                                        <div class="product-info__item_top">
                                            <p class="product-info__item_title">Сторона:</p>
                                            <div class="product-info__item_value">Левый+Правый</div>
                                        </div>
                                    </div>
                                </li>
                            </ul>

                            <button class="btn product-btn">Заказать сейчас</button>
                        </div>
                    </div>

                    <!-- product -->
                    <div class="product">
                        <div class="product-image">
                            <img src="/images/product/porog.png" alt="Сюда вставить название товара" />
                        </div>

                        <h3 class="product-title">Ремонтный порог</h3>

                        <div class="product-info">
                            <ul class="product-list">
                                <!-- item -->
                                <li>
                                    <div class="product-info__item">
                                        <div class="product-info__item_top">
                                            <p class="product-info__item_title">Материал:</p>
                                            <div class="product-info__item_value">Холодная сталь</div>
                                        </div>
                                    </div>
                                </li>

                                <!-- item -->
                                <li>
                                    <div class="product-info__item">
                                        <div class="product-info__item_top">
                                            <p class="product-info__item_title">Толщина металла:</p>
                                            <div class="product-info__item_value">1мм</div>
                                        </div>
                                    </div>
                                </li>

                                <!-- item -->
                                <li>
                                    <div class="product-info__item">
                                        <div class="product-info__item_top">
                                            <p class="product-info__item_title">Сторона:</p>
                                            <div class="product-info__item_value">Левый+Правый</div>
                                        </div>
                                    </div>
                                </li>
                            </ul>

                            <button class="btn product-btn">Заказать сейчас</button>
                        </div>
                    </div>

                    <!-- product -->
                    <div class="product">
                        <div class="product-image">
                            <img src="/images/product/porog.png" alt="Сюда вставить название товара" />
                        </div>

                        <h3 class="product-title">Ремонтный порог</h3>

                        <div class="product-info">
                            <ul class="product-list">
                                <!-- item -->
                                <li>
                                    <div class="product-info__item">
                                        <div class="product-info__item_top">
                                            <p class="product-info__item_title">Материал:</p>
                                            <div class="product-info__item_value">Холодная сталь</div>
                                        </div>
                                    </div>
                                </li>

                                <!-- item -->
                                <li>
                                    <div class="product-info__item">
                                        <div class="product-info__item_top">
                                            <p class="product-info__item_title">Толщина металла:</p>
                                            <div class="product-info__item_value">1мм</div>
                                        </div>
                                    </div>
                                </li>

                                <!-- item -->
                                <li>
                                    <div class="product-info__item">
                                        <div class="product-info__item_top">
                                            <p class="product-info__item_title">Сторона:</p>
                                            <div class="product-info__item_value">Левый+Правый</div>
                                        </div>
                                    </div>
                                </li>
                            </ul>

                            <button class="btn product-btn">Заказать сейчас</button>
                        </div>
                    </div>

                    <!-- product -->
                    <div class="product">
                        <div class="product-image">
                            <img src="/images/product/porog.png" alt="Сюда вставить название товара" />
                        </div>

                        <h3 class="product-title">Ремонтный порог</h3>

                        <div class="product-info">
                            <ul class="product-list">
                                <!-- item -->
                                <li>
                                    <div class="product-info__item">
                                        <div class="product-info__item_top">
                                            <p class="product-info__item_title">Материал:</p>
                                            <div class="product-info__item_value">Холодная сталь</div>
                                        </div>
                                    </div>
                                </li>

                                <!-- item -->
                                <li>
                                    <div class="product-info__item">
                                        <div class="product-info__item_top">
                                            <p class="product-info__item_title">Толщина металла:</p>
                                            <div class="product-info__item_value">1мм</div>
                                        </div>
                                    </div>
                                </li>

                                <!-- item -->
                                <li>
                                    <div class="product-info__item">
                                        <div class="product-info__item_top">
                                            <p class="product-info__item_title">Сторона:</p>
                                            <div class="product-info__item_value">Левый+Правый</div>
                                        </div>
                                    </div>
                                </li>
                            </ul>

                            <button class="btn product-btn">Заказать сейчас</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="marks-section section">
            <div class="container">
                <h2 class="h2">Выберите автозапчасти по марке</h2>

                <div class="mark__wrap">
                    <!-- item -->
                    <a href="/" class="mark">
                        <div class="mark-image">
                            <picture>
                                <source srcset="/images/mark/ww.webp" type="image/webp"><img src="/images/mark/ww.png"
                                    alt="Audi" />
                            </picture>
                        </div>
                        <div class="mark-info">
                            <h3 class="mark-title">VOLKSWAGEN</h3>
                            <p class="mark-count">(50 моделей)</p>
                        </div>
                    </a>

                    <!-- item -->
                    <a href="/" class="mark">
                        <div class="mark-image">
                            <picture>
                                <source srcset="/images/mark/skoda.webp" type="image/webp"><img
                                    src="/images/mark/skoda.png" alt="Audi" />
                            </picture>
                        </div>
                        <div class="mark-info">
                            <h3 class="mark-title">skoda</h3>
                            <p class="mark-count">(50 моделей)</p>
                        </div>
                    </a>

                    <!-- item -->
                    <a href="/" class="mark">
                        <div class="mark-image">
                            <picture>
                                <source srcset="/images/mark/volvo.webp" type="image/webp"><img
                                    src="/images/mark/volvo.png" alt="Audi" />
                            </picture>
                        </div>
                        <div class="mark-info">
                            <h3 class="mark-title">volvo</h3>
                            <p class="mark-count">(50 моделей)</p>
                        </div>
                    </a>

                    <!-- item -->
                    <a href="/" class="mark">
                        <div class="mark-image">
                            <picture>
                                <source srcset="/images/mark/opel.webp" type="image/webp"><img
                                    src="/images/mark/opel.png" alt="Audi" />
                            </picture>
                        </div>
                        <div class="mark-info">
                            <h3 class="mark-title">Opel</h3>
                            <p class="mark-count">(50 моделей)</p>
                        </div>
                    </a>

                    <!-- item -->
                    <a href="/" class="mark">
                        <div class="mark-image">
                            <picture>
                                <source srcset="/images/mark/kia.webp" type="image/webp"><img src="/images/mark/kia.png"
                                    alt="Kia Logo" />
                            </picture>
                        </div>
                        <div class="mark-info">
                            <h3 class="mark-title">kia</h3>
                            <p class="mark-count">(50 моделей)</p>
                        </div>
                    </a>

                    <!-- item -->
                    <a href="/" class="mark">
                        <div class="mark-image">
                            <picture>
                                <source srcset="/images/mark/reno.webp" type="image/webp"><img
                                    src="/images/mark/reno.png" alt="Reno" />
                            </picture>
                        </div>
                        <div class="mark-info">
                            <h3 class="mark-title">Reno</h3>
                            <p class="mark-count">(50 моделей)</p>
                        </div>
                    </a>

                    <!-- item -->
                    <a href="/" class="mark">
                        <div class="mark-image">
                            <picture>
                                <source srcset="/images/mark/mercedes.webp" type="image/webp"><img
                                    src="/images/mark/mercedes.png" alt="Mercedes" />
                            </picture>
                        </div>
                        <div class="mark-info">
                            <h3 class="mark-title">Mercedes</h3>
                            <p class="mark-count">(50 моделей)</p>
                        </div>
                    </a>

                    <!-- item -->
                    <a href="/" class="mark">
                        <div class="mark-image">
                            <picture>
                                <source srcset="/images/mark/ford.webp" type="image/webp"><img
                                    src="/images/mark/ford.png" alt="Ford logo" />
                            </picture>
                        </div>
                        <div class="mark-info">
                            <h3 class="mark-title">ford</h3>
                            <p class="mark-count">(50 моделей)</p>
                        </div>
                    </a>

                    <!-- item -->
                    <a href="/" class="mark">
                        <div class="mark-image">
                            <picture>
                                <source srcset="/images/mark/audi.webp" type="image/webp"><img
                                    src="/images/mark/audi.png" alt="Audi" />
                            </picture>
                        </div>
                        <div class="mark-info">
                            <h3 class="mark-title">Audi</h3>
                            <p class="mark-count">(50 моделей)</p>
                        </div>
                    </a>

                    <!-- item -->
                    <a href="/" class="mark">
                        <div class="mark-image">
                            <picture>
                                <source srcset="/images/mark/bmw.webp" type="image/webp"><img src="/images/mark/bmw.png"
                                    alt="Bmw logo" />
                            </picture>
                        </div>
                        <div class="mark-info">
                            <h3 class="mark-title">bmw</h3>
                            <p class="mark-count">(50 моделей)</p>
                        </div>
                    </a>

                    <!-- item -->
                    <a href="/" class="mark">
                        <div class="mark-image">
                            <picture>
                                <source srcset="/images/mark/land-rover.webp" type="image/webp"><img
                                    src="/images/mark/land-rover.png" alt="Audi" />
                            </picture>
                        </div>
                        <div class="mark-info">
                            <h3 class="mark-title">land rover</h3>
                            <p class="mark-count">(50 моделей)</p>
                        </div>
                    </a>

                    <!-- item -->
                    <a href="/" class="mark">
                        <div class="mark-image">
                            <picture>
                                <source srcset="/images/mark/hyundai.webp" type="image/webp"><img
                                    src="/images/mark/hyundai.png" alt="Audi" />
                            </picture>
                        </div>
                        <div class="mark-info">
                            <h3 class="mark-title">hyundai</h3>
                            <p class="mark-count">(50 моделей)</p>
                        </div>
                    </a>
                </div>

                <a href="/" class="btn mark-section-btn">Все марки</a>
            </div>
        </section>

        <section class="installing-section section">
            <div class="container">
                <div class="installing-section__top">
                    <h2 class="h2 installing-section__title">
                        Примеры установок <br />
                        наших деталей
                    </h2>

                    <p class="installing-section__description">
                        Примеры установки наших деталей на автомобили клиентов. Примеры
                        установки наших деталей на автомобили клиентов. Примеры установки наших
                        деталей на автомобили клиентов. Примеры установки наших деталей на
                        автомобили клиентов.
                    </p>

                    <div class="installing-section-arrows slider-arrow">
                        <div class="installing-swiper-button-prev swiper-button-prev">
                            <img src="/images/icons/arr-prev.svg" alt="Prev Slide" />
                        </div>
                        <div class="installing-swiper-button-next swiper-button-next">
                            <img src="/images/icons/arr-next.svg" alt="Next Slide" />
                        </div>
                    </div>
                </div>

                <!-- Slider -->
                <div class="installing-section__slider_wrap">
                    <div class="swiper installing-section__slider">
                        <div class="swiper-wrapper">
                            <!-- Item -->
                            <div class="swiper-slide">
                                <div class="installing-item">
                                    <div class="installing-item__top">
                                        <h3 class="installing-item__top_title">
                                            Замена порогов на BMW
                                        </h3>
                                        <a href="/" class="installing-item__link">
                                            <span>Хочу такую же деталь</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                viewBox="0 0 18 18" fill="white">
                                                <path
                                                    d="M15.5855 9.39779L10.523 14.4603C10.4174 14.5658 10.2743 14.6251 10.125 14.6251C9.97573 14.6251 9.83258 14.5658 9.72703 14.4603C9.62148 14.3547 9.56219 14.2116 9.56219 14.0623C9.56219 13.9131 9.62148 13.7699 9.72703 13.6644L13.8298 9.56232H2.8125C2.66332 9.56232 2.52024 9.50306 2.41475 9.39757C2.30926 9.29208 2.25 9.14901 2.25 8.99982C2.25 8.85064 2.30926 8.70757 2.41475 8.60208C2.52024 8.49659 2.66332 8.43732 2.8125 8.43732H13.8298L9.72703 4.33529C9.62148 4.22975 9.56219 4.08659 9.56219 3.93732C9.56219 3.78806 9.62148 3.6449 9.72703 3.53936C9.83258 3.43381 9.97573 3.37451 10.125 3.37451C10.2743 3.37451 10.4174 3.43381 10.523 3.53936L15.5855 8.60186C15.6378 8.6541 15.6793 8.71613 15.7076 8.78442C15.7359 8.85271 15.7504 8.9259 15.7504 8.99982C15.7504 9.07375 15.7359 9.14694 15.7076 9.21523C15.6793 9.28351 15.6378 9.34555 15.5855 9.39779Z" />
                                            </svg>
                                        </a>
                                    </div>

                                    <div class="installing-item__photos">
                                        <!-- Item -->
                                        <div class="installing-item__item">
                                            <picture>
                                                <source srcset="/images/installing/1.webp" type="image/webp"><img
                                                    src="/images/installing/1.jpg" alt="Фото до ремонта" />
                                            </picture>
                                            <div class="installing-item__info">
                                                <h4>До ремонта</h4>
                                            </div>
                                        </div>

                                        <!-- Item -->
                                        <div class="installing-item__item">
                                            <img src="/images/installing/2.jpg" alt="Фото во время ремонта" />
                                            <div class="installing-item__info">
                                                <h4>Во время ремонта</h4>
                                            </div>
                                        </div>

                                        <!-- Item -->
                                        <div class="installing-item__item">
                                            <img src="/images/installing/3.jpg" alt="Фото после ремонта" />
                                            <div class="installing-item__info">
                                                <h4>После ремонта</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Item -->
                            <div class="swiper-slide">
                                <div class="installing-item">
                                    <div class="installing-item__top">
                                        <h3 class="installing-item__top_title">
                                            Замена порогов на BMW
                                        </h3>
                                        <a href="/" class="installing-item__link">
                                            <span>Хочу такую же деталь</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                viewBox="0 0 18 18" fill="white">
                                                <path
                                                    d="M15.5855 9.39779L10.523 14.4603C10.4174 14.5658 10.2743 14.6251 10.125 14.6251C9.97573 14.6251 9.83258 14.5658 9.72703 14.4603C9.62148 14.3547 9.56219 14.2116 9.56219 14.0623C9.56219 13.9131 9.62148 13.7699 9.72703 13.6644L13.8298 9.56232H2.8125C2.66332 9.56232 2.52024 9.50306 2.41475 9.39757C2.30926 9.29208 2.25 9.14901 2.25 8.99982C2.25 8.85064 2.30926 8.70757 2.41475 8.60208C2.52024 8.49659 2.66332 8.43732 2.8125 8.43732H13.8298L9.72703 4.33529C9.62148 4.22975 9.56219 4.08659 9.56219 3.93732C9.56219 3.78806 9.62148 3.6449 9.72703 3.53936C9.83258 3.43381 9.97573 3.37451 10.125 3.37451C10.2743 3.37451 10.4174 3.43381 10.523 3.53936L15.5855 8.60186C15.6378 8.6541 15.6793 8.71613 15.7076 8.78442C15.7359 8.85271 15.7504 8.9259 15.7504 8.99982C15.7504 9.07375 15.7359 9.14694 15.7076 9.21523C15.6793 9.28351 15.6378 9.34555 15.5855 9.39779Z" />
                                            </svg>
                                        </a>
                                    </div>

                                    <div class="installing-item__photos">
                                        <!-- Item -->
                                        <div class="installing-item__item">
                                            <picture>
                                                <source srcset="/images/installing/1.webp" type="image/webp"><img
                                                    src="/images/installing/1.jpg" alt="Фото до ремонта" />
                                            </picture>
                                            <div class="installing-item__info">
                                                <h4>До ремонта</h4>
                                            </div>
                                        </div>

                                        <!-- Item -->
                                        <div class="installing-item__item">
                                            <img src="/images/installing/2.jpg" alt="Фото во время ремонта" />
                                            <div class="installing-item__info">
                                                <h4>Во время ремонта</h4>
                                            </div>
                                        </div>

                                        <!-- Item -->
                                        <div class="installing-item__item">
                                            <img src="/images/installing/3.jpg" alt="Фото после ремонта" />
                                            <div class="installing-item__info">
                                                <h4>После ремонта</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Item -->
                            <div class="swiper-slide">
                                <div class="installing-item">
                                    <div class="installing-item__top">
                                        <h3 class="installing-item__top_title">
                                            Замена порогов на BMW
                                        </h3>
                                        <a href="/" class="installing-item__link">
                                            <span>Хочу такую же деталь</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                viewBox="0 0 18 18" fill="white">
                                                <path
                                                    d="M15.5855 9.39779L10.523 14.4603C10.4174 14.5658 10.2743 14.6251 10.125 14.6251C9.97573 14.6251 9.83258 14.5658 9.72703 14.4603C9.62148 14.3547 9.56219 14.2116 9.56219 14.0623C9.56219 13.9131 9.62148 13.7699 9.72703 13.6644L13.8298 9.56232H2.8125C2.66332 9.56232 2.52024 9.50306 2.41475 9.39757C2.30926 9.29208 2.25 9.14901 2.25 8.99982C2.25 8.85064 2.30926 8.70757 2.41475 8.60208C2.52024 8.49659 2.66332 8.43732 2.8125 8.43732H13.8298L9.72703 4.33529C9.62148 4.22975 9.56219 4.08659 9.56219 3.93732C9.56219 3.78806 9.62148 3.6449 9.72703 3.53936C9.83258 3.43381 9.97573 3.37451 10.125 3.37451C10.2743 3.37451 10.4174 3.43381 10.523 3.53936L15.5855 8.60186C15.6378 8.6541 15.6793 8.71613 15.7076 8.78442C15.7359 8.85271 15.7504 8.9259 15.7504 8.99982C15.7504 9.07375 15.7359 9.14694 15.7076 9.21523C15.6793 9.28351 15.6378 9.34555 15.5855 9.39779Z" />
                                            </svg>
                                        </a>
                                    </div>

                                    <div class="installing-item__photos">
                                        <!-- Item -->
                                        <div class="installing-item__item">
                                            <picture>
                                                <source srcset="/images/installing/1.webp" type="image/webp"><img
                                                    src="/images/installing/1.jpg" alt="Фото до ремонта" />
                                            </picture>
                                            <div class="installing-item__info">
                                                <h4>До ремонта</h4>
                                            </div>
                                        </div>

                                        <!-- Item -->
                                        <div class="installing-item__item">
                                            <img src="/images/installing/2.jpg" alt="Фото во время ремонта" />
                                            <div class="installing-item__info">
                                                <h4>Во время ремонта</h4>
                                            </div>
                                        </div>

                                        <!-- Item -->
                                        <div class="installing-item__item">
                                            <img src="/images/installing/3.jpg" alt="Фото после ремонта" />
                                            <div class="installing-item__info">
                                                <h4>После ремонта</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="how-we-work-section section">
            <div class="container">
                <!-- Top -->
                <div class="how-we-work__top">
                    <h2 class="h2 how-we-work__title">Как мы работаем?</h2>
                    <p class="how-we-work__description">
                        Сюда еще хочется написать текст, о том, что мы можем поменять деталь и
                        еще предоставить некоторые хочется написать текст, о том, что мы можем
                        поменять деталь и еще предоставить некоторые опции для покупателя Сюда
                        еще хочется написать текст, о том, что мы можем поменять деталь и еще
                        предоставить некоторые опции для покупателя
                    </p>
                </div>

                <div class="how-we-work__wrap">
                    <!-- Nums -->
                    <div class="how-we-work__wrap_nums">
                        <div class="how-we-work__num">1</div>
                        <div class="how-we-work__num_div"></div>
                        <div class="how-we-work__num">2</div>
                        <div class="how-we-work__num_div"></div>
                        <div class="how-we-work__num">3</div>
                        <div class="how-we-work__num_div"></div>
                        <div class="how-we-work__num">4</div>
                        <div class="how-we-work__num_div"></div>
                    </div>

                    <!-- Items -->
                    <div class="how-we-work__wrap_info">
                        <!-- Item -->
                        <div class="how-we-work__info">
                            <h4 class="how-we-work__info_title">Заявка</h4>
                            <p class="how-we-work__info_description">
                                <a href="#">Оставьте заявку</a> на сайте или свяжитесь с нами любым
                                удобным способом.
                            </p>
                        </div>

                        <!-- Item -->
                        <div class="how-we-work__info">
                            <h4 class="how-we-work__info_title">Консультация</h4>
                            <p class="how-we-work__info_description">
                                Наш менеджер ответит на все вопросы, поможет выбрать нужное
                                и уточнит адрес.
                            </p>
                        </div>

                        <!-- Item -->
                        <div class="how-we-work__info">
                            <h4 class="how-we-work__info_title">Доставка</h4>
                            <p class="how-we-work__info_description">
                                В течении 3-х дней мы упакуем, отправим и вышлем вам номер заказа.
                            </p>
                        </div>

                        <!-- Item -->
                        <div class="how-we-work__info">
                            <h4 class="how-we-work__info_title">Гарантии</h4>
                            <p class="how-we-work__info_description">
                                После получения мы даём вам 90 дней гарантии и время на примерку
                                детали.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Тут калькулятор -->
        <section class="about-section section">
            <div class="container">
                <h2 class="h2 about-section__title">О нашей компании</h2>

                <div class="about-section__top">
                    <div class="about-section__top_video">
                        <picture>
                            <source srcset="/images/about/1.webp" type="image/webp"><img src="/images/about/1.jpg"
                                alt="" />
                        </picture>
                    </div>

                    <div class="about-section__top_text">
                        <p>
                            Мы — производственная компания. За несколько лет работы у нас
                            накопился колоссальный опыт в производстве кузовных запчастей. Наши
                            опытные мастера, могут произвести кузовные запчасти практически на все
                            модели автомобилей.
                        </p>
                        <p>
                            Для производства кузовных порогов и арок мы используем только
                            качественную высокоуглеродистую листовую сталь. Современные станки с
                            ЧПУ позволяют избежать ошибок при производстве. Собственная логистика,
                            позволяет оперативно доставлять заказы клиентам.
                        </p>
                    </div>

                    <div class="about-section__cards">
                        <!-- item -->
                        <div class="about-section__card">
                            <div class="about-section__card_image">
                                <img src="/images/about/content-1.png" alt="Современное оборудование" />
                            </div>

                            <div class="about-section__card_content">
                                <h3 class="about-section__card_title">Современное оборудование</h3>
                                <p class="about-section__card_text">
                                    Наше производство оснащено современным оборудованием и
                                    технологиями, что позволяет нам создавать ремонтные пороги,
                                    соответствующие самым высоким стандартам качества. Мы используем
                                    только качественные материалы и комплектующие, что гарантирует
                                    долговечность и надёжность нашей продукции.
                                </p>
                            </div>
                        </div>

                        <!-- item -->
                        <div class="about-section__card">
                            <div class="about-section__card_image">
                                <img src="/images/about/content-1.png" alt="Современное оборудование" />
                            </div>

                            <div class="about-section__card_content">
                                <h3 class="about-section__card_title">Современное оборудование</h3>
                                <p class="about-section__card_text">
                                    Наше производство оснащено современным оборудованием и
                                    технологиями, что позволяет нам создавать ремонтные пороги,
                                    соответствующие самым высоким стандартам качества. Мы используем
                                    только качественные материалы и комплектующие, что гарантирует
                                    долговечность и надёжность нашей продукции.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="blog-section section">
            <div class="container">
                <h2 class="h2">Блог</h2>

                <div class="blog-section__items">
                    <div class="swiper blog-section-slider">
                        <div class="swiper-wrapper">
                            <!-- Slide -->
                            <div class="swiper-slide"><!-- Card -->
                                <div class="blog-card">
                                    <div class="blog-card__image">
                                        <picture>
                                            <source srcset="/images/blog/1.webp" type="image/webp"><img
                                                src="/images/blog/1.jpg" alt="Как правильно подобрать порог?" />
                                        </picture>
                                    </div>

                                    <div class="blog-card__content">
                                        <h3 class="blog-card__title">Как правильно подобрать порог?</h3>
                                        <p class="blog-card__description">
                                            Первые строки из статьи, которые займут, ну допустим где-то пять строк,
                                            чтобы люди могли прочитать быстреко информацию и заинтересоваться в этой
                                            статье, после чего перешли к ней и прочитали полностью. А еще это поможет
                                            сео, ведь по тексту смогут найти страницу в поиске
                                        </p>

                                        <div class="blog-card__bottom">
                                            <p class="blog-card__date">12.02.2024</p>
                                            <a href="/blog-single.html" class="blog-card__link">
                                                <span>Читать статью</span>

                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                    viewBox="0 0 18 18" fill="#1E1E1E">
                                                    <path
                                                        d="M15.5855 9.39828L10.523 14.4608C10.4174 14.5663 10.2743 14.6256 10.125 14.6256C9.97573 14.6256 9.83258 14.5663 9.72703 14.4608C9.62148 14.3552 9.56219 14.2121 9.56219 14.0628C9.56219 13.9135 9.62148 13.7704 9.72703 13.6648L13.8298 9.56281H2.8125C2.66332 9.56281 2.52024 9.50355 2.41475 9.39806C2.30926 9.29257 2.25 9.1495 2.25 9.00031C2.25 8.85113 2.30926 8.70805 2.41475 8.60257C2.52024 8.49708 2.66332 8.43781 2.8125 8.43781H13.8298L9.72703 4.33578C9.62148 4.23023 9.56219 4.08708 9.56219 3.93781C9.56219 3.78855 9.62148 3.64539 9.72703 3.53984C9.83258 3.4343 9.97573 3.375 10.125 3.375C10.2743 3.375 10.4174 3.4343 10.523 3.53984L15.5855 8.60234C15.6378 8.65458 15.6793 8.71662 15.7076 8.78491C15.7359 8.8532 15.7504 8.92639 15.7504 9.00031C15.7504 9.07423 15.7359 9.14743 15.7076 9.21572C15.6793 9.284 15.6378 9.34604 15.5855 9.39828Z">
                                                    </path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Slide -->
                            <div class="swiper-slide"><!-- Card -->
                                <div class="blog-card">
                                    <div class="blog-card__image">
                                        <picture>
                                            <source srcset="/images/blog/1.webp" type="image/webp"><img
                                                src="/images/blog/1.jpg" alt="Как правильно подобрать порог?" />
                                        </picture>
                                    </div>

                                    <div class="blog-card__content">
                                        <h3 class="blog-card__title">Как правильно подобрать порог?</h3>
                                        <p class="blog-card__description">
                                            Первые строки из статьи, которые займут, ну допустим где-то пять строк,
                                            чтобы люди могли прочитать быстреко информацию и заинтересоваться в этой
                                            статье, после чего перешли к ней и прочитали полностью. А еще это поможет
                                            сео, ведь по тексту смогут найти страницу в поиске
                                        </p>

                                        <div class="blog-card__bottom">
                                            <p class="blog-card__date">12.02.2024</p>
                                            <a href="/blog-single.html" class="blog-card__link">
                                                <span>Читать статью</span>

                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                    viewBox="0 0 18 18" fill="#1E1E1E">
                                                    <path
                                                        d="M15.5855 9.39828L10.523 14.4608C10.4174 14.5663 10.2743 14.6256 10.125 14.6256C9.97573 14.6256 9.83258 14.5663 9.72703 14.4608C9.62148 14.3552 9.56219 14.2121 9.56219 14.0628C9.56219 13.9135 9.62148 13.7704 9.72703 13.6648L13.8298 9.56281H2.8125C2.66332 9.56281 2.52024 9.50355 2.41475 9.39806C2.30926 9.29257 2.25 9.1495 2.25 9.00031C2.25 8.85113 2.30926 8.70805 2.41475 8.60257C2.52024 8.49708 2.66332 8.43781 2.8125 8.43781H13.8298L9.72703 4.33578C9.62148 4.23023 9.56219 4.08708 9.56219 3.93781C9.56219 3.78855 9.62148 3.64539 9.72703 3.53984C9.83258 3.4343 9.97573 3.375 10.125 3.375C10.2743 3.375 10.4174 3.4343 10.523 3.53984L15.5855 8.60234C15.6378 8.65458 15.6793 8.71662 15.7076 8.78491C15.7359 8.8532 15.7504 8.92639 15.7504 9.00031C15.7504 9.07423 15.7359 9.14743 15.7076 9.21572C15.6793 9.284 15.6378 9.34604 15.5855 9.39828Z">
                                                    </path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Slide -->
                            <div class="swiper-slide"><!-- Card -->
                                <div class="blog-card">
                                    <div class="blog-card__image">
                                        <picture>
                                            <source srcset="/images/blog/1.webp" type="image/webp"><img
                                                src="/images/blog/1.jpg" alt="Как правильно подобрать порог?" />
                                        </picture>
                                    </div>

                                    <div class="blog-card__content">
                                        <h3 class="blog-card__title">Как правильно подобрать порог?</h3>
                                        <p class="blog-card__description">
                                            Первые строки из статьи, которые займут, ну допустим где-то пять строк,
                                            чтобы люди могли прочитать быстреко информацию и заинтересоваться в этой
                                            статье, после чего перешли к ней и прочитали полностью. А еще это поможет
                                            сео, ведь по тексту смогут найти страницу в поиске
                                        </p>

                                        <div class="blog-card__bottom">
                                            <p class="blog-card__date">12.02.2024</p>
                                            <a href="/blog-single.html" class="blog-card__link">
                                                <span>Читать статью</span>

                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                    viewBox="0 0 18 18" fill="#1E1E1E">
                                                    <path
                                                        d="M15.5855 9.39828L10.523 14.4608C10.4174 14.5663 10.2743 14.6256 10.125 14.6256C9.97573 14.6256 9.83258 14.5663 9.72703 14.4608C9.62148 14.3552 9.56219 14.2121 9.56219 14.0628C9.56219 13.9135 9.62148 13.7704 9.72703 13.6648L13.8298 9.56281H2.8125C2.66332 9.56281 2.52024 9.50355 2.41475 9.39806C2.30926 9.29257 2.25 9.1495 2.25 9.00031C2.25 8.85113 2.30926 8.70805 2.41475 8.60257C2.52024 8.49708 2.66332 8.43781 2.8125 8.43781H13.8298L9.72703 4.33578C9.62148 4.23023 9.56219 4.08708 9.56219 3.93781C9.56219 3.78855 9.62148 3.64539 9.72703 3.53984C9.83258 3.4343 9.97573 3.375 10.125 3.375C10.2743 3.375 10.4174 3.4343 10.523 3.53984L15.5855 8.60234C15.6378 8.65458 15.6793 8.71662 15.7076 8.78491C15.7359 8.8532 15.7504 8.92639 15.7504 9.00031C15.7504 9.07423 15.7359 9.14743 15.7076 9.21572C15.6793 9.284 15.6378 9.34604 15.5855 9.39828Z">
                                                    </path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Slide -->
                            <div class="swiper-slide"><!-- Card -->
                                <div class="blog-card">
                                    <div class="blog-card__image">
                                        <picture>
                                            <source srcset="/images/blog/1.webp" type="image/webp"><img
                                                src="/images/blog/1.jpg" alt="Как правильно подобрать порог?" />
                                        </picture>
                                    </div>

                                    <div class="blog-card__content">
                                        <h3 class="blog-card__title">Как правильно подобрать порог?</h3>
                                        <p class="blog-card__description">
                                            Первые строки из статьи, которые займут, ну допустим где-то пять строк,
                                            чтобы люди могли прочитать быстреко информацию и заинтересоваться в этой
                                            статье, после чего перешли к ней и прочитали полностью. А еще это поможет
                                            сео, ведь по тексту смогут найти страницу в поиске
                                        </p>

                                        <div class="blog-card__bottom">
                                            <p class="blog-card__date">12.02.2024</p>
                                            <a href="/blog-single.html" class="blog-card__link">
                                                <span>Читать статью</span>

                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                    viewBox="0 0 18 18" fill="#1E1E1E">
                                                    <path
                                                        d="M15.5855 9.39828L10.523 14.4608C10.4174 14.5663 10.2743 14.6256 10.125 14.6256C9.97573 14.6256 9.83258 14.5663 9.72703 14.4608C9.62148 14.3552 9.56219 14.2121 9.56219 14.0628C9.56219 13.9135 9.62148 13.7704 9.72703 13.6648L13.8298 9.56281H2.8125C2.66332 9.56281 2.52024 9.50355 2.41475 9.39806C2.30926 9.29257 2.25 9.1495 2.25 9.00031C2.25 8.85113 2.30926 8.70805 2.41475 8.60257C2.52024 8.49708 2.66332 8.43781 2.8125 8.43781H13.8298L9.72703 4.33578C9.62148 4.23023 9.56219 4.08708 9.56219 3.93781C9.56219 3.78855 9.62148 3.64539 9.72703 3.53984C9.83258 3.4343 9.97573 3.375 10.125 3.375C10.2743 3.375 10.4174 3.4343 10.523 3.53984L15.5855 8.60234C15.6378 8.65458 15.6793 8.71662 15.7076 8.78491C15.7359 8.8532 15.7504 8.92639 15.7504 9.00031C15.7504 9.07423 15.7359 9.14743 15.7076 9.21572C15.6793 9.284 15.6378 9.34604 15.5855 9.39828Z">
                                                    </path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Slide -->
                            <div class="swiper-slide"><!-- Card -->
                                <div class="blog-card">
                                    <div class="blog-card__image">
                                        <picture>
                                            <source srcset="/images/blog/1.webp" type="image/webp"><img
                                                src="/images/blog/1.jpg" alt="Как правильно подобрать порог?" />
                                        </picture>
                                    </div>

                                    <div class="blog-card__content">
                                        <h3 class="blog-card__title">Как правильно подобрать порог?</h3>
                                        <p class="blog-card__description">
                                            Первые строки из статьи, которые займут, ну допустим где-то пять строк,
                                            чтобы люди могли прочитать быстреко информацию и заинтересоваться в этой
                                            статье, после чего перешли к ней и прочитали полностью. А еще это поможет
                                            сео, ведь по тексту смогут найти страницу в поиске
                                        </p>

                                        <div class="blog-card__bottom">
                                            <p class="blog-card__date">12.02.2024</p>
                                            <a href="/blog-single.html" class="blog-card__link">
                                                <span>Читать статью</span>

                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                    viewBox="0 0 18 18" fill="#1E1E1E">
                                                    <path
                                                        d="M15.5855 9.39828L10.523 14.4608C10.4174 14.5663 10.2743 14.6256 10.125 14.6256C9.97573 14.6256 9.83258 14.5663 9.72703 14.4608C9.62148 14.3552 9.56219 14.2121 9.56219 14.0628C9.56219 13.9135 9.62148 13.7704 9.72703 13.6648L13.8298 9.56281H2.8125C2.66332 9.56281 2.52024 9.50355 2.41475 9.39806C2.30926 9.29257 2.25 9.1495 2.25 9.00031C2.25 8.85113 2.30926 8.70805 2.41475 8.60257C2.52024 8.49708 2.66332 8.43781 2.8125 8.43781H13.8298L9.72703 4.33578C9.62148 4.23023 9.56219 4.08708 9.56219 3.93781C9.56219 3.78855 9.62148 3.64539 9.72703 3.53984C9.83258 3.4343 9.97573 3.375 10.125 3.375C10.2743 3.375 10.4174 3.4343 10.523 3.53984L15.5855 8.60234C15.6378 8.65458 15.6793 8.71662 15.7076 8.78491C15.7359 8.8532 15.7504 8.92639 15.7504 9.00031C15.7504 9.07423 15.7359 9.14743 15.7076 9.21572C15.6793 9.284 15.6378 9.34604 15.5855 9.39828Z">
                                                    </path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Slide -->
                            <div class="swiper-slide"><!-- Card -->
                                <div class="blog-card">
                                    <div class="blog-card__image">
                                        <picture>
                                            <source srcset="/images/blog/1.webp" type="image/webp"><img
                                                src="/images/blog/1.jpg" alt="Как правильно подобрать порог?" />
                                        </picture>
                                    </div>

                                    <div class="blog-card__content">
                                        <h3 class="blog-card__title">Как правильно подобрать порог?</h3>
                                        <p class="blog-card__description">
                                            Первые строки из статьи, которые займут, ну допустим где-то пять строк,
                                            чтобы люди могли прочитать быстреко информацию и заинтересоваться в этой
                                            статье, после чего перешли к ней и прочитали полностью. А еще это поможет
                                            сео, ведь по тексту смогут найти страницу в поиске
                                        </p>

                                        <div class="blog-card__bottom">
                                            <p class="blog-card__date">12.02.2024</p>
                                            <a href="/blog-single.html" class="blog-card__link">
                                                <span>Читать статью</span>

                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                    viewBox="0 0 18 18" fill="#1E1E1E">
                                                    <path
                                                        d="M15.5855 9.39828L10.523 14.4608C10.4174 14.5663 10.2743 14.6256 10.125 14.6256C9.97573 14.6256 9.83258 14.5663 9.72703 14.4608C9.62148 14.3552 9.56219 14.2121 9.56219 14.0628C9.56219 13.9135 9.62148 13.7704 9.72703 13.6648L13.8298 9.56281H2.8125C2.66332 9.56281 2.52024 9.50355 2.41475 9.39806C2.30926 9.29257 2.25 9.1495 2.25 9.00031C2.25 8.85113 2.30926 8.70805 2.41475 8.60257C2.52024 8.49708 2.66332 8.43781 2.8125 8.43781H13.8298L9.72703 4.33578C9.62148 4.23023 9.56219 4.08708 9.56219 3.93781C9.56219 3.78855 9.62148 3.64539 9.72703 3.53984C9.83258 3.4343 9.97573 3.375 10.125 3.375C10.2743 3.375 10.4174 3.4343 10.523 3.53984L15.5855 8.60234C15.6378 8.65458 15.6793 8.71662 15.7076 8.78491C15.7359 8.8532 15.7504 8.92639 15.7504 9.00031C15.7504 9.07423 15.7359 9.14743 15.7076 9.21572C15.6793 9.284 15.6378 9.34604 15.5855 9.39828Z">
                                                    </path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Slide -->
                            <div class="swiper-slide"><!-- Card -->
                                <div class="blog-card">
                                    <div class="blog-card__image">
                                        <picture>
                                            <source srcset="/images/blog/1.webp" type="image/webp"><img
                                                src="/images/blog/1.jpg" alt="Как правильно подобрать порог?" />
                                        </picture>
                                    </div>

                                    <div class="blog-card__content">
                                        <h3 class="blog-card__title">Как правильно подобрать порог?</h3>
                                        <p class="blog-card__description">
                                            Первые строки из статьи, которые займут, ну допустим где-то пять строк,
                                            чтобы люди могли прочитать быстреко информацию и заинтересоваться в этой
                                            статье, после чего перешли к ней и прочитали полностью. А еще это поможет
                                            сео, ведь по тексту смогут найти страницу в поиске
                                        </p>

                                        <div class="blog-card__bottom">
                                            <p class="blog-card__date">12.02.2024</p>
                                            <a href="/blog-single.html" class="blog-card__link">
                                                <span>Читать статью</span>

                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                    viewBox="0 0 18 18" fill="#1E1E1E">
                                                    <path
                                                        d="M15.5855 9.39828L10.523 14.4608C10.4174 14.5663 10.2743 14.6256 10.125 14.6256C9.97573 14.6256 9.83258 14.5663 9.72703 14.4608C9.62148 14.3552 9.56219 14.2121 9.56219 14.0628C9.56219 13.9135 9.62148 13.7704 9.72703 13.6648L13.8298 9.56281H2.8125C2.66332 9.56281 2.52024 9.50355 2.41475 9.39806C2.30926 9.29257 2.25 9.1495 2.25 9.00031C2.25 8.85113 2.30926 8.70805 2.41475 8.60257C2.52024 8.49708 2.66332 8.43781 2.8125 8.43781H13.8298L9.72703 4.33578C9.62148 4.23023 9.56219 4.08708 9.56219 3.93781C9.56219 3.78855 9.62148 3.64539 9.72703 3.53984C9.83258 3.4343 9.97573 3.375 10.125 3.375C10.2743 3.375 10.4174 3.4343 10.523 3.53984L15.5855 8.60234C15.6378 8.65458 15.6793 8.71662 15.7076 8.78491C15.7359 8.8532 15.7504 8.92639 15.7504 9.00031C15.7504 9.07423 15.7359 9.14743 15.7076 9.21572C15.6793 9.284 15.6378 9.34604 15.5855 9.39828Z">
                                                    </path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Slide -->
                            <div class="swiper-slide"><!-- Card -->
                                <div class="blog-card">
                                    <div class="blog-card__image">
                                        <picture>
                                            <source srcset="/images/blog/1.webp" type="image/webp"><img
                                                src="/images/blog/1.jpg" alt="Как правильно подобрать порог?" />
                                        </picture>
                                    </div>

                                    <div class="blog-card__content">
                                        <h3 class="blog-card__title">Как правильно подобрать порог?</h3>
                                        <p class="blog-card__description">
                                            Первые строки из статьи, которые займут, ну допустим где-то пять строк,
                                            чтобы люди могли прочитать быстреко информацию и заинтересоваться в этой
                                            статье, после чего перешли к ней и прочитали полностью. А еще это поможет
                                            сео, ведь по тексту смогут найти страницу в поиске
                                        </p>

                                        <div class="blog-card__bottom">
                                            <p class="blog-card__date">12.02.2024</p>
                                            <a href="/blog-single.html" class="blog-card__link">
                                                <span>Читать статью</span>

                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                    viewBox="0 0 18 18" fill="#1E1E1E">
                                                    <path
                                                        d="M15.5855 9.39828L10.523 14.4608C10.4174 14.5663 10.2743 14.6256 10.125 14.6256C9.97573 14.6256 9.83258 14.5663 9.72703 14.4608C9.62148 14.3552 9.56219 14.2121 9.56219 14.0628C9.56219 13.9135 9.62148 13.7704 9.72703 13.6648L13.8298 9.56281H2.8125C2.66332 9.56281 2.52024 9.50355 2.41475 9.39806C2.30926 9.29257 2.25 9.1495 2.25 9.00031C2.25 8.85113 2.30926 8.70805 2.41475 8.60257C2.52024 8.49708 2.66332 8.43781 2.8125 8.43781H13.8298L9.72703 4.33578C9.62148 4.23023 9.56219 4.08708 9.56219 3.93781C9.56219 3.78855 9.62148 3.64539 9.72703 3.53984C9.83258 3.4343 9.97573 3.375 10.125 3.375C10.2743 3.375 10.4174 3.4343 10.523 3.53984L15.5855 8.60234C15.6378 8.65458 15.6793 8.71662 15.7076 8.78491C15.7359 8.8532 15.7504 8.92639 15.7504 9.00031C15.7504 9.07423 15.7359 9.14743 15.7076 9.21572C15.6793 9.284 15.6378 9.34604 15.5855 9.39828Z">
                                                    </path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Slide -->
                            <div class="swiper-slide"><!-- Card -->
                                <div class="blog-card">
                                    <div class="blog-card__image">
                                        <picture>
                                            <source srcset="/images/blog/1.webp" type="image/webp"><img
                                                src="/images/blog/1.jpg" alt="Как правильно подобрать порог?" />
                                        </picture>
                                    </div>

                                    <div class="blog-card__content">
                                        <h3 class="blog-card__title">Как правильно подобрать порог?</h3>
                                        <p class="blog-card__description">
                                            Первые строки из статьи, которые займут, ну допустим где-то пять строк,
                                            чтобы люди могли прочитать быстреко информацию и заинтересоваться в этой
                                            статье, после чего перешли к ней и прочитали полностью. А еще это поможет
                                            сео, ведь по тексту смогут найти страницу в поиске
                                        </p>

                                        <div class="blog-card__bottom">
                                            <p class="blog-card__date">12.02.2024</p>
                                            <a href="/blog-single.html" class="blog-card__link">
                                                <span>Читать статью</span>

                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                    viewBox="0 0 18 18" fill="#1E1E1E">
                                                    <path
                                                        d="M15.5855 9.39828L10.523 14.4608C10.4174 14.5663 10.2743 14.6256 10.125 14.6256C9.97573 14.6256 9.83258 14.5663 9.72703 14.4608C9.62148 14.3552 9.56219 14.2121 9.56219 14.0628C9.56219 13.9135 9.62148 13.7704 9.72703 13.6648L13.8298 9.56281H2.8125C2.66332 9.56281 2.52024 9.50355 2.41475 9.39806C2.30926 9.29257 2.25 9.1495 2.25 9.00031C2.25 8.85113 2.30926 8.70805 2.41475 8.60257C2.52024 8.49708 2.66332 8.43781 2.8125 8.43781H13.8298L9.72703 4.33578C9.62148 4.23023 9.56219 4.08708 9.56219 3.93781C9.56219 3.78855 9.62148 3.64539 9.72703 3.53984C9.83258 3.4343 9.97573 3.375 10.125 3.375C10.2743 3.375 10.4174 3.4343 10.523 3.53984L15.5855 8.60234C15.6378 8.65458 15.6793 8.71662 15.7076 8.78491C15.7359 8.8532 15.7504 8.92639 15.7504 9.00031C15.7504 9.07423 15.7359 9.14743 15.7076 9.21572C15.6793 9.284 15.6378 9.34604 15.5855 9.39828Z">
                                                    </path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- arrows -->
                    <div class="slider-arrow">
                        <div class="swiper-button-prev">
                            <img src="/images/icons/arr-prev.svg" alt="Prev Slide" />
                        </div>
                        <div class="swiper-button-next">
                            <img src="/images/icons/arr-next.svg" alt="Next Slide" />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="faq-section section">
            <div class="container">
                <h2 class="h2">
                    Часто <br />
                    задаваемые <br />
                    вопросы
                </h2>
                <div class="faq-wrap">
                    <!--  -->
                    <div class="faq-left">
                        <p class="faq-description">
                            Если не нашли ответ на нужный вопрос
                            <a href="#">оставьте заявку</a> или свяжитесь с нами. Мы с
                            удовольствием расскажем все подробнее и проконсультируем вас.
                        </p>

                        <button class="btn">Получить консультацию</button>
                    </div>

                    <!-- Right -->
                    <div class="faq-right">
                        <div class="faq-accordition">
                            <!-- Item -->
                            <div class="faq-item accordion active">
                                <div class="faq-item__head accordion__intro">
                                    <p>У вас есть пороги на все модели авто?</p>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="#1E1E1E">
                                        <path d="M5 9.07129L12.0711 16.1424L19.1421 9.07129" />
                                    </svg>
                                </div>

                                <div class="accordion__content">
                                    <div class="faq-item__content">
                                        <p>
                                            Наше производство оснащено современным оборудованием и
                                            технологиями, что позволяет нам создавать ремонтные пороги,
                                            соответствующие самым высоким стандартам качества. Мы
                                            используем только качественные материалы и комплектующие, что
                                            гарантирует долговечность и надёжность нашей продукции.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Item -->
                            <div class="faq-item accordion">
                                <div class="faq-item__head accordion__intro">
                                    <p>Что делать, если на мой автомобиль нет порогов в базе?</p>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="#1E1E1E">
                                        <path d="M5 9.07129L12.0711 16.1424L19.1421 9.07129" />
                                    </svg>
                                </div>

                                <div class="accordion__content">
                                    <div class="faq-item__content">
                                        <p>
                                            Наше производство оснащено современным оборудованием и
                                            технологиями, что позволяет нам создавать ремонтные пороги,
                                            соответствующие самым высоким стандартам качества. Мы
                                            используем только качественные материалы и комплектующие, что
                                            гарантирует долговечность и надёжность нашей продукции.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Item -->
                            <div class="faq-item accordion">
                                <div class="faq-item__head accordion__intro">
                                    <p>Подойдут ли пороги к моему авто?</p>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="#1E1E1E">
                                        <path d="M5 9.07129L12.0711 16.1424L19.1421 9.07129" />
                                    </svg>
                                </div>

                                <div class="accordion__content">
                                    <div class="faq-item__content">
                                        <p>
                                            Наше производство оснащено современным оборудованием и
                                            технологиями, что позволяет нам создавать ремонтные пороги,
                                            соответствующие самым высоким стандартам качества. Мы
                                            используем только качественные материалы и комплектующие, что
                                            гарантирует долговечность и надёжность нашей продукции.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Item -->
                            <div class="faq-item accordion">
                                <div class="faq-item__head accordion__intro">
                                    <p>Сколько будут стоить пороги на моё авто"?</p>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="#1E1E1E">
                                        <path d="M5 9.07129L12.0711 16.1424L19.1421 9.07129" />
                                    </svg>
                                </div>

                                <div class="accordion__content">
                                    <div class="faq-item__content">
                                        <p>
                                            Наше производство оснащено современным оборудованием и
                                            технологиями, что позволяет нам создавать ремонтные пороги,
                                            соответствующие самым высоким стандартам качества. Мы
                                            используем только качественные материалы и комплектующие, что
                                            гарантирует долговечность и надёжность нашей продукции.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Item -->
                            <div class="faq-item accordion">
                                <div class="faq-item__head accordion__intro">
                                    <p>Подойдут ли пороги к моему авто?</p>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="#1E1E1E">
                                        <path d="M5 9.07129L12.0711 16.1424L19.1421 9.07129" />
                                    </svg>
                                </div>

                                <div class="accordion__content">
                                    <div class="faq-item__content">
                                        <p>
                                            Наше производство оснащено современным оборудованием и
                                            технологиями, что позволяет нам создавать ремонтные пороги,
                                            соответствующие самым высоким стандартам качества. Мы
                                            используем только качественные материалы и комплектующие, что
                                            гарантирует долговечность и надёжность нашей продукции.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Item -->
                            <div class="faq-item accordion">
                                <div class="faq-item__head accordion__intro">
                                    <p>В течение какого времени я могу получить пороги?</p>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="#1E1E1E">
                                        <path d="M5 9.07129L12.0711 16.1424L19.1421 9.07129" />
                                    </svg>
                                </div>

                                <div class="accordion__content">
                                    <div class="faq-item__content">
                                        <p>
                                            Наше производство оснащено современным оборудованием и
                                            технологиями, что позволяет нам создавать ремонтные пороги,
                                            соответствующие самым высоким стандартам качества. Мы
                                            используем только качественные материалы и комплектующие, что
                                            гарантирует долговечность и надёжность нашей продукции.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

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
