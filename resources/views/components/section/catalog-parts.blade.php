@props(['block' => null])

@php
    use App\Models\Product;
    use Illuminate\Support\Facades\Storage;

    $title = $block?->title;
    $productIds = collect($block?->items ?? [])
        ->map(fn($id) => (int) $id)
        ->filter(fn($id) => $id > 0)
        ->values()
        ->all();

    $products = collect();

    if ($block && !empty($productIds)) {
        $products = Product::query()
            ->whereIn('id', $productIds)
            ->get()
            ->sortBy(fn($product) => array_search($product->id, $productIds, true))
            ->values();
    }
@endphp

@if ($block && $products->count())
    <section class="catalog-parts-section section">
        <div class="container">
            @if (!empty($title))
                <h2 class="h2 catalog-parts-section__title">{{ $title }}</h2>
            @endif

            <div class="catalog-parts-grid">
                @foreach ($products as $p)
                    @php
                        $adminPath = !empty($p->image) && $p->image !== 'default' ? ltrim($p->image, '/') : null;
                        if ($adminPath && !Storage::disk('public')->exists($adminPath)) {
                            $adminPath = null;
                        }

                        $fallbackPath = null;
                        foreach (['webp', 'jpg', 'jpeg', 'png'] as $ext) {
                            $pp = "products_default/{$p->slug}.{$ext}";
                            if (Storage::disk('public')->exists($pp)) {
                                $fallbackPath = $pp;
                                break;
                            }
                        }

                        $finalPath = $adminPath ?: $fallbackPath;
                        $imageUrl = $finalPath ? asset('storage/' . $finalPath) : asset('images/no-image.jpg');

                        $price = $p->price ? number_format((int) $p->price, 0, '.', ' ') . ' ₽' : '';
                        $priceOld = $p->price_old ? number_format((int) $p->price_old, 0, '.', ' ') . ' ₽' : '';
                        $hasDiscount = !empty($priceOld);
                    @endphp

                    <article class="catalog-parts-card{{ $hasDiscount ? ' has-discount' : '' }}">
                        <div class="catalog-parts-card__image">
                            <img src="{{ $imageUrl }}" alt="{{ $p->title }}" loading="lazy">
                        </div>

                        <div class="catalog-parts-card__meta">
                            <h3 class="catalog-parts-card__title">{{ $p->title }}</h3>

                            @if (!empty($price) || !empty($priceOld))
                                <div class="catalog-parts-card__prices">
                                    @if (!empty($price))
                                        <div class="catalog-parts-card__price">{{ $price }}</div>
                                    @endif

                                    @if (!empty($priceOld))
                                        <div class="catalog-parts-card__price-old">{{ $priceOld }}</div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <button type="button" class="catalog-parts-card__btn" data-micromodal-trigger="modal-product"
                            data-product-id="{{ $p->id }}" data-product-title="{{ $p->title }}"
                            data-product-price="{{ $price }}" data-product-price-old="{{ $priceOld }}"
                            data-request-source="home" data-request-car="">
                            Заказать
                        </button>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endif
