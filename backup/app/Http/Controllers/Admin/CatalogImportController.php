<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\CatalogImportStartJob;
use App\Models\ImportRun;
use App\Services\CatalogSpreadsheetReader;
use App\Services\ImportLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\Car;
use App\Models\Product;

class CatalogImportController extends Controller
{
  public function index()
  {
    $user = Auth::user();

    // берём последний run
    $run = ImportRun::query()->orderByDesc('id')->first();

    $logs = [];
    if ($run) {
      $logs = ImportLogger::tail($run, 200);
    }

    return view('admin.imports.import_catalog', compact('user', 'run', 'logs'));
  }

  public function upload(Request $request)
  {
    $request->validate([
      'file' => ['required', 'file', 'mimes:xlsx,xls,csv'],
    ]);

    $file = $request->file('file');
    $hash = sha1_file($file->getRealPath());

    // если последний run уже с таким же файлом — просто возвращаемся (не дублим)
    $existing = ImportRun::query()->where('file_hash', $hash)->orderByDesc('id')->first();
    if ($existing) {
      return redirect()->route('admin.import.catalog')
        ->with('success', 'Файл уже загружен ранее. Можно нажать "Продолжить" или "Старт с начала".');
    }

    $storedPath = $file->store('imports', 'local'); // storage/app/imports/...

    $run = new ImportRun();
    $run->stored_path = $storedPath;
    $run->original_name = $file->getClientOriginalName();
    $run->file_hash = $hash;

    $run->status = 'ready';
    $run->chunk_size = $run->chunk_size ?: 300;
    $run->total_rows = 0;
    $run->processed_rows = 0;
    $run->current_row = 0;
    $run->save();

    ImportLogger::info($run, 'Файл загружен', [
      'original_name' => $run->original_name,
      'stored_path' => $run->stored_path,
    ]);

    return redirect()->route('admin.import.catalog')->with('success', 'Файл загружен.');
  }

  /**
   * Start = С НАЧАЛА.
   * Если уже был прогресс — требуем confirm=1 (для модалки).
   */
  public function start(Request $request, CatalogSpreadsheetReader $reader)
  {
    $run = ImportRun::query()->orderByDesc('id')->first();

    if (!$run) {
      return response()->json([
        'ok' => false,
        'message' => 'Сначала загрузите файл импорта.',
      ], 409);
    }

    if (!in_array($run->status, ['ready', 'paused', 'failed', 'done', 'canceled', 'running'], true)) {
      $run->status = 'ready';
    }

    $hasProgress = (int)$run->current_row > 0 || (int)$run->processed_rows > 0;

    if ($hasProgress && !$request->boolean('confirm')) {
      return response()->json([
        'ok' => false,
        'need_confirm' => true,
        'message' => 'Запуск с начала сбросит прогресс. Подтвердить?',
      ], 409);
    }

    // Сбрасываем прогресс run (НЕ трогаем данные в БД здесь!)
    $run->status = 'ready';
    $run->total_rows = 0;
    $run->processed_rows = 0;
    $run->current_row = 0;
    $run->last_error = null;
    $run->started_at = null;
    $run->finished_at = null;
    $run->heartbeat_at = null;
    $run->save();

    // Раньше тут был хард-делит products — убираем.
    // Удаление "всего лишнего" теперь делается ПОСЛЕ импорта через cleanup (по last_import_run_id).

    $abs = storage_path('app/' . $run->stored_path);
    $run->total_rows = $reader->countDataRows($abs);

    $h1 = $reader->readFirstHeaderRow($abs);
    $h2 = $reader->readSecondHeaderRow($abs);

    $run->detail_columns = $this->buildDetailColumns($h1, $h2);
    $run->save();

    ImportLogger::info($run, 'Старт с начала', [
      'total_rows' => $run->total_rows,
      'chunk_size' => $run->chunk_size,
    ]);

    CatalogImportStartJob::dispatch($run->id)->onQueue('imports');

    return response()->json(['ok' => true, 'run_id' => $run->id]);
  }

  /**
   * Resume = продолжить с текущей current_row
   */
  public function resume()
  {
    $run = ImportRun::query()->orderByDesc('id')->first();

    if (!$run) {
      return response()->json([
        'ok' => false,
        'message' => 'Сначала загрузите файл импорта.',
      ], 409);
    }

    if ($run->status === 'running') {
      return response()->json(['ok' => true, 'message' => 'Уже запущено.']);
    }

    if (!in_array($run->status, ['ready', 'paused', 'failed'], true)) {
      return response()->json(['ok' => false, 'message' => 'Нельзя продолжить из статуса: ' . $run->status], 409);
    }

    $run->status = 'ready';
    $run->last_error = null;
    $run->save();

    ImportLogger::info($run, 'Продолжение импорта', [
      'current_row' => $run->current_row,
      'processed_rows' => $run->processed_rows,
    ]);

    CatalogImportStartJob::dispatch($run->id)->onQueue('imports');

    return response()->json(['ok' => true, 'run_id' => $run->id]);
  }

  public function pause()
  {
    $run = ImportRun::query()->orderByDesc('id')->first();

    if (!$run) {
      return response()->json([
        'ok' => false,
        'message' => 'Нет активного импорта. Сначала загрузите файл.',
      ], 409);
    }

    $run->status = 'paused';
    $run->save();

    ImportLogger::info($run, 'Импорт поставлен на паузу');

    return response()->json(['ok' => true]);
  }

  public function clearLogs()
  {
    $run = ImportRun::query()->orderByDesc('id')->first();

    if (!$run) {
      return response()->json([
        'ok' => false,
        'message' => 'Логи очищать нечего: запусков импорта ещё нет.',
      ], 409);
    }

    ImportLogger::clear($run);

    return response()->json(['ok' => true]);
  }

  public function status()
  {
    $run = ImportRun::query()->orderByDesc('id')->first();

    if (!$run) {
      return response()->json(['ok' => true, 'run' => null, 'logs' => []]);
    }

    $logs = ImportLogger::tail($run, 200);
    $logs = array_values(array_unique($logs));

    // прогресс в процентах
    $progress = 0;
    if ((int)$run->total_rows > 0) {
      $progress = (int)floor(((int)$run->processed_rows / (int)$run->total_rows) * 100);
    }

    return response()->json([
      'ok' => true,
      'run' => [
        'id' => $run->id,
        'status' => $run->status,
        'total_rows' => (int)$run->total_rows,
        'processed_rows' => (int)$run->processed_rows,
        'current_row' => (int)$run->current_row,
        'progress' => $progress,
        'last_error' => $run->last_error,
      ],
      'logs' => $logs,
    ]);
  }

  /**
   * CLEANUP: удалить всё, чего НЕ было в текущем импорт-файле.
   * Дёргать только когда run.status === 'done'
   *
   * Роут добавишь:
   * Route::post('/import_catalog/cleanup', [CatalogImportController::class, 'cleanup'])->name('import.catalog.cleanup');
   */
  public function cleanup()
  {
    $run = ImportRun::query()->orderByDesc('id')->first();

    if (!$run) {
      return response()->json([
        'ok' => false,
        'message' => 'Запуска импорта ещё не было.',
      ], 409);
    }

    if ($run->status !== 'done') {
      return response()->json([
        'ok' => false,
        'message' => 'Cleanup можно запускать только после завершения импорта (status=done). Текущий статус: ' . $run->status,
      ], 409);
    }

    try {
      $counts = $this->cleanupAfterImport((int)$run->id);

      ImportLogger::info($run, 'Cleanup завершён', $counts);

      return response()->json([
        'ok' => true,
        'message' => 'Cleanup выполнен.',
        'deleted' => $counts,
      ]);
    } catch (\Throwable $e) {
      ImportLogger::error($run, 'Ошибка Cleanup: ' . $e->getMessage());
      return response()->json([
        'ok' => false,
        'message' => 'Ошибка Cleanup: ' . $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Удаляет (soft delete) всё, что не помечено last_import_run_id = $runId.
   * Важно: порядок от "детей" к "родителям".
   */
  private function cleanupAfterImport(int $runId): array
  {
    $tables = ['products','cars','car_models','car_makes'];
    foreach ($tables as $t) {
      if (!Schema::hasColumn($t, 'last_import_run_id')) {
        throw new \RuntimeException("В таблице {$t} нет колонки last_import_run_id (нужна миграция).");
      }
    }

    return DB::transaction(function () use ($runId) {
      $deletedProducts = Product::query()
        ->where(function ($q) use ($runId) {
          $q->whereNull('last_import_run_id')
            ->orWhere('last_import_run_id', '!=', $runId);
        })
        ->delete();

      $deletedCars = Car::query()
        ->where(function ($q) use ($runId) {
          $q->whereNull('last_import_run_id')
            ->orWhere('last_import_run_id', '!=', $runId);
        })
        ->delete();

      $deletedModels = CarModel::query()
        ->where(function ($q) use ($runId) {
          $q->whereNull('last_import_run_id')
            ->orWhere('last_import_run_id', '!=', $runId);
        })
        ->delete();

      $deletedMakes = CarMake::query()
        ->where(function ($q) use ($runId) {
          $q->whereNull('last_import_run_id')
            ->orWhere('last_import_run_id', '!=', $runId);
        })
        ->delete();

      return [
        'products' => (int)$deletedProducts,
        'cars' => (int)$deletedCars,
        'car_models' => (int)$deletedModels,
        'car_makes' => (int)$deletedMakes,
      ];
    });
  }

  // buildDetailColumns — тот, что мы делали под реальные заголовки
  private function buildDetailColumns(array $h1, array $h2): array
  {
    $out = [];

    $bodyIdx = null;
    foreach ($h2 as $i => $v) {
      $name = trim((string)$v);
      if (mb_strtolower($name) === 'кузов') {
        $bodyIdx = (int)$i;
        break;
      }
    }
    $start = $bodyIdx !== null ? ($bodyIdx + 1) : 6;

    $last = 0;
    $max = max(count($h1), count($h2));
    for ($i = $max - 1; $i >= 0; $i--) {
      $v1 = trim((string)($h1[$i] ?? ''));
      $v2 = trim((string)($h2[$i] ?? ''));
      if ($v1 !== '' || $v2 !== '') {
        $last = $i;
        break;
      }
    }

    $currentGroup = '';
    for ($i = 0; $i <= $last; $i++) {
      $g = trim((string)($h1[$i] ?? ''));
      if ($g !== '') $currentGroup = $g;

      if ($i < $start) continue;

      $n = trim((string)($h2[$i] ?? ''));
      if ($n === '' && $currentGroup === '') continue;

      if ($n !== '' && $currentGroup !== '') {
        $title = (mb_strtolower($n) === mb_strtolower($currentGroup)) ? $n : ($currentGroup . ' ' . $n);
      } else {
        $title = $n !== '' ? $n : $currentGroup;
      }

      $title = trim(preg_replace('~\s+~u', ' ', $title));
      if ($title !== '') $out[$i] = $title;
    }

    return $out;
  }
}
