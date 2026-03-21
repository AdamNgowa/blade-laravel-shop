<?php

namespace App\Http\Controllers;

use App\Models\Product;


class MainController extends Controller
{
    public function welcome(){
        $products = Product::with('category')->get();
        return view('welcome',compact('products'));
    }
    public function shop(){
        $products = Product::with('category')->get();
        return view('shop',compact('products'));
    }
}
