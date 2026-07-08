<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionItemController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('', function () {
        return view('auth.login');
    })->name('login');
});

Route::view('/', 'landing-page');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/edit-profile', [ProfileController::class, 'edit'])->name('edit-profile');
    Route::post('/edit-profile', [ProfileController::class, 'update']);
    Route::get('/edit-profile/verify-email', [ProfileController::class, 'verifyEmailChange'])->name('profile.verify-email-change');

    Route::view('/change-password', 'change-password')->name('change-password');

    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::get('checkout/products', [CheckoutController::class, 'search'])->name('checkout.products');
    Route::post('checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('checkout/{transaction}/receipt', [CheckoutController::class, 'receipt'])->name('checkout.receipt');

    Route::get('categories/data/list', [CategoryController::class, 'list'])->name('categories.list');
    Route::resource('categories', CategoryController::class);

    Route::get('products/data/list', [ProductController::class, 'list'])->name('products.list');
    Route::resource('products', ProductController::class);

    Route::get('customers/data/list', [CustomerController::class, 'list'])->name('customers.list');
    Route::resource('customers', CustomerController::class);

    Route::get('stock-movements/data/list', [StockMovementController::class, 'list'])->name('stock-movements.list');
    Route::resource('stock-movements', StockMovementController::class);

    Route::get('transactions/data/list', [TransactionController::class, 'list'])->name('transactions.list');
    Route::resource('transactions', TransactionController::class);

    Route::get('transaction-items/data/list', [TransactionItemController::class, 'list'])->name('transaction-items.list');
    Route::resource('transaction-items', TransactionItemController::class);

});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
