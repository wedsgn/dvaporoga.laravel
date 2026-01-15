@extends('layouts.front')

@section('content')
  <main>
    {{ Breadcrumbs::render('car_model.show', $car_make, $car_model) }}
    <section class="catalog-page-section">
      <div class="container">
        <div class="catalog-page-top --model">
          <div class="catalog-page-top__left">
            <h1 class="h1 catalog-page__title">Поколения модели {{ $car_make->title }} {{ $car_model->title }}
            </h1>
          </div>
        </div>
      </div>
    </section>

    <section class="section car-generation-section">
      <div class="container">
        <div class="car-generation__wrap">
          @foreach ($generations as $generation => $models)
            <x-car-generation-card :car_make="$car_make" :car_model="$car_model" :generations="[$generation => $models]" />
          @endforeach
        </div>
      </div>
    </section>

    <x-section.car-models :models="$car_make->car_models->whereNotIn('id', [$car_model->id])" :concern_title="$car_make->title" />

    <x-section.about-parts />
    <x-section.how-we-work />
    <x-section.about-company />
    <x-section.faq />
    {{-- <x-section.products :items="$products" /> --}}
    {{-- <x-section.installing /> --}}
    <x-section.faq />
  </main>
@endsection
