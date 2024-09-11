@props([
    'part' => $part,
])

<div class="product">
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
                            @if (count($part->steel_types) <= 1)
                                @foreach ($part->steel_types as $steel_type)
                                    {{ $steel_type->title }}
                                @endforeach
                            @else
                                <select class="form-select js-choice" single name="steel_type_id" id="steel_type_id">
                                    @foreach ($part->steel_types as $steel_type)
                                        <option value="{{ $steel_type->id }}"
                                            {{ $part->steel_type_id == $steel_type->id ? 'selected' : '' }}>
                                            {{ $steel_type->title }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
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
                            @if (count($part->thicknesses) <= 1)
                                @foreach ($part->thicknesses as $thickness)
                                    {{ $thickness->title }}
                                @endforeach
                            @else
                                <select class="form-select js-choice" single name="thickness_id" id="thickness_id">
                                    @foreach ($part->thicknesses as $thickness)
                                        <option value="{{ $thickness->id }}"
                                            {{ $part->thickness_id == $thickness->id ? 'selected' : '' }}>
                                            {{ $thickness->title }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
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
                            @if (count($part->types) <= 1)
                                @foreach ($part->types as $type)
                                    {{ $type->title }}
                                @endforeach
                            @else
                                <select class="form-select js-choice" name="type_id" id="type_id">
                                    @foreach ($part->types as $type)
                                        <option value="{{ $type->id }}"
                                            {{ $part->type_id == $type->id ? 'selected' : '' }}>
                                            {{ $type->title }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif

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
                            @if (count($part->sizes) <= 1)
                                @foreach ($part->sizes as $size)
                                    {{ $size->title }}
                                @endforeach
                            @else
                                <select class="form-select js-choice" name="size_id" id="size_id">
                                    @foreach ($part->sizes as $size)
                                        <option value="{{ $size->id }}"
                                            {{ $part->size_id == $size->id ? 'selected' : '' }}>
                                            {{ $size->title }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif

                        </div>
                    </div>
                </li>
            @endif
            <li>
                <div class="product-info__item">
                    <div class="product-info__item_top">
                        <p class="product-info__item_title">Цена:</p>
                        <div class="product-info__item_value">Сюда цена выводится руб</div>
                    </div>
                </div>
            </li>
        </ul>

        <button class="btn product-btn" data-micromodal-trigger="modal-1"
            data-product-id="{{ $part->id }}">Заказать сейчас</button>
    </div>
</div>
