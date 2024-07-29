@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">{{ __('admin.new_product_card_title') }}</h4>
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
                        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row gy-4">

                                <div class="col-xxl-6 col-md-6">
                                    <div>
                                        <label for="valueInput" class="form-label">{{ __('admin.field_title') }} *</label>
                                        <input type="text" value="{{ old('title') }}" class="form-control"
                                            id="valueInput" name="title" placeholder="{{ __('admin.placeholder_text') }}">
                                    </div>
                                </div>
                                <div class="col-xxl-6 col-md-6">
                                    <div>
                                        <label for="valueInput"
                                            class="form-label">{{ __('admin.field_price_one_side') }}</label>
                                        <input type="text" value="{{ old('price_one_side') }}" class="form-control"
                                            id="valueInput" name="price_one_side"
                                            placeholder="{{ __('admin.placeholder_text') }}">
                                    </div>
                                </div>
                                <div class="col-xxl-6 col-md-6">
                                    <div>
                                        <label for="valueInput" class="form-label">{{ __('admin.field_price_set') }}</label>
                                        <input type="text" value="{{ old('price_set') }}" class="form-control"
                                            id="valueInput" name="price_set"
                                            placeholder="{{ __('admin.placeholder_text') }}">
                                    </div>
                                </div>
                                <div class="col-xxl-6 col-md-6">
                                    <div>
                                        <label for="valueInput"
                                            class="form-label">{{ __('admin.field_metal_thickness') }}</label>
                                        <input type="text" value="{{ old('metal_thickness') }}" class="form-control"
                                            id="valueInput" name="metal_thickness"
                                            placeholder="{{ __('admin.placeholder_text') }}">
                                    </div>
                                </div>
                                <div class="col-xxl-6 col-md-6">
                                    <div>
                                        <label for="valueInput" class="form-label">{{ __('admin.field_material') }}</label>
                                        <input type="text" value="{{ old('material') }}" class="form-control"
                                            id="valueInput" name="material"
                                            placeholder="{{ __('admin.placeholder_text') }}">
                                    </div>
                                </div>
                                <div class="col-xxl-6 col-md-6">
                                  <div>
                                      <label for="valueInput" class="form-label">{{ __('admin.field_side') }}</label>
                                      <input type="text" value="{{ old('side') }}" class="form-control"
                                          id="valueInput" name="side"
                                          placeholder="{{ __('admin.placeholder_text') }}">
                                  </div>
                              </div>
                                <div class="col-xxl-6 col-md-6">
                                    <div>
                                        <label for="valueInput" class="form-label">{{ __('admin.field_size') }}
                                            *</label>
                                        <input type="text" value="{{ old('size') }}" class="form-control"
                                            id="valueInput" name="size"
                                            placeholder="{{ __('admin.placeholder_text') }}">
                                    </div>
                                </div>

                                <div class="col-xxl-6 col-md-6">
                                    <div class="mb-3">
                                        <label for="valueInput"
                                            class="form-label">{{ __('admin.aside_title_cars') }}</label>

                                        @if (!count($cars) == 0)
                                            <select id="valueInput" class="form-control" data-choices
                                                data-choices-removeItem name="cars[]" multiple>
                                                @foreach ($cars as $car)
                                                    <option value="{{ $car->title }}"
                                                        {{ collect(old('cars'))->contains($car->title) ? 'selected' : '' }}>
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

                                <div class="col-xxl-6 col-md-6">
                                    <div>
                                        <label for="formFile" class="form-label">{{ __('admin.field_image_mob') }}</label>
                                        <input class="form-control" type="file" id="formFile" name="image_mob">
                                    </div>
                                </div>
                                <div class="col-xxl-6 col-md-6">
                                    <div>
                                        <label for="formFile" class="form-label">{{ __('admin.field_image') }}</label>
                                        <input class="form-control" type="file" id="formFile" name="image">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"
                                        for="basic-default-message">{{ __('admin.field_description') }}</label>
                                    <textarea id="basic-default-message" class="form-control" name="description"
                                        placeholder="{{ __('admin.placeholder_text') }}" style="height: 234px;">{{ old('description') }}</textarea>
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
