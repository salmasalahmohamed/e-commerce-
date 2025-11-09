<?php

use App\Livewire\ProductsPage;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;

Route::get('/', \App\Livewire\HomePage::class);
Route::get('/categories', \App\Livewire\CategoriesPage::class);
Route::get('/products', \App\Livewire\ProductsPage::class);

Route::get('/cart', \App\Livewire\CartPage::class);
Route::get('/product/{slug}', \App\Livewire\ProductDetailPage::class)->name('product.details');


Route::middleware('guest')->group(function (){

    Route::get('/login', \App\Livewire\Auth\LoginPage::class)->name('login');
    Route::get('/register', \App\Livewire\Auth\RegisterPage::class);
    Route::get('/forgot', \App\Livewire\Auth\ForgotPage::class)->name('password.request');
    Route::get('/reset/{token}', \App\Livewire\Auth\RegisterPage::class)->name('password.reset');

});

Route::middleware('auth')->group(function (){
    Route::get('/logout',function (){
       \Illuminate\Support\Facades\Auth::logout();
        return redirect('/');
    });
    Route::get('/checkout', \App\Livewire\CheckoutPage::class);
    Route::get('/my-orders/{order:id}', \App\Livewire\OrderDetailPage::class)->name('my-orders.show');
    Route::get('/my-orders', \App\Livewire\MyOrdersPage::class);
    Route::get('/success', \App\Livewire\SuccessPage::class)->name('success');
    Route::get('/cancel', \App\Livewire\CancelPage::class)->name('cancel');


});
