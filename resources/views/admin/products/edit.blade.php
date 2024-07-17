@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">{{ __('admin.edit_product_card_title') }} {{ $item->title }}
                    </h4>
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
        @else
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
        @else
        @endif
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="live-preview">
                        <form action="{{ route('admin.products.update', $item->slug) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('patch')
                            <div class="row gy-4">
                                <div class="col-xxl-6 col-md-6">
                                    <div>
                                        <label for="valueInput" class="form-label">{{ __('admin.field_title') }} *</label>
                                        <input type="text" value="{{ $item->title }}" class="form-control"
                                            id="valueInput" name="title"
                                            placeholder="{{ __('admin.placeholder_text') }}">
                                        <input type="hidden"name="old_title" value="{{ $item->title }}">
                                    </div>
                                </div>
                                <div class="col-xxl-6 col-md-6">
                                    <div>
                                        <label for="valueInput"
                                            class="form-label">{{ __('admin.field_price_one_side') }}</label>
                                        <input type="text" value="{{ $item->price_one_side }}" class="form-control"
                                            id="valueInput" name="price_one_side"
                                            placeholder="{{ __('admin.placeholder_text') }}">
                                    </div>
                                </div>
                                <div class="col-xxl-6 col-md-6">
                                    <div>
                                        <label for="valueInput"
                                            class="form-label">{{ __('admin.field_price_set') }}</label>
                                        <input type="text" value="{{ $item->price_set }}" class="form-control"
                                            id="valueInput" name="price_set"
                                            placeholder="{{ __('admin.placeholder_text') }}">
                                    </div>
                                </div>
                                <div class="col-xxl-6 col-md-6">
                                    <div>
                                        <label for="valueInput"
                                            class="form-label">{{ __('admin.field_metal_thickness') }}</label>
                                        <input type="text" value="{{ $item->metal_thickness }}" class="form-control"
                                            id="valueInput" name="metal_thickness"
                                            placeholder="{{ __('admin.placeholder_text') }}">
                                    </div>
                                </div>
                                <div class="col-xxl-6 col-md-6">
                                    <div>
                                        <label for="valueInput" class="form-label">{{ __('admin.field_size') }}
                                            *</label>
                                        <input type="text" value="{{ $item->size }}" class="form-control"
                                            id="valueInput" name="size"
                                            placeholder="{{ __('admin.placeholder_text') }}">
                                    </div>
                                </div>

                                <div class="col-xxl-6 col-md-6">
                                  <div class="mb-3">
                                      <label for="valueInput" class="form-label">{{ __('admin.aside_title_cars') }}</label>
                                      @if (!count($cars) == 0)
                                      <select id="valueInput" class="form-control" data-choices data-choices-removeItem name="cars[]" multiple>
                                          @foreach ($cars as $car)
                                          <option value="{{ $car->title }}" {{ collect($item->cars)->contains('title', $car->title) ? 'selected' : '' }}>
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
                                        for="basic-default-message">{{ __('admin.field_description') }} *</label>
                                    <textarea id="basic-default-message" class="form-control" name="description"
                                        placeholder="{{ __('admin.placeholder_text') }}" style="height: 234px;">{{ $item->description }}</textarea>
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
