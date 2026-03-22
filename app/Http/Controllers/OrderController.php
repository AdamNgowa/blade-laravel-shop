<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // Show checkout page
    public function checkout()
    {
        $cartItems = Cart::where('user_id', Auth::id())
            ->with('product')
            ->get();

        // Redirect to shop if cart is empty
        if ($cartItems->isEmpty()) {
            return redirect()->route('shop')
                ->with('error', 'Your cart is empty!');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('checkout', compact('cartItems', 'total'));
    }

    // Place the order
    public function placeOrder(Request $request)
    {
        // Validate form
        $request->validate([
            'address' => 'required|string|max:255',
            'city'    => 'required|string|max:255',
            'phone'   => 'required|string|max:20',
            'notes'   => 'nullable|string',
        ]);

        // Get cart items
        $cartItems = Cart::where('user_id', Auth::id())
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('shop')
                ->with('error', 'Your cart is empty!');
        }

        // Calculate total
        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        // Use DB transaction - if anything fails nothing is saved
        DB::transaction(function () use ($request, $cartItems, $total) {

            // Create the order
            $order = Order::create([
                'user_id' => Auth::id(),
                'total'   => $total,
                'status'  => 'pending',
                'address' => $request->address,
                'city'    => $request->city,
                'phone'   => $request->phone,
                'notes'   => $request->notes,
            ]);

            // Create order items from cart
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'price'      => $item->product->price,
                ]);
            }

            // Clear the cart after order placed
            Cart::where('user_id', Auth::id())->delete();
        });

        return redirect()->route('orders')
            ->with('success', 'Order placed successfully!');
    }

    // Show all orders for logged in user
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
        //Here we load multiple relationships at once from Order model
        //Order → has many → OrderItems → belongs to → Product
            ->with('items.product')
            ->latest()
            ->get();

        return view('orders', compact('orders'));
    }

    // Show single order details
    public function show($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            //We load multiple relationships here as well
            //Here we load multiple relationships at once from Order model
            //Order → has many → OrderItems → belongs to → Product
            ->with('items.product')
            ->firstOrFail();

        return view('order-detail', compact('order'));
    }
}