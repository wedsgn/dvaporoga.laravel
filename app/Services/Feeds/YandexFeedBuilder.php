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
        $xml->writeElement('name', config('app.name'));
        $xml->writeElement('company', config('app.name'));
        $xml->writeElement('url', $this->siteUrl());

        $this->writeCurrencies($xml);
        $this->writeCategories($xml);
        $this->writeOffers($xml);

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
        $this->writeCategory($xml, 2, 'Каталог по марке', 1);
        $this->writeCategory($xml, 3, 'Каталог по модели', 2);
        $this->writeCategory($xml, 4, 'Каталог по автомобилю', 3);
        $this->writeCategory($xml, 10, 'Детали', 1);
        $this->writeCategory($xml, 11, 'Пороги', 10);

        $xml->endElement();
    }

    protected function writeCategory(XMLWriter $xml, int $id, string $title, ?int $parentId = null): void
    {
        $xml->startElement('category');
        $xml->writeAttribute('id', (string)$id);

        if ($parentId !== null) {
            $xml->writeAttribute('parentId', (string)$parentId);
        }

        $xml->text($title);
        $xml->endElement();
    }

    protected function writeOffers(XMLWriter $xml): void
    {
        $xml->startElement('offers');

        $this->writeMakeOffers($xml);
        $this->writeModelOffers($xml);
        $this->writeCarAndProductOffers($xml);

        $xml->endElement();
    }

    protected function writeMakeOffers(XMLWriter $xml): void
    {
        CarMake::query()
            ->visible()
            ->whereHas('car_models.cars.products', fn($q) => $q->where('price', '>', 0))
            ->orderBy('id')
            ->chunk(50, function ($makes) use ($xml) {
                foreach ($makes as $make) {
                    $price = $this->getMinPriceForMake($make);

                    if ($price <= 0) {
                        continue;
                    }

                    $this->writeLandingOffer(
                        xml: $xml,
                        id: 'make-' . $make->id,
                        categoryId: 2,
                        name: $this->normalizeText('Кузовные детали для ' . $make->title),
                        url: $this->buildMakeUrl($make),
                        price: $price,
                        picture: $this->resolveMakeImage($make),
                        description: $this->buildMakeDescription($make),
                        vendor: (string)$make->title,
                        model: '',
                        params: [
                            'Тип страницы' => 'Марка',
                            'Марка' => (string)$make->title,
                        ]
                    );
                }
            });
    }

    protected function writeModelOffers(XMLWriter $xml): void
    {
        CarModel::query()
            ->with('car_make')
            ->whereHas('car_make', fn($q) => $q->visible())
            ->whereHas('cars.products', fn($q) => $q->where('price', '>', 0))
            ->orderBy('id')
            ->chunk(50, function ($models) use ($xml) {
                foreach ($models as $model) {
                    if (!$model->car_make) {
                        continue;
                    }

                    $price = $this->getMinPriceForModel($model);

                    if ($price <= 0) {
                        continue;
                    }

                    $this->writeLandingOffer(
                        xml: $xml,
                        id: 'model-' . $model->id,
                        categoryId: 3,
                        name: $this->normalizeText('Кузовные детали для ' . $model->car_make->title . ' ' . $model->title),
                        url: $this->buildModelUrl($model),
                        price: $price,
                        picture: $this->resolveModelImage($model),
                        description: $this->buildModelDescription($model),
                        vendor: (string)$model->car_make->title,
                        model: (string)$model->title,
                        params: [
                            'Тип страницы' => 'Модель',
                            'Марка' => (string)$model->car_make->title,
                            'Модель' => (string)$model->title,
                        ]
                    );
                }
            });
    }

    protected function writeCarAndProductOffers(XMLWriter $xml): void
    {
        Car::query()
            ->with([
                'car_model.car_make',
                'products' => fn($q) => $q->withPivot(['image', 'image_mob'])->orderBy('products.id'),
            ])
            ->whereHas('products', fn($q) => $q->where('price', '>', 0))
            ->orderBy('id')
            ->chunk(50, function ($cars) use ($xml) {
                foreach ($cars as $car) {
                    if (!$car->car_model || !$car->car_model->car_make) {
                        continue;
                    }

                    /** @var Collection $products */
                    $products = $car->products
                        ->filter(fn($product) => (int)($product->price ?? 0) > 0)
                        ->values();

                    if ($products->isEmpty()) {
                        continue;
                    }

                    $this->writeCatalogOffer($xml, $car, $products);

                    foreach ($products as $product) {
                        $this->writeProductOffer($xml, $car, $product);
                    }
                }
            });
    }

    protected function writeLandingOffer(
        XMLWriter $xml,
        string $id,
        int $categoryId,
        string $name,
        string $url,
        int $price,
        string $picture,
        string $description,
        string $vendor = '',
        string $model = '',
        array $params = []
    ): void {
        $xml->startElement('offer');
        $xml->writeAttribute('id', $id);
        $xml->writeAttribute('available', 'true');

        $xml->writeElement('name', $name);
        $xml->writeElement('url', $url);
        $xml->writeElement('price', (string)max(1, $price));
        $xml->writeElement('currencyId', 'RUB');
        $xml->writeElement('categoryId', (string)$categoryId);

        if ($picture !== '') {
            $xml->writeElement('picture', $picture);
        }

        if ($vendor !== '') {
            $xml->writeElement('vendor', $vendor);
        }

        if ($model !== '') {
            $xml->writeElement('model', $model);
        }

        if ($description !== '') {
            $xml->startElement('description');
            $xml->writeCData($description);
            $xml->endElement();
        }

        foreach ($params as $paramName => $paramValue) {
            $this->writeParam($xml, (string)$paramName, (string)$paramValue);
        }

        $xml->endElement();
    }

    protected function writeCatalogOffer(XMLWriter $xml, $car, Collection $products): void
    {
        $vendor = (string)($car->car_model->car_make->title ?? '');
        $model = (string)($car->car_model->title ?? '');
        $fullModel = trim($model . ' ' . (string)($car->generation ?? ''));

        $this->writeLandingOffer(
            xml: $xml,
            id: 'catalog-' . $car->id,
            categoryId: 4,
            name: $this->buildCatalogName($car),
            url: $this->buildCarUrl($car),
            price: $this->getCatalogMinPrice($products),
            picture: $this->resolveCatalogImage($car, $products),
            description: $this->buildCatalogDescription($car, $products),
            vendor: $vendor,
            model: $fullModel,
            params: [
                'Тип страницы' => 'Автомобиль',
                'Марка' => $vendor,
                'Модель' => $model,
                'Поколение' => (string)($car->generation ?? ''),
                'Годы выпуска' => (string)($car->years ?? ''),
                'Кузов' => (string)($car->body ?? ''),
            ]
        );
    }

    protected function writeProductOffer(XMLWriter $xml, $car, $product): void
    {
        $price = (int)($product->price ?? 0);

        if ($price <= 0) {
            return;
        }

        $xml->startElement('offer');
        $xml->writeAttribute('id', $product->id . '-' . $car->id);
        $xml->writeAttribute('available', 'true');

        $name = $this->buildProductName($car, $product);

        $xml->writeElement('name', $name);
        $xml->writeElement('url', $this->buildProductUrl($car, $product));
        $xml->writeElement('price', (string)$price);

        $old = $this->getOldPrice($product);
        if ($old !== null && $old > $price) {
            $xml->writeElement('oldprice', (string)$old);
        }

        $xml->writeElement('currencyId', 'RUB');
        $xml->writeElement('categoryId', (string)$this->resolveCategoryId($product));
        $xml->writeElement('picture', $this->resolveImage($car, $product));

        $vendor = (string)($car->car_model->car_make->title ?? '');
        if ($vendor !== '') {
            $xml->writeElement('vendor', $vendor);
        }

        $model = (string)($car->car_model->title ?? '');
        if ($model !== '') {
            $xml->writeElement('model', trim($model . ' ' . (string)($car->generation ?? '')));
        }

        $desc = $this->buildDescription($car, $product);
        if ($desc !== '') {
            $xml->startElement('description');
            $xml->writeCData($desc);
            $xml->endElement();
        }

        $this->writeParam($xml, 'Тип страницы', 'Деталь');
        $this->writeParam($xml, 'Деталь', (string)($product->title ?? ''));
        $this->writeParam($xml, 'Марка', $vendor);
        $this->writeParam($xml, 'Модель', (string)($car->car_model->title ?? ''));
        $this->writeParam($xml, 'Поколение', (string)($car->generation ?? ''));
        $this->writeParam($xml, 'Годы выпуска', (string)($car->years ?? ''));
        $this->writeParam($xml, 'Кузов', (string)($car->body ?? ''));

        $xml->endElement();
    }

    protected function buildCatalogName($car): string
    {
        $make = (string)($car->car_model->car_make->title ?? '');
        $model = (string)($car->car_model->title ?? '');
        $generation = trim((string)($car->generation ?? ''));
        $years = trim((string)($car->years ?? ''));
        $body = $this->bodyWithDoors($car);

        return $this->normalizeText(implode(' ', array_filter([
            'Каталог деталей',
            $make,
            $model,
            $generation,
            $years !== '' ? "({$years})" : null,
            $body,
        ], fn($v) => $v !== null && trim((string)$v) !== '')));
    }

    protected function buildProductName($car, $product): string
    {
        $make  = (string)($car->car_model->car_make->title ?? '');
        $model = (string)($car->car_model->title ?? '');
        $generation = trim((string)($car->generation ?? ''));
        $years = trim((string)($car->years ?? ''));
        $body = $this->bodyWithDoors($car);

        return $this->normalizeText(implode(' ', array_filter([
            trim((string)($product->title ?? '')),
            $make,
            $model,
            $generation,
            $years !== '' ? "({$years})" : null,
            $body,
        ], fn($v) => $v !== null && trim((string)$v) !== '')));
    }

    protected function buildMakeDescription(CarMake $make): string
    {
        $models = $make->car_models()
            ->whereHas('cars.products', fn($q) => $q->where('price', '>', 0))
            ->orderBy('title')
            ->limit(20)
            ->pluck('title')
            ->filter()
            ->implode(', ');

        $text = 'Каталог кузовных деталей для ' . $make->title . '. ';

        if ($models !== '') {
            $text .= 'Модели: ' . $models . '. ';
        }

        $text .= 'Пороги, арки и другие кузовные элементы собственного производства. Оплата при получении, доставка по РФ.';

        return trim($text);
    }

    protected function buildModelDescription(CarModel $model): string
    {
        $cars = $model->cars()
            ->whereHas('products', fn($q) => $q->where('price', '>', 0))
            ->orderBy('years')
            ->limit(20)
            ->get()
            ->map(fn($car) => $this->normalizeText(implode(' ', array_filter([
                $car->generation,
                $car->years ? '(' . $car->years . ')' : null,
                $this->bodyWithDoors($car),
            ]))))
            ->filter()
            ->implode(', ');

        $text = 'Каталог кузовных деталей для ' . $model->car_make->title . ' ' . $model->title . '. ';

        if ($cars !== '') {
            $text .= 'Поколения и кузова: ' . $cars . '. ';
        }

        $text .= 'Пороги, арки и другие кузовные элементы собственного производства. Оплата при получении, доставка по РФ.';

        return trim($text);
    }

    protected function buildCatalogDescription($car, Collection $products): string
    {
        $make = (string)($car->car_model->car_make->title ?? '');
        $model = (string)($car->car_model->title ?? '');
        $generation = trim((string)($car->generation ?? ''));
        $years = trim((string)($car->years ?? ''));
        $body = $this->bodyWithDoors($car);

        $carName = $this->normalizeText(implode(' ', array_filter([
            $make,
            $model,
            $generation,
            $years !== '' ? "({$years})" : null,
            $body,
        ])));

        $productNames = $products
            ->pluck('title')
            ->filter()
            ->map(fn($title) => trim((string)$title))
            ->unique()
            ->values()
            ->take(20)
            ->implode(', ');

        $text = 'Каталог кузовных деталей для ' . $carName . '. ';

        if ($productNames !== '') {
            $text .= 'В наличии: ' . $productNames . '. ';
        }

        $text .= 'Собственное производство. 1–1.5 мм. ХКС и Цинк. Оплата при получении.';

        return trim($text);
    }

    protected function getMinPriceForMake(CarMake $make): int
    {
        return (int)(DB::table('products')
            ->join('car_product', 'products.id', '=', 'car_product.product_id')
            ->join('cars', 'cars.id', '=', 'car_product.car_id')
            ->join('car_models', 'car_models.id', '=', 'cars.car_model_id')
            ->where('car_models.car_make_id', $make->id)
            ->whereNull('products.deleted_at')
            ->whereNull('cars.deleted_at')
            ->whereNull('car_models.deleted_at')
            ->where('products.price', '>', 0)
            ->min('products.price') ?? 0);
    }

    protected function getMinPriceForModel(CarModel $model): int
    {
        return (int)(DB::table('products')
            ->join('car_product', 'products.id', '=', 'car_product.product_id')
            ->join('cars', 'cars.id', '=', 'car_product.car_id')
            ->where('cars.car_model_id', $model->id)
            ->whereNull('products.deleted_at')
            ->whereNull('cars.deleted_at')
            ->where('products.price', '>', 0)
            ->min('products.price') ?? 0);
    }

    protected function getCatalogMinPrice(Collection $products): int
    {
        $min = $products
            ->pluck('price')
            ->filter(fn($price) => $price !== null && $price !== '')
            ->map(fn($price) => (int)$price)
            ->filter(fn($price) => $price > 0)
            ->min();

        return (int)($min ?? 0);
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
            $model->slug
        ));
    }

    protected function buildCarUrl($car): string
    {
        return $this->absoluteUrl(sprintf(
            '/katalog/%s/%s/%s',
            $car->car_model->car_make->slug,
            $car->car_model->slug,
            $car->slug
        ));
    }

    protected function buildProductUrl($car, $product): string
    {
        return $this->buildCarUrl($car) . '?part=' . rawurlencode((string)$product->slug);
    }

    protected function resolveMakeImage(CarMake $make): string
    {
        if (!empty($make->image) && $make->image !== 'default') {
            return $this->toPublicUrl((string)$make->image);
        }

        $car = Car::query()
            ->whereHas('car_model', fn($q) => $q->where('car_make_id', $make->id))
            ->whereNotNull('image')
            ->where('image', '<>', '')
            ->where('image', '<>', 'default')
            ->orderBy('id')
            ->first();

        if ($car) {
            return $this->toPublicUrl((string)$car->image);
        }

        return $this->fallbackImage();
    }

    protected function resolveModelImage(CarModel $model): string
    {
        if (!empty($model->image) && $model->image !== 'default') {
            return $this->toPublicUrl((string)$model->image);
        }

        $car = $model->cars()
            ->whereNotNull('image')
            ->where('image', '<>', '')
            ->where('image', '<>', 'default')
            ->orderBy('id')
            ->first();

        if ($car) {
            return $this->toPublicUrl((string)$car->image);
        }

        return $this->fallbackImage();
    }

    protected function resolveCatalogImage($car, Collection $products): string
    {
        $carImage = (string)($car->image ?? '');
        if ($carImage !== '' && $carImage !== 'default') {
            return $this->toPublicUrl($carImage);
        }

        $firstProduct = $products->first();
        if ($firstProduct) {
            return $this->resolveImage($car, $firstProduct);
        }

        return $this->fallbackImage();
    }

    protected function resolveImage($car, $product): string
    {
        $img = $product->pivot->image ?? null;
        if (!empty($img) && $img !== 'default') {
            return $this->toPublicUrl((string)$img);
        }

        $img = $product->image ?? null;
        if (!empty($img) && $img !== 'default') {
            return $this->toPublicUrl((string)$img);
        }

        return $this->defaultProductImage($product);
    }

    protected function toPublicUrl(string $path): string
    {
        $path = trim($path);

        if ($path === '') {
            return $this->fallbackImage();
        }

        if (preg_match('~^https?://~i', $path)) {
            return $path;
        }

        $path = ltrim($path, '/');

        if (str_starts_with($path, 'storage/uploads/')) {
            return $this->absoluteUrl($path);
        }

        if (str_starts_with($path, 'products_default/')) {
            return $this->absoluteUrl('storage/' . $path);
        }

        if (str_starts_with($path, 'storage/')) {
            return $this->absoluteUrl($path);
        }

        if (str_starts_with($path, 'images/')) {
            return $this->absoluteUrl($path);
        }

        return $this->absoluteUrl('storage/' . $path);
    }

    protected function defaultProductImage($product): string
    {
        $t = mb_strtolower(trim((string)($product->title ?? '')));

        if (str_contains($t, 'лонжерон')) {
            return $this->absoluteUrl('storage/products_default/lonzeron.png');
        }

        if (str_contains($t, 'торцев') || str_contains($t, 'заглушк')) {
            return $this->absoluteUrl('storage/products_default/torcevaia-zagluska.jpeg');
        }

        if (str_contains($t, 'ремкомплект') && str_contains($t, 'пола')) {
            return $this->absoluteUrl('storage/products_default/remkomplekt-pola.jpeg');
        }

        if (str_contains($t, 'усилител') || str_contains($t, 'соединител')) {
            return $this->absoluteUrl('storage/products_default/usilitel-soedinitel-porogov.png');
        }

        if (str_contains($t, 'пенк') || str_contains($t, 'пена')) {
            if (str_contains($t, 'багаж')) {
                return $this->absoluteUrl('storage/products_default/penka-bagaznika.jpg');
            }
            if (str_contains($t, 'перед') && str_contains($t, 'двер')) {
                return $this->absoluteUrl('storage/products_default/penka-perednei-dveri.jpg');
            }
            if (str_contains($t, 'зад') && str_contains($t, 'двер')) {
                return $this->absoluteUrl('storage/products_default/penka-zadnei-dveri.jpg');
            }
            return $this->absoluteUrl('storage/products_default/penka-bagaznika.jpg');
        }

        if (str_contains($t, 'арка')) {
            if (str_contains($t, 'карман') && str_contains($t, 'зад')) {
                return $this->absoluteUrl('storage/products_default/arka-karman-zadniaia.jpg');
            }
            if (str_contains($t, 'перед')) {
                return $this->absoluteUrl('storage/products_default/arka-peredniaia.jpg');
            }
            if (str_contains($t, 'внутрен') && str_contains($t, 'универс')) {
                return $this->absoluteUrl('storage/products_default/arka-vnutrenniaia-universalnaia.jpeg');
            }
            if (str_contains($t, 'внутрен')) {
                return $this->absoluteUrl('storage/products_default/arka-vnutrenniaia.jpeg');
            }
            return $this->absoluteUrl('storage/products_default/arka-zadniaia.jpg');
        }

        return $this->fallbackImage();
    }

    protected function fallbackImage(): string
    {
        return $this->absoluteUrl('storage/products_default/porog.png');
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
        $title = mb_strtolower((string)($product->title ?? ''));

        if (str_contains($title, 'порог')) {
            return 11;
        }

        return 10;
    }

    protected function getOldPrice($product): ?int
    {
        foreach (['oldprice', 'old_price', 'price_old', 'oldPrice'] as $field) {
            if (isset($product->{$field}) && $product->{$field} !== null && $product->{$field} !== '') {
                return (int)$product->{$field};
            }
        }

        return null;
    }

    protected function buildDescription($car, $product): string
    {
        foreach (['description', 'desc', 'text'] as $field) {
            if (!empty($product->{$field})) {
                return trim((string)$product->{$field});
            }
        }

        return 'Собственное производство. 1–1.5 мм. ХКС и Цинк. Оплата при получении.';
    }

    protected function bodyWithDoors($car): string
    {
        $body = trim((string)($car->body ?? ''));

        if ($body !== '' && preg_match('/\b\d+\s*дв\.?/ui', $body)) {
            return $body;
        }

        $title = (string)($car->title ?? '');
        if (preg_match('/\b(\d+)\s*дв\.?/ui', $title, $m)) {
            $doors = $m[1] . ' дв.';
            return trim($body !== '' ? ($body . ' ' . $doors) : $doors);
        }

        return $body;
    }

    protected function siteUrl(): string
    {
        $url = trim((string)config('app.url'));

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
}
