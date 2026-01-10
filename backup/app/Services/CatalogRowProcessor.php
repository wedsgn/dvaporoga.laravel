<?php

namespace App\Services;

use App\Models\Car;
use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\Product;
use App\Models\ImportRun;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CatalogRowProcessor
{
  public function processRow(ImportRun $run, array $row, array $detailColumns, array $ctx = []): void
  {
    $excelRow = $ctx['excel_row'] ?? null;
    $dataRow  = $ctx['data_row'] ?? null;

    // 0..5 = базовые поля
    $photoUrl   = trim((string)($row[0] ?? ''));
    $makeTitle  = trim((string)($row[1] ?? ''));
    $modelTitle = trim((string)($row[2] ?? ''));
    $genRaw     = trim((string)($row[3] ?? ''));

    $years = trim((string)($row[4] ?? ''));
    $body  = trim((string)($row[5] ?? ''));

    // полностью пустые строки (в excel бывают визуальные)
    if ($makeTitle === '' && $modelTitle === '' && $genRaw === '' && $years === '' && $body === '') {
      return;
    }

    // нормализованные ключи для дедупликации
    $makeKey  = $this->normKey($makeTitle);
    $modelKey = $this->normKey($modelTitle);
    $genKey   = $this->normKey($genRaw);

    // если критично пусто — пропускаем, но с понятным логом
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

    // поколение — приводим к нормальному виду
    $generation = $this->formatGeneration($genRaw);

    // slug марка/модель
    $makeSlug  = Str::slug($makeTitle);
    $modelSlug = Str::slug($modelTitle);

    // title/slug машины по схеме:
    // Марка + Модель + Кузов + Поколение
    $carTitle = trim(preg_replace(
      '~\s+~u',
      ' ',
      trim($makeTitle . ' ' . $modelTitle . ' ' . $body . ' ' . $generation)
    ));
    $carSlug = Str::slug($carTitle);

    // norm_key машины (для дедупликации)
    $carKey = $this->normKey(trim($makeTitle . ' ' . $modelTitle . ' ' . $body . ' ' . $generation));

    // 1) Марка
    $make = $this->getOrCreateMake($run, $makeTitle, $makeSlug, $makeKey);

    // 2) Модель
    $model = $this->getOrCreateModel($run, $make, $modelTitle, $modelSlug, $modelKey);

    // 3) Машина
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

    // картинка — как было (job)
    if ($photoUrl !== '' && $photoUrl !== '1' && preg_match('~^https?://~i', $photoUrl)) {
      $cur = (string)($car->image ?? '');
      $need = false;

      if ($cur === '') $need = true;
      else if (preg_match('~^https?://~i', $cur)) $need = true;
      else {
        if (!\Illuminate\Support\Facades\Storage::disk('public')->exists(ltrim($cur, '/'))) {
          $need = true;
        }
      }

      if ($need) {
        \App\Jobs\CarImageDownloadJob::dispatch(
          $run->id,
          $car->id,
          $model->id,
          $photoUrl
        )->onQueue('imports-images');
      }
    }

    // 5) Детали (products) — создаём/обновляем записи ПОД ЭТУ машину
    foreach ($detailColumns as $colIndex => $detailTitle) {
      $val = trim((string)($row[$colIndex] ?? ''));
      if ($val !== '1') continue;

      $norm = $this->normKey($detailTitle);

      $baseSlug = ($norm === 'порог') ? 'porog' : Str::slug($detailTitle);
      $slug = $baseSlug . '-' . $car->id;

      $this->getOrCreateProductForCar(
        run: $run,
        carId: (int)$car->id,
        title: (string)$detailTitle,
        slug: (string)$slug,
        normKey: (string)$norm
      );
    }

    ImportLogger::info($run, 'Прогресс: строка обработана', [
      'excel_row' => $excelRow,
      'make' => $make->title ?? null,
      'model' => $model->title ?? null,
      'car_id' => $car->id ?? null,
      'created' => $created,
    ]);
  }

  /** -------------------- UPSERTS -------------------- */

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

      // ВАЖНО: помечаем, что запись присутствует в текущем файле
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

    // если slug уже занят в другой марке — делаем title+make
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

    return CarModel::create($data);
  }

  /**
   * Возвращает: [Car $car, bool $created]
   * Важно: если нашли существующий — НЕ переписываем title/slug.
   */
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

      // дозаполняем только пустые поля, НЕ трогаем title/slug
      if (Schema::hasColumn('cars', 'generation') && empty($car->generation) && $generation !== '') {
        $upd['generation'] = $generation;
      }

      if (Schema::hasColumn('cars', 'years') && empty($car->years) && $years !== '') {
        $upd['years'] = $years;
      }

      if (Schema::hasColumn('cars', 'body') && empty($car->body) && $body !== '') {
        $upd['body'] = $body;
      }

      // ВАЖНО: пометка текущего импорта
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

    // создаём новую запись
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

  private function getOrCreateProductForCar(
    ImportRun $run,
    int $carId,
    string $title,
    string $slug,
    string $normKey
  ): Product {
    $q = Product::query();

    if (Schema::hasColumn('products', 'car_id')) {
      $q->where('car_id', $carId);
    }

    if (Schema::hasColumn('products', 'norm_key')) {
      $q->where('norm_key', $normKey);
    } else {
      $q->where('slug', $slug);
    }

    $p = $q->orderBy('id')->first();

    // фоллбэк по slug
    if (!$p) {
      $p = Product::query()->where('slug', $slug)->orderBy('id')->first();
    }

    if ($p) {
      $upd = [];

      // если по каким-то причинам car_id не заполнен — дозаполним
      if (Schema::hasColumn('products', 'car_id') && empty($p->car_id)) {
        $upd['car_id'] = $carId;
      }

      // если norm_key пустой — дозаполним
      if (Schema::hasColumn('products', 'norm_key') && empty($p->norm_key)) {
        $upd['norm_key'] = $normKey;
      }

      // пометка текущего импорта
      if (Schema::hasColumn('products', 'last_import_run_id')) {
        if ((int)($p->last_import_run_id ?? 0) !== (int)$run->id) {
          $upd['last_import_run_id'] = (int)$run->id;
        }
      }

      if ($upd) {
        $this->safeUpdateById($p->getTable(), (int)$p->id, $upd);
        foreach ($upd as $k => $v) $p->{$k} = $v;
      }

      return $p;
    }

    $data = [
      'title' => $title,
      'slug'  => $slug,
    ];

    if (Schema::hasColumn('products', 'car_id')) $data['car_id'] = $carId;
    if (Schema::hasColumn('products', 'norm_key')) $data['norm_key'] = $normKey;
    if (Schema::hasColumn('products', 'description')) $data['description'] = '';
    if (Schema::hasColumn('products', 'is_published')) $data['is_published'] = true;
    if (Schema::hasColumn('products', 'last_import_run_id')) $data['last_import_run_id'] = (int)$run->id;

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

  /** -------------------- SAFE UPDATE -------------------- */

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
    return trim($s);
  }

  /**
   * "8" => "8 поколение"
   * "6 (EJ, EK ...)" => оставляем как есть
   */
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
