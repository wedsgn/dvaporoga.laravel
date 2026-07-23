<?php

namespace App\Services\Feeds;

use App\Models\Car;
use App\Models\CarMake;
use App\Models\CarModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use XMLWriter;

class YandexFeedBuilder
{
    protected string $filePath;

    protected string $catalogFallbackPath = 'images/hero-car.webp';

    public function __construct()
    {
        $this->filePath = storage_path('app/feeds/yandex.yml');
    }

    public function build(): void
    {
        if (!is_dir(dirname($this->filePath))) {
            mkdir(dirname($this->filePath), 0755, true);
        }

        $xml = new XMLWriter();
        $xml->openURI($this->filePath);
        $xml->startDocument('1.0', 'UTF-8');
        $xml->setIndent(true);

        $xml->startElement('yml_catalog');
        $xml->writeAttribute('date', now()->format('Y-m-d H:i'));

        $xml->startElement('shop');
        $xml->writeElement('name', 'Арки и пороги');
        $xml->writeElement('company', config('app.name'));
        $xml->writeElement('url', $this->siteUrl());

        $this->writeCurrencies($xml);
        $this->writeCategories($xml);
        $this->writeOffers($xml);
        $this->writeCollections($xml);

        $xml->endElement(); // shop
        $xml->endElement(); // yml_catalog

        $xml->endDocument();
        $xml->flush();
    }

    protected function writeCurrencies(XMLWriter $xml): void
    {
        $xml->startElement('currencies');

        $xml->startElement('currency');
        $xml->writeAttribute('id', 'RUB');
        $xml->writeAttribute('rate', '1');
        $xml->endElement();

        $xml->endElement();
    }

    protected function writeCategories(XMLWriter $xml): void
    {
        $xml->startElement('categories');

        $this->writeCategory($xml, 1, 'Автодетали');
        $this->writeCategory($xml, 10, 'Арки и пороги', 1);
        $this->writeCategory($xml, 11, 'Пороги', 10);

        $xml->endElement();
    }

    protected function writeCategory(XMLWriter $xml, int $id, string $title, ?int $parentId = null): void
    {
        $xml->startElement('category');
        $xml->writeAttribute('id', (string) $id);

        if ($parentId !== null) {
            $xml->writeAttribute('parentId', (string) $parentId);
        }

        $xml->text($title);
        $xml->endElement();
    }

    protected function writeOffers(XMLWriter $xml): void
    {
        $xml->startElement('offers');

        $this->carFeedQuery()->chunk(50, function ($cars) use ($xml) {
            foreach ($cars as $car) {
                if (!$car->car_model || !$car->car_model->car_make) {
                    continue;
                }

                /** @var Collection $products */
                $products = $car->products
                    ->filter(fn($product) => (int) ($product->price ?? 0) > 0)
                    ->values();

                foreach ($products as $product) {
                    $this->writeProductOffer($xml, $car, $product);
                }
            }
        });

        $xml->endElement();
    }

    protected function writeCollections(XMLWriter $xml): void
    {
        $xml->startElement('collections');

        $this->writeMakeCollections($xml);
        $this->writeModelCollections($xml);
        $this->writeCarCollections($xml);

        $xml->endElement();
    }

    protected function writeMakeCollections(XMLWriter $xml): void
    {
        CarMake::query()
            ->visible()
            ->whereHas('car_models.cars.products', fn($query) => $query->where('price', '>', 0))
            ->orderBy('id')
            ->chunk(50, function ($makes) use ($xml) {
                $imageMap = $this->firstValidCarImagesByMake($makes->pluck('id')->all());

                foreach ($makes as $make) {
                    $this->writeCollection(
                        xml: $xml,
                        id: 'make-' . $make->id,
                        url: $this->buildMakeUrl($make),
                        name: $this->normalizeText('Арки и пороги для ' . $make->title),
                        picture: $this->resolveMakeCollectionImage($make, $imageMap),
                        description: $this->buildMakeDescription($make),
                    );
                }
            });
    }

    protected function writeModelCollections(XMLWriter $xml): void
    {
        CarModel::query()
            ->with('car_make')
            ->whereHas('car_make', fn($query) => $query->visible())
            ->whereHas('cars.products', fn($query) => $query->where('price', '>', 0))
            ->orderBy('id')
            ->chunk(50, function ($models) use ($xml) {
                $imageMap = $this->firstValidCarImagesByModel($models->pluck('id')->all());

                foreach ($models as $model) {
                    if (!$model->car_make) {
                        continue;
                    }

                    $this->writeCollection(
                        xml: $xml,
                        id: 'model-' . $model->id,
                        url: $this->buildModelUrl($model),
                        name: $this->normalizeText('Арки и пороги для ' . $model->car_make->title . ' ' . $model->title),
                        picture: $this->resolveModelCollectionImage($model, $imageMap),
                        description: $this->buildModelDescription($model),
                    );
                }
            });
    }

    protected function writeCarCollections(XMLWriter $xml): void
    {
        $this->carFeedQuery()->chunk(50, function ($cars) use ($xml) {
            $modelIds = $cars->pluck('car_model_id')->filter()->unique()->values()->all();
            $makeIds = $cars
                ->pluck('car_model.car_make.id')
                ->filter()
                ->unique()
                ->values()
                ->all();

            $modelImageMap = $this->firstValidCarImagesByModel($modelIds);
            $makeImageMap = $this->firstValidCarImagesByMake($makeIds);

            foreach ($cars as $car) {
                if (!$car->car_model || !$car->car_model->car_make) {
                    continue;
                }

                /** @var Collection $products */
                $products = $car->products
                    ->filter(fn($product) => (int) ($product->price ?? 0) > 0)
                    ->values();

                if ($products->isEmpty()) {
                    continue;
                }

                $this->writeCollection(
                    xml: $xml,
                    id: 'car-' . $car->id,
                    url: $this->buildCarUrl($car),
                    name: $this->buildCatalogName($car),
                    picture: $this->resolveCatalogCollectionImage($car, $modelImageMap, $makeImageMap),
                    description: $this->buildCatalogDescription($car, $products),
                );
            }
        });
    }

    protected function writeCollection(
        XMLWriter $xml,
        string $id,
        string $url,
        string $name,
        string $picture,
        string $description
    ): void {
        $xml->startElement('collection');
        $xml->writeAttribute('id', $id);
        $xml->writeElement('url', $url);

        if ($picture !== '') {
            $xml->writeElement('picture', $picture);
        }

        $xml->writeElement('name', $name);

        if ($description !== '') {
            $xml->startElement('description');
            $xml->writeCData($description);
            $xml->endElement();
        }

        $xml->endElement();
    }

    protected function carFeedQuery()
    {
        return Car::query()
            ->with([
                'car_model.car_make',
                'products' => fn($query) => $query->withPivot(['image', 'image_mob'])->orderBy('products.id'),
            ])
            ->whereHas('car_model.car_make', fn($query) => $query->visible())
            ->whereHas('products', fn($query) => $query->where('price', '>', 0))
            ->orderBy('id');
    }

    protected function writeProductOffer(XMLWriter $xml, $car, $product): void
    {
        $price = (int) ($product->price ?? 0);

        if ($price <= 0) {
            return;
        }

        $xml->startElement('offer');
        $xml->writeAttribute('id', $product->id . '-' . $car->id);
        $xml->writeAttribute('available', 'true');

        $name = $this->buildProductName($car, $product);

        $xml->writeElement('name', $name);
        $xml->writeElement('url', $this->buildProductUrl($car, $product));
        $xml->writeElement('price', (string) $price);

        $old = $this->getOldPrice($product);
        if ($old !== null && $old > $price) {
            $xml->writeElement('oldprice', (string) $old);
        }

        $xml->writeElement('currencyId', 'RUB');
        $xml->writeElement('categoryId', (string) $this->resolveCategoryId($product));
        $xml->writeElement('picture', $this->resolveImage($car, $product));

        $vendor = (string) ($car->car_model->car_make->title ?? '');
        if ($vendor !== '') {
            $xml->writeElement('vendor', $vendor);
        }

        $model = (string) ($car->car_model->title ?? '');
        if ($model !== '') {
            $xml->writeElement('model', trim($model . ' ' . (string) ($car->generation ?? '')));
        }

        $makeId = (int) ($car->car_model->car_make->id ?? 0);
        $modelId = (int) ($car->car_model->id ?? 0);
        $carId = (int) ($car->id ?? 0);

        if ($makeId > 0) {
            $xml->writeElement('collectionId', 'make-' . $makeId);
        }

        if ($modelId > 0) {
            $xml->writeElement('collectionId', 'model-' . $modelId);
        }

        if ($carId > 0) {
            $xml->writeElement('collectionId', 'car-' . $carId);
        }

        $desc = $this->buildDescription($car, $product);
        if ($desc !== '') {
            $xml->startElement('description');
            $xml->writeCData($desc);
            $xml->endElement();
        }

        $this->writeParam($xml, 'Тип страницы', 'Деталь');
        $this->writeParam($xml, 'Деталь', (string) ($product->title ?? ''));
        $this->writeParam($xml, 'Марка', $vendor);
        $this->writeParam($xml, 'Модель', (string) ($car->car_model->title ?? ''));
        $this->writeParam($xml, 'Поколение', (string) ($car->generation ?? ''));
        $this->writeParam($xml, 'Годы выпуска', (string) ($car->years ?? ''));
        $this->writeParam($xml, 'Кузов', (string) ($car->body ?? ''));

        $xml->endElement();
    }

    protected function buildCatalogName($car): string
    {
        $make = (string) ($car->car_model->car_make->title ?? '');
        $model = (string) ($car->car_model->title ?? '');
        $generation = trim((string) ($car->generation ?? ''));
        $years = trim((string) ($car->years ?? ''));
        $body = $this->bodyWithDoors($car);

        return $this->normalizeText(implode(' ', array_filter([
            'Арки и пороги',
            $make,
            $model,
            $generation,
            $years !== '' ? "({$years})" : null,
            $body,
        ], fn($value) => $value !== null && trim((string) $value) !== '')));
    }

    protected function buildProductName($car, $product): string
    {
        $make = (string) ($car->car_model->car_make->title ?? '');
        $model = (string) ($car->car_model->title ?? '');
        $generation = trim((string) ($car->generation ?? ''));
        $years = trim((string) ($car->years ?? ''));
        $body = $this->bodyWithDoors($car);

        return $this->normalizeText(implode(' ', array_filter([
            trim((string) ($product->title ?? '')),
            $make,
            $model,
            $generation,
            $years !== '' ? "({$years})" : null,
            $body,
        ], fn($value) => $value !== null && trim((string) $value) !== '')));
    }

    protected function buildMakeDescription(CarMake $make): string
    {
        return sprintf(
            'Арки и пороги для %s. В наличии пороги, арки и другие кузовные элементы собственного производства. Оплата при получении, доставка по РФ.',
            $make->title
        );
    }

    protected function buildModelDescription(CarModel $model): string
    {
        return sprintf(
            'Арки и пороги для %s %s. В наличии пороги, арки и другие кузовные элементы собственного производства. Оплата при получении, доставка по РФ.',
            $model->car_make->title,
            $model->title
        );
    }

    protected function buildCatalogDescription($car, Collection $products): string
    {
        $make = (string) ($car->car_model->car_make->title ?? '');
        $model = (string) ($car->car_model->title ?? '');
        $generation = trim((string) ($car->generation ?? ''));
        $years = trim((string) ($car->years ?? ''));
        $body = $this->bodyWithDoors($car);

        $carName = rtrim($this->normalizeText(implode(' ', array_filter([
            $make,
            $model,
            $generation,
            $years !== '' ? "({$years})" : null,
            $body,
        ]))), '. ');

        $productNames = $products
            ->pluck('title')
            ->filter()
            ->map(fn($title) => trim((string) $title))
            ->unique()
            ->values()
            ->take(20)
            ->implode(', ');

        $text = 'Арки и пороги для ' . $carName . '. ';

        if ($productNames !== '') {
            $text .= 'В наличии: ' . $productNames . '. ';
        }

        $text .= 'Собственное производство. 1–1.5 мм. ХКС и Цинк. Оплата при получении.';

        return trim($text);
    }

    protected function buildMakeUrl(CarMake $make): string
    {
        return $this->absoluteUrl('/katalog/' . $make->slug);
    }

    protected function buildModelUrl(CarModel $model): string
    {
        return $this->absoluteUrl(sprintf(
            '/katalog/%s/%s',
            $model->car_make->slug,
            $model->slug,
        ));
    }

    protected function buildCarUrl($car): string
    {
        return $this->absoluteUrl(sprintf(
            '/katalog/%s/%s/%s',
            $car->car_model->car_make->slug,
            $car->car_model->slug,
            $car->slug,
        ));
    }

    protected function buildProductUrl($car, $product): string
    {
        return $this->buildCarUrl($car) . '?part=' . rawurlencode((string) $product->slug);
    }

    protected function resolveMakeCollectionImage(CarMake $make, array $imageMap): string
    {
        if (isset($imageMap[$make->id])) {
            return $this->toPublicUrl($imageMap[$make->id]);
        }

        return $this->catalogFallbackImage();
    }

    protected function resolveModelCollectionImage(CarModel $model, array $imageMap): string
    {
        if (isset($imageMap[$model->id])) {
            return $this->toPublicUrl($imageMap[$model->id]);
        }

        return $this->catalogFallbackImage();
    }

    protected function resolveCatalogCollectionImage($car, array $modelImageMap, array $makeImageMap): string
    {
        $carImage = trim((string) ($car->image ?? ''));
        if ($this->hasValidImagePath($carImage)) {
            return $this->toPublicUrl($carImage);
        }

        $modelId = (int) ($car->car_model_id ?? 0);
        if ($modelId > 0 && isset($modelImageMap[$modelId])) {
            return $this->toPublicUrl($modelImageMap[$modelId]);
        }

        $makeId = (int) ($car->car_model->car_make->id ?? 0);
        if ($makeId > 0 && isset($makeImageMap[$makeId])) {
            return $this->toPublicUrl($makeImageMap[$makeId]);
        }

        return $this->catalogFallbackImage();
    }

    protected function resolveImage($car, $product): string
    {
        $image = $product->pivot->image ?? null;
        if ($this->hasValidImagePath($image)) {
            return $this->toPublicUrl((string) $image);
        }

        $image = $product->image ?? null;
        if ($this->hasValidImagePath($image)) {
            return $this->toPublicUrl((string) $image);
        }

        return $this->defaultProductImage($product);
    }

    protected function firstValidCarImagesByMake(array $makeIds): array
    {
        $makeIds = array_values(array_unique(array_filter(array_map('intval', $makeIds))));
        if ($makeIds === []) {
            return [];
        }

        $firstCarIds = $this->validCarsWithImagesQuery()
            ->whereIn('car_models.car_make_id', $makeIds)
            ->selectRaw('car_models.car_make_id as group_id, MIN(cars.id) as car_id')
            ->groupBy('car_models.car_make_id')
            ->pluck('car_id', 'group_id');

        return $this->mapGroupedCarIdsToImages($firstCarIds);
    }

    protected function firstValidCarImagesByModel(array $modelIds): array
    {
        $modelIds = array_values(array_unique(array_filter(array_map('intval', $modelIds))));
        if ($modelIds === []) {
            return [];
        }

        $firstCarIds = $this->validCarsWithImagesQuery()
            ->whereIn('cars.car_model_id', $modelIds)
            ->selectRaw('cars.car_model_id as group_id, MIN(cars.id) as car_id')
            ->groupBy('cars.car_model_id')
            ->pluck('car_id', 'group_id');

        return $this->mapGroupedCarIdsToImages($firstCarIds);
    }

    protected function validCarsWithImagesQuery()
    {
        return DB::table('cars')
            ->join('car_models', 'car_models.id', '=', 'cars.car_model_id')
            ->join('car_makes', 'car_makes.id', '=', 'car_models.car_make_id')
            ->join('car_product', 'car_product.car_id', '=', 'cars.id')
            ->join('products', 'products.id', '=', 'car_product.product_id')
            ->whereNull('cars.deleted_at')
            ->whereNull('car_models.deleted_at')
            ->whereNull('car_makes.deleted_at')
            ->whereNull('products.deleted_at')
            ->where('car_makes.is_hidden', false)
            ->where('products.price', '>', 0)
            ->whereNotNull('cars.image')
            ->where('cars.image', '<>', '')
            ->where('cars.image', '<>', 'default');
    }

    protected function mapGroupedCarIdsToImages($groupedCarIds): array
    {
        $groupedCarIds = collect($groupedCarIds)
            ->map(fn($carId) => (int) $carId)
            ->filter(fn($carId) => $carId > 0);

        if ($groupedCarIds->isEmpty()) {
            return [];
        }

        $imagesByCarId = DB::table('cars')
            ->whereIn('id', $groupedCarIds->unique()->values()->all())
            ->pluck('image', 'id');

        return $groupedCarIds
            ->mapWithKeys(function ($carId, $groupId) use ($imagesByCarId) {
                $image = (string) ($imagesByCarId[$carId] ?? '');

                if (!$this->hasValidImagePath($image)) {
                    return [];
                }

                return [(int) $groupId => $image];
            })
            ->all();
    }

    protected function toPublicUrl(string $path): string
    {
        $path = trim($path);

        if ($path === '') {
            return '';
        }

        $image = $this->resolvePublicImageData($path);

        return $this->versionedImageUrl($image['url'], $image['sourcePath']);
    }

    protected function resolvePublicImageData(string $path): array
    {
        $path = trim($path);

        if ($path === '') {
            return ['url' => '', 'sourcePath' => null];
        }

        if (preg_match('~^https?://~i', $path)) {
            return ['url' => $path, 'sourcePath' => null];
        }

        $normalized = ltrim($path, '/');

        if (str_starts_with($normalized, 'products_default/')) {
            return [
                'url' => $this->absoluteUrl('storage/' . $normalized),
                'sourcePath' => storage_path('app/public/' . $normalized),
            ];
        }

        if (str_starts_with($normalized, 'storage/')) {
            $storageRelative = substr($normalized, strlen('storage/'));

            return [
                'url' => $this->absoluteUrl($normalized),
                'sourcePath' => storage_path('app/public/' . $storageRelative),
            ];
        }

        if (str_starts_with($normalized, 'uploads/')) {
            return [
                'url' => $this->absoluteUrl('storage/' . $normalized),
                'sourcePath' => storage_path('app/public/' . $normalized),
            ];
        }

        if (str_starts_with($normalized, 'images/')) {
            return [
                'url' => $this->absoluteUrl($normalized),
                'sourcePath' => public_path($normalized),
            ];
        }

        return [
            'url' => $this->absoluteUrl('storage/' . $normalized),
            'sourcePath' => storage_path('app/public/' . $normalized),
        ];
    }

    protected function versionedImageUrl(string $url, ?string $sourcePath = null): string
    {
        if ($url === '' || $sourcePath === null || !is_file($sourcePath)) {
            return $url;
        }

        $mtime = @filemtime($sourcePath);
        if ($mtime === false) {
            return $url;
        }

        return str_contains($url, '?')
            ? $url . '&v=' . $mtime
            : $url . '?v=' . $mtime;
    }

    protected function defaultProductImage($product): string
    {
        $title = mb_strtolower(trim((string) ($product->title ?? '')));

        if (str_contains($title, 'лонжерон')) {
            return $this->toPublicUrl('products_default/lonzeron.png');
        }

        if (str_contains($title, 'торцев') || str_contains($title, 'заглушк')) {
            return $this->toPublicUrl('products_default/torcevaia-zagluska.jpeg');
        }

        if (str_contains($title, 'ремкомплект') && str_contains($title, 'пола')) {
            return $this->toPublicUrl('products_default/remkomplekt-pola.jpeg');
        }

        if (str_contains($title, 'усилител') || str_contains($title, 'соединител')) {
            return $this->toPublicUrl('products_default/usilitel-soedinitel-porogov.png');
        }

        if (str_contains($title, 'пенк') || str_contains($title, 'пена')) {
            if (str_contains($title, 'багаж')) {
                return $this->toPublicUrl('products_default/penka-bagaznika.jpg');
            }
            if (str_contains($title, 'перед') && str_contains($title, 'двер')) {
                return $this->toPublicUrl('products_default/penka-perednei-dveri.jpg');
            }
            if (str_contains($title, 'зад') && str_contains($title, 'двер')) {
                return $this->toPublicUrl('products_default/penka-zadnei-dveri.jpg');
            }

            return $this->toPublicUrl('products_default/penka-bagaznika.jpg');
        }

        if (str_contains($title, 'арка')) {
            if (str_contains($title, 'карман') && str_contains($title, 'зад')) {
                return $this->toPublicUrl('products_default/arka-karman-zadniaia.jpg');
            }
            if (str_contains($title, 'перед')) {
                return $this->toPublicUrl('products_default/arka-peredniaia.jpg');
            }
            if (str_contains($title, 'внутрен') && str_contains($title, 'универс')) {
                return $this->toPublicUrl('products_default/arka-vnutrenniaia-universalnaia.jpeg');
            }
            if (str_contains($title, 'внутрен')) {
                return $this->toPublicUrl('products_default/arka-vnutrenniaia.jpeg');
            }

            return $this->toPublicUrl('products_default/arka-zadniaia.jpg');
        }

        return $this->fallbackImage();
    }

    protected function fallbackImage(): string
    {
        return $this->toPublicUrl('products_default/porog.png');
    }

    protected function catalogFallbackImage(): string
    {
        return $this->toPublicUrl($this->catalogFallbackPath);
    }

    protected function writeParam(XMLWriter $xml, string $name, string $value): void
    {
        $value = trim($value);
        if ($value === '') {
            return;
        }

        $xml->startElement('param');
        $xml->writeAttribute('name', $name);
        $xml->text($value);
        $xml->endElement();
    }

    protected function resolveCategoryId($product): int
    {
        $title = mb_strtolower((string) ($product->title ?? ''));

        if (str_contains($title, 'порог')) {
            return 11;
        }

        return 10;
    }

    protected function getOldPrice($product): ?int
    {
        foreach (['oldprice', 'old_price', 'price_old', 'oldPrice'] as $field) {
            if (isset($product->{$field}) && $product->{$field} !== null && $product->{$field} !== '') {
                return (int) $product->{$field};
            }
        }

        return null;
    }

    protected function buildDescription($car, $product): string
    {
        foreach (['description', 'desc', 'text'] as $field) {
            if (!empty($product->{$field})) {
                return trim((string) $product->{$field});
            }
        }

        return 'Собственное производство. 1–1.5 мм. ХКС и Цинк. Оплата при получении.';
    }

    protected function bodyWithDoors($car): string
    {
        $body = trim((string) ($car->body ?? ''));

        if ($body !== '' && preg_match('/\b\d+\s*дв\.?/ui', $body)) {
            return $body;
        }

        $title = (string) ($car->title ?? '');
        if (preg_match('/\b(\d+)\s*дв\.?/ui', $title, $matches)) {
            $doors = $matches[1] . ' дв.';

            return trim($body !== '' ? ($body . ' ' . $doors) : $doors);
        }

        return $body;
    }

    protected function siteUrl(): string
    {
        $url = trim((string) config('app.url'));

        if ($url === '' || str_contains($url, 'localhost') || str_contains($url, '127.0.0.1')) {
            $url = 'https://dvaporoga.ru';
        }

        return rtrim($url, '/');
    }

    protected function absoluteUrl(string $path): string
    {
        if (preg_match('~^https?://~i', $path)) {
            return $path;
        }

        return $this->siteUrl() . '/' . ltrim($path, '/');
    }

    protected function normalizeText(string $text): string
    {
        return trim(preg_replace('/\s+/u', ' ', $text) ?? $text);
    }

    protected function hasValidImagePath(?string $path): bool
    {
        $path = trim((string) $path);

        return $path !== '' && $path !== 'default';
    }
}
