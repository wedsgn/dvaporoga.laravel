@extends('layouts.admin')

@section('content')
    <div class="row">


        <div class="col-lg-8">
            <div class="card">

                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="flex-grow-1">
                            <h3 class="card-header align-items-center d-flex">{{ __('admin.product_card_title') }}:
                                {{ $item->title }}</h3>
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
                                        <a type="button" class="dropdown-item" href="{{ route('admin.products.index') }}">
                                            <i class="ri-arrow-left-line align-bottom me-2 text-muted"></i>
                                            {{ __('admin.btn_back') }}</a>
                                    </li>

                                    <li><a href="{{ route('admin.products.edit', $item->slug) }}"
                                            class="dropdown-item edit-item-btn"><i
                                                class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                                            {{ __('admin.btn_edit') }}</a></li>
                                    <li>
                                        <button type="submit" class="dropdown-item text-danger" data-bs-toggle="modal"
                                            data-bs-target="#modalScrollable{{ $item->slug }}"><i
                                                class="bx bx-trash me-1 text-danger" role="button"></i>
                                            {{ __('admin.btn_delete') }}</button>
                                    </li>
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
            <div class="card">
                <div class="card-body">
                    <h5 class="card-header align-items-center d-flex">{{ __('admin.product_card_info') }}</h5>
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

                                <div class="modal fade" id="modalScrollable{{ $item->slug }}" tabindex="-1"
                                    style="display: none;" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalScrollableTitle">
                                                    {{ __('admin.question_delete') }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p
                                                    class="mt-1 text-sm text-gray-600 dark:text-gray-400  alert alert-warning text-wrap">
                                                    {{ __('admin.notification_delete') }}
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    data-bs-dismiss="modal">
                                                    {{ __('admin.btn_close') }}
                                                </button>
                                                <form action="{{ route('admin.products.destroy', $item->slug) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" data-bs-toggle="modal"
                                                        data-bs-target="#modalScrollableConfirm">{{ __('admin.btn_confirm') }}</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </tbody>
                        </table>
                    </div>
                </div><!-- end card body -->
            </div>
            <div class="card">
            <h5 class="card-header align-items-center d-flex">{{ __('admin.product_characteristics') }}</h5>
            <div class="card-body">
                <h5 class="card-title mb-4">{{ __('admin.aside_title_sizes') }} :</h5>
                <a href="{{ route('admin.products.sizes.sizeCreate', $item->slug) }}"
                    class="btn btn-sm btn-success"><i class="ri-add-line align-bottom me-2 text-muted"></i>
                    {{ __('admin.btn_add_size') }}</a>
                @if (session('success_size'))
                    <div class="mt-3 alert alert-success alert-dismissible fade show" role="alert">
                        <i class="ri-check-double-line align-middle"></i>
                        {{ session('success_size') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @endif
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('admin.field_size') }}</th>
                            <th>{{ __('admin.field_action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($item->sizes as $size)
                            <tr>
                                <td>{{ $size->title }}</td>
                                <td>
                                    <a href="{{ route('admin.products.sizes.sizeEdit', [$item->slug, $size->id]) }}"
                                        class="btn btn-sm btn-primary">{{ __('admin.btn_edit') }}</a>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#modalScrollableConfirmDeleteSize">
                                        {{ __('admin.btn_delete') }}
                                    </button>
                                </td>
                            </tr>
                            <div class="mt-4">
                                <div class="modal fade" id="modalScrollableConfirmDeleteSize" tabindex="-1"
                                    aria-labelledby="modalScrollableConfirmDeleteSizeLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalScrollableConfirmDeleteSizeLabel">
                                                    {{ __('admin.modal_title_delete') }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>{{ __('admin.modal_text_delete') }}</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    data-bs-dismiss="modal">
                                                    {{ __('admin.btn_close') }}
                                                </button>
                                                <form
                                                    action="{{ route('admin.products.sizes.sizeDestroy', [$item->slug, $size->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalScrollableConfirm">{{ __('admin.btn_confirm') }}</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="2" class="text-danger text-center">
                                    {{ __('admin.notification_no_entries') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-body">
                <h5 class="card-title mb-4">{{ __('admin.aside_title_steel_types') }} :</h5>
                <a href="{{ route('admin.products.steel_types.steelTypeCreate', $item->slug) }}"
                    class="btn btn-sm btn-success"><i class="ri-add-line align-bottom me-2 text-muted"></i>
                    {{ __('admin.btn_add_steel_type') }}</a>
                @if (session('success_steel_type'))
                    <div class="mt-3 alert alert-success alert-dismissible fade show" role="alert">
                        <i class="ri-check-double-line align-middle"></i>
                        {{ session('success_steel_type') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @endif
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('admin.field_steel_type') }}</th>
                            <th>{{ __('admin.field_action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($item->steel_types as $steel_type)
                            <tr>
                                <td>{{ $steel_type->title }}</td>
                                <td>
                                    <a href="{{ route('admin.products.steel_types.steelTypeEdit', [$item->slug, $steel_type->id]) }}"
                                        class="btn btn-sm btn-primary">{{ __('admin.btn_edit') }}</a>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#modalScrollableConfirmDeletesteelType">
                                        {{ __('admin.btn_delete') }}
                                    </button>
                                </td>
                            </tr>
                            <div class="mt-4">
                                <div class="modal fade" id="modalScrollableConfirmDeletesteelType" tabindex="-1"
                                    aria-labelledby="modalScrollableConfirmDeletesteelTypeLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"
                                                    id="modalScrollableConfirmDeletesteelTypeLabel">
                                                    {{ __('admin.modal_title_delete') }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>{{ __('admin.modal_text_delete') }}</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    data-bs-dismiss="modal">
                                                    {{ __('admin.btn_close') }}
                                                </button>
                                                <form
                                                    action="{{ route('admin.products.steel_types.steelTypeDestroy', [$item->slug, $steel_type->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalScrollableConfirm">{{ __('admin.btn_confirm') }}</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="2" class="text-danger text-center">
                                    {{ __('admin.notification_no_entries') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-body">
                <h5 class="card-title mb-4">{{ __('admin.aside_title_thicknesses') }} :</h5>
                <a href="{{ route('admin.products.thicknesses.thicknessCreate', $item->slug) }}"
                    class="btn btn-sm btn-success"><i class="ri-add-line align-bottom me-2 text-muted"></i>
                    {{ __('admin.btn_add_thickness') }}</a>
                @if (session('success_thickness'))
                    <div class="mt-3 alert alert-success alert-dismissible fade show" role="alert">
                        <i class="ri-check-double-line align-middle"></i>
                        {{ session('success_thickness') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @endif
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('admin.field_thickness') }}</th>
                            <th>{{ __('admin.field_action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($item->thicknesses as $thickness)
                            <tr>
                                <td>{{ $thickness->title }}</td>
                                <td>
                                    <a href="{{ route('admin.products.thicknesses.thicknessEdit', [$item->slug, $thickness->id]) }}"
                                        class="btn btn-sm btn-primary">{{ __('admin.btn_edit') }}</a>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#modalScrollableConfirmDeletethickness">
                                        {{ __('admin.btn_delete') }}
                                    </button>
                                </td>
                            </tr>
                            <div class="mt-4">
                                <div class="modal fade" id="modalScrollableConfirmDeletethickness" tabindex="-1"
                                    aria-labelledby="modalScrollableConfirmDeletethicknessLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"
                                                    id="modalScrollableConfirmDeletethicknessLabel">
                                                    {{ __('admin.modal_title_delete') }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>{{ __('admin.modal_text_delete') }}</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    data-bs-dismiss="modal">
                                                    {{ __('admin.btn_close') }}
                                                </button>
                                                <form
                                                    action="{{ route('admin.products.thicknesses.thicknessDestroy', [$item->slug, $thickness->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalScrollableConfirm">{{ __('admin.btn_confirm') }}</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="2" class="text-danger text-center">
                                    {{ __('admin.notification_no_entries') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-body">
                <h5 class="card-title mb-4">{{ __('admin.aside_title_types') }} :</h5>
                <a href="{{ route('admin.products.types.typeCreate', $item->slug) }}"
                    class="btn btn-sm btn-success"><i class="ri-add-line align-bottom me-2 text-muted"></i>
                    {{ __('admin.btn_add_type') }}</a>
                @if (session('success_type'))
                    <div class="mt-3 alert alert-success alert-dismissible fade show" role="alert">
                        <i class="ri-check-double-line align-middle"></i>
                        {{ session('success_type') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @endif
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('admin.field_type') }}</th>
                            <th>{{ __('admin.field_action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($item->types as $type)
                            <tr>
                                <td>{{ $type->title }}</td>
                                <td>
                                    <a href="{{ route('admin.products.types.typeEdit', [$item->slug, $type->id]) }}"
                                        class="btn btn-sm btn-primary">{{ __('admin.btn_edit') }}</a>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#modalScrollableConfirmDeletetype">
                                        {{ __('admin.btn_delete') }}
                                    </button>
                                </td>
                            </tr>
                            <div class="mt-4">
                                <div class="modal fade" id="modalScrollableConfirmDeletetype" tabindex="-1"
                                    aria-labelledby="modalScrollableConfirmDeletetypeLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalScrollableConfirmDeletetypeLabel">
                                                    {{ __('admin.modal_title_delete') }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>{{ __('admin.modal_text_delete') }}</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    data-bs-dismiss="modal">
                                                    {{ __('admin.btn_close') }}
                                                </button>
                                                <form
                                                    action="{{ route('admin.products.types.typeDestroy', [$item->slug, $type->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalScrollableConfirm">{{ __('admin.btn_confirm') }}</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="2" class="text-danger text-center">
                                    {{ __('admin.notification_no_entries') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-body">
                <h5 class="card-title mb-4">{{ __('admin.aside_title_prices') }} :</h5>
                <a href="{{ route('admin.products.prices.priceCreate', $item->slug) }}"
                    class="btn btn-sm btn-success"><i class="ri-add-line align-bottom me-2 text-muted"></i>
                    {{ __('admin.btn_add_price') }}</a>
                @if (session('success_price'))
                    <div class="mt-3 alert alert-success alert-dismissible fade show" role="alert">
                        <i class="ri-check-double-line align-middle"></i>
                        {{ session('success_price') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @endif
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('admin.field_price_one_side') }}</th>
                            <th>{{ __('admin.field_price_set') }}</th>
                            <th>{{ __('admin.field_action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($item->prices as $price)
                            <tr>
                                <td>{{ $price->one_side }}</td>
                                <td>{{ $price->set }}</td>
                                <td>
                                    <a href="{{ route('admin.products.prices.priceEdit', [$item->slug, $price->id]) }}"
                                        class="btn btn-sm btn-primary">{{ __('admin.btn_edit') }}</a>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#modalScrollableConfirmDeleteprice">
                                        {{ __('admin.btn_delete') }}
                                    </button>
                                </td>
                            </tr>
                            <div class="mt-4">
                                <div class="modal fade" id="modalScrollableConfirmDeleteprice" tabindex="-1"
                                    aria-labelledby="modalScrollableConfirmDeletepriceLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalScrollableConfirmDeletepriceLabel">
                                                    {{ __('admin.modal_title_delete') }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>{{ __('admin.modal_text_delete') }}</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    data-bs-dismiss="modal">
                                                    {{ __('admin.btn_close') }}
                                                </button>
                                                <form
                                                    action="{{ route('admin.products.prices.priceDestroy', [$item->slug, $price->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalScrollableConfirm">{{ __('admin.btn_confirm') }}</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="2" class="text-danger text-center">
                                    {{ __('admin.notification_no_entries') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            </div>
            <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-4">{{ __('admin.aside_title_cars') }} :</h5>
              <div class="d-flex flex-wrap gap-2 fs-16">
                  @forelse ($item->cars as $car)
                      <a href="{{ route('admin.cars.show', $car->slug) }}"
                          class="badge bg-primary-subtle text-primary">{{ $car->title }}</a>
                  @empty
                      <div class="text-danger">{{ __('admin.notification_no_entries') }}</div>
                  @endforelse
              </div>
          </div>
        </div>
        </div>
    </div>
@endsection
