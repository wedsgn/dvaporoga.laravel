@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h4 class="card-title mb-0 flex-grow-1">
                        Товар: {{ $product->title }} — машины и картинки
                    </h4>

                    <a href="{{ route('admin.products.show', $product->slug) }}" class="btn btn-sm btn-light">
                        Назад к товару
                    </a>
                </div>

                <div class="card-body">

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

                    <form method="GET" action="{{ route('admin.products.cars.index', $product) }}" class="row g-2 mb-3">
                        <div class="col-md-8">
                            <input type="text" name="q" value="{{ $q }}" class="form-control"
                                placeholder="Поиск по названию машины...">
                        </div>
                        <div class="col-md-4 d-flex gap-2">
                            <button class="btn btn-primary" type="submit">Найти</button>
                            <a class="btn btn-light" href="{{ route('admin.products.cars.index', $product) }}">Сброс</a>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th style="min-width:320px;">Машина</th>
                                    <th style="min-width:160px;">Картинка сейчас</th>
                                    <th style="min-width:420px;">Заменить (только для этой машины)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cars as $car)
                                    @php
                                        $pivotImg = $pivotImages[$car->id] ?? null;
                                        $img = $pivotImg ?: $product->image;
                                        $src = $img ? asset('storage/' . ltrim($img, '/')) : null;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $car->title }}</div>
                                            <div class="text-muted" style="font-size:12px;">car_id: {{ $car->id }}
                                            </div>
                                        </td>

                                        @php
                                            $pivotImg = $pivotImages[$car->id] ?? null;

                                            // 1) admin image (products.image)
                                            $adminPath = !empty($product->image) ? ltrim($product->image, '/') : null;

                                            // 2) pivot image (car_product.image)
                                            $pivotPath = !empty($pivotImg) ? ltrim($pivotImg, '/') : null;

                                            // 3) fallback file by slug in products_default/
                                            $fallbackPath = null;
                                            foreach (['webp', 'jpg', 'jpeg', 'png'] as $ext) {
                                                $p = "products_default/{$product->slug}.{$ext}";
                                                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($p)) {
                                                    $fallbackPath = $p;
                                                    break;
                                                }
                                            }

                                            // PRIORITY: admin > pivot > fallback
                                            $finalPath = $adminPath ?: ($pivotPath ?: $fallbackPath);
                                            $finalSrc = $finalPath ? asset('storage/' . $finalPath) : null;

                                            $source = $adminPath
                                                ? 'админка (товар)'
                                                : ($pivotPath
                                                    ? 'таблица (pivot)'
                                                    : ($fallbackPath
                                                        ? 'дефолт по slug'
                                                        : 'нет'));
                                        @endphp

                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                @if ($finalSrc)
                                                    <img src="{{ $finalSrc }}" alt=""
                                                        style="max-width:140px;height:auto;border-radius:8px;">
                                                @else
                                                    <span class="text-danger">Нет изображения</span>
                                                @endif

                                                <div style="font-size:12px;">
                                                    <div class="text-muted">Источник:</div>
                                                    <div class="{{ $source === 'нет' ? 'text-danger' : 'text-success' }}">
                                                        {{ $source }}
                                                    </div>

                                                    <div class="text-muted mt-1" style="font-size:11px;">
                                                        admin: {{ $adminPath ?? '—' }}<br>
                                                        pivot: {{ $pivotPath ?? '—' }}<br>
                                                        fallback: {{ $fallbackPath ?? '—' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <form method="POST"
                                                action="{{ route('admin.products.cars.image', [$product, $car]) }}"
                                                enctype="multipart/form-data" class="d-flex gap-2 align-items-start">
                                                @csrf
                                                <input type="file" name="image" class="form-control">
                                                <button class="btn btn-soft-success" type="submit">Заменить</button>
                                            </form>
                                            <div class="text-muted mt-1" style="font-size:12px;">JPG/PNG/WebP до 5MB</div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-muted">Нет машин с этим товаром.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $cars->links() }}

                </div>
            </div>

        </div>
    </div>
@endsection
