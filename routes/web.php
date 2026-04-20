<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware(['auth'])->name('dashboard');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Review Routes
    Route::post('/products/{product}/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');

    // Shop Routes
    Route::get('/shop/create', [App\Http\Controllers\ShopController::class, 'create'])->name('shop.create');
    Route::post('/shop', [App\Http\Controllers\ShopController::class, 'store'])->name('shop.store');

    // Seller Routes
    Route::prefix('seller')->name('seller.')->group(function () {
        Route::resource('products', App\Http\Controllers\Seller\ProductController::class);
    });

    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Order Routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

    // Admin Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
            Route::get('/incoming', [App\Http\Controllers\Admin\OrderController::class, 'incoming'])->name('incoming');
            Route::get('/confirmed', [App\Http\Controllers\Admin\OrderController::class, 'confirmed'])->name('confirmed');
            Route::get('/completed', [App\Http\Controllers\Admin\OrderController::class, 'completed'])->name('completed');
            Route::patch('/{order}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('updateStatus');
        });

        Route::prefix('products')->name('products.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\ProductController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\ProductController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\ProductController::class, 'store'])->name('store');
            Route::get('/{product}/edit', [App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('edit');
            Route::patch('/{product}', [App\Http\Controllers\Admin\ProductController::class, 'update'])->name('update');
            Route::get('/export', [App\Http\Controllers\Admin\ProductController::class, 'export'])->name('export');
        });

        Route::prefix('reviews')->name('reviews.')->group(function () {
            Route::get('/incoming', [App\Http\Controllers\Admin\ReviewController::class, 'incoming'])->name('incoming');
            Route::get('/', [App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('index');
            Route::patch('/{review}/approve', [App\Http\Controllers\Admin\ReviewController::class, 'approve'])->name('approve');
            Route::delete('/{review}', [App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('stock')->name('stock.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\StockController::class, 'index'])->name('index');
        });
    });
});

Route::get('/shops/{shop:slug}', [App\Http\Controllers\ShopController::class, 'show'])->name('shops.show');

require __DIR__ . '/auth.php';
