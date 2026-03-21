<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ✅ Everything requires login
Route::middleware(['auth'])->group(function(){

    // Your pages
    Route::controller(MainController::class)->group(function(){
        Route::get('/', 'welcome')->name('welcome');
        Route::get('/shop', 'shop')->name('shop');
        Route::get('/products/{slug}','product')->name('product');
    });

    // Dashboard
    Route::get('/dashboard', function(){
        return view('dashboard');
    })->name('dashboard');

    // Profile
    Route::controller(ProfileController::class)->group(function(){
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

});

require __DIR__.'/auth.php'; // ← keeps login/register routes public