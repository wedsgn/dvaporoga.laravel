@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">

                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="flex-grow-1">
                            <h3 class="card-header align-items-center d-flex">{{ __('admin.page_card_title') }}:
                                {{ $item->title_admin }}</h3>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="dropdown">
                                <a href="#" role="button" id="dropdownMenuLink1" data-bs-toggle="dropdown"
                                    aria-expanded="false" class="">
                                    <i class="ri-more-2-fill fs-14"></i>
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink1"
                                    style="">
                                    <li>
                                        <a type="button" class="dropdown-item" href="{{ route('admin.pages.index') }}">
                                            <i class="ri-arrow-left-line align-bottom me-2 text-muted"></i>
                                            {{ __('admin.btn_back') }}</a>
                                    </li>

                                    <li><a href="{{ route('admin.pages.edit', $item->slug) }}"
                                            class="dropdown-item edit-item-btn"><i
                                                class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                                            {{ __('admin.btn_edit') }}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    @if ($item->description)
                        <h5 class="text-muted">{{ __('admin.field_description') }}:</h5>
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td class="text-muted">{!! $item->description !!}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @else
                    @endif

                </div>
                <!--end card-body-->
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-header align-items-center d-flex">{{ __('admin.page_card_info') }}</h5>
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th class="ps-0" scope="row">Id:</th>
                                    <td class="text-muted">{{ $item->id }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">{{ __('admin.field_title') }}:</th>
                                    <td class="text-muted">{{ $item->title }}</td>
                                </tr>

                                <tr>
                                    <th class="ps-0" scope="row">{{ __('admin.field_slug') }}:</th>
                                    <td class="text-muted">{{ $item->slug }}</td>
                                </tr>

                                <tr>
                                    <th class="ps-0" scope="row">{{ __('admin.field_created') }}:</th>
                                    <td class="text-muted">{{ $item->created_at }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">{{ __('admin.field_updated') }}:</th>
                                    <td class="text-muted">{{ $item->updated_at }}</td>
                                </tr>

                            </tbody>
                        </table>

                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-header align-items-center d-flex">{{ __('admin.title_seo') }}</h5>
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>

                                <tr>
                                    <th class="ps-0" scope="row">{{ __('admin.field_meta_title') }}:</th>
                                    <td class="text-muted">{{ $item->meta_title }}</td>
                                </tr>

                                <tr>
                                    <th class="ps-0" scope="row">{{ __('admin.field_meta_description') }}:</th>
                                    <td class="text-muted">{{ $item->meta_description }}</td>
                                </tr>

                                <tr>
                                    <th class="ps-0" scope="row">{{ __('admin.field_meta_keywords') }}:</th>
                                    <td class="text-muted">{{ $item->meta_keywords }}</td>
                                </tr>

                                <tr>
                                    <th class="ps-0" scope="row">{{ __('admin.field_og_title') }}:</th>
                                    <td class="text-muted">{{ $item->og_title }}</td>
                                </tr>

                                <tr>
                                    <th class="ps-0" scope="row">{{ __('admin.field_og_description') }}:</th>
                                    <td class="text-muted">{{ $item->og_description }}</td>
                                </tr>

                                <tr>
                                    <th class="ps-0" scope="row">{{ __('admin.field_og_url') }}:</th>
                                    <td class="text-muted">{{ $item->og_url }}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div><!-- end card body -->
            </div>
        </div>
    </div>
@endsection
