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


        <x-forms.request-form
            goal="banner"
            form-id="car-single-form-home-banner"
            checkbox-id="choose-check-home-banner"
            title="Подбор деталей"
        />
        {{-- <x-section.choose-auto :makes="$makesForForm" /> --}}
        {{-- <x-section.marquee /> --}}
        {{-- <x-section.features /> --}}
        <x-section.marks :items="$car_makes" />
        <x-section.about-parts />
        <x-section.repair-examples :block="$repairExamplesBlock" />
        <x-section.catalog-parts :block="$catalogPartsBlock" />
        <x-section.gallery :block="$galleryBlock" />
        <x-section.reviews />
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
        {{-- <x-section.partnership /> --}}


    </main>
@endsection
