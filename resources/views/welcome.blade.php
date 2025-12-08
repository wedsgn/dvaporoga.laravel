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


    <x-section.choose-auto :makes="$makesForForm" />
    {{-- <x-section.marquee /> --}}
    {{-- <x-section.features /> --}}
    <x-section.about-parts />
    {{-- <x-section.marks :items="$car_makes" /> --}}
    <x-section.gallery />
    {{-- <x-section.products :items="$products" /> --}}
    {{-- <x-section.installing /> --}}
    <x-section.how-we-work />
    <x-section.about-company />
    @if ($blogs->count())
      <x-section.blog :items="$blogs" />
    @endif
    <x-section.faq />



  </main>
@endsection
