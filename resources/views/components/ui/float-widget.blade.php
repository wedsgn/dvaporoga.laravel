@props([
    'phone' => null,
    'vk' => null,
    'max' => null,
    'telegram' => null,
])

<div class="call-float" id="callFloatWidget">
    <div class="call-float__items" id="callFloatItems">
        @if (!empty($telegram))
            <a href="{{ $telegram }}"
               class="call-float__item call-float__item--social"
               target="_blank"
               rel="noopener noreferrer"
               aria-label="Telegram">
                <img src="{{ asset('images/socials/tg.svg') }}" alt="" width="28" height="28">
            </a>
        @endif

        @if (!empty($max))
            <a href="{{ $max }}"
               class="call-float__item call-float__item--social"
               target="_blank"
               rel="noopener noreferrer"
               aria-label="MAX">
                <img src="{{ asset('images/socials/max.svg') }}" alt="" width="28" height="28">
            </a>
        @endif

        @if (!empty($vk))
            <a href="{{ $vk }}"
               class="call-float__item call-float__item--social"
               target="_blank"
               rel="noopener noreferrer"
               aria-label="ВКонтакте">
                <img src="{{ asset('images/socials/vk.svg') }}" alt="" width="28" height="28">
            </a>
        @endif

        @if (!empty($phone))
            <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}"
               class="call-float__item call-float__item--phone"
               aria-label="Позвонить">
                <img src="{{ asset('images/logos/widget/phone-widget.svg') }}" alt="" width="28" height="28">
            </a>
        @endif
    </div>

    <button class="call-float__toggle" id="callFloatToggle" type="button" aria-label="Открыть меню">
        <span class="call-float__ring"></span>
        <span class="call-float__btn">
            <img class="call-float__icon call-float__icon--menu"
                 src="{{ asset('images/logos/widget/menu-widget.svg') }}"
                 alt=""
                 width="28"
                 height="28">

            <img class="call-float__icon call-float__icon--close"
                 src="{{ asset('images/logos/widget/close-widget.svg') }}"
                 alt=""
                 width="28"
                 height="28">
        </span>
    </button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const widget = document.getElementById('callFloatWidget');
    const toggle = document.getElementById('callFloatToggle');

    if (!widget || !toggle) return;

    toggle.addEventListener('click', function (e) {
        e.stopPropagation();
        widget.classList.toggle('is-open');
    });

    document.addEventListener('click', function (e) {
        if (!widget.contains(e.target)) {
            widget.classList.remove('is-open');
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            widget.classList.remove('is-open');
        }
    });
});
</script>
