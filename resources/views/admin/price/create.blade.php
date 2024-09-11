@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">{{__('admin.new_price_card_title')}}  {{ $product->title }}</h4>
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
                        <form action="{{ route('admin.products.prices.priceStore', $product->slug) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row gy-4">

                            <div class="col-xxl-6 col-md-6">
                                <div>
                                    <label for="valueInput" class="form-label">{{ __('admin.field_one_side') }} *</label>
                                    <input type="text" value="{{ old('one_side') }}" class="form-control"
                                        id="valueInput" name="one_side" placeholder="{{ __('admin.placeholder_text') }}">
                                </div>
                            </div>

                            <div class="col-xxl-6 col-md-6">
                              <div>
                                  <label for="valueInput" class="form-label">{{ __('admin.field_set') }} *</label>
                                  <input type="text" value="{{ old('set') }}" class="form-control"
                                      id="valueInput" name="set" placeholder="{{ __('admin.placeholder_text') }}">
                              </div>
                          </div>

                            <div class="col-xxl-6 col-md-6">
                                <label for="valueInput" class="form-label">{{__('admin.field_thickness')}} *</label>
                                <select type="text" data-choices class="form-control" name="thickness_id"
                                    id="valueInput">
                                    <option value="" {{ old('thickness_id') == null ? 'selected' : '' }}>
                                        {{ __('admin.without_param') }}
                                    </option>
                                    @if (!count($thicknesses) == 0)
                                        @foreach ($thicknesses as $item)
                                            <option value="{{ $item->title }}"
                                                {{ $item->id == old('thickness_id') ? 'selected' : '' }}>
                                                {{ $item->title }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="" disabled>{{__('admin.notification_no_entries_thickness')}}</option>
                                    @endif
                                </select>
                            </div>

                            <div class="col-xxl-6 col-md-6">
                                <label for="valueInput" class="form-label">{{__('admin.field_steel_type')}} *</label>
                                @if (!count($steel_types) == 0)
                                    <select type="text" data-choices class="form-control" name="steel_type_id"
                                        id="valueInput">
                                        <option value="" {{ old('steel_type_id') == null ? 'selected' : '' }}>
                                          {{ __('admin.without_param') }}
                                      </option>
                                        @foreach ($steel_types as $item)
                                            <option value="{{ $item->title }}"
                                                {{ $item->id == old('steel_type_id') ? 'selected' : '' }}>
                                                {{ $item->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <div class="text-danger">
                                        {{__('admin.notification_no_entries_steel_type')}}
                                    </div>
                                @endif
                            </div>

                            <div class="col-xxl-6 col-md-6">
                                <label for="valueInput" class="form-label">{{__('admin.field_type')}} *</label>
                                @if (!count($types) == 0)
                                    <select type="text" data-choices class="form-control" name="type_id"
                                        id="valueInput">
                                        <option value="" {{ old('type_id') == null ? 'selected' : '' }}>
                                          {{ __('admin.without_param') }}
                                      </option>
                                        @foreach ($types as $item)
                                            <option value="{{ $item->title }}"
                                                {{ $item->id == old('type_id') ? 'selected' : '' }}>
                                                {{ $item->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <div class="text-danger">
                                        {{__('admin.notification_no_entries_type')}}
                                    </div>
                                @endif
                            </div>

                            <div class="col-xxl-6 col-md-6">
                                <label for="valueInput" class="form-label">{{__('admin.field_size')}} *</label>
                                @if (!count($sizes) == 0)
                                    <select type="text" data-choices class="form-control" name="size_id"
                                        id="valueInput">
                                        <option value="" {{ old('size_id') == null ? 'selected' : '' }}>
                                          {{ __('admin.without_param') }}
                                      </option>
                                        @foreach ($sizes as $item)
                                            <option value="{{ $item->title }}"
                                                {{ $item->id == old('size_id') ? 'selected' : '' }}>
                                                {{ $item->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <div class="text-danger">
                                        {{__('admin.notification_no_entries_size')}}
                                    </div>
                                @endif
                            </div>

                            </div>
                            <button type="submit" class="btn btn-success waves-effect waves-light mt-5">{{__('admin.btn_save')}}</button>

                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

@include('admin.upload_script')
@endsection

