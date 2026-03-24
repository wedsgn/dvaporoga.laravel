<!DOCTYPE html>
<html lang="ru">

@include('parts.head')

<body>
    @include('parts.header')

    @yield('content')

    @include('parts.footer')

    <x-forms.modal-request-form modal-id="modal-1" title-id="modal-1-title" desc-id="modal-1-desc" goal="banner"
        form-id="modal-1" checkbox-id="policy-modal-1"
        description="Мы свяжемся с вами в течение 5-ти минут
и ответим на все вопросы" />

    <x-forms.modal-request-form modal-id="modal-hero" title-id="modal-hero-title" desc-id="modal-hero-desc" goal="banner"
        form-id="modal-form-hero" checkbox-id="policy-modal-hero"
        description="Мы свяжемся с вами в течение 5-ти минут
и ответим на все вопросы" />

    <x-forms.modal-request-form modal-id="modal-about" title-id="modal-about-title" desc-id="modal-about-desc"
        goal="company" form-id="modal-form-about" checkbox-id="policy-modal-about"
        description="Мы свяжемся с вами в течение 5-ти минут
и ответим на все вопросы" />

    <x-forms.modal-request-form modal-id="modal-delivery" title-id="modal-delivery-title" desc-id="modal-delivery-desc"
        goal="delivery" form-id="modal-form-delivery" checkbox-id="policy-modal-delivery"
        description="Мы свяжемся с вами в течение 5-ти минут
и ответим на все вопросы" />

    <x-forms.modal-request-form modal-id="modal-faq" title-id="modal-faq-title" desc-id="modal-faq-desc" goal="faq"
        form-id="modal-form-faq" checkbox-id="policy-modal-faq"
        description="Мы свяжемся с вами в течение 5-ти минут
и ответим на все вопросы" />

    <x-forms.modal-request-form modal-id="modal-product" title-id="modal-product-title" desc-id="modal-product-desc"
        action="{{ route('request_product.store') }}" goal="calculator" form-id="modal-product"
        checkbox-id="policy-modal-product" product-mode="true" :car-title="$car->title ?? ''"
        description="Мы свяжемся с вами в течение 5-ти минут
и ответим на все вопросы" />

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

    <x-forms.modal-request-form modal-id="modal-3" title-id="modal-3-title" desc-id="modal-3-desc"
        title="Остались вопросы?" goal="automatic" form-id="modal-3" checkbox-id="policy-modal-3"
        description="Оставьте свой номер телефона и мы перезвоним Вам в кратчайшее время, чтобы ответить на все Ваши вопросы!" />
    <x-ui.float-widget :phone="$main_info->phone" :vk="$main_info->vk" :max="$main_info->max" :telegram="$main_info->telegram" />
    <x-ui.cookie-banner />
    <script src="https://app.reviewlab.ru/widget/index-es2015.js" defer></script>
</body>

</html>
