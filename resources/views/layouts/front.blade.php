<!DOCTYPE html>
<html lang="ru">

@include('parts.head')

<body>
    @include('parts.header')

    @yield('content')

    @include('parts.footer')

    <x-forms.modal-request-form
        modal-id="modal-1"
        title-id="modal-1-title"
        desc-id="modal-1-desc"
        goal="banner"
        form-id="modal-1"
        checkbox-id="policy-modal-1"
        description="Мы свяжемся с вами в течение 5-ти минут
и ответим на все вопросы"
    />

    <x-forms.modal-request-form
        modal-id="modal-hero"
        title-id="modal-hero-title"
        desc-id="modal-hero-desc"
        goal="banner"
        form-id="modal-form-hero"
        checkbox-id="policy-modal-hero"
        description="Мы свяжемся с вами в течение 5-ти минут
и ответим на все вопросы"
    />

    <x-forms.modal-request-form
        modal-id="modal-about"
        title-id="modal-about-title"
        desc-id="modal-about-desc"
        goal="company"
        form-id="modal-form-about"
        checkbox-id="policy-modal-about"
        description="Мы свяжемся с вами в течение 5-ти минут
и ответим на все вопросы"
    />

    <x-forms.modal-request-form
        modal-id="modal-delivery"
        title-id="modal-delivery-title"
        desc-id="modal-delivery-desc"
        goal="delivery"
        form-id="modal-form-delivery"
        checkbox-id="policy-modal-delivery"
        description="Мы свяжемся с вами в течение 5-ти минут
и ответим на все вопросы"
    />

    <x-forms.modal-request-form
        modal-id="modal-faq"
        title-id="modal-faq-title"
        desc-id="modal-faq-desc"
        goal="faq"
        form-id="modal-form-faq"
        checkbox-id="policy-modal-faq"
        description="Мы свяжемся с вами в течение 5-ти минут
и ответим на все вопросы"
    />

    <x-forms.modal-request-form
        modal-id="modal-product"
        title-id="modal-product-title"
        desc-id="modal-product-desc"
        action="{{ route('request_product.store') }}"
        goal="calculator"
        form-id="modal-product"
        checkbox-id="policy-modal-product"
        product-mode="true"
        :car-title="$car->title ?? ''"
        description="Мы свяжемся с вами в течение 5-ти минут
и ответим на все вопросы"
    />

    <div class="modal modal-success micromodal-slide" id="modal-2" aria-hidden="true">
        <div class="modal__overlay" data-micromodal-close>
            <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-2-title"
                aria-describedby="modal-2-desc" tabindex="-1">
                <header class="modal__header">
                    <h2 class="modal__title" id="modal-2-title">Заявка успешно отправлена</h2>
                    <p class="modal__description" id="modal-2-desc">
                        Менеджер свяжется в течении 5 минут.
                        <br>
                        Время работы с 9:00 до 21:00 по Мск
                    </p>
                    <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
                </header>
            </div>
        </div>
    </div>

    <x-forms.modal-request-form
        modal-id="modal-3"
        title-id="modal-3-title"
        desc-id="modal-3-desc"
        title="Остались вопросы?"
        goal="automatic"
        form-id="modal-3"
        checkbox-id="policy-modal-3"
        description="Оставьте свой номер телефона и мы перезвоним Вам в кратчайшее время, чтобы ответить на все Ваши вопросы!"
    />

    <a href="tel:{{ $main_info->phone }}" class="call-float" aria-label="Позвонить">
        <span class="call-float__ring"></span>
        <span class="call-float__btn">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2
        19.86 19.86 0 0 1-8.63-3.07
        19.5 19.5 0 0 1-6-6
        19.86 19.86 0 0 1-3.07-8.67
        A2 2 0 0 1 4.11 2h3
        a2 2 0 0 1 2 1.72
        12.84 12.84 0 0 0 .7 2.81
        a2 2 0 0 1-.45 2.11L8.09 9.91
        a16 16 0 0 0 6 6l1.27-1.27
        a2 2 0 0 1 2.11-.45
        12.84 12.84 0 0 0 2.81.7
        A2 2 0 0 1 22 16.92z" />
            </svg>
        </span>
    </a>

    <script>
        if (!window._teletypeWidget) {
            window._teletypeWidget = window._teletypeWidget || {};
            !function() {
                var t = document.getElementsByTagName("app-teletype-root");
                if (t.length > 0 && _teletypeWidget.init) return;
                var d = new Date().getTime();
                var n = document.createElement("script"),
                    c = document.getElementsByTagName("script")[0];
                n.id = "teletype-widget-embed";
                n.src = "https://widget.teletype.app/init.js?_=" + d;
                n.async = !0;
                n.setAttribute("data-embed-version", "0.1");
                c.parentNode.insertBefore(n, c);
            }();

            document.addEventListener("teletype.ready", function() {
                console.log("Teletype ready");
            });

            window.teletypeExternalId = "9r-HEiFWuZSmfbxCznKb6eaiqhAQ_cGYiIaCvpzpesillSszGAEE-SLrPf945waD";
        }
    </script>

    <x-ui.cookie-banner />

    <script src="{{ asset('/js/forms-ajax.js') }}"></script>
    <script src="{{ asset('/js/ym-goals.js') }}"></script>
    <script src="{{ asset('/js/product_calc.js') }}"></script>
    <script src="https://app.reviewlab.ru/widget/index-es2015.js" defer></script>
</body>
</html>
