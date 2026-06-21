@extends('layouts.admin')

@section('content')
    @include('admin.partials.related-entities-styles')

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
                                {{ __('admin.car_card_title') }}: {{ $item->title }}
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
                                        <a type="button" class="dropdown-item" href="{{ route('admin.cars.index') }}">
                                            <i class="ri-arrow-left-line align-bottom me-2 text-muted"></i>
                                            {{ __('admin.btn_back') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.cars.edit', $item->slug) }}"
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
                                    @if ($item->image === 'default')
                                        <img src="{{ asset('images/cars/merc.png') }}" class="img-fluid"
                                            alt="{{ $item->title }}">
                                    @else
                                        <img src="{{ asset('storage/' . ltrim($item->image, '/')) }}" class="img-fluid"
                                            alt="{{ $item->title }}">
                                    @endif
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
                            <h5 class="card-title mb-1">Связанные детали</h5>
                            <p class="admin-related-topbar__note mb-0">
                                Выберите новые картинки у нужных карточек. До сохранения сразу покажем предпросмотр,
                                чтобы было удобно менять много изображений подряд.
                            </p>
                        </div>
                        <span class="badge bg-info-subtle text-info fs-12">
                            {{ $relatedProducts->count() }} связанных деталей
                        </span>
                    </div>

                    @if ($relatedProducts->isNotEmpty())
                        <form method="POST" action="{{ route('admin.cars.products.images.update', $item->slug) }}"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="admin-related-grid">
                                @foreach ($relatedProducts as $related)
                                    @php
                                        $product = $related['product'];
                                        $image = $related['image'];
                                    @endphp

                                    <article class="admin-related-card" data-product-card>
                                        <div class="admin-related-card__image">
                                            @if ($image['url'])
                                                <img src="{{ $image['url'] }}" alt="{{ $product->title }}">
                                            @else
                                                <div class="admin-related-card__placeholder">
                                                    Нет изображения для предпросмотра
                                                </div>
                                            @endif
                                        </div>

                                        <div class="admin-related-card__body">
                                            <div>
                                                <h6 class="admin-related-card__title">{{ $product->title }}</h6>
                                                <p class="admin-related-card__meta">
                                                    slug: {{ $product->slug }} | id: {{ $product->id }}
                                                </p>
                                            </div>

                                            <div class="admin-related-badges">
                                                <span
                                                    class="badge bg-{{ $image['source_badge'] }}-subtle text-{{ $image['source_badge'] }} js-image-source-badge">
                                                    {{ $image['source_label'] }}
                                                </span>

                                                @if ($related['has_custom_image'])
                                                    <span class="badge bg-success-subtle text-success">Есть своя картинка</span>
                                                @endif
                                            </div>

                                            <p class="admin-related-help js-image-source-info">
                                                Сейчас показана актуальная картинка, которая используется для этой детали.
                                            </p>

                                            <div class="admin-related-info-list">
                                                @foreach ($image['current_source_details'] as $detail)
                                                    <div class="admin-related-info-item">
                                                        <i class="{{ $detail['icon'] }}"></i>
                                                        <span>{{ $detail['label'] }}: {{ $detail['value'] }}</span>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <div>
                                                <label class="admin-related-toolbar__label mb-2">
                                                    Новая картинка для этой детали
                                                </label>
                                                <input type="file" name="product_images[{{ $product->id }}]"
                                                    class="form-control js-related-image-input" accept=".jpg,.jpeg,.png,.webp">
                                                <div class="form-text mt-2 js-related-file-name">
                                                    Файл еще не выбран
                                                </div>
                                                <div class="admin-related-pending-note d-none js-related-upload-state">
                                                    Новый файл будет сохранён после нажатия кнопки ниже.
                                                </div>
                                            </div>

                                            <div class="admin-related-actions">
                                                <a href="{{ route('admin.products.show', $product->slug) }}"
                                                    class="btn btn-sm btn-light">
                                                    Открыть деталь
                                                </a>
                                                <a href="{{ route('admin.products.edit', $product->slug) }}"
                                                    class="btn btn-sm btn-secondary">
                                                    Редактировать
                                                </a>
                                                <a href="{{ route('admin.products.cars.index', $product) }}"
                                                    class="btn btn-sm btn-soft-info">
                                                    Все авто детали
                                                </a>

                                                @if ($related['has_custom_image'])
                                                    <button type="submit" form="delete-image-{{ $product->id }}"
                                                        class="btn btn-sm btn-soft-danger"
                                                        onclick="return confirm('Удалить индивидуальную картинку только у этой детали?');">
                                                        Сбросить custom
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>

                            <div class="mt-4 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line align-bottom me-1"></i>
                                    Сохранить все изменения
                                </button>
                            </div>
                        </form>

                        @foreach ($relatedProducts as $related)
                            @if ($related['has_custom_image'])
                                <form id="delete-image-{{ $related['product']->id }}" method="POST"
                                    action="{{ route('admin.products.cars.image.delete', [$related['product'], $item]) }}"
                                    class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endif
                        @endforeach
                    @else
                        <div class="admin-related-empty">
                            У этого автомобиля пока нет связанных деталей. Когда связь появится, здесь сразу будут карточки
                            и быстрые действия по картинкам.
                        </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-header align-items-center d-flex">{{ __('admin.car_card_info') }}</h5>
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
                                    <th class="ps-0" scope="row">{{ __('admin.field_generation') }}:</th>
                                    <td class="text-muted">{{ $item->generation }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">{{ __('admin.field_years') }}:</th>
                                    <td class="text-muted">{{ $item->years }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">{{ __('admin.field_body') }}:</th>
                                    <td class="text-muted">{{ $item->body }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">{{ __('admin.field_artikul') }}:</th>
                                    <td class="text-muted">{{ $item->artikul }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">{{ __('admin.car_make_card_title') }}:</th>
                                    <td class="text-muted">{{ $item->car_model?->car_make?->title ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">{{ __('admin.car_model_card_title') }}:</th>
                                    <td class="text-muted">{{ $item->car_model?->title ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Связанных деталей:</th>
                                    <td class="text-muted">{{ $relatedProducts->count() }}</td>
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
                                    <form action="{{ route('admin.cars.destroy', $item->slug) }}" method="POST">
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInputs = document.querySelectorAll('.js-related-image-input');

            fileInputs.forEach((input) => {
                input.addEventListener('change', function() {
                    const card = input.closest('[data-product-card]');
                    const file = input.files && input.files[0] ? input.files[0] : null;

                    if (!card || !file) {
                        return;
                    }

                    const imageWrap = card.querySelector('.admin-related-card__image');
                    const placeholder = card.querySelector('.admin-related-card__placeholder');
                    const fileName = card.querySelector('.js-related-file-name');
                    const uploadState = card.querySelector('.js-related-upload-state');
                    const sourceBadge = card.querySelector('.js-image-source-badge');
                    const sourceInfo = card.querySelector('.js-image-source-info');

                    let previewImage = imageWrap.querySelector('img');

                    if (!previewImage) {
                        previewImage = document.createElement('img');
                        imageWrap.appendChild(previewImage);
                    }

                    if (previewImage.dataset.previewObjectUrl) {
                        URL.revokeObjectURL(previewImage.dataset.previewObjectUrl);
                    }

                    const objectUrl = URL.createObjectURL(file);

                    previewImage.src = objectUrl;
                    previewImage.alt = file.name;
                    previewImage.dataset.previewObjectUrl = objectUrl;

                    if (placeholder) {
                        placeholder.classList.add('d-none');
                    }

                    if (fileName) {
                        fileName.textContent = `Выбран файл: ${file.name}`;
                    }

                    if (uploadState) {
                        uploadState.classList.remove('d-none');
                    }

                    if (sourceBadge) {
                        sourceBadge.className = 'badge bg-warning-subtle text-warning js-image-source-badge';
                        sourceBadge.textContent = 'Новый файл выбран';
                    }

                    if (sourceInfo) {
                        sourceInfo.textContent = 'Это локальный предпросмотр. На сервер файл отправится только после сохранения.';
                    }

                    card.classList.add('admin-related-card--dirty');
                });
            });
        });
    </script>
@endpush
