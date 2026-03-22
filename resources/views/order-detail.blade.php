<x-app-layout>
   <x-slot name="header">
      <h2 class="text-xl font-semibold text-gray-800">Order #{{ $order->id }}</h2>
   </x-slot>

   <div class="mx-auto max-w-7xl px-4 py-8">
      <div class="flex gap-8">

         {{-- Order Items --}}
         <div class="flex-1">
            <div class="rounded bg-white p-6 shadow">
               <h3 class="mb-4 text-lg font-bold">Items Ordered</h3>

               <div class="divide-y">
                  @foreach ($order->items as $item)
                     <div class="flex items-center gap-4 py-4">
                        @if ($item->product->image)
                           <img src="{{ asset('storage/' . $item->product->image) }}"
                              class="h-16 w-16 rounded object-cover">
                        @endif
                        <div class="flex-1">
                           <p class="font-medium">{{ $item->product->title }}</p>
                           <p class="text-sm text-gray-500">Qty: {{ $item->quantity }}</p>
                        </div>
                        <p class="font-semibold text-green-600">
                           ${{ number_format($item->price * $item->quantity, 2) }}
                        </p>
                     </div>
                  @endforeach
               </div>

               <div class="mt-4 border-t pt-4 text-right">
                  <p class="text-xl font-bold">
                     Total: ${{ number_format($order->total, 2) }}
                  </p>
               </div>
            </div>
         </div>

         {{-- Order Info --}}
         <div class="w-80">
            <div class="rounded bg-white p-6 shadow">
               <h3 class="mb-4 text-lg font-bold">Order Info</h3>

               <div class="space-y-3 text-sm">
                  <div>
                     <p class="text-gray-500">Status</p>
                     <p class="font-semibold capitalize">{{ $order->status }}</p>
                  </div>
                  <div>
                     <p class="text-gray-500">Date</p>
                     <p class="font-semibold">{{ $order->created_at->format('M d, Y') }}</p>
                  </div>
                  <div>
                     <p class="text-gray-500">Address</p>
                     <p class="font-semibold">{{ $order->address }}</p>
                  </div>
                  <div>
                     <p class="text-gray-500">City</p>
                     <p class="font-semibold">{{ $order->city }}</p>
                  </div>
                  <div>
                     <p class="text-gray-500">Phone</p>
                     <p class="font-semibold">{{ $order->phone }}</p>
                  </div>
                  @if ($order->notes)
                     <div>
                        <p class="text-gray-500">Notes</p>
                        <p class="font-semibold">{{ $order->notes }}</p>
                     </div>
                  @endif
               </div>
            </div>

            <a href="{{ route('orders') }}"
               class="mt-4 block rounded bg-gray-600 px-4 py-2 text-center text-white hover:bg-gray-700">
               Back to Orders
            </a>
         </div>

      </div>
   </div>
</x-app-layout>
