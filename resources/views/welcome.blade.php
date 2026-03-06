@extends('layouts.front')

@section('content')
    <main>

        {{-- Hero --}}
        <section class="index-hero-section section">
            <div class="container">
                <div class="index-hero-section__top">
                    <h1 class="h1 uppercase h1-home">
                        {!! $page->title !!}
                    </h1>

                    <div class="index-hero-section__descr">
                        {!! $page->description !!}
                    </div>
                </div>

                <x-section.hero-banners :page="$page" />
            </div>
        </section>


        <x-forms.request-form goal="banner" form-id="car-single-form-home-banner" checkbox-id="choose-check-home-banner" />
        {{-- <x-section.choose-auto :makes="$makesForForm" /> --}}
        {{-- <x-section.marquee /> --}}
        {{-- <x-section.features /> --}}
        <x-section.marks :items="$car_makes" />
        <x-section.about-parts />
        <x-section.gallery />
        <section class="reviews-widget-section section">
            <div class="container">

                <h2 class="reviews-widget-section__title">
                    Отзывы клиентов
                </h2>

                <review-lab data-widgetid="69984c4658896b169079008c"></review-lab>

            </div>
        </section>
        <x-section.how-we-work />

        <x-forms.request-form goal="delivery" form-id="car-single-form-home-delivery"
            checkbox-id="choose-check-home-delivery" />

        <x-section.about-company />
        {{-- <x-section.products :items="$products" /> --}}
        {{-- <x-section.installing /> --}}
        @if ($blogs->count())
            <x-section.blog :items="$blogs" />
        @endif
        <x-section.faq />



    </main>
@endsection
