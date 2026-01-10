@php
  $data = [
      [
          'svg' => 'images/marquee/1.svg',
          'text' => 'Оплата при получении',
      ],
      [
          'svg' => 'images/marquee/2.svg',
          'text' => 'Гарантия 90 дней',
      ],
      [
          'svg' => 'images/marquee/3.svg',
          'text' => 'Полное повторение оригинала',
      ],
      [
          'svg' => 'images/marquee/4.svg',
          'text' => 'Изготовлены на высокоточном оборудовании',
      ],
      [
          'svg' => 'images/marquee/5.svg',
          'text' => 'Надежная упаковка',
      ],
  ];
@endphp

<section class="marquee-section">
  <div class="contaiфner">
    <div class="marquee__wrap">
      {{-- Item --}}
      @for ($i = 0; $i < 4; $i++)
        @foreach ($data as $item)
          <div class="marquee-item">
            <div class="marquee-item__wrap">
              <div class="marquee-item__icon">
                <img src="{{ asset($item['svg']) }}" alt="">
              </div>
              <div class="marquee-item__text">{{ $item['text'] }}</div>
            </div>
          </div>
        @endforeach
      @endfor
    </div>
  </div>
</section>

{{-- 
Оплата при получении



 
--}}
