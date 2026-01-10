@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">
                        {{ __('admin.edit_product_card_title') }} {{ $item->title }}
                    </h4>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger alert-border-left alert-dismissible fade show " role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
        </div>

        @if (!empty($item->image_mob))
            <div class="col-xxl-6">
                <div class="card">
                    <div class="card-body">
                        <p class="card-title-desc text-muted">{{ __('admin.field_current_image_mob') }}</p>
                        <div class="live-preview">
                            <div>
                                <img src="{{ asset('storage') . '/' . $item->image_mob }}" class="img-fluid"
                                    alt="Responsive image">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (!empty($item->image))
            <div class="col-xxl-6">
                <div class="card">
                    <div class="card-body">
                        <p class="card-title-desc text-muted">{{ __('admin.field_current_image') }}</p>
                        <div class="live-preview">
                            <div>
                                <img src="{{ asset('storage') . '/' . $item->image }}" class="img-fluid"
                                    alt="Responsive image">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="live-preview">
                        <form action="{{ route('admin.products.update', $item->slug) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('patch')

                            <input type="hidden" name="id" value="{{ $item->id }}">

                            <div class="row gy-4">
                                <div class="col-xxl-6 col-md-6">
                                    <div>
                                        <label for="titleInput" class="form-label">{{ __('admin.field_title') }} *</label>
                                        <input type="text" value="{{ old('title', $item->title) }}" class="form-control"
                                            id="titleInput" name="title"
                                            placeholder="{{ __('admin.placeholder_text') }}">
                                        <input type="hidden" name="old_title" value="{{ $item->title }}">
                                    </div>
                                </div>

                                <div class="col-xxl-6 col-md-6">
                                    <div class="mb-3">
                                        <label for="carSelect"
                                            class="form-label">{{ __('admin.aside_title_cars') }}</label>

                                        @if (count($cars) > 0)
                                            <select id="carSelect" class="form-control" data-choices name="car_id">
                                                <option value="">{{ __('admin.placeholder_text') }}</option>

                                                @foreach ($cars as $car)
                                                    <option value="{{ $car->id }}"
                                                        {{ (string) old('car_id', $item->car_id) === (string) $car->id ? 'selected' : '' }}>
                                                        {{ $car->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <div class="text-danger">
                                                {{ __('admin.notification_no_entries_cars') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-xxl-4 col-md-4">
                                    <div>
                                        <label for="priceInput" class="form-label">Цена</label>
                                        <input type="number" min="0" step="1"
                                            value="{{ old('price', $item->price) }}" class="form-control" id="priceInput"
                                            name="price" placeholder="Например: 1850">
                                    </div>
                                </div>

                                <div class="col-xxl-4 col-md-4">
                                    <div>
                                        <label for="discountInput" class="form-label">Скидка, %</label>
                                        <input type="number" min="0" max="100" step="1"
                                            value="{{ old('discount_percentage', $item->discount_percentage) }}"
                                            class="form-control" id="discountInput" name="discount_percentage"
                                            placeholder="Например: 10">
                                    </div>
                                </div>

                                <div class="col-xxl-4 col-md-4">
                                    <div>
                                        <label for="priceOldInput" class="form-label">Старая цена</label>
                                        <input type="number" min="0" step="1"
                                            value="{{ old('price_old', $item->price_old) }}" class="form-control"
                                            id="priceOldInput" name="price_old" placeholder="Например: 2350">
                                    </div>
                                </div>
                                <div class="col-xxl-6 col-md-6">
                                    <div>
                                        <label for="imageMobFile"
                                            class="form-label">{{ __('admin.field_image_mob') }}</label>
                                        <input class="form-control" type="file" id="imageMobFile" name="image_mob">
                                    </div>
                                </div>

                                <div class="col-xxl-6 col-md-6">
                                    <div>
                                        <label for="imageFile" class="form-label">{{ __('admin.field_image') }}</label>
                                        <input class="form-control" type="file" id="imageFile" name="image">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"
                                        for="basic-default-message">{{ __('admin.field_description') }} *</label>
                                    <textarea id="basic-default-message" class="form-control" name="description"
                                        placeholder="{{ __('admin.placeholder_text') }}" style="height: 234px;">{{ old('description', $item->description) }}</textarea>
                                </div>
                            </div>

                            <button type="submit"
                                class="btn btn-soft-success waves-effect waves-light mt-5 float-end">{{ __('admin.btn_save') }}</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
