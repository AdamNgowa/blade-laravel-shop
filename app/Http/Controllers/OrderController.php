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
    $request->validate([
        'address'        => 'required|string|max:255',
        'city'           => 'required|string|max:255',
        'phone'          => 'required|string|max:20',
        'notes'          => 'nullable|string',
        'payment_method' => 'required|in:stk_push,till,paybill',
    ]);

    $cartItems = Cart::where('user_id', Auth::id())
        ->with('product')
        ->get();

    if ($cartItems->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'Your cart is empty!'
        ]);
    }

    $total = $cartItems->sum(function ($item) {
        return $item->product->price * $item->quantity;
    });

    // Assign transaction result directly to $order
    $order = DB::transaction(function () use ($request, $cartItems, $total) {

        $order = Order::create([
            'user_id'        => Auth::id(),
            'total'          => $total,
            'status'         => 'pending',
            'address'        => $request->address,
            'city'           => $request->city,
            'phone'          => $request->phone,
            'notes'          => $request->notes,
            'payment_method' => $request->payment_method,
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item->product_id,
                'quantity'   => $item->quantity,
                'price'      => $item->product->price,
            ]);
        }

        Cart::where('user_id', Auth::id())->delete();

        return $order; // this is key
    });

    // Till or paybill
    if ($request->payment_method !== 'stk_push') {
        return redirect()->route('orders')
            ->with('success', 'Order placed! Please complete your M-Pesa payment.');
    }

    // STK push - now $order is not null 
    return response()->json([
        'success'  => true,
        'order_id' => $order->id,
        'message'  => 'Order created successfully'
    ]);
}

    // Show all orders
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('items.product')
            ->latest()
            ->get();

        return view('orders', compact('orders'));
    }

    // Show single order
    public function show($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->with('items.product')
            ->firstOrFail();

        return view('order-detail', compact('order'));
    }
}