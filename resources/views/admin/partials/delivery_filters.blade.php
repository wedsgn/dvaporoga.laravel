@php
    $base = array_filter(['search' => request('search')], fn ($value) => $value !== null && $value !== '');
    $link = fn (array $params = []) => route($routeName, array_merge($base, $params));
    $is = fn (array $params = []) => collect($params)->every(fn ($value, $key) => (string) request($key) === (string) $value);
@endphp

<div class="d-flex flex-wrap gap-2 mt-3">
    <a class="btn btn-sm {{ !request()->hasAny(['delivery_problem', 'channel', 'status']) ? 'btn-primary' : 'btn-outline-primary' }}"
       href="{{ $link() }}">Все</a>
    <a class="btn btn-sm {{ request()->boolean('delivery_problem') ? 'btn-primary' : 'btn-outline-primary' }}"
       href="{{ $link(['delivery_problem' => 1]) }}">Есть проблемы</a>
    <a class="btn btn-sm {{ $is(['channel' => 'bitrix', 'status' => 'failed']) ? 'btn-primary' : 'btn-outline-primary' }}"
       href="{{ $link(['channel' => 'bitrix', 'status' => 'failed']) }}">Bitrix: ошибка</a>
    <a class="btn btn-sm {{ $is(['channel' => 'uis', 'status' => 'problem']) ? 'btn-primary' : 'btn-outline-primary' }}"
       href="{{ $link(['channel' => 'uis', 'status' => 'problem']) }}">UIS: ошибка/не подтверждено</a>
    <a class="btn btn-sm {{ $is(['channel' => 'yandex_metrika', 'status' => 'problem']) ? 'btn-primary' : 'btn-outline-primary' }}"
       href="{{ $link(['channel' => 'yandex_metrika', 'status' => 'problem']) }}">Метрика: ошибка/не подтверждено</a>
</div>
