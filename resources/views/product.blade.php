<x-app-layout>
   <x-slot name="header">
      <h2 class="text-xl font-semibold text-gray-800"></h2>
   </x-slot>
   <div class="mx-auto max-w-7xl px-4 py-8">
      <div class="flex gap-8 rounded bg-white p-6 shadow">
         {{-- Image --}}
         @if ($product->image)
            <img src="{{ asset("storage/" . $product->image) }}" class="h-80 w-80 rounded-sm object-cover">
         @endif
         {{-- details --}}
         <div>
            <p class="text-sm text-gray-500">{{ $product->category->name }}</p>
            <h1 class="mt-2 text-3xl font-bold">{{ $product->title }}</h1>
            <p class="mt-2 text-2xl font-semibold text-green-600">${{ $product->price }}</p>
            <p class="mt-4 text-gray-600">{{ $product->description }}</p>
            <p class="mt-4 text-sm text-gray-400">{{ $product->quantity }} in stock</p>
            <button class="mt-6 rounded bg-blue-600 px-6 py-2 text-white hover:bg-blue-700">
               Add to Cart
            </button>
         </div>
      </div>
   </div>

</x-app-layout>
