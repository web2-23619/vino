<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scrapper</title>
</head>
<body>
    <h1>Liste des vins</h1>
    <form action="{{ route('goutte') }}" method="get">
        <button type="submit">Update list</button>
    </form>
        <ul class="product">
        @foreach ($products as $product)
            <li>
                <img src="{{ $product['productImageUrl'] }}" alt="{{ $product['productTitle'] }}">
                <h3>{{ $product['productTitle'] }}</h3>
                <p>{{ $product['productType'] }}</p>
                <p>{{ $product['productVolume'] }}</p>
                <p>{{ $product['productCountry'] }}</p>
                <p>{{ $product['productPrice'] }}</p>
                <p>{{ $product['productUpc'] }}</p>
            </li>
            @endforeach
        </ul>
</body>
</html>