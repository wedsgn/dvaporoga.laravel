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

            <a href="{{ $main_info->whats_app }}" target="_blank" class="footer-social">
              <img src="{{ asset('images/socials/wa.svg') }}" alt="dvaporoga whatssup">
            </a>
            <a href="{{ $main_info->telegram }}" target="_blank" class="footer-social">
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
              <a href="tel:{{ $main_info->phone }}">{{ $main_info->phone }}</a>
            </div>
            <span>Бесплатный звонок по РФ</span>
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
          {{-- <a href="{{ route('catalog') }}" class="footer__nav-link">Каталог</a> --}}
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
        <p>{{ $main_info->company_details }}</p>

        <p>Сайт не является офертой</p>
      </div>
    </div>
  </div>
</footer>
<script>
(function (w, d) {
  var YM_ID = 104319970, GOAL = 'lead';

  function ymSafe(meta){
    if (typeof w.ym === 'function') {
      try { w.ym(YM_ID, 'reachGoal', GOAL, meta || {}); } catch(e){}
    }
  }

  var lastTs = 0;
  try { lastTs = parseInt(w.sessionStorage.getItem('ym_lead_last') || '0', 10) || 0; } catch(e){}
  function recently(ms){ return (Date.now() - lastTs) < ms; }
  function mark(){ lastTs = Date.now(); try { w.sessionStorage.setItem('ym_lead_last', String(lastTs)); } catch(e){} }

  function metaFor(form){
    var fid = (form.querySelector('[name="form_id"]') || {}).value || form.id || '';
    var act = form.getAttribute('action') || form.dataset.action || '';
    return {
      page: w.location.pathname + w.location.search,
      form_id: fid,
      action: act
    };
  }

  async function handleModalSubmit(e){
    var form = e.target;
    if (!form.matches('.modal-form')) return;

    e.preventDefault();

    var submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
    if (submitBtn) submitBtn.disabled = true;

    var url = form.dataset.action;
    var fd  = new FormData(form);

    try {
      var res = await fetch(url, {
        method: 'POST',
        body: fd,
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin',
        cache: 'no-store'
      });

      if (res.ok && !recently(2000)) {
        mark();
        ymSafe(metaFor(form));
      }
    } catch (err) {
    } finally {
      if (submitBtn) submitBtn.disabled = false;
    }
  }

  function handleCartSubmit(e){
    var form = e.target;
    if (!form.matches('#cart-form')) return;

    if (!recently(2000)) {
      mark();
      ymSafe(metaFor(form));
    }
    var btn = form.querySelector('button[type="submit"], input[type="submit"]');
    if (btn) btn.disabled = true;
    setTimeout(function(){ if (btn) btn.disabled = false; }, 4000);
  }

  function bind(){
    document.addEventListener('submit', handleModalSubmit, true);
    var cart = document.querySelector('#cart-form');
    if (cart && !cart.__boundYmCart) {
      cart.addEventListener('submit', handleCartSubmit, true);
      cart.__boundYmCart = true;
    }
  }

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', bind);
  else bind();

})(window, document);
</script>
