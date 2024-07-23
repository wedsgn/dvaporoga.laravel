@php
    $data = ['', '', '', '', '', ''];
@endphp

<section class="blog-section section">
    <div class="container">
        <h2 class="h2">Блог</h2>

        <div class="blog-section__items">
            <div class="swiper blog-section-slider">
                <div class="swiper-wrapper">


                    @foreach ($data as $item)
                        <div class="swiper-slide">
                            <x-blog-card />
                        </div>
                    @endforeach


                </div>
            </div>
            <!-- arrows -->
            <div class="slider-arrow">
                <div class="swiper-button-prev">
                    <img src="/images/icons/arr-prev.svg" alt="Prev Slide" />
                </div>
                <div class="swiper-button-next">
                    <img src="/images/icons/arr-next.svg" alt="Next Slide" />
                </div>
            </div>
        </div>
    </div>
</section>
