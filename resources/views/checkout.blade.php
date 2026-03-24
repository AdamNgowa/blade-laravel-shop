<x-app-layout>
   <x-slot name="header">
      <h2 class="text-xl font-semibold text-gray-800">Checkout</h2>
   </x-slot>

   <div class="mx-auto max-w-7xl px-4 py-8">
      <div class="flex gap-8">

         {{-- Checkout Form --}}
         <div class="flex-1">
            <div class="mb-6 rounded bg-white p-6 shadow">
               <h3 class="mb-6 text-lg font-bold">Shipping Details</h3>

               <form method="POST" action="{{ route('order.place') }}" id="checkout-form">
                  @csrf

                  {{-- Address --}}
                  <div class="mb-4">
                     <label class="mb-1 block text-sm font-medium text-gray-700">Address</label>
                     <input type="text" name="address" value="{{ old('address') }}"
                        class="w-full rounded border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="123 Main Street">
                     @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                     @enderror
                  </div>

                  {{-- City --}}
                  <div class="mb-4">
                     <label class="mb-1 block text-sm font-medium text-gray-700">City</label>
                     <input type="text" name="city" value="{{ old('city') }}"
                        class="w-full rounded border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Nairobi">
                     @error('city')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                     @enderror
                  </div>

                  {{-- Phone --}}
                  <div class="mb-4">
                     <label class="mb-1 block text-sm font-medium text-gray-700">Phone</label>
                     <input type="text" name="phone" id="phone-input" value="{{ old('phone') }}"
                        class="w-full rounded border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="07XXXXXXXX">
                     @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                     @enderror
                  </div>

                  {{-- Notes --}}
                  <div class="mb-6">
                     <label class="mb-1 block text-sm font-medium text-gray-700">Notes (Optional)</label>
                     <textarea name="notes" rows="3"
                        class="w-full rounded border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Any delivery instructions...">{{ old('notes') }}</textarea>
                  </div>

                  {{-- Payment Method --}}
                  <div class="mb-6">
                     <label class="mb-2 block text-sm font-medium text-gray-700">Payment Method</label>
                     <div class="flex gap-4">

                        {{-- STK Push --}}
                        <label class="flex-1 cursor-pointer">
                           <input type="radio" name="payment_method" value="stk_push" class="peer hidden" checked>
                           <div
                              class="rounded border-2 border-gray-200 p-4 text-center peer-checked:border-green-500 peer-checked:bg-green-50">
                              <p class="text-sm font-semibold">STK Push</p>
                              <p class="mt-1 text-xs text-gray-500">Prompt on phone</p>
                           </div>
                        </label>

                        {{-- Till Number --}}
                        <label class="flex-1 cursor-pointer">
                           <input type="radio" name="payment_method" value="till" class="peer hidden">
                           <div
                              class="rounded border-2 border-gray-200 p-4 text-center peer-checked:border-green-500 peer-checked:bg-green-50">
                              <p class="text-sm font-semibold">Till Number</p>
                              <p class="mt-1 text-xs text-gray-500">Buy Goods</p>
                           </div>
                        </label>

                        {{-- Paybill --}}
                        <label class="flex-1 cursor-pointer">
                           <input type="radio" name="payment_method" value="paybill" class="peer hidden">
                           <div
                              class="rounded border-2 border-gray-200 p-4 text-center peer-checked:border-green-500 peer-checked:bg-green-50">
                              <p class="text-sm font-semibold">Paybill</p>
                              <p class="mt-1 text-xs text-gray-500">Pay Bill</p>
                           </div>
                        </label>

                     </div>
                  </div>

                  {{-- STK Push Button --}}
                  <div id="stk-section">
                     <button type="button" id="stk-btn" onclick="initiateStkPush()"
                        class="w-full rounded bg-green-600 py-3 font-semibold text-white hover:bg-green-700">
                        Pay ${{ number_format($total, 2) }} via M-Pesa
                     </button>
                     <div id="stk-status" class="mt-4 hidden rounded p-4 text-center"></div>
                  </div>

                  {{-- Till Section --}}
                  <div id="till-section" class="hidden">
                     <div class="mb-4 rounded border border-green-200 bg-green-50 p-4">
                        <p class="font-bold text-green-800">Till Number</p>
                        <p class="mt-1 text-3xl font-bold text-green-600">
                           {{ config('mpesa.till_number') }}
                        </p>
                        <div class="mt-3 space-y-1 text-sm text-gray-600">
                           <p>1. Open M-Pesa on your phone</p>
                           <p>2. Go to <strong>Lipa Na M-Pesa</strong></p>
                           <p>3. Select <strong>Buy Goods and Services</strong></p>
                           <p>4. Enter Till: <strong>{{ config('mpesa.till_number') }}</strong></p>
                           <p>5. Enter Amount: <strong>KES {{ number_format($total, 2) }}</strong></p>
                           <p>6. Enter PIN and confirm</p>
                        </div>
                     </div>
                     <button type="submit"
                        class="w-full rounded bg-green-600 py-3 font-semibold text-white hover:bg-green-700">
                        I Have Paid via Till Number
                     </button>
                  </div>

                  {{-- Paybill Section --}}
                  <div id="paybill-section" class="hidden">
                     <div class="mb-4 rounded border border-blue-200 bg-blue-50 p-4">
                        <p class="font-bold text-blue-800">Paybill Number</p>
                        <p class="mt-1 text-3xl font-bold text-blue-600">
                           {{ config('mpesa.shortcode') }}
                        </p>
                        <div class="mt-3 space-y-1 text-sm text-gray-600">
                           <p>1. Open M-Pesa on your phone</p>
                           <p>2. Go to <strong>Lipa Na M-Pesa</strong></p>
                           <p>3. Select <strong>Pay Bill</strong></p>
                           <p>4. Business No: <strong>{{ config('mpesa.shortcode') }}</strong></p>
                           <p>5. Account No: <strong>Order-{{ Auth::id() }}</strong></p>
                           <p>6. Amount: <strong>KES {{ number_format($total, 2) }}</strong></p>
                           <p>7. Enter PIN and confirm</p>
                        </div>
                     </div>
                     <button type="submit"
                        class="w-full rounded bg-blue-600 py-3 font-semibold text-white hover:bg-blue-700">
                        I Have Paid via Paybill
                     </button>
                  </div>

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

   <script>
      // Show/hide payment sections
      document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
         radio.addEventListener('change', function() {
            document.getElementById('stk-section').classList.add('hidden');
            document.getElementById('till-section').classList.add('hidden');
            document.getElementById('paybill-section').classList.add('hidden');

            if (this.value === 'stk_push') {
               document.getElementById('stk-section').classList.remove('hidden');
            } else if (this.value === 'till') {
               document.getElementById('till-section').classList.remove('hidden');
            } else if (this.value === 'paybill') {
               document.getElementById('paybill-section').classList.remove('hidden');
            }
         });
      });

      // STK Push
      function initiateStkPush() {
         const phone = document.getElementById('phone-input').value;
         const stkBtn = document.getElementById('stk-btn');
         const statusDiv = document.getElementById('stk-status');

         if (!phone) {
            alert('Please enter your phone number first');
            return;
         }

         // Show loading
         statusDiv.classList.remove('hidden');
         statusDiv.className = 'mt-4 rounded p-4 text-center bg-blue-50 text-blue-800';
         statusDiv.innerHTML = '⏳ Creating your order...';
         stkBtn.disabled = true;
         stkBtn.innerText = 'Processing...';

         // Step 1 - Create order first
         fetch('{{ route('order.place') }}', {
               method: 'POST',
               headers: {
                  'X-CSRF-TOKEN': '{{ csrf_token() }}'
               },
               body: new FormData(document.getElementById('checkout-form'))
            })
            .then(response => response.json())
            .then(data => {
               if (!data.success) {
                  throw new Error(data.message || 'Failed to create order');
               }

               statusDiv.innerHTML = '⏳ Sending payment prompt to your phone...';

               // Step 2 - Initiate STK Push with order id
               return fetch('{{ route('mpesa.stk') }}', {
                  method: 'POST',
                  headers: {
                     'Content-Type': 'application/json',
                     'X-CSRF-TOKEN': '{{ csrf_token() }}'
                  },
                  body: JSON.stringify({
                     phone: phone,
                     order_id: data.order_id
                  })
               });
            })
            .then(response => response.json())
            .then(data => {
               if (data.success) {
                  statusDiv.className = 'mt-4 rounded p-4 text-center bg-green-50 text-green-800';
                  statusDiv.innerHTML = `
                  ✅ ${data.message}<br>
                  <small class="text-gray-500">Enter your PIN on your phone to complete payment</small>
                  <br><br>
                  <a href="{{ route('orders') }}"
                     class="inline-block mt-2 rounded bg-green-600 px-6 py-2 text-white hover:bg-green-700">
                     View My Orders
                  </a>
               `;
               } else {
                  throw new Error(data.message || 'Failed to send prompt');
               }
            })
            .catch(error => {
               statusDiv.className = 'mt-4 rounded p-4 text-center bg-red-50 text-red-800';
               statusDiv.innerHTML = '❌ ' + error.message;
               stkBtn.disabled = false;
               stkBtn.innerText = 'Pay ${{ number_format($total, 2) }} via M-Pesa';
            });
      }
   </script>

</x-app-layout>
