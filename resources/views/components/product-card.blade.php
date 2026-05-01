@props([
    'part' => $part,
])


<div class="product" data-prices="{{ json_encode($part->prices) }}" data-item="{{ json_encode($part) }}">


    <div class="modal micromodal-slide product-modal-card" id="modal-prod-{{ $part->slug }}" aria-hidden="true">
        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
            <div class="modal__container" role="dialog" aria-modal="true"
                aria-labelledby="modal-prod-{{ $part->slug }}-title"
                aria-describedby="modal-prod-{{ $part->slug }}-desc">
                <header class="modal__header">
                    <h2 class="modal__title" id="modal-prod-{{ $part->slug }}-title">Остались вопросы?</h2>
                    <p class="modal__description" id="modal-prod-{{ $part->slug }}-desc">Заполните форму и мы свяжемся с вами<br>в течение 5-ти минут и ответим на все вопросы</p>
                    <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
                </header>
                <form class="modal-form modal-form-product" action="{{ route('request_product.store') }}"
                    data-action="{{ route('request_product.store') }}" data-ym-goal="prices" data-ym-mode="manual"
                    method="POST">
                    @csrf
                    <div class="input-item">
                        <input type="text" placeholder="Имя" class="input" name="name" />
                        <div class="field-error" data-error-for="name"></div>
                    </div>

                    <div class="input-item">
                        <input type="tel" placeholder="+7 (999) 000-00-00" class="input" name="phone" />
                        <div class="field-error" data-error-for="phone"></div>
                    </div>

                    <input type="hidden" name="current_url" value="{{ request()->fullUrl() }}">
                    <input type="hidden" name="car" value="Без привязки к авто(Блок на главной)">
                    <input type="hidden" name="data" id="productDataInput" value="[]">
                    <input type="hidden" name="total_price" id="productTotalInput" value="">
                    <input type="hidden" name="form_id" value="modal-form-product-{{ $part->slug }}">
                    {{-- UTM метки --}}
                    <input type="hidden" name="utm_source" value="{{ $utm['utm_source'] ?? '' }}">
                    <input type="hidden" name="utm_medium" value="{{ $utm['utm_medium'] ?? '' }}">
                    <input type="hidden" name="utm_campaign" value="{{ $utm['utm_campaign'] ?? '' }}">
                    <input type="hidden" name="utm_term" value="{{ $utm['utm_term'] ?? '' }}">
                    <input type="hidden" name="utm_content" value="{{ $utm['utm_content'] ?? '' }}">
                    <input type="hidden" name="cm_id" value="{{ $utm['cm_id'] ?? '' }}">

                    <button class="btn lg submit-modal" type="submit">Отправить</button>

                    <div class="form-policy-wrap">
                        <div class="form-policy">
                            <input type="checkbox" id="product-policy-{{ $part->id }}" name="policy" value="1"
                                required checked>
                            <label for="product-policy-{{ $part->id }}">
                                Я соглашаюсь с
                                <a href="{{ url('/policy.pdf') }}" target="_blank" rel="noopener noreferrer">
                                    политикой конфиденциальности
                                </a>
                                и даю согласие на обработку персональных данных
                            </label>
                        </div>
                        <div class="field-error field-error--policy" data-error-for="policy"></div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="product-image">
        @if ($part->image === 'default')
            <img src="{{ asset('images/mark/no-image.png') }}" alt="Изображения нет" />
        @else
            <img src="{{ asset('storage') . '/' . $part->image }}" alt="Логотип {{ $part->title }}" />
        @endif
    </div>



    <h3 class="product-title">{{ $part->title }}</h3>

    <div class="product-info">
        <ul class="product-list">
            <!-- item -->
            @if ($part->steel_types->count() > 0)
                <li>
                    <div class="product-info__item">
                        <div class="product-info__item_top">
                            <p class="product-info__item_title">Материал:</p>

                            <select class="form-select steel-select js-choice" single name="steel_type_id"
                                id="steel_type_id">
                                @foreach ($part->steel_types as $steel_type)
                                    <option value="{{ $steel_type->id }}"
                                        {{ $part->steel_type_id == $steel_type->id ? 'selected' : '' }}>
                                        {{ $steel_type->title }}
                                    </option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                </li>
            @endif



            <!-- item -->
            @if ($part->thicknesses->count() > 0)
                <li>
                    <div class="product-info__item">
                        <div class="product-info__item_top">
                            <p class="product-info__item_title">Толщина металла:</p>

                            <select class="form-select thickness_select js-choice" single name="thickness_id"
                                id="thickness_id">
                                @foreach ($part->thicknesses as $thickness)
                                    <option value="{{ $thickness->id }}"
                                        {{ $part->thickness_id == $thickness->id ? 'selected' : '' }}>
                                        {{ $thickness->title }}
                                    </option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                </li>
            @endif

            <!-- item -->
            @if ($part->types->count() > 0)
                <li>
                    <div class="product-info__item">
                        <div class="product-info__item_top">
                            <p class="product-info__item_title">Тип:</p>

                            <select class="form-select type-selector js-choice" name="type_id" id="type_id">
                                @foreach ($part->types as $type)
                                    <option value="{{ $type->id }}"
                                        {{ $part->type_id == $type->id ? 'selected' : '' }}>
                                        {{ $type->title }}
                                    </option>
                                @endforeach
                            </select>


                        </div>
                    </div>
                </li>
            @endif
            <!-- item -->
            @if ($part->sizes->count() > 0)
                <li>
                    <div class="product-info__item">
                        <div class="product-info__item_top">
                            <p class="product-info__item_title">Размер:</p>

                            <select class="form-select size-selector js-choice" name="size_id" id="size_id">
                                @foreach ($part->sizes as $size)
                                    <option value="{{ $size->id }}"
                                        {{ $part->size_id == $size->id ? 'selected' : '' }}>
                                        {{ $size->title }}
                                    </option>
                                @endforeach
                            </select>


                        </div>
                    </div>
                </li>
            @endif
            <li>
                <div class="product-info__item">
                    <div class="product-info__item_top">
                        <p class="product-info__item_title">Цена:</p>
                        <div class="product-info__item_value product-price"><span></span> руб</div>
                    </div>
                </div>
            </li>
        </ul>

        <button class="btn product-btn" data-micromodal-trigger="modal-prod-{{ $part->slug }}"
            data-product-id="{{ $part->id }}">
            Заказать сейчас
        </button>
    </div>
</div>
