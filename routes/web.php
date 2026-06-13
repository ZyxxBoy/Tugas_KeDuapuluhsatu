<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductCategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produk', [HomeController::class, 'produk'])->name('produk');
Route::get('/cart', [CartController::class, 'index'])->name('cart');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified', 'admin'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::prefix('dashboard')->middleware('admin')->group(function () {

        Route::resource('/produk', ProductController::class)->names('dashboard.produk');
        Route::resource('/kategori', ProductCategoryController::class)->names('dashboard.kategori');
        Route::resource('/order', OrderController::class)->names('dashboard.order');
    });
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
