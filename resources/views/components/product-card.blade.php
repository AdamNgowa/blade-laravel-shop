@props(['product'])
<div class="rounded bg-white p-4 shadow">
   @if ($product->image)
      <img src="{{ asset('storage/' . $product->image) }}" class="h-48 w-full rounded object-cover">
   @endif
   <h3 class="mt-2 text-lg font-bold">{{ $product->title }}</h3>
   <p class="text-sm text-gray-500">{{ $product->category->name }}</p>
   <p class="mt-3 font-semibold text-green-400">{{ $product->price }}</p>
   {{-- Product link --}}
   <a href="{{ route('product', $product->slug) }}"
      class="mt-4 block rounded bg-blue-600 py-2 text-center text-white hover:bg-blue-700">

      View Product
   </a>
   {{-- add to cart --}}
   <form action="{{ route('cart.add') }}" method="post">
      @csrf
      <input type="hidden" name="product_id" value="{{ $product->id }}">
      <button type="submit" class="mt-2 w-full rounded bg-green-600 py-2 text-white hover:bg-green-700">
         Add to Cart
      </button>
   </form>
</div>
