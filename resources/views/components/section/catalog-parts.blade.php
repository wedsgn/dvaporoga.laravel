@props(['block' => null])

@php
$images = $block?->images ?? [];
$title = $block?->title;
@endphp

@if($block && count($images))
<section class="catalog-parts-section section">

    <div class="container">

        <h2 class="catalog-parts-title h2">
            {{ $title }}
        </h2>

        <div class="catalog-parts-grid">

            @foreach ($images as $item)

                @php
                $url = str_starts_with($item,'uploads/')
                    ? asset('storage/'.$item)
                    : asset($item);
                @endphp

                <a
                    class="catalog-parts-item"
                    href="{{ $url }}"
                    data-fancybox="catalog-parts"
                >
                    <img
                        src="{{ $url }}"
                        alt="{{ $title }}"
                        loading="lazy"
                    >
                </a>

            @endforeach

        </div>

    </div>

</section>
@endif
