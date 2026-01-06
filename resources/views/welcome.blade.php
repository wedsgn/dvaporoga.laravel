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


    <section class="car-single-form-section">
      <div class="container">
        <div class="car-single-form-section__top">
          <div class="car-single-form-section__label">
            <img src="{{ asset('images/icons/fire.svg') }}" alt="">
            <p>Акция</p>
          </div>
          <h2 class="car-single-form-section__title">Оставьте заявку</h2>
        </div>

        <p class="car-single-form-section__descr">И мы перезвоним вам в течении минуты и ответим на все вопросы</p>

        <form class="car-single-form" data-action="{{ route('request_consultation.store') }}" data-ym-goal="lead">
          @csrf
          <input type="hidden" name="form_id" value="car-single-form-home">
          <input type="hidden" name="current_url"
            value="{{ url()->current() }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}">

          {{-- UTM метки --}}
          <input type="hidden" name="utm_source" value="{{ request('utm_source') }}">
          <input type="hidden" name="utm_medium" value="{{ request('utm_medium') }}">
          <input type="hidden" name="utm_campaign" value="{{ request('utm_campaign') }}">
          <input type="hidden" name="utm_term" value="{{ request('utm_term') }}">
          <input type="hidden" name="utm_content" value="{{ request('utm_content') }}">

          <div class="choose-section__form_row">
            <div class="input-item">
              <input class="input black" type="text" name="name" placeholder="Имя">
            </div>

            <div class="input-item">
              <input class="input black" type="tel" name="phone" placeholder="+7 (___) ___ __ __">
            </div>

            <button type="submit" class="btn btn-black car-single-form-btn">Отправить</button>
          </div>

          <div class="form-policy">
            <input type="checkbox" id="choose-check" name="policy" value="1" checked="" required="">
            <label for="choose-check">
              Я соглашаюсь с
              <a href="http://localhost:8000/policy.pdf" target="_blank">политикой конфиденциальности</a>
              и даю согласие на обработку персональных данных
            </label>
          </div>
        </form>
      </div>
    </section>

    {{-- <x-section.choose-auto :makes="$makesForForm" /> --}}


    {{-- <x-section.marquee /> --}}
    {{-- <x-section.features /> --}}
    <x-section.about-parts />
    {{-- <x-section.marks :items="$car_makes" /> --}}
    <x-section.gallery />
    {{-- <x-section.products :items="$products" /> --}}
    {{-- <x-section.installing /> --}}
    <x-section.about-company />
    @if ($blogs->count())
      <x-section.blog :items="$blogs" />
    @endif
    <x-section.faq />



  </main>
@endsection
