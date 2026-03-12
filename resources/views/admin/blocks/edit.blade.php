@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('admin.blocks.update', $block->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <div class="flex-grow-1">
                                <h3 class="card-header align-items-center d-flex">
                                    Редактирование блока: {{ $block->name }}
                                </h3>
                            </div>
                            <div class="flex-shrink-0">
                                <a href="{{ route('admin.blocks.index') }}" class="btn btn-light">
                                    Назад
                                </a>
                            </div>
                        </div>

                        @if (session('status') === 'block-updated')
                            <div class="alert alert-success">
                                Блок успешно сохранён.
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label">Заголовок блока</label>
                            <input
                                type="text"
                                name="title"
                                value="{{ old('title', $block->title) }}"
                                class="form-control @error('title') is-invalid @enderror"
                            >
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($block->key === 'repair_examples')

                            <div class="mb-4">
                                <label class="form-label">Текущие карточки</label>

                                @if(!empty($block->items))
                                    <div class="row g-3">
                                        @foreach($block->items as $index => $item)
                                            @php
                                                $before = $item['before'] ?? null;
                                                $after = $item['after'] ?? null;
                                            @endphp

                                            <div class="col-md-6">
                                                <div class="card h-100 border">
                                                    <div class="card-body">
                                                        <div class="form-check mb-3">
                                                            <input
                                                                class="form-check-input"
                                                                type="checkbox"
                                                                name="keep_items[]"
                                                                value="{{ $index }}"
                                                                id="keep_item_{{ $index }}"
                                                                checked
                                                            >
                                                            <label class="form-check-label" for="keep_item_{{ $index }}">
                                                                Оставить карточку
                                                            </label>
                                                        </div>

                                                        <div class="row g-2">
                                                            <div class="col-6">
                                                                <div class="small text-muted mb-1">До</div>
                                                                @if($before)
                                                                    <img
                                                                        src="{{ str_starts_with($before, 'uploads/') ? asset('storage/' . $before) : asset($before) }}"
                                                                        alt=""
                                                                        class="img-fluid rounded border"
                                                                        style="width:100%; height:180px; object-fit:cover;"
                                                                    >
                                                                @endif
                                                            </div>

                                                            <div class="col-6">
                                                                <div class="small text-muted mb-1">После</div>
                                                                @if($after)
                                                                    <img
                                                                        src="{{ str_starts_with($after, 'uploads/') ? asset('storage/' . $after) : asset($after) }}"
                                                                        alt=""
                                                                        class="img-fluid rounded border"
                                                                        style="width:100%; height:180px; object-fit:cover;"
                                                                    >
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted mb-0">Карточек пока нет.</p>
                                @endif
                            </div>

                            <hr class="my-4">

                            <div class="mb-3 d-flex align-items-center justify-content-between">
                                <label class="form-label mb-0">Новые карточки "До / После"</label>
                                <button type="button" class="btn btn-primary btn-sm" id="add-repair-card">
                                    Добавить карточку
                                </button>
                            </div>

                            <div id="repair-cards-container"></div>

                            <small class="text-muted d-block mt-2">
                                Карточка добавится только если выбраны оба файла: "До" и "После".
                            </small>

                        @elseif($block->key === 'catalog_default_parts')

                            @php
                                $selectedProducts = collect(old('selected_products', $block->items ?? []))
                                    ->map(fn($id) => (int) $id)
                                    ->all();
                            @endphp

                            <div class="mb-4">
                                <label class="form-label">Товары для блока</label>

                                @if(isset($products) && $products->count())
                                    <div class="row g-3">
                                        @foreach($products as $product)
                                            @php
                                                $imagePath = null;

                                                if (!empty($product->image) && $product->image !== 'default') {
                                                    $imagePath = asset('storage/' . ltrim($product->image, '/'));
                                                } else {
                                                    foreach (['webp', 'jpg', 'jpeg', 'png'] as $ext) {
                                                        $fallback = "products_default/{$product->slug}.{$ext}";
                                                        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($fallback)) {
                                                            $imagePath = asset('storage/' . $fallback);
                                                            break;
                                                        }
                                                    }
                                                }

                                                $imagePath = $imagePath ?: asset('images/no-image.jpg');
                                            @endphp

                                            <div class="col-md-4 col-xl-3">
                                                <label class="card h-100 border" style="cursor:pointer;">
                                                    <div class="card-body">
                                                        <div class="form-check mb-3">
                                                            <input
                                                                class="form-check-input"
                                                                type="checkbox"
                                                                name="selected_products[]"
                                                                value="{{ $product->id }}"
                                                                {{ in_array((int) $product->id, $selectedProducts, true) ? 'checked' : '' }}
                                                            >
                                                            <span class="form-check-label">Показать в блоке</span>
                                                        </div>

                                                        <div class="mb-3">
                                                            <img
                                                                src="{{ $imagePath }}"
                                                                alt="{{ $product->title }}"
                                                                class="img-fluid rounded border"
                                                                style="width:100%; height:180px; object-fit:contain; background:#f8f9fa;"
                                                            >
                                                        </div>

                                                        <div class="fw-semibold mb-2">{{ $product->title }}</div>

                                                        <div class="small text-muted mb-1">
                                                            Цена:
                                                            <strong class="text-dark">
                                                                {{ $product->price ? number_format((int) $product->price, 0, '.', ' ') . ' ₽' : '—' }}
                                                            </strong>
                                                        </div>

                                                        @if($product->price_old)
                                                            <div class="small text-muted">
                                                                Старая цена:
                                                                <strong class="text-dark">
                                                                    {{ number_format((int) $product->price_old, 0, '.', ' ') . ' ₽' }}
                                                                </strong>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted mb-0">Товаров пока нет.</p>
                                @endif
                            </div>

                        @else

                            <div class="mb-4">
                                <label class="form-label">Текущие фото</label>

                                @if(!empty($block->images))
                                    <div class="row g-3">
                                        @foreach($block->images as $image)
                                            <div class="col-md-3">
                                                <div class="card h-100">
                                                    <img
                                                        src="{{ str_starts_with($image, 'uploads/') ? asset('storage/' . $image) : asset($image) }}"
                                                        alt=""
                                                        class="card-img-top"
                                                        style="height: 180px; object-fit: cover;"
                                                    >
                                                    <div class="card-body">
                                                        <div class="form-check">
                                                            <input
                                                                class="form-check-input"
                                                                type="checkbox"
                                                                name="keep_images[]"
                                                                value="{{ $image }}"
                                                                id="keep_{{ $loop->index }}"
                                                                checked
                                                            >
                                                            <label class="form-check-label" for="keep_{{ $loop->index }}">
                                                                Оставить
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted mb-0">Фотографий пока нет.</p>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Добавить новые фото</label>
                                <input
                                    type="file"
                                    name="new_images[]"
                                    multiple
                                    accept="image/*"
                                    class="form-control @error('new_images.*') is-invalid @enderror"
                                >
                                @error('new_images.*')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    Можно выбрать сразу несколько изображений.
                                </small>
                            </div>

                        @endif

                        <div class="mt-4">
                            <button type="submit" class="btn btn-success">
                                Сохранить
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($block->key === 'repair_examples')
        <template id="repair-card-template">
            <div class="card border mb-3 repair-card-item">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="mb-0 repair-card-title">Новая карточка</h5>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-repair-card">
                            Удалить
                        </button>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Фото "До"</label>
                            <input type="file" class="form-control repair-before-input" accept="image/*">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Фото "После"</label>
                            <input type="file" class="form-control repair-after-input" accept="image/*">
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const addBtn = document.getElementById('add-repair-card');
                const container = document.getElementById('repair-cards-container');
                const template = document.getElementById('repair-card-template');

                if (!addBtn || !container || !template) return;

                function reindexCards() {
                    const cards = container.querySelectorAll('.repair-card-item');

                    cards.forEach((card, i) => {
                        const title = card.querySelector('.repair-card-title');
                        const beforeInput = card.querySelector('.repair-before-input');
                        const afterInput = card.querySelector('.repair-after-input');

                        if (title) {
                            title.textContent = 'Новая карточка ' + (i + 1);
                        }

                        if (beforeInput) {
                            beforeInput.name = `new_items[${i}][before]`;
                        }

                        if (afterInput) {
                            afterInput.name = `new_items[${i}][after]`;
                        }
                    });
                }

                addBtn.addEventListener('click', function () {
                    const fragment = template.content.cloneNode(true);
                    container.appendChild(fragment);
                    reindexCards();
                });

                container.addEventListener('click', function (e) {
                    const removeBtn = e.target.closest('.remove-repair-card');

                    if (!removeBtn) return;

                    const card = removeBtn.closest('.repair-card-item');
                    if (card) {
                        card.remove();
                        reindexCards();
                    }
                });

                addBtn.click();
            });
        </script>
    @endif
@endsection
