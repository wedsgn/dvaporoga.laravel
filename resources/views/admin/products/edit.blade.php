@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">
                        {{ __('admin.edit_product_card_title') }} {{ $item->title }}
                    </h4>

                    <div class="flex-shrink-0 d-flex gap-2">
                        <a href="{{ route('admin.products.show', $item->slug) }}" class="btn btn-sm btn-light">Назад</a>
                        <a href="{{ route('admin.products.cars.index', $item) }}" class="btn btn-sm btn-outline-primary">
                            Картинки по машинам
                        </a>
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger alert-border-left alert-dismissible fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            {{-- SEARCH --}}
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.products.edit', $item->slug) }}" class="row g-2">
                        <div class="col-md-8">
                            <input type="text" name="q" value="{{ $q ?? '' }}" class="form-control"
                                placeholder="Поиск машины...">
                        </div>
                        <div class="col-md-4 d-flex gap-2">
                            <button class="btn btn-outline-primary" type="submit">Найти</button>
                            <a class="btn btn-outline-light"
                                href="{{ route('admin.products.edit', $item->slug) }}">Сброс</a>
                        </div>
                    </form>

                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-3">
                        <div class="text-muted" style="font-size:12px;">
                            Выбранные машины сохраняются даже при поиске/пагинации (локально в браузере).
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary-subtle text-primary fs-6">
                                Выбрано: <span id="selectedCount">0</span>
                            </span>

                            <button type="button" class="btn btn-sm btn-outline-secondary" id="btnSelectPage">
                                Выбрать всё на странице
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="btnUnselectPage">
                                Снять всё на странице
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger" id="btnClearAll">
                                Сбросить ВСЁ
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- CURRENT DEFAULT IMAGES --}}
        @if (!empty($item->image_mob))
            <div class="col-xxl-6">
                <div class="card">
                    <div class="card-body">
                        <p class="card-title-desc text-muted">
                            {{ __('admin.field_current_image_mob') }}
                            <span class="text-muted" style="font-size:12px;">(дефолт)</span>
                        </p>
                        <img src="{{ asset('storage/' . ltrim($item->image_mob, '/')) }}" class="img-fluid" alt="">
                    </div>
                </div>
            </div>
        @endif

        @if (!empty($item->image))
            <div class="col-xxl-6">
                <div class="card">
                    <div class="card-body">
                        <p class="card-title-desc text-muted">
                            {{ __('admin.field_current_image') }}
                            <span class="text-muted" style="font-size:12px;">(дефолт)</span>
                        </p>
                        <img src="{{ asset('storage/' . ltrim($item->image, '/')) }}" class="img-fluid" alt="">
                    </div>
                </div>
            </div>
        @endif

        {{-- UPDATE FORM --}}
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="live-preview">

                        <form id="productEditForm" action="{{ route('admin.products.update', $item->slug) }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('patch')

                            <input type="hidden" name="id" value="{{ $item->id }}">

                            {{-- сюда JS положит выбранные id --}}
                            <input type="hidden" name="car_ids_json" id="car_ids_json" value="[]">

                            <div class="row gy-4">

                                {{-- TITLE --}}
                                <div class="col-xxl-6 col-md-6">
                                    <div>
                                        <label for="titleInput" class="form-label">{{ __('admin.field_title') }} *</label>
                                        <input type="text" value="{{ old('title', $item->title) }}" class="form-control"
                                            id="titleInput" name="title"
                                            placeholder="{{ __('admin.placeholder_text') }}">
                                        <input type="hidden" name="old_title" value="{{ $item->title }}">
                                    </div>
                                </div>

                                {{-- PRICE / DISCOUNT / OLD PRICE --}}
                                <div class="col-xxl-4 col-md-4">
                                    <div>
                                        <label for="priceInput" class="form-label">Цена</label>
                                        <input type="number" min="0" step="1"
                                            value="{{ old('price', $item->price) }}" class="form-control" id="priceInput"
                                            name="price" placeholder="Например: 1850">
                                    </div>
                                </div>

                                <div class="col-xxl-4 col-md-4">
                                    <div>
                                        <label for="discountInput" class="form-label">Скидка, %</label>
                                        <input type="number" min="0" max="100" step="1"
                                            value="{{ old('discount_percentage', $item->discount_percentage) }}"
                                            class="form-control" id="discountInput" name="discount_percentage"
                                            placeholder="Например: 10">
                                    </div>
                                </div>

                                <div class="col-xxl-4 col-md-4">
                                    <div>
                                        <label for="priceOldInput" class="form-label">Старая цена</label>
                                        <input type="number" min="0" step="1"
                                            value="{{ old('price_old', $item->price_old) }}" class="form-control"
                                            id="priceOldInput" name="price_old" placeholder="Например: 2350">
                                    </div>
                                </div>

                                {{-- DEFAULT IMAGES --}}
                                <div class="col-xxl-6 col-md-6">
                                    <div>
                                        <label for="imageMobFile" class="form-label">
                                            {{ __('admin.field_image_mob') }}
                                            <span class="text-muted" style="font-size:12px;">(дефолт)</span>
                                        </label>
                                        <input class="form-control" type="file" id="imageMobFile" name="image_mob">
                                    </div>
                                </div>

                                <div class="col-xxl-6 col-md-6">
                                    <div>
                                        <label for="imageFile" class="form-label">
                                            {{ __('admin.field_image') }}
                                            <span class="text-muted" style="font-size:12px;">(дефолт)</span>
                                        </label>
                                        <input class="form-control" type="file" id="imageFile" name="image">
                                    </div>
                                </div>

                                {{-- DESCRIPTION --}}
                                <div class="col-12">
                                    <label class="form-label"
                                        for="basic-default-message">{{ __('admin.field_description') }}</label>
                                    <textarea id="basic-default-message" class="form-control" name="description"
                                        placeholder="{{ __('admin.placeholder_text') }}" style="height: 234px;">{{ old('description', $item->description) }}</textarea>
                                </div>

                                {{-- CARS --}}
                                <div class="col-12">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                        <label class="form-label mb-0">Связанные машины</label>
                                        <div class="text-muted" style="font-size:12px;">
                                            Сейчас связано в БД: {{ (int) ($item->cars_count ?? 0) }}
                                        </div>
                                    </div>

                                    <div class="border rounded p-2 mt-2" style="max-height: 420px; overflow:auto;">
                                        @forelse($cars as $car)
                                            <div class="form-check">
                                                <input class="form-check-input car-check" type="checkbox"
                                                    data-car-id="{{ (int) $car->id }}" id="car_{{ $car->id }}">
                                                <label class="form-check-label" for="car_{{ $car->id }}">
                                                    {{ $car->title }}
                                                </label>
                                            </div>
                                        @empty
                                            <div class="text-muted">Машин не найдено.</div>
                                        @endforelse
                                    </div>

                                    <div class="mt-2">
                                        {{ $cars->links() }}
                                    </div>
                                </div>

                            </div>

                            <button type="submit" class="btn btn-soft-success waves-effect waves-light mt-4 float-end">
                                {{ __('admin.btn_save') }}
                            </button>

                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        (function() {
            const productId = @json((int) $item->id);

            const STORAGE_KEY = 'product_car_ids:' + productId;

            const serverSelected = @json(array_map('intval', $selectedCarIds ?? []));
            const serverSig = @json($serverSig ?? '');
            let selected = new Set();

            function loadSelected() {
                if (!serverSig) {
                    try {
                        localStorage.removeItem(STORAGE_KEY);
                    } catch (_) {}
                    return new Set(serverSelected);
                }

                try {
                    const raw = localStorage.getItem(STORAGE_KEY);
                    if (!raw) return new Set(serverSelected);

                    const data = JSON.parse(raw);

                    if (data && typeof data === 'object' && Array.isArray(data.ids)) {
                        if (data.sig === serverSig) {
                            return new Set(
                                data.ids
                                .map(n => parseInt(n, 10))
                                .filter(n => Number.isInteger(n) && n > 0)
                            );
                        }

                        localStorage.removeItem(STORAGE_KEY);
                        return new Set(serverSelected);
                    }

                    if (Array.isArray(data)) {
                        localStorage.removeItem(STORAGE_KEY);
                        return new Set(serverSelected);
                    }

                    localStorage.removeItem(STORAGE_KEY);
                    return new Set(serverSelected);

                } catch (e) {
                    try {
                        localStorage.removeItem(STORAGE_KEY);
                    } catch (_) {}
                    return new Set(serverSelected);
                }
            }


            function saveSelected() {
                const arr = Array.from(selected.values()).sort((a, b) => a - b);
                localStorage.setItem(STORAGE_KEY, JSON.stringify({
                    sig: serverSig,
                    ids: arr
                }));
            }

            function updateUI() {
                document.querySelectorAll('.car-check').forEach(cb => {
                    const id = parseInt(cb.dataset.carId, 10);
                    cb.checked = selected.has(id);
                });

                const el = document.getElementById('selectedCount');
                if (el) el.textContent = String(selected.size);

                const hidden = document.getElementById('car_ids_json');
                if (hidden) hidden.value = JSON.stringify(Array.from(selected.values()));
            }

            function init() {
                selected = loadSelected();
                saveSelected();
                updateUI();
                document.addEventListener('change', (e) => {
                    const cb = e.target.closest('.car-check');
                    if (!cb) return;
                    const id = parseInt(cb.dataset.carId, 10);
                    if (!id) return;

                    if (cb.checked) selected.add(id);
                    else selected.delete(id);

                    saveSelected();
                    updateUI();
                });

                const btnSelectPage = document.getElementById('btnSelectPage');
                if (btnSelectPage) {
                    btnSelectPage.addEventListener('click', () => {
                        document.querySelectorAll('.car-check').forEach(cb => {
                            const id = parseInt(cb.dataset.carId, 10);
                            if (id) selected.add(id);
                        });
                        saveSelected();
                        updateUI();
                    });
                }

                const btnUnselectPage = document.getElementById('btnUnselectPage');
                if (btnUnselectPage) {
                    btnUnselectPage.addEventListener('click', () => {
                        document.querySelectorAll('.car-check').forEach(cb => {
                            const id = parseInt(cb.dataset.carId, 10);
                            if (id) selected.delete(id);
                        });
                        saveSelected();
                        updateUI();
                    });
                }

                const btnClearAll = document.getElementById('btnClearAll');
                if (btnClearAll) {
                    btnClearAll.addEventListener('click', () => {
                        if (!confirm('Сбросить ВСЕ выбранные машины для этого товара?')) return;
                        selected = new Set();
                        saveSelected();
                        updateUI();
                    });
                }

                const form = document.getElementById('productEditForm');
                if (form) {
                    form.addEventListener('submit', () => {
                        const hidden = document.getElementById('car_ids_json');
                        if (hidden) hidden.value = JSON.stringify(Array.from(selected.values()));
                    });
                }
            }

            document.addEventListener('DOMContentLoaded', init);
        })();
    </script>

@endsection
