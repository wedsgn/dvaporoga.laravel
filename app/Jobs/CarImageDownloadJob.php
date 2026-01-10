<?php

namespace App\Jobs;

use App\Models\Car;
use App\Models\CarModel;
use App\Models\ImportRun;
use App\Services\CarImageDownloader;
use App\Services\ImportLogger;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class CarImageDownloadJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  public int $tries = 3;
  public int $backoff = 10;

  public function __construct(
    public int $runId,
    public int $carId,
    public int $carModelId,
    public string $url
  ) {}

  public function handle(CarImageDownloader $downloader): void
  {
    $run = ImportRun::find($this->runId);
    if ($run && in_array($run->status, ['paused', 'canceled'], true)) {
      return;
    }

    // тянем связи, чтобы построить финальную папку
    $car = Car::withTrashed()
      ->with(['car_model.car_make'])
      ->find($this->carId);

    if (!$car) return;

    // если уже локальный путь НЕ из cache — не трогаем ТОЛЬКО если файл реально существует
    $cur = (string)($car->image ?? '');
    $isHttp  = (bool)preg_match('~^https?://~i', $cur);
    $isCache = str_starts_with($cur, 'uploads/cache/');
    $isLocal = ($cur !== '' && !$isHttp);

    if ($isLocal && !$isCache) {
      // "default" считаем как "картинки нет" — надо скачать/заменить
      if ($cur === 'default') {
        // продолжаем (будем качать)
      } else {
        $p = ltrim($cur, '/');
        if (Storage::disk('public')->exists($p)) {
          return; // файл на месте — выходим
        }
        // файла нет — продолжаем (будем качать)
      }
    }

    // 1) скачали (или взяли готовое) в cache
    $cachePath = $downloader->downloadToCacheWebp($this->url);
    if (!$cachePath) {
      if ($run) ImportLogger::warn($run, 'Фото: не удалось скачать', [
        'car_id' => $this->carId,
        'url' => $this->url,
      ]);
      return;
    }

    // 2) публикуем из cache в папку машины
    $makeSlug  = (string)optional(optional($car->car_model)->car_make)->slug;
    $modelSlug = (string)optional($car->car_model)->slug;
    $carSlug   = (string)$car->slug;

    // если каких-то slug нет — не ломаем импорт, просто оставим cache
    if ($makeSlug === '' || $modelSlug === '' || $carSlug === '') {
      if ($run) ImportLogger::warn($run, 'Фото: не удалось построить финальный путь (нет slug)', [
        'car_id' => $car->id,
        'make_slug' => $makeSlug,
        'model_slug' => $modelSlug,
        'car_slug' => $carSlug,
        'cache' => $cachePath,
      ]);
      return;
    }

    // имя файла можно оставить хешем URL (из cachePath)
    $filename = basename($cachePath);
    $finalDir = "uploads/cars/{$makeSlug}/{$modelSlug}/{$carSlug}";
    $finalPath = "{$finalDir}/{$filename}";

    $disk = Storage::disk('public');

    // копируем только если ещё нет
    if (!$disk->exists($finalPath)) {
      // на всякий случай создадим директорию (для некоторых драйверов полезно)
      $disk->makeDirectory($finalDir);

      // copy может падать если source нет — проверим
      if ($disk->exists($cachePath)) {
        $disk->copy($cachePath, $finalPath);
      } else {
        if ($run) ImportLogger::warn($run, 'Фото: cache-файл исчез до публикации', [
          'car_id' => $car->id,
          'cache' => $cachePath,
        ]);
        return;
      }
    }

    $car->refresh();
    $cur = (string)($car->image ?? '');
    $isHttp  = (bool)preg_match('~^https?://~i', $cur);
    $isCache = str_starts_with($cur, 'uploads/cache/');
    $isLocal = ($cur !== '' && !$isHttp);

    $needUpdate = false;

    if ($cur === '' || $isHttp || $isCache || $cur === 'default') {
      $needUpdate = true;
    } elseif ($isLocal) {
      $p = ltrim($cur, '/');
      if (!Storage::disk('public')->exists($p)) $needUpdate = true;
    }

    if ($needUpdate) {
      $car->image = $finalPath;
      $car->save();
    }

    $model = CarModel::withTrashed()->find($this->carModelId);
    if ($model) {
      $mcur = (string)($model->image ?? '');
      if ($mcur === '' || preg_match('~^https?://~i', $mcur) || str_starts_with($mcur, 'uploads/cache/')) {
        $model->image = $finalPath;
        $model->save();
      }
    }

    if ($run) {
      ImportLogger::info($run, 'Фото: опубликовано', [
        'car_id' => $car->id,
        'cache' => $cachePath,
        'final' => $finalPath,
      ]);
    }
  }
}
