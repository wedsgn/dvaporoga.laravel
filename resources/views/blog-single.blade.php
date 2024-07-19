@extends('layouts.front')

@section('content')
    <main>
        <section class="breadcrumbs-section">
            <div class="container">
                <ul class="breadcrumbs">
                    <li><a href="/">Главная</a></li>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M14 8.0013L10.6667 4.66797M14 8.0013L10.6667 11.3346M14 8.0013H2" stroke="#1E1E1E"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </li>
                    <li><a href="/blog.html">Блог</a></li>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"
                            fill="none">
                            <path d="M14 8.0013L10.6667 4.66797M14 8.0013L10.6667 11.3346M14 8.0013H2" stroke="#1E1E1E"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </li>
                    <li>Как правильно выбрать порог</li>
                </ul>
            </div>
        </section>

        <section class="blog-page-section">
            <div class="container">
                <h1 class="h1">Как правильно выбрать порог</h1>

                <div class="blog-page-poster">
                    <img src="/images/about/1.jpg" alt="" />
                </div>

                <div class="blog-page__description">
                    <p>
                        Мы — производственная компания. За несколько лет работы у нас
                        накопился колоссальный опыт в производстве кузовных запчастей.
                        Наши опытные мастера, могут произвести кузовные запчасти
                        практически на все модели автомобилей.
                    </p>
                    <p>
                        Мы — производственная компания. За несколько лет работы у нас
                        накопился колоссальный опыт в производстве кузовных запчастей.
                        Наши опытные мастера, могут произвести кузовные запчасти
                        практически на все модели автомобилей.
                    </p>
                </div>
            </div>
        </section>
    </main>
@endsection
