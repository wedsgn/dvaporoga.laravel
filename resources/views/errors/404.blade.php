@extends('layouts.front')

@section('content')
<main>
  <section class="not-found-section">
    <div class="container">
      <div class="not-found__wrap">
        <h1 class="not-found__title">404</h1>
        <p class="not-found__description">
          Кажется что-то пошло не так! Страница, которую вы запрашиваете, не
          существует. Возможно она устарела, была удалена, или был введен
          неверный адрес в адресной строке.
        </p>

        <div class="not-found__btns">
          <a href="{{ route('home') }}" class="btn">Перейти на главную</a>
          <a href="{{ route('catalog') }}" class="btn">Перейти в каталог</a>
        </div>
      </div>
    </div>
  </section>
</main>
@endsection
