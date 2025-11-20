 {{-- <div class="index-hero__banners">
   <div class="swiper swiper-banner">
     <div class="swiper-wrapper">
       <div class="swiper-slide">
         <div class="index-hero__slide --arka">
           <div class="index-hero__slide_wrap">
             <div class="index-hero__slide_info">
               <h2 class="index-hero__slide_title">
                 Кузовная ремонтная арка
               </h2>

               <p class="index-hero__slide_descr">
                 Ремкомплект предназначен для ремонта порогов при коррозии, деформации или повреждений при ДТП.
               </p>

               <div class="index-hero__slide_prices">
                 <div class="index-hero__slide_price">
                   от 1 900 руб
                 </div>
                 <div class="index-hero__slide_oldprice">
                   2 500 руб
                 </div>
               </div>

               <div class="index-hero__slide_btns">
                 <button class="btn  lg index-hero__slide_btn " data-micromodal-trigger="modal-hero">
                   Заказать
                 </button>
               </div>
             </div>

             <div class="index-hero__slide_image">
               <img src="{{ asset('images/banners/1.png') }}" alt="Ремонтные арки">

               <div class="index-hero__slide_tag">
                 <p>Материал - хкс и оцинкованная сталь</p>
                 <div class="dot"></div>
               </div>
               <div class="index-hero__slide_tag">
                 <p>Толщина - 0,8 мм</p>
                 <div class="dot"></div>
               </div>
               <div class="index-hero__slide_tag">
                 <p>Повторение оригинальных изгибов <br> и контуров крыльев.</p>
                 <div class="dot"></div>
               </div>
               <div class="index-hero__slide_tag">
                 <p>На переднию и заднюю часть авто</p>
                 <div class="dot"></div>
               </div>
             </div>

             <div class="index-hero__slide_prices --mob">
               <div class="index-hero__slide_price">
                 от 1 900 руб
               </div>
               <div class="index-hero__slide_oldprice">
                 2 500 руб
               </div>
             </div>

             <div class="index-hero__slide_btns --mob">
               <button class="btn  lg index-hero__slide_btn " data-micromodal-trigger="modal-hero">
                 Заказать
               </button>
             </div>
           </div>
           <div class="index-hero__slide-fx --left"></div>
           <div class="index-hero__slide-fx --right"></div>
         </div>
       </div>

       <div class="swiper-slide">
         <div class="index-hero__slide --porog">
           <div class="index-hero__slide_wrap">
             <div class="index-hero__slide_info">
               <h2 class="index-hero__slide_title">
                 Кузовной ремонтный порог </h2>

               <p class="index-hero__slide_descr">
                 Ремкомплект предназначен для ремонта порогов при коррозии, деформации или повреждений при ДТП.
               </p>

               <div class="index-hero__slide_prices">
                 <div class="index-hero__slide_price">
                   от 1 690 руб
                 </div>
                 <div class="index-hero__slide_oldprice">
                   2 500 руб
                 </div>
               </div>

               <div class="index-hero__slide_btns">
                 <button class="btn  lg index-hero__slide_btn " data-micromodal-trigger="modal-hero">
                   Заказать
                 </button>
               </div>
             </div>

             <div class="index-hero__slide_image">
               <img src="{{ asset('images/banners/2.png') }}" alt="Ремонтные пороги">

               <div class="index-hero__slide_tag">
                 <p>Материал - хкс и оцинкованная сталь</p>
                 <div class="dot"></div>
               </div>
               <div class="index-hero__slide_tag">
                 <p>Толщина - 1 - 1,5 мм</p>
                 <div class="dot"></div>
               </div>
               <div class="index-hero__slide_tag">
                 <p>Полное повторение оригиналов</p>
                 <div class="dot"></div>
               </div>

             </div>

             <div class="index-hero__slide_prices --mob">
               <div class="index-hero__slide_price">
                 от 1 690 руб
               </div>
               <div class="index-hero__slide_oldprice">
                 2 500 руб
               </div>
             </div>

             <div class="index-hero__slide_btns --mob">
               <button class="btn  lg index-hero__slide_btn " data-micromodal-trigger="modal-hero">
                 Заказать
               </button>
             </div>
           </div>
           <div class="index-hero__slide-fx --left"></div>
           <div class="index-hero__slide-fx --right"></div>
         </div>
       </div>

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
 </div> --}}





 @if(isset($page) && $page->banners->count())

<div class="index-hero__banners">
   <div class="swiper swiper-banner">
     <div class="swiper-wrapper">
       @foreach($page->banners as $banner)
         <div class="swiper-slide">
           {{-- Весь слайд кликабелен и открывает модалку --}}
           <div class="index-hero__slide"
                data-micromodal-trigger="modal-hero"
                role="button"
                tabindex="0">

             <div class="index-hero__slide_wrap">
               <div class="index-hero__slide_image">
                 <picture>
                   @if($banner->image_mobile)
                     <source media="(max-width: 768px)"
                             srcset="{{ asset('storage/' . $banner->image_mobile) }}">
                   @endif

                   @if($banner->image_desktop)
                     <img src="{{ asset('storage/' . $banner->image_desktop) }}"
                          alt="{{ $banner->title ?? '' }}">
                   @endif
                 </picture>
               </div>
             </div>

             <div class="index-hero__slide-fx --left"></div>
             <div class="index-hero__slide-fx --right"></div>
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
