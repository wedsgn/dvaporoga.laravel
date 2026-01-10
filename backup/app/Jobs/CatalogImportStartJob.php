<?php

namespace App\Jobs;

use App\Models\ImportRun;
use App\Services\CatalogSpreadsheetReader;
use App\Services\ImportLogger;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CatalogImportStartJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  public function __construct(public int $runId) {}

  public function handle(CatalogSpreadsheetReader $reader): void
  {
    $run = ImportRun::findOrFail($this->runId);

    if (in_array($run->status, ['done', 'canceled'], true)) return;
    if ($run->status === 'paused') return;

    $run->status = 'running';
    $run->started_at ??= now();
    $run->last_error = null;
    $run->heartbeat_at = now();
    $run->save();

    $abs = storage_path('app/' . $run->stored_path);

    // 1) total rows
    if ((int)$run->total_rows <= 0) {
      $run->total_rows = $reader->countDataRows($abs);
      $run->save();
    }

    // 2) detail columns map (строим 1 раз и сохраняем в run)
    if (empty($run->detail_columns) || !is_array($run->detail_columns)) {
      $h1 = $reader->readFirstHeaderRow($abs);   // группы: Пороги/Арки/Пенки
      $h2 = $reader->readSecondHeaderRow($abs);  // названия колонок + подтипы

      $detailColumns = $this->buildDetailColumns($h1, $h2);

      $run->detail_columns = $detailColumns;
      $run->save();

      ImportLogger::info($run, 'Карта деталей построена', [
        'count' => count($detailColumns),
      ]);
    }

    ImportLogger::info($run, 'Импорт запущен', [
      'total_rows' => (int)$run->total_rows,
      'current_row' => (int)$run->current_row,
      'chunk_size' => (int)$run->chunk_size,
    ]);

    CatalogImportChunkJob::dispatch($run->id)->onQueue('imports');
  }

  /**
   * Строим мапу колонок деталей:
   * index => "Арки Передние"
   * index => "Порог"
   *
   * Важно:
   * - 1 строка: группа (может быть merged, поэтому надо "протягивать" последнее значение)
   * - 2 строка: подтип/название колонки
   * - старт колонок деталей ищем по "Кузов" во 2 строке
   */
  private function buildDetailColumns(array $h1, array $h2): array
  {
    $out = [];

    // 1) находим индекс колонки "Кузов" во 2-й строке (там точно подписано)
    $bodyIdx = null;
    foreach ($h2 as $i => $v) {
      $name = trim((string)$v);
      if (mb_strtolower($name) === 'кузов') {
        $bodyIdx = (int)$i;
        break;
      }
    }

    // если "Кузов" не нашли — фоллбэк: как раньше
    $start = $bodyIdx !== null ? ($bodyIdx + 1) : 6;

    // 2) определяем последний "живой" столбец (чтобы не пробегать пустой хвост)
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

    // 3) строим названия деталей по "группа + подтип"
    $currentGroup = '';
    for ($i = 0; $i <= $last; $i++) {

      // группа из 1-й строки, может быть merged -> поэтому протягиваем last non-empty
      $g = trim((string)($h1[$i] ?? ''));
      if ($g !== '') {
        $currentGroup = $g;
      }

      if ($i < $start) continue;

      $n = trim((string)($h2[$i] ?? ''));

      // если обе пустые — это точно не деталь
      if ($n === '' && $currentGroup === '') continue;

      // Варианты:
      // - "Порог" (группы нет, подтип есть) => "Порог"
      // - "Арки" + "Передние" => "Арки Передние"
      // - "Пенки" + "Багажник" => "Пенки Багажник"
      // - иногда бывает наоборот: группа есть, подтип пустой => используем группу
      if ($n !== '' && $currentGroup !== '') {
        // не делаем "Арки Арки"
        $title = (mb_strtolower($n) === mb_strtolower($currentGroup))
          ? $n
          : ($currentGroup . ' ' . $n);
      } else {
        $title = $n !== '' ? $n : $currentGroup;
      }

      $title = trim(preg_replace('~\s+~u', ' ', $title));
      if ($title === '') continue;

      $out[$i] = $title;
    }

    return $out;
  }
}
