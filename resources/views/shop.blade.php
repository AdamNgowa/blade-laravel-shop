<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Shop</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-3 gap-6">
            @foreach($products as $product)
                <x-product-card :productInCard="$product" />
            @endforeach
        </div>
    </div>
</x-app-layout>