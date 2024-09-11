@props([
    'part' => $part,
])



<div class="product" data-prices="{{ json_encode($part->prices) }}" data-item="{{ json_encode($part) }}">


    <div class="modal micromodal-slide product-modal-card" id="modal-prod-{{ $part->slug }}" aria-hidden="true">
        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
            <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
                <header class="modal__header">
                    <h2 class="modal__title" id="modal-1-title">Заполните форму</h2>
                    <p class="modal__description">Мы свяжемся с вами в течение 5-nb минут <br> и ответим на все вопросы
                    </p>
                    <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
                </header>
                <form class="modal-form-product">
                    @csrf
                    <input type="hidden" name="product_id" id="productIdInput" value="{{ $part->id }}" />
                    <input type="hidden" name="product_price" id="productPriceInput" value="" />
                    <input type="hidden" name="price_id" id="productPriceId" value="" />
                    <input type="text" placeholder="Имя" class="input" name="name" required />
                    <input type="tel" placeholder="+7 (___) ___ __ __" class="input" name="phone" required />
                    <input type="hidden" name="form_id" value="Форма с карточки товара">

                    <button class="btn lg submit-modal" type="submit">Отправить</button>

                    <p class="copyright">
                        Нажимая кнопку “Отправить” вы соглашаетесь с нашей
                        <a href="" download=""> политикой конфиденциальности </a>
                    </p>
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
