<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ORDER #{{ $order->order_code }}</title>
</head>

<body>
    <p><img src="{{ public_path('assets/img/logo_clean.png') }}" alt="S MODE" width="100"></p>
    <br>
    <div style="font-size: 80%">
        <p>
            <b>PLACED</b><br>
            <br>
            ID #:{{ $order->order_id }}<br>
            Date: {{ $order->order_placed }} <br>
            Order #:{{ $order->order_code }}<br>
        </p>
    </div>
</body>

</html>
