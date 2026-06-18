@props([
    'goal' => 'banner',
    'formId' => 'car-single-form',
    'checkboxId' => 'request-form-policy',
    'title' => 'Оставьте заявку',
    'descr' => 'Мы подберем деталь под ваш автомобиль и ответим на все вопросы',
    'sectionClass' => '--white',
    'submitText' => 'Отправить',
])

<section class="car-single-form-section home-page-form-section {{ $sectionClass }}">
    <div class="container">
        <div class="car-single-form-section__card">
            <div class="car-single-form-section__top">
                <h2 class="car-single-form-section__title">{{ $title }}</h2>
            </div>

            <p class="car-single-form-section__descr">{{ $descr }}</p>

            <form
                class="car-single-form"
                action="{{ route('request_consultation.store') }}"
                data-action="{{ route('request_consultation.store') }}"
                data-ym-goal="{{ $goal }}"
                data-ym-mode="manual"
            >
                @csrf

                <input type="hidden" name="form_id" value="{{ $formId }}">
                <input
                    type="hidden"
                    name="current_url"
                    value="{{ url()->current() }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
                >

                <input type="hidden" name="utm_source" value="{{ $utm['utm_source'] ?? '' }}">
                <input type="hidden" name="utm_medium" value="{{ $utm['utm_medium'] ?? '' }}">
                <input type="hidden" name="utm_campaign" value="{{ $utm['utm_campaign'] ?? '' }}">
                <input type="hidden" name="utm_term" value="{{ $utm['utm_term'] ?? '' }}">
                <input type="hidden" name="utm_content" value="{{ $utm['utm_content'] ?? '' }}">
                <input type="hidden" name="cm_id" value="{{ $utm['cm_id'] ?? '' }}">

                <div class="choose-section__form_row">
                    <div class="input-item">
                        <input class="input" type="text" name="name" placeholder="Имя">
                        <div class="field-error" data-error-for="name"></div>
                    </div>

                    <div class="input-item">
                        <input class="input" type="tel" name="phone" placeholder="+7 (999) 000-00-00">
                        <div class="field-error" data-error-for="phone"></div>
                    </div>

                    <button type="submit" class="btn btn-black car-single-form-btn">{{ $submitText }}</button>
                </div>

                <div class="form-policy-wrap">
                    <div class="form-policy">
                        <input type="checkbox" id="{{ $checkboxId }}" name="policy" value="1" required>
                        <label for="{{ $checkboxId }}">
                            <x-forms.policy-consent :submit-text="$submitText" />
                        </label>
                    </div>

                    <div class="field-error field-error--policy" data-error-for="policy"></div>
                </div>
            </form>
        </div>
    </div>
</section>
