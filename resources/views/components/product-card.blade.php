@props([
    'part' => $part,
])

<div class="product" data-prices="{{ json_encode($part->prices) }}" data-item="{{ json_encode($part) }}">
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

        <button class="btn product-btn" data-micromodal-trigger="modal-1"
            data-product-id="{{ $part->id }}">Заказать сейчас</button>
    </div>
</div>
