@extends('layouts.front')

@section('content')
    <main>
        {{ Breadcrumbs::render('blog') }}

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
