
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProductController,
    CartController,
    OrderController,
    AuthController
};

// Public routes
Route::get('/', [ProductController::class, 'index']);

// Guest cart API routes
Route::prefix('api')->group(function () {
    Route::get('/products', [ProductController::class, 'list']);
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'add']);
    Route::put('/cart/update-quantity/{productId}', [CartController::class, 'updateQuantity']);
    Route::delete('/cart/remove/{productId}', [CartController::class, 'remove']);
});

// Auth routes
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister']);
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [OrderController::class, 'showCheckout'])->name('checkout');
    Route::post('/checkout', [OrderController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/order/confirmation/{order}', [OrderController::class, 'showConfirmation'])->name('order.confirmation');
    Route::get('/api/orders', [OrderController::class, 'index']);
});

