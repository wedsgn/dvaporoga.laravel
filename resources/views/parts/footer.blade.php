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
        <p>{{ $main_info->company_details }}</p>

        <p>Сайт не является офертой</p>
      </div>
    </div>
  </div>
</footer>
<script>
  (function(w, d) {
    if (w.__ymFormsGoalInstalled) return;
    w.__ymFormsGoalInstalled = true;

    var YM_ID = 104319970;
    var ATTR = 'data-ym-goal';
    var MODE_ATTR = 'data-ym-mode'; // auto | manual

    var DEV = false; // <-- включить/выключить лог

    var GOAL_MAP = {
      'cart-lead': 'lead',
      'calculator': 'calculator',
      'banner': 'banner',
      'faq': 'faq',
      'company': 'company',
      'delivery': 'delivery',
      'automatic': 'automatic',
      'lead': 'lead'
    };

    var perFormTs = new WeakMap();

    function resolveGoal(raw) {
      if (!raw) return 'lead';
      raw = String(raw).trim().toLowerCase();
      return GOAL_MAP[raw] || raw;
    }

    function getAction(form) {
      return form.getAttribute('action') ||
        (form.dataset ? (form.dataset.action || '') : '');
    }

    function devLog() {
      if (!DEV) return;
      if (console && console.log) {
        console.log.apply(console, ['%c[YMGoals]', 'color:#32a852;font-weight:bold;', ...arguments]);
      }
    }

    function fire(form, extra) {
      if (!form || typeof w.ym !== 'function') return;

      var last = perFormTs.get(form) || 0;
      if (Date.now() - last < 4000) {
        devLog('SKIP duplicate fire:', form);
        return;
      }
      perFormTs.set(form, Date.now());

      var rawGoal = form.getAttribute(ATTR);
      var goal = resolveGoal(rawGoal);
      var fidEl = form.querySelector('[name="form_id"]');
      var action = getAction(form);
      var mode = (form.getAttribute(MODE_ATTR) || 'auto').toLowerCase();

      var params = Object.assign({
        goal_name: goal,
        label: rawGoal || 'unknown',
        form_id: fidEl ? fidEl.value : (form.id || ''),
        action: action || null,
        page: w.location.origin + w.location.pathname,
        mode: mode
      }, extra || {});

      devLog('FIRE', {
        goal: goal,
        rawGoal: rawGoal,
        form: form,
        params: params
      });

      w.ym(YM_ID, 'reachGoal', goal, params);
    }

    w.YMGoals = w.YMGoals || {
      fire
    };

    // AUTO режим — только для data-ym-mode!="manual"
    d.addEventListener('submit', function(e) {
      var f = e.target;
      if (!f || !f.hasAttribute(ATTR)) return;

      var mode = (f.getAttribute(MODE_ATTR) || 'auto').toLowerCase();
      if (mode === 'manual') {
        devLog('AUTO-SKIP (manual mode):', f);
        return;
      }

      devLog('AUTO-FIRE ON SUBMIT:', f);
      fire(f, {
        trigger: 'submit'
      });
    }, true);

  })(window, document);
</script>
