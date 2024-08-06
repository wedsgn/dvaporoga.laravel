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

        h1 {
            color: #333;
            font-size: 24px;
            margin-top: 0;
        }

        p {
            color: #666;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>{{ $details['subject'] }}</h1>
        @if(isset($details['name']) && !empty($details['name']))
            <p><strong>Имя:</strong> {{ $details['name'] }}</p>
        @endif
        @if(isset($details['phone']) && !empty($details['phone']))
            <p><strong>Телефон:</strong> {{ $details['phone'] }}</p>
        @endif
        @if(isset($details['data']) && !empty($details['data']))
            <p><strong>Данные:</strong> {{ $details['data'] }}</p>
        @endif
        @if(isset($details['form']) && !empty($details['form']))
            <p><strong>С какой части сайта:</strong> {{ $details['form'] }}</p>
        @endif
    </div>
</body>
</html>
