<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Yandex Feed Preview</title>
    <style>
        body { font-family: Arial; background: #f5f5f5; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px,1fr)); gap: 16px; }
        .card {
            background: #fff;
            border-radius: 10px;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,.1);
        }
        img { width: 100%; height: 200px; object-fit: contain; background:#fafafa; border-radius:8px; }
        .name { font-weight: bold; margin: 10px 0 6px; line-height: 1.25; }
        .row { font-size: 13px; margin: 3px 0; color:#333; }
        .price { font-size: 18px; font-weight: 700; }
        .old { text-decoration: line-through; opacity: .6; margin-left: 8px; }
        .muted { opacity: .7; }
        .params { margin-top: 10px; padding-top: 10px; border-top: 1px solid #eee; }
        .param { font-size: 13px; margin: 2px 0; }
        a { text-decoration: none; color: inherit; }
        .btn { display:inline-block; margin-top:10px; padding:8px 10px; border-radius:8px; background:#111; color:#fff; font-size:13px; }
        .tag { display:inline-block; font-size:12px; padding:3px 8px; border-radius:999px; background:#f0f0f0; margin-right:6px; }
    </style>
</head>
<body>

<h1>Предпросмотр фида</h1>

<div class="grid">
@foreach($offers as $offer)
    @php
        $id = (string)($offer['id'] ?? '');
        $available = (string)($offer['available'] ?? '');
        $name = (string)($offer->name ?? '');
        $url = (string)($offer->url ?? '');
        $price = (string)($offer->price ?? '');
        $oldprice = (string)($offer->oldprice ?? '');
        $currencyId = (string)($offer->currencyId ?? '');
        $categoryId = (string)($offer->categoryId ?? '');
        $picture = (string)($offer->picture ?? '');
        $vendor = (string)($offer->vendor ?? '');
        $model = (string)($offer->model ?? '');
        $description = (string)($offer->description ?? '');
    @endphp

    <div class="card">
        <div class="row muted">
            <span class="tag">id: {{ $id }}</span>
            <span class="tag">available: {{ $available }}</span>
            <span class="tag">cat: {{ $categoryId }}</span>
        </div>

        <img src="{{ $picture }}" alt="{{ $name }}">

        <div class="name">{{ $name }}</div>

        <div class="row">
            <span class="price">{{ $price }} {{ $currencyId === 'RUB' ? '₽' : $currencyId }}</span>
            @if($oldprice !== '')
                <span class="old">{{ $oldprice }} ₽</span>
            @endif
        </div>

        <div class="row"><b>vendor:</b> {{ $vendor }}</div>
        <div class="row"><b>model:</b> {{ $model }}</div>

        @if($description !== '')
            <div class="row"><b>description:</b> {{ $description }}</div>
        @endif

        <div class="params">
            <div class="row"><b>params:</b></div>
            @foreach($offer->param ?? [] as $p)
                <div class="param">
                    <b>{{ (string)($p['name'] ?? '') }}:</b> {{ (string)$p }}
                </div>
            @endforeach
        </div>

        <a class="btn" href="{{ $url }}" target="_blank">Перейти</a>
    </div>
@endforeach
</div>

</body>
</html>
