<x-app-layout>
   <x-slot name="header">
      <h2 class="text-xl font-semibold text-gray-800">My Cart</h2>
   </x-slot>

   <div class="mx-auto max-w-7xl px-4 py-8">

      {{-- Success message --}}
      @if (session('success'))
         <div class="mb-6 rounded bg-green-100 p-4 text-green-800">
            {{ session('success') }}
         </div>
      @endif

      @if ($cartItems->isEmpty())
         <div class="py-16 text-center">
            <p class="text-lg text-gray-500">Your cart is empty</p>
            <a href="{{ route('shop') }}"
               class="mt-4 inline-block rounded bg-blue-600 px-6 py-2 text-white hover:bg-blue-700">
               Go to Shop
            </a>
         </div>
      @else
         <div class="overflow-hidden rounded bg-white shadow">
            <table class="w-full">
               <thead class="bg-gray-50">
                  <tr>
                     <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Product</th>
                     <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Price</th>
                     <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Quantity</th>
                     <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Subtotal</th>
                     <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Action</th>
                  </tr>
               </thead>
               <tbody class="divide-y divide-gray-200">
                  @foreach ($cartItems as $item)
                     <tr>
                        {{-- Product --}}
                        <td class="flex items-center gap-4 px-6 py-4">
                           @if ($item->product->image)
                              <img src="{{ asset('storage/' . $item->product->image) }}"
                                 class="h-16 w-16 rounded object-cover">
                           @endif
                           <span class="font-medium">{{ $item->product->title }}</span>
                        </td>

                        {{-- Price --}}
                        <td class="px-6 py-4">${{ $item->product->price }}</td>

                        {{-- Quantity --}}
                        <td class="px-6 py-4">
                           <form method="POST" action="{{ route('cart.update', $item->id) }}">
                              @csrf
                              @method('PATCH')
                              <input type="number" name="quantity" value="{{ $item->quantity }}" min="1"
                                 class="w-16 rounded border px-2 py-1 text-center">
                              <button type="submit" class="ml-2 text-sm text-blue-600 hover:underline">
                                 Update
                              </button>
                           </form>
                        </td>

                        {{-- Subtotal --}}
                        <td class="px-6 py-4 font-semibold text-green-600">
                           ${{ number_format($item->product->price * $item->quantity, 2) }}
                        </td>

                        {{-- Remove --}}
                        <td class="px-6 py-4">
                           <form method="POST" action="{{ route('cart.remove', $item->id) }}">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="text-sm text-red-600 hover:underline">
                                 Remove
                              </button>
                           </form>
                        </td>
                     </tr>
                  @endforeach
               </tbody>
            </table>
         </div>

         {{-- Total and Actions --}}
         <div class="mt-6 flex items-center justify-between">
            <form method="POST" action="{{ route('cart.clear') }}">
               @csrf
               @method('DELETE')
               <button type="submit" class="rounded border border-red-600 px-4 py-2 text-red-600 hover:bg-red-50">
                  Clear Cart
               </button>
            </form>

            <div class="text-right">
               <p class="text-xl font-bold">Total: ${{ number_format($total, 2) }}</p>
               <button class="mt-2 rounded bg-green-600 px-8 py-3 text-white hover:bg-green-700">
                  Checkout
               </button>
            </div>
         </div>

      @endif
   </div>
</x-app-layout>
