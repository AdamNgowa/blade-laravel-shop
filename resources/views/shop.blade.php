<!DOCTYPE html>
<html>
<head>
    <title>Shop</title>
</head>
<body>

    <nav>
        <a href="/">Home</a>
        <a href="/shop">Shop</a>
    </nav>

    <h1>Shop</h1>

    @foreach($products as $product)
        <div>
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" width="200">
            @endif
            <h3>{{ $product->title }}</h3>
            <p>{{ $product->category->name }}</p>
            <p>${{ $product->price }}</p>
        </div>
    @endforeach

</body>
</html>