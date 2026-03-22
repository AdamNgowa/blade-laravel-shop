<x-app-layout>
   <x-slot name="header">
      <h2 class="text-xl font-semibold text-gray-800">Checkout</h2>
   </x-slot>

   <div class="mx-auto max-w-7xl px-4 py-8">
      <div class="flex gap-8">

         {{-- Checkout Form --}}
         <div class="flex-1">
            <div class="rounded bg-white p-6 shadow">
               <h3 class="mb-6 text-lg font-bold">Shipping Details</h3>

               <form method="POST" action="{{ route('order.place') }}">
                  @csrf

                  {{-- Address --}}
                  <div class="mb-4">
                     <label class="mb-1 block text-sm font-medium text-gray-700">
                        Address
                     </label>
                     <input type="text" name="address" value="{{ old('address') }}"
                        class="w-full rounded border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="123 Main Street">
                     @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                     @enderror
                  </div>

                  {{-- City --}}
                  <div class="mb-4">
                     <label class="mb-1 block text-sm font-medium text-gray-700">
                        City
                     </label>
                     <input type="text" name="city" value="{{ old('cityyy') }}"
                        class="w-full rounded border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Nairobi">
                     @error('city')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                     @enderror
                  </div>

                  {{-- Phone --}}
                  <div class="mb-4">
                     <label class="mb-1 block text-sm font-medium text-gray-700">
                        Phone
                     </label>
                     <input type="text" name="phone" value="{{ old('phone') }}"
                        class="w-full rounded border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="+254 700 000000">
                     @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                     @enderror
                  </div>

                  {{-- Notes --}}
                  <div class="mb-6">
                     <label class="mb-1 block text-sm font-medium text-gray-700">
                        Notes (Optional)
                     </label>
                     <textarea name="notes" rows="3"
                        class="w-full rounded border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Any delivery instructions...">{{ old('notes') }}</textarea>
                  </div>

                  <button type="submit"
                     class="w-full rounded bg-green-600 py-3 font-semibold text-white hover:bg-green-700">
                     Place Order — ${{ number_format($total, 2) }}
                  </button>

               </form>
            </div>
         </div>

         {{-- Order Summary --}}
         <div class="w-80">
            <div class="rounded bg-white p-6 shadow">
               <h3 class="mb-4 text-lg font-bold">Order Summary</h3>

               <div class="divide-y">
                  @foreach ($cartItems as $item)
                     <div class="flex justify-between py-3">
                        <div>
                           <p class="font-medium">{{ $item->product->title }}</p>
                           <p class="text-sm text-gray-500">Qty: {{ $item->quantity }}</p>
                        </div>
                        <p class="font-semibold">
                           ${{ number_format($item->product->price * $item->quantity, 2) }}
                        </p>
                     </div>
                  @endforeach
               </div>

               <div class="mt-4 border-t pt-4">
                  <div class="flex justify-between text-lg font-bold">
                     <span>Total</span>
                     <span>${{ number_format($total, 2) }}</span>
                  </div>
               </div>
            </div>
         </div>

      </div>
   </div>
</x-app-layout>
