<!DOCTYPE html>
<html>
<head>
    <title>{{ $details['subject'] }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .card {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .card h2 {
            color: #333;
            font-size: 18px;
            margin: 0;
        }

        .card p {
            color: #666;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Новая {{ $details['subject'] }}</h1>
        @if(isset($details['name']) && !empty($details['name']))
            <p><strong>Имя:</strong> {{ $details['name'] }}</p>
        @endif
        @if(isset($details['phone']) && !empty($details['phone']))
            <p><strong>Телефон:</strong> {{ $details['phone'] }}</p>
        @endif

        @if(isset($details['form']) && !empty($details['form']))
            <p><strong>С какой части сайта:</strong> {{ $details['form'] }}</p>
        @endif
        @if(isset($details['products']) && !empty($details['products']))
            @php $productsGrouped = collect($details['products'])->groupBy('id'); @endphp
            @foreach($productsGrouped as $productId => $products)
                <div class="card">
                    <h2>{{ $products->first()->title }} ({{ $products->count() }})</h2>
                    <p><strong>Материал:</strong> {{ $products->first()->metal_thickness }}</p>
                    <p><strong>Цена:</strong> {{ $products->first()->price_one_side }}</p>
                </div>
            @endforeach
        @endif
        @if(isset($details['car']) && !empty($details['car']))
        <p><strong>Автомобиль:</strong> {{ $details['car'] }}</p>
    @endif
        @if(isset($details['total_price']) && !empty($details['total_price']))
            <p><strong>Итого:</strong> {{ $details['total_price'] }} руб.</p>
        @endif
    </div>
</body>
</html>
