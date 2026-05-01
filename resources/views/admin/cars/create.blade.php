@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">{{ __('admin.new_car_card_title') }}</h4>
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
                        <form action="{{ route('admin.cars.store') }}" method="POST" enctype="multipart/form-data">
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
                                            class="form-label">{{ __('admin.field_generation') }}</label>
                                        <input type="text" value="{{ old('generation') }}" class="form-control"
                                            id="valueInput" name="generation"
                                            placeholder="{{ __('admin.placeholder_text') }}">
                                    </div>
                                </div>
                                <div class="col-xxl-6 col-md-6">
                                    <div>
                                        <label for="valueInput" class="form-label">{{ __('admin.field_years') }}</label>
                                        <input type="text" value="{{ old('years') }}" class="form-control"
                                            id="valueInput" name="years"
                                            placeholder="{{ __('admin.placeholder_text') }}">
                                    </div>
                                </div>
                                <div class="col-xxl-6 col-md-6">
                                    <div>
                                        <label for="valueInput" class="form-label">{{ __('admin.field_body') }} *</label>
                                        <input type="text" value="{{ old('body') }}" class="form-control"
                                            id="valueInput" name="body"
                                            placeholder="{{ __('admin.placeholder_text') }}">
                                    </div>
                                </div>
                                <div class="col-xxl-6 col-md-6">
                                    <div>
                                        <label for="valueInput" class="form-label">{{ __('admin.field_artikul') }}
                                            *</label>
                                        <input type="text" value="{{ old('artikul') }}" class="form-control"
                                            id="valueInput" name="artikul"
                                            placeholder="{{ __('admin.placeholder_text') }}">
                                    </div>
                                </div>
                                <div class="col-xxl-6 col-md-6">
                                  <div>
                                      <label for="valueInput" class="form-label">{{ __('admin.field_top') }}
                                          *</label>
                                      <input type="text" value="{{ old('top') }}" class="form-control"
                                          id="valueInput" name="top"
                                          placeholder="{{ __('admin.placeholder_text') }}">
                                  </div>
                              </div>
                                <div class="col-xxl-6 col-md-6">
                                    <label for="valueInput" class="form-label">{{ __('admin.car_make_card_title') }} *</label>
                                    @if (!count($car_models) == 0)
                                        <select type="text" data-choices class="form-control" name="car_model_id"
                                            id="valueInput">
                                            @foreach ($car_models as $item)
                                                <option value="{{ $item->title }}"
                                                    {{ $item->id == old('car_model_id') ? 'selected' : '' }}>
                                                    {{ $item->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        <div class="text-danger">
                                            {{ __('admin.notification_no_entries_car_models') }}
                                        </div>
                                    @endif
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
                                        placeholder="{{ __('admin.placeholder_text') }}" style="height: 234px;">{{ old('description') }}</textarea>
                                </div>
                            </div>

                            <div class="row gy-4">

                            <div class="card-header align-items-center d-flex">
                            </div>
                                <div class="col-xxl-6 col-md-6">
                                  <h4 class="card-title mb-0 flex-grow-1">{{ __('admin.title_seo') }}</h4>
                                    <div>
                                        <label for="valueInput" class="form-label">{{ __('admin.field_meta_title') }}</label>
                                        <input type="text" value="{{ old('meta_title') }}" class="form-control"
                                            id="valueInput" name="meta_title"
                                            placeholder="{{ __('admin.placeholder_text') }}">
                                    </div>
                                    <div>
                                        <label for="valueInput" class="form-label">{{ __('admin.field_meta_keywords') }}</label>
                                        <input type="text" value="{{ old('meta_keywords') }}" class="form-control"
                                            id="valueInput" name="meta_keywords"
                                            placeholder="{{ __('admin.placeholder_text') }}">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __('admin.field_meta_description') }}</label>
                                    <textarea id="editor" class="form-control" name="meta_description" placeholder="{{ __('admin.placeholder_text') }}"
                                        style="height: 234px;">{{ old('meta_description') }}</textarea>
                                </div>
                                <div class="col-xxl-6 col-md-6">
                                    <div>
                                        <label for="valueInput" class="form-label">{{ __('admin.field_og_url') }}</label>
                                        <input type="text" value="{{ old('og_url') }}" class="form-control"
                                            id="valueInput" name="og_url"
                                            placeholder="{{ __('admin.placeholder_text') }}">
                                    </div>
                                    <div>
                                        <label for="valueInput" class="form-label">{{ __('admin.field_og_title') }}</label>
                                        <input type="text" value="{{ old('og_title') }}" class="form-control"
                                            id="valueInput" name="og_title"
                                            placeholder="{{ __('admin.placeholder_text') }}">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __('admin.field_og_description') }}</label>
                                    <textarea id="editor" class="form-control" name="og_description"
                                        placeholder="{{ __('admin.placeholder_text') }}" style="height: 234px;">{{ old('og_description') }}</textarea>
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
