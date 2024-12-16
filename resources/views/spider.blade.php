<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Liste des vins</h1>
    <form action="{{ route('spider') }}">
        <button type="submit">Update list</button>
    </form>
    @foreach ($items as $item)
        <div class="item">
            <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}">
            <h3>{{ $item['title'] }}</h3>
            <p>{{ $item['type'] }}</p>
            <p>{{ $item['volume'] }}</p>
            <p>{{ $item['country'] }}</p>
            <p>{{ $item['price'] }}</p>
            <p>{{ $item['upc'] }}</p>
        </div>
    @endforeach
</body>
</html>