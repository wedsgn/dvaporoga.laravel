@props([
    'modalId',
    'titleId',
    'descId',
    'title' => 'Заполните форму',
    'description' => 'Мы свяжемся с вами в течение 5-ти минут и ответим на все вопросы',
    'action' => route('request_consultation.store'),
    'goal' => 'banner',
    'formId' => 'modal-form',
    'checkboxId' => null,
    'submitText' => 'Отправить',
    'productMode' => false,
    'carTitle' => '',
])

@php
    $checkboxId = $checkboxId ?: 'policy-' . $modalId;
@endphp

<div class="modal micromodal-slide" id="{{ $modalId }}" aria-hidden="true">
    <div class="modal__overlay" data-micromodal-close>
        <div
            class="modal__container"
            role="dialog"
            aria-modal="true"
            aria-labelledby="{{ $titleId }}"
            aria-describedby="{{ $descId }}"
            tabindex="-1"
        >
            <header class="modal__header">
                <h2 class="modal__title" id="{{ $titleId }}">{{ $title }}</h2>
                <p class="modal__description" id="{{ $descId }}">
                    {!! nl2br(e($description)) !!}
                </p>
                <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
            </header>

            <form
                @if($modalId === 'modal-product') id="modal-product-form" @endif
                class="modal-form"
                action="{{ $action }}"
                data-action="{{ $action }}"
                data-ym-goal="{{ $goal }}"
                data-ym-mode="manual"
            >
                @csrf

                <div class="input-item">
                    <input type="text" placeholder="Имя" class="input" name="name" />
                    <div class="field-error" data-error-for="name"></div>
                </div>

                <div class="input-item">
                    <input type="tel" placeholder="+7 (___) ___ __ __" class="input" name="phone" />
                    <div class="field-error" data-error-for="phone"></div>
                </div>

                <input type="hidden" name="form_id" value="{{ $formId }}">

                @if($productMode)
                    <input type="hidden" name="current_url" value="{{ request()->fullUrl() }}">
                    <input type="hidden" name="car" value="{{ $carTitle }}">
                    <input type="hidden" name="data" id="modal-product-data" value="[]">
                    <input type="hidden" name="total_price" id="modal-product-total" value="">
                @endif

                <input type="hidden" name="utm_source" value="{{ $utm['utm_source'] ?? '' }}">
                <input type="hidden" name="utm_medium" value="{{ $utm['utm_medium'] ?? '' }}">
                <input type="hidden" name="utm_campaign" value="{{ $utm['utm_campaign'] ?? '' }}">
                <input type="hidden" name="utm_term" value="{{ $utm['utm_term'] ?? '' }}">
                <input type="hidden" name="utm_content" value="{{ $utm['utm_content'] ?? '' }}">
                <input type="hidden" name="cm_id" value="{{ $utm['cm_id'] ?? '' }}">

                <div class="form-policy-wrap">
                    <div class="form-policy">
                        <input type="checkbox" id="{{ $checkboxId }}" name="policy" value="1" required>
                        <label for="{{ $checkboxId }}">
                            Я соглашаюсь с
                            <a href="{{ url('/policy.pdf') }}" target="_blank" rel="noopener noreferrer">
                                политикой конфиденциальности
                            </a>
                            и даю согласие на обработку персональных данных
                        </label>
                    </div>
                    <div class="field-error field-error--policy" data-error-for="policy"></div>
                </div>

                <button class="btn lg submit-modal" type="submit">{{ $submitText }}</button>
            </form>
        </div>
    </div>
</div>
