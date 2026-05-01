@props(['block' => null])

@php
    $items = $block?->items ?? [];
    $title = $block?->title ?: 'Примеры ремонтов авто';

    $assetUrl = function ($path) {
        if (!$path) {
            return '';
        }

        return str_starts_with($path, 'uploads/') ? asset('storage/' . $path) : asset($path);
    };
@endphp

@if (!empty($items))
    <section class="repair-examples-section section">
        <div class="container">
            <div class="repair-examples-section__top">
                <h2 class="h2 repair-examples-section__title">{{ $title }}</h2>
            </div>

            <div class="swiper repair-examples-slider">
                <div class="swiper-wrapper">
                    @foreach ($items as $index => $item)
                        @php
                            $before = $assetUrl($item['before'] ?? '');
                            $after = $assetUrl($item['after'] ?? '');
                            $galleryId = 'repair-before-after-' . $index;
                        @endphp

                        @if ($before && $after)
                            <div class="swiper-slide">
                                <div class="repair-card">
                                    <div class="repair-card__labels">
                                        <span class="repair-card__label repair-card__label--left">до</span>
                                        <span class="repair-card__label repair-card__label--right">после</span>
                                    </div>

                                    <div class="repair-compare" data-compare>
                                        <div class="repair-compare__after">
                                            <a href="{{ $after }}" data-fancybox="{{ $galleryId }}">
                                                <img src="{{ $after }}" alt="После ремонта" loading="lazy">
                                            </a>
                                        </div>

                                        <div class="repair-compare__before" data-compare-overlay>
                                            <a href="{{ $before }}" data-fancybox="{{ $galleryId }}">
                                                <img src="{{ $before }}" alt="До ремонта" loading="lazy">
                                            </a>
                                        </div>

                                        <button type="button" class="repair-compare__handle" data-compare-handle
                                            aria-label="Сравнить фото">
                                            <span class="repair-compare__line"></span>
                                            <span class="repair-compare__circle">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="m15 18-6-6 6-6" />
                                                </svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="m9 18 6-6-6-6" />
                                                </svg>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="swiper-pagination repair-examples-pagination"></div>
        </div>
    </section>
@endif
