<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // show cart page
    public function index(){
        $cartItems = Cart::where('user_id',Auth::id())
        ->with('product')
        ->get();

        $total=$cartItems->sum(function($item){
            return $item->product->price*$item->quantity;
        });

        return view('cart',compact('cartItems','total'));

    }

    //Add to cart
    public function add(Request $request){
        $exisitingCartItem = Cart::where('user_id',Auth::id())
        ->where('product_id',$request->product_id)
        ->first();

        //Product already in cart increase quantity
        if($exisitingCartItem){
            $exisitingCartItem->increment('quantity');
        }else{
            //Product not in cart add it
            Cart::create([
                'user_id'=>Auth::id(),
                'product_id'=>$request->product_id,
                'quantity'=>1
            ]);
        }
        return redirect()->back()->with('success','Product added to cart');

    }


    //Remove from cart
    public function remove($id){
        Cart::where('id',$id)
        ->where('user_id',Auth::id())
        ->delete();

        return redirect()->back()->with('success','Product removed from cart');
    }

    //update quantity
    public function update(Request $request,$id){
        Cart::where('id',$id)
        ->where('user_id',Auth::id())
        ->update(['quantity'=>$request->quantity]);
        return redirect()->back()->with('success','Cart updated successfully');
    }

    //Clear the entire cart
    public function clear(){
        Cart::where('user_id',Auth::id())
        ->delete();
        return redirect()->back()->with('success','cart cleared successfully');
    }
    
}
