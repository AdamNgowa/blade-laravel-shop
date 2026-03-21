<x-app-layout>
   <x-slot name="header">
      <h2 class="text-xl font-semibold text-gray-800">Shop</h2>
   </x-slot>

   <div class="mx-auto max-w-7xl px-4 py-8">
      <div class="grid grid-cols-3 gap-6">
         @foreach ($products as $product)
            <x-product-card :product="$product" />
         @endforeach
      </div>
   </div>
</x-app-layout>
