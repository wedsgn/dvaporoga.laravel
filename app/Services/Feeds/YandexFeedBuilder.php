<?php

namespace App\Services\Feeds;

use App\Models\Car;
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
        $xml->writeElement('url', config('app.url'));

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

        $xml->startElement('currency');
        $xml->writeAttribute('id', 'RUR');
        $xml->writeAttribute('rate', '1');
        $xml->endElement();

        $xml->endElement();
    }

    protected function writeCategories(XMLWriter $xml): void
    {
        $xml->startElement('categories');

        $xml->startElement('category');
        $xml->writeAttribute('id', 1);
        $xml->text('Деталь');
        $xml->endElement();

        $xml->startElement('category');
        $xml->writeAttribute('id', 2);
        $xml->text('Порог');
        $xml->endElement();

        $xml->endElement();
    }

    protected function writeOffers(XMLWriter $xml): void
    {
        $xml->startElement('offers');

        Car::query()
            ->with(['car_model.car_make'])
            ->chunk(50, function ($cars) use ($xml) {
                foreach ($cars as $car) {
                    $products = $car->products()
                        ->withPivot(['id', 'image', 'image_mob'])
                        ->get();

                    if ($products->isEmpty()) {
                        continue;
                    }

                    // Сначала добавляем offer каталога авто
                    $this->writeCatalogOffer($xml, $car, $products);

                    // Затем стандартные offer деталей
                    foreach ($products as $product) {
                        $this->writeOffer($xml, $car, $product);
                    }
                }
            });

        $xml->endElement();
    }

    protected function writeCatalogOffer(XMLWriter $xml, $car, $products): void
    {
        $xml->startElement('offer');
        $xml->writeAttribute('id', 'catalog-' . $car->id);
        $xml->writeAttribute('available', 'true');

        $name = $this->buildCatalogName($car);
        $url = $this->buildUrl($car);
        $price = $this->getCatalogMinPrice($products);
        $picture = $this->resolveCatalogImage($car, $products);
        $description = $this->buildCatalogDescription($car, $products);

        $xml->writeElement('name', $name);
        $xml->writeElement('url', $url);
        $xml->writeElement('price', $price > 0 ? $price : 1);
        $xml->writeElement('currencyId', 'RUB');
        $xml->writeElement('categoryId', 1);

        if ($picture !== '') {
            $xml->writeElement('picture', $picture);
        }

        $vendor = (string)($car->car_model->car_make->title ?? '');
        if ($vendor !== '') {
            $xml->writeElement('vendor', $vendor);
        }

        $model = (string)($car->car_model->title ?? '');
        if ($model !== '') {
            $xml->writeElement('model', trim($model . ' ' . (string)($car->generation ?? '')));
        }

        if ($description !== '') {
            $xml->startElement('description');
            $xml->writeCData($description);
            $xml->endElement();
        }

        $this->writeParam($xml, 'Марка', $vendor);
        $this->writeParam($xml, 'Модель', (string)($car->car_model->title ?? ''));
        $this->writeParam($xml, 'Поколение', (string)($car->generation ?? ''));
        $this->writeParam($xml, 'Годы выпуска', (string)($car->years ?? ''));
        $this->writeParam($xml, 'Кузов', (string)($car->body ?? ''));

        $xml->endElement();
    }

    protected function writeOffer(XMLWriter $xml, $car, $product): void
    {
        $xml->startElement('offer');
        $xml->writeAttribute('id', $product->id . '-' . $car->id);
        $xml->writeAttribute('available', 'true');

        $name = $this->buildName($car, $product);

        $xml->writeElement('name', $name);
        $xml->writeElement('url', $this->buildUrl($car));

        $price = (int) ($product->price ?? 0);
        $xml->writeElement('price', $price);

        $old = $this->getOldPrice($product);
        if ($old !== null && (int)$old > 0 && (int)$old > $price) {
            $xml->writeElement('oldprice', (int)$old);
        }

        $xml->writeElement('currencyId', 'RUB');
        $xml->writeElement('categoryId', $this->resolveCategoryId($product));
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

        $parts = array_filter([
            'Каталог деталей',
            trim($make),
            trim($model),
            $generation,
            $years !== '' ? "({$years})" : null,
            $body,
        ], fn($v) => $v !== null && trim((string)$v) !== '');

        return trim(preg_replace('/\s+/u', ' ', implode(' ', $parts)));
    }

    protected function buildCatalogDescription($car, $products): string
    {
        $make = (string)($car->car_model->car_make->title ?? '');
        $model = (string)($car->car_model->title ?? '');
        $generation = trim((string)($car->generation ?? ''));
        $years = trim((string)($car->years ?? ''));
        $body = $this->bodyWithDoors($car);

        $carName = trim(preg_replace('/\s+/u', ' ', implode(' ', array_filter([
            $make,
            $model,
            $generation,
            $years !== '' ? "({$years})" : null,
            $body,
        ]))));

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

    protected function getCatalogMinPrice($products): int
    {
        $min = $products
            ->pluck('price')
            ->filter(fn($price) => $price !== null && $price !== '')
            ->map(fn($price) => (int)$price)
            ->filter(fn($price) => $price > 0)
            ->min();

        return (int)($min ?? 0);
    }

    protected function resolveCatalogImage($car, $products): string
    {
        $carImage = (string)($car->image ?? '');
        if ($carImage !== '') {
            return $this->toPublicUrl($carImage);
        }

        $firstProduct = $products->first();
        if ($firstProduct) {
            return $this->resolveImage($car, $firstProduct);
        }

        return '';
    }

    protected function buildName($car, $product): string
    {
        $make  = (string)($car->car_model->car_make->title ?? '');
        $model = (string)($car->car_model->title ?? '');

        $generation = trim((string)($car->generation ?? ''));
        $years = trim((string)($car->years ?? ''));
        $body = $this->bodyWithDoors($car);

        $parts = array_filter([
            trim((string)($product->title ?? '')),
            trim($make),
            trim($model),
            $generation,
            $years !== '' ? "({$years})" : null,
            $body,
        ], fn($v) => $v !== null && trim((string)$v) !== '');

        return trim(preg_replace('/\s+/u', ' ', implode(' ', $parts)));
    }

    protected function buildUrl($car): string
    {
        return url(sprintf(
            '/katalog/%s/%s/%s',
            $car->car_model->car_make->slug,
            $car->car_model->slug,
            $car->slug
        ));
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

    protected function resolveImage($car, $product): string
    {
        $img = $product->pivot->image ?? null;
        if (!empty($img)) {
            return $this->toPublicUrl($img);
        }

        $img = $product->image ?? null;
        if (!empty($img)) {
            return $this->toPublicUrl($img);
        }

        return $this->defaultProductImage($product);
    }

    protected function toPublicUrl(string $path): string
    {
        $path = trim($path);

        if (preg_match('~^https?://~i', $path)) {
            return $path;
        }

        $path = ltrim($path, '/');

        if (str_starts_with($path, 'storage/uploads/')) {
            return asset($path);
        }

        if (str_starts_with($path, 'products_default/')) {
            return asset('storage/' . $path);
        }

        if (str_starts_with($path, 'storage/')) {
            return asset($path);
        }

        if (str_starts_with($path, 'images/')) {
            return asset($path);
        }

        return asset('storage/' . $path);
    }

    protected function defaultProductImage($product): string
    {
        $t = mb_strtolower(trim((string)($product->title ?? '')));

        if (str_contains($t, 'лонжерон')) {
            return asset('storage/products_default/lonzeron.png');
        }

        if (str_contains($t, 'торцев') || str_contains($t, 'заглушк')) {
            return asset('storage/products_default/torcevaia-zagluska.jpeg');
        }

        if (str_contains($t, 'ремкомплект') && str_contains($t, 'пола')) {
            return asset('storage/products_default/remkomplekt-pola.jpeg');
        }

        if (str_contains($t, 'усилител') || str_contains($t, 'соединител')) {
            return asset('storage/products_default/usilitel-soedinitel-porogov.png');
        }

        if (str_contains($t, 'пенк') || str_contains($t, 'пена')) {
            if (str_contains($t, 'багаж')) {
                return asset('storage/products_default/penka-bagaznika.jpg');
            }
            if (str_contains($t, 'перед') && str_contains($t, 'двер')) {
                return asset('storage/products_default/penka-perednei-dveri.jpg');
            }
            if (str_contains($t, 'зад') && str_contains($t, 'двер')) {
                return asset('storage/products_default/penka-zadnei-dveri.jpg');
            }
            return asset('storage/products_default/penka-bagaznika.jpg');
        }

        if (str_contains($t, 'арка')) {
            if (str_contains($t, 'карман') && str_contains($t, 'зад')) {
                return asset('storage/products_default/arka-karman-zadniaia.jpg');
            }
            if (str_contains($t, 'перед')) {
                return asset('storage/products_default/arka-peredniaia.jpg');
            }
            if (str_contains($t, 'внутрен') && str_contains($t, 'универс')) {
                return asset('storage/products_default/arka-vnutrenniaia-universalnaia.jpeg');
            }
            if (str_contains($t, 'внутрен')) {
                return asset('storage/products_default/arka-vnutrenniaia.jpeg');
            }
            return asset('storage/products_default/arka-zadniaia.jpg');
        }

        if (str_contains($t, 'порог')) {
            return asset('storage/products_default/porog.png');
        }

        return asset('storage/products_default/porog.png');
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
            return 2;
        }

        return 1;
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

        return 'Собственное производство.1–1.5 мм. ХКС и Цинк. Оплата при получении.';
    }
}
