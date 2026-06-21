@extends('layouts.admin')

@section('content')
    @include('admin.partials.related-entities-styles')

    @php
        $carsByGeneration = $item->cars->groupBy(function ($car) {
            return filled($car->generation) ? $car->generation : 'Без поколения';
        });
    @endphp

    <div class="row">
        <div class="col-lg-12">
            @if (session('success'))
                <div class="alert alert-success alert-border-left alert-dismissible fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-border-left alert-dismissible fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="flex-grow-1">
                            <h3 class="card-header align-items-center d-flex mb-0">
                                {{ __('admin.car_model_card_title') }}: {{ $item->title }}
                            </h3>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="dropdown">
                                <a href="#" role="button" id="dropdownMenuLink1" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="ri-more-2-fill fs-14"></i>
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink1">
                                    <li>
                                        <a type="button" class="dropdown-item"
                                            href="{{ route('admin.car_models.index') }}">
                                            <i class="ri-arrow-left-line align-bottom me-2 text-muted"></i>
                                            {{ __('admin.btn_back') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.car_models.edit', $item->slug) }}"
                                            class="dropdown-item edit-item-btn">
                                            <i class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                                            {{ __('admin.btn_edit') }}
                                        </a>
                                    </li>
                                    <li>
                                        <button type="submit" class="dropdown-item text-danger" data-bs-toggle="modal"
                                            data-bs-target="#modalScrollable{{ $item->slug }}">
                                            <i class="bx bx-trash me-1 text-danger" role="button"></i>
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

            <div class="row">
                @if (!empty($item->image))
                    <div class="col-xxl-6">
                        <div class="card">
                            <div class="card-body">
                                <p class="card-title-desc text-muted">{{ __('admin.field_current_image') }}</p>
                                <div class="live-preview">
                                    <img src="{{ asset('storage/' . ltrim($item->image, '/')) }}" class="img-fluid"
                                        alt="{{ $item->title }}">
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (!empty($item->image_mob))
                    <div class="col-xxl-6">
                        <div class="card">
                            <div class="card-body">
                                <p class="card-title-desc text-muted">{{ __('admin.field_current_image_mob') }}</p>
                                <div class="live-preview">
                                    <img src="{{ asset('storage/' . ltrim($item->image_mob, '/')) }}" class="img-fluid"
                                        alt="{{ $item->title }} mobile">
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="card admin-related-section">
                <div class="card-body">
                    <div class="admin-related-topbar">
                        <div>
                            <h5 class="card-title mb-1">Связанные автомобили</h5>
                            <p class="admin-related-topbar__note mb-0">
                                Автомобили этой модели сгруппированы по поколениям, чтобы было удобно быстро найти нужную
                                запись и перейти к ее редактированию.
                            </p>
                        </div>
                        <span class="badge bg-info-subtle text-info fs-12">
                            {{ $item->cars->count() }} автомобилей
                        </span>
                    </div>

                    @if ($item->cars->isNotEmpty())
                        @foreach ($carsByGeneration as $generation => $cars)
                            <div class="admin-generation-group">
                                <div class="admin-generation-group__head">
                                    <h6 class="admin-generation-group__title">{{ $generation }}</h6>
                                    <span class="badge bg-primary-subtle text-primary">
                                        {{ $cars->count() }} записей
                                    </span>
                                </div>

                                <div class="admin-related-grid">
                                    @foreach ($cars as $car)
                                        @php
                                            $previewUrl = null;
                                            if (filled($car->image) && $car->image !== 'default') {
                                                $previewUrl = asset('storage/' . ltrim($car->image, '/'));
                                            } elseif ($car->image === 'default') {
                                                $previewUrl = asset('images/cars/merc.png');
                                            }
                                        @endphp

                                        <article class="admin-related-card">
                                            <div class="admin-related-card__image">
                                                @if ($previewUrl)
                                                    <img src="{{ $previewUrl }}" alt="{{ $car->title }}">
                                                @else
                                                    <div class="admin-related-card__placeholder">
                                                        Нет превью автомобиля
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="admin-related-card__body">
                                                <div>
                                                    <h6 class="admin-related-card__title">{{ $car->title }}</h6>
                                                    <p class="admin-related-card__meta">slug: {{ $car->slug }}</p>
                                                </div>

                                                <div class="admin-related-badges">
                                                    @if ($car->years)
                                                        <span class="badge bg-info-subtle text-info">{{ $car->years }}</span>
                                                    @endif
                                                    @if ($car->body)
                                                        <span class="badge bg-secondary-subtle text-secondary">
                                                            {{ $car->body }}
                                                        </span>
                                                    @endif
                                                    <span class="badge bg-primary-subtle text-primary">
                                                        Деталей: {{ $car->products_count }}
                                                    </span>
                                                </div>

                                                <div class="admin-related-info-list">
                                                    <div class="admin-related-info-item">
                                                        <i class="ri-file-list-3-line"></i>
                                                        <span>ID авто: {{ $car->id }}</span>
                                                    </div>
                                                    @if ($car->artikul)
                                                        <div class="admin-related-info-item">
                                                            <i class="ri-price-tag-3-line"></i>
                                                            <span>Артикул: {{ $car->artikul }}</span>
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="admin-related-actions">
                                                    <a href="{{ route('admin.cars.show', $car->slug) }}"
                                                        class="btn btn-sm btn-light">
                                                        Открыть авто
                                                    </a>
                                                    <a href="{{ route('admin.cars.edit', $car->slug) }}"
                                                        class="btn btn-sm btn-secondary">
                                                        Редактировать
                                                    </a>
                                                </div>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="admin-related-empty">
                            У этой модели пока нет связанных автомобилей.
                        </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-header align-items-center d-flex">{{ __('admin.car_model_card_info') }}</h5>
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
                                    <th class="ps-0" scope="row">{{ __('admin.car_make_card_title') }}:</th>
                                    <td class="text-muted">{{ $item->car_make?->title ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Связанных автомобилей:</th>
                                    <td class="text-muted">{{ $item->cars->count() }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Поколений:</th>
                                    <td class="text-muted">{{ $carsByGeneration->count() }}</td>
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

                    <div class="modal fade" id="modalScrollable{{ $item->slug }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalScrollableTitle">{{ __('admin.question_delete') }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 alert alert-warning text-wrap">
                                        {{ __('admin.notification_delete') }}
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        {{ __('admin.btn_close') }}
                                    </button>
                                    <form action="{{ route('admin.car_models.destroy', $item->slug) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            {{ __('admin.btn_confirm') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
                </div>
            </div>
        </div>
    </div>
@endsection
