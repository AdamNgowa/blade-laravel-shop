<x-app-layout>
   <x-slot name="header">
      <h2 class="text-xl font-semibold text-gray-800">My Orders</h2>
   </x-slot>

   <div class="mx-auto max-w-7xl px-4 py-8">

      @if (session('success'))
         <div class="mb-6 rounded bg-green-100 p-4 text-green-800">
            {{ session('success') }}
         </div>
      @endif

      @if ($orders->isEmpty())
         <div class="py-16 text-center">
            <p class="text-lg text-gray-500">You have no orders yet</p>
            <a href="{{ route('shop') }}"
               class="mt-4 inline-block rounded bg-blue-600 px-6 py-2 text-white hover:bg-blue-700">
               Go Shopping
            </a>
         </div>
      @else
         <div class="space-y-4">
            @foreach ($orders as $order)
               <div class="rounded bg-white p-6 shadow">
                  <div class="flex items-center justify-between">
                     <div>
                        <p class="font-bold">Order #{{ $order->id }}</p>
                        <p class="text-sm text-gray-500">
                           {{ $order->created_at->format('M d, Y') }}
                        </p>
                     </div>

                     <div class="text-right">
                        <p class="font-bold text-green-600">
                           ${{ number_format($order->total, 2) }}
                        </p>
                        {{-- Status Badge --}}
                        <span
                           class="{{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }} {{ $order->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }} {{ $order->status === 'shipped' ? 'bg-purple-100 text-purple-800' : '' }} {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : '' }} {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }} rounded-full px-3 py-1 text-xs font-semibold">
                           {{ ucfirst($order->status) }}
                        </span>
                     </div>

                     <a href="{{ route('order.show', $order->id) }}"
                        class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                        View Details
                     </a>
                  </div>
               </div>
            @endforeach
         </div>
      @endif

   </div>
</x-app-layout>
