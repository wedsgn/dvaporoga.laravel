@php
  $data = [
      'images/gallery/1.jpg',
      'images/gallery/2.jpg',
      'images/gallery/3.jpg',
      'images/gallery/4.jpg',
      'images/gallery/5.jpg',
      'images/gallery/6.jpg',
      // 'images/gallery/7.jpg',
      'images/gallery/8.jpg',
      'images/gallery/9.jpg',
      'images/gallery/10.jpg',
  ];

@endphp

<section class="gallery-section section">
  <div class="container">
    <h2 class="h2">В ассортименте запчасти на 3000 моделей авто</h2>

    <div class="gallery-section__wrap">
      @foreach ($data as $item)
        <a href="{{ asset($item) }}" data-fancybox="gallery">
          <img src="{{ asset($item) }}" />
        </a>
      @endforeach

    </div>



    <div class="gallery-swiper-mob">

      <div class="swiper gallery-swiper">
        <div class="swiper-wrapper">
          @foreach ($data as $item)
            <div class="swiper-slide">
              <a href="{{ asset($item) }}" data-fancybox="gallery">
                <img src="{{ asset($item) }}" />
              </a>
            </div>
          @endforeach
        </div>

      </div>
      <div class="swiper-pagination banners-pagination gallery-pagination"></div>

      <div class="swiper-button-prev slider-arrow slider-arrow-prev gallery-arrow-prev">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          class="lucide lucide-arrow-left-icon lucide-arrow-left">
          <path d="m12 19-7-7 7-7" />
          <path d="M19 12H5" />
        </svg>
      </div>


      <div class="swiper-button-next slider-arrow slider-arrow-next gallery-arrow-next">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          class="lucide lucide-arrow-right-icon lucide-arrow-right">
          <path d="M5 12h14" />
          <path d="m12 5 7 7-7 7" />
        </svg>
      </div>
    </div>
  </div>
</section>
