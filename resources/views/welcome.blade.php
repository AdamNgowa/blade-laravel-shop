<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Welcome</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-3 gap-6">
            @foreach($products as $productInWelcome)
                <x-product-card :productInCard="$productInWelcome" />
            @endforeach
        </div>
    </div>
</x-app-layout>