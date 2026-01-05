<?php

namespace App\Services;

use App\Models\Car;
use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\Product;
use App\Models\ImportRun;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class CatalogRowProcessor
{
  public function processRow(ImportRun $run, array $row, array $detailColumns, array $ctx = []): void
  {
    // Excel data row number (0-based) / excel row number (1-based) for logs
    $excelRow = $ctx['excel_row'] ?? null;

    $photoUrl   = trim((string)($row[0] ?? ''));
    $makeTitle  = trim((string)($row[1] ?? ''));
    $modelTitle = trim((string)($row[2] ?? ''));
    $genTitle   = trim((string)($row[3] ?? ''));

    $years = trim((string)($row[4] ?? ''));
    $body  = trim((string)($row[5] ?? ''));

    // пустые строки пропускаем молча
    if ($makeTitle === '' && $modelTitle === '' && $genTitle === '') {
      return;
    }

    $makeKey  = $this->normKey($makeTitle);
    $modelKey = $this->normKey($modelTitle);
    $genKey   = $this->normKey($genTitle);

    if ($makeKey === '' || $modelKey === '' || $genKey === '') {
      $rMake = isset($row[1]) ? (string)$row[1] : '';
      $rModel = isset($row[2]) ? (string)$row[2] : '';
      $rGen = isset($row[3]) ? (string)$row[3] : '';

      ImportLogger::warn(
        $run,
        "Пропуск строки Excel #{$excelRow}: пустые марка/модель/поколение. RAW: make='{$rMake}' model='{$rModel}' gen='{$rGen}'",
        [
          'excel_row' => $excelRow,
          'raw_make' => $rMake,
          'raw_model' => $rModel,
          'raw_generation' => $rGen,
          'trim_make' => $makeTitle,
          'trim_model' => $modelTitle,
          'trim_generation' => $genTitle,
          'norm_make' => $makeKey,
          'norm_model' => $modelKey,
          'norm_generation' => $genKey,
        ]
      );
      return;
    }

    $makeSlug  = Str::slug($makeTitle);
    $modelSlug = Str::slug($modelTitle);
    $generation = $genTitle;

    $carTitle = trim($makeTitle . ' ' . $modelTitle . ' ' . $body . ' ' . $generation);
    $carSlug  = Str::slug($carTitle);
    $carKey = $this->normKey(trim($makeTitle . ' ' . $modelTitle . ' ' . $generation));

    // 1) Марка
    $make = $this->getOrCreateMake($makeTitle, $makeSlug, $makeKey);

    // 2) Модель (учитываем глобальный unique по slug)
    $model = $this->getOrCreateModel($make, $modelTitle, $modelSlug, $modelKey);

    // 3) Поколение (Car)
    $car = $this->getOrCreateCar($model, $carTitle, $carSlug, $carKey, $years, $body, $generation);

    // 4) Фото: если URL и не "1"
    $localImagePath = $this->downloadCarImageIfNeeded($photoUrl, $make->slug, $model->slug, $car->slug);
    if ($localImagePath) {
      $carImage = (string)($car->image ?? '');
      if ($carImage === '' || str_starts_with($carImage, 'http')) {
        $this->safeUpdateById($car->getTable(), $car->id, ['image' => $localImagePath]);
      }

      $modelImage = (string)($model->image ?? '');
      if ($modelImage === '' || str_starts_with($modelImage, 'http')) {
        $this->safeUpdateById($model->getTable(), $model->id, ['image' => $localImagePath]);
      }
    }

    // 5) Детали: если 1 — создать связь car <-> product
    foreach ($detailColumns as $colIndex => $detailTitle) {
      $val = trim((string)($row[$colIndex] ?? ''));
      if ($val !== '1') continue;

      $prodKey  = $this->normKey($detailTitle);
      $prodSlug = Str::slug($detailTitle);

      $product = $this->getOrCreateProduct($detailTitle, $prodSlug, $prodKey);

      // связь без дублей
      $car->products()->syncWithoutDetaching([$product->id]);
    }

    // Логируем не каждую строку (иначе логов будет миллионы)
    // Например, раз в 200 строк:
    if (isset($ctx['data_row']) && ($ctx['data_row'] % 200 === 0)) {
      ImportLogger::info($run, 'Прогресс: строка обработана', [
        'excel_row' => $excelRow,
        'make' => $make->title ?? null,
        'model' => $model->title ?? null,
        'car' => $car->title ?? null,
      ]);
    }
  }

  /** -------------------- UPSERTS -------------------- */

  private function getOrCreateMake(string $title, string $slug, string $normKey): CarMake
  {
    // 1) по norm_key
    $make = CarMake::query()->where('norm_key', $normKey)->orderBy('id')->first();

    // 2) по slug
    if (!$make) {
      $make = CarMake::query()->where('slug', $slug)->orderBy('id')->first();
    }

    // 3) если нашли и norm_key пустой — заполняем
    if ($make) {
      if (empty($make->norm_key)) {
        $this->safeUpdateById($make->getTable(), $make->id, ['norm_key' => $normKey]);
        $make->norm_key = $normKey;
      }
      return $make;
    }

    // 4) создаём
    $data = [
      'title'    => $title,
      'slug'     => $slug,
      'norm_key' => $normKey,
    ];

    if (Schema::hasColumn('car_makes', 'description')) $data['description'] = '';
    if (Schema::hasColumn('car_makes', 'is_published')) $data['is_published'] = true;

    return CarMake::create($data);
  }

  private function getOrCreateModel(CarMake $make, string $title, string $slug, string $normKey): CarModel
  {
    $makeId = $make->id;

    // 1) по norm_key в рамках марки
    $model = CarModel::query()
      ->where('car_make_id', $makeId)
      ->where('norm_key', $normKey)
      ->orderBy('id')
      ->first();

    // 2) по slug в рамках марки
    if (!$model) {
      $model = CarModel::query()
        ->where('car_make_id', $makeId)
        ->where('slug', $slug)
        ->orderBy('id')
        ->first();
    }

    // 3) нашли — заполнить norm_key если пустой
    if ($model) {
      if (empty($model->norm_key)) {
        $this->safeUpdateById($model->getTable(), $model->id, ['norm_key' => $normKey]);
        $model->norm_key = $normKey;
      }
      return $model;
    }

    // 4) конфликт глобального unique slug: если slug уже занят другой маркой
    if (CarModel::query()->where('slug', $slug)->exists()) {
      $base = $slug . '-' . ($make->slug ?: Str::slug($make->title ?? (string)$makeId));
      $slug = $this->makeUniqueSlugForCarModels($base);
    }

    // 5) создаём
    $data = [
      'car_make_id' => $makeId,
      'title'       => $title,
      'slug'        => $slug,
      'norm_key'    => $normKey,
    ];

    if (Schema::hasColumn('car_models', 'description')) $data['description'] = '';
    if (Schema::hasColumn('car_models', 'is_published')) $data['is_published'] = true;

    return CarModel::create($data);
  }

  private function getOrCreateCar(
    CarModel $model,
    string $title,
    string $slug,
    string $normKey,
    string $years,
    string $body,
    string $generation
  ): Car {
    $modelId = $model->id;

    // 1) norm_key в рамках модели
    $car = Car::query()
      ->where('car_model_id', $modelId)
      ->where('norm_key', $normKey)
      ->orderBy('id')
      ->first();

    // 2) slug в рамках модели
    if (!$car) {
      $car = Car::query()
        ->where('car_model_id', $modelId)
        ->where('slug', $slug)
        ->orderBy('id')
        ->first();
    }

    if ($car) {
      if (empty($car->norm_key)) {
        $this->safeUpdateById($car->getTable(), $car->id, ['norm_key' => $normKey]);
        $car->norm_key = $normKey;
      }

      $upd = [];

      if (Schema::hasColumn('cars', 'generation') && empty($car->generation) && $generation !== '') {
        $upd['generation'] = $generation;
      }

      if (Schema::hasColumn('cars', 'years') && empty($car->years) && $years !== '') $upd['years'] = $years;
      if (Schema::hasColumn('cars', 'body') && empty($car->body) && $body !== '') $upd['body'] = $body;

      if ($upd) $this->safeUpdateById($car->getTable(), $car->id, $upd);

      return $car;
    }

    // если у cars тоже глобальный unique slug — можно включить такую же уникализацию (пока не включаю)
    // if (Car::where('slug',$slug)->exists()) { ... }

    $data = [
      'car_model_id' => $modelId,
      'title'        => $title,
      'slug'         => $slug,
      'norm_key'     => $normKey,
    ];

    if (Schema::hasColumn('cars', 'description')) $data['description'] = '';
    if (Schema::hasColumn('cars', 'is_published')) $data['is_published'] = true;
    if (Schema::hasColumn('cars', 'years')) $data['years'] = $years !== '' ? $years : null;
    if (Schema::hasColumn('cars', 'body'))  $data['body']  = $body  !== '' ? $body  : null;
    if (Schema::hasColumn('cars', 'generation')) {
      $data['generation'] = $generation;
    }
    return Car::create($data);
  }

  private function getOrCreateProduct(string $title, string $slug, string $normKey): Product
  {
    // 1) norm_key
    $p = Product::query()->where('norm_key', $normKey)->orderBy('id')->first();

    // 2) slug
    if (!$p) {
      $p = Product::query()->where('slug', $slug)->orderBy('id')->first();
    }

    if ($p) {
      if (empty($p->norm_key)) {
        $this->safeUpdateById($p->getTable(), $p->id, ['norm_key' => $normKey]);
        $p->norm_key = $normKey;
      }
      return $p;
    }

    // если products.slug тоже уникален глобально, возможны конфликты; пока оставим как есть

    $data = [
      'title'    => $title,
      'slug'     => $slug,
      'norm_key' => $normKey,
    ];
    if (Schema::hasColumn('products', 'description')) $data['description'] = '';
    if (Schema::hasColumn('products', 'is_published')) $data['is_published'] = true;

    return Product::create($data);
  }

  /** -------------------- SLUG UNIQUE HELPERS -------------------- */

  private function makeUniqueSlugForCarModels(string $base): string
  {
    $slug = $base;
    $n = 2;

    while (CarModel::query()->where('slug', $slug)->exists()) {
      $slug = $base . '-' . $n;
      $n++;
      if ($n > 2000) {
        $slug = $base . '-' . Str::random(6);
        break;
      }
    }

    return $slug;
  }

  /** -------------------- IMAGE -------------------- */

  private function downloadCarImageIfNeeded(string $value, string $makeSlug, string $modelSlug, string $carSlug): ?string
  {
    $value = trim($value);

    if ($value === '' || $value === '1') return null;
    if (!preg_match('~^https?://~i', $value)) return null;

    try {
      $resp = Http::timeout(25)
        ->retry(2, 500)
        ->withHeaders([
          'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120 Safari/537.36',
          'Accept' => 'image/avif,image/webp,image/apng,image/*,*/*;q=0.8',
        ])
        ->withOptions(['allow_redirects' => true])
        ->get($value);

      if (!$resp->successful()) {
        return null; // не критично
      }


      $bytes = $resp->body();
      if (!$bytes || strlen($bytes) < 200) {
        return null;
      }

      $dir  = "uploads/cars/{$makeSlug}/{$modelSlug}/{$carSlug}";
      $file = Str::random(12) . ".webp";
      $path = "{$dir}/{$file}";

      $img = Image::read($bytes)->toWebp(80);
      Storage::disk('public')->put($path, (string)$img);

      return $path;
    } catch (\Throwable $e) {
      // фото не должно валить импорт
      return null;
    }
  }

  /** -------------------- SAFE UPDATE (без fillable проблем) -------------------- */

  private function safeUpdateById(string $table, int $id, array $data): void
  {
    $filtered = [];
    foreach ($data as $k => $v) {
      if (Schema::hasColumn($table, $k)) {
        $filtered[$k] = $v;
      }
    }
    if (!$filtered) return;

    DB::table($table)->where('id', $id)->update($filtered);
  }

  /** -------------------- NORMALIZE -------------------- */

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
