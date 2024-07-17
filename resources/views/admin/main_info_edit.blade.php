@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">{{ __('admin.edit_company_info') }}
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

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="live-preview">
                        <form action="{{ route('admin.update_info', $item->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('patch')
                            <div class="row gy-4">
                                <div class="col-xxl-6 col-md-6">
                                    <div>
                                        <label for="valueInput" class="form-label">{{ __('admin.fild_company_title') }}
                                            *</label>
                                        <input type="text" value="{{ $item->company_title }}" class="form-control"
                                            id="valueInput" name="company_title"
                                            placeholder="{{ __('admin.placeholder_text') }}">
                                    </div>
                                </div>
                                <div class="col-xxl-6 col-md-6">
                                    <div>
                                        <label for="valueInput" class="form-label">{{ __('admin.fild_phone') }} *</label>
                                        <input type="text" value="{{ $item->phone }}" class="form-control"
                                            id="valueInput" name="phone" placeholder="{{ __('admin.placeholder_text') }}">
                                    </div>
                                </div>
                                <div class="col-xxl-6 col-md-6">
                                    <div>
                                        <label for="valueInput" class="form-label">{{ __('admin.fild_whats_app') }}
                                            *</label>
                                        <input type="text" value="{{ $item->whats_app }}" class="form-control"
                                            id="valueInput" name="whats_app"
                                            placeholder="{{ __('admin.placeholder_text') }}">
                                    </div>
                                </div>
                                <div class="col-xxl-6 col-md-6">
                                    <div>
                                        <label for="valueInput" class="form-label">{{ __('admin.fild_telegram') }}
                                            *</label>
                                        <input type="text" value="{{ $item->telegram }}" class="form-control"
                                            id="valueInput" name="telegram"
                                            placeholder="{{ __('admin.placeholder_text') }}">
                                    </div>
                                </div>
                                <div class="col-xxl-6 col-md-6">
                                    <div>
                                        <label for="valueInput" class="form-label">{{ __('admin.fild_company_details') }}
                                            *</label>
                                        <input type="text" value="{{ $item->company_details }}" class="form-control"
                                            id="valueInput" name="company_details"
                                            placeholder="{{ __('admin.placeholder_text') }}">
                                    </div>
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
