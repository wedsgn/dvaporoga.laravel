@extends('layouts.admin')

@section('content')
    <div class="row">

        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="flex-grow-1">
                            <h3 class="card-header align-items-center d-flex">
                                {{ __('admin.product_card_title') }}: {{ $item->title }}
                            </h3>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="dropdown">
                                <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ri-more-2-fill fs-14"></i>
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.products.index') }}">
                                            <i class="ri-arrow-left-line align-bottom me-2 text-muted"></i>
                                            {{ __('admin.btn_back') }}
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('admin.products.edit', $item->slug) }}"
                                            class="dropdown-item edit-item-btn">
                                            <i class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                                            {{ __('admin.btn_edit') }}
                                        </a>
                                    </li>

                                    <li>
                                        <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal"
                                            data-bs-target="#modalScrollable{{ $item->id }}">
                                            <i class="bx bx-trash me-1 text-danger"></i>
                                            {{ __('admin.btn_delete') }}
                                        </button>
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
                    @endif
                </div>
            </div>

            @if ($item->image_mob)
                <div class="col-xxl-6">
                    <div class="card">
                        <div class="card-body">
                            <p class="card-title-desc text-muted">{{ __('admin.field_current_image_mob') }}</p>
                            <img src="{{ asset('storage/' . $item->image_mob) }}" class="img-fluid">
                        </div>
                    </div>
                </div>
            @endif

            @if ($item->image)
                <div class="col-xxl-6">
                    <div class="card">
                        <div class="card-body">
                            <p class="card-title-desc text-muted">{{ __('admin.field_current_image') }}</p>
                            <img src="{{ asset('storage/' . $item->image) }}" class="img-fluid">
                        </div>
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <h5 class="card-header align-items-center d-flex">
                        {{ __('admin.product_card_info') }}
                    </h5>

                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th>ID:</th>
                                <td class="text-muted">{{ $item->id }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.field_title') }}:</th>
                                <td class="text-muted">{{ $item->title }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.field_slug') }}:</th>
                                <td class="text-muted">{{ $item->slug }}</td>
                            </tr>
                            <tr>
                                <th>Цена:</th>
                                <td class="text-muted">
                                    @if (!is_null($item->price))
                                        {{ number_format((int) $item->price, 0, '.', ' ') }} ₽
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th>Скидка, %:</th>
                                <td class="text-muted">
                                    @if (!is_null($item->discount_percentage))
                                        {{ (int) $item->discount_percentage }}%
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th>Старая цена:</th>
                                <td class="text-muted">
                                    @if (!is_null($item->price_old))
                                        {{ number_format((int) $item->price_old, 0, '.', ' ') }} ₽
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.field_created') }}:</th>
                                <td class="text-muted">{{ $item->created_at }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.field_updated') }}:</th>
                                <td class="text-muted">{{ $item->updated_at }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ХАРАКТЕРИСТИКИ (sizes / steel_types / thicknesses / types / prices) --}}
            {{-- ОСТАВЛЕНЫ БЕЗ ИЗМЕНЕНИЙ — они не зависят от car_id --}}

        </div>

        {{-- БЛОК МАШИНЫ --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">{{ __('admin.aside_title_cars') }}</h5>

                    @if ($item->car)
                        <a href="{{ route('admin.cars.show', $item->car->slug) }}"
                            class="badge bg-primary-subtle text-primary fs-6">
                            {{ $item->car->title }}
                        </a>
                    @else
                        <div class="text-danger">
                            {{ __('admin.notification_no_entries') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    {{-- MODAL DELETE --}}
    <div class="modal fade" id="modalScrollable{{ $item->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('admin.question_delete') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="alert alert-warning">
                        {{ __('admin.notification_delete') }}
                    </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        {{ __('admin.btn_close') }}
                    </button>
                    <form action="{{ route('admin.products.destroy', $item->slug) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger">{{ __('admin.btn_confirm') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
