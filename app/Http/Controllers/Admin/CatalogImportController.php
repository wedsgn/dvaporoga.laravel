<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\CatalogImportStartJob;
use App\Models\ImportLog;
use App\Models\ImportRun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CatalogImportController extends Controller
{
  public function index(Request $request)
  {
    $user = Auth::user();

    $run = null;
    if ($request->query('run_id')) {
      $run = ImportRun::find((int)$request->query('run_id'));
    } else {
      $run = ImportRun::where('type', 'catalog')->orderByDesc('id')->first();
    }

    return view('admin.imports.import_catalog', compact('user', 'run'));
  }

  public function upload(Request $request)
  {
    $request->validate([
      'file' => ['required', 'file', 'mimes:xlsx,xls,csv'],
    ]);

    $file = $request->file('file');
    $hash = hash_file('sha256', $file->getRealPath());
    $original = $file->getClientOriginalName();

    // если такой файл уже загружали — возвращаем существующий run
    $existing = ImportRun::where('type', 'catalog')
      ->where('file_hash', $hash)
      ->orderByDesc('id')
      ->first();

    if ($existing && in_array($existing->status, ['uploaded', 'queued', 'running', 'failed', 'paused', 'done'], true)) {
      return response()->json([
        'ok' => true,
        'message' => 'Файл уже загружен ранее',
        'run_id' => $existing->id,
        'status' => $existing->status,
      ]);
    }

    $path = $file->storeAs('imports', $hash . '-' . time() . '.' . $file->getClientOriginalExtension());

    $run = ImportRun::create([
      'type' => 'catalog',
      'status' => 'uploaded',
      'original_name' => $original,
      'stored_path' => $path,
      'file_hash' => $hash,
      'chunk_size' => 100,
    ]);

    return response()->json([
      'ok' => true,
      'message' => 'Файл загружен',
      'run_id' => $run->id,
      'status' => $run->status,
    ]);
  }

  public function start(ImportRun $run)
  {
    if ($run->type !== 'catalog') abort(404);

    if (in_array($run->status, ['running', 'queued'], true)) {
      return response()->json(['ok' => true, 'message' => 'Уже запущен', 'run_id' => $run->id]);
    }

    if ($run->status === 'done') {
      // если хочешь “перезапуск” — сбрасывай прогресс тут
      return response()->json(['ok' => false, 'message' => 'Импорт уже завершён', 'run_id' => $run->id], 422);
    }

    $run->status = 'queued';
    $run->last_error = null;
    $run->save();

    CatalogImportStartJob::dispatch($run->id)->onQueue('imports');

    return response()->json(['ok' => true, 'message' => 'Поставлено в очередь', 'run_id' => $run->id]);
  }

  public function pause(ImportRun $run)
  {
    if ($run->type !== 'catalog') abort(404);
    if ($run->status === 'running') {
      $run->status = 'paused';
      $run->save();
    }
    return response()->json(['ok' => true, 'status' => $run->status]);
  }

  public function resume(ImportRun $run)
  {
    if ($run->type !== 'catalog') abort(404);

    if (in_array($run->status, ['paused', 'failed', 'uploaded'], true)) {
      $run->status = 'queued';
      $run->last_error = null;
      $run->save();

      CatalogImportStartJob::dispatch($run->id)->onQueue('imports');
    }

    return response()->json(['ok' => true, 'status' => $run->status]);
  }

  public function status(ImportRun $run, Request $request)
  {
    if ($run->type !== 'catalog') abort(404);

    $afterId = (int)$request->query('after_id', 0);

    $logsQ = ImportLog::where('import_run_id', $run->id)->orderBy('id', 'asc');
    if ($afterId > 0) $logsQ->where('id', '>', $afterId);

    $logs = $logsQ->limit(200)->get(['id', 'level', 'message', 'created_at']);

    return response()->json([
      'ok' => true,
      'run' => [
        'id' => $run->id,
        'status' => $run->status,
        'total_rows' => $run->total_rows,
        'processed_rows' => $run->processed_rows,
        'current_row' => $run->current_row,
        'progress' => $run->progressPercent(),
        'last_error' => $run->last_error,
      ],
      'logs' => $logs,
    ]);
  }
  public function clearLogs(ImportRun $run)
  {
    if ($run->type !== 'catalog') abort(404);

    $run->logs()->delete();

    return response()->json([
      'ok' => true,
      'message' => 'Логи очищены',
    ]);
  }

  public function restart(ImportRun $run)
  {
    if ($run->type !== 'catalog') abort(404);

    $run->status = 'queued';
    $run->processed_rows = 0;
    $run->current_row = 0;
    $run->last_error = null;
    $run->started_at = null;
    $run->finished_at = null;
    $run->save();


    CatalogImportStartJob::dispatch($run->id)->onQueue('imports');

    return response()->json([
      'ok' => true,
      'message' => 'Запуск с начала поставлен в очередь',
      'run_id' => $run->id,
    ]);
  }
}
