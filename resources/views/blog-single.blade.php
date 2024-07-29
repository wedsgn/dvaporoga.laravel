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
                    <li>{{ $blog->title }}</li>
                </ul>
            </div>
        </section>

        <section class="blog-page-section">
            <div class="container">
                <h1 class="h1">{{ $blog->title }}</h1>

                <div class="blog-page-poster">
                    <img src="{{ $blog->image }}" alt="" />
                </div>

                <div class="blog-page__description">
                    {!! $blog->description !!}
                </div>
            </div>
        </section>
    </main>
@endsection
