<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\MpesaController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Everything requires login
Route::middleware(['auth'])->group(function () {

    // Main routes
    Route::controller(MainController::class)->group(function () {
        Route::get('/', 'welcome')->name('welcome');
        Route::get('/shop', 'shop')->name('shop');
        Route::get('/products/{slug}', 'product')->name('product');
    });

    // Cart routes
    Route::controller(CartController::class)->group(function () {
        Route::get('/cart', 'index')->name('cart');
        Route::post('/cart/add', 'add')->name('cart.add');
        Route::delete('/cart/remove/{id}', 'remove')->name('cart.remove');
        Route::patch('/cart/update/{id}', 'update')->name('cart.update');
        Route::delete('/cart/clear', 'clear')->name('cart.clear');
    });

    // Order routes
    Route::controller(OrderController::class)->group(function () {
        Route::get('/checkout', 'checkout')->name('checkout');
        Route::post('/checkout', 'placeOrder')->name('order.place');
        Route::get('/orders', 'index')->name('orders');
        Route::get('/orders/{id}', 'show')->name('order.show');
    });

    // M-Pesa STK Push - requires auth
    Route::post('/mpesa/stk-push', [MpesaController::class, 'stkPush'])
        ->name('mpesa.stk');

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });
});

// M-Pesa Callback - must be public (Safaricom calls this)
Route::post('/mpesa/callback', [MpesaController::class, 'callback'])
    ->name('mpesa.callback');

require __DIR__ . '/auth.php';