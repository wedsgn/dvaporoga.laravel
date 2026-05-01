<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="{{ asset('images/favicon/favicon.ico') }}" type="image/x-icon" />
  <link rel="icon" href="{{ asset('images/favicon/mstile-144x144.png') }}" type="image/png" />
  <link rel="apple-touch-icon" href="{{ asset('images/favicon/apple.png') }}" />
  @if (!isset($page))
    <title>404 - Page not found</title>
    <meta name="description" content="Page not found">
  @else
    <title>{{ $page->meta_title }}</title>
    <meta name="description" content="{{ $page->meta_description }}">
    <meta name="keywords" content="{{ $page->meta_keywords }}">
    <meta property="og:locale" content="ru_RU">
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $page->og_title }}">
    <meta property="og:description" content="{{ $page->og_description }}">
    <meta property="og:url" content="{{ $page->og_url }}">
    <meta property="og:site_name" content="{{ $main_info->company_title }}">
    <meta property="og:image" content="{{ $main_info->company_image }}">
  @endif
  <meta name="yandex-verification" content="d134887cc07e929d" />
  <meta name="google-site-verification" content="D-rV3CqHjnyPJfkgP5TR3xLeMlrPQ4rhlJ_J8jR07CQ" />

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/fancybox/fancybox.css" />

  <script type="text/javascript">
    window._ab_id_ = 164880
  </script>
  <script src="https://cdn.botfaqtor.ru/one.js"></script>


  <link rel="canonical" href="{{ url()->current() }}" />
  <!-- Yandex.Metrika counter -->
  <script type="text/javascript">
    (function(m, e, t, r, i, k, a) {
      m[i] = m[i] || function() {
        (m[i].a = m[i].a || []).push(arguments)
      };
      m[i].l = 1 * new Date();
      for (var j = 0; j < document.scripts.length; j++) {
        if (document.scripts[j].src === r) {
          return;
        }
      }
      k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
    })(window, document, 'script', 'https://mc.yandex.ru/metrika/tag.js?id=104319970', 'ym');

    ym(104319970, 'init', {
      ssr: true,
      webvisor: true,
      clickmap: true,
      ecommerce: "dataLayer",
      accurateTrackBounce: true,
      trackLinks: true
    });
  </script>
  <noscript>
    <div><img src="https://mc.yandex.ru/watch/104319970" style="position:absolute; left:-9999px;" alt="" />
    </div>
  </noscript>
  <!-- /Yandex.Metrika counter -->

  <!-- UIS -->
  <script type="text/javascript" async src="https://app.uiscom.ru/static/cs.min.js?k=82NGqqgGe6LoP1GRRGMot38MQC4Sx2QW">
  </script>
  <!-- UIS -->
  <!-- /Yandex.Metrika counter -->
  @vite(['resources/css/app.scss', 'resources/js/app.js'])


</head>
