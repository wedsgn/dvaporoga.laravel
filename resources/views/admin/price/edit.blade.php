@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">{{ __('admin.edit_price_card_title') }} {{ $product->title }}</h4>
                </div>
                <div class="alert alert-warning alert-border-left alert-dismissible fade show" role="alert">
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  <div>
                      {{__('admin.notification_choose_param')}}
                  </div>
              </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger alert-border-left alert-dismissible fade show " role="alert">

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                    @foreach ($errors->all() as $error)
                        <div>
                            {{ $error }}
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="live-preview">
                        <form action="{{ route('admin.products.prices.priceUpdate', [$product->slug, $price->id]) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('patch')
                            <div class="row gy-4">

                                <div class="col-xxl-6 col-md-6">
                                    <div>
                                        <label for="valueInput" class="form-label">{{ __('admin.field_one_side') }}</label>
                                        <input type="text" value="{{ $price->one_side }}" class="form-control"
                                            id="valueInput" name="one_side"
                                            placeholder="{{ __('admin.placeholder_text') }}">
                                    </div>
                                </div>

                                <div class="col-xxl-6 col-md-6">
                                    <div>
                                        <label for="valueInput" class="form-label">{{ __('admin.field_set') }}</label>
                                        <input type="text" value="{{ $price->set }}" class="form-control"
                                            id="valueInput" name="set" placeholder="{{ __('admin.placeholder_text') }}">
                                    </div>
                                </div>

                                <div class="col-xxl-6 col-md-6">
                                    <div class="mb-3">
                                        <label for="valueInput"
                                            class="form-label">{{ __('admin.field_metal_thickness') }}</label>
                                        <select id="valueInput" class="form-control" name="thickness_id">
                                            <option value="" {{ $price->thickness_id == null ? 'selected' : '' }}>
                                                {{ __('admin.without_param') }}
                                            </option>
                                            @foreach ($thicknesses as $thickness)
                                                <option value="{{ $thickness->id }}"
                                                    {{ $price->thickness_id == $thickness->id ? 'selected' : '' }}>
                                                    {{ $thickness->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xxl-6 col-md-6">
                                    <div class="mb-3">
                                        <label for="valueInput"
                                            class="form-label">{{ __('admin.field_steel_type') }}</label>
                                        <select id="valueInput" class="form-control" name="steel_type_id">
                                            <option value="" {{ $price->steel_type_id == null ? 'selected' : '' }}>
                                                {{ __('admin.without_param') }}
                                            </option>
                                            @foreach ($steel_types as $steel_type)
                                                <option value="{{ $steel_type->id }}"
                                                    {{ $price->steel_type_id == $steel_type->id ? 'selected' : '' }}>
                                                    {{ $steel_type->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xxl-6 col-md-6">
                                    <div class="mb-3">
                                        <label for="valueInput"
                                            class="form-label">{{ __('admin.field_title_type') }}</label>
                                        <select id="valueInput" class="form-control" name="type_id">
                                            <option value="" {{ $price->type_id == null ? 'selected' : '' }}>
                                                {{ __('admin.without_param') }}
                                            </option>
                                            @foreach ($types as $type)
                                                <option value="{{ $type->id }}"
                                                    {{ $price->type_id == $type->id ? 'selected' : '' }}>
                                                    {{ $type->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xxl-6 col-md-6">
                                    <div class="mb-3">
                                        <label for="valueInput"
                                            class="form-label">{{ __('admin.field_title_size') }}</label>
                                        <select id="valueInput" class="form-control" name="size_id">
                                            <option value="" {{ $price->size_id == null ? 'selected' : '' }}>
                                                {{ __('admin.without_param') }}
                                            </option>
                                            @foreach ($sizes as $size)
                                                <option value="{{ $size->id }}"
                                                    {{ $price->size_id == $size->id ? 'selected' : '' }}>
                                                    {{ $size->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <button type="submit"
                                class="btn btn-success waves-effect waves-light mt-5">{{ __('admin.btn_save') }}</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @include('admin.upload_script')
@endsection
