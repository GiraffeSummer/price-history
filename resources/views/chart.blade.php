<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>chart</title>

    <!-- Fonts -->
    <script>
        const priceHistory = {!! json_encode($history) !!}
        const product = {!! json_encode($product) !!}
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/chart.js')}}" defer></script>
</head>

<body class="antialiased">
    <canvas id="myChart"></canvas>
</body>

</html>