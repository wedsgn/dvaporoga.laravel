<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>{{ $details['subject'] }}</title>
  <style>
    body{font-family:Arial,sans-serif;background:#f2f2f2;margin:0;padding:0}
    .container{max-width:600px;margin:0 auto;padding:20px;background:#fff;border:1px solid #ddd;border-radius:5px;box-shadow:0 2px 5px rgba(0,0,0,.1)}
    h1{color:#333;font-size:24px;margin-top:0}
    p{color:#666;margin-bottom:10px}
  </style>
</head>
<body>
  <div class="container">
    <h1>Новая {{ $details['subject'] }}</h1>

    @if(!empty($details['name']))
      <p><strong>Имя:</strong> {{ $details['name'] }}</p>
    @endif

    @if(!empty($details['phone']))
      <p><strong>Телефон:</strong> {{ $details['phone'] }}</p>
    @endif

    @php
      $make  = $details['make']  ?? null;
      $model = $details['model'] ?? null;
      $car   = $details['car']   ?? trim(($make ?: '').' '.($model ?: ''));
    @endphp

    @if(!empty($car))
      <p><strong>Автомобиль:</strong> {{ $car }}</p>
    @endif

    @if(!empty($details['form']))
      <p><strong>С какой части сайта:</strong> {{ $details['form'] }}</p>
    @endif

    @if(!empty($details['current_url']))
      <p><strong>URL:</strong> {{ $details['current_url'] }}</p>
    @endif
  </div>
</body>
</html>
