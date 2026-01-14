@extends('layouts.admin')

@section('content')
    <div class="container py-3">

        <h3 class="mb-3">Импорт каталога</h3>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Upload --}}
        <div class="card mb-3">

            <div class="card-body">

                <form method="POST" action="{{ route('admin.import.catalog.upload') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="d-flex gap-3 align-items-center">
                        <input type="file" name="file" class="form-control" required>
                        <button class="btn btn-primary">Загрузить файл</button>
                    </div>
                    @error('file')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </form>

                @if (!empty($run))
                    <div class="mt-3 text-muted">
                        <div>Run #{{ $run->id }} | Статус: <span id="runStatus">{{ $run->status }}</span></div>
                        <div>Файл: {{ $run->original_name ?? $run->stored_path }}</div>
                    </div>
                @else
                    <div class="mt-3 text-muted">
                        Сначала загрузите файл импорта.
                    </div>
                @endif

<table class="table table-bordered" style="width:100%; margin-top:10px;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Дата загрузки</th>
            <th>Файл</th>
            <th>Размер</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    @forelse($runs as $r)
        <tr>
            <td>{{ $r->id }}</td>
            <td>{{ optional($r->created_at)->format('Y-m-d H:i') }}</td>
            <td>{{ $r->original_name ?? $r->stored_path }}</td>
            <td>
                @if(!empty($r->file_size))
                    {{ number_format($r->file_size / 1024 / 1024, 2) }} MB
                @else
                    —
                @endif
            </td>
            <td style="white-space:nowrap;">
                <a class="btn btn-sm btn-primary"
                   href="{{ route('admin.import_catalog.download', $r) }}">
                    Скачать
                </a>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5">Файлы ещё не загружались.</td>
        </tr>
    @endforelse
    </tbody>
</table>
            </div>

        </div>
        <hr>



        {{-- Controls --}}
        <div class="card mb-3">
            <div class="card-body d-flex flex-wrap gap-2 align-items-center">
                <button id="btnStart" class="btn btn-success">Старт</button>
                <button id="btnResume" class="btn btn-outline-success">Продолжить</button>
                <button id="btnPause" class="btn btn-warning">Пауза</button>
                <button id="btnClearLogs" class="btn btn-outline-secondary">Очистить логи</button>
                <span class="ms-auto text-muted" id="hint"></span>
            </div>
        </div>

        {{-- Progress --}}
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <div>Прогресс: <b id="progressText">0 / 0</b></div>
                    <div><b id="progressPercent">0%</b></div>
                </div>
                <div class="progress">
                    <div id="progressBar" class="progress-bar" role="progressbar" style="width:0%"></div>
                </div>
                <div class="text-muted mt-2" id="lastError"></div>
            </div>
        </div>

        {{-- Logs --}}
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0">Логи</h5>
                </div>
                <pre id="logBox"
                    style="height: 320px; overflow:auto; background:#0b1220; color:#cfe7ff; padding:12px; border-radius:8px;">
@foreach ($logs ?? [] as $l)
{{ $l . "\n" }}
@endforeach
</pre>
            </div>
        </div>

    </div>

    {{-- Modal confirm start --}}
    <div class="modal fade" id="confirmStartModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Запуск с начала</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    Уже есть прогресс импорта. Запуск “Старт” обработает файл с начала и сбросит прогресс. Продолжить?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" id="btnConfirmStart" class="btn btn-danger">Да, старт с начала</button>
                </div>
            </div>
        </div>
    </div>
<script>
    (function() {
        // CSRF: сначала из meta, затем фоллбэк
        const csrf =
            document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
            "{{ csrf_token() }}";

        // ВАЖНО: относительные URL (без http/https и домена)
        const routes = {
            start: "{{ route('admin.import.catalog.start', [], false) }}",
            resume: "{{ route('admin.import.catalog.resume', [], false) }}",
            pause: "{{ route('admin.import.catalog.pause', [], false) }}",
            clearLogs: "{{ route('admin.import.catalog.clearLogs', [], false) }}",
            status: "{{ route('admin.import.catalog.status', [], false) }}",
        };

        const elStatus = document.getElementById("runStatus");
        const elProgressText = document.getElementById("progressText");
        const elProgressPercent = document.getElementById("progressPercent");
        const elProgressBar = document.getElementById("progressBar");
        const elLogBox = document.getElementById("logBox");
        const elLastError = document.getElementById("lastError");
        const elHint = document.getElementById("hint");

        const btnStart = document.getElementById("btnStart");
        const btnResume = document.getElementById("btnResume");
        const btnPause = document.getElementById("btnPause");
        const btnClearLogs = document.getElementById("btnClearLogs");

        const modalEl = document.getElementById("confirmStartModal");
        const btnConfirmStart = document.getElementById("btnConfirmStart");

        // ✅ ВАЖНО: polling должен быть объявлен
        let polling = null;

        // ---- sticky autoscroll ----
        let stickToBottom = true;

        function isNearBottom(el, gap = 40) {
            return (el.scrollTop + el.clientHeight) >= (el.scrollHeight - gap);
        }

        if (elLogBox) {
            elLogBox.addEventListener('scroll', () => {
                stickToBottom = isNearBottom(elLogBox);
            });
        }

        function showError(message) {
            if (!elLastError) return;
            elLastError.textContent = message || "";
            elLastError.classList.remove('text-muted');
            if (message) elLastError.classList.add('text-danger');
            else elLastError.classList.remove('text-danger');
        }

        function clearError() {
            showError("");
        }

        async function post(url, body = {}) {
            const res = await fetch(url, {
                method: "POST",
                credentials: "same-origin",
                cache: "no-store",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrf,
                    "X-Requested-With": "XMLHttpRequest",
                },
                body: JSON.stringify(body),
            });

            const data = await res.json().catch(() => ({}));
            return {
                ok: res.ok,
                status: res.status,
                data
            };
        }

        function hardCloseModal() {
            document.querySelectorAll(".modal-backdrop").forEach((b) => b.remove());
            document.body.classList.remove("modal-open");
            document.body.style.removeProperty("padding-right");
        }

        function showConfirmModal() {
            if (!modalEl || typeof bootstrap === "undefined") return;
            const inst = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            inst.show();
        }

        function hideConfirmModal() {
            if (!modalEl || typeof bootstrap === "undefined") return;
            const inst = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            inst.hide();
            setTimeout(hardCloseModal, 200);
        }

        function setHintByStatus(status) {
            if (!elHint) return;
            if (status === "running") elHint.textContent = "Импорт выполняется...";
            else if (status === "paused") elHint.textContent = "Пауза.";
            else if (status === "failed") elHint.textContent =
                "Ошибка. Можно нажать «Продолжить» после исправления.";
            else if (status === "done") elHint.textContent = "Готово.";
            else elHint.textContent = "";
        }

        function escapeHtml(str) {
            return String(str ?? "")
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;");
        }

        function renderLogs(logs) {
            return (logs || []).map(rawLine => {
                const raw = String(rawLine ?? "");
                const safe = escapeHtml(raw);

                // для проверки — используем строку без пробелов слева
                const l = raw.trimStart();

                // WARN: может быть "WARN:", "[WARN]", "WARNING"
                if (/\bWARN(?:ING)?\b\s*:/i.test(l) || /\[WARN(?:ING)?\]/i.test(l)) {
                    return `<span class="log-warn">${safe}</span>`;
                }

                // ERROR: может быть "ERROR:", "ERR:", "[ERROR]"
                if (/\bERROR\b\s*:/i.test(l) || /\bERR\b\s*:/i.test(l) || /\[ERROR\]/i.test(l) || /\[ERR\]/i.test(l)) {
                    return `<span class="log-error">${safe}</span>`;
                }

                return safe;
            }).join("\n");
        }

        async function refresh() {
            try {
                const url = new URL(routes.status, window.location.origin);
                url.searchParams.set("_t", Date.now()); // анти-кэш

                const res = await fetch(url.toString(), {
                    method: "GET",
                    credentials: "same-origin",
                    cache: "no-store",
                    headers: {
                        "Accept": "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                    },
                });

                const json = await res.json().catch(() => null);
                if (!json || !json.ok) return;

                // если run отсутствует — покажем подсказку и выключим кнопки импорта
                if (!json.run) {
                    if (elStatus) elStatus.textContent = "";
                    if (elProgressText) elProgressText.textContent = "0 / 0";
                    if (elProgressPercent) elProgressPercent.textContent = "0%";
                    if (elProgressBar) elProgressBar.style.width = "0%";
                    setHintByStatus("");
                    if (btnPause) btnPause.disabled = true;
                    if (btnResume) btnResume.disabled = true;
                    return;
                }

                const run = json.run;
                const logs = Array.isArray(json.logs) ? json.logs : [];

                if (elStatus) elStatus.textContent = run.status ?? "";
                if (elProgressText) elProgressText.textContent = `${run.processed_rows ?? 0} / ${run.total_rows ?? 0}`;
                if (elProgressPercent) elProgressPercent.textContent = `${run.progress ?? 0}%`;
                if (elProgressBar) elProgressBar.style.width = `${run.progress ?? 0}%`;

                if (run.last_error) showError(`Ошибка: ${run.last_error}`);
                else clearError();

                if (elLogBox) {
                    const shouldStick = stickToBottom || isNearBottom(elLogBox);

                    elLogBox.innerHTML = renderLogs(logs);

                    if (shouldStick) {
                        elLogBox.scrollTop = elLogBox.scrollHeight;
                        stickToBottom = true;
                    }
                }

                setHintByStatus(run.status);

                if (btnPause) btnPause.disabled = (run.status !== "running");
                if (btnResume) btnResume.disabled = !(["ready", "paused", "failed"].includes(run.status));
            } catch (e) {
                // не ломаем polling
            }
        }

        function startPolling() {
            if (polling) return;
            polling = setInterval(refresh, 1500);
        }

        // START (может потребовать confirm)
        if (btnStart) {
            btnStart.addEventListener("click", async () => {
                clearError();

                const r = await post(routes.start, { confirm: 0 });

                if (!r.ok) {
                    if (r.status === 409 && r.data && r.data.need_confirm) {
                        showConfirmModal();
                        return;
                    }
                    showError(r.data?.message || "Ошибка запуска импорта");
                    return;
                }

                startPolling();
                await refresh();
            });
        }

        // CONFIRM START
        if (btnConfirmStart) {
            btnConfirmStart.addEventListener("click", async () => {
                clearError();

                hideConfirmModal();
                const r = await post(routes.start, { confirm: 1 });

                if (!r.ok) {
                    showError(r.data?.message || "Ошибка запуска импорта");
                    return;
                }

                startPolling();
                await refresh();
            });
        }

        // RESUME
        if (btnResume) {
            btnResume.addEventListener("click", async () => {
                clearError();

                const r = await post(routes.resume);

                if (!r.ok) {
                    showError(r.data?.message || "Ошибка продолжения импорта");
                    return;
                }

                startPolling();
                await refresh();
            });
        }

        // PAUSE
        if (btnPause) {
            btnPause.addEventListener("click", async () => {
                clearError();

                const r = await post(routes.pause);

                if (!r.ok) {
                    showError(r.data?.message || "Ошибка паузы");
                    return;
                }

                await refresh();
            });
        }

        // CLEAR LOGS
        if (btnClearLogs) {
            btnClearLogs.addEventListener("click", async () => {
                clearError();

                const r = await post(routes.clearLogs);

                if (!r.ok) {
                    showError(r.data?.message || "Ошибка очистки логов");
                    return;
                }

                if (elLogBox) elLogBox.textContent = "";
                await refresh();
            });
        }

        // initial
        startPolling();
        refresh();
    })();
</script>

    <style>
        #logBox {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-size: 13px;
            line-height: 1.4;
            white-space: pre-wrap;
        }

        .log-warn {
            color: #ffb020;
            /* оранжевый */
            font-weight: 600;
        }

        .log-error {
            color: #ff5c5c;
            /* красный */
            font-weight: 700;
        }
    </style>
@endsection
