<footer class="footer section">
  <div class="container">
    {{-- Footer Top --}}
    <div class="footer-top">
      <div class="footer-top__wrap">
        <div class="footer-top__left">
          <a href="{{ route('home') }}" class="footer-logo">
            <img src="{{ asset('images/footerlogo.svg') }}" alt="">
          </a>

          <div class="footer-bottom__descr --mob">
            Ремонтные арки и пороги
            для всех автомобилей
          </div>
        </div>

        <div class="footer-top__right">
          <div class="footer-socials">

            <a href="https://api.whatsapp.com/send?phone=79812086415" target="_blank" class="footer-social">
              <img src="{{ asset('images/socials/wa.svg') }}" alt="dvaporoga whatssup">
            </a>
            <a href="https://t.me/+79812086415" target="_blank" class="footer-social">
              <img src="{{ asset('images/socials/tg.svg') }}" alt="dvaporoga telegram">
            </a>
            <a href="https://vk.com/avtoporogiru" target="_blank" class="footer-social">
              <img src="{{ asset('images/socials/vk.svg') }}" alt="dvaporoga vk">
            </a>
          </div>
          <div class="header__phone --footer">
            <div class="header__phone_top">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-phone-call-icon lucide-phone-call">
                <path d="M13 2a9 9 0 0 1 9 9"></path>
                <path d="M13 6a5 5 0 0 1 5 5"></path>
                <path
                  d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384">
                </path>
              </svg>
              <a href="tel:8 800 100 56 25">8 800 100 56 25</a>
            </div>
            <span>Беспланый звонок по РФ</span>
          </div>
        </div>
      </div>
    </div>

    {{-- footer bootm --}}

    <div class="footer-bottom">
      <div class="footer-bottom__wrap">
        <div class="footer-bottom__descr">
          Ремонтные арки и пороги
          для всех автомобилей
        </div>
        <nav class="footer-bottom__nav">
          <a href="{{ route('home') }}#features" class="footer__nav-link">Преимущества</a>
          <a href="{{ route('catalog') }}" class="footer__nav-link">Каталог</a>
          <a href="{{ route('blog') }}" class="footer__nav-link">Блог</a>
          <a href="{{ route('home') }}#about" class="footer__nav-link">О нас</a>
          <a href="{{ route('home') }}#delivery" class="footer__nav-link">Доставка</a>
          <a href="{{ route('home') }}#faq" class="footer__nav-link">FAQ</a>

      </div>
    </div>
  </div>


  <div class="footer-copy">
    <div class="container">
      <div class="footer-copy__wrap">
        <p>ИП ФЕДОРОВ АЛЕКСАНДР ЮРЬЕВИЧ 427284, Удмуртская, Толошур, улица Толошурская, д. 21 ИНН 780520987721</p>

        <p>Сайт не является офертой</p>
      </div>
    </div>
  </div>
</footer>
