@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-xxl-12">
            <div class="card">
                <div class="demo-inline-spacing">
                    @if (session('status') === 'main_info-updated')
                        <div class="alert alert-primary alert-dismissible" role="alert">
                            {{ __('admin.alert_updated') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session('status') === 'import-cars-success')
                        <div class="alert alert-success alert-dismissible" role="alert">
                            {{ __('admin.alert_cars_success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session('status') === 'import-products-success')
                        <div class="alert alert-success alert-dismissible" role="alert">
                            {{ __('admin.alert_products_success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <h5 class="card-title mb-3">{{ __('admin.company_info') }}</h5>

                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th class="ps-0" scope="row">{{ __('admin.fild_company_title') }} :</th>
                                    <td class="text-muted">{{ $main_info->company_title }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">{{ __('admin.fild_phone') }} :</th>
                                    <td class="text-muted">{{ $main_info->phone }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">{{ __('admin.fild_whats_app') }} :</th>
                                    <td class="text-muted">{{ $main_info->whats_app }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">{{ __('admin.fild_telegram') }} :</th>
                                    <td class="text-muted">{{ $main_info->telegram }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">{{ __('admin.fild_company_details') }} :</th>
                                    <td class="text-muted">{{ $main_info->company_details }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div><!-- end card body -->
            </div>
            <div class="col-sm-auto ms-auto">
                <div class="list-grid-nav hstack gap-1">
                    <a href="{{ route('admin.edit_info', $main_info->id) }}" class="btn btn-soft-success addMembers-modal">
                        <i class="ri-pencil-fill align-bottom me-2 text-muted"></i>{{ __('admin.btn_edit_company_info') }}
                    </a>
                    <a href="{{ route('admin.import_cars') }}"class="btn btn-primary btn-label right">
                        <i class="ri-roadster-fill label-icon align-middle fs-16 ms-2"></i> {{ __('admin.btn_load_cars') }}
                    </a>
                    <a href="{{ route('admin.import_products') }}" class="btn btn-primary btn-label right">
                        <i class="ri-hammer-line label-icon align-middle fs-16 ms-2"></i>
                        {{ __('admin.btn_load_products') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
