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
    <section class="car-single-parts-section section">
        <div class="container">
            @if (!empty($title))
                <h2 class="h2">{{ $title }}</h2>
            @endif

            <div class="car-single-parts">
                @foreach ($products as $p)
                    @php
                        $adminPath = !empty($p->image) && $p->image !== 'default' ? ltrim($p->image, '/') : null;

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
                    @endphp

                    <x-car-single-part :id="$p->id" :image="$imageUrl" :discount_percentage="$p->discount_percentage ? '-' . $p->discount_percentage . ' %' : ''" :title="$p->title"
                        :description="$p->description ?? ''" :price="$p->price ? number_format((int) $p->price, 0, '.', ' ') . ' ₽' : ''" :priceOld="$p->price_old ? number_format((int) $p->price_old, 0, '.', ' ') . ' ₽' : ''" :link="$p->link ?? ''" :alt="$p->title"
                        request-source="home" request-car="" />
                @endforeach
            </div>
        </div>
    </section>
@endif
