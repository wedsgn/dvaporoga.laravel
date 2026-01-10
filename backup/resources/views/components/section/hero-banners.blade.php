@php
    $desktopBanners = $page->banners->filter(fn ($b) => $b->image_desktop);
    $mobileBanners  = $page->banners->filter(fn ($b) => $b->image_mobile);
@endphp

@if (($desktopBanners->count() || $mobileBanners->count()) && isset($page))
  <div class="index-hero__banners">

    {{-- Десктопный слайдер --}}
    @if ($desktopBanners->count())
      <div class="swiper swiper-banner swiper-banner-desktop">
        <div class="swiper-wrapper">
          @foreach ($desktopBanners as $banner)
            <div class="swiper-slide">
              <div class="index-hero__slide"
                   data-micromodal-trigger="modal-hero"
                   role="button"
                   tabindex="0">

                <div class="index-hero__slide_wrap">
                  <div class="index-hero__slide_image">
                    <img src="{{ asset('storage/' . $banner->image_desktop) }}"
                         alt="{{ $banner->title ?? '' }}">
                  </div>
                </div>

              </div>
            </div>
          @endforeach
        </div>

        <div class="banners-pagination hero-pag hero-pag-desktop swiper-pagination"></div>

        <div class="swiper-button-prev slider-arrow slider-arrow-prev hero-banner-arrow-prev hero-banner-arrow-prev-desktop">
          {{-- svg влево --}}
        </div>
        <div class="swiper-button-next slider-arrow slider-arrow-next hero-banner-arrow-next hero-banner-arrow-next-desktop">
          {{-- svg вправо --}}
        </div>
      </div>
    @endif

    {{-- Мобильный слайдер --}}
    @if ($mobileBanners->count())
      <div class="swiper swiper-banner swiper-banner-mobile">
        <div class="swiper-wrapper">
          @foreach ($mobileBanners as $banner)
            <div class="swiper-slide">
              <div class="index-hero__slide"
                   data-micromodal-trigger="modal-hero"
                   role="button"
                   tabindex="0">

                <div class="index-hero__slide_wrap">
                  <div class="index-hero__slide_image">
                    <img src="{{ asset('storage/' . $banner->image_mobile) }}"
                         alt="{{ $banner->title ?? '' }}">
                  </div>
                </div>

              </div>
            </div>
          @endforeach
        </div>

        <div class="banners-pagination hero-pag hero-pag-mobile swiper-pagination"></div>

        <div class="swiper-button-prev slider-arrow slider-arrow-prev hero-banner-arrow-prev hero-banner-arrow-prev-mobile">
          {{-- svg влево --}}
        </div>
        <div class="swiper-button-next slider-arrow slider-arrow-next hero-banner-arrow-next hero-banner-arrow-next-mobile">
          {{-- svg вправо --}}
        </div>
      </div>
    @endif

  </div>
@endif
