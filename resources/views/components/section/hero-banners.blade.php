@if (isset($page) && $page->banners->count())

  <div class="index-hero__banners">
    <div class="swiper swiper-banner">
      <div class="swiper-wrapper">
        @foreach ($page->banners as $banner)
          <div class="swiper-slide">
            {{-- Весь слайд кликабелен и открывает модалку --}}
            <div class="index-hero__slide" data-micromodal-trigger="modal-hero" role="button" tabindex="0">

              <div class="index-hero__slide_wrap">
                <div class="index-hero__slide_image">
                  <picture>
                    @if ($banner->image_mobile)
                      <source media="(max-width: 768px)" srcset="{{ asset('storage/' . $banner->image_mobile) }}">
                    @endif

                    @if ($banner->image_desktop)
                      <img src="{{ asset('storage/' . $banner->image_desktop) }}" alt="{{ $banner->title ?? '' }}">
                    @endif
                  </picture>
                </div>
              </div>

            </div>
          </div>
        @endforeach
      </div>
    </div>

    <div class="banners-pagination hero-pag swiper-pagination"></div>

    <div class="swiper-button-prev slider-arrow slider-arrow-prev hero-banner-arrow-prev gallery-arrow-prev">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
        class="lucide lucide-arrow-left-icon lucide-arrow-left">
        <path d="m12 19-7-7 7-7" />
        <path d="M19 12H5" />
      </svg>
    </div>

    <div class="swiper-button-next slider-arrow slider-arrow-next hero-banner-arrow-next gallery-arrow-next">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
        class="lucide lucide-arrow-right-icon lucide-arrow-right">
        <path d="M5 12h14" />
        <path d="m12 5 7 7-7 7" />
      </svg>
    </div>
  </div>
@endif
