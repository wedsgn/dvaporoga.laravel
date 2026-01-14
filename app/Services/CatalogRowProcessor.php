<?php

namespace App\Services;

use App\Jobs\CarImageDownloadJob;
use App\Jobs\ProductImageDownloadJob;
use App\Models\Car;
use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\Product;
use App\Models\ImportRun;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;

class CatalogRowProcessor
{
  public function processRow(ImportRun $run, array $row, array $detailColumns, array $ctx = []): void
  {
    $excelRow = $ctx['excel_row'] ?? null;
    $dataRow  = $ctx['data_row'] ?? null;

    $photoUrl   = trim((string)($row[0] ?? ''));
    $makeTitle  = trim((string)($row[1] ?? ''));
    $modelTitle = trim((string)($row[2] ?? ''));
    $genRaw     = trim((string)($row[3] ?? ''));

    $years = trim((string)($row[4] ?? ''));
    $body  = trim((string)($row[5] ?? ''));

    if ($makeTitle === '' && $modelTitle === '' && $genRaw === '' && $years === '' && $body === '') {
      return;
    }

    $makeKey  = $this->normKey($makeTitle);
    $modelKey = $this->normKey($modelTitle);
    $genKey   = $this->normKey($genRaw);

    if ($makeKey === '' || $modelKey === '' || $genKey === '') {
      ImportLogger::warn($run, "Строка пропущена: пустые марка/модель/поколение (после нормализации)", [
        'excel_row' => $excelRow,
        'data_row'  => $dataRow,
        'raw_make'  => $makeTitle,
        'raw_model' => $modelTitle,
        'raw_generation' => $genRaw,
        'years' => $years,
        'body'  => $body,
        'norm_make' => $makeKey,
        'norm_model' => $modelKey,
        'norm_generation' => $genKey,
      ]);
      return;
    }

    $generation = $this->formatGeneration($genRaw);

    $makeSlug  = Str::slug($makeTitle);
    $modelSlug = Str::slug($modelTitle);

    $carTitle = trim(preg_replace(
      '~\s+~u',
      ' ',
      trim($makeTitle . ' ' . $modelTitle . ' ' . $body . ' ' . $generation)
    ));
    $carSlug = Str::slug($carTitle);


    $carKey = $this->normKey(trim($makeTitle . ' ' . $modelTitle . ' ' . $body . ' ' . $generation));


    $make = $this->getOrCreateMake($run, $makeTitle, $makeSlug, $makeKey);


    $model = $this->getOrCreateModel($run, $make, $modelTitle, $modelSlug, $modelKey);


    [$car, $created] = $this->getOrCreateCar(
      $run,
      $model,
      $carTitle,
      $carSlug,
      $carKey,
      $years,
      $body,
      $generation
    );

    $carPhotoUrl = $this->normalizeUrl($photoUrl);
    if ($carPhotoUrl && $carPhotoUrl !== '1') {
      $cur = (string)($car->image ?? '');
      $need = false;

      if ($cur === '') $need = true;
      else if (preg_match('~^https?://~i', $cur)) $need = true;
      else {
        if (!Storage::disk('public')->exists(ltrim($cur, '/'))) {
          $need = true;
        }
      }

      if ($need) {
        CarImageDownloadJob::dispatch(
          $run->id,
          $car->id,
          $model->id,
          $carPhotoUrl
        )->onQueue('imports-images');
      }
    }

    $sync = [];
    $imageJobs = [];

    foreach ($detailColumns as $colIndex => $detailTitle) {
      $valRaw = (string)($row[$colIndex] ?? '');
      $val = trim($valRaw);

      if ($val === '') continue;

      $has = false;
      $photoUrl = null;

      if ($val === '1') {
        $has = true;
      } else {
        $maybe = $this->normalizeUrl($val);
        if ($maybe) {
          $has = true;
          $photoUrl = $maybe;
        }
      }

      if (!$has) continue;

      $product = $this->getOrCreateBaseProductByTitle($run, (string)$detailTitle);

      $sync[(int)$product->id] = [
        'image' => null,
        'image_mob' => null,
      ];

      if ($photoUrl) {
        $imageJobs[] = [
          'run_id' => (int)$run->id,
          'car_id' => (int)$car->id,
          'product_id' => (int)$product->id,
          'url' => $photoUrl,
        ];
      }
    }

    $car->products()->sync($sync);

    foreach ($imageJobs as $job) {
      ProductImageDownloadJob::dispatch(
        $job['run_id'],
        $job['car_id'],
        $job['product_id'],
        $job['url']
      )->onQueue('imports-images');
    }

    ImportLogger::info($run, 'Прогресс: строка обработана', [
      'excel_row' => $excelRow,
      'make' => $make->title ?? null,
      'model' => $model->title ?? null,
      'car_id' => $car->id ?? null,
      'created' => $created,
    ]);
  }


  private function getOrCreateMake(ImportRun $run, string $title, string $slug, string $normKey): CarMake
  {
    $make = CarMake::query()
      ->when(Schema::hasColumn('car_makes', 'norm_key'), fn($q) => $q->where('norm_key', $normKey))
      ->orderBy('id')
      ->first();

    if (!$make) {
      $make = CarMake::query()->where('slug', $slug)->orderBy('id')->first();
    }

    if ($make) {
      $upd = [];

      if (Schema::hasColumn('car_makes', 'norm_key') && empty($make->norm_key)) {
        $upd['norm_key'] = $normKey;
      }

      if (Schema::hasColumn('car_makes', 'last_import_run_id')) {
        if ((int)($make->last_import_run_id ?? 0) !== (int)$run->id) {
          $upd['last_import_run_id'] = (int)$run->id;
        }
      }

      if ($upd) {
        $this->safeUpdateById($make->getTable(), (int)$make->id, $upd);
        foreach ($upd as $k => $v) $make->{$k} = $v;
      }

      return $make;
    }

    $data = [
      'title' => $title,
      'slug'  => $slug,
    ];

    if (Schema::hasColumn('car_makes', 'norm_key')) $data['norm_key'] = $normKey;
    if (Schema::hasColumn('car_makes', 'description')) $data['description'] = '';
    if (Schema::hasColumn('car_makes', 'is_published')) $data['is_published'] = true;
    if (Schema::hasColumn('car_makes', 'last_import_run_id')) $data['last_import_run_id'] = (int)$run->id;

    return CarMake::create($data);
  }

  private function getOrCreateModel(ImportRun $run, CarMake $make, string $title, string $slug, string $normKey): CarModel
  {
    $makeId = (int)$make->id;

    $model = CarModel::query()
      ->where('car_make_id', $makeId)
      ->when(Schema::hasColumn('car_models', 'norm_key'), fn($q) => $q->where('norm_key', $normKey))
      ->orderBy('id')
      ->first();

    if (!$model) {
      $model = CarModel::query()
        ->where('car_make_id', $makeId)
        ->where('slug', $slug)
        ->orderBy('id')
        ->first();
    }

    if ($model) {
      $upd = [];

      if (Schema::hasColumn('car_models', 'norm_key') && empty($model->norm_key)) {
        $upd['norm_key'] = $normKey;
      }

      if (Schema::hasColumn('car_models', 'last_import_run_id')) {
        if ((int)($model->last_import_run_id ?? 0) !== (int)$run->id) {
          $upd['last_import_run_id'] = (int)$run->id;
        }
      }

      if ($upd) {
        $this->safeUpdateById($model->getTable(), (int)$model->id, $upd);
        foreach ($upd as $k => $v) $model->{$k} = $v;
      }

      return $model;
    }

    if (CarModel::query()->where('slug', $slug)->exists()) {
      $base = Str::slug($title . '-' . ($make->slug ?: $make->title));
      $slug = $this->makeUniqueSlugForCarModels($base);
    }

    $data = [
      'car_make_id' => $makeId,
      'title' => $title,
      'slug'  => $slug,
    ];

    if (Schema::hasColumn('car_models', 'norm_key')) $data['norm_key'] = $normKey;
    if (Schema::hasColumn('car_models', 'description')) $data['description'] = '';
    if (Schema::hasColumn('car_models', 'is_published')) $data['is_published'] = true;
    if (Schema::hasColumn('car_models', 'last_import_run_id')) $data['last_import_run_id'] = (int)$run->id;
    try {
      return CarModel::create($data);
    } catch (QueryException $e) {
      // Гонка между воркерами: параллельные чанки могут одновременно создать один и тот же slug.
      // В этом случае просто читаем уже вставленную запись и продолжаем импорт.
      if ((string)$e->getCode() === '23505') {
        $model = CarModel::query()->where('slug', $slug)->orderBy('id')->first();
        if ($model) return $model;
      }
      throw $e;
    }
  }

  private function getOrCreateCar(
    ImportRun $run,
    CarModel $model,
    string $title,
    string $slug,
    string $normKey,
    string $years,
    string $body,
    string $generation
  ): array {
    $modelId = (int)$model->id;

    $car = Car::query()
      ->where('car_model_id', $modelId)
      ->when(Schema::hasColumn('cars', 'norm_key'), fn($q) => $q->where('norm_key', $normKey))
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
      $upd = [];

      if (Schema::hasColumn('cars', 'norm_key') && empty($car->norm_key)) {
        $upd['norm_key'] = $normKey;
      }

      if (Schema::hasColumn('cars', 'generation') && empty($car->generation) && $generation !== '') {
        $upd['generation'] = $generation;
      }

      if (Schema::hasColumn('cars', 'years') && empty($car->years) && $years !== '') {
        $upd['years'] = $years;
      }

      if (Schema::hasColumn('cars', 'body') && empty($car->body) && $body !== '') {
        $upd['body'] = $body;
      }

      if (Schema::hasColumn('cars', 'last_import_run_id')) {
        if ((int)($car->last_import_run_id ?? 0) !== (int)$run->id) {
          $upd['last_import_run_id'] = (int)$run->id;
        }
      }

      if ($upd) {
        $this->safeUpdateById($car->getTable(), (int)$car->id, $upd);
        foreach ($upd as $k => $v) $car->{$k} = $v;
      }

      return [$car, false];
    }

    $data = [
      'car_model_id' => $modelId,
      'title' => $title,
      'slug'  => $slug,
    ];

    if (Schema::hasColumn('cars', 'norm_key')) $data['norm_key'] = $normKey;
    if (Schema::hasColumn('cars', 'description')) $data['description'] = '';
    if (Schema::hasColumn('cars', 'is_published')) $data['is_published'] = true;
    if (Schema::hasColumn('cars', 'years')) $data['years'] = $years !== '' ? $years : null;
    if (Schema::hasColumn('cars', 'body'))  $data['body']  = $body  !== '' ? $body  : null;
    if (Schema::hasColumn('cars', 'generation')) $data['generation'] = $generation !== '' ? $generation : null;
    if (Schema::hasColumn('cars', 'last_import_run_id')) $data['last_import_run_id'] = (int)$run->id;

    $car = Car::create($data);

    return [$car, true];
  }


private function getOrCreateBaseProductByTitle(ImportRun $run, string $title): Product
{
    $title = trim($title);
    $normKey = $this->normKey($title);
    $slug = ($normKey === 'порог') ? 'porog' : Str::slug($title);

    // 1) Ищем базовую деталь (car_id NULL) включая soft-deleted
    $q = Product::withTrashed();

    if (Schema::hasColumn('products', 'car_id')) {
        $q->whereNull('car_id');
    }

    if (Schema::hasColumn('products', 'norm_key')) {
        $q->where('norm_key', $normKey);
    } else {
        $q->where('slug', $slug);
    }

    /** @var Product|null $p */
    $p = $q->orderBy('id')->first();

    // 2) Фолбэк по slug (всё ещё базовая, car_id NULL)
    if (!$p) {
        $q2 = Product::withTrashed()->where('slug', $slug);
        if (Schema::hasColumn('products', 'car_id')) $q2->whereNull('car_id');
        $p = $q2->orderBy('id')->first();
    }

    // 3) ЖЁСТКИЙ фолбэк: slug уникален глобально, поэтому ищем без car_id-ограничений
    if (!$p) {
        $p = Product::withTrashed()
            ->where('slug', $slug)
            ->orderBy('id')
            ->first();
    }

    // 4) Если нашли — восстановить/обновить и вернуть
    if ($p) {
        if (method_exists($p, 'restore') && !is_null($p->deleted_at)) {
            $p->restore();
        }

        $upd = [];

        if (Schema::hasColumn('products', 'norm_key') && empty($p->norm_key)) {
            $upd['norm_key'] = $normKey;
        }

        // ВАЖНО: если нашли "не базовую" (car_id != null), а ты хочешь именно базовую деталь:
        // можешь раскомментировать, чтобы "перевести" её в базовую.
        // Но это решение спорное — я бы так делал только если ты уверен, что lonzeron всегда общий.
        /*
        if (Schema::hasColumn('products', 'car_id') && !is_null($p->car_id)) {
            $upd['car_id'] = null;
        }
        */

        if (Schema::hasColumn('products', 'last_import_run_id')) {
            $upd['last_import_run_id'] = (int)$run->id;
        }

        if (empty($p->image)) {
            $def = $this->resolveDefaultProductImage($title);
            if ($def) $upd['image'] = $def;
        }

        if ($upd) {
            $this->safeUpdateById($p->getTable(), (int)$p->id, $upd);
            foreach ($upd as $k => $v) $p->{$k} = $v;
        }

        return $p;
    }

    // 5) Если не нашли — создаём
    $data = [
        'title' => $title,
        'slug'  => $slug,
    ];

    if (Schema::hasColumn('products', 'norm_key')) $data['norm_key'] = $normKey;
    if (Schema::hasColumn('products', 'description')) $data['description'] = '';
    if (Schema::hasColumn('products', 'is_published')) $data['is_published'] = true;
    if (Schema::hasColumn('products', 'last_import_run_id')) $data['last_import_run_id'] = (int)$run->id;

    $def = $this->resolveDefaultProductImage($title);
    if ($def) $data['image'] = $def;

    // 6) Подстраховка от гонки
    try {
        return Product::create($data);
    } catch (\Illuminate\Database\QueryException $e) {
        $sqlState = $e->errorInfo[0] ?? null;
        if ($sqlState === '23505') {
            $p = Product::withTrashed()->where('slug', $slug)->orderBy('id')->first();
            if ($p) {
                if (method_exists($p, 'restore') && !is_null($p->deleted_at)) {
                    $p->restore();
                }
                return $p;
            }
        }
        throw $e;
    }
}



  private function resolveDefaultProductImage(string $productTitle): ?string
  {
    $baseDir = storage_path('app/products_defolt');

    $candidates = [
      $productTitle,
      $this->normKey($productTitle),
      Str::slug($productTitle),
    ];

    $exts = ['jpg', 'jpeg', 'png', 'webp'];

    foreach ($candidates as $name) {
      $name = trim((string)$name);
      if ($name === '') continue;

      foreach ($exts as $ext) {
        $src = $baseDir . DIRECTORY_SEPARATOR . $name . '.' . $ext;
        if (!is_file($src)) continue;

        $destRel = 'products_default/' . Str::slug($productTitle) . '.' . $ext;
        $destAbs = storage_path('app/public/' . $destRel);

        if (!is_file($destAbs)) {
          @mkdir(dirname($destAbs), 0775, true);
          @copy($src, $destAbs);
        }

        return $destRel;
      }
    }

    return null;
  }


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


  private function normKey(string $s): string
  {
    $s = trim(mb_strtolower($s));
    $s = str_replace(['ё'], ['е'], $s);
    $s = preg_replace('~\s+~u', ' ', $s);
    $s = preg_replace('~[^\p{L}\p{N}\s\-]+~u', '', $s);
    return trim($s);
  }

  private function normalizeUrl(string $raw): ?string
  {
    $raw = trim($raw);
    if ($raw === '' || $raw === '1') return null;

    if (preg_match('~^https?://~i', $raw)) return $raw;

    if (!preg_match('~\s~u', $raw) && str_contains($raw, '.') && str_contains($raw, '/')) {
      return 'https://' . ltrim($raw, '/');
    }

    return null;
  }


  private function formatGeneration(string $raw): string
  {
    $raw = trim($raw);
    if ($raw === '') return '';

    if (preg_match('~^\d+$~', $raw)) {
      return $raw . ' поколение';
    }

    return $raw;
  }
}
