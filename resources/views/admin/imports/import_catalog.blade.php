@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>Импорт каталога (фон + прогресс)</h1>

        <div class="card p-3 mb-3">
            <div class="mb-2"><b>Текущий импорт:</b>
                <span id="runInfo">
                    @if ($run)
                        #{{ $run->id }} ({{ $run->status }})
                    @else
                        нет
                    @endif
                </span>
            </div>

            <form id="uploadForm" enctype="multipart/form-data">
                @csrf
                <div class="mb-2">
                    <input type="file" name="file" required class="form-control" />
                </div>
                <button class="btn btn-primary" type="submit">Загрузить файл</button>
            </form>

            <div class="mt-3 d-flex gap-2">
                <button class="btn btn-success" id="btnStart" disabled>Старт</button>
                <button class="btn btn-warning" id="btnPause" disabled>Пауза</button>
                <button class="btn btn-info" id="btnResume" disabled>Продолжить</button>
                <button class="btn btn-danger" id="btnClearLogs" disabled>Очистить логи</button>
            </div>
        </div>

        <div class="card p-3 mb-3">
            <div class="mb-2"><b>Прогресс:</b> <span id="progressText">0%</span></div>
            <div style="height: 16px; background: #222; border-radius: 6px; overflow: hidden;">
                <div id="progressBar" style="height: 16px; width: 0%; background: #3b82f6;"></div>
            </div>
            <div class="mt-2" id="rowsText">0 / 0</div>
            <div class="mt-2 text-danger" id="lastError"></div>
        </div>

        <div class="card p-3">
            <div class="mb-2"><b>Логи:</b></div>
            <div id="logs"
                style="height: 320px; overflow:auto; background:#111; color:#ddd; padding:10px; font-family: monospace; font-size: 12px;">
            </div>
        </div>
    </div>
    <div id="restartModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.6); z-index:9999;">
        <div style="max-width:520px; margin:12% auto; background:#fff; padding:18px; border-radius:10px;">
            <h3 style="margin:0 0 10px;">Запустить с начала?</h3>
            <p style="margin:0 0 14px;">
                Импорт уже запускался. Старт с начала приведёт к повторной обработке файла с первой строки.
            </p>
            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <button id="modalCancel" class="btn btn-secondary">Отмена</button>
                <button id="modalConfirm" class="btn btn-danger">Да, запустить с начала</button>
            </div>
        </div>
    </div>
    <script>
        (() => {
            let runId = @json($run?->id);
            let lastLogId = 0;
            let timer = null;

            const csrf = document.querySelector('input[name="_token"]').value;

            const $ = (id) => document.getElementById(id);

            function setButtons(status) {
                $('btnStart').disabled = !runId || ['running', 'queued'].includes(status);
                $('btnPause').disabled = !runId || status !== 'running';
                $('btnResume').disabled = !runId || !['paused', 'failed', 'uploaded'].includes(status);
                $('btnClearLogs').disabled = !runId;
            }

            function appendLog(line) {
                const el = $('logs');
                el.innerHTML += line + "<br>";
                el.scrollTop = el.scrollHeight;
            }

            async function poll() {
                if (!runId) return;

                const url = `{{ url('/admin/import_catalog') }}/${runId}/status?after_id=${lastLogId}`;
                const res = await fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const data = await res.json();

                if (!data.ok) return;

                const run = data.run;
                $('runInfo').innerText = `#${run.id} (${run.status})`;
                $('progressText').innerText = run.progress + '%';
                $('progressBar').style.width = run.progress + '%';
                $('rowsText').innerText = `${run.processed_rows} / ${run.total_rows}`;
                $('lastError').innerText = run.last_error ? run.last_error : '';

                setButtons(run.status);

                if (data.logs && data.logs.length) {
                    data.logs.forEach(l => {
                        lastLogId = Math.max(lastLogId, l.id);
                        appendLog(`[${l.created_at}] ${l.level.toUpperCase()}: ${l.message}`);
                    });
                }

                // если done/canceled — остановим polling
                if (['done', 'canceled'].includes(run.status)) {
                    clearInterval(timer);
                    timer = null;
                }
            }

            function startPolling() {
                if (timer) return;
                poll();
                timer = setInterval(poll, 1500);
            }

            $('uploadForm').addEventListener('submit', async (e) => {
                e.preventDefault();

                const fd = new FormData(e.target);
                const res = await fetch(`{{ route('admin.import.catalog.upload') }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    },
                    body: fd
                });

                const data = await res.json();
                if (!data.ok) {
                    alert(data.message || 'Upload error');
                    return;
                }

                runId = data.run_id;
                lastLogId = 0;
                $('logs').innerHTML = '';
                $('runInfo').innerText = `#${runId} (${data.status})`;
                setButtons(data.status);
                startPolling();
            });

            function showRestartModal(onConfirm) {
                const modal = $('restartModal');
                modal.style.display = 'block';

                const cancel = $('modalCancel');
                const confirm = $('modalConfirm');

                const cleanup = () => {
                    modal.style.display = 'none';
                    cancel.onclick = null;
                    confirm.onclick = null;
                };

                cancel.onclick = () => cleanup();
                confirm.onclick = async () => {
                    cleanup();
                    await onConfirm();
                };
            }

            async function doRestart() {
                await fetch(`{{ url('/admin/import_catalog') }}/${runId}/restart`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    }
                });
                startPolling();
            }

            $('btnStart').addEventListener('click', async () => {
                if (!runId) return;

                const res = await fetch(`{{ url('/admin/import_catalog') }}/${runId}/status`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const data = await res.json();
                const status = data?.run?.status;

                if (['running', 'queued', 'paused', 'failed', 'done'].includes(status)) {
                    showRestartModal(doRestart);
                    return;
                }

                await doRestart();
            });

            $('btnPause').addEventListener('click', async () => {
                if (!runId) return;
                await fetch(`{{ url('/admin/import_catalog') }}/${runId}/pause`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    }
                });
                startPolling();
            });

            $('btnResume').addEventListener('click', async () => {
                if (!runId) return;
                await fetch(`{{ url('/admin/import_catalog') }}/${runId}/resume`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    }
                });
                startPolling();
            });
            $('btnClearLogs').addEventListener('click', async () => {
                if (!runId) return;

                await fetch(`{{ url('/admin/import_catalog') }}/${runId}/logs`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    }
                });

                lastLogId = 0;
                $('logs').innerHTML = '';
            });
            // если уже есть run — стартуем polling
            if (runId) {
                setButtons(@json($run?->status ?? 'uploaded'));
                startPolling();
            }
        })();
    </script>
@endsection
