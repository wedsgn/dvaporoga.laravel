@extends('layouts.front')

@section('content')
    <main>
        {{ Breadcrumbs::render('car_model.show', $car_make, $car_model) }}

        <section class="catalog-page-section">
          <div class="container">
            <div class="catalog-page-top --model">
              <div class="catalog-page-top__left">
                <h1 class="h1 catalog-page__title">Поколения {{ $car_model->title }}</h1>

                <p class="model-count">{{ $car_model->count() }} Моделей</p>
              </div>
              <p class="catalog-page__description">
                {{ $car_model->description }}
              </p>
            </div>
          </div>
        </section>

        <section class="section car-generation-section">
          <div class="container">
            <div class="car-generation__wrap">
              @foreach($generations as $years => $models)
                <x-car-generation-card :car_make="$car_make" :car_model="$car_model" :generations="[$years => $models]" />
              @endforeach
            </div>
          </div>
        </section>

        {{-- <x-section.car-models :models="$audiModels" /> --}}

        {{-- <x-section.products :items="$products" /> --}}
        <x-section.installing />
        <x-section.faq />


    </main>
@endsection
