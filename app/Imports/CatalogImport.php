<?php

namespace App\Imports;

use App\Models\Car;
use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;

// Если в проекте есть Intervention Image (Laravel Facade):
use Intervention\Image\Laravel\Facades\Image;

class CatalogImport implements ToCollection
{
  /**
   * В файле 2 строки заголовков:
   *  - строка 1: группы (Порог/Арки/Пенки)
   *  - строка 2: конкретные колонки деталей
   *
   * Значит данные начинаются с 3-й строки.
   */
  private int $skipRows = 2;

  /**
   * Индексы колонок в xlsx:
   * 0 фото
   * 1 марка
   * 2 модель
   * 3 поколение
   * 4 годы
   * 5 кузов
   * 6..14 детали (9 колонок)
   */
  private int $detailsStartIndex = 6;
  private int $detailsEndIndex   = 14;

  /** @var array<int,string> [colIndex => detailTitle] */
  private array $detailColumns = [];

  public function collection(Collection $rows)
  {
    if ($rows->count() <= $this->skipRows) {
      throw new \RuntimeException('Файл пустой или без данных (меньше 3 строк).');
    }

    // 2-я строка заголовков (index=1) — в ней названия "дочек" деталей
    $headerRow2 = $rows->get(1);
    $this->buildDetailColumns($headerRow2);

    DB::transaction(function () use ($rows) {

      // Удаляем старую логику "у всех машин есть все детали"
      // (если FK не позволит truncate — delete() безопаснее)
      DB::table('car_product')->delete();

      for ($i = $this->skipRows; $i < $rows->count(); $i++) {
        $row = $rows->get($i);

        $photoUrl   = trim((string)($row[0] ?? ''));
        $makeTitle  = trim((string)($row[1] ?? ''));
        $modelTitle = trim((string)($row[2] ?? ''));
        $genTitle   = trim((string)($row[3] ?? ''));

        // Доп. поля (если у тебя они реально есть в таблице cars)
        $years = trim((string)($row[4] ?? ''));
        $body  = trim((string)($row[5] ?? ''));

        // Пропускаем пустые строки
        if ($makeTitle === '' && $modelTitle === '' && $genTitle === '') {
          continue;
        }

        // Нормализация ключей
        $makeKey  = $this->normKey($makeTitle);
        $modelKey = $this->normKey($modelTitle);
        $genKey   = $this->normKey($genTitle);

        if ($makeKey === '' || $modelKey === '' || $genKey === '') {
          Log::warning('Import: row skipped due empty make/model/gen after normalize', [
            'row' => $i + 1,
            'make' => $makeTitle,
            'model' => $modelTitle,
            'gen' => $genTitle,
          ]);
          continue;
        }

        // 1) Марка
        $makeSlug = Str::slug($makeTitle);
        $make = $this->getOrCreateCarMake($makeTitle, $makeSlug, $makeKey);

        // 2) Модель
        $modelSlug = Str::slug($modelTitle);
        $model = $this->getOrCreateCarModel($make->id, $modelTitle, $modelSlug, $modelKey);

        // 3) Поколение (Car)
        $carSlug = Str::slug($genTitle);
        $car = $this->getOrCreateCar($model->id, $genTitle, $carSlug, $genKey, $years, $body);

        // 4) Фото: если URL — скачиваем, если пусто/1 — не трогаем
        $localImagePath = $this->downloadCarImageIfNeeded($photoUrl, $makeSlug, $modelSlug, $carSlug);

        if ($localImagePath) {
          // по умолчанию: фото на поколение
          $this->safeUpdateModel($car, ['image' => $localImagePath]);

          // если у модели пусто — можно заполнить тем же
          if (empty($model->image)) {
            $this->safeUpdateModel($model, ['image' => $localImagePath]);
          }
        }

        // 5) Детали: связь только если значение == 1
        foreach ($this->detailColumns as $colIndex => $detailTitle) {
          $val = trim((string)($row[$colIndex] ?? ''));

          if ($val !== '1') {
            continue;
          }

          $productKey  = $this->normKey($detailTitle);
          $productSlug = Str::slug($detailTitle);

          $product = $this->getOrCreateProduct($detailTitle, $productSlug, $productKey);

          $car->products()->syncWithoutDetaching([$product->id]);
        }
      }
    });
  }

  private function buildDetailColumns($headerRow2): void
  {
    $this->detailColumns = [];

    for ($i = $this->detailsStartIndex; $i <= $this->detailsEndIndex; $i++) {
      $title = trim((string)($headerRow2[$i] ?? ''));
      if ($title === '') continue;

      $this->detailColumns[$i] = $title;
    }

    if (empty($this->detailColumns)) {
      throw new \RuntimeException('Не найдены колонки деталей (проверь структуру Excel).');
    }
  }

  /**
   * ЛОГИКА: сначала norm_key, потом slug; если нашли — и norm_key пустой, заполняем.
   */
  private function getOrCreateCarMake(string $title, string $slug, string $normKey): CarMake
  {
    $make = CarMake::query()
      ->where('norm_key', $normKey)
      ->orderBy('id')
      ->first();

    if (!$make) {
      $make = CarMake::query()
        ->where('slug', $slug)
        ->orderBy('id')
        ->first();
    }

    if ($make) {
      if (empty($make->norm_key)) {
        $this->safeUpdateModel($make, ['norm_key' => $normKey]);
      }
      return $make;
    }

    $data = [
      'title'       => $title,
      'slug'        => $slug,
      'norm_key'    => $normKey,
    ];

    // обязательные поля (у тебя description NOT NULL)
    if (Schema::hasColumn('car_makes', 'description')) {
      $data['description'] = '';
    }
    if (Schema::hasColumn('car_makes', 'is_published')) {
      $data['is_published'] = true;
    }

    return CarMake::create($data);
  }

  private function getOrCreateCarModel(int $makeId, string $title, string $slug, string $normKey): CarModel
  {
    // 1) Поиск по norm_key в рамках марки
    $model = CarModel::query()
      ->where('car_make_id', $makeId)
      ->where('norm_key', $normKey)
      ->orderBy('id')
      ->first();

    // 2) Поиск по slug в рамках марки
    if (!$model) {
      $model = CarModel::query()
        ->where('car_make_id', $makeId)
        ->where('slug', $slug)
        ->orderBy('id')
        ->first();
    }

    // 3) Если нашли — заполняем norm_key если пусто
    if ($model) {
      if (empty($model->norm_key)) {
        $this->safeUpdateModel($model, ['norm_key' => $normKey]);
      }
      return $model;
    }

    // 4) Если не нашли — проверяем глобальный конфликт slug (уникален во всей таблице)
    $global = CarModel::query()->where('slug', $slug)->first();
    if ($global) {
      // slug уже занят: делаем slug = <modelSlug>-<makeSlug>
      $make = CarMake::query()->find($makeId);
      $makeSlug = $make ? $make->slug : (string)$makeId;

      $base = $slug . '-' . $makeSlug;
      $slug = $this->makeUniqueCarModelSlug($base);
    }

    // 5) Создаём
    $data = [
      'car_make_id' => $makeId,
      'title'       => $title,
      'slug'        => $slug,
      'norm_key'    => $normKey,
    ];

    if (\Illuminate\Support\Facades\Schema::hasColumn('car_models', 'description')) {
      $data['description'] = '';
    }
    if (\Illuminate\Support\Facades\Schema::hasColumn('car_models', 'is_published')) {
      $data['is_published'] = true;
    }

    return CarModel::create($data);
  }

  /**
   * Делает slug уникальным в car_models (если base уже занят).
   * integra-acura, integra-acura-2, integra-acura-3...
   */
  private function makeUniqueCarModelSlug(string $base): string
  {
    $slug = $base;
    $n = 2;

    while (CarModel::query()->where('slug', $slug)->exists()) {
      $slug = $base . '-' . $n;
      $n++;
      if ($n > 2000) {
        // защита от бесконечного цикла при совсем сломанной базе
        $slug = $base . '-' . \Illuminate\Support\Str::random(6);
        break;
      }
    }

    return $slug;
  }


  private function getOrCreateCar(
    int $modelId,
    string $title,
    string $slug,
    string $normKey,
    string $years,
    string $body
  ): Car {
    $car = Car::query()
      ->where('car_model_id', $modelId)
      ->where('norm_key', $normKey)
      ->orderBy('id')
      ->first();

    if (!$car) {
      $car = Car::query()
        ->where('car_model_id', $modelId)
        ->where('slug', $slug)
        ->orderBy('id')
        ->first();
    }

    if ($car) {
      if (empty($car->norm_key)) {
        $this->safeUpdateModel($car, ['norm_key' => $normKey]);
      }

      // Если в таблице есть years/body — можно аккуратно заполнить пустые
      $upd = [];
      if (Schema::hasColumn('cars', 'years') && empty($car->years) && $years !== '') {
        $upd['years'] = $years;
      }
      if (Schema::hasColumn('cars', 'body') && empty($car->body) && $body !== '') {
        $upd['body'] = $body;
      }
      if (!empty($upd)) {
        $this->safeUpdateModel($car, $upd);
      }

      return $car;
    }

    $data = [
      'car_model_id' => $modelId,
      'title'        => $title,
      'slug'         => $slug,
      'norm_key'     => $normKey,
    ];

    if (Schema::hasColumn('cars', 'description')) {
      $data['description'] = '';
    }
    if (Schema::hasColumn('cars', 'is_published')) {
      $data['is_published'] = true;
    }
    if (Schema::hasColumn('cars', 'years')) {
      $data['years'] = $years !== '' ? $years : null;
    }
    if (Schema::hasColumn('cars', 'body')) {
      $data['body'] = $body !== '' ? $body : null;
    }

    return Car::create($data);
  }

  private function getOrCreateProduct(string $title, string $slug, string $normKey): Product
  {
    $product = Product::query()
      ->where('norm_key', $normKey)
      ->orderBy('id')
      ->first();

    if (!$product) {
      $product = Product::query()
        ->where('slug', $slug)
        ->orderBy('id')
        ->first();
    }

    if ($product) {
      if (empty($product->norm_key)) {
        $this->safeUpdateModel($product, ['norm_key' => $normKey]);
      }
      return $product;
    }

    $data = [
      'title'    => $title,
      'slug'     => $slug,
      'norm_key' => $normKey,
    ];

    if (Schema::hasColumn('products', 'description')) {
      $data['description'] = '';
    }
    if (Schema::hasColumn('products', 'is_published')) {
      $data['is_published'] = true;
    }

    return Product::create($data);
  }

  /**
   * Скачиваем внешнюю картинку и сохраняем в storage/public/...
   * Если пусто или "1" — возвращаем null.
   */
  private function downloadCarImageIfNeeded(string $value, string $makeSlug, string $modelSlug, string $carSlug): ?string
  {
    $value = trim($value);

    if ($value === '' || $value === '1') {
      return null;
    }

    if (!preg_match('~^https?://~i', $value)) {
      return null;
    }

    try {
      $resp = Http::timeout(20)->retry(2, 300)->get($value);

      if (!$resp->successful()) {
        Log::warning('Import image: failed http', ['url' => $value, 'status' => $resp->status()]);
        return null;
      }

      $contentType = strtolower((string)$resp->header('Content-Type'));
      if (!str_starts_with($contentType, 'image/')) {
        Log::warning('Import image: not image content-type', ['url' => $value, 'ct' => $contentType]);
        return null;
      }

      $bytes = $resp->body();
      if (!$bytes || strlen($bytes) < 200) {
        return null;
      }

      $dir  = "uploads/cars/{$makeSlug}/{$modelSlug}/{$carSlug}";
      $file = Str::random(12) . ".webp";
      $path = "{$dir}/{$file}";

      // Конвертация в webp (если Intervention подключен)
      $img = Image::read($bytes)->toWebp(80);
      Storage::disk('public')->put($path, (string)$img);

      return $path;
    } catch (\Throwable $e) {
      Log::error('Import image: exception', ['url' => $value, 'e' => $e->getMessage()]);
      return null;
    }
  }

  /**
   * Обновление модели так, чтобы:
   *  - не падать на mass-assignment (если norm_key/description не в fillable)
   *  - и всё равно гарантированно обновлять нужные поля
   *
   * Для этого делаем update через Query Builder по id.
   */
  private function safeUpdateModel($model, array $data): void
  {
    if (!$model || empty($data)) return;

    $table = $model->getTable();

    // отфильтруем только реально существующие колонки (чтобы не ловить SQL ошибки)
    $filtered = [];
    foreach ($data as $k => $v) {
      if (Schema::hasColumn($table, $k)) {
        $filtered[$k] = $v;
      }
    }

    if (empty($filtered)) return;

    DB::table($table)->where('id', $model->id)->update($filtered);

    // подхватим в объект (чтобы дальше код видел актуальные значения)
    foreach ($filtered as $k => $v) {
      $model->{$k} = $v;
    }
  }

  private function normKey(string $s): string
  {
    $s = trim(mb_strtolower($s));
    $s = str_replace(['ё'], ['е'], $s);
    $s = preg_replace('~\s+~u', ' ', $s);
    $s = preg_replace('~[^\p{L}\p{N}\s\-]+~u', '', $s);
    $s = trim($s);

    return $s;
  }
}
